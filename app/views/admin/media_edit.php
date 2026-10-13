<?php require APPROOT . '/views/admin/inc/header.php';
$a = $data['asset'];
$e = function ($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); };
$err = function ($key) use ($data) {
    return isset($data['errors'][$key]) ? '<div class="admin-field-error" data-for="' . $key . '"><i class="fas fa-circle-exclamation"></i> ' . htmlspecialchars($data['errors'][$key]) . '</div>' : '';
};
$back = URLROOT . $data['returnTo'];
$used = count($a['usage']);
$url = URLROOT . $a['path'];
$size = $a['bytes'] >= 1048576 ? number_format($a['bytes'] / 1048576, 1) . ' MB' : max(1, round($a['bytes'] / 1024)) . ' KB';
?>

<div class="admin-wrap">
    <a href="<?php echo $e($back); ?>" class="admin-back-link"><i class="fas fa-arrow-left"></i> Back to Media Library</a>

    <div class="admin-page-header">
        <div>
            <h1>Edit Image</h1>
            <p class="admin-page-subtitle">Rename this image, or replace the picture itself. A replacement shows up everywhere this image is used.</p>
        </div>
        <?php if ($a['isUpload'] && !$used): ?>
            <div class="admin-header-actions">
                <form method="POST" action="<?php echo URLROOT; ?>/admin/media-delete/<?php echo (int)$a['id']; ?>"
                      data-confirm-title="Delete this image?"
                      data-confirm-text="“<?php echo $e($a['name']); ?>” will be deleted from the library and the server. It is not used anywhere on the site."
                      data-confirm-button="Delete image">
                    <input type="hidden" name="csrf_token" value="<?php echo $e($data['csrfToken']); ?>">
                    <input type="hidden" name="return" value="<?php echo $e($data['returnTo']); ?>">
                    <button type="submit" class="admin-btn admin-btn-danger-ghost"><i class="fas fa-trash"></i> Delete</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <?php require APPROOT . '/views/admin/inc/flash.php'; ?>

    <form method="POST" enctype="multipart/form-data" class="admin-form" data-validate data-dirty-guard id="mediaEditForm">
        <input type="hidden" name="csrf_token" value="<?php echo $e($data['csrfToken']); ?>">
        <input type="hidden" name="return" value="<?php echo $e($data['returnTo']); ?>">

        <div class="admin-detail-layout">
            <div class="admin-card">
                <h2 class="admin-card-title"><i class="fas fa-image"></i> Image</h2>
                <div class="admin-media-preview">
                    <img src="<?php echo $e($url); ?>" alt="<?php echo $e($a['name']); ?>" id="mediaPreview">
                    <span class="admin-media-preview-badge" id="newBadge" hidden><i class="fas fa-arrows-rotate"></i> New image (not saved yet)</span>
                </div>

                <label for="name">Name <span class="admin-req">*</span></label>
                <input type="text" id="name" name="name" value="<?php echo $e($data['form']['name']); ?>" maxlength="150" required data-label="Name"<?php echo isset($data['errors']['name']) ? ' class="is-invalid" aria-invalid="true"' : ''; ?>>
                <?php echo $err('name'); ?>
                <p class="admin-help-text">Shown in the Media Library and image pickers. It doesn't change the file address.</p>

                <label for="replace_file">Replace image <span class="admin-field-limit">optional</span></label>
                <input type="file" id="replace_file" name="replace_file" accept="image/png,image/jpeg,image/webp,image/gif" data-types="JPG, PNG, WebP or GIF" data-preview="mediaPreview" data-label="Replacement image"<?php echo isset($data['errors']['replace_file']) ? ' class="is-invalid"' : ''; ?>>
                <?php echo $err('replace_file'); ?>
                <p class="admin-help-text">
                    JPG, PNG, WebP or GIF, up to 5 MB.
                    <?php if ($used): ?>
                        <strong>The new image will replace this one in all <?php echo $used; ?> place<?php echo $used === 1 ? '' : 's'; ?> listed on the right.</strong>
                    <?php endif; ?>
                    <?php if (!$a['isUpload']): ?>
                        The original built-in file is kept on the server.
                    <?php endif; ?>
                </p>
            </div>

            <div class="admin-detail-side">
                <div class="admin-card">
                    <h2 class="admin-card-title"><i class="fas fa-circle-info"></i> Details</h2>
                    <dl class="admin-detail-list admin-detail-list-compact">
                        <dt>Type</dt><dd><?php echo $e($a['ext']); ?> &middot; <?php echo $a['isUpload'] ? 'Upload' : 'Built-in'; ?></dd>
                        <dt>Size</dt><dd><?php echo $a['exists'] ? $size . ($a['width'] ? ' &middot; ' . (int)$a['width'] . ' × ' . (int)$a['height'] . ' px' : '') : '<span class="admin-text-danger">File missing</span>'; ?></dd>
                        <dt>Added</dt><dd><?php echo date('M j, Y g:i A', strtotime($a['uploaded_at'])); ?></dd>
                        <dt>Address</dt><dd><span class="admin-mono admin-break"><?php echo $e($a['path']); ?></span>
                            <button type="button" class="admin-btn admin-btn-secondary admin-btn-sm admin-copy-path" data-path="<?php echo $e($url); ?>" style="margin-top:6px;"><i class="fas fa-copy"></i> Copy URL</button></dd>
                    </dl>
                </div>
                <div class="admin-card">
                    <h2 class="admin-card-title"><i class="fas fa-link"></i> Used in (<?php echo $used; ?>)</h2>
                    <?php if ($used): ?>
                        <ul class="admin-usage-list">
                            <?php foreach ($a['usage'] as $u): ?><li><?php echo $e($u); ?></li><?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="admin-help-text" style="margin:0;">Not used on any page yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="admin-form-footer admin-form-footer-sticky">
            <a href="<?php echo $e($back); ?>" class="admin-btn admin-btn-secondary">Cancel</a>
            <button type="submit" class="admin-btn" id="mediaSaveBtn"><i class="fas fa-check"></i> Update Image</button>
        </div>
    </form>
</div>

<script>
(function () {
    var file = document.getElementById('replace_file');
    var used = <?php echo (int)$used; ?>;
    file.addEventListener('change', function () {
        document.getElementById('newBadge').hidden = !(file.files && file.files[0] && !file.classList.contains('is-invalid'));
    });
    document.querySelectorAll('.admin-copy-path').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var h = btn.innerHTML;
            if (navigator.clipboard) navigator.clipboard.writeText(btn.dataset.path).then(function () { btn.innerHTML = '<i class="fas fa-check"></i> Copied'; setTimeout(function () { btn.innerHTML = h; }, 1500); });
        });
    });
    // replacing an image that is live on the site asks first
    var form = document.getElementById('mediaEditForm');
    if (used > 0) {
        file.addEventListener('change', function () {
            if (file.files && file.files[0]) {
                form.dataset.confirmTitle = 'Replace this image everywhere?';
                form.dataset.confirmText = 'The new image will immediately replace this one in ' + used + ' place' + (used === 1 ? '' : 's') + ' on the website.';
                form.dataset.confirmButton = 'Replace image';
                form.dataset.confirmTone = 'primary';
            } else {
                delete form.dataset.confirmTitle;
            }
        });
    }
})();
</script>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
