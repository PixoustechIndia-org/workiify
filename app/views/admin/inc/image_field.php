<?php
/**
 * Expects in scope: $f (field def with 'key'), $val (current path string),
 * $data['mediaAssets'] (array of ['id','path','original_name']).
 */
?>
<div class="admin-image-field">
    <img src="<?php echo $val ? URLROOT . htmlspecialchars($val) : ''; ?>"
         class="admin-current-img"
         id="preview_<?php echo $f['key']; ?>"
         alt=""
         style="<?php echo $val ? '' : 'display:none;'; ?>">

    <input type="hidden" name="<?php echo $f['key']; ?>_existing" id="existing_<?php echo $f['key']; ?>" value="<?php echo htmlspecialchars($val); ?>">

    <div class="admin-image-field-actions">
        <button type="button" class="admin-btn admin-btn-sm admin-btn-secondary admin-toggle-library" data-target="library_<?php echo $f['key']; ?>">
            <i class="fas fa-photo-film"></i> Browse Media Library
        </button>
    </div>

    <div class="admin-media-picker" id="library_<?php echo $f['key']; ?>" style="display:none;">
        <?php if (empty($data['mediaAssets'])): ?>
            <p class="admin-empty" style="margin:0;">No assets yet — upload one below.</p>
        <?php endif; ?>
        <?php foreach ($data['mediaAssets'] as $asset): ?>
            <div class="admin-media-picker-thumb-wrap">
                <img src="<?php echo URLROOT . htmlspecialchars($asset['path']); ?>"
                     class="admin-media-picker-thumb<?php echo ($asset['path'] === $val) ? ' selected' : ''; ?>"
                     title="<?php echo htmlspecialchars($asset['original_name']); ?>"
                     onclick="adminPickAsset('<?php echo $f['key']; ?>', '<?php echo htmlspecialchars(addslashes($asset['path'])); ?>', this)">
                <span class="admin-media-picker-check"><i class="fas fa-check"></i></span>
            </div>
        <?php endforeach; ?>
    </div>

    <label style="font-weight:400; font-size:0.8rem; color:var(--admin-muted); margin-top:12px;">Or upload a new image (added to the library automatically):</label>
    <input type="file" id="upload_<?php echo $f['key']; ?>" name="<?php echo $f['key']; ?>" accept="image/png,image/jpeg,image/webp,image/gif,image/svg+xml">
</div>
