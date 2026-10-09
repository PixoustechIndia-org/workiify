<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['title']); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin.css">
</head>
<body class="admin-body">
    <div class="admin-login-wrap">
        <div class="admin-card admin-login-card">
            <div class="admin-login-brand">
                <span class="admin-login-mark"><i class="fas fa-layer-group"></i></span>
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
