<?php
require APPROOT . '/views/admin/inc/header.php';

$e = function ($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); };
$cats = $data['categories'];
$csrf = $data['csrfToken'];

// One small POST form per action (CSRF protected; asks first when it removes something).
$act = function ($action, $svc, $inner, $class = '', $title = '', $confirm = null) use ($e, $csrf) {
    $attrs = $confirm ? ' data-confirm-title="' . $e($confirm['title']) . '" data-confirm-text="' . $e($confirm['text']) . '" data-confirm-button="' . $e($confirm['button']) . '"' : '';
    return '<form method="POST" action="' . URLROOT . '/admin/service-action/' . $action . '/' . (int)$svc['id'] . '" class="svc-inline-form"' . $attrs . '>'
        . '<input type="hidden" name="csrf_token" value="' . $e($csrf) . '">'
        . '<input type="hidden" name="return" value="/admin/services">'
        . '<button type="submit" class="' . $e($class) . '" title="' . $e($title) . '">' . $inner . '</button></form>';
};
$thumb = function ($svc) use ($e) {
    return $svc['card_image'] !== ''
        ? '<img class="svc-thumb" src="' . $e(URLROOT . $svc['card_image']) . '" alt="" loading="lazy">'
        : '<span class="svc-thumb svc-thumb-empty"><i class="fas fa-image"></i></span>';
};

$groups = [];
foreach ($data['services'] as $svc) $groups[$svc['category']][] = $svc;
?>

<div class="admin-wrap svc-wrap">
    <div class="admin-page-header">
        <div>
            <h1>Services</h1>
            <p class="admin-page-subtitle">Every service is a page built from the same template. Click <strong>Edit content</strong> to change anything on it. Changes go live as soon as you save.</p>
        </div>
        <a href="<?php echo URLROOT; ?>/admin/service-new" class="admin-btn"><i class="fas fa-plus"></i> Add Service</a>
    </div>

    <?php if (!empty($data['flash'])): ?>
        <div class="admin-<?php echo $data['flash']['type'] === 'error' ? 'error' : 'message'; ?>"><?php echo $e($data['flash']['message']); ?></div>
    <?php endif; ?>
    <?php if (!empty($data['offline'])): ?>
        <div class="admin-error"><strong>The services table is unavailable.</strong> The public site is showing built-in defaults and nothing here can be saved until the database connection (or permission to create tables) is fixed. Details are in the PHP error log.</div>
    <?php endif; ?>

    <?php if (empty($data['services'])): ?>
        <div class="admin-card svc-card">
            <p class="svc-empty"><i class="fas fa-briefcase"></i><br>No services yet.<br><a href="<?php echo URLROOT; ?>/admin/service-new" class="admin-btn admin-btn-sm" style="margin-top:14px;"><i class="fas fa-plus"></i> Add your first service</a></p>
        </div>
    <?php endif; ?>

    <?php foreach ($cats as $key => $meta): if (empty($groups[$key])) continue; $items = $groups[$key]; ?>
        <div class="admin-card svc-card">
            <div class="svc-group-head"><h2><?php echo $e($meta['label']); ?></h2><span><?php echo count($items); ?> service<?php echo count($items) === 1 ? '' : 's'; ?></span></div>
            <?php foreach ($items as $i => $svc):
                $published = $svc['status'] === 'published';
                $first = $i === 0; $last = $i === count($items) - 1; ?>
                <div class="svc-row<?php echo $published ? '' : ' is-draft'; ?>">
                    <div class="svc-cell svc-main">
                        <?php echo $thumb($svc); ?>
                        <div class="svc-main-text">
                            <a class="svc-title" href="<?php echo URLROOT; ?>/admin/section/<?php echo Service_model::pageKey($svc['id']); ?>/banner"><?php echo $e($svc['title']); ?></a>
                            <span class="svc-slug">/services/<?php echo $e($svc['slug']); ?></span>
                        </div>
                    </div>
                    <div class="svc-cell svc-status">
                        <?php echo $act($published ? 'unpublish' : 'publish', $svc, '<span class="svc-dot"></span>' . ($published ? 'Published' : 'Draft'),
                            'svc-pill ' . ($published ? 'is-on' : 'is-off'), $published ? 'Click to unpublish (hide from visitors)' : 'Click to publish'); ?>
                    </div>
                    <div class="svc-cell svc-order">
                        <?php echo $first ? '<span class="svc-arrow is-disabled"><i class="fas fa-chevron-up"></i></span>' : $act('up', $svc, '<i class="fas fa-chevron-up"></i>', 'svc-arrow', 'Move up'); ?>
                        <?php echo $last ? '<span class="svc-arrow is-disabled"><i class="fas fa-chevron-down"></i></span>' : $act('down', $svc, '<i class="fas fa-chevron-down"></i>', 'svc-arrow', 'Move down'); ?>
                    </div>
                    <div class="svc-cell svc-actions">
                        <a class="admin-btn admin-btn-sm" href="<?php echo URLROOT; ?>/admin/section/<?php echo Service_model::pageKey($svc['id']); ?>/banner"><i class="fas fa-pen"></i> Edit content</a>
                        <a class="svc-icon-btn" href="<?php echo URLROOT; ?>/services/<?php echo $e($svc['slug']); ?>" target="_blank" rel="noopener" title="<?php echo $published ? 'View on the site' : 'Preview draft (admins only)'; ?>"><i class="fas fa-arrow-up-right-from-square"></i></a>
                        <?php echo $act('trash', $svc, '<i class="fas fa-trash"></i>', 'svc-icon-btn svc-icon-danger', 'Remove this service', [
                            'title' => 'Remove “' . $svc['title'] . '”?',
                            'text' => 'It will disappear from the site, the menu and the Services page straight away' . ($published ? ' (it is live right now)' : '') . '. Nothing is lost: you can bring it back from “Recently removed”.',
                            'button' => 'Remove service']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <?php if (!empty($data['trashed'])): ?>
        <div class="admin-card svc-card svc-removed">
            <div class="svc-group-head"><h2>Recently removed</h2><span><?php echo count($data['trashed']); ?></span></div>
            <p class="svc-note">These are hidden from the whole site. Restore one to bring it back as a draft, or delete it forever.</p>
            <?php foreach ($data['trashed'] as $svc): ?>
                <div class="svc-row is-draft svc-row-removed">
                    <div class="svc-cell svc-main">
                        <?php echo $thumb($svc); ?>
                        <div class="svc-main-text">
                            <span class="svc-title"><?php echo $e($svc['title']); ?></span>
                            <span class="svc-slug">Removed <?php echo $e(date('M j, Y', strtotime($svc['deleted_at']))); ?></span>
                        </div>
                    </div>
                    <div class="svc-cell svc-actions">
                        <?php echo $act('restore', $svc, '<i class="fas fa-rotate-left"></i> Restore', 'admin-btn admin-btn-sm admin-btn-secondary', 'Bring it back as a draft'); ?>
                        <?php echo $act('purge', $svc, '<i class="fas fa-ban"></i> Delete forever', 'admin-btn admin-btn-sm admin-btn-danger-ghost', 'Delete permanently', [
                            'title' => 'Permanently delete “' . $svc['title'] . '”?',
                            'text' => 'This deletes the service and all of its content for good. It cannot be undone.',
                            'button' => 'Delete forever']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/admin/inc/confirm_modal.php'; ?>
<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
