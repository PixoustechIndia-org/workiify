<?php require APPROOT . '/views/admin/inc/header.php'; ?>

<div class="admin-wrap">
    <div class="admin-page-header">
        <h1><?php echo htmlspecialchars($data['sectionLabel']); ?></h1>
        <button type="button" id="livePreviewBtn" class="admin-btn admin-btn-secondary admin-btn-sm">
            <i class="fas fa-desktop"></i> Live Preview
        </button>
    </div>

    <?php if (!empty($data['message'])): ?>
        <div class="admin-message"><?php echo htmlspecialchars($data['message']); ?></div>
    <?php endif; ?>
    <?php if (!empty($data['flash'])): ?>
        <div class="admin-<?php echo $data['flash']['type'] === 'error' ? 'error' : 'message'; ?>"><?php echo htmlspecialchars($data['flash']['message']); ?></div>
    <?php endif; ?>

    <?php if ($data['fieldsData']): ?>
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-subtitle">Section Text &amp; Images</h2>
            </div>
            <form method="POST" enctype="multipart/form-data" class="admin-form" id="sectionForm">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrfToken']); ?>">

                <?php foreach ($data['fieldsData']['schema']['fields'] as $f):
                    $val = $data['fieldsData']['values'][$f['key']] ?? '';
                ?>
                    <label for="<?php echo $f['key']; ?>">
                        <?php echo htmlspecialchars($f['label']); ?>
                        <?php if (!empty($f['maxlength'])): ?><span class="admin-field-limit">max <?php echo $f['maxlength']; ?> characters</span><?php endif; ?>
                    </label>
                    <?php if ($f['type'] === 'textarea'): ?>
                        <textarea id="<?php echo $f['key']; ?>" name="<?php echo $f['key']; ?>"<?php echo !empty($f['maxlength']) ? ' maxlength="' . $f['maxlength'] . '"' : ''; ?>><?php echo htmlspecialchars($val); ?></textarea>
                    <?php elseif ($f['type'] === 'image'): ?>
                        <?php require APPROOT . '/views/admin/inc/image_field.php'; ?>
                    <?php elseif ($f['type'] === 'icon'): ?>
                        <?php require APPROOT . '/views/admin/inc/icon_field.php'; ?>
                    <?php else: ?>
                        <input type="text" id="<?php echo $f['key']; ?>" name="<?php echo $f['key']; ?>" value="<?php echo htmlspecialchars($val); ?>"<?php echo !empty($f['maxlength']) ? ' maxlength="' . $f['maxlength'] . '"' : ''; ?>>
                    <?php endif; ?>
                <?php endforeach; ?>

                <div class="admin-actions">
                    <button type="submit" class="admin-btn"><i class="fas fa-check"></i> Save Changes</button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <?php if ($data['itemsData']):
        $schema = $data['itemsData']['schema'];
        $count = count($data['itemsData']['items']);
        $min = $schema['minItems'] ?? 0;
        $max = $schema['maxItems'] ?? null;
        $atMax = $max !== null && $count >= $max;
        $atMin = $count <= $min;
        $locked = $max !== null && $min === $max;
    ?>
        <div class="admin-card">
            <div class="admin-card-header" style="margin-bottom:10px;">
                <h2 class="admin-card-subtitle"><?php echo htmlspecialchars($schema['label']); ?></h2>
                <?php if ($atMax): ?>
                    <span class="admin-btn admin-btn-sm admin-btn-secondary is-disabled" title="Maximum reached"><i class="fas fa-plus"></i> Add New</span>
                <?php else: ?>
                    <a href="<?php echo URLROOT; ?>/admin/item-edit/<?php echo $data['page']; ?>/<?php echo $data['itemsData']['sectionKey']; ?>/new" class="admin-btn admin-btn-sm"><i class="fas fa-plus"></i> Add New</a>
                <?php endif; ?>
            </div>

            <p class="admin-limit-note">
                <?php if ($locked): ?>
                    <i class="fas fa-lock"></i> This section is built for exactly <?php echo $max; ?> entries so the layout stays aligned — entries can be edited but not added or removed.
                <?php else: ?>
                    <?php echo $count; ?><?php echo $max !== null ? ' / ' . $max : ''; ?> entries.
                    <?php if ($atMax): ?> Maximum reached — remove one to add another.<?php endif; ?>
                    <?php if ($min > 0): ?> (at least <?php echo $min; ?> required)<?php endif; ?>
                <?php endif; ?>
            </p>

            <?php
                $fieldKeys = array_column($schema['fields'], 'key');
                $imageKey = null;
                foreach ($schema['fields'] as $f) { if ($f['type'] === 'image') { $imageKey = $f['key']; break; } }
                $titleKey = $fieldKeys[0];
                if ($titleKey === $imageKey && isset($fieldKeys[1])) { $titleKey = $fieldKeys[1]; }
            ?>

            <?php if (empty($data['itemsData']['items'])): ?>
                <p class="admin-empty">No entries yet. Click "+ Add New" to create one.</p>
            <?php else: ?>
                <?php foreach ($data['itemsData']['items'] as $item):
                    $d = $item['data'];
                    $title = $d[$titleKey] ?? ('Item #' . $item['id']);
                    $sub = '';
                    foreach ($fieldKeys as $fk) {
                        if ($fk !== $titleKey && $fk !== $imageKey && !empty($d[$fk])) { $sub = $d[$fk]; break; }
                    }
                ?>
                    <div class="admin-item-row">
                        <?php if ($imageKey && !empty($d[$imageKey])): ?>
                            <img src="<?php echo URLROOT . htmlspecialchars($d[$imageKey]); ?>" class="admin-item-thumb" alt="">
                        <?php endif; ?>
                        <div class="admin-item-info">
                            <strong><?php echo htmlspecialchars($title); ?></strong>
                            <?php if ($sub): ?><span><?php echo htmlspecialchars($sub); ?></span><?php endif; ?>
                        </div>
                        <div class="admin-item-row-actions">
                            <a href="<?php echo URLROOT; ?>/admin/item-edit/<?php echo $data['page']; ?>/<?php echo $data['itemsData']['sectionKey']; ?>/<?php echo $item['id']; ?>" class="admin-btn admin-btn-sm admin-btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                            <?php if ($atMin): ?>
                                <span class="admin-btn admin-btn-sm admin-btn-danger-ghost is-disabled" title="At least <?php echo $min; ?> required"><i class="fas fa-trash"></i> Delete</span>
                            <?php else: ?>
                                <form method="POST" action="<?php echo URLROOT; ?>/admin/item-delete/<?php echo $data['page']; ?>/<?php echo $data['itemsData']['sectionKey']; ?>/<?php echo $item['id']; ?>" onsubmit="return confirm('Delete this entry?');" style="margin:0;">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrfToken']); ?>">
                                    <button type="submit" class="admin-btn admin-btn-sm admin-btn-danger-ghost"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Live Preview: renders the real page template, with any pending (unsaved)
     field edits from this section's form overlaid, inside a laptop-shaped
     frame. Available on every section, including item-only ones with no
     text form -- it just shows the page as currently saved in that case. -->
