<?php require APPROOT . '/views/admin/inc/header.php'; ?>

<div class="admin-wrap">
    <div class="admin-page-header">
        <div>
            <h1>Media Library</h1>
            <p class="admin-page-subtitle">Every image used across the site, plus anything you upload here or from an edit form. Pick these from any image field instead of uploading the same file twice.</p>
        </div>
    </div>

    <div class="admin-card">
        <h2 class="admin-card-subtitle" style="margin-bottom:14px;">Upload New Image</h2>

        <?php if (!empty($data['message'])): ?>
            <div class="admin-message"><?php echo htmlspecialchars($data['message']); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="admin-form">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrfToken']); ?>">
            <label for="new_asset" style="margin-top:0;">Choose an image file</label>
            <input type="file" id="new_asset" name="new_asset" accept="image/png,image/jpeg,image/webp,image/gif,image/svg+xml" required>
            <div class="admin-actions">
                <button type="submit" class="admin-btn"><i class="fas fa-upload"></i> Upload</button>
            </div>
        </form>
    </div>

    <div class="admin-card">
        <div class="admin-group-title" style="margin-top:0;"><i class="fas fa-images"></i> <?php echo count($data['assets']); ?> asset(s)</div>

        <?php if (empty($data['assets'])): ?>
            <p class="admin-empty">No assets yet.</p>
        <?php else: ?>
            <div class="admin-media-grid">
                <?php foreach ($data['assets'] as $asset): ?>
                    <div class="admin-media-grid-item">
                        <img src="<?php echo URLROOT . htmlspecialchars($asset['path']); ?>" alt="">
                        <span title="<?php echo htmlspecialchars($asset['path']); ?>"><?php echo htmlspecialchars($asset['original_name'] ?: $asset['path']); ?></span>
                        <form method="POST" action="<?php echo URLROOT; ?>/admin/media-delete/<?php echo $asset['id']; ?>" onsubmit="return confirm('Remove this asset from the library? If it is still used on a live page, that image will stop showing there.');">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrfToken']); ?>">
                            <button type="submit" class="admin-btn admin-btn-sm admin-btn-danger-ghost" style="width:100%; justify-content:center;"><i class="fas fa-trash"></i> Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
