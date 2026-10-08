<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['title']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin.css">
</head>
<body class="admin-body">
    <div class="admin-topbar">
        <a href="<?php echo URLROOT; ?>/admin" class="admin-brand"><i class="fas fa-layer-group"></i> WORKIIFY ADMIN</a>
        <div>
            <a href="<?php echo URLROOT; ?>/" target="_blank" style="margin-right:20px;">View Site <i class="fas fa-arrow-up-right-from-square"></i></a>
            <a href="<?php echo URLROOT; ?>/admin/logout" class="admin-logout">Log Out</a>
        </div>
    </div>

    <div class="admin-shell">
        <aside class="admin-sidebar">
            <?php
                $current = $_SERVER['REQUEST_URI'];
                function admin_nav_active($needle, $current) { return strpos($current, $needle) !== false; }

                $homeSectionActive = false;
                foreach (Home_content_model::navSections() as $s) {
                    if (admin_nav_active('/admin/section/' . $s['key'], $current)
                        || (!empty($s['items']) && admin_nav_active('/admin/item-edit/' . $s['items'], $current))) {
                        $homeSectionActive = true;
                        break;
                    }
                }
                $homeExpanded = $homeSectionActive || !admin_nav_active('/admin/media', $current);
            ?>
            <div class="admin-nav-page-label">Pages</div>
            <nav class="admin-sidebar-nav">
                <div class="admin-nav-page">
                    <button type="button" class="admin-nav-page-toggle<?php echo $homeExpanded ? ' open' : ''; ?>" data-target="admin-nav-home-sections">
                        <i class="fas fa-chevron-right admin-nav-chevron"></i> Home Page
                    </button>
                    <ul class="admin-nav-page-sections" id="admin-nav-home-sections" style="<?php echo $homeExpanded ? '' : 'display:none;'; ?>">
                        <?php foreach (Home_content_model::navSections() as $section):
                            $active = admin_nav_active('/admin/section/' . $section['key'], $current)
                                || (!empty($section['items']) && admin_nav_active('/admin/item-edit/' . $section['items'], $current));
                        ?>
                            <li>
                                <a href="<?php echo URLROOT; ?>/admin/section/<?php echo $section['key']; ?>" class="admin-nav-link<?php echo $active ? ' active' : ''; ?>">
                                    <?php echo htmlspecialchars($section['label']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <?php foreach (['About Us', 'Services', 'Gallery', 'Contact Us'] as $future): ?>
                    <div class="admin-nav-page admin-nav-page-disabled" title="Coming soon">
                        <span class="admin-nav-page-toggle disabled">
                            <i class="fas fa-chevron-right admin-nav-chevron"></i> <?php echo htmlspecialchars($future); ?>
                            <span class="admin-nav-soon">Soon</span>
                        </span>
                    </div>
                <?php endforeach; ?>
            </nav>

            <div class="admin-nav-page-label">Assets</div>
            <nav class="admin-sidebar-nav">
                <div class="admin-nav-page">
                    <a href="<?php echo URLROOT; ?>/admin/media" class="admin-nav-link admin-nav-top-link<?php echo admin_nav_active('/admin/media', $current) ? ' active' : ''; ?>">
                        <i class="fas fa-images"></i> Media Library
                    </a>
                </div>
            </nav>
        </aside>

        <main class="admin-main">