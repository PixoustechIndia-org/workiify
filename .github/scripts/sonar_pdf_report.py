"""
Build a PDF report from a SonarQube (or SonarCloud) analysis.

Runs in GitHub Actions right after the SonarQube scan step. It reads results
from the SonarQube Web API and writes a PDF with:
  - Quality Gate status (PASSED / FAILED) and the conditions behind it
  - Main metrics (bugs, vulnerabilities, code smells, coverage, duplication...)
  - Issue counts by severity and by file
  - Full list of open issues (file, line, severity, type, message)
  - Security hotspots waiting for review

Environment variables:
  SONAR_HOST_URL     e.g. https://sonar.example.com   (required)
  SONAR_TOKEN        a SonarQube *user* token          (required)
  SONAR_PROJECT_KEY  defaults to sonar.projectKey in sonar-project.properties
  REPORT_FILE        defaults to sonar-report.pdf
"""

import base64
import json
import os
import sys
import time
import urllib.error
import urllib.parse
import urllib.request
from collections import Counter
from datetime import datetime, timedelta, timezone
from xml.sax.saxutils import escape

from reportlab.lib import colors
from reportlab.lib.enums import TA_CENTER
from reportlab.lib.pagesizes import A4, landscape
from reportlab.lib.styles import ParagraphStyle, getSampleStyleSheet
from reportlab.lib.units import mm
from reportlab.platypus import (KeepTogether, PageBreak, Paragraph,
                                SimpleDocTemplate, Spacer, Table, TableStyle)

HOST = os.environ.get("SONAR_HOST_URL", "").rstrip("/")
TOKEN = os.environ.get("SONAR_TOKEN", "")
REPORT_FILE = os.environ.get("REPORT_FILE", "sonar-report.pdf")
MAX_ISSUES = 2000  # keeps the PDF a sensible size

IST = timezone(timedelta(hours=5, minutes=30))


# --------------------------------------------------------------------------- #
# SonarQube API helpers
# --------------------------------------------------------------------------- #
def api(path, params=None, absolute_url=None):
    url = absolute_url or f"{HOST}{path}"
    if params:
        url += ("&" if "?" in url else "?") + urllib.parse.urlencode(params)
    req = urllib.request.Request(url)
    # token as username with empty password works on every SonarQube version
    auth = base64.b64encode(f"{TOKEN}:".encode()).decode()
    req.add_header("Authorization", f"Basic {auth}")
    try:
        with urllib.request.urlopen(req, timeout=60) as resp:
            return json.loads(resp.read().decode("utf-8"))
    except urllib.error.HTTPError as e:
        body = e.read().decode("utf-8", "replace")[:300]
        raise RuntimeError(f"SonarQube API {e.code} on {path}: {body}") from e


def project_key_from_properties():
    try:
        with open("sonar-project.properties", encoding="utf-8") as f:
            for line in f:
                line = line.strip()
                if line.startswith("sonar.projectKey="):
                    return line.split("=", 1)[1].strip()
    except FileNotFoundError:
        pass
    return None


def wait_for_background_task():
    """The scanner only uploads; SonarQube processes the report in the
    background. Wait for that to finish so the numbers are the new ones."""
    task_file = os.path.join(".scannerwork", "report-task.txt")
    if not os.path.exists(task_file):
        return
    props = {}
    with open(task_file, encoding="utf-8") as f:
        for line in f:
            if "=" in line:
                k, v = line.strip().split("=", 1)
                props[k] = v
    ce_url = props.get("ceTaskUrl")
    if not ce_url:
        return
    for _ in range(60):  # up to ~5 minutes
        status = api(None, absolute_url=ce_url).get("task", {}).get("status")
        if status in ("SUCCESS", "FAILED", "CANCELED"):
            print(f"Background task finished: {status}")
            return
        time.sleep(5)
    print("Warning: background task still running, report may show old data")


def fetch_all(path, params, list_key, limit):
    items, page = [], 1
    while len(items) < limit:
        data = api(path, {**params, "p": page, "ps": 500})
        batch = data.get(list_key, [])
        items.extend(batch)
        total = data.get("paging", {}).get("total", data.get("total", 0))
        if not batch or len(items) >= total:
            break
        page += 1
    return items[:limit], data.get("paging", {}).get("total", len(items))


