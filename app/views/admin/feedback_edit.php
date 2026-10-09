<?php require APPROOT . '/views/admin/inc/header.php'; ?>

<div class="admin-page-header">
    <div>
        <div class="admin-breadcrumbs">
            <a href="<?php echo URLROOT; ?>/admin/feedback">Feedback</a> &gt; Review
        </div>
        <h1 class="admin-page-title">Review Feedback</h1>
    </div>
</div>

<?php if (!empty($data['flash'])): ?>
    <div class="admin-alert admin-alert-<?php echo htmlspecialchars($data['flash']['type']); ?>">
        <?php echo htmlspecialchars($data['flash']['message']); ?>
    </div>
<?php endif; ?>

<div class="admin-card max-w-800">
    <form action="<?php echo URLROOT; ?>/admin/feedback_edit/<?php echo $data['testimonial']->id; ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrf_token']); ?>">
        
        <div class="admin-form-group">
            <label class="admin-form-label">Status</label>
            <select name="status" class="admin-form-control" style="max-width: 200px;">
                <option value="Pending" <?php echo ($data['testimonial']->status === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="Approved" <?php echo ($data['testimonial']->status === 'Approved') ? 'selected' : ''; ?>>Approved</option>
                <option value="Rejected" <?php echo ($data['testimonial']->status === 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
            </select>
            <small class="admin-form-text">Set to Approved to publish on the Testimonials page.</small>
        </div>

        <div class="admin-form-group">
            <label class="admin-checkbox-label" style="display: flex; align-items: center; gap: 8px; margin-top: 10px;">
                <input type="checkbox" name="show_on_home" value="1" <?php echo ($data['testimonial']->show_on_home) ? 'checked' : ''; ?>>
                <span style="font-weight: 500;">Show on Home & About Us</span>
            </label>
            <small class="admin-form-text">Check to feature this testimonial on the main website sections.</small>
        </div>

        <hr style="margin: 30px 0; border: none; border-top: 1px solid var(--border-color);">

        <div class="admin-form-group">
            <label class="admin-form-label">Full Name</label>
            <input type="text" name="full_name" class="admin-form-control" value="<?php echo htmlspecialchars($data['testimonial']->full_name); ?>" required>
        </div>

        <div class="admin-form-group">
            <label class="admin-form-label">Email <small>(for reference only)</small></label>
            <input type="text" class="admin-form-control" value="<?php echo htmlspecialchars($data['testimonial']->email); ?>" disabled>
        </div>

        <div class="admin-form-group" style="display: flex; gap: 20px;">
            <div style="flex: 1;">
                <label class="admin-form-label">Company</label>
                <input type="text" name="company" class="admin-form-control" value="<?php echo htmlspecialchars($data['testimonial']->company); ?>">
            </div>
            <div style="flex: 1;">
                <label class="admin-form-label">Designation</label>
                <input type="text" name="designation" class="admin-form-control" value="<?php echo htmlspecialchars($data['testimonial']->designation); ?>">
            </div>
        </div>

        <div class="admin-form-group">
            <label class="admin-form-label">Rating</label>
            <select name="rating" class="admin-form-control" style="max-width: 150px;">
                <?php for($i=1; $i<=5; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo ($data['testimonial']->rating == $i) ? 'selected' : ''; ?>><?php echo $i; ?> Star<?php echo $i>1?'s':''; ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="admin-form-group">
            <label class="admin-form-label">Feedback</label>
            <textarea name="feedback" class="admin-form-control" rows="6" required><?php echo htmlspecialchars($data['testimonial']->feedback); ?></textarea>
            <small class="admin-form-text">You may edit for spelling and grammar before publishing.</small>
        </div>
        
        <?php if (!empty($data['testimonial']->photo)): ?>
        <div class="admin-form-group">
            <label class="admin-form-label">Uploaded Photo</label>
            <img src="<?php echo URLROOT; ?>/uploads/testimonials/<?php echo htmlspecialchars($data['testimonial']->photo); ?>" style="max-width: 150px; border-radius: 8px;">
        </div>
        <?php endif; ?>

        <div class="admin-form-actions">
            <button type="submit" class="admin-btn admin-btn-primary"><i class="fas fa-save"></i> Save Changes</button>
            <a href="<?php echo URLROOT; ?>/admin/feedback" class="admin-btn admin-btn-outline">Cancel</a>
        </div>
    </form>
</div>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
