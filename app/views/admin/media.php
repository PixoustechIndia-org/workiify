<?php require APPROOT . '/views/admin/inc/header.php';
$f = $data['filters'];
$c = $data['counts'];
$returnTo = admin_current_path();
$csrf = htmlspecialchars($data['csrfToken']);
$fmtSize = function ($b) { return $b >= 1048576 ? number_format($b / 1048576, 1) . ' MB' : max(1, round($b / 1024)) . ' KB'; };
$tabs = ['' => ['All', $c['all']], 'used' => ['In use', $c['used']], 'unused' => ['Not used', $c['unused']], 'upload' => ['My uploads', $c['upload']], 'builtin' => ['Built-in', $c['builtin']]];
?>

<div class="admin-wrap admin-wrap-wide">
    <div class="admin-page-header">
        <div>
            <h1>Media Library</h1>
            <p class="admin-page-subtitle">Every image used across the site, plus anything you upload here or from an edit form. Pick these from any image field instead of uploading the same file twice.</p>
        </div>
    </div>

    <?php require APPROOT . '/views/admin/inc/flash.php'; ?>

    <div class="admin-card">
        <h2 class="admin-card-title"><i class="fas fa-upload"></i> Upload a new image</h2>
        <form method="POST" enctype="multipart/form-data" class="admin-form admin-upload-row" data-validate>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf; ?>">
            <div class="admin-upload-input">
                <label for="new_asset" class="sr-only">Choose an image file</label>
                <input type="file" id="new_asset" name="new_asset" accept="image/png,image/jpeg,image/webp,image/gif" data-types="JPG, PNG, WebP or GIF" data-label="Image" data-required-msg="Choose an image to upload." required>
                <p class="admin-help-text">JPG, PNG, WebP or GIF, up to 5 MB.</p>
            </div>
            <button type="submit" class="admin-btn" data-busy="Uploading…"><i class="fas fa-upload"></i> Upload</button>
        </form>
    </div>

    <div class="admin-tabs" aria-label="Filter images">
        <?php foreach ($tabs as $k => $t): ?>
            <a href="<?php echo admin_query_url(['kind' => $k, 'page' => null]); ?>" class="admin-tab<?php echo $f['kind'] === $k ? ' is-active' : ''; ?>"><?php echo $t[0]; ?> <span class="admin-tab-count"><?php echo $t[1]; ?></span></a>
        <?php endforeach; ?>
    </div>

    <div class="admin-card admin-card-flush">
        <form method="GET" action="<?php echo URLROOT; ?>/admin/media" class="admin-toolbar" data-no-lock>
            <?php if ($f['kind'] !== ''): ?><input type="hidden" name="kind" value="<?php echo htmlspecialchars($f['kind']); ?>"><?php endif; ?>
            <?php if ($data['sort'] !== 'date' || $data['dir'] !== 'desc'): ?>
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($data['sort']); ?>">
                <input type="hidden" name="dir" value="<?php echo htmlspecialchars($data['dir']); ?>">
            <?php endif; ?>
            <div class="admin-search">
                <i class="fas fa-magnifying-glass"></i>
                <input type="search" name="q" value="<?php echo htmlspecialchars($f['q']); ?>" placeholder="Search by file name" aria-label="Search images" maxlength="100">
            </div>
            <button type="submit" class="admin-btn admin-btn-sm"><i class="fas fa-magnifying-glass"></i> Search</button>
            <?php if ($f['q'] !== ''): ?>
                <a href="<?php echo admin_query_url(['q' => null, 'page' => null]); ?>" class="admin-btn admin-btn-secondary admin-btn-sm"><i class="fas fa-xmark"></i> Clear</a>
            <?php endif; ?>
        </form>

        <div class="admin-table-responsive">
            <table class="admin-table admin-table-stack admin-media-table">
                <thead>
                    <tr>
                        <th class="admin-col-thumb">Preview</th>
                        <?php echo admin_sort_th('name', 'Name', $data['sort'], $data['dir']); ?>
                        <th>Type</th>
                        <?php echo admin_sort_th('size', 'Size', $data['sort'], $data['dir']); ?>
                        <?php echo admin_sort_th('used', 'Used in', $data['sort'], $data['dir']); ?>
                        <?php echo admin_sort_th('date', 'Added', $data['sort'], $data['dir']); ?>
                        <th class="admin-col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['assets'])): ?>
                        <tr class="admin-row-empty">
                            <td colspan="7">
                                <div class="admin-empty-state">
                                    <i class="fas fa-images"></i>
                                    <strong><?php echo $f['q'] !== '' || $f['kind'] !== '' ? 'No images match these filters' : 'No images yet'; ?></strong>
                                    <span><?php echo $f['q'] !== '' || $f['kind'] !== '' ? 'Try a different search or filter.' : 'Upload one above.'; ?></span>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data['assets'] as $a):
                            $used = count($a['usage']);
                            $url = URLROOT . $a['path'];
                            $canDelete = $a['isUpload'] && !$used;
                        ?>
                            <tr>
                                <td class="admin-col-thumb">
                                    <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" rel="noopener" title="Open full size">
                                        <img src="<?php echo htmlspecialchars($url); ?>" alt="" class="admin-media-row-thumb" loading="lazy">
                                    </a>
                                </td>
                                <td data-label="Name">
                                    <a class="admin-table-title" href="<?php echo URLROOT; ?>/admin/media-edit/<?php echo (int)$a['id']; ?>"><?php echo htmlspecialchars($a['name']); ?></a>
                                    <small class="admin-truncate admin-mono" title="<?php echo htmlspecialchars($a['path']); ?>"><?php echo htmlspecialchars($a['path']); ?></small>
                                </td>
                                <td data-label="Type">
                                    <span class="admin-chip"><?php echo htmlspecialchars($a['ext']); ?></span>
                                    <span class="admin-chip<?php echo $a['isUpload'] ? ' is-primary' : ''; ?>"><?php echo $a['isUpload'] ? 'Upload' : 'Built-in'; ?></span>
                                </td>
                                <td data-label="Size" class="admin-nowrap">
                                    <?php if ($a['exists']): ?>
                                        <?php echo $fmtSize($a['bytes']); ?>
                                        <?php if ($a['width']): ?><small><?php echo (int)$a['width']; ?> × <?php echo (int)$a['height']; ?> px</small><?php endif; ?>
                                    <?php else: ?>
                                        <span class="admin-chip is-danger"><i class="fas fa-triangle-exclamation"></i> File missing</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Used in">
                                    <?php if ($used): ?>
                                        <span class="admin-chip is-success" title="<?php echo htmlspecialchars(implode("\n", $a['usage'])); ?>"><i class="fas fa-link"></i> <?php echo $used; ?> place<?php echo $used === 1 ? '' : 's'; ?></span>
                                        <small class="admin-truncate"><?php echo htmlspecialchars($a['usage'][0]); ?><?php echo $used > 1 ? ' +' . ($used - 1) . ' more' : ''; ?></small>
                                    <?php else: ?>
                                        <span class="admin-muted">Not used</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Added" class="admin-nowrap"><?php echo date('M j, Y', strtotime($a['uploaded_at'])); ?></td>
                                <td class="admin-col-actions">
                                    <div class="admin-row-actions">
                                        <button type="button" class="admin-icon-btn admin-copy-path" data-path="<?php echo htmlspecialchars($url); ?>" title="Copy image address" aria-label="Copy image address"><i class="fas fa-copy"></i></button>
                                        <a href="<?php echo URLROOT; ?>/admin/media-edit/<?php echo (int)$a['id']; ?>" class="admin-icon-btn" title="Edit" aria-label="Edit image"><i class="fas fa-pen"></i></a>
                                        <?php if ($canDelete): ?>
                                            <form method="POST" action="<?php echo URLROOT; ?>/admin/media-delete/<?php echo (int)$a['id']; ?>"
                                                  data-confirm-title="Delete this image?"
                                                  data-confirm-text="“<?php echo htmlspecialchars($a['name']); ?>” will be deleted from the library and the server. It is not used anywhere on the site."
                                                  data-confirm-button="Delete image">
                                                <input type="hidden" name="csrf_token" value="<?php echo $csrf; ?>">
                                                <input type="hidden" name="return" value="<?php echo htmlspecialchars($returnTo); ?>">
                                                <button type="submit" class="admin-icon-btn is-danger" title="Delete" aria-label="Delete image"><i class="fas fa-trash"></i></button>
                                            </form>
                                        <?php else: ?>
                                            <span class="admin-icon-btn is-locked" title="<?php echo $a['isUpload'] ? 'In use on the website. Replace it there (or use Edit → Replace image) first.' : 'Built-in site image: it cannot be deleted. Use Edit to replace it.'; ?>"><i class="fas fa-lock"></i></span>
                                        <?php endif; ?>
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

<script>
document.querySelectorAll('.admin-copy-path').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var done = function () { var h = btn.innerHTML; btn.innerHTML = '<i class="fas fa-check"></i>'; setTimeout(function () { btn.innerHTML = h; }, 1500); };
        if (navigator.clipboard) navigator.clipboard.writeText(btn.dataset.path).then(done);
        else window.prompt('Copy this address:', btn.dataset.path);
    });
});
</script>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