# --------------------------------------------------------------------------- #
# Collect data
# --------------------------------------------------------------------------- #
METRICS = [
    ("alert_status", "Quality Gate"),
    ("bugs", "Bugs"),
    ("vulnerabilities", "Vulnerabilities"),
    ("security_hotspots", "Security Hotspots"),
    ("code_smells", "Code Smells"),
    ("coverage", "Coverage (%)"),
    ("duplicated_lines_density", "Duplications (%)"),
    ("ncloc", "Lines of Code"),
    ("reliability_rating", "Reliability Rating"),
    ("security_rating", "Security Rating"),
    ("sqale_rating", "Maintainability Rating"),
    ("sqale_index", "Technical Debt"),
]
RATING = {"1.0": "A", "2.0": "B", "3.0": "C", "4.0": "D", "5.0": "E"}
METRIC_NAMES = {
    "new_reliability_rating": "Reliability Rating (new code)",
    "new_security_rating": "Security Rating (new code)",
    "new_maintainability_rating": "Maintainability Rating (new code)",
    "new_coverage": "Coverage on new code (%)",
    "new_duplicated_lines_density": "Duplications on new code (%)",
    "new_security_hotspots_reviewed": "Hotspots reviewed (new code, %)",
    "new_violations": "New issues",
    "reliability_rating": "Reliability Rating",
    "security_rating": "Security Rating",
    "sqale_rating": "Maintainability Rating",
    "coverage": "Coverage (%)",
    "duplicated_lines_density": "Duplications (%)",
    "security_hotspots_reviewed": "Hotspots reviewed (%)",
}


def fmt_metric(key, value):
    if value is None or value == "":
        return "-"
    if key.endswith("_rating"):
        return RATING.get(str(float(value)), value)
    if key == "sqale_index":
        minutes = int(float(value))
        days, rem = divmod(minutes, 480)  # SonarQube uses 8h days
        hours, mins = divmod(rem, 60)
        parts = [f"{days}d" if days else "", f"{hours}h" if hours else "",
                 f"{mins}min" if mins else ""]
        return " ".join(p for p in parts if p) or "0"
    if key == "alert_status":
        return {"OK": "PASSED", "ERROR": "FAILED"}.get(value, value)
    return value


def collect(project_key):
    comp = api("/api/measures/component", {
        "component": project_key,
        "metricKeys": ",".join(k for k, _ in METRICS),
    })["component"]
    measures = {m["metric"]: m.get("value", m.get("period", {}).get("value"))
                for m in comp.get("measures", [])}

    gate = api("/api/qualitygates/project_status",
               {"projectKey": project_key})["projectStatus"]

    issues, issue_total = fetch_all(
        "/api/issues/search",
        {"componentKeys": project_key, "resolved": "false",
         "s": "SEVERITY", "asc": "false"},
        "issues", MAX_ISSUES)

    try:
        hotspots, _ = fetch_all(
            "/api/hotspots/search",
            {"projectKey": project_key, "status": "TO_REVIEW"},
            "hotspots", MAX_ISSUES)
    except RuntimeError as e:  # older servers / missing permission
        print(f"Skipping hotspots: {e}")
        hotspots = []

    return comp, measures, gate, issues, issue_total, hotspots


# --------------------------------------------------------------------------- #
# PDF
# --------------------------------------------------------------------------- #
GREEN = colors.HexColor("#1e7b34")
RED = colors.HexColor("#c0392b")
GREY = colors.HexColor("#f2f2f2")
DARK = colors.HexColor("#2c3e50")
SEV_ORDER = ["BLOCKER", "CRITICAL", "MAJOR", "MINOR", "INFO"]
SEV_COLOR = {"BLOCKER": "#8e0000", "CRITICAL": "#c0392b", "MAJOR": "#d35400",
             "MINOR": "#b7950b", "INFO": "#2471a3"}

styles = getSampleStyleSheet()
H1 = ParagraphStyle("H1", parent=styles["Title"], fontSize=20, spaceAfter=4)
H2 = ParagraphStyle("H2", parent=styles["Heading2"], textColor=DARK,
                    spaceBefore=10, spaceAfter=6)
BODY = ParagraphStyle("B", parent=styles["BodyText"], fontSize=9, leading=11)
SMALL = ParagraphStyle("S", parent=BODY, fontSize=7.5, leading=9)
CENTER = ParagraphStyle("C", parent=BODY, alignment=TA_CENTER)


def p(text, style=BODY):
    return Paragraph(escape(str(text)), style)


def table(rows, widths, header=True, zebra=True):
    t = Table(rows, colWidths=widths, repeatRows=1 if header else 0)
    st = [
        ("GRID", (0, 0), (-1, -1), 0.4, colors.HexColor("#cccccc")),
        ("VALIGN", (0, 0), (-1, -1), "TOP"),
        ("FONTSIZE", (0, 0), (-1, -1), 8),
        ("TOPPADDING", (0, 0), (-1, -1), 3),
        ("BOTTOMPADDING", (0, 0), (-1, -1), 3),
    ]
    if header:
        st += [("BACKGROUND", (0, 0), (-1, 0), DARK),
               ("TEXTCOLOR", (0, 0), (-1, 0), colors.white),
               ("FONTNAME", (0, 0), (-1, 0), "Helvetica-Bold")]
    if zebra:
        for i in range(1 if header else 0, len(rows)):
            if i % 2 == 0:
                st.append(("BACKGROUND", (0, i), (-1, i), GREY))
    t.setStyle(TableStyle(st))
    return t


