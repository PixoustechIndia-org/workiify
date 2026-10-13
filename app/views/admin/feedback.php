<?php require_once APPROOT . '/views/admin/inc/header.php';
// Repeated values, defined once
$selectedAttr = ' selected';
$f = $data['filters'];
$counts = $data['counts'];
$returnTo = admin_current_path();
$hasFilters = $f['q'] !== '' || $f['rating'] !== '' || $f['featured'] !== '';
$csrf = htmlspecialchars($data['csrfToken']);
?>

<div class="admin-wrap admin-wrap-wide">
    <div class="admin-page-header">
        <div>
            <h1>Feedback &amp; Testimonials</h1>
            <p class="admin-page-subtitle">Review what visitors submit. Approved feedback appears on the Testimonials page; featured feedback also appears on Home &amp; About Us.</p>
        </div>
        <a href="<?php echo URLROOT; ?>/admin/feedback-edit/new" class="admin-btn"><i class="fas fa-plus"></i> Add Testimonial</a>
    </div>

    <?php require_once APPROOT . '/views/admin/inc/flash.php'; ?>

    <div class="admin-tabs" role="tablist" aria-label="Filter by status">
        <a href="<?php echo admin_query_url(['status' => null, 'page' => null]); ?>" class="admin-tab<?php echo $f['status'] === '' ? ' is-active' : ''; ?>">All <span class="admin-tab-count"><?php echo $counts['all']; ?></span></a>
        <?php foreach (Testimonial::statuses() as $s): ?>
            <a href="<?php echo admin_query_url(['status' => $s, 'page' => null]); ?>" class="admin-tab<?php echo $f['status'] === $s ? ' is-active' : ''; ?>"><?php echo $s; ?> <span class="admin-tab-count"><?php echo $counts[$s]; ?></span></a>
        <?php endforeach; ?>
    </div>

    <div class="admin-card admin-card-flush">
        <form method="GET" action="<?php echo URLROOT; ?>/admin/feedback" class="admin-toolbar" data-no-lock>
            <?php if ($f['status'] !== ''): ?><input type="hidden" name="status" value="<?php echo htmlspecialchars($f['status']); ?>"><?php endif; ?>
            <?php if ($data['sort'] !== 'date' || $data['dir'] !== 'desc'): ?>
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($data['sort']); ?>">
                <input type="hidden" name="dir" value="<?php echo htmlspecialchars($data['dir']); ?>">
            <?php endif; ?>
            <div class="admin-search">
                <i class="fas fa-magnifying-glass"></i>
                <input type="search" name="q" value="<?php echo htmlspecialchars($f['q']); ?>" placeholder="Search name, email, company or text" aria-label="Search feedback" maxlength="100">
            </div>
            <select name="rating" class="admin-select admin-select-sm" aria-label="Rating" data-autosubmit>
                <option value="">Any rating</option>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <option value="<?php echo $i; ?>"<?php echo $f['rating'] === (string)$i ? $selectedAttr : ''; ?>><?php echo $i; ?> star<?php echo $i > 1 ? 's' : ''; ?></option>
                <?php endfor; ?>
            </select>
            <select name="featured" class="admin-select admin-select-sm" aria-label="Featured" data-autosubmit>
                <option value="">Featured or not</option>
                <option value="1"<?php echo $f['featured'] === '1' ? $selectedAttr : ''; ?>>Featured on Home</option>
                <option value="0"<?php echo $f['featured'] === '0' ? $selectedAttr : ''; ?>>Not featured</option>
            </select>
            <button type="submit" class="admin-btn admin-btn-sm"><i class="fas fa-filter"></i> Apply</button>
            <?php if ($hasFilters): ?>
                <a href="<?php echo URLROOT; ?>/admin/feedback<?php echo $f['status'] !== '' ? '?status=' . urlencode($f['status']) : ''; ?>" class="admin-btn admin-btn-secondary admin-btn-sm"><i class="fas fa-xmark"></i> Clear</a>
            <?php endif; ?>
        </form>

        <div class="admin-table-responsive">
            <table class="admin-table admin-table-stack">
                <thead>
                    <tr>
                        <?php echo admin_sort_th('name', 'Name', $data['sort'], $data['dir']); ?>
                        <?php echo admin_sort_th('rating', 'Rating', $data['sort'], $data['dir']); ?>
                        <th>Feedback</th>
                        <?php echo admin_sort_th('status', 'Status', $data['sort'], $data['dir']); ?>
                        <th>Featured</th>
                        <?php echo admin_sort_th('date', 'Date', $data['sort'], $data['dir']); ?>
                        <th class="admin-col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['testimonials'])): ?>
                        <tr class="admin-row-empty">
                            <td colspan="7">
                                <div class="admin-empty-state">
                                    <i class="fas <?php echo $hasFilters || $f['status'] !== '' ? 'fa-magnifying-glass' : 'fa-comment-dots'; ?>"></i>
                                    <strong><?php echo $hasFilters || $f['status'] !== '' ? 'No feedback matches these filters' : 'No feedback yet'; ?></strong>
                                    <span><?php echo $hasFilters || $f['status'] !== '' ? 'Try a different search or clear the filters.' : 'Feedback submitted on the Testimonials page will appear here for review.'; ?></span>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data['testimonials'] as $item): ?>
                            <tr>
                                <td data-label="Name">
                                    <div class="admin-person">
                                        <?php if (!empty($item->photo)): ?>
                                            <img src="<?php echo URLROOT; ?>/uploads/testimonials/<?php echo htmlspecialchars($item->photo); ?>" alt="" class="admin-avatar" loading="lazy">
                                        <?php else: ?>
                                            <span class="admin-avatar admin-avatar-initial"><?php echo htmlspecialchars(mb_strtoupper(mb_substr($item->full_name, 0, 1))); ?></span>
                                        <?php endif; ?>
                                        <span>
                                            <a class="admin-table-title" href="<?php echo URLROOT; ?>/admin/feedback-edit/<?php echo (int)$item->id; ?>"><?php echo htmlspecialchars($item->full_name); ?></a>
                                            <small><?php
                                                $fbSub = trim(($item->designation ? $item->designation . ', ' : '') . ($item->company ?? ''), ', ');
                                                if ($fbSub === '') { $fbSub = $item->email ?: '—'; }
                                                echo htmlspecialchars($fbSub);
                                            ?></small>
                                        </span>
                                    </div>
                                </td>
                                <td data-label="Rating"><span class="admin-stars" title="<?php echo (int)$item->rating; ?> out of 5"><?php for ($i = 1; $i <= 5; $i++) { echo '<i class="fa' . ($i <= $item->rating ? 's' : 'r') . ' fa-star"></i>'; } ?></span></td>
                                <td data-label="Feedback"><span class="admin-clamp" title="<?php echo htmlspecialchars($item->feedback); ?>"><?php echo htmlspecialchars($item->feedback); ?></span></td>
                                <td data-label="Status"><span class="admin-status-badge <?php echo strtolower(htmlspecialchars($item->status)); ?>"><?php echo htmlspecialchars($item->status); ?></span></td>
                                <td data-label="Featured">
                                    <?php if ($item->show_on_home): ?>
                                        <span class="admin-text-success" title="<?php echo $item->status === 'Approved' ? 'Shown on Home & About Us' : 'Will show on Home & About Us once approved'; ?>"><i class="fas fa-star"></i> Yes</span>
                                    <?php else: ?>
                                        <span class="admin-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Date" class="admin-nowrap"><?php echo date('M j, Y', strtotime($item->created_at)); ?></td>
                                <td class="admin-col-actions">
                                    <div class="admin-row-actions">
                                        <?php foreach (['Approved' => ['fa-check', 'Approve', 'is-success'], 'Rejected' => ['fa-ban', 'Reject', 'is-warn']] as $st => $cfg):
                                            if ($item->status === $st) { continue; } ?>
                                            <form method="POST" action="<?php echo URLROOT; ?>/admin/feedback-status/<?php echo (int)$item->id; ?>/<?php echo $st; ?>">
                                                <input type="hidden" name="csrf_token" value="<?php echo $csrf; ?>">
                                                <input type="hidden" name="return" value="<?php echo htmlspecialchars($returnTo); ?>">
                                                <button type="submit" class="admin-icon-btn <?php echo $cfg[2]; ?>" title="<?php echo $cfg[1]; ?>" aria-label="<?php echo $cfg[1]; ?>"><i class="fas <?php echo $cfg[0]; ?>"></i></button>
                                            </form>
                                        <?php endforeach; ?>
                                        <a href="<?php echo URLROOT; ?>/admin/feedback-edit/<?php echo (int)$item->id; ?>" class="admin-icon-btn" title="View / Edit" aria-label="View or edit feedback"><i class="fas fa-pen"></i></a>
                                        <form method="POST" action="<?php echo URLROOT; ?>/admin/feedback-delete/<?php echo (int)$item->id; ?>"
                                              data-confirm-title="Delete this feedback?"
                                              data-confirm-text="Feedback from <?php echo htmlspecialchars($item->full_name); ?> will be permanently deleted<?php echo $item->status === 'Approved' ? ' and removed from the website' : ''; ?>. This cannot be undone."
                                              data-confirm-button="Delete feedback">
                                            <input type="hidden" name="csrf_token" value="<?php echo $csrf; ?>">
                                            <input type="hidden" name="return" value="<?php echo htmlspecialchars($returnTo); ?>">
                                            <button type="submit" class="admin-icon-btn is-danger" title="Delete" aria-label="Delete feedback"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php require_once APPROOT . '/views/admin/inc/pagination.php'; ?>
    </div>
</div>

<?php require_once APPROOT . '/views/admin/inc/footer.php'; ?>