<div class="live-preview-overlay" id="livePreviewOverlay">
    <div class="live-preview-toolbar">
        <span><i class="fas fa-circle-notch fa-spin" id="livePreviewSpinner" style="display:none;"></i> Live Preview</span>
        <button type="button" id="livePreviewClose" class="admin-btn admin-btn-sm admin-btn-secondary">
            <i class="fas fa-xmark"></i> Close
        </button>
    </div>
    <div class="laptop-mockup">
        <div class="laptop-screen" id="laptopScreen">
            <iframe id="livePreviewFrame" title="Live preview"></iframe>
        </div>
        <div class="laptop-base"><div class="laptop-notch"></div></div>
    </div>
</div>

<script>
(function() {
    var btn = document.getElementById('livePreviewBtn');
    if (!btn) return;

    var overlay = document.getElementById('livePreviewOverlay');
    var closeBtn = document.getElementById('livePreviewClose');
    var frame = document.getElementById('livePreviewFrame');
    var screenEl = document.getElementById('laptopScreen');
    var spinner = document.getElementById('livePreviewSpinner');
    var form = document.getElementById('sectionForm');
    var page = <?php echo json_encode($data['page']); ?>;
    var previewUrl = <?php echo json_encode(URLROOT . '/admin/preview/' . $data['page']); ?>;
    var csrfToken = <?php echo json_encode($data['csrfToken']); ?>;

    var FRAME_W = 1440, FRAME_H = 900;

    function sizeScreen() {
        var maxW = Math.min(window.innerWidth * 0.86, 1240);
        var scale = maxW / FRAME_W;
        screenEl.style.width = (FRAME_W * scale) + 'px';
        screenEl.style.height = (FRAME_H * scale) + 'px';
        frame.style.width = FRAME_W + 'px';
        frame.style.height = FRAME_H + 'px';
        frame.style.transform = 'scale(' + scale + ')';
    }

    function collectFieldValues() {
        var values = {};
        if (!form) return values;
        form.querySelectorAll('input[type="text"], textarea').forEach(function(el) {
            if (el.id) values[el.id] = el.value;
        });
        return values;
    }

    var pending = false, queued = false;
    function renderPreview() {
        if (pending) { queued = true; return; }
        pending = true;
        spinner.style.display = '';
        var body = new URLSearchParams();
        body.set('csrf_token', csrfToken);
        body.set('fields_json', JSON.stringify(collectFieldValues()));
        fetch(previewUrl, { method: 'POST', body: body })
            .then(function(res) { return res.text(); })
            .then(function(html) {
                frame.srcdoc = html;
            })
            .catch(function() { /* preview is best-effort; ignore network errors */ })
            .finally(function() {
                pending = false;
                spinner.style.display = 'none';
                if (queued) { queued = false; renderPreview(); }
            });
    }

    var debounceTimer = null;
    function scheduleRender() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(renderPreview, 450);
    }

    btn.addEventListener('click', function() {
        overlay.classList.add('active');
        sizeScreen();
        renderPreview();
    });
    closeBtn.addEventListener('click', function() {
        overlay.classList.remove('active');
    });
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) overlay.classList.remove('active');
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && overlay.classList.contains('active')) overlay.classList.remove('active');
    });
    window.addEventListener('resize', function() {
        if (overlay.classList.contains('active')) sizeScreen();
    });

    if (form) {
        form.querySelectorAll('input[type="text"], textarea').forEach(function(el) {
            el.addEventListener('input', scheduleRender);
        });
    }
})();
</script>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
