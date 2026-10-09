<?php require APPROOT . '/views/admin/inc/header.php'; ?>

<div class="admin-page-header">
    <div>
        <div class="admin-breadcrumbs">
            <a href="<?php echo URLROOT; ?>/admin/enquiries">Enquiries</a> &gt; View
        </div>
        <h1 class="admin-page-title">Enquiry from <?php echo htmlspecialchars($data['enquiry']->company_name); ?></h1>
    </div>
</div>

<?php if (!empty($data['flash'])): ?>
    <div class="admin-alert admin-alert-<?php echo htmlspecialchars($data['flash']['type']); ?>">
        <?php echo htmlspecialchars($data['flash']['message']); ?>
    </div>
<?php endif; ?>

<div class="admin-card max-w-800">
    <div class="admin-form-group">
        <label class="admin-form-label">Submitted</label>
        <input type="text" class="admin-form-control" value="<?php echo date('M d, Y \a\t g:i A', strtotime($data['enquiry']->created_at)); ?> &middot; via <?php echo ucfirst(htmlspecialchars($data['enquiry']->source)); ?> page" disabled>
    </div>

    <div class="admin-form-group" style="display: flex; gap: 20px;">
        <div style="flex: 1;">
            <label class="admin-form-label">Company Name</label>
            <input type="text" class="admin-form-control" value="<?php echo htmlspecialchars($data['enquiry']->company_name); ?>" disabled>
        </div>
        <div style="flex: 1;">
            <label class="admin-form-label">Contact Number</label>
            <input type="text" class="admin-form-control" value="<?php echo htmlspecialchars($data['enquiry']->contact_no); ?>" disabled>
        </div>
    </div>

    <div class="admin-form-group">
        <label class="admin-form-label">Company Address</label>
        <input type="text" class="admin-form-control" value="<?php echo htmlspecialchars($data['enquiry']->company_address); ?>" disabled>
    </div>

    <div class="admin-form-group">
        <label class="admin-form-label">Email</label>
        <input type="text" class="admin-form-control" value="<?php echo htmlspecialchars($data['enquiry']->email); ?>" disabled>
    </div>

    <div class="admin-form-group">
        <label class="admin-form-label">Requirement</label>
        <input type="text" class="admin-form-control" value="<?php echo $data['enquiry']->req_type === 'space' ? 'Required space (sq. ft.)' : 'Seats (nos.)'; ?> &mdash; <?php echo htmlspecialchars($data['enquiry']->req_value); ?>" disabled>
    </div>

    <?php if (!empty($data['enquiry']->additional)): ?>
    <div class="admin-form-group">
        <label class="admin-form-label">Additional Requirements</label>
        <textarea class="admin-form-control" rows="4" disabled><?php echo htmlspecialchars($data['enquiry']->additional); ?></textarea>
    </div>
    <?php endif; ?>

    <div class="admin-form-group">
        <label class="admin-form-label">Notification Email</label>
        <input type="text" class="admin-form-control" value="<?php echo $data['enquiry']->email_sent ? 'Sent successfully' : 'Failed to send (enquiry is still saved here)'; ?>" disabled>
    </div>

    <hr style="margin: 30px 0; border: none; border-top: 1px solid var(--border-color);">

    <form action="<?php echo URLROOT; ?>/admin/enquiry_view/<?php echo $data['enquiry']->id; ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrf_token']); ?>">

        <div class="admin-form-group">
            <label class="admin-form-label">Status</label>
            <select name="status" class="admin-form-control" style="max-width: 200px;">
                <option value="New" <?php echo ($data['enquiry']->status === 'New') ? 'selected' : ''; ?>>New</option>
                <option value="Contacted" <?php echo ($data['enquiry']->status === 'Contacted') ? 'selected' : ''; ?>>Contacted</option>
                <option value="Closed" <?php echo ($data['enquiry']->status === 'Closed') ? 'selected' : ''; ?>>Closed</option>
            </select>
        </div>

        <div class="admin-form-actions">
            <button type="submit" class="admin-btn admin-btn-primary"><i class="fas fa-save"></i> Update Status</button>
            <a href="tel:<?php echo htmlspecialchars($data['enquiry']->contact_no); ?>" class="admin-btn admin-btn-outline"><i class="fas fa-phone"></i> Call</a>
            <a href="mailto:<?php echo htmlspecialchars($data['enquiry']->email); ?>" class="admin-btn admin-btn-outline"><i class="fas fa-envelope"></i> Email</a>
            <a href="<?php echo URLROOT; ?>/admin/enquiries" class="admin-btn admin-btn-outline">Back</a>
        </div>
    </form>
</div>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
