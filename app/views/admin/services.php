<?php
require APPROOT . '/views/admin/inc/header.php';

$e = function ($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); };
$cats = $data['categories'];
$csrf = $data['csrfToken'];
$services = $data['services'] ?? [];
$trashed = $data['trashed'] ?? [];

// Calculate counts
$totalCount = count($services);
$publishedCount = 0;
$draftCount = 0;
foreach ($services as $s) {
    if ($s['status'] === 'published') {
        $publishedCount++;
    } else {
        $draftCount++;
    }
}
$trashedCount = count($trashed);

// One small POST form per action (CSRF protected; asks first when it removes something).
$act = function ($action, $svc, $inner, $class = '', $title = '', $confirm = null) use ($e, $csrf) {
    $attrs = $confirm ? ' data-confirm-title="' . $e($confirm['title']) . '" data-confirm-text="' . $e($confirm['text']) . '" data-confirm-button="' . $e($confirm['button']) . '"' : '';
    return '<form method="POST" action="' . URLROOT . '/admin/service-action/' . $action . '/' . (int)$svc['id'] . '" class="svc-inline-form"' . $attrs . '>'
        . '<input type="hidden" name="csrf_token" value="' . $e($csrf) . '">'
        . '<input type="hidden" name="return" value="/admin/services">'
        . '<button type="submit" class="' . $e($class) . '" title="' . $e($title) . '">' . $inner . '</button></form>';
};

$thumb = function ($svc) use ($e) {
    return !empty($svc['card_image'])
        ? '<img class="svc-thumb" src="' . $e(URLROOT . $svc['card_image']) . '" alt="" loading="lazy">'
        : '<span class="svc-thumb svc-thumb-empty"><i class="fas fa-briefcase"></i></span>';
};

$groups = [];
foreach ($services as $svc) {
    $groups[$svc['category']][] = $svc;
}
?>

