<?php
/**
 * One form field of the CMS, whatever its type. Expects in scope: $f (field definition with
 * 'key', 'label', 'type' and optionally 'maxlength' / 'options'), $val (current value) and
 * $data['mediaAssets'] (for image fields).
 *
 * Types: text, textarea, image, icon, select, toggle (an on/off switch), heading (a divider
 * label that groups the fields below it).
 */
$fid = $f['key'];
$flimit = !empty($f['maxlength']) ? ' maxlength="' . (int)$f['maxlength'] . '"' : '';
?>
<?php if ($f['type'] === 'heading'): ?>
    <div class="admin-field-heading"><?php echo htmlspecialchars($f['label']); ?></div>

<?php elseif ($f['type'] === 'toggle'): ?>
    <label class="admin-toggle-row" for="<?php echo $fid; ?>">
        <input type="checkbox" id="<?php echo $fid; ?>" name="<?php echo $fid; ?>" value="1"<?php echo ($val === '1' || $val === 1 || $val === true) ? ' checked' : ''; ?>>
        <span class="admin-toggle-track"><span class="admin-toggle-knob"></span></span>
        <span class="admin-toggle-text"><?php echo htmlspecialchars($f['label']); ?></span>
    </label>

<?php else: ?>
    <label for="<?php echo $fid; ?>">
        <?php echo htmlspecialchars($f['label']); ?>
        <?php if (!empty($f['maxlength']) && $f['type'] !== 'icon'): ?><span class="admin-field-limit">max <?php echo (int)$f['maxlength']; ?> characters</span><?php endif; ?>
    </label>
    <?php if ($f['type'] === 'textarea'): ?>
        <textarea id="<?php echo $fid; ?>" name="<?php echo $fid; ?>"<?php echo $flimit; ?>><?php echo htmlspecialchars((string)$val); ?></textarea>
    <?php elseif ($f['type'] === 'image'): ?>
        <?php require APPROOT . '/views/admin/inc/image_field.php'; ?>
    <?php elseif ($f['type'] === 'icon'): ?>
        <?php require APPROOT . '/views/admin/inc/icon_field.php'; ?>
    <?php elseif ($f['type'] === 'select'): ?>
        <select id="<?php echo $fid; ?>" name="<?php echo $fid; ?>" class="admin-select">
            <?php foreach ($f['options'] as $optValue => $optLabel): ?>
                <option value="<?php echo htmlspecialchars((string)$optValue); ?>"<?php echo ((string)$val === (string)$optValue) ? ' selected' : ''; ?>><?php echo htmlspecialchars($optLabel); ?></option>
            <?php endforeach; ?>
        </select>
    <?php else: ?>
        <input type="text" id="<?php echo $fid; ?>" name="<?php echo $fid; ?>" value="<?php echo htmlspecialchars((string)$val); ?>"<?php echo $flimit; ?>>
    <?php endif; ?>
<?php endif; ?>
