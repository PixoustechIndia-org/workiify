<?php require APPROOT . '/views/admin/inc/header.php'; ?>

<div class="admin-wrap">
    <h1 style="margin-top:0;">Dashboard</h1>
    <p style="color:#64748b;">Pick a section from the sidebar, or jump in below.</p>

    <div class="admin-group-title" style="margin-top:0;">Home Page Sections</div>
    <div class="admin-link-grid">
        <?php foreach ($data['navSections'] as $section): ?>
            <a class="admin-link-card" href="<?php echo URLROOT; ?>/admin/section/<?php echo $section['key']; ?>">
                <strong><?php echo htmlspecialchars($section['label']); ?></strong>
                <span>Edit text, images &amp; entries</span>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="admin-group-title">Assets</div>
    <div class="admin-link-grid">
        <a class="admin-link-card" href="<?php echo URLROOT; ?>/admin/media">
            <strong>Media Library</strong>
            <span>Upload once, reuse everywhere</span>
        </a>
    </div>
</div>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
