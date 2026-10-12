<?php
require APPROOT . '/views/admin/inc/header.php';

$e = function ($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); };
$form = $data['form'];
?>

<div class="admin-wrap svc-wrap">
    <a href="<?php echo URLROOT; ?>/admin/services" class="admin-back-link"><i class="fas fa-arrow-left"></i> All Services</a>

    <div class="admin-card">
        <h2 style="margin-top:0;"><i class="fas fa-plus" style="color:var(--admin-primary); margin-right:8px;"></i>Add a Service</h2>
        <p class="admin-page-subtitle" style="margin-top:-4px;">The new service gets the same page template as every other service. You fill in its content afterwards, section by section.</p>

        <?php if (!empty($data['errors'])): ?>
            <div class="admin-error" role="alert">
                <strong>Please fix the following:</strong>
                <ul style="margin:8px 0 0 18px; padding:0;"><?php foreach ($data['errors'] as $err): ?><li><?php echo $e($err); ?></li><?php endforeach; ?></ul>
            </div>
        <?php endif; ?>

        <form method="POST" class="admin-form" id="newServiceForm" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo $e($data['csrfToken']); ?>">

            <label for="title">Service name <span class="admin-field-limit">max 150 characters</span></label>
            <input type="text" id="title" name="title" value="<?php echo $e($form['title']); ?>" maxlength="150" placeholder="e.g. Podcast Studio" required autofocus>

            <label for="slug">Page address <span class="admin-field-limit">the part after /services/ (filled in from the name)</span></label>
            <div class="svc-slug-input">
                <span>/services/</span>
                <input type="text" id="slug" name="slug" value="<?php echo $e($form['slug']); ?>" maxlength="80" placeholder="podcast-studio" autocomplete="off" spellcheck="false" data-auto="<?php echo $form['slug'] === '' ? '1' : '0'; ?>">
            </div>

            <label for="category">Which section of the Services page should it appear in?</label>
            <select id="category" name="category" class="admin-select">
                <?php foreach ($data['categories'] as $key => $meta): ?>
                    <option value="<?php echo $e($key); ?>"<?php echo $form['category'] === $key ? ' selected' : ''; ?>><?php echo $e($meta['label']); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="copy_from">Start from</label>
            <select id="copy_from" name="copy_from" class="admin-select">
                <option value="0"<?php echo !$form['copy_from'] ? ' selected' : ''; ?>>A blank page (banner + introduction, add the rest as you need it)</option>
                <?php foreach ($data['existing'] as $svc): ?>
                    <option value="<?php echo (int)$svc['id']; ?>"<?php echo (int)$form['copy_from'] === (int)$svc['id'] ? ' selected' : ''; ?>>A copy of: <?php echo $e($svc['title']); ?></option>
                <?php endforeach; ?>
            </select>
            <p class="admin-help-text">Copying is the fastest way to a full page: every section and entry is duplicated and ready to edit.</p>

            <div class="admin-actions">
                <button type="submit" class="admin-btn"><i class="fas fa-check"></i> Create Service</button>
                <a href="<?php echo URLROOT; ?>/admin/services" class="admin-btn admin-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    function slugify(s) {
        return s.toLowerCase().normalize('NFKD').replace(/[̀-ͯ]/g, '').replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').slice(0, 80);
    }
    var title = document.getElementById('title'), slug = document.getElementById('slug');
    title.addEventListener('input', function () { if (slug.dataset.auto === '1') slug.value = slugify(title.value); });
    slug.addEventListener('input', function () { slug.dataset.auto = '0'; });
    slug.addEventListener('blur', function () { slug.value = slugify(slug.value); });
    document.getElementById('newServiceForm').addEventListener('submit', function (e) {
        if (!title.value.trim()) { e.preventDefault(); title.focus(); return; }
        this.querySelector('button[type=submit]').disabled = true;
    });
})();
</script>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
