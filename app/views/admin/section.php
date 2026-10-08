<?php require APPROOT . '/views/admin/inc/header.php'; ?>

<div class="admin-wrap">
    <h1 style="margin-top:0;"><?php echo htmlspecialchars($data['sectionLabel']); ?></h1>

    <?php if (!empty($data['message'])): ?>
        <div class="admin-message"><?php echo htmlspecialchars($data['message']); ?></div>
    <?php endif; ?>
    <?php if (!empty($data['flash'])): ?>
        <div class="admin-<?php echo $data['flash']['type'] === 'error' ? 'error' : 'message'; ?>"><?php echo htmlspecialchars($data['flash']['message']); ?></div>
    <?php endif; ?>

    <?php if ($data['fieldsData']): ?>
        <div class="admin-card">
            <h2 class="admin-card-subtitle">Section Text &amp; Images</h2>
            <form method="POST" enctype="multipart/form-data" class="admin-form">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrfToken']); ?>">

                <?php foreach ($data['fieldsData']['schema']['fields'] as $f):
                    $val = $data['fieldsData']['values'][$f['key']] ?? '';
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
                    <button type="submit" class="admin-btn">Save Changes</button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <?php if ($data['itemsData']):
        $schema = $data['itemsData']['schema'];
        $count = count($data['itemsData']['items']);
        $min = $schema['minItems'] ?? 0;
        $max = $schema['maxItems'] ?? null;
        $atMax = $max !== null && $count >= $max;
        $atMin = $count <= $min;
        $locked = $max !== null && $min === $max;
    ?>
        <div class="admin-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
                <h2 class="admin-card-subtitle" style="margin:0;"><?php echo htmlspecialchars($schema['label']); ?></h2>
                <?php if ($atMax): ?>
                    <span class="admin-btn admin-btn-sm admin-btn-secondary" style="opacity:0.6; cursor:not-allowed;" title="Maximum reached">+ Add New</span>
                <?php else: ?>
                    <a href="<?php echo URLROOT; ?>/admin/item-edit/<?php echo $data['itemsData']['sectionKey']; ?>/new" class="admin-btn admin-btn-sm">+ Add New</a>
                <?php endif; ?>
            </div>

            <p class="admin-limit-note">
                <?php if ($locked): ?>
                    <i class="fas fa-lock"></i> This section is built for exactly <?php echo $max; ?> entries so the layout stays aligned — entries can be edited but not added or removed.
                <?php else: ?>
                    <?php echo $count; ?><?php echo $max !== null ? ' / ' . $max : ''; ?> entries.
                    <?php if ($atMax): ?> Maximum reached — remove one to add another.<?php endif; ?>
                    <?php if ($min > 0): ?> (at least <?php echo $min; ?> required)<?php endif; ?>
                <?php endif; ?>
            </p>

            <?php
                $fieldKeys = array_column($schema['fields'], 'key');
                $imageKey = null;
                foreach ($schema['fields'] as $f) { if ($f['type'] === 'image') { $imageKey = $f['key']; break; } }
                $titleKey = $fieldKeys[0];
                if ($titleKey === $imageKey && isset($fieldKeys[1])) { $titleKey = $fieldKeys[1]; }
            ?>

            <?php if (empty($data['itemsData']['items'])): ?>
                <p class="admin-empty">No entries yet. Click "+ Add New" to create one.</p>
            <?php else: ?>
                <?php foreach ($data['itemsData']['items'] as $item):
                    $d = $item['data'];
                    $title = $d[$titleKey] ?? ('Item #' . $item['id']);
                    $sub = '';
                    foreach ($fieldKeys as $fk) {
                        if ($fk !== $titleKey && $fk !== $imageKey && !empty($d[$fk])) { $sub = $d[$fk]; break; }
                    }
                ?>
                    <div class="admin-item-row">
                        <?php if ($imageKey && !empty($d[$imageKey])): ?>
                            <img src="<?php echo URLROOT . htmlspecialchars($d[$imageKey]); ?>" class="admin-item-thumb" alt="">
                        <?php endif; ?>
                        <div class="admin-item-info">
                            <strong><?php echo htmlspecialchars($title); ?></strong>
                            <?php if ($sub): ?><span><?php echo htmlspecialchars($sub); ?></span><?php endif; ?>
                        </div>
                        <div class="admin-item-row-actions">
                            <a href="<?php echo URLROOT; ?>/admin/item-edit/<?php echo $data['itemsData']['sectionKey']; ?>/<?php echo $item['id']; ?>" class="admin-btn admin-btn-sm">Edit</a>
                            <?php if ($atMin): ?>
                                <span class="admin-btn admin-btn-sm admin-btn-danger" style="opacity:0.5; cursor:not-allowed;" title="At least <?php echo $min; ?> required">Delete</span>
                            <?php else: ?>
                                <form method="POST" action="<?php echo URLROOT; ?>/admin/item-delete/<?php echo $data['itemsData']['sectionKey']; ?>/<?php echo $item['id']; ?>" onsubmit="return confirm('Delete this entry?');" style="margin:0;">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrfToken']); ?>">
                                    <button type="submit" class="admin-btn admin-btn-sm admin-btn-danger">Delete</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