def short_path(component, project_key):
    return component.split(":", 1)[1] if component.startswith(project_key + ":") else component


def build_pdf(project_key, comp, measures, gate, issues, issue_total, hotspots):
    page = landscape(A4)
    doc = SimpleDocTemplate(REPORT_FILE, pagesize=page,
                            leftMargin=12 * mm, rightMargin=12 * mm,
                            topMargin=12 * mm, bottomMargin=14 * mm,
                            title=f"SonarQube Report - {comp.get('name', project_key)}")
    W = page[0] - 24 * mm
    now = datetime.now(IST).strftime("%d %b %Y, %I:%M %p IST")
    commit = os.environ.get("GITHUB_SHA", "")[:7]
    branch = os.environ.get("GITHUB_REF_NAME", "")
    run_url = ""
    if os.environ.get("GITHUB_RUN_ID"):
        run_url = (f"{os.environ.get('GITHUB_SERVER_URL', 'https://github.com')}/"
                   f"{os.environ.get('GITHUB_REPOSITORY', '')}/actions/runs/"
                   f"{os.environ['GITHUB_RUN_ID']}")

    story = [p("SonarQube Code Quality Report", H1),
             p(comp.get("name", project_key), CENTER),
             Spacer(1, 6)]

    info = [["Project key", project_key], ["Generated", now],
            ["Branch", branch or "-"], ["Commit", commit or "-"],
            ["SonarQube", HOST]]
    if run_url:
        info.append(["GitHub run", run_url])
    story.append(table([[p(a), p(b)] for a, b in info], [40 * mm, W - 40 * mm],
                       header=False))
    story.append(Spacer(1, 8))

    # Quality gate banner
    passed = gate.get("status") == "OK"
    banner = Table([[Paragraph(
        f"<font color='white' size='16'><b>QUALITY GATE: "
        f"{'PASSED' if passed else 'FAILED' if gate.get('status') == 'ERROR' else escape(gate.get('status', 'NONE'))}"
        f"</b></font>", CENTER)]], colWidths=[W], rowHeights=[14 * mm])
    banner.setStyle(TableStyle([
        ("BACKGROUND", (0, 0), (-1, -1), GREEN if passed else RED),
        ("VALIGN", (0, 0), (-1, -1), "MIDDLE")]))
    story.append(banner)

    conds = gate.get("conditions", [])
    if conds:
        story.append(p("Quality Gate conditions", H2))
        rows = [["Condition", "Actual", "Required", "Result"]]
        for c in conds:
            ok = c.get("status") == "OK"
            mk = c.get("metricKey", "")
            op = {"GT": "≤", "LT": "≥"}.get(c.get("comparator"), "")
            req = c.get("errorThreshold", "")
            if mk.endswith("_rating"):
                req = RATING.get(str(float(req)), req) if req else req
            rows.append([
                p(METRIC_NAMES.get(mk, mk)),
                p(fmt_metric(mk, c.get("actualValue"))),
                p(f"{op} {req}".replace("≤", "at most").replace("≥", "at least")),
                Paragraph(f"<font color='{'#1e7b34' if ok else '#c0392b'}'><b>"
                          f"{'OK' if ok else 'FAILED'}</b></font>", BODY)])
        story.append(table(rows, [W * 0.45, W * 0.18, W * 0.22, W * 0.15]))

    # Metrics
    story.append(p("Overall metrics", H2))
    mrows = [["Metric", "Value"]] + [
        [p(label), p(fmt_metric(k, measures.get(k)))]
        for k, label in METRICS if k != "alert_status"]
    story.append(table(mrows, [W * 0.5, W * 0.5]))

    # Issue summary
    sev = Counter(i.get("severity", "UNKNOWN") for i in issues)
    typ = Counter(i.get("type", "UNKNOWN") for i in issues)
    files = Counter(short_path(i.get("component", ""), project_key) for i in issues)

    summary = [p(f"Open issues: {issue_total}"
                 + (f" (first {len(issues)} listed)" if issue_total > len(issues) else ""), H2)]
    srows = [["Severity", "Count"]] + [[s, str(sev[s])] for s in SEV_ORDER if sev.get(s)]
    trows = [["Type", "Count"]] + [[t.replace("_", " ").title(), str(n)] for t, n in typ.most_common()]
    side = Table([[table(srows, [35 * mm, 20 * mm]) if len(srows) > 1 else p("No issues"),
                   table(trows, [40 * mm, 20 * mm]) if len(trows) > 1 else ""]],
                 colWidths=[70 * mm, 75 * mm])
    side.setStyle(TableStyle([("VALIGN", (0, 0), (-1, -1), "TOP"),
                              ("LEFTPADDING", (0, 0), (-1, -1), 0)]))
    summary.append(side)
    story.append(KeepTogether(summary))

    if files:
        story.append(p("Files with most issues", H2))
        frows = [["File", "Issues"]] + [[p(f, SMALL), str(n)] for f, n in files.most_common(15)]
        story.append(table(frows, [W - 25 * mm, 25 * mm]))

    # Issue list
    if issues:
        story.append(PageBreak())
        story.append(p("Open issues (most severe first)", H2))
        rows = [["#", "Severity", "Type", "File : Line", "Message", "Rule"]]
        for n, i in enumerate(issues, 1):
            s = i.get("severity", "")
            rows.append([
                str(n),
                Paragraph(f"<font color='{SEV_COLOR.get(s, '#000000')}'><b>{escape(s)}</b></font>", SMALL),
                p(i.get("type", "").replace("_", " ").title(), SMALL),
                p(f"{short_path(i.get('component', ''), project_key)} : {i.get('line', '-')}", SMALL),
                p(i.get("message", ""), SMALL),
                p(i.get("rule", ""), SMALL)])
        story.append(table(rows, [10 * mm, 20 * mm, 22 * mm, W * 0.27,
                                  W - 52 * mm - W * 0.27 - 32 * mm, 32 * mm]))

    # Hotspots
    if hotspots:
        story.append(PageBreak())
        story.append(p(f"Security hotspots to review: {len(hotspots)}", H2))
        rows = [["#", "Risk", "Category", "File : Line", "Message"]]
        for n, h in enumerate(hotspots, 1):
            rows.append([
                str(n), p(h.get("vulnerabilityProbability", ""), SMALL),
                p(h.get("securityCategory", "").replace("-", " ").title(), SMALL),
                p(f"{short_path(h.get('component', ''), project_key)} : {h.get('line', '-')}", SMALL),
                p(h.get("message", ""), SMALL)])
        story.append(table(rows, [10 * mm, 18 * mm, 40 * mm, W * 0.3,
                                  W - 68 * mm - W * 0.3]))

    def footer(canvas, _doc):
        canvas.saveState()
        canvas.setFont("Helvetica", 7.5)
        canvas.setFillColor(colors.grey)
        canvas.drawString(12 * mm, 7 * mm, f"{comp.get('name', project_key)} - SonarQube report - {now}")
        canvas.drawRightString(page[0] - 12 * mm, 7 * mm, f"Page {_doc.page}")
        canvas.restoreState()

    doc.build(story, onFirstPage=footer, onLaterPages=footer)


