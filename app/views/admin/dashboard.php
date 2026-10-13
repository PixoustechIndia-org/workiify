<?php require APPROOT . '/views/admin/inc/header.php';
$ec = $data['enquiryCounts'];
$fc = $data['feedbackCounts'];
$sc = $data['serviceCounts'];
?>

<div class="admin-wrap admin-wrap-wide">
    <div class="admin-page-header">
        <div>
            <h1>Dashboard</h1>
            <p class="admin-page-subtitle">Welcome back<?php echo !empty($_SESSION['admin_username']) ? ', ' . htmlspecialchars($_SESSION['admin_username']) : ''; ?>. Here's what needs your attention.</p>
        </div>
    </div>

    <?php require APPROOT . '/views/admin/inc/flash.php'; ?>

    <div class="admin-stat-grid">
        <a class="admin-stat-card" href="<?php echo URLROOT; ?>/admin/enquiries?status=New">
            <span class="admin-stat-icon is-warning"><i class="fas fa-envelope-open-text"></i></span>
            <span class="admin-stat-body">
                <span class="admin-stat-num"><?php echo (int)$ec['New']; ?></span>
                <span class="admin-stat-label">New enquiries</span>
                <span class="admin-stat-sub"><?php echo (int)$ec['all']; ?> in total</span>
            </span>
        </a>
        <a class="admin-stat-card" href="<?php echo URLROOT; ?>/admin/feedback?status=Pending">
            <span class="admin-stat-icon is-primary"><i class="fas fa-comment-dots"></i></span>
            <span class="admin-stat-body">
                <span class="admin-stat-num"><?php echo (int)$fc['Pending']; ?></span>
                <span class="admin-stat-label">Feedback to review</span>
                <span class="admin-stat-sub"><?php echo (int)$fc['Approved']; ?> approved</span>
            </span>
        </a>
        <a class="admin-stat-card" href="<?php echo URLROOT; ?>/admin/services">
            <span class="admin-stat-icon is-success"><i class="fas fa-briefcase"></i></span>
            <span class="admin-stat-body">
                <span class="admin-stat-num"><?php echo (int)$sc['published']; ?></span>
                <span class="admin-stat-label">Live services</span>
                <span class="admin-stat-sub"><?php echo (int)$sc['draft']; ?> draft<?php echo $sc['draft'] === 1 ? '' : 's'; ?></span>
            </span>
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title"><i class="fas fa-inbox"></i> Latest enquiries</h2>
            <a href="<?php echo URLROOT; ?>/admin/enquiries" class="admin-btn admin-btn-secondary admin-btn-sm">View all <i class="fas fa-arrow-right"></i></a>
        </div>
        <?php if (empty($data['recentEnquiries'])): ?>
            <p class="admin-empty">No enquiries yet. Leads from the website's forms will appear here.</p>
        <?php else: ?>
            <div class="admin-table-responsive">
                <table class="admin-table admin-table-stack">
                    <thead><tr><th>Company</th><th>Contact</th><th>Status</th><th>Received</th><th class="admin-col-actions"><span class="sr-only">Actions</span></th></tr></thead>
                    <tbody>
                        <?php foreach ($data['recentEnquiries'] as $item): ?>
                            <tr>
                                <td data-label="Company"><strong><?php echo htmlspecialchars($item->company_name); ?></strong></td>
                                <td data-label="Contact"><?php echo htmlspecialchars($item->email); ?><br><small><?php echo htmlspecialchars($item->contact_no); ?></small></td>
                                <td data-label="Status"><span class="admin-status-badge <?php echo strtolower(htmlspecialchars($item->status)); ?>"><?php echo htmlspecialchars($item->status); ?></span></td>
                                <td data-label="Received"><small><?php echo date('M j, Y g:i A', strtotime($item->created_at)); ?></small></td>
                                <td class="admin-col-actions"><a href="<?php echo URLROOT; ?>/admin/enquiry-view/<?php echo (int)$item->id; ?>" class="admin-btn admin-btn-secondary admin-btn-sm"><i class="fas fa-eye"></i> View</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php foreach ($data['pages'] as $page): ?>
        <div class="admin-group-title">
            <i class="fas <?php echo admin_page_icon($page['key']); ?>"></i>
            <?php echo htmlspecialchars($page['label']); ?> Sections
        </div>
        <div class="admin-link-grid">
            <?php foreach ($page['sections'] as $section): ?>
                <a class="admin-link-card" href="<?php echo URLROOT; ?>/admin/section/<?php echo $page['key']; ?>/<?php echo $section['key']; ?>">
                    <span class="admin-link-card-icon"><i class="fas <?php echo admin_section_icon($section); ?>"></i></span>
                    <span>
                        <strong><?php echo htmlspecialchars($section['label']); ?></strong>
                        <span><?php echo !empty($section['items']) && empty($section['fields']) ? 'Add, edit &amp; reorder entries' : (!empty($section['items']) ? 'Edit text, images &amp; entries' : 'Edit text &amp; images'); ?></span>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <div class="admin-group-title"><i class="fas fa-briefcase"></i> Services</div>
    <div class="admin-link-grid">
        <a class="admin-link-card" href="<?php echo URLROOT; ?>/admin/services">
            <span class="admin-link-card-icon"><i class="fas fa-briefcase"></i></span>
            <span>
                <strong>Manage Services</strong>
                <span>Add, remove, publish and order services</span>
            </span>
        </a>
        <a class="admin-link-card" href="<?php echo URLROOT; ?>/admin/service-new">
            <span class="admin-link-card-icon"><i class="fas fa-plus"></i></span>
            <span>
                <strong>Add a Service</strong>
                <span>Create a new service page</span>
            </span>
        </a>
    </div>

    <div class="admin-group-title"><i class="fas fa-layer-group"></i> Modules</div>
    <div class="admin-link-grid">
        <a class="admin-link-card" href="<?php echo URLROOT; ?>/admin/enquiries">
            <span class="admin-link-card-icon"><i class="fas fa-envelope-open-text"></i></span>
            <span>
                <strong>Enquiries</strong>
                <span>Review leads from the Home, Contact &amp; popup forms</span>
            </span>
        </a>
        <a class="admin-link-card" href="<?php echo URLROOT; ?>/admin/feedback">
            <span class="admin-link-card-icon"><i class="fas fa-comment-dots"></i></span>
            <span>
                <strong>Feedback</strong>
                <span>Manage customer feedback and testimonials</span>
            </span>
        </a>
    </div>

    <div class="admin-group-title"><i class="fas fa-box-archive"></i> Assets</div>
    <div class="admin-link-grid">
        <a class="admin-link-card" href="<?php echo URLROOT; ?>/admin/media">
            <span class="admin-link-card-icon"><i class="fas fa-images"></i></span>
            <span>
                <strong>Media Library</strong>
                <span>Upload once, reuse everywhere</span>
            </span>
        </a>
        <a class="admin-link-card" href="<?php echo URLROOT; ?>/admin/history">
            <span class="admin-link-card-icon"><i class="fas fa-clock-rotate-left"></i></span>
            <span>
                <strong>History</strong>
                <span>View and restore previous content revisions</span>
            </span>
        </a>
    </div>
</div>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
