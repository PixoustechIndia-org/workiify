<?php require APPROOT . '/views/admin/inc/header.php'; ?>

<div class="admin-wrap">
    <div class="admin-page-header">
        <div>
            <h1>Dashboard</h1>
            <p class="admin-page-subtitle">Pick a section from the sidebar, or jump in below.</p>
        </div>
    </div>

    <?php foreach ($data['pages'] as $page): ?>
        <div class="admin-group-title">
            <i class="fas <?php echo admin_page_icon($page['key']); ?>"></i>
            <?php echo htmlspecialchars($page['label']); ?> Sections
        </div>
        <div class="admin-link-grid">
            <?php foreach ($page['sections'] as $section): ?>
                <a class="admin-link-card" href="<?php echo URLROOT; ?>/admin/section/<?php echo $page['key']; ?>/<?php echo $section['key']; ?>">
                    <span class="admin-link-card-icon"><i class="fas <?php echo admin_section_icon($section); ?>"></i></span>
                    <span>
                        <strong><?php echo htmlspecialchars($section['label']); ?></strong>
                        <span>Edit text, images &amp; entries</span>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <div class="admin-group-title"><i class="fas fa-box-archive"></i> Assets</div>
    <div class="admin-link-grid">
        <a class="admin-link-card" href="<?php echo URLROOT; ?>/admin/media">
            <span class="admin-link-card-icon"><i class="fas fa-images"></i></span>
            <span>
                <strong>Media Library</strong>
                <span>Upload once, reuse everywhere</span>
            </span>
        </a>
    </div>
</div>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
