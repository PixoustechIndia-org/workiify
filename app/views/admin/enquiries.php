<?php require APPROOT . '/views/admin/inc/header.php'; ?>

<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Enquiries</h1>
        <p class="admin-page-subtitle">Leads submitted via the Home page, Contact page, and the site-wide popup</p>
    </div>
</div>

<?php if (!empty($data['flash'])): ?>
    <div class="admin-alert admin-alert-<?php echo htmlspecialchars($data['flash']['type']); ?>">
        <?php echo htmlspecialchars($data['flash']['message']); ?>
    </div>
<?php endif; ?>

<div class="admin-card">
    <div class="admin-table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Contact</th>
                    <th>Requirement</th>
                    <th>Source</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['enquiries'])): ?>
                <tr>
                    <td colspan="8" class="admin-empty-state">No enquiries yet.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($data['enquiries'] as $item): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($item->company_name); ?></strong><br>
                            <small><?php echo htmlspecialchars($item->contact_no); ?></small>
                        </td>
                        <td>
                            <small><?php echo htmlspecialchars($item->email); ?></small>
                        </td>
                        <td>
                            <?php echo $item->req_type === 'space' ? 'Space' : 'Seats'; ?>: <?php echo htmlspecialchars($item->req_value); ?>
                        </td>
                        <td>
                            <span class="admin-status-badge" style="background:#e0e7ff; color:#4338ca;"><?php echo ucfirst(htmlspecialchars($item->source)); ?></span>
                        </td>
                        <td>
                            <?php if ($item->email_sent): ?>
                                <span style="color: #059669;"><i class="fas fa-check-circle"></i> Sent</span>
                            <?php else: ?>
                                <span style="color: #dc2626;"><i class="fas fa-triangle-exclamation"></i> Failed</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="admin-status-badge <?php echo strtolower($item->status); ?>">
                                <?php echo htmlspecialchars($item->status); ?>
                            </span>
                        </td>
                        <td>
                            <small><?php echo date('M d, Y', strtotime($item->created_at)); ?></small>
                        </td>
                        <td>
                            <a href="<?php echo URLROOT; ?>/admin/enquiry_view/<?php echo $item->id; ?>" class="admin-btn admin-btn-outline admin-btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .admin-status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .admin-status-badge.new {
        background-color: #fef3c7;
        color: #d97706;
    }
    .admin-status-badge.contacted {
        background-color: #dbeafe;
        color: #1d4ed8;
    }
    .admin-status-badge.closed {
        background-color: #d1fae5;
        color: #059669;
    }
</style>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
