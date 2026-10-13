<?php require APPROOT . '/views/admin/inc/header.php';
$q = $data['enquiry'];
$isWeb = in_array($q->source, Enquiry::webSources(), true);
?>

<div class="admin-wrap">
    <a href="<?php echo URLROOT; ?>/admin/enquiries" class="admin-back-link"><i class="fas fa-arrow-left"></i> Back to Enquiries</a>

    <div class="admin-page-header">
        <div>
            <h1><?php echo htmlspecialchars($q->company_name); ?></h1>
            <p class="admin-page-subtitle">
                Enquiry #<?php echo (int)$q->id; ?> &middot; received <?php echo date('M j, Y \a\t g:i A', strtotime($q->created_at)); ?>
                &middot; <?php echo htmlspecialchars($data['sources'][$q->source] ?? ucfirst($q->source)); ?>
            </p>
        </div>
        <div class="admin-header-actions">
            <a href="<?php echo URLROOT; ?>/admin/enquiry-edit/<?php echo (int)$q->id; ?>" class="admin-btn admin-btn-secondary"><i class="fas fa-pen"></i> Edit</a>
            <form method="POST" action="<?php echo URLROOT; ?>/admin/enquiry-delete/<?php echo (int)$q->id; ?>"
                  data-confirm-title="Delete this enquiry?"
                  data-confirm-text="The enquiry from <?php echo htmlspecialchars($q->company_name); ?> will be permanently deleted. This cannot be undone."
                  data-confirm-button="Delete enquiry">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrfToken']); ?>">
                <button type="submit" class="admin-btn admin-btn-danger-ghost"><i class="fas fa-trash"></i> Delete</button>
            </form>
        </div>
    </div>

    <?php require APPROOT . '/views/admin/inc/flash.php'; ?>

    <div class="admin-detail-layout">
        <div class="admin-card">
            <h2 class="admin-card-title"><i class="fas fa-building"></i> Enquiry details</h2>
            <dl class="admin-detail-list">
                <dt>Company name</dt>
                <dd><?php echo htmlspecialchars($q->company_name); ?></dd>
                <dt>Company address</dt>
                <dd><?php echo nl2br(htmlspecialchars($q->company_address)); ?></dd>
                <dt>Contact number</dt>
                <dd><a href="tel:<?php echo htmlspecialchars($q->contact_no); ?>" class="admin-link"><?php echo htmlspecialchars($q->contact_no); ?></a></dd>
                <dt>Email</dt>
                <dd><a href="mailto:<?php echo htmlspecialchars($q->email); ?>" class="admin-link"><?php echo htmlspecialchars($q->email); ?></a></dd>
                <dt>Requirement</dt>
                <dd><?php echo $q->req_type === 'space' ? 'Required space: <strong>' . number_format((int)$q->req_value) . ' sq. ft.</strong>' : 'Seats: <strong>' . (int)$q->req_value . '</strong>'; ?></dd>
                <dt>Additional requirements</dt>
                <dd><?php echo $q->additional !== null && trim($q->additional) !== '' ? nl2br(htmlspecialchars($q->additional)) : '<span class="admin-muted">None given</span>'; ?></dd>
                <?php if ($isWeb): ?>
                    <dt>Team notification email</dt>
                    <dd><?php echo $q->email_sent
                        ? '<span class="admin-text-success"><i class="fas fa-circle-check"></i> Sent</span>'
                        : '<span class="admin-text-danger"><i class="fas fa-triangle-exclamation"></i> Could not be sent (the enquiry is still saved here)</span>'; ?></dd>
                <?php endif; ?>
            </dl>
        </div>

        <div class="admin-card admin-detail-side">
            <h2 class="admin-card-title"><i class="fas fa-list-check"></i> Follow-up</h2>
            <p class="admin-help-text" style="margin-top:0;">Current status: <span class="admin-status-badge <?php echo strtolower(htmlspecialchars($q->status)); ?>"><?php echo htmlspecialchars($q->status); ?></span></p>
            <form action="<?php echo URLROOT; ?>/admin/enquiry-view/<?php echo (int)$q->id; ?>" method="POST" class="admin-form">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrfToken']); ?>">
                <label for="status">Change status</label>
                <select id="status" name="status" class="admin-select">
                    <?php foreach (Enquiry::statuses() as $s): ?>
                        <option value="<?php echo $s; ?>"<?php echo $q->status === $s ? ' selected' : ''; ?>><?php echo $s; ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="admin-btn admin-btn-block" style="margin-top:12px;"><i class="fas fa-check"></i> Update Status</button>
            </form>
            <div class="admin-divider"></div>
            <div class="admin-stack-actions">
                <a href="tel:<?php echo htmlspecialchars($q->contact_no); ?>" class="admin-btn admin-btn-secondary admin-btn-block"><i class="fas fa-phone"></i> Call <?php echo htmlspecialchars($q->contact_no); ?></a>
                <a href="mailto:<?php echo htmlspecialchars($q->email); ?>?subject=<?php echo rawurlencode('Your enquiry with Workiify'); ?>" class="admin-btn admin-btn-secondary admin-btn-block"><i class="fas fa-envelope"></i> Send an email</a>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
