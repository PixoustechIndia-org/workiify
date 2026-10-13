<?php require APPROOT . '/views/admin/inc/header.php';
$returnTo = admin_current_path();
?>

<div class="admin-wrap">
    <div class="admin-page-header">
        <div>
            <h1>History</h1>
            <p class="admin-page-subtitle">Every section save and entry change leaves a snapshot here (the last 50 per page are kept). Restore one to undo a change.</p>
        </div>
    </div>

    <?php require APPROOT . '/views/admin/inc/flash.php'; ?>

    <div class="admin-card admin-card-flush">
        <form method="GET" action="<?php echo URLROOT; ?>/admin/history" class="admin-toolbar" data-no-lock>
            <label for="p" class="admin-toolbar-label">Page</label>
            <select id="p" name="p" class="admin-select admin-select-sm" data-autosubmit>
                <option value="">All pages</option>
                <?php foreach ($data['pageLabels'] as $key => $label): ?>
                    <option value="<?php echo htmlspecialchars($key); ?>"<?php echo $data['pageFilter'] === (string)$key ? ' selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                <?php endforeach; ?>
            </select>
            <noscript><button type="submit" class="admin-btn admin-btn-sm">Apply</button></noscript>
        </form>

        <div class="admin-list-pad">
            <?php if (empty($data['revisions'])): ?>
                <p class="admin-empty">No changes recorded<?php echo $data['pageFilter'] !== '' ? ' for this page' : ' yet'; ?>. As soon as you save a section or edit an entry, it'll show up here.</p>
            <?php else: ?>
                <?php foreach ($data['revisions'] as $rev):
                    $icon = 'fa-pen-to-square';
                    $actionWord = 'Saved';
                    $tone = '';
                    if ($rev['entity_type'] === 'item') { $icon = 'fa-pen'; $actionWord = 'Updated'; }
                    if ($rev['entity_type'] === 'item_deleted') { $icon = 'fa-trash'; $actionWord = 'Deleted'; $tone = ' is-danger'; }
                    $pageName = $data['pageLabels'][$rev['page']] ?? $rev['page'];
                ?>
                    <div class="admin-item-row">
                        <span class="admin-link-card-icon<?php echo $tone; ?>" style="flex-shrink:0;"><i class="fas <?php echo $icon; ?>"></i></span>
                        <div class="admin-item-info">
                            <strong><?php echo htmlspecialchars($rev['label']); ?></strong>
                            <span>
                                <?php echo $actionWord; ?> in <?php echo htmlspecialchars($pageName); ?> &rarr; <?php echo htmlspecialchars($rev['sectionLabel']); ?>
                                &middot; <?php echo htmlspecialchars(date('M j, Y g:i a', strtotime($rev['created_at']))); ?>
                            </span>
                        </div>
                        <div class="admin-item-row-actions">
                            <form method="POST" action="<?php echo URLROOT; ?>/admin/restore-revision/<?php echo (int)$rev['id']; ?>"
                                  data-confirm-title="Restore this version?"
                                  data-confirm-text="<?php echo $rev['entity_type'] === 'item_deleted' ? 'The deleted entry “' . htmlspecialchars($rev['label']) . '” will be added back to ' . htmlspecialchars($pageName) . '.' : 'The content will go back to how it was before this change. The current content is saved to History first, so you can undo this too.'; ?>"
                                  data-confirm-button="Restore" data-confirm-tone="primary">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrfToken']); ?>">
                                <input type="hidden" name="return" value="<?php echo htmlspecialchars($returnTo); ?>">
                                <button type="submit" class="admin-btn admin-btn-secondary admin-btn-sm"><i class="fas fa-clock-rotate-left"></i> Restore</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php require APPROOT . '/views/admin/inc/pagination.php'; ?>
    </div>
</div>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
