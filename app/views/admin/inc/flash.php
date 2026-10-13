<?php
/**
 * The one place admin screens show feedback after an action. Reads from $data:
 *   'flash'  => ['message' => ..., 'type' => success|error|info]  (set before a redirect)
 *   'errors' => list or field-keyed array of validation messages  (form screens)
 */
$flashIcons = ['success' => 'fa-circle-check', 'error' => 'fa-circle-exclamation', 'info' => 'fa-circle-info'];
?>
<?php if (!empty($data['flash'])):
    $ft = isset($flashIcons[$data['flash']['type']]) ? $data['flash']['type'] : 'info';
?>
    <div class="admin-alert admin-alert-<?php echo $ft; ?>" role="<?php echo $ft === 'error' ? 'alert' : 'status'; ?>">
        <i class="fas <?php echo $flashIcons[$ft]; ?>"></i>
        <span class="admin-alert-text"><?php echo htmlspecialchars($data['flash']['message']); ?></span>
        <button type="button" class="admin-alert-close" aria-label="Dismiss" onclick="this.parentNode.remove()"><i class="fas fa-xmark"></i></button>
    </div>
<?php endif; ?>
<?php if (!empty($data['errors'])): ?>
    <div class="admin-alert admin-alert-error" role="alert">
        <i class="fas fa-circle-exclamation"></i>
        <div class="admin-alert-text">
            <strong>Nothing was saved. Please fix the following:</strong>
            <ul><?php foreach ($data['errors'] as $flashErr): ?><li><?php echo htmlspecialchars($flashErr); ?></li><?php endforeach; ?></ul>
        </div>
    </div>
<?php endif; ?>
