<?php require APPROOT . '/views/admin/inc/header.php'; ?>

<div class="admin-wrap">
    <a href="<?php echo URLROOT; ?>/admin/section/<?php echo $data['navKey']; ?>" class="admin-back-link">&larr; Back to <?php echo htmlspecialchars($data['schema']['label']); ?></a>

    <div class="admin-card">
        <h2 style="margin-top:0;"><?php echo $data['isNew'] ? 'Add New' : 'Edit'; ?> &mdash; <?php echo htmlspecialchars($data['schema']['label']); ?></h2>

        <form method="POST" enctype="multipart/form-data" class="admin-form">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrfToken']); ?>">

            <?php foreach ($data['schema']['fields'] as $f):
                $val = $data['data'][$f['key']] ?? '';
            ?>
                <label for="<?php echo $f['key']; ?>">
                    <?php echo htmlspecialchars($f['label']); ?>
                    <?php if (!empty($f['maxlength'])): ?><span class="admin-field-limit">max <?php echo $f['maxlength']; ?> characters</span><?php endif; ?>
                </label>
                <?php if ($f['type'] === 'textarea'): ?>
                    <textarea id="<?php echo $f['key']; ?>" name="<?php echo $f['key']; ?>"<?php echo !empty($f['maxlength']) ? ' maxlength="' . $f['maxlength'] . '"' : ''; ?>><?php echo htmlspecialchars($val); ?></textarea>
                <?php elseif ($f['type'] === 'image'): ?>
                    <?php require APPROOT . '/views/admin/inc/image_field.php'; ?>
                <?php else: ?>
                    <input type="text" id="<?php echo $f['key']; ?>" name="<?php echo $f['key']; ?>" value="<?php echo htmlspecialchars($val); ?>"<?php echo !empty($f['maxlength']) ? ' maxlength="' . $f['maxlength'] . '"' : ''; ?>>
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="admin-actions">
                <button type="submit" class="admin-btn">Save Entry</button>
                <a href="<?php echo URLROOT; ?>/admin/section/<?php echo $data['navKey']; ?>" class="admin-btn admin-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
