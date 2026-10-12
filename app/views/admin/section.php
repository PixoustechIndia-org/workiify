<?php
require APPROOT . '/views/admin/inc/header.php';

$isService = !empty($data['isService']);
$svc = $data['service'] ?? null;
$allSections = $data['allSections'] ?? [];

$secIcons = [
    'settings' => 'fa-gear',
    'banner' => 'fa-image',
    'introduction' => 'fa-align-left',
    'highlight-1' => 'fa-wand-magic-sparkles',
    'options' => 'fa-cubes',
    'location' => 'fa-location-dot',
    'feature-tabs' => 'fa-table-cells',
    'who-its-for' => 'fa-users',
    'key-points' => 'fa-list-ol',
    'steps' => 'fa-shoe-prints',
    'highlight-2' => 'fa-wand-magic-sparkles',
    'faq' => 'fa-circle-question',
    'closing-banner' => 'fa-bullhorn',
    'explore-more' => 'fa-compass',
];

$currentIndex = -1;
foreach ($allSections as $idx => $sItem) {
    if ($sItem['key'] === $data['navKey']) {
        $currentIndex = $idx;
        break;
    }
}
$prevSec = ($currentIndex > 0) ? $allSections[$currentIndex - 1] : null;
$nextSec = ($currentIndex >= 0 && $currentIndex < count($allSections) - 1) ? $allSections[$currentIndex + 1] : null;

// Check if current section has a toggle that is off
$currentIsOff = false;
if ($isService && !empty($data['fieldsData']['schema']['fields'])) {
    foreach ($data['fieldsData']['schema']['fields'] as $f) {
        if ($f['type'] === 'toggle') {
            $tVal = $data['fieldsData']['values'][$f['key']] ?? '0';
            if ($tVal !== '1' && $tVal !== 1 && $tVal !== true) {
                $currentIsOff = true;
            }
            break;
        }
    }
}
?>