def write_job_summary(project_key, measures, gate, issue_total):
    path = os.environ.get("GITHUB_STEP_SUMMARY")
    if not path:
        return
    status = {"OK": "✅ PASSED", "ERROR": "❌ FAILED"}.get(gate.get("status"), gate.get("status"))
    lines = [f"## SonarQube: {project_key}", "", f"**Quality Gate:** {status}", "",
             "| Metric | Value |", "|---|---|"]
    for k, label in METRICS:
        if k != "alert_status":
            lines.append(f"| {label} | {fmt_metric(k, measures.get(k))} |")
    lines += ["", f"Open issues: **{issue_total}**", "",
              "📄 Full PDF report: download **sonarqube-report** from the Artifacts section below."]
    with open(path, "a", encoding="utf-8") as f:
        f.write("\n".join(lines) + "\n")


def main():
    if not HOST or not TOKEN:
        sys.exit("SONAR_HOST_URL and SONAR_TOKEN must be set (GitHub repo secrets).")
    project_key = os.environ.get("SONAR_PROJECT_KEY") or project_key_from_properties()
    if not project_key:
        sys.exit("Project key not found: set SONAR_PROJECT_KEY or sonar.projectKey.")

    wait_for_background_task()
    comp, measures, gate, issues, issue_total, hotspots = collect(project_key)
    build_pdf(project_key, comp, measures, gate, issues, issue_total, hotspots)
    write_job_summary(project_key, measures, gate, issue_total)
    print(f"PDF written: {REPORT_FILE}  (gate={gate.get('status')}, issues={issue_total})")


if __name__ == "__main__":
    main()
