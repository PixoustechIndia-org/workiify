<?php require APPROOT . '/views/admin/inc/header.php';
$f = $data['filters'];
$counts = $data['counts'];
$sources = $data['sources'];
$returnTo = admin_current_path();
$hasFilters = $f['q'] !== '' || $f['source'] !== '' || $f['from'] !== '' || $f['to'] !== '';
?>

<div class="admin-wrap admin-wrap-wide">
    <div class="admin-page-header">
        <div>
            <h1>Enquiries</h1>
            <p class="admin-page-subtitle">Leads from the Home page, Contact page and site-wide popup, plus any you record by hand.</p>
        </div>
        <a href="<?php echo URLROOT; ?>/admin/enquiry-edit/new" class="admin-btn"><i class="fas fa-plus"></i> Add Enquiry</a>
    </div>

    <?php require APPROOT . '/views/admin/inc/flash.php'; ?>

    <div class="admin-tabs" role="tablist" aria-label="Filter by status">
        <a href="<?php echo admin_query_url(['status' => null, 'page' => null]); ?>" class="admin-tab<?php echo $f['status'] === '' ? ' is-active' : ''; ?>">All <span class="admin-tab-count"><?php echo $counts['all']; ?></span></a>
        <?php foreach (Enquiry::statuses() as $s): ?>
            <a href="<?php echo admin_query_url(['status' => $s, 'page' => null]); ?>" class="admin-tab<?php echo $f['status'] === $s ? ' is-active' : ''; ?>"><?php echo $s; ?> <span class="admin-tab-count"><?php echo $counts[$s]; ?></span></a>
        <?php endforeach; ?>
    </div>

    <div class="admin-card admin-card-flush">
        <form method="GET" action="<?php echo URLROOT; ?>/admin/enquiries" class="admin-toolbar" data-validate data-no-lock>
            <?php if ($f['status'] !== ''): ?><input type="hidden" name="status" value="<?php echo htmlspecialchars($f['status']); ?>"><?php endif; ?>
            <?php if ($data['sort'] !== 'date' || $data['dir'] !== 'desc'): ?>
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($data['sort']); ?>">
                <input type="hidden" name="dir" value="<?php echo htmlspecialchars($data['dir']); ?>">
            <?php endif; ?>
            <div class="admin-search">
                <i class="fas fa-magnifying-glass"></i>
                <input type="search" name="q" value="<?php echo htmlspecialchars($f['q']); ?>" placeholder="Search company, email, phone or address" aria-label="Search enquiries" maxlength="100">
            </div>
            <select name="source" class="admin-select admin-select-sm" aria-label="Source" data-autosubmit>
                <option value="">All sources</option>
                <?php foreach ($sources as $k => $label): ?>
                    <option value="<?php echo $k; ?>"<?php echo $f['source'] === $k ? ' selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                <?php endforeach; ?>
            </select>
            <div class="admin-date-range">
                <label for="from" class="sr-only">From date</label>
                <input type="date" id="from" name="from" value="<?php echo htmlspecialchars($f['from']); ?>" max="<?php echo date('Y-m-d'); ?>" data-label="From date" title="From date">
                <span>to</span>
                <label for="to" class="sr-only">To date</label>
                <input type="date" id="to" name="to" value="<?php echo htmlspecialchars($f['to']); ?>" max="<?php echo date('Y-m-d'); ?>" data-after="from" data-label="To date" title="To date">
            </div>
            <button type="submit" class="admin-btn admin-btn-sm"><i class="fas fa-filter"></i> Apply</button>
            <?php if ($hasFilters): ?>
                <a href="<?php echo URLROOT; ?>/admin/enquiries<?php echo $f['status'] !== '' ? '?status=' . urlencode($f['status']) : ''; ?>" class="admin-btn admin-btn-secondary admin-btn-sm"><i class="fas fa-xmark"></i> Clear</a>
            <?php endif; ?>
        </form>

        <div class="admin-table-responsive">
            <table class="admin-table admin-table-stack">
                <thead>
                    <tr>
                        <?php echo admin_sort_th('company', 'Company', $data['sort'], $data['dir']); ?>
                        <th>Contact</th>
                        <th>Requirement</th>
                        <?php echo admin_sort_th('source', 'Source', $data['sort'], $data['dir']); ?>
                        <?php echo admin_sort_th('status', 'Status', $data['sort'], $data['dir']); ?>
                        <?php echo admin_sort_th('date', 'Received', $data['sort'], $data['dir']); ?>
                        <th class="admin-col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['enquiries'])): ?>
                        <tr class="admin-row-empty">
                            <td colspan="7">
                                <div class="admin-empty-state">
                                    <i class="fas <?php echo $hasFilters || $f['status'] !== '' ? 'fa-magnifying-glass' : 'fa-inbox'; ?>"></i>
                                    <strong><?php echo $hasFilters || $f['status'] !== '' ? 'No enquiries match these filters' : 'No enquiries yet'; ?></strong>
                                    <span><?php echo $hasFilters || $f['status'] !== '' ? 'Try a different search or clear the filters.' : 'Leads from the website forms will appear here as soon as they arrive.'; ?></span>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data['enquiries'] as $item):
                            $isWeb = in_array($item->source, Enquiry::webSources(), true);
                        ?>
                            <tr>
                                <td data-label="Company">
                                    <a class="admin-table-title" href="<?php echo URLROOT; ?>/admin/enquiry-view/<?php echo (int)$item->id; ?>"><?php echo htmlspecialchars($item->company_name); ?></a>
                                    <small class="admin-truncate" title="<?php echo htmlspecialchars($item->company_address); ?>"><?php echo htmlspecialchars($item->company_address); ?></small>
                                </td>
                                <td data-label="Contact">
                                    <a href="mailto:<?php echo htmlspecialchars($item->email); ?>" class="admin-link" title="<?php echo htmlspecialchars($item->email); ?>"><?php echo htmlspecialchars($item->email); ?></a><br>
                                    <a href="tel:<?php echo htmlspecialchars($item->contact_no); ?>" class="admin-link-muted"><?php echo htmlspecialchars($item->contact_no); ?></a>
                                </td>
                                <td data-label="Requirement"><?php echo $item->req_type === 'space' ? number_format((int)$item->req_value) . ' sq. ft.' : (int)$item->req_value . ' seat' . ((int)$item->req_value === 1 ? '' : 's'); ?></td>
                                <td data-label="Source">
                                    <span class="admin-chip"><?php echo htmlspecialchars($sources[$item->source] ?? ucfirst($item->source)); ?></span>
                                    <?php if ($isWeb && !$item->email_sent): ?>
                                        <span class="admin-chip is-danger" title="The notification email to your team could not be sent. The enquiry is still saved here."><i class="fas fa-triangle-exclamation"></i> Email not sent</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Status"><span class="admin-status-badge <?php echo strtolower(htmlspecialchars($item->status)); ?>"><?php echo htmlspecialchars($item->status); ?></span></td>
                                <td data-label="Received" class="admin-nowrap"><span title="<?php echo date('M j, Y g:i A', strtotime($item->created_at)); ?>"><?php echo date('M j, Y', strtotime($item->created_at)); ?></span></td>
                                <td class="admin-col-actions">
                                    <div class="admin-row-actions">
                                        <a href="<?php echo URLROOT; ?>/admin/enquiry-view/<?php echo (int)$item->id; ?>" class="admin-icon-btn" title="View" aria-label="View enquiry"><i class="fas fa-eye"></i></a>
                                        <a href="<?php echo URLROOT; ?>/admin/enquiry-edit/<?php echo (int)$item->id; ?>" class="admin-icon-btn" title="Edit" aria-label="Edit enquiry"><i class="fas fa-pen"></i></a>
                                        <form method="POST" action="<?php echo URLROOT; ?>/admin/enquiry-delete/<?php echo (int)$item->id; ?>"
                                              data-confirm-title="Delete this enquiry?"
                                              data-confirm-text="The enquiry from <?php echo htmlspecialchars($item->company_name); ?> will be permanently deleted. This cannot be undone."
                                              data-confirm-button="Delete enquiry">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrfToken']); ?>">
                                            <input type="hidden" name="return" value="<?php echo htmlspecialchars($returnTo); ?>">
                                            <button type="submit" class="admin-icon-btn is-danger" title="Delete" aria-label="Delete enquiry"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php require APPROOT . '/views/admin/inc/pagination.php'; ?>
    </div>
</div>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