<div class="admin-wrap svc-editor-wrap">
    <?php if ($isService && $svc): ?>
        <!-- Service Hub Workspace Bar -->
        <div class="svc-workspace-bar">
            <div class="svc-workspace-left">
                <a href="<?php echo URLROOT; ?>/admin/services" class="svc-workspace-back" title="Return to Services list">
                    <i class="fas fa-arrow-left"></i> All Services
                </a>
                <span class="svc-workspace-sep">/</span>
                <div class="svc-workspace-identity">
                    <h1 class="svc-workspace-title"><?php echo htmlspecialchars($svc['title']); ?></h1>
                    <?php if (!empty($data['publicUrl'])): ?>
                        <span class="svc-workspace-slug">
                            <i class="fas fa-link"></i> <?php echo htmlspecialchars($data['publicUrl']); ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="svc-workspace-right">
                <!-- Status Switch Form -->
                <?php $isPub = ($svc['status'] === 'published'); ?>
                <form method="POST" action="<?php echo URLROOT; ?>/admin/service-action/<?php echo $isPub ? 'unpublish' : 'publish'; ?>/<?php echo (int)$svc['id']; ?>" class="svc-inline-form">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrfToken']); ?>">
                    <input type="hidden" name="return" value="/admin/section/<?php echo $data['page']; ?>/<?php echo $data['navKey']; ?>">
                    <button type="submit" class="svc-pill <?php echo $isPub ? 'is-on' : 'is-off'; ?>" title="<?php echo $isPub ? 'Click to switch to Draft' : 'Click to Publish live'; ?>">
                        <span class="svc-dot"></span>
                        <span class="svc-pill-text"><?php echo $isPub ? 'Published' : 'Draft'; ?></span>
                    </button>
                </form>

                <?php if (!empty($data['publicUrl'])): ?>
                    <a href="<?php echo URLROOT . htmlspecialchars($data['publicUrl']); ?>" target="_blank" rel="noopener" class="admin-btn admin-btn-sm admin-btn-secondary svc-workspace-btn" title="View page in new tab">
                        <i class="fas fa-arrow-up-right-from-square"></i> View Page
                    </a>
                <?php endif; ?>

                <button type="button" id="livePreviewBtn" class="admin-btn admin-btn-sm svc-workspace-preview-btn">
                    <i class="fas fa-desktop"></i> Live Preview
                </button>
            </div>
        </div>

        <!-- Horizontal Section Sub-Navigation Tabs -->
        <div class="svc-tabs-bar">
            <div class="svc-tabs-scroll">
                <?php foreach ($allSections as $sItem):
                    $isActive = ($sItem['key'] === $data['navKey']);
                    $sOff = !empty($sItem['off']);
                    $sIsSettings = !empty($sItem['settings']);
                    $iconClass = $secIcons[$sItem['key']] ?? 'fa-pen-to-square';
                ?>
                    <a href="<?php echo URLROOT; ?>/admin/section/<?php echo $data['page']; ?>/<?php echo $sItem['key']; ?>"
                       class="svc-tab-item<?php echo $isActive ? ' is-active' : ''; ?><?php echo $sOff ? ' is-disabled-sec' : ''; ?>"
                       title="<?php echo htmlspecialchars($sItem['label'] . ($sOff ? ' (Hidden on live site)' : '')); ?>">
                        <i class="fas <?php echo $iconClass; ?> svc-tab-icon"></i>
                        <span class="svc-tab-text"><?php echo htmlspecialchars($sItem['label']); ?></span>
                        <?php if ($sOff): ?>
                            <span class="svc-tab-state-badge is-off">OFF</span>
                        <?php elseif (!$sIsSettings): ?>
                            <span class="svc-tab-dot-state is-on"></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="admin-page-header">
            <div>
                <h1><?php echo htmlspecialchars($data['sectionLabel']); ?></h1>
                <?php if (!empty($data['sectionHelp'])): ?>
                    <p class="admin-page-subtitle"><?php echo htmlspecialchars($data['sectionHelp']); ?></p>
                <?php endif; ?>
            </div>
            <button type="button" id="livePreviewBtn" class="admin-btn admin-btn-secondary admin-btn-sm">
                <i class="fas fa-desktop"></i> Live Preview
            </button>
        </div>
    <?php endif; ?>

    <?php if ($isService): ?>
        <div class="svc-current-section-header">
            <div>
                <h2>
                    <i class="fas <?php echo $secIcons[$data['navKey']] ?? 'fa-pen-to-square'; ?>" style="color:var(--admin-primary); margin-right:8px;"></i>
                    <?php echo htmlspecialchars($data['sectionLabel']); ?>
                </h2>
                <?php if (!empty($data['sectionHelp'])): ?>
                    <p class="svc-section-description"><?php echo htmlspecialchars($data['sectionHelp']); ?></p>
                <?php endif; ?>
            </div>
            <?php if (!$isService): ?>
                <button type="button" id="livePreviewBtn" class="admin-btn admin-btn-secondary admin-btn-sm">
                    <i class="fas fa-desktop"></i> Live Preview
                </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($currentIsOff): ?>
        <div class="svc-disabled-banner">
            <div class="svc-disabled-icon"><i class="fas fa-eye-slash"></i></div>
            <div class="svc-disabled-content">
                <strong>This section is currently hidden on the website</strong>
                <p>Visitors won't see this section until you switch on the "Show this section on the page" toggle below and click Save.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($data['message'])): ?>
        <div class="admin-message svc-flash"><i class="fas fa-circle-check"></i> <?php echo htmlspecialchars($data['message']); ?></div>
    <?php endif; ?>
    <?php if (!empty($data['flash'])): ?>
        <div class="admin-<?php echo $data['flash']['type'] === 'error' ? 'error' : 'message'; ?> svc-flash">
            <i class="fas <?php echo $data['flash']['type'] === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check'; ?>"></i>
            <?php echo htmlspecialchars($data['flash']['message']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($data['errors'])): ?>
        <div class="admin-error" role="alert">
            <strong><i class="fas fa-circle-exclamation"></i> Please fix the following:</strong>
            <ul style="margin:8px 0 0 18px; padding:0;"><?php foreach ($data['errors'] as $err): ?><li><?php echo htmlspecialchars($err); ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <!-- Main Fields Form -->
    <?php if ($data['fieldsData']): ?>
        <div class="admin-card svc-editor-card">
            <div class="admin-card-header">
                <h3 class="admin-card-subtitle"><i class="fas fa-sliders" style="margin-right:6px; color:var(--admin-primary);"></i> Section Content &amp; Settings</h3>
            </div>
            <form method="POST" enctype="multipart/form-data" class="admin-form" id="sectionForm">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrfToken']); ?>">

                <?php foreach ($data['fieldsData']['schema']['fields'] as $f):
                    $val = $data['fieldsData']['values'][$f['key']] ?? '';
                    require APPROOT . '/views/admin/inc/field_input.php';

                    // If we just rendered meta_description in settings, render the Google SERP preview
                    if ($f['key'] === 'meta_description' && $data['navKey'] === 'settings'): ?>
                        <div class="svc-serp-box">
                            <div class="svc-serp-header">
                                <i class="fab fa-google"></i>
                                <span>Google Search Result Preview</span>
                            </div>
                            <div class="svc-serp-card">
                                <div class="svc-serp-url">workiify.com › services › <span id="serpSlug"><?php echo htmlspecialchars($svc['slug'] ?? 'service'); ?></span></div>
                                <div class="svc-serp-title" id="serpTitle"><?php echo htmlspecialchars(!empty($data['fieldsData']['values']['meta_title']) ? $data['fieldsData']['values']['meta_title'] . ' | Workiify' : ($svc['title'] ?? 'Service') . ' | Workiify'); ?></div>
                                <div class="svc-serp-desc" id="serpDesc"><?php echo htmlspecialchars(!empty($data['fieldsData']['values']['meta_description']) ? $data['fieldsData']['values']['meta_description'] : (!empty($data['fieldsData']['values']['card_summary']) ? $data['fieldsData']['values']['card_summary'] : 'Discover flexible workspace options with Workiify in Coimbatore.')); ?></div>
                            </div>
                        </div>
                    <?php endif;
                endforeach; ?>

                <div class="admin-actions svc-bottom-actions">
                    <div class="svc-actions-left">
                        <?php if ($prevSec): ?>
                            <a href="<?php echo URLROOT; ?>/admin/section/<?php echo $data['page']; ?>/<?php echo $prevSec['key']; ?>" class="admin-btn admin-btn-secondary admin-btn-sm" title="Go to <?php echo htmlspecialchars($prevSec['label']); ?>">
                                <i class="fas fa-chevron-left"></i> <?php echo htmlspecialchars($prevSec['label']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="svc-actions-right">
                        <span class="svc-unsaved-chip" id="unsavedNotice" style="display:none;">
                            <i class="fas fa-circle-dot"></i> Unsaved changes
                        </span>
                        <button type="submit" class="admin-btn svc-save-btn">
                            <i class="fas fa-check"></i> Save Changes
                        </button>
                        <?php if ($nextSec): ?>
                            <button type="submit" name="redirect_to_section" value="<?php echo htmlspecialchars($nextSec['key']); ?>" class="admin-btn admin-btn-primary svc-next-btn" title="Save and navigate to <?php echo htmlspecialchars($nextSec['label']); ?>">
                                Save &amp; Continue <i class="fas fa-chevron-right"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- Repeatable Items List -->
    <?php if ($data['itemsData']):
        $schema = $data['itemsData']['schema'];
        $count = count($data['itemsData']['items']);
        $min = $schema['minItems'] ?? 0;
        $max = $schema['maxItems'] ?? null;
        $atMax = $max !== null && $count >= $max;
        $atMin = $count <= $min;
        $locked = $max !== null && $min === $max;
    ?>
        <div class="admin-card svc-editor-card">
            <div class="admin-card-header" style="margin-bottom:10px;">
                <h3 class="admin-card-subtitle"><i class="fas fa-layer-group" style="margin-right:6px; color:var(--admin-primary);"></i> <?php echo htmlspecialchars($schema['label']); ?></h3>
                <?php if ($atMax): ?>
                    <span class="admin-btn admin-btn-sm admin-btn-secondary is-disabled" title="Maximum reached"><i class="fas fa-plus"></i> Add New</span>
                <?php else: ?>
                    <a href="<?php echo URLROOT; ?>/admin/item-edit/<?php echo $data['page']; ?>/<?php echo $data['itemsData']['sectionKey']; ?>/new" class="admin-btn admin-btn-sm"><i class="fas fa-plus"></i> Add New Entry</a>
                <?php endif; ?>
            </div>

            <p class="admin-limit-note">
                <?php if ($locked): ?>
                    <i class="fas fa-lock"></i> This section is built for exactly <?php echo $max; ?> entries so the layout stays aligned — entries can be edited but not added or removed.
                <?php else: ?>
                    <strong><?php echo $count; ?><?php echo $max !== null ? ' / ' . $max : ''; ?></strong> entries currently configured.
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
                <div class="svc-empty" style="padding:32px 10px;">
                    <div class="svc-empty-icon"><i class="fas fa-folder-open"></i></div>
                    <h3>No entries yet</h3>
                    <p>Click "+ Add New Entry" above to add items to this section.</p>
                </div>
            <?php else: ?>
                <div class="svc-items-list">
                    <?php foreach ($data['itemsData']['items'] as $idx => $item):
                        $d = $item['data'];
                        $title = $d[$titleKey] ?? ('Item #' . $item['id']);
                        $sub = '';
                        foreach ($fieldKeys as $fk) {
                            if ($fk !== $titleKey && $fk !== $imageKey && !empty($d[$fk])) { $sub = $d[$fk]; break; }
                        }
                    ?>
                        <div class="admin-item-row svc-item-row">
                            <?php if ($imageKey && !empty($d[$imageKey])): ?>
                                <img src="<?php echo URLROOT . htmlspecialchars($d[$imageKey]); ?>" class="admin-item-thumb" alt="" loading="lazy">
                            <?php endif; ?>
                            <div class="admin-item-info">
                                <strong><?php echo htmlspecialchars($title); ?></strong>
                                <?php if ($sub): ?><span><?php echo htmlspecialchars($sub); ?></span><?php endif; ?>
                            </div>
                            <div class="admin-item-row-actions">
                                <?php if ($count > 1): ?>
                                    <?php foreach (['up' => ['fa-chevron-up', $idx === 0], 'down' => ['fa-chevron-down', $idx === $count - 1]] as $dir => $cfg): ?>
                                        <?php if ($cfg[1]): ?>
                                            <span class="admin-move-btn is-disabled"><i class="fas <?php echo $cfg[0]; ?>"></i></span>
                                        <?php else: ?>
                                            <form method="POST" action="<?php echo URLROOT; ?>/admin/item-move/<?php echo $data['page']; ?>/<?php echo $data['itemsData']['sectionKey']; ?>/<?php echo $item['id']; ?>/<?php echo $dir; ?>" style="margin:0;">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrfToken']); ?>">
                                                <button type="submit" class="admin-move-btn" title="Move <?php echo $dir; ?>"><i class="fas <?php echo $cfg[0]; ?>"></i></button>
                                            </form>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
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
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Live Preview Overlay Modal -->
<div class="live-preview-overlay" id="livePreviewOverlay">
    <div class="live-preview-toolbar">
        <span><i class="fas fa-circle-notch fa-spin" id="livePreviewSpinner" style="display:none;"></i> Live Preview: <?php echo htmlspecialchars($data['pageLabel'] ?? 'Service'); ?></span>
        <button type="button" id="livePreviewClose" class="admin-btn admin-btn-sm admin-btn-secondary">
            <i class="fas fa-xmark"></i> Close Preview
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
    // Live Preview Script
    var btn = document.getElementById('livePreviewBtn');
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
        if (!screenEl || !frame) return;
        var maxW = Math.min(window.innerWidth * 0.88, 1280);
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
        form.querySelectorAll('input[type="text"], textarea, select').forEach(function(el) {
            if (el.id) values[el.id] = el.value;
        });
        form.querySelectorAll('input[type="checkbox"]').forEach(function(el) {
            if (el.id) values[el.id] = el.checked ? '1' : '0';
        });
        return values;
    }

    var pending = false, queued = false;
    function renderPreview() {
        if (!frame) return;
        if (pending) { queued = true; return; }
        pending = true;
        if (spinner) spinner.style.display = '';
        var body = new URLSearchParams();
        body.set('csrf_token', csrfToken);
        body.set('fields_json', JSON.stringify(collectFieldValues()));
        fetch(previewUrl, { method: 'POST', body: body })
            .then(function(res) { return res.text(); })
            .then(function(html) {
                frame.srcdoc = html;
            })
            .catch(function() { /* preview is best effort */ })
            .finally(function() {
                pending = false;
                if (spinner) spinner.style.display = 'none';
                if (queued) { queued = false; renderPreview(); }
            });
    }

    var debounceTimer = null;
    function scheduleRender() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(renderPreview, 450);
    }

    if (btn && overlay) {
        btn.addEventListener('click', function() {
            overlay.classList.add('active');
            sizeScreen();
            renderPreview();
        });
        if (closeBtn) closeBtn.addEventListener('click', function() { overlay.classList.remove('active'); });
        overlay.addEventListener('click', function(e) { if (e.target === overlay) overlay.classList.remove('active'); });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && overlay.classList.contains('active')) overlay.classList.remove('active');
        });
        window.addEventListener('resize', function() {
            if (overlay.classList.contains('active')) sizeScreen();
        });
    }

    // Unsaved Changes Tracking
    var formIsDirty = false;
    var unsavedNotice = document.getElementById('unsavedNotice');

    function markDirty() {
        if (!formIsDirty) {
            formIsDirty = true;
            if (unsavedNotice) unsavedNotice.style.display = 'inline-flex';
        }
    }

    if (form) {
        form.querySelectorAll('input[type="text"], textarea').forEach(function(el) {
            el.addEventListener('input', function() {
                markDirty();
                scheduleRender();
                updateCharCounts(this);
                updateSerpPreview();
            });
        });
        form.querySelectorAll('select, input[type="checkbox"]').forEach(function(el) {
            el.addEventListener('change', function() {
                markDirty();
                scheduleRender();
            });
        });
        form.addEventListener('submit', function() {
            formIsDirty = false;
        });
    }

    // Warn if navigating away with unsaved changes
    window.addEventListener('beforeunload', function(e) {
        if (formIsDirty) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    document.querySelectorAll('.svc-tab-item, .svc-workspace-back').forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (formIsDirty) {
                if (!confirm('You have unsaved changes on this section. Leave without saving?')) {
                    e.preventDefault();
                }
            }
        });
    });

    // Character Counter helper
    function updateCharCounts(inputEl) {
        if (!inputEl || !inputEl.id) return;
        var counter = document.querySelector('.admin-char-count[data-for="' + inputEl.id + '"]');
        if (counter) {
            counter.textContent = inputEl.value.length;
        }
    }

    // SERP Preview helper
    function updateSerpPreview() {
        var titleInput = document.getElementById('meta_title') || document.getElementById('title');
        var descInput = document.getElementById('meta_description') || document.getElementById('card_summary');
        var slugInput = document.getElementById('slug');
        var serpTitle = document.getElementById('serpTitle');
        var serpDesc = document.getElementById('serpDesc');
        var serpSlug = document.getElementById('serpSlug');

        if (serpTitle && titleInput) {
            var tVal = titleInput.value.trim();
            serpTitle.textContent = tVal ? (tVal.indexOf('Workiify') !== -1 ? tVal : tVal + ' | Workiify') : 'Service Title | Workiify';
        }
        if (serpDesc && descInput) {
            var dVal = descInput.value.trim();
            serpDesc.textContent = dVal || 'Discover flexible workspace options with Workiify in Coimbatore.';
        }
        if (serpSlug && slugInput) {
            serpSlug.textContent = slugInput.value.trim() || 'service';
        }
    }

    // Initialize character counters & SERP
    document.querySelectorAll('.admin-char-count').forEach(function(el) {
        var id = el.getAttribute('data-for');
        var inp = document.getElementById(id);
        if (inp) el.textContent = inp.value.length;
    });
    updateSerpPreview();
})();
</script>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
