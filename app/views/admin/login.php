<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['title']); ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin.css">
</head>
<body class="admin-body">
    <div class="admin-login-wrap">
        <div class="admin-card admin-login-card">
            <h1>Workiify Admin</h1>
            <?php if (!empty($data['error'])): ?>
                <div class="admin-error"><?php echo htmlspecialchars($data['error']); ?></div>
            <?php endif; ?>
            <form method="POST" class="admin-form">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <div class="admin-actions">
                    <button type="submit" class="admin-btn" style="width:100%;">Log In</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