<div class="admin-wrap admin-wrap-wide svc-wrap">
    <!-- Header -->
    <div class="admin-page-header svc-page-header">
        <div>
            <div class="svc-breadcrumbs">
                <span>Services CMS</span>
            </div>
            <h1>Services Management</h1>
            <p class="admin-page-subtitle">Build, customize, and manage all your service landing pages in one place. Changes publish instantly.</p>
        </div>
        <div class="svc-header-actions">
            <a href="<?php echo URLROOT; ?>/admin/service-new" class="admin-btn admin-btn-primary svc-btn-add">
                <i class="fas fa-plus"></i> Add New Service
            </a>
        </div>
    </div>

    <?php require APPROOT . '/views/admin/inc/flash.php'; ?>

    <?php if (!empty($data['offline'])): ?>
        <div class="admin-error">
            <strong>The services table is unavailable.</strong> The public site is showing built-in defaults and nothing here can be saved until the database connection is restored.
        </div>
    <?php endif; ?>

    <!-- KPI / Overview Stat Cards -->
    <div class="svc-stats-grid">
        <div class="svc-stat-card" data-filter-status="all">
            <div class="svc-stat-icon is-primary"><i class="fas fa-briefcase"></i></div>
            <div class="svc-stat-details">
                <span class="svc-stat-num"><?php echo $totalCount; ?></span>
                <span class="svc-stat-label">Total Services</span>
            </div>
        </div>
        <div class="svc-stat-card" data-filter-status="published">
            <div class="svc-stat-icon is-success"><i class="fas fa-circle-check"></i></div>
            <div class="svc-stat-details">
                <span class="svc-stat-num"><?php echo $publishedCount; ?></span>
                <span class="svc-stat-label">Live on Website</span>
            </div>
        </div>
        <div class="svc-stat-card" data-filter-status="draft">
            <div class="svc-stat-icon is-warning"><i class="fas fa-clock"></i></div>
            <div class="svc-stat-details">
                <span class="svc-stat-num"><?php echo $draftCount; ?></span>
                <span class="svc-stat-label">Draft Pages</span>
            </div>
        </div>
        <?php if ($trashedCount > 0): ?>
            <div class="svc-stat-card" data-filter-status="trashed">
                <div class="svc-stat-icon is-muted"><i class="fas fa-trash-can"></i></div>
                <div class="svc-stat-details">
                    <span class="svc-stat-num"><?php echo $trashedCount; ?></span>
                    <span class="svc-stat-label">Recently Removed</span>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Search & Filter Bar -->
    <div class="svc-toolbar-card">
        <div class="svc-search-box">
            <i class="fas fa-magnifying-glass svc-search-icon"></i>
            <input type="text" id="svcSearchInput" placeholder="Filter services by name, URL slug, or keywords..." autocomplete="off">
            <button type="button" id="svcSearchClear" class="svc-search-clear" title="Clear search" style="display:none;"><i class="fas fa-xmark"></i></button>
        </div>

        <div class="svc-filter-pills">
            <span class="svc-filter-label"><i class="fas fa-filter"></i> Category:</span>
            <button type="button" class="svc-filter-pill is-active" data-cat="all">All (<?php echo $totalCount; ?>)</button>
            <?php foreach ($cats as $cKey => $cMeta):
                $cCount = isset($groups[$cKey]) ? count($groups[$cKey]) : 0;
            ?>
                <button type="button" class="svc-filter-pill" data-cat="<?php echo $e($cKey); ?>">
                    <?php echo $e($cMeta['label']); ?> (<?php echo $cCount; ?>)
                </button>
            <?php endforeach; ?>
        </div>

        <div class="svc-status-toggle-wrap">
            <span class="svc-filter-label">Status:</span>
            <div class="svc-segmented-control">
                <button type="button" class="svc-segment-btn is-active" data-status="all">All</button>
                <button type="button" class="svc-segment-btn" data-status="published">Published</button>
                <button type="button" class="svc-segment-btn" data-status="draft">Drafts</button>
            </div>
        </div>
    </div>

    <!-- Empty State for Empty Database -->
    <?php if (empty($services)): ?>
        <div class="admin-card svc-card">
            <div class="svc-empty">
                <div class="svc-empty-icon"><i class="fas fa-briefcase"></i></div>
                <h3>No services found</h3>
                <p>Get started by creating your first service page template.</p>
                <a href="<?php echo URLROOT; ?>/admin/service-new" class="admin-btn svc-btn-add" style="margin-top:14px;">
                    <i class="fas fa-plus"></i> Add your first service
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Category Groups & Service Lists -->
    <div id="svcGroupsContainer">
        <?php foreach ($cats as $key => $meta):
            if (empty($groups[$key])) continue;
            $items = $groups[$key];
        ?>
            <div class="admin-card svc-card svc-category-card" data-category-group="<?php echo $e($key); ?>">
                <div class="svc-group-head">
                    <div class="svc-group-title">
                        <span class="svc-category-badge"><i class="fas fa-folder-open"></i></span>
                        <div>
                            <h2><?php echo $e($meta['label']); ?></h2>
                            <p class="svc-group-sub"><?php echo $e($meta['subtitle'] ?? ''); ?></p>
                        </div>
                    </div>
                    <span class="svc-badge-count"><span class="svc-cat-count"><?php echo count($items); ?></span> service<?php echo count($items) === 1 ? '' : 's'; ?></span>
                </div>

                <div class="svc-list">
                    <?php foreach ($items as $i => $svc):
                        $published = $svc['status'] === 'published';
                        $first = $i === 0;
                        $last = $i === count($items) - 1;
                        $fullUrl = URLROOT . '/services/' . $svc['slug'];
                        $pageKey = Service_model::pageKey($svc['id']);
                    ?>
                        <div class="svc-row<?php echo $published ? ' is-published' : ' is-draft'; ?>"
                             data-service-id="<?php echo (int)$svc['id']; ?>"
                             data-service-title="<?php echo strtolower($e($svc['title'])); ?>"
                             data-service-slug="<?php echo strtolower($e($svc['slug'])); ?>"
                             data-service-category="<?php echo $e($svc['category']); ?>"
                             data-service-status="<?php echo $e($svc['status']); ?>">
                            
                            <!-- Left: Thumbnail & Details -->
                            <div class="svc-cell svc-main">
                                <?php echo $thumb($svc); ?>
                                <div class="svc-main-text">
                                    <div class="svc-title-row">
                                        <a class="svc-title" href="<?php echo URLROOT; ?>/admin/section/<?php echo $pageKey; ?>/banner">
                                            <?php echo $e($svc['title']); ?>
                                        </a>
                                        <?php if (!empty($svc['card_badge'])): ?>
                                            <span class="svc-tag is-badge"><?php echo $e($svc['card_badge']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="svc-meta-row">
                                        <span class="svc-slug" title="<?php echo $e($fullUrl); ?>">
                                            <i class="fas fa-link"></i> /services/<?php echo $e($svc['slug']); ?>
                                        </span>
                                        <button type="button" class="svc-copy-btn" data-url="<?php echo $e($fullUrl); ?>" title="Copy link to clipboard">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                    <div class="svc-tags-row">
                                        <?php if (!empty($svc['show_in_nav'])): ?>
                                            <span class="svc-tag is-nav" title="Visible in site navigation dropdown"><i class="fas fa-compass"></i> In Menu</span>
                                        <?php endif; ?>
                                        <?php if (!empty($svc['show_on_listing'])): ?>
                                            <span class="svc-tag is-listing" title="Visible on Services overview page"><i class="fas fa-table-cells-large"></i> On Listing</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Pill Switch -->
                            <div class="svc-cell svc-status">
                                <?php echo $act($published ? 'unpublish' : 'publish', $svc,
                                    '<span class="svc-dot"></span><span class="svc-pill-text">' . ($published ? 'Published' : 'Draft') . '</span>',
                                    'svc-pill ' . ($published ? 'is-on' : 'is-off'),
                                    $published ? 'Click to unpublish (hide from visitors)' : 'Click to publish (make live)'); ?>
                            </div>

                            <!-- Reorder Arrows -->
                            <div class="svc-cell svc-order">
                                <?php echo $first
                                    ? '<span class="svc-arrow is-disabled"><i class="fas fa-chevron-up"></i></span>'
                                    : $act('up', $svc, '<i class="fas fa-chevron-up"></i>', 'svc-arrow', 'Move up'); ?>
                                <?php echo $last
                                    ? '<span class="svc-arrow is-disabled"><i class="fas fa-chevron-down"></i></span>'
                                    : $act('down', $svc, '<i class="fas fa-chevron-down"></i>', 'svc-arrow', 'Move down'); ?>
                            </div>

                            <!-- Actions Toolbar -->
                            <div class="svc-cell svc-actions">
                                <a class="admin-btn admin-btn-sm svc-edit-btn" href="<?php echo URLROOT; ?>/admin/section/<?php echo $pageKey; ?>/banner" title="Edit content sections">
                                    <i class="fas fa-pen-to-square"></i> <span class="svc-lbl-full">Edit Content</span><span class="svc-lbl-short">Edit</span>
                                </a>
                                <a class="admin-btn admin-btn-sm admin-btn-secondary svc-settings-btn" href="<?php echo URLROOT; ?>/admin/section/<?php echo $pageKey; ?>/settings" title="Edit name, card, menu, and SEO">
                                    <i class="fas fa-gear"></i> <span class="svc-lbl-full">Settings &amp; SEO</span><span class="svc-lbl-short">Settings</span>
                                </a>
                                <a class="svc-icon-btn" href="<?php echo URLROOT; ?>/services/<?php echo $e($svc['slug']); ?>" target="_blank" rel="noopener" title="<?php echo $published ? 'View live page' : 'Preview draft (admins only)'; ?>">
                                    <i class="fas fa-arrow-up-right-from-square"></i>
                                </a>
                                <?php echo $act('trash', $svc, '<i class="fas fa-trash-can"></i>', 'svc-icon-btn svc-icon-danger', 'Remove this service', [
                                    'title' => 'Remove “' . $svc['title'] . '”?',
                                    'text' => 'It will disappear from the site, the menu, and the Services page straight away' . ($published ? ' (it is live right now)' : '') . '. Nothing is lost: you can bring it back from “Recently removed”.',
                                    'button' => 'Remove service']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Search Zero Results Notice -->
    <div class="admin-card svc-card" id="svcSearchZeroState" style="display:none;">
        <div class="svc-empty">
            <div class="svc-empty-icon"><i class="fas fa-magnifying-glass"></i></div>
            <h3>No matching services</h3>
            <p>We couldn't find any services matching your search or filter criteria.</p>
            <button type="button" class="admin-btn admin-btn-secondary" id="svcResetFilters" style="margin-top:12px;">
                <i class="fas fa-rotate-left"></i> Reset Filters
            </button>
        </div>
    </div>

    <!-- Trashed / Recently Removed -->
    <?php if (!empty($trashed)): ?>
        <div class="admin-card svc-card svc-removed" id="svcTrashedCard">
            <div class="svc-group-head">
                <div class="svc-group-title">
                    <span class="svc-category-badge is-removed"><i class="fas fa-trash-can"></i></span>
                    <div>
                        <h2>Recently Removed</h2>
                        <p class="svc-group-sub">These pages are hidden from the website. Restore one to draft or permanently delete.</p>
                    </div>
                </div>
                <span class="svc-badge-count"><?php echo count($trashed); ?></span>
            </div>
            <div class="svc-list">
                <?php foreach ($trashed as $svc): ?>
                    <div class="svc-row is-draft svc-row-removed">
                        <div class="svc-cell svc-main">
                            <?php echo $thumb($svc); ?>
                            <div class="svc-main-text">
                                <span class="svc-title"><?php echo $e($svc['title']); ?></span>
                                <span class="svc-slug"><i class="fas fa-clock-rotate-left"></i> Removed on <?php echo $e(date('M j, Y', strtotime($svc['deleted_at']))); ?></span>
                            </div>
                        </div>
                        <div class="svc-cell svc-actions">
                            <?php echo $act('restore', $svc, '<i class="fas fa-rotate-left"></i> Restore as Draft', 'admin-btn admin-btn-sm admin-btn-secondary', 'Bring it back as a draft'); ?>
                            <?php echo $act('purge', $svc, '<i class="fas fa-ban"></i> Delete Permanently', 'admin-btn admin-btn-sm admin-btn-danger-ghost', 'Delete permanently', [
                                'title' => 'Permanently delete “' . $svc['title'] . '”?',
                                'text' => 'This permanently deletes the service and all its content. This action cannot be undone.',
                                'button' => 'Delete forever']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
(function() {
    var searchInput = document.getElementById('svcSearchInput');
    var clearBtn = document.getElementById('svcSearchClear');
    var catPills = document.querySelectorAll('.svc-filter-pill');
    var statusBtns = document.querySelectorAll('.svc-segment-btn');
    var statCards = document.querySelectorAll('.svc-stat-card');
    var rows = document.querySelectorAll('.svc-row:not(.svc-row-removed)');
    var categoryCards = document.querySelectorAll('.svc-category-card');
    var zeroState = document.getElementById('svcSearchZeroState');
    var resetBtn = document.getElementById('svcResetFilters');

    var currentCat = 'all';
    var currentStatus = 'all';
    var searchQuery = '';

    function applyFilters() {
        var visibleCount = 0;

        categoryCards.forEach(function(card) {
            var groupCat = card.getAttribute('data-category-group');
            var cardRows = card.querySelectorAll('.svc-row');
            var cardVisibleCount = 0;

            cardRows.forEach(function(row) {
                var title = row.getAttribute('data-service-title') || '';
                var slug = row.getAttribute('data-service-slug') || '';
                var rowCat = row.getAttribute('data-service-category') || '';
                var rowStatus = row.getAttribute('data-service-status') || '';

                var matchQuery = !searchQuery || title.indexOf(searchQuery) !== -1 || slug.indexOf(searchQuery) !== -1;
                var matchCat = (currentCat === 'all' || rowCat === currentCat);
                var matchStatus = (currentStatus === 'all' || rowStatus === currentStatus);

                if (matchQuery && matchCat && matchStatus) {
                    row.style.display = '';
                    cardVisibleCount++;
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Hide whole category card if it has 0 visible items or category filter doesn't match
            if (cardVisibleCount > 0) {
                card.style.display = '';
                var countEl = card.querySelector('.svc-cat-count');
                if (countEl) countEl.textContent = cardVisibleCount;
            } else {
                card.style.display = 'none';
            }
        });

        if (zeroState) {
            zeroState.style.display = (visibleCount === 0) ? 'block' : 'none';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            searchQuery = this.value.trim().toLowerCase();
            if (clearBtn) clearBtn.style.display = searchQuery ? 'inline-flex' : 'none';
            applyFilters();
        });
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            searchQuery = '';
            clearBtn.style.display = 'none';
            searchInput.focus();
            applyFilters();
        });
    }

    catPills.forEach(function(pill) {
        pill.addEventListener('click', function() {
            catPills.forEach(function(p) { p.classList.remove('is-active'); });
            this.classList.add('is-active');
            currentCat = this.getAttribute('data-cat');
            applyFilters();
        });
    });

    statusBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            statusBtns.forEach(function(b) { b.classList.remove('is-active'); });
            this.classList.add('is-active');
            currentStatus = this.getAttribute('data-status');
            applyFilters();
        });
    });

    statCards.forEach(function(card) {
        card.addEventListener('click', function() {
            var filter = this.getAttribute('data-filter-status');
            if (!filter) return;
            if (filter === 'trashed') {
                var el = document.getElementById('svcTrashedCard');
                if (el) el.scrollIntoView({ behavior: 'smooth' });
                return;
            }
            statusBtns.forEach(function(b) {
                if (b.getAttribute('data-status') === filter) {
                    b.click();
                }
            });
        });
    });

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) { searchInput.value = ''; searchQuery = ''; }
            if (clearBtn) clearBtn.style.display = 'none';
            catPills.forEach(function(p) { p.classList.toggle('is-active', p.getAttribute('data-cat') === 'all'); });
            statusBtns.forEach(function(b) { b.classList.toggle('is-active', b.getAttribute('data-status') === 'all'); });
            currentCat = 'all';
            currentStatus = 'all';
            applyFilters();
        });
    }

    // Copy to clipboard helper
    document.querySelectorAll('.svc-copy-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            var url = this.getAttribute('data-url');
            if (!url) return;
            navigator.clipboard.writeText(url).then(function() {
                var origHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check" style="color:var(--admin-success);"></i>';
                setTimeout(function() { btn.innerHTML = origHtml; }, 1800);
            });
        });
    });
})();
</script>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
