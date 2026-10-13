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
    <?php
        $svcMode = $isService && $svc;
        $isPub = $svcMode && $svc['status'] === 'published';
        $secUrl = function ($k) use ($data) { return URLROOT . '/admin/section/' . $data['page'] . '/' . $k; };
        // The page reads top to bottom; sections are grouped the same way so the list matches the live page.
        $secGroups = [
            'setup' => ['title' => 'Basic setup', 'hint' => 'Name, address & page header', 'keys' => ['settings', 'banner']],
            'body' => ['title' => 'Page content', 'hint' => 'In the order visitors see them', 'keys' => []],
            'end' => ['title' => 'End of page', 'hint' => 'Last things on the page', 'keys' => ['closing-banner', 'explore-more']],
        ];
        if (!$svcMode) {
            // Home / About / Contact / Gallery: one list, top to bottom, like the live page
            $secGroups = ['setup' => ['title' => '', 'hint' => '', 'keys' => []], 'body' => ['title' => 'Top to bottom', 'hint' => 'In the order visitors see them', 'keys' => []], 'end' => ['title' => '', 'hint' => '', 'keys' => []]];
        }
        $grouped = ['setup' => [], 'body' => [], 'end' => []];
        foreach ($allSections as $n => $sItem) {
            $sItem['num'] = $n + 1;
            if (empty($sItem['help'])) {
                $sItem['help'] = !empty($sItem['items']) && !empty($sItem['fields']) ? 'Text, images and a list of entries' : (!empty($sItem['items']) ? 'A list of entries you can add, edit and reorder' : 'Text and images');
            }
            $g = in_array($sItem['key'], $secGroups['setup']['keys'], true) ? 'setup' : (in_array($sItem['key'], $secGroups['end']['keys'], true) ? 'end' : 'body');
            $grouped[$g][] = $sItem;
        }
        $pageSections = array_values(array_filter($allSections, function ($s) { return empty($s['settings']); }));
        $shownCount = count(array_filter($pageSections, function ($s) { return empty($s['off']); }));
        $currentSec = $allSections[$currentIndex] ?? null;
        $statusOf = function ($s) use ($svcMode) {
            if (!$svcMode || !empty($s['settings'])) return ['setup', 'fa-gear', ''];
            return empty($s['off']) ? ['on', 'fa-eye', 'Visible'] : ['off', 'fa-eye-slash', 'Hidden'];
        };
    ?>
    <?php if (!$svcMode): ?>
        <!-- Which page, and where to see it -->
        <div class="svc-workspace-bar">
            <div class="svc-workspace-left">
                <a href="<?php echo URLROOT; ?>/admin" class="svc-workspace-back" title="Back to the dashboard">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
                <div class="svc-workspace-identity">
                    <span class="svc-workspace-eyebrow">Editing website page</span>
                    <h1 class="svc-workspace-title"><?php echo htmlspecialchars($data['pageLabel']); ?></h1>
                    <?php if (!empty($data['publicUrl'])): ?>
                        <span class="svc-workspace-slug"><i class="fas fa-link"></i> <?php echo htmlspecialchars($data['publicUrl']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="svc-workspace-right">
                <?php if (!empty($data['publicUrl'])): ?>
                    <a href="<?php echo URLROOT . htmlspecialchars($data['publicUrl']); ?>" target="_blank" rel="noopener" class="admin-btn admin-btn-sm admin-btn-secondary svc-workspace-btn" title="Open the saved page in a new tab">
                        <i class="fas fa-arrow-up-right-from-square"></i> Open page
                    </a>
                <?php endif; ?>
                <button type="button" id="livePreviewBtn" class="admin-btn admin-btn-sm svc-workspace-preview-btn" title="See your unsaved changes before saving">
                    <i class="fas fa-desktop"></i> Preview changes
                </button>
            </div>
        </div>
    <?php else: ?>
        <!-- Which service, is it live, and where to see it -->
        <div class="svc-workspace-bar">
            <div class="svc-workspace-left">
                <a href="<?php echo URLROOT; ?>/admin/services" class="svc-workspace-back" title="Back to the list of all services">
                    <i class="fas fa-arrow-left"></i> All Services
                </a>
                <div class="svc-workspace-identity">
                    <span class="svc-workspace-eyebrow">Editing service page</span>
                    <h1 class="svc-workspace-title"><?php echo htmlspecialchars($svc['title']); ?></h1>
                    <?php if (!empty($data['publicUrl'])): ?>
                        <span class="svc-workspace-slug"><i class="fas fa-link"></i> <?php echo htmlspecialchars($data['publicUrl']); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="svc-workspace-right">
                <div class="svc-live-status <?php echo $isPub ? 'is-live' : 'is-draft'; ?>">
                    <span class="svc-live-dot"></span>
                    <span>
                        <strong><?php echo $isPub ? 'Live on website' : 'Draft (not on website)'; ?></strong>
                        <small><?php echo $isPub ? 'Visitors can see this page' : 'Only admins can see it'; ?></small>
                    </span>
                    <form method="POST" action="<?php echo URLROOT; ?>/admin/service-action/<?php echo $isPub ? 'unpublish' : 'publish'; ?>/<?php echo (int)$svc['id']; ?>" class="svc-inline-form"
                        <?php if ($isPub): ?>
                          data-confirm-title="Take this page off the website?"
                          data-confirm-text="“<?php echo htmlspecialchars($svc['title']); ?>” will be hidden from visitors, the menu and the Services page until you publish it again. Nothing is deleted."
                          data-confirm-button="Unpublish"
                        <?php endif; ?>>
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrfToken']); ?>">
                        <input type="hidden" name="return" value="/admin/section/<?php echo $data['page']; ?>/<?php echo $data['navKey']; ?>">
                        <button type="submit" class="svc-live-toggle"><?php echo $isPub ? 'Unpublish' : 'Publish now'; ?></button>
                    </form>
                </div>

                <?php if (!empty($data['publicUrl'])): ?>
                    <a href="<?php echo URLROOT . htmlspecialchars($data['publicUrl']); ?>" target="_blank" rel="noopener" class="admin-btn admin-btn-sm admin-btn-secondary svc-workspace-btn" title="Open the saved page in a new tab">
                        <i class="fas fa-arrow-up-right-from-square"></i> Open page
                    </a>
                <?php endif; ?>
                <button type="button" id="livePreviewBtn" class="admin-btn admin-btn-sm svc-workspace-preview-btn" title="See your unsaved changes before saving">
                    <i class="fas fa-desktop"></i> Preview changes
                </button>
            </div>
        </div>
    <?php endif; ?>

        <div class="svc-editor-layout">
            <!-- Page sections: a numbered list that mirrors the live page from top to bottom -->
            <aside class="svc-steps" aria-label="Page sections">
                <div class="svc-steps-head">
                    <strong>Page sections</strong>
                    <span>Click a section to edit it.</span>
                    <?php if (!$svcMode): ?>
                        <small class="svc-steps-count"><?php echo count($allSections); ?> sections on this page</small>
                    <?php else: ?>
                    <div class="svc-steps-progress" title="<?php echo $shownCount; ?> of <?php echo count($pageSections); ?> page sections are switched on">
                        <div class="svc-steps-bar"><span style="width:<?php echo count($pageSections) ? round($shownCount / count($pageSections) * 100) : 0; ?>%"></span></div>
                        <small><strong><?php echo $shownCount; ?> of <?php echo count($pageSections); ?></strong> sections shown on the website</small>
                    </div>
                    <?php endif; ?>
                </div>

                <nav class="svc-steps-list">
                    <?php foreach ($grouped as $gKey => $items): if (!$items) continue; ?>
                        <div class="svc-steps-group">
                            <div class="svc-steps-group-title"><?php echo $secGroups[$gKey]['title']; ?> <small><?php echo $secGroups[$gKey]['hint']; ?></small></div>
                            <?php foreach ($items as $sItem):
                                $isActive = ($sItem['key'] === $data['navKey']);
                                [$st, $stIcon, $stText] = $statusOf($sItem);
                            ?>
                                <a href="<?php echo $secUrl($sItem['key']); ?>"
                                   class="svc-tab-item svc-step is-<?php echo $st; ?><?php echo $isActive ? ' is-active' : ''; ?>"
                                   <?php echo $isActive ? 'aria-current="page"' : ''; ?>
                                   title="<?php echo htmlspecialchars($sItem['help'] ?? $sItem['label']); ?>">
                                    <span class="svc-step-num"><?php echo $sItem['num']; ?></span>
                                    <span class="svc-step-text">
                                        <span class="svc-step-label"><?php echo htmlspecialchars($sItem['label']); ?></span>
                                        <?php if (!empty($sItem['help'])): ?><span class="svc-step-help"><?php echo htmlspecialchars($sItem['help']); ?></span><?php endif; ?>
                                    </span>
                                    <?php if ($st !== 'setup'): ?><span class="svc-step-status is-<?php echo $st; ?>"><i class="fas <?php echo $stIcon; ?>"></i> <?php echo $stText; ?></span><?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </nav>

                <?php if ($svcMode): ?>
                <div class="svc-steps-legend">
                    <span><i class="fas fa-eye"></i> Visible: shown to visitors</span>
                    <span><i class="fas fa-eye-slash"></i> Hidden: switched off, not shown</span>
                </div>
                <?php endif; ?>

                <!-- Phone & tablet: the same list as a dropdown -->
                <div class="svc-secnav-mobile">
                    <a href="<?php echo $prevSec ? $secUrl($prevSec['key']) : '#'; ?>" class="svc-tab-item svc-secnav-step<?php echo $prevSec ? '' : ' is-disabled'; ?>" aria-label="Previous section"<?php echo $prevSec ? '' : ' aria-disabled="true" tabindex="-1"'; ?>><i class="fas fa-chevron-left"></i></a>
                    <label for="svcSectionSelect" class="sr-only">Go to section</label>
                    <select id="svcSectionSelect" class="admin-select">
                        <?php foreach ($grouped as $gKey => $items): if (!$items) continue; ?>
                            <optgroup label="<?php echo htmlspecialchars($secGroups[$gKey]['title'] ?: $data['pageLabel']); ?>">
                                <?php foreach ($items as $sItem): [, , $stText] = $statusOf($sItem); ?>
                                    <option value="<?php echo htmlspecialchars($secUrl($sItem['key'])); ?>"<?php echo $sItem['key'] === $data['navKey'] ? ' selected' : ''; ?>>
                                        <?php echo $sItem['num']; ?>. <?php echo htmlspecialchars($sItem['label']); ?><?php echo $stText !== '' ? ' (' . $stText . ')' : ''; ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                    <a href="<?php echo $nextSec ? $secUrl($nextSec['key']) : '#'; ?>" class="svc-tab-item svc-secnav-step<?php echo $nextSec ? '' : ' is-disabled'; ?>" aria-label="Next section"<?php echo $nextSec ? '' : ' aria-disabled="true" tabindex="-1"'; ?>><i class="fas fa-chevron-right"></i></a>
                </div>
            </aside>

            <div class="svc-editor-main">
                <?php [$curSt, $curIcon, $curText] = $currentSec ? $statusOf($currentSec) : ['setup', 'fa-gear', '']; ?>
                <div class="svc-current-section-header">
                    <div>
                        <span class="svc-section-step">Section <?php echo $currentIndex + 1; ?> of <?php echo count($allSections); ?></span>
                        <h2>
                            <i class="fas <?php echo $svcMode ? ($secIcons[$data['navKey']] ?? 'fa-pen-to-square') : admin_section_icon($currentSec ?? []); ?>"></i>
                            <?php echo htmlspecialchars($data['sectionLabel']); ?>
                            <?php if ($curSt !== 'setup'): ?>
                                <span class="svc-step-status is-<?php echo $curSt; ?> svc-step-status-lg"><i class="fas <?php echo $curIcon; ?>"></i> <?php echo $curSt === 'on' ? 'Visible on website' : 'Hidden from website'; ?></span>
                            <?php endif; ?>
                        </h2>
                        <?php if (!empty($data['sectionHelp'])): ?>
                            <p class="svc-section-description"><?php echo htmlspecialchars($data['sectionHelp']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

    <?php if ($currentIsOff): ?>
        <div class="svc-disabled-banner">
            <div class="svc-disabled-icon"><i class="fas fa-eye-slash"></i></div>
            <div class="svc-disabled-content">
                <strong>This section is hidden from the website</strong>
                <p>You can still edit it. To show it to visitors, turn on <em>“Show this section on the page”</em> below, then click <b>Save changes</b>.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php require APPROOT . '/views/admin/inc/flash.php'; ?>

    <!-- Main Fields Form -->
    <?php if ($data['fieldsData']): ?>
        <div class="admin-card svc-editor-card">
            <div class="admin-card-header">
                <h3 class="admin-card-subtitle"><i class="fas fa-pen-to-square" style="margin-right:6px; color:var(--admin-primary);"></i> Edit this section</h3>
            </div>
            <form method="POST" enctype="multipart/form-data" class="admin-form" id="sectionForm" data-validate>
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
                                <i class="fas fa-chevron-left"></i> <span class="svc-btn-hint">Previous:</span> <?php echo htmlspecialchars($prevSec['label']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="svc-actions-right">
                        <span class="svc-unsaved-chip" id="unsavedNotice" style="display:none;">
                            <i class="fas fa-circle-dot"></i> Unsaved changes
                        </span>
                        <button type="submit" class="admin-btn svc-save-btn">
                            <i class="fas fa-check"></i> Save changes
                        </button>
                        <?php if ($nextSec): ?>
                            <button type="submit" name="redirect_to_section" value="<?php echo htmlspecialchars($nextSec['key']); ?>" class="admin-btn svc-next-btn" title="Save and navigate to <?php echo htmlspecialchars($nextSec['label']); ?>">
                                Save &amp; next: <?php echo htmlspecialchars($nextSec['label']); ?> <i class="fas fa-chevron-right"></i>
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
                    <a href="<?php echo URLROOT; ?>/admin/item-edit/<?php echo $data['page']; ?>/<?php echo $data['itemsData']['sectionKey']; ?>/new" class="admin-btn admin-btn-sm"><i class="fas fa-plus"></i> Add entry</a>
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
                // the icon is shown as a picture, so the entry is named by its first text field
                $iconKey = null;
                foreach ($schema['fields'] as $f) { if ($f['type'] === 'icon') { $iconKey = $f['key']; break; } }
                $titleKey = $fieldKeys[0];
                foreach ($schema['fields'] as $f) { if (!in_array($f['type'], ['image', 'icon', 'toggle', 'select'], true)) { $titleKey = $f['key']; break; } }
            ?>

            <?php if (empty($data['itemsData']['items'])): ?>
                <div class="svc-empty" style="padding:32px 10px;">
                    <div class="svc-empty-icon"><i class="fas fa-folder-open"></i></div>
                    <h3>No entries yet</h3>
                    <p>Click <strong>Add entry</strong> above to add the first one.</p>
                </div>
            <?php else: ?>
                <div class="svc-items-list">
                    <?php foreach ($data['itemsData']['items'] as $idx => $item):
                        $d = $item['data'];
                        $title = trim((string)($d[$titleKey] ?? '')) !== '' ? $d[$titleKey] : ('Entry ' . ($idx + 1));
                        $sub = '';
                        foreach ($fieldKeys as $fk) {
                            if ($fk !== $titleKey && $fk !== $imageKey && $fk !== $iconKey && !empty($d[$fk])) { $sub = $d[$fk]; break; }
                        }
                    ?>
                        <div class="admin-item-row svc-item-row">
                            <?php if ($imageKey && !empty($d[$imageKey])): ?>
                                <img src="<?php echo URLROOT . htmlspecialchars($d[$imageKey]); ?>" class="admin-item-thumb" alt="" loading="lazy">
                            <?php elseif ($iconKey && !empty($d[$iconKey])): ?>
                                <span class="admin-item-icon"><i class="fas <?php echo htmlspecialchars($d[$iconKey]); ?>"></i></span>
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
                                    <form method="POST" action="<?php echo URLROOT; ?>/admin/item-delete/<?php echo $data['page']; ?>/<?php echo $data['itemsData']['sectionKey']; ?>/<?php echo $item['id']; ?>" style="margin:0;"
                                          data-confirm-title="Delete this entry?"
                                          data-confirm-text="“<?php echo htmlspecialchars(mb_substr((string)$title, 0, 80)); ?>” will be removed from <?php echo htmlspecialchars($data['pageLabel']); ?>. You can restore it from History."
                                          data-confirm-button="Delete entry">
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
            </div><!-- /.svc-editor-main -->
        </div><!-- /.svc-editor-layout -->
</div>

<!-- Live Preview: full-screen, device switcher, always fits the screen -->
<div class="live-preview-overlay" id="livePreviewOverlay" role="dialog" aria-modal="true" aria-labelledby="livePreviewTitle" aria-hidden="true">
    <div class="live-preview-toolbar">
        <div class="lp-title">
            <span class="lp-title-main" id="livePreviewTitle"><i class="fas fa-desktop"></i> Preview: <?php echo htmlspecialchars($isService && $svc ? $svc['title'] : ($data['pageLabel'] ?? 'Page')); ?></span>
            <span class="lp-status" id="livePreviewStatus"><i class="fas fa-circle-notch fa-spin"></i> Loading…</span>
        </div>
        <div class="lp-devices" role="group" aria-label="Preview size">
            <button type="button" class="lp-device" data-device="desktop" title="Desktop (1440 px)"><i class="fas fa-desktop"></i><span>Desktop</span></button>
            <button type="button" class="lp-device" data-device="tablet" title="Tablet (820 px)"><i class="fas fa-tablet-screen-button"></i><span>Tablet</span></button>
            <button type="button" class="lp-device" data-device="mobile" title="Mobile (390 px)"><i class="fas fa-mobile-screen-button"></i><span>Mobile</span></button>
        </div>
        <div class="lp-actions">
            <button type="button" id="livePreviewRefresh" class="lp-icon-btn" title="Refresh preview" aria-label="Refresh preview"><i class="fas fa-rotate-right"></i></button>
            <button type="button" id="livePreviewClose" class="lp-close-btn" title="Close preview (Esc)"><i class="fas fa-xmark"></i><span>Close</span></button>
        </div>
    </div>
    <p class="lp-note"><i class="fas fa-circle-info"></i> Shows your unsaved text changes. Newly uploaded images appear after you save. Nothing is published until you click <b>Save changes</b>.</p>
    <div class="lp-stage" id="livePreviewStage">
        <div class="lp-device-frame" id="livePreviewDevice">
            <iframe id="livePreviewFrame" title="Page preview"></iframe>
        </div>
    </div>
</div>

<script>
(function() {
    // Live Preview Script
    var btn = document.getElementById('livePreviewBtn');
    var overlay = document.getElementById('livePreviewOverlay');
    var closeBtn = document.getElementById('livePreviewClose');
    var frame = document.getElementById('livePreviewFrame');
        var form = document.getElementById('sectionForm');
    var page = <?php echo json_encode($data['page']); ?>;
    var previewUrl = <?php echo json_encode(URLROOT . '/admin/preview/' . $data['page']); ?>;
    var csrfToken = <?php echo json_encode($data['csrfToken']); ?>;

    var stage = document.getElementById('livePreviewStage');
    var deviceEl = document.getElementById('livePreviewDevice');
    var statusEl = document.getElementById('livePreviewStatus');
    var DEVICES = { desktop: 1440, tablet: 820, mobile: 390 };
    var device = window.innerWidth < 700 ? 'mobile' : (window.innerWidth < 1100 ? 'tablet' : 'desktop');

    // Lay the page out at the real device width, then scale it down only if the screen is narrower,
    // filling the available height so the whole frame (and the toolbar) always stays on screen.
    function sizeScreen() {
        if (!stage || !frame) return;
        var pad = window.innerWidth < 600 ? 8 : 24;
        var availW = stage.clientWidth - pad * 2;
        var availH = stage.clientHeight - pad * 2;
        var w = DEVICES[device];
        var scale = Math.min(1, availW / w);
        deviceEl.className = 'lp-device-frame is-' + device;
        deviceEl.style.width = Math.floor(w * scale) + 'px';
        deviceEl.style.height = Math.floor(availH) + 'px';
        frame.style.width = w + 'px';
        frame.style.height = Math.floor(availH / scale) + 'px';
        frame.style.transform = scale < 1 ? 'scale(' + scale + ')' : 'none';
        document.querySelectorAll('.lp-device').forEach(function (b) {
            var on = b.dataset.device === device;
            b.classList.toggle('is-active', on);
            b.setAttribute('aria-pressed', on ? 'true' : 'false');
        });
    }
    function setStatus(loading) {
        if (!statusEl) return;
        statusEl.innerHTML = loading ? '<i class="fas fa-circle-notch fa-spin"></i> Updating…' : '<i class="fas fa-circle-check"></i> Up to date';
        statusEl.classList.toggle('is-ready', !loading);
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
        // images picked from the library (a new upload only shows after saving)
        form.querySelectorAll('input[type="hidden"][id^="existing_"]').forEach(function(el) {
            values[el.id.replace(/^existing_/, '')] = el.value;
        });
        return values;
    }

    var pending = false, queued = false;
    function renderPreview() {
        if (!frame) return;
        if (pending) { queued = true; return; }
        pending = true;
        setStatus(true);
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
                setStatus(false);
                if (queued) { queued = false; renderPreview(); }
            });
    }

    var debounceTimer = null;
    function scheduleRender() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(renderPreview, 450);
    }

    var lastFocus = null;
    function openPreview() {
        lastFocus = document.activeElement;
        overlay.classList.add('active');
        overlay.setAttribute('aria-hidden', 'false');
        document.body.classList.add('lp-open');
        sizeScreen();
        renderPreview();
        var c = document.getElementById('livePreviewClose');
        if (c) c.focus();
    }
    function closePreview() {
        overlay.classList.remove('active');
        overlay.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('lp-open');
        if (lastFocus) lastFocus.focus();
    }
    if (btn && overlay) {
        btn.addEventListener('click', openPreview);
        if (closeBtn) closeBtn.addEventListener('click', closePreview);
        document.getElementById('livePreviewRefresh').addEventListener('click', renderPreview);
        document.querySelectorAll('.lp-device').forEach(function (b) {
            b.addEventListener('click', function () { device = b.dataset.device; sizeScreen(); });
        });
        stage.addEventListener('click', function(e) { if (e.target === stage) closePreview(); });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && overlay.classList.contains('active')) closePreview();
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
        form.querySelectorAll('select, input[type="checkbox"], input[type="file"], input[type="hidden"][id^="existing_"]').forEach(function(el) {
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

    // phone section picker: same unsaved-changes check as the tabs
    var secSelect = document.getElementById('svcSectionSelect');
    if (secSelect) {
        var secInitial = secSelect.value;
        secSelect.addEventListener('change', function() {
            if (formIsDirty && !confirm('You have unsaved changes on this section. Leave without saving?')) {
                secSelect.value = secInitial;
                return;
            }
            formIsDirty = false;
            window.location.href = secSelect.value;
        });
    }

    document.querySelectorAll('.svc-tab-item, .svc-workspace-back').forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (link.classList.contains('is-disabled')) { e.preventDefault(); return; }
            if (formIsDirty) {
                if (!confirm('You have unsaved changes on this section. Leave without saving?')) {
                    e.preventDefault();
                } else {
                    formIsDirty = false;   // already confirmed; don't ask again on unload
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
