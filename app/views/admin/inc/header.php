<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['title']); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin.css">
</head>
<body class="admin-body">
    <div class="admin-topbar">
        <a href="<?php echo URLROOT; ?>/admin" class="admin-brand">
            <span class="admin-brand-mark"><i class="fas fa-layer-group"></i></span>
            WORKIIFY ADMIN
        </a>
        <div class="admin-topbar-actions">
            <a href="<?php echo URLROOT; ?>/" target="_blank" class="admin-view-site">View Site <i class="fas fa-arrow-up-right-from-square"></i></a>
            <a href="<?php echo URLROOT; ?>/admin/logout" class="admin-logout"><i class="fas fa-right-from-bracket"></i> Log Out</a>
        </div>
    </div>

    <div class="admin-shell">
        <aside class="admin-sidebar">
            <?php
                $current = $_SERVER['REQUEST_URI'];
                function admin_nav_active($needle, $current) { return strpos($current, $needle) !== false; }
                function admin_page_icon($pageKey) {
                    $icons = [
                        'home' => 'fa-house',
                        'about' => 'fa-circle-info',
                        'contact' => 'fa-envelope',
                        'gallery' => 'fa-images',
                    ];
                    return $icons[$pageKey] ?? 'fa-file-lines';
                }
                function admin_section_icon($section) {
                    if (!empty($section['items']) && empty($section['fields'])) return 'fa-layer-group';
                    if (($section['key'] ?? '') === 'banner') return 'fa-image';
                    return 'fa-pen-to-square';
                }
                // Turns "fa-map-marker-alt" into "Map Marker Alt" for a picker tile's tooltip.
                function admin_icon_label($iconClass) {
                    return ucwords(str_replace('-', ' ', preg_replace('/^fa-/', '', $iconClass)));
                }
            ?>
            <div class="admin-nav-page-label">Pages</div>
            <nav class="admin-sidebar-nav">
                <?php foreach (Home_content_model::pages() as $pageKey => $pageLabel):
                    $sections = Home_content_model::navSections($pageKey);
                    $sectionActive = false;
                    foreach ($sections as $s) {
                        if (admin_nav_active('/admin/section/' . $pageKey . '/' . $s['key'], $current)
                            || (!empty($s['items']) && admin_nav_active('/admin/item-edit/' . $pageKey . '/' . $s['items'], $current))) {
                            $sectionActive = true;
                            break;
                        }
                    }
                    $pageExpanded = $sectionActive || (!admin_nav_active('/admin/media', $current) && $pageKey === array_key_first(Home_content_model::pages()));
                ?>
                    <div class="admin-nav-page">
                        <button type="button" class="admin-nav-page-toggle<?php echo $pageExpanded ? ' open' : ''; ?>" data-target="admin-nav-<?php echo $pageKey; ?>-sections">
                            <span class="admin-nav-page-icon"><i class="fas <?php echo admin_page_icon($pageKey); ?>"></i></span>
                            <span class="admin-nav-page-text"><?php echo htmlspecialchars($pageLabel); ?></span>
                            <i class="fas fa-chevron-right admin-nav-chevron"></i>
                        </button>
                        <ul class="admin-nav-page-sections<?php echo $pageExpanded ? ' expanded' : ''; ?>" id="admin-nav-<?php echo $pageKey; ?>-sections">
                            <?php foreach ($sections as $section):
                                $active = admin_nav_active('/admin/section/' . $pageKey . '/' . $section['key'], $current)
                                    || (!empty($section['items']) && admin_nav_active('/admin/item-edit/' . $pageKey . '/' . $section['items'], $current));
                            ?>
                                <li>
                                    <a href="<?php echo URLROOT; ?>/admin/section/<?php echo $pageKey; ?>/<?php echo $section['key']; ?>" class="admin-nav-link<?php echo $active ? ' active' : ''; ?>">
                                        <i class="fas <?php echo admin_section_icon($section); ?>"></i>
                                        <?php echo htmlspecialchars($section['label']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </nav>

            <div class="admin-nav-page-label">Modules</div>
            <nav class="admin-sidebar-nav">
                <div class="admin-nav-page">
                    <a href="<?php echo URLROOT; ?>/admin/enquiries" class="admin-nav-link admin-nav-top-link<?php echo admin_nav_active('/admin/enquiries', $current) ? ' active' : ''; ?>">
                        <i class="fas fa-envelope-open-text"></i> Enquiries
                    </a>
                    <a href="<?php echo URLROOT; ?>/admin/feedback" class="admin-nav-link admin-nav-top-link<?php echo admin_nav_active('/admin/feedback', $current) ? ' active' : ''; ?>">
                        <i class="fas fa-comment-dots"></i> Feedback
                    </a>
                </div>
            </nav>

            <div class="admin-nav-page-label">Assets</div>
            <nav class="admin-sidebar-nav">
                <div class="admin-nav-page">
                    <a href="<?php echo URLROOT; ?>/admin/media" class="admin-nav-link admin-nav-top-link<?php echo admin_nav_active('/admin/media', $current) ? ' active' : ''; ?>">
                        <i class="fas fa-images"></i> Media Library
                    </a>
                    <a href="<?php echo URLROOT; ?>/admin/history" class="admin-nav-link admin-nav-top-link<?php echo admin_nav_active('/admin/history', $current) ? ' active' : ''; ?>">
                        <i class="fas fa-clock-rotate-left"></i> History
                    </a>
                </div>
            </nav>
        </aside>

        <main class="admin-main">
