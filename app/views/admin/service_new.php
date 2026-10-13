<?php
require APPROOT . '/views/admin/inc/header.php';

$e = function ($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); };
$form = $data['form'];
?>

<div class="admin-wrap svc-wrap">
    <div class="svc-breadcrumbs">
        <a href="<?php echo URLROOT; ?>/admin/services"><i class="fas fa-arrow-left"></i> All Services</a>
        <span class="svc-workspace-sep">/</span>
        <span>Add Service</span>
    </div>

    <div class="admin-card svc-new-card">
        <div class="svc-new-header">
            <div class="svc-new-icon">
                <i class="fas fa-plus"></i>
            </div>
            <div>
                <h2>Create a New Service</h2>
                <p class="admin-page-subtitle">Every service gets the full Workiify landing page template. You can configure sections, pricing, features, and SEO right after creating it.</p>
            </div>
        </div>

        <?php require APPROOT . '/views/admin/inc/flash.php'; ?>

        <form method="POST" class="admin-form" id="newServiceForm" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo $e($data['csrfToken']); ?>">

            <div class="svc-form-group">
                <label for="title">Service Name <span class="admin-field-limit">e.g., Coworking Space, Meeting Room</span></label>
                <input type="text" id="title" name="title" value="<?php echo $e($form['title']); ?>" maxlength="150" placeholder="e.g. Private Executive Suite" required autofocus>
            </div>

            <div class="svc-form-group">
                <label for="slug">Page Address (URL Slug) <span class="admin-field-limit">Auto-generated from title</span></label>
                <div class="svc-slug-input">
                    <span><?php echo rtrim(URLROOT, '/'); ?>/services/</span>
                    <input type="text" id="slug" name="slug" value="<?php echo $e($form['slug']); ?>" maxlength="80" placeholder="private-executive-suite" autocomplete="off" spellcheck="false" data-auto="<?php echo $form['slug'] === '' ? '1' : '0'; ?>">
                </div>
                <p class="admin-help-text"><i class="fas fa-info-circle"></i> This forms the permanent public link for this service page.</p>
            </div>

            <div class="svc-form-group">
                <label for="category">Category (Placement on Services Page)</label>
                <select id="category" name="category" class="admin-select">
                    <?php foreach ($data['categories'] as $key => $meta): ?>
                        <option value="<?php echo $e($key); ?>"<?php echo $form['category'] === $key ? ' selected' : ''; ?>>
                            <?php echo $e($meta['label']); ?> &mdash; <?php echo $e($meta['eyebrow'] ?? ''); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="admin-help-text">Determines which section of the main Services overview page this card will show up under.</p>
            </div>

            <div class="svc-form-group">
                <label for="copy_from">Starting Content Template</label>
                <select id="copy_from" name="copy_from" class="admin-select">
                    <option value="0"<?php echo !$form['copy_from'] ? ' selected' : ''; ?>>Blank Template (Banner + Introduction, add and enable sections as needed)</option>
                    <?php if (!empty($data['existing'])): ?>
                        <optgroup label="Clone from an existing service">
                            <?php foreach ($data['existing'] as $svc): ?>
                                <option value="<?php echo (int)$svc['id']; ?>"<?php echo (int)$form['copy_from'] === (int)$svc['id'] ? ' selected' : ''; ?>>
                                    Clone all content from: <?php echo $e($svc['title']); ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endif; ?>
                </select>
                <p class="admin-help-text"><i class="fas fa-sparkles"></i> <strong>Pro tip:</strong> Cloning duplicates all text, photos, options, and FAQs from that service so you don't have to start from scratch.</p>
            </div>

            <div class="admin-actions" style="margin-top:28px;">
                <button type="submit" class="admin-btn svc-save-btn"><i class="fas fa-check"></i> Create Service &amp; Open Editor</button>
                <a href="<?php echo URLROOT; ?>/admin/services" class="admin-btn admin-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    function slugify(s) {
        return s.toLowerCase().normalize('NFKD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').slice(0, 80);
    }
    var title = document.getElementById('title'), slug = document.getElementById('slug');
    if (title && slug) {
        title.addEventListener('input', function () {
            if (slug.dataset.auto === '1') slug.value = slugify(title.value);
        });
        slug.addEventListener('input', function () { slug.dataset.auto = '0'; });
        slug.addEventListener('blur', function () { slug.value = slugify(slug.value); });
    }
    var form = document.getElementById('newServiceForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (!title.value.trim()) {
                e.preventDefault();
                title.focus();
                return;
            }
            var btn = this.querySelector('button[type=submit]');
            if (btn) btn.disabled = true;
        });
    }
})();
</script>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
