<?php require APPROOT . '/views/admin/inc/header.php';
$form = $data['form'];
$meta = $data['meta'];
$e = function ($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); };
// Inline message + invalid marker for one field, from the server-side validation.
$err = function ($key) use ($data) {
    return isset($data['errors'][$key]) ? '<div class="admin-field-error" data-for="' . $key . '"><i class="fas fa-circle-exclamation"></i> ' . htmlspecialchars($data['errors'][$key]) . '</div>' : '';
};
$inv = function ($key) use ($data) { return isset($data['errors'][$key]) ? ' class="is-invalid" aria-invalid="true"' : ''; };
$back = URLROOT . $data['returnTo'];
$photoUrl = !empty($form['photo']) ? URLROOT . '/uploads/testimonials/' . rawurlencode($form['photo']) : '';
?>

<div class="admin-wrap">
    <a href="<?php echo $e($back); ?>" class="admin-back-link"><i class="fas fa-arrow-left"></i> Back to Feedback</a>

    <div class="admin-page-header">
        <div>
            <h1><?php echo $data['isNew'] ? 'Add Testimonial' : 'Review Feedback'; ?></h1>
            <p class="admin-page-subtitle">
                <?php if ($data['isNew']): ?>
                    Add a testimonial you received elsewhere (e.g. a Google review). Fields marked * are required.
                <?php else: ?>
                    Submitted <?php echo date('M j, Y \a\t g:i A', strtotime($meta->created_at)); ?><?php echo !empty($meta->ip_address) ? ' via the website' : ''; ?>. You may correct spelling and grammar before publishing.
                <?php endif; ?>
            </p>
        </div>
        <?php if (!$data['isNew']): ?>
            <div class="admin-header-actions">
                <form method="POST" action="<?php echo URLROOT; ?>/admin/feedback-delete/<?php echo (int)$data['id']; ?>"
                      data-confirm-title="Delete this feedback?"
                      data-confirm-text="Feedback from <?php echo $e($meta->full_name); ?> will be permanently deleted<?php echo $meta->status === 'Approved' ? ' and removed from the website' : ''; ?>. This cannot be undone."
                      data-confirm-button="Delete feedback">
                    <input type="hidden" name="csrf_token" value="<?php echo $e($data['csrfToken']); ?>">
                    <input type="hidden" name="return" value="<?php echo $e($data['returnTo']); ?>">
                    <button type="submit" class="admin-btn admin-btn-danger-ghost"><i class="fas fa-trash"></i> Delete</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <?php require APPROOT . '/views/admin/inc/flash.php'; ?>

    <form method="POST" enctype="multipart/form-data" class="admin-form" data-validate data-dirty-guard>
        <input type="hidden" name="csrf_token" value="<?php echo $e($data['csrfToken']); ?>">
        <input type="hidden" name="return" value="<?php echo $e($data['returnTo']); ?>">

        <div class="admin-detail-layout">
            <div class="admin-card">
                <h2 class="admin-card-title"><i class="fas fa-user"></i> Testimonial</h2>
                <div class="admin-form-grid">
                    <div class="admin-form-col-full">
                        <label for="full_name">Full name <span class="admin-req">*</span></label>
                        <input type="text" id="full_name" name="full_name" value="<?php echo $e($form['full_name']); ?>" maxlength="100" required data-label="Full name"<?php echo $inv('full_name'); ?>>
                        <?php echo $err('full_name'); ?>
                    </div>
                    <div>
                        <label for="designation">Designation</label>
                        <input type="text" id="designation" name="designation" value="<?php echo $e($form['designation']); ?>" maxlength="100" placeholder="e.g. Founder" data-label="Designation"<?php echo $inv('designation'); ?>>
                        <?php echo $err('designation'); ?>
                    </div>
                    <div>
                        <label for="company">Company</label>
                        <input type="text" id="company" name="company" value="<?php echo $e($form['company']); ?>" maxlength="150" data-label="Company"<?php echo $inv('company'); ?>>
                        <?php echo $err('company'); ?>
                    </div>
                    <div class="admin-form-col-full">
                        <label for="email">Email <span class="admin-field-limit">for your reference only, never shown on the site</span></label>
                        <input type="email" id="email" name="email" value="<?php echo $e($form['email']); ?>" maxlength="255" placeholder="name@company.com" data-label="Email" data-rule="email"<?php echo $inv('email'); ?>>
                        <?php echo $err('email'); ?>
                    </div>
                    <div class="admin-form-col-full">
                        <label for="feedback">Feedback <span class="admin-req">*</span> <span class="admin-field-limit"><span class="admin-char-count" data-for="feedback"><?php echo mb_strlen((string)$form['feedback']); ?></span> / 1000 characters (min. 20)</span></label>
                        <textarea id="feedback" name="feedback" rows="7" minlength="20" maxlength="1000" required data-label="Feedback"<?php echo $inv('feedback'); ?>><?php echo $e($form['feedback']); ?></textarea>
                        <?php echo $err('feedback'); ?>
                    </div>
                </div>
            </div>

            <div class="admin-detail-side">
                <div class="admin-card">
                    <h2 class="admin-card-title"><i class="fas fa-eye"></i> Publishing</h2>
                    <label for="status">Status <span class="admin-req">*</span></label>
                    <select id="status" name="status" class="admin-select" required data-label="Status">
                        <?php foreach (Testimonial::statuses() as $s): ?>
                            <option value="<?php echo $s; ?>"<?php echo $form['status'] === $s ? ' selected' : ''; ?>><?php echo $s; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo $err('status'); ?>
                    <p class="admin-help-text">Only <strong>Approved</strong> feedback appears on the website.</p>

                    <label class="admin-toggle-row" for="show_on_home">
                        <input type="checkbox" id="show_on_home" name="show_on_home" value="1"<?php echo !empty($form['show_on_home']) ? ' checked' : ''; ?>>
                        <span class="admin-toggle-track"><span class="admin-toggle-knob"></span></span>
                        <span class="admin-toggle-text">Feature on Home &amp; About Us</span>
                    </label>
                    <p class="admin-help-text admin-text-warn" id="featureWarn"<?php echo (!empty($form['show_on_home']) && $form['status'] !== 'Approved') ? '' : ' hidden'; ?>><i class="fas fa-triangle-exclamation"></i> Featured feedback only shows once it is Approved.</p>

                    <label for="rating">Rating <span class="admin-req">*</span></label>
                    <div class="admin-rating-input" role="radiogroup" aria-label="Rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="rating<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>"<?php echo (string)$form['rating'] === (string)$i ? ' checked' : ''; ?>>
                            <label for="rating<?php echo $i; ?>" title="<?php echo $i; ?> star<?php echo $i > 1 ? 's' : ''; ?>"><i class="fas fa-star"></i></label>
                        <?php endfor; ?>
                    </div>
                    <?php echo $err('rating'); ?>
                </div>

                <div class="admin-card">
                    <h2 class="admin-card-title"><i class="fas fa-image"></i> Photo</h2>
                    <div class="admin-photo-field">
                        <img src="<?php echo $e($photoUrl); ?>" alt="" class="admin-avatar admin-avatar-lg" id="photoPreview"<?php echo $photoUrl ? '' : ' style="display:none;"'; ?>>
                        <span class="admin-avatar admin-avatar-lg admin-avatar-initial" id="photoPlaceholder"<?php echo $photoUrl ? ' style="display:none;"' : ''; ?>><i class="fas fa-user"></i></span>
                        <div>
                            <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp" data-types="JPG, PNG or WebP" data-preview="photoPreview" data-label="Photo">
                            <p class="admin-help-text">JPG, PNG or WebP, up to 5 MB. Optional.</p>
                            <?php if ($photoUrl): ?>
                                <label class="admin-check"><input type="checkbox" name="remove_photo" value="1" id="removePhoto"> Remove current photo</label>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php echo $err('photo'); ?>
                </div>
            </div>
        </div>

        <div class="admin-form-footer admin-form-footer-sticky">
            <a href="<?php echo $e($back); ?>" class="admin-btn admin-btn-secondary">Cancel</a>
            <button type="submit" class="admin-btn"><i class="fas fa-check"></i> <?php echo $data['isNew'] ? 'Add Testimonial' : 'Save Changes'; ?></button>
        </div>
    </form>
</div>

<script>
(function () {
    var status = document.getElementById('status'), feat = document.getElementById('show_on_home'), warn = document.getElementById('featureWarn');
    function sync() { warn.hidden = !(feat.checked && status.value !== 'Approved'); }
    status.addEventListener('change', sync);
    feat.addEventListener('change', sync);
    var file = document.getElementById('photo');
    file.addEventListener('change', function () {
        if (file.files && file.files[0]) {
            document.getElementById('photoPlaceholder').style.display = 'none';
            var rm = document.getElementById('removePhoto');
            if (rm) rm.checked = false;
        }
    });
})();
</script>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
