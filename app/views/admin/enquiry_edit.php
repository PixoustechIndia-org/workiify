<?php require APPROOT . '/views/admin/inc/header.php';
$form = $data['form'];
$e = function ($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); };
// Inline message + invalid marker for one field, from the server-side validation.
$err = function ($key) use ($data) {
    return isset($data['errors'][$key]) ? '<div class="admin-field-error" data-for="' . $key . '"><i class="fas fa-circle-exclamation"></i> ' . htmlspecialchars($data['errors'][$key]) . '</div>' : '';
};
$inv = function ($key) use ($data) { return isset($data['errors'][$key]) ? ' class="is-invalid" aria-invalid="true"' : ''; };
$back = $data['isNew'] ? URLROOT . '/admin/enquiries' : URLROOT . '/admin/enquiry-view/' . (int)$data['enquiry']->id;
?>

<div class="admin-wrap">
    <a href="<?php echo $back; ?>" class="admin-back-link"><i class="fas fa-arrow-left"></i> <?php echo $data['isNew'] ? 'Back to Enquiries' : 'Back to enquiry'; ?></a>

    <div class="admin-page-header">
        <div>
            <h1><?php echo $data['isNew'] ? 'Add Enquiry' : 'Edit Enquiry'; ?></h1>
            <p class="admin-page-subtitle"><?php echo $data['isNew'] ? 'Record a lead that came in by phone, email or in person.' : 'Correct the details of this enquiry. Fields marked * are required.'; ?></p>
        </div>
    </div>

    <?php require APPROOT . '/views/admin/inc/flash.php'; ?>

    <div class="admin-card">
        <form method="POST" class="admin-form" data-validate data-dirty-guard>
            <input type="hidden" name="csrf_token" value="<?php echo $e($data['csrfToken']); ?>">

            <div class="admin-form-grid">
                <div class="admin-form-col-full">
                    <label for="company_name">Company name <span class="admin-req">*</span></label>
                    <input type="text" id="company_name" name="company_name" value="<?php echo $e($form['company_name']); ?>" maxlength="150" required data-label="Company name"<?php echo $inv('company_name'); ?>>
                    <?php echo $err('company_name'); ?>
                </div>

                <div class="admin-form-col-full">
                    <label for="company_address">Company address <span class="admin-req">*</span></label>
                    <textarea id="company_address" name="company_address" rows="2" maxlength="250" required data-label="Company address"<?php echo $inv('company_address'); ?>><?php echo $e($form['company_address']); ?></textarea>
                    <?php echo $err('company_address'); ?>
                </div>

                <div>
                    <label for="contact_no">Contact number <span class="admin-req">*</span></label>
                    <input type="tel" id="contact_no" name="contact_no" value="<?php echo $e($form['contact_no']); ?>" maxlength="16" placeholder="98765 43210" inputmode="tel" autocomplete="off" required data-label="Contact number" data-rule="mobile"<?php echo $inv('contact_no'); ?>>
                    <?php echo $err('contact_no'); ?>
                </div>

                <div>
                    <label for="email">Email <span class="admin-req">*</span></label>
                    <input type="email" id="email" name="email" value="<?php echo $e($form['email']); ?>" maxlength="150" placeholder="name@company.com" required data-label="Email" data-rule="email"<?php echo $inv('email'); ?>>
                    <?php echo $err('email'); ?>
                </div>

                <div>
                    <label for="req_type">Requirement type <span class="admin-req">*</span></label>
                    <select id="req_type" name="req_type" class="admin-select<?php echo isset($data['errors']['req_type']) ? ' is-invalid' : ''; ?>" required data-label="Requirement type">
                        <option value="seats"<?php echo $form['req_type'] === 'seats' ? ' selected' : ''; ?>>Seats (nos.)</option>
                        <option value="space"<?php echo $form['req_type'] === 'space' ? ' selected' : ''; ?>>Required space (sq. ft.)</option>
                    </select>
                    <?php echo $err('req_type'); ?>
                </div>

                <div>
                    <label for="req_value"><span id="reqValueLabel"><?php echo $form['req_type'] === 'space' ? 'Square feet' : 'Number of seats'; ?></span> <span class="admin-req">*</span></label>
                    <input type="number" id="req_value" name="req_value" value="<?php echo $e($form['req_value']); ?>" min="1" max="1000000" step="1" inputmode="numeric" required data-label="This value"<?php echo $inv('req_value'); ?>>
                    <?php echo $err('req_value'); ?>
                </div>

                <div class="admin-form-col-full">
                    <label for="additional">Additional requirements <span class="admin-field-limit"><span class="admin-char-count" data-for="additional"><?php echo mb_strlen((string)$form['additional']); ?></span> / 1000 characters</span></label>
                    <textarea id="additional" name="additional" rows="4" maxlength="1000" data-label="Additional requirements"<?php echo $inv('additional'); ?>><?php echo $e($form['additional']); ?></textarea>
                    <?php echo $err('additional'); ?>
                </div>

                <div>
                    <label for="source">Source <span class="admin-req">*</span></label>
                    <select id="source" name="source" class="admin-select" required data-label="Source">
                        <?php foreach ($data['sources'] as $k => $label): ?>
                            <option value="<?php echo $k; ?>"<?php echo $form['source'] === $k ? ' selected' : ''; ?>><?php echo $e($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo $err('source'); ?>
                </div>

                <div>
                    <label for="status">Status <span class="admin-req">*</span></label>
                    <select id="status" name="status" class="admin-select" required data-label="Status">
                        <?php foreach (Enquiry::statuses() as $s): ?>
                            <option value="<?php echo $s; ?>"<?php echo $form['status'] === $s ? ' selected' : ''; ?>><?php echo $s; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo $err('status'); ?>
                </div>
            </div>

            <div class="admin-form-footer">
                <a href="<?php echo $back; ?>" class="admin-btn admin-btn-secondary">Cancel</a>
                <button type="submit" class="admin-btn"><i class="fas fa-check"></i> <?php echo $data['isNew'] ? 'Add Enquiry' : 'Update Enquiry'; ?></button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('req_type').addEventListener('change', function () {
    document.getElementById('reqValueLabel').textContent = this.value === 'space' ? 'Square feet' : 'Number of seats';
});
</script>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
