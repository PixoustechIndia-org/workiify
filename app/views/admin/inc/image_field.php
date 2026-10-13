<?php
/**
 * One image field. The admin picks ONE of two sources:
 *   - "Choose from Media Library": opens the shared picker dialog (rendered once per page, below)
 *   - "Upload from computer": a new file, uploaded (and added to the library) when the form is saved
 * Expects in scope: $f (field def with 'key' and 'label'), $val (current path string),
 * $data['mediaAssets'] (array of ['id','path','original_name']).
 * Posts: <key>_existing (library path) and <key> (file upload, wins when present).
 */
$ik = $f['key'];
$iname = $val !== '' ? basename($val) : '';
foreach ($data['mediaAssets'] as $asset) {
    if ($asset['path'] === $val && !empty($asset['original_name'])) { $iname = $asset['original_name']; break; }
}
?>
<div class="admin-image-field" data-key="<?php echo $ik; ?>" data-original="<?php echo htmlspecialchars($val); ?>" data-original-name="<?php echo htmlspecialchars($iname); ?>">
    <div class="aif-preview">
        <img src="<?php echo $val ? URLROOT . htmlspecialchars($val) : ''; ?>" id="preview_<?php echo $ik; ?>" alt=""<?php echo $val ? '' : ' hidden'; ?>>
        <div class="aif-empty" id="empty_<?php echo $ik; ?>"<?php echo $val ? ' hidden' : ''; ?>><i class="fas fa-image"></i><span>No image selected</span></div>
    </div>

    <div class="aif-body">
        <div class="aif-status" id="status_<?php echo $ik; ?>">
            <span class="aif-badge is-current"><i class="fas fa-circle-check"></i> <?php echo $val ? 'Current image' : 'Not set'; ?></span>
            <strong class="aif-name" id="name_<?php echo $ik; ?>" title="<?php echo htmlspecialchars($val); ?>"><?php echo $val ? htmlspecialchars($iname) : 'Choose an image below'; ?></strong>
        </div>

        <p class="aif-choose-label">Change image: pick <strong>one</strong> option</p>
        <div class="aif-options">
            <button type="button" class="aif-option" onclick="adminOpenMediaPicker('<?php echo $ik; ?>')">
                <span class="aif-option-icon"><i class="fas fa-photo-film"></i></span>
                <span><strong>Choose from Media Library</strong><small>Use an image already on the site</small></span>
            </button>
            <label class="aif-option" for="upload_<?php echo $ik; ?>">
                <span class="aif-option-icon"><i class="fas fa-upload"></i></span>
                <span><strong>Upload from computer</strong><small>JPG, PNG, WebP or GIF, up to 5 MB</small></span>
            </label>
        </div>
        <input type="file" class="aif-file" id="upload_<?php echo $ik; ?>" name="<?php echo $ik; ?>" accept="image/png,image/jpeg,image/webp,image/gif" data-types="JPG, PNG, WebP or GIF" data-label="<?php echo htmlspecialchars($f['label']); ?>">
        <button type="button" class="aif-undo" id="undo_<?php echo $ik; ?>" hidden onclick="adminUndoImage('<?php echo $ik; ?>')"><i class="fas fa-rotate-left"></i> Undo, keep the current image</button>
    </div>

    <input type="hidden" name="<?php echo $ik; ?>_existing" id="existing_<?php echo $ik; ?>" value="<?php echo htmlspecialchars($val); ?>">
</div>

<?php if (empty($GLOBALS['adminMediaPickerRendered'])): $GLOBALS['adminMediaPickerRendered'] = true; ?>
<!-- Shared "Choose from Media Library" dialog (one per page, used by every image field) -->
<div class="amp-modal" id="adminMediaPicker" hidden role="dialog" aria-modal="true" aria-labelledby="ampTitle">
    <div class="amp-backdrop" data-amp-close></div>
    <div class="amp-box">
        <div class="amp-head">
            <div>
                <h3 id="ampTitle">Choose from Media Library</h3>
                <p>Click an image to use it. <?php echo count($data['mediaAssets']); ?> images available.</p>
            </div>
            <button type="button" class="amp-close" data-amp-close aria-label="Close"><i class="fas fa-xmark"></i></button>
        </div>
        <div class="amp-search">
            <i class="fas fa-magnifying-glass"></i>
            <input type="search" id="ampSearch" placeholder="Search by file name…" autocomplete="off" aria-label="Search images">
        </div>
        <div class="amp-grid" id="ampGrid">
            <?php if (empty($data['mediaAssets'])): ?>
                <p class="admin-empty" style="grid-column:1/-1;">The library is empty. Use “Upload from computer” instead.</p>
            <?php endif; ?>
            <?php foreach ($data['mediaAssets'] as $asset):
                $aname = $asset['original_name'] ?: basename($asset['path']); ?>
                <button type="button" class="amp-item" data-path="<?php echo htmlspecialchars($asset['path']); ?>" data-name="<?php echo htmlspecialchars($aname); ?>" data-search="<?php echo htmlspecialchars(strtolower($aname . ' ' . $asset['path'])); ?>" title="<?php echo htmlspecialchars($aname); ?>">
                    <span class="amp-thumb"><img src="<?php echo URLROOT . htmlspecialchars($asset['path']); ?>" alt="" loading="lazy"></span>
                    <span class="amp-name"><?php echo htmlspecialchars($aname); ?></span>
                    <span class="amp-check"><i class="fas fa-check"></i></span>
                </button>
            <?php endforeach; ?>
            <p class="admin-empty" id="ampNoMatch" hidden style="grid-column:1/-1;">No images match your search.</p>
        </div>
        <div class="amp-foot">
            <span>Don't see the right image? Close this and use <strong>Upload from computer</strong>.</span>
            <button type="button" class="admin-btn admin-btn-secondary admin-btn-sm" data-amp-close>Cancel</button>
        </div>
    </div>
</div>
<?php endif; ?>
