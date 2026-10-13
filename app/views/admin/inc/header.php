<!DOCTYPE html>
<html lang="en">
<?php require_once APPROOT . '/views/admin/inc/head.php'; ?>
<body class="admin-body">
    <?php
// Repeated values, defined once
$navActiveCls = ' active';
$navSectionUrl = '/admin/section/';
$navItemUrl = '/admin/item-edit/';
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
            if (!empty($section['items']) && empty($section['fields'])) { return 'fa-layer-group'; }
            if (($section['key'] ?? '') === 'banner') { return 'fa-image'; }
            return 'fa-pen-to-square';
        }
        // Turns "fa-map-marker-alt" into "Map Marker Alt" for a picker tile's tooltip.
        function admin_icon_label($iconClass) {
            return ucwords(str_replace('-', ' ', preg_replace('/^fa-/', '', $iconClass)));
        }
        // The current admin path + query string relative to URLROOT (e.g. "/admin/enquiries?status=New"),
        // posted as "return" by row actions so they land back on the same filtered page.
        function admin_current_path() {
            $base = rtrim((string)parse_url(URLROOT, PHP_URL_PATH), '/');
            $uri = $_SERVER['REQUEST_URI'];
            return ($base !== '' && strpos($uri, $base) === 0) ? substr($uri, strlen($base)) : $uri;
        }
        // Link to the current list with some query parameters changed (null removes one).
        function admin_query_url(array $changes) {
            $params = $_GET;
            unset($params['url']);
            foreach ($changes as $k => $v) {
                if ($v === null || $v === '') { unset($params[$k]); } else { $params[$k] = $v; }
            }
            $path = strtok(admin_current_path(), '?');
            return htmlspecialchars(URLROOT . $path . ($params ? '?' . http_build_query($params) : ''));
        }
        // A sortable column header: click to sort by it, click again to reverse.
        function admin_sort_th($key, $label, $sort, $dir) {
            $active = $sort === $key;
            $next = $active && $dir === 'asc' ? 'desc' : 'asc';
            $icon = 'fa-sort';
            $ariaSort = '';
            if ($active) {
                $icon = $dir === 'asc' ? 'fa-sort-up' : 'fa-sort-down';
                $ariaSort = ' aria-sort="' . ($dir === 'asc' ? 'ascending' : 'descending') . '"';
            }
            return '<th class="admin-th-sort' . ($active ? ' is-sorted' : '') . '"' . $ariaSort . '>'
                . '<a href="' . admin_query_url(['sort' => $key, 'dir' => $next, 'page' => null]) . '">' . htmlspecialchars($label) . ' <i class="fas ' . $icon . '"></i></a></th>';
        }

        $navNewEnquiries = 0;
        $navPendingFeedback = 0;
        try {
            $navNewEnquiries = (int)(new Enquiry())->getNewCount();
            $navPendingFeedback = (int)((new Testimonial())->statusCounts()['Pending'] ?? 0);
        } catch (Throwable $e) {
            // badges are a convenience; never break the page over them
        }
    ?>
    <div class="admin-topbar">
        <div class="admin-topbar-left">
            <button type="button" class="admin-menu-toggle" id="adminMenuToggle" aria-label="Open menu" aria-controls="adminSidebar" aria-expanded="false">
                <i class="fas fa-bars"></i>
            </button>
            <a href="<?php echo URLROOT; ?>/admin" class="admin-brand">
                <span class="admin-brand-mark"><img src="<?php echo URLROOT; ?>/images/favicon-animated.svg" alt="Workiify" width="30" height="30" style="display:block; border-radius:8px;"></span>
                <span class="admin-brand-text">WORKIIFY ADMIN</span>
            </a>
        </div>
        <div class="admin-topbar-actions">
            <a href="<?php echo URLROOT; ?>/" target="_blank" rel="noopener" class="admin-view-site" title="View Site"><span class="admin-topbar-label">View Site</span> <i class="fas fa-arrow-up-right-from-square"></i></a>
            <?php if (!empty($_SESSION['admin_username'])): ?>
                <span class="admin-topbar-user" title="Signed in"><i class="fas fa-circle-user"></i> <span class="admin-topbar-label"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span></span>
            <?php endif; ?>
            <a href="<?php echo URLROOT; ?>/admin/logout" class="admin-logout" title="Log Out"><i class="fas fa-right-from-bracket"></i> <span class="admin-topbar-label">Log Out</span></a>
        </div>
    </div>

    <div class="admin-shell">
        <div class="admin-sidebar-backdrop" id="adminSidebarBackdrop"></div>
        <aside class="admin-sidebar" id="adminSidebar">
            <nav class="admin-sidebar-nav" aria-label="Overview">
                <div class="admin-nav-page">
                    <a href="<?php echo URLROOT; ?>/admin" class="admin-nav-link admin-nav-top-link<?php echo preg_match('#/admin/?(\?|$)#', $current) ? $navActiveCls : ''; ?>">
                        <i class="fas fa-gauge-high"></i> Dashboard
                    </a>
                </div>
            </nav>

            <div class="admin-nav-page-label">Pages</div>
            <nav class="admin-sidebar-nav" aria-label="Pages">
                <?php foreach (Home_content_model::pages() as $pageKey => $pageLabel):
                    $sections = Home_content_model::navSections($pageKey);
                    $sectionActive = false;
                    foreach ($sections as $s) {
                        if (admin_nav_active($navSectionUrl . $pageKey . '/' . $s['key'], $current)
                            || (!empty($s['items']) && admin_nav_active($navItemUrl . $pageKey . '/' . $s['items'], $current))) {
                            $sectionActive = true;
                            break;
                        }
                    }
                    // One link per page: its sections are listed inside the page editor (same as services).
                    $firstSection = $sections[0]['key'] ?? '';
                ?>
                    <div class="admin-nav-page">
                        <a href="<?php echo URLROOT; ?>/admin/section/<?php echo $pageKey; ?>/<?php echo $firstSection; ?>" class="admin-nav-page-toggle admin-nav-page-link<?php echo $sectionActive ? ' is-current' : ''; ?>"<?php echo $sectionActive ? ' aria-current="page"' : ''; ?>>
                            <span class="admin-nav-page-icon"><i class="fas <?php echo admin_page_icon($pageKey); ?>"></i></span>
                            <span class="admin-nav-page-text"><?php echo htmlspecialchars($pageLabel); ?></span>
                            <span class="admin-nav-count" title="<?php echo count($sections); ?> sections"><?php echo count($sections); ?></span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </nav>

            <div class="admin-nav-page-label">Services</div>
            <nav class="admin-sidebar-nav" aria-label="Services">
                <?php
                    // One compact list: each service opens its editor, whose own section tabs take it from there.
                    $navServices = Service_model::sidebarList();
                    $svcAnyActive = false;
                    foreach ($navServices as $navSvc) {
                        $k = Service_model::pageKey($navSvc['id']);
                        if (admin_nav_active($navSectionUrl . $k . '/', $current) || admin_nav_active($navItemUrl . $k . '/', $current)) { $svcAnyActive = true; break; }
                    }
                    $svcListOpen = $svcAnyActive || admin_nav_active('/admin/service', $current);
                ?>
                <div class="admin-nav-page">
                    <a href="<?php echo URLROOT; ?>/admin/services" class="admin-nav-link admin-nav-top-link<?php echo admin_nav_active('/admin/services', $current) ? $navActiveCls : ''; ?>">
                        <i class="fas fa-list"></i> All Services
                    </a>
                    <a href="<?php echo URLROOT; ?>/admin/service-new" class="admin-nav-link admin-nav-top-link<?php echo admin_nav_active('/admin/service-new', $current) ? $navActiveCls : ''; ?>">
                        <i class="fas fa-plus"></i> Add Service
                    </a>
                    <button type="button" class="admin-nav-page-toggle admin-nav-svc-toggle<?php echo $svcListOpen ? ' open' : ''; ?>" data-target="admin-nav-svc-list" aria-expanded="<?php echo $svcListOpen ? 'true' : 'false'; ?>">
                        <span class="admin-nav-page-icon"><i class="fas fa-briefcase"></i></span>
                        <span class="admin-nav-page-text">Edit a service <span class="admin-nav-count"><?php echo count($navServices); ?></span></span>
                        <i class="fas fa-chevron-right admin-nav-chevron"></i>
                    </button>
                    <div class="admin-nav-page-sections admin-nav-svc-list<?php echo $svcListOpen ? ' expanded' : ''; ?>" id="admin-nav-svc-list">
                        <?php if (count($navServices) > 5): ?>
                            <div class="admin-nav-filter">
                                <i class="fas fa-magnifying-glass"></i>
                                <input type="search" id="adminNavSvcFilter" placeholder="Find a service…" aria-label="Find a service" autocomplete="off">
                            </div>
                        <?php endif; ?>
                        <ul>
                            <?php foreach ($navServices as $navSvc):
                                $svcKey = Service_model::pageKey($navSvc['id']);
                                $svcActive = admin_nav_active($navSectionUrl . $svcKey . '/', $current) || admin_nav_active($navItemUrl . $svcKey . '/', $current);
                            ?>
                                <li data-name="<?php echo htmlspecialchars(strtolower($navSvc['title'])); ?>">
                                    <a href="<?php echo URLROOT; ?>/admin/section/<?php echo $svcKey; ?>/banner" class="admin-nav-link<?php echo $svcActive ? $navActiveCls : ''; ?>">
                                        <i class="fas <?php echo htmlspecialchars($navSvc['nav_icon'] ?: 'fa-briefcase'); ?>"></i>
                                        <span class="admin-nav-link-text"><?php echo htmlspecialchars($navSvc['title']); ?></span>
                                        <?php if ($navSvc['status'] !== 'published'): ?><em class="admin-nav-draft">draft</em><?php endif; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <p class="admin-nav-empty" id="adminNavSvcEmpty" hidden>No service matches.</p>
                    </div>
                </div>
            </nav>

            <div class="admin-nav-page-label">Modules</div>
            <nav class="admin-sidebar-nav" aria-label="Modules">
                <div class="admin-nav-page">
                    <a href="<?php echo URLROOT; ?>/admin/enquiries" class="admin-nav-link admin-nav-top-link<?php echo admin_nav_active('/admin/enquir', $current) ? $navActiveCls : ''; ?>">
                        <i class="fas fa-envelope-open-text"></i> Enquiries
                        <?php if ($navNewEnquiries > 0): ?><span class="admin-nav-badge" title="<?php echo $navNewEnquiries; ?> new"><?php echo $navNewEnquiries; ?></span><?php endif; ?>
                    </a>
                    <a href="<?php echo URLROOT; ?>/admin/feedback" class="admin-nav-link admin-nav-top-link<?php echo admin_nav_active('/admin/feedback', $current) ? $navActiveCls : ''; ?>">
                        <i class="fas fa-comment-dots"></i> Feedback
                        <?php if ($navPendingFeedback > 0): ?><span class="admin-nav-badge" title="<?php echo $navPendingFeedback; ?> awaiting review"><?php echo $navPendingFeedback; ?></span><?php endif; ?>
                    </a>
                </div>
            </nav>

            <div class="admin-nav-page-label">Assets</div>
            <nav class="admin-sidebar-nav" aria-label="Assets">
                <div class="admin-nav-page">
                    <a href="<?php echo URLROOT; ?>/admin/media" class="admin-nav-link admin-nav-top-link<?php echo admin_nav_active('/admin/media', $current) ? $navActiveCls : ''; ?>">
                        <i class="fas fa-images"></i> Media Library
                    </a>
                    <a href="<?php echo URLROOT; ?>/admin/history" class="admin-nav-link admin-nav-top-link<?php echo admin_nav_active('/admin/history', $current) ? $navActiveCls : ''; ?>">
                        <i class="fas fa-clock-rotate-left"></i> History
                    </a>
                </div>
            </nav>
        </aside>

        <main class="admin-main">
