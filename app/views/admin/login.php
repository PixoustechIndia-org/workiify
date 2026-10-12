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
                <div class="admin-error"><?php echo htmlspecialchars($data['error']); ?></div>
            <?php endif; ?>

            <form method="POST" class="admin-form">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <div class="admin-actions">
                    <button type="submit" class="admin-btn" style="width:100%; justify-content:center;">
                        <i class="fas fa-arrow-right-to-bracket"></i> Log In
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
