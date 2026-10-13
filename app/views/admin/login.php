<!DOCTYPE html>
<html lang="en">
<?php require APPROOT . '/views/admin/inc/head.php'; ?>
<body class="admin-body">
    <div class="admin-login-wrap">
        <div class="admin-card admin-login-card">
            <div class="admin-login-brand">
                <span class="admin-login-mark"><img src="<?php echo URLROOT; ?>/images/favicon-animated.svg" alt="Workiify" width="46" height="46" style="display:block; border-radius:12px;"></span>
                <h1>Workiify Admin</h1>
                <p>Sign in to manage your site's content</p>
            </div>

            <?php if (!empty($data['error'])): ?>
                <div class="admin-alert admin-alert-error" role="alert"><i class="fas fa-circle-exclamation"></i><span class="admin-alert-text"><?php echo htmlspecialchars($data['error']); ?></span></div>
            <?php elseif (!empty($data['flash'])): ?>
                <div class="admin-alert admin-alert-<?php echo $data['flash']['type'] === 'error' ? 'error' : 'info'; ?>" role="status"><i class="fas fa-circle-info"></i><span class="admin-alert-text"><?php echo htmlspecialchars($data['flash']['message']); ?></span></div>
            <?php endif; ?>

            <form method="POST" class="admin-form" id="loginForm" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrfToken']); ?>">

                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($data['username'] ?? ''); ?>" maxlength="100" autocomplete="username" required<?php echo empty($data['username']) ? ' autofocus' : ''; ?>>

                <label for="password">Password</label>
                <div class="admin-password-wrap">
                    <input type="password" id="password" name="password" autocomplete="current-password" required<?php echo !empty($data['username']) ? ' autofocus' : ''; ?>>
                    <button type="button" class="admin-password-toggle" id="pwToggle" aria-label="Show password"><i class="fas fa-eye"></i></button>
                </div>

                <div class="admin-actions">
                    <button type="submit" class="admin-btn admin-btn-block">
                        <i class="fas fa-arrow-right-to-bracket"></i> Log In
                    </button>
                </div>
            </form>
            <p class="admin-login-foot"><a href="<?php echo URLROOT; ?>/"><i class="fas fa-arrow-left"></i> Back to website</a></p>
        </div>
    </div>
    <script>
    (function () {
        var form = document.getElementById('loginForm');
        var pw = document.getElementById('password');
        document.getElementById('pwToggle').addEventListener('click', function () {
            var show = pw.type === 'password';
            pw.type = show ? 'text' : 'password';
            this.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            this.innerHTML = '<i class="fas ' + (show ? 'fa-eye-slash' : 'fa-eye') + '"></i>';
        });
        form.addEventListener('submit', function (e) {
            var missing = ['username', 'password'].filter(function (id) { return !document.getElementById(id).value.trim(); });
            form.querySelectorAll('.admin-field-error').forEach(function (el) { el.remove(); });
            form.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });
            if (missing.length) {
                e.preventDefault();
                missing.forEach(function (id) {
                    var el = document.getElementById(id);
                    el.classList.add('is-invalid');
                    var msg = document.createElement('div');
                    msg.className = 'admin-field-error';
                    msg.textContent = (id === 'username' ? 'Username' : 'Password') + ' is required.';
                    (id === 'password' ? el.parentNode : el).insertAdjacentElement('afterend', msg);
                });
                document.getElementById(missing[0]).focus();
                return;
            }
            var btn = form.querySelector('button[type=submit]');
            setTimeout(function () { btn.disabled = true; btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Signing in…'; }, 0);
        });
    })();
    </script>
</body>
</html>
