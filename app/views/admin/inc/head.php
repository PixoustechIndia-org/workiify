<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['title']); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin.css?v=<?php echo @filemtime(APPROOT . "/../public/css/admin.css"); ?>">

    <!-- Favicon (Animated Workiify Logo) -->
    <link rel="icon" type="image/svg+xml" href="<?php echo URLROOT; ?>/images/favicon-animated.svg">
    <link rel="alternate icon" href="<?php echo URLROOT; ?>/favicon.svg">
    <script src="<?php echo URLROOT; ?>/js/favicon-animator.js" defer></script>
</head>
