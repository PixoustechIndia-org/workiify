<?php require APPROOT . '/views/admin/inc/header.php'; ?>

<div class="admin-wrap">
    <div class="admin-page-header">
        <div>
            <h1>History</h1>
            <p class="admin-page-subtitle">Every section save or item change leaves a snapshot here. Restore any of the last 50 changes across every page.</p>
        </div>
    </div>

    <?php if (!empty($data['flash'])): ?>
        <div class="admin-<?php echo $data['flash']['type'] === 'error' ? 'error' : 'message'; ?>"><?php echo htmlspecialchars($data['flash']['message']); ?></div>
    <?php endif; ?>

    <div class="admin-card">
        <?php if (empty($data['revisions'])): ?>
            <p class="admin-empty">No changes recorded yet. As soon as you save a section or edit an item, it'll show up here.</p>
        <?php else: ?>
            <?php foreach ($data['revisions'] as $rev):
                $icon = 'fa-pen-to-square';
                $actionWord = 'Saved';
                if ($rev['entity_type'] === 'item') { $icon = 'fa-pen'; $actionWord = 'Updated'; }
                if ($rev['entity_type'] === 'item_deleted') { $icon = 'fa-trash'; $actionWord = 'Deleted'; }
            ?>
                <div class="admin-item-row">
                    <span class="admin-link-card-icon" style="flex-shrink:0;"><i class="fas <?php echo $icon; ?>"></i></span>
                    <div class="admin-item-info">
                        <strong><?php echo htmlspecialchars($rev['label']); ?></strong>
                        <span>
                            <?php echo $actionWord; ?> in <?php echo htmlspecialchars($data['pageLabels'][$rev['page']] ?? $rev['page']); ?> &rarr; <?php echo htmlspecialchars($rev['sectionLabel']); ?>
                            &middot; <?php echo htmlspecialchars(date('M j, g:i a', strtotime($rev['created_at']))); ?>
                        </span>
                    </div>
                    <div class="admin-item-row-actions">
                        <form method="POST" action="<?php echo URLROOT; ?>/admin/restore-revision/<?php echo $rev['id']; ?>" onsubmit="return confirm('Restore this version? The current content will be saved as a new history entry first, so you can undo this too.');" style="margin:0;">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrfToken']); ?>">
                            <button type="submit" class="admin-btn admin-btn-sm"><i class="fas fa-clock-rotate-left"></i> Restore</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
