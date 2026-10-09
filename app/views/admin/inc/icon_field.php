<?php
/**
 * Expects in scope: $f (field def with 'key'), $val (current FontAwesome
 * class string, e.g. "fa-wifi").
 */
?>
<div class="admin-icon-field">
    <div class="admin-icon-preview-row">
        <span class="admin-icon-preview-box"><i class="fas <?php echo htmlspecialchars($val ?: 'fa-icons'); ?>" id="iconPreview_<?php echo $f['key']; ?>"></i></span>
        <input type="text" id="<?php echo $f['key']; ?>" name="<?php echo $f['key']; ?>" value="<?php echo htmlspecialchars($val); ?>" placeholder="e.g. fa-wifi" oninput="adminUpdateIconPreview('<?php echo $f['key']; ?>')">
    </div>

    <button type="button" class="admin-btn admin-btn-sm admin-btn-secondary admin-toggle-library" data-target="iconlib_<?php echo $f['key']; ?>">
        <i class="fas fa-shapes"></i> Browse Icons
    </button>

    <div class="admin-icon-picker" id="iconlib_<?php echo $f['key']; ?>" style="display:none;">
        <?php foreach (Home_content_model::iconChoices() as $iconClass): ?>
            <button type="button"
                    class="admin-icon-picker-tile<?php echo ($iconClass === $val) ? ' selected' : ''; ?>"
                    title="<?php echo htmlspecialchars(admin_icon_label($iconClass)); ?>"
                    onclick="adminPickIcon('<?php echo $f['key']; ?>', '<?php echo $iconClass; ?>', this)">
                <i class="fas <?php echo $iconClass; ?>"></i>
            </button>
        <?php endforeach; ?>
    </div>
    <p style="font-weight:400; font-size:0.78rem; color:var(--admin-muted); margin:10px 0 0;">
        Click a picture to use it. Can't find the one you want? <a href="https://fontawesome.com/icons" target="_blank" rel="noopener">Browse more icons on Font Awesome <i class="fas fa-arrow-up-right-from-square" style="font-size:0.7em;"></i></a> and type its name here (e.g. "fa-coffee"). Make sure it's a free, solid-style icon so it matches the rest of the site.
    </p>
</div>
