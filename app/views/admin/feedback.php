<?php require APPROOT . '/views/admin/inc/header.php'; ?>

<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Feedback & Testimonials</h1>
        <p class="admin-page-subtitle">Manage customer feedback and publish testimonials</p>
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
                    <th>Name</th>
                    <th>Rating</th>
                    <th>Feedback</th>
                    <th>Status</th>
                    <th>Featured</th>
                    <th>Date</th>
                    <th style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['testimonials'])): ?>
                <tr>
                    <td colspan="7" class="admin-empty-state">No feedback found.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($data['testimonials'] as $item): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($item->full_name); ?></strong><br>
                            <small><?php echo htmlspecialchars($item->email); ?></small>
                        </td>
                        <td>
                            <div style="color: #fbbf24;">
                                <?php for($i=0; $i<$item->rating; $i++) echo '<i class="fas fa-star"></i>'; ?>
                            </div>
                        </td>
                        <td>
                            <div style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?php echo htmlspecialchars($item->feedback); ?>
                            </div>
                        </td>
                        <td>
                            <span class="admin-status-badge <?php echo strtolower($item->status); ?>">
                                <?php echo htmlspecialchars($item->status); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($item->show_on_home): ?>
                                <span style="color: var(--primary-color);"><i class="fas fa-check-circle"></i> Yes</span>
                            <?php else: ?>
                                <span style="color: #9ca3af;">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small><?php echo date('M d, Y', strtotime($item->created_at)); ?></small>
                        </td>
                        <td>
                            <a href="<?php echo URLROOT; ?>/admin/feedback_edit/<?php echo $item->id; ?>" class="admin-btn admin-btn-outline admin-btn-sm">
                                <i class="fas fa-pen-to-square"></i> Review
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
    .admin-status-badge.pending {
        background-color: #fef3c7;
        color: #d97706;
    }
    .admin-status-badge.approved {
        background-color: #d1fae5;
        color: #059669;
    }
    .admin-status-badge.rejected {
        background-color: #fee2e2;
        color: #dc2626;
    }
</style>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
