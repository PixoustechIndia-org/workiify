<?php require_once APPROOT . '/views/admin/inc/header.php';
$back = URLROOT . '/admin/section/' . $data['page'] . '/' . $data['navKey'];
?>

<div class="admin-wrap">
    <a href="<?php echo $back; ?>" class="admin-back-link"><i class="fas fa-arrow-left"></i> Back to <?php echo htmlspecialchars($data['schema']['label']); ?></a>

    <div class="admin-page-header">
        <div>
            <h1><?php echo $data['isNew'] ? 'Add Entry' : 'Edit Entry'; ?></h1>
            <p class="admin-page-subtitle"><?php echo htmlspecialchars($data['pageLabel']); ?> &rarr; <?php echo htmlspecialchars($data['schema']['label']); ?></p>
        </div>
    </div>

    <?php require_once APPROOT . '/views/admin/inc/flash.php'; ?>

    <div class="admin-card">
        <form method="POST" enctype="multipart/form-data" class="admin-form" data-validate data-dirty-guard>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrfToken']); ?>">

            <?php foreach ($data['schema']['fields'] as $f):
                $val = $data['data'][$f['key']] ?? '';
                require APPROOT . '/views/admin/inc/field_input.php'; // NOSONAR - included once per field on purpose (require_once would show only the first field)
            endforeach; ?>

            <div class="admin-form-footer">
                <a href="<?php echo $back; ?>" class="admin-btn admin-btn-secondary">Cancel</a>
                <button type="submit" class="admin-btn"><i class="fas fa-check"></i> <?php echo $data['isNew'] ? 'Add Entry' : 'Save Entry'; ?></button>
            </div>
        </form>
    </div>
</div>

<?php require_once APPROOT . '/views/admin/inc/footer.php'; ?>
