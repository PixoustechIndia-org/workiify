<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- Basic SEO tags -->
    <title><?php echo isset($data['title']) ? htmlspecialchars($data['title']) : SITENAME; ?></title>
    <meta name="description" content="<?php echo isset($data['description']) ? htmlspecialchars($data['description']) : 'Premium Coworking Space in Coimbatore'; ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Manrope:wght@600;700;800&family=Oswald:wght@700&family=Anton&display=swap" rel="stylesheet">
    
    <!-- Icons (FontAwesome or similar, using CDN for now) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AOS (Animate On Scroll) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <header class="site-header">
        <div class="container header-container">
            <a href="<?php echo URLROOT; ?>/" class="brand-logo">
                <div class="brand-icon">
                    <img src="<?php echo URLROOT; ?>/images/logo-light.svg" alt="Workiify" class="logo-light">
                    <img src="<?php echo URLROOT; ?>/images/logo-dark.svg" alt="Workiify" class="logo-dark">
                </div>
                <div class="brand-text">
                    <span class="brand-name">WORKIIFY</span>
                    <span class="tagline">Workspace Simplified</span>
                </div>
            </a>
            
            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            
            <nav class="main-nav">
                <?php
                    $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                    $urlroot_path = parse_url(URLROOT, PHP_URL_PATH);
                    // Get the path relative to the app root
                    $rel_path = str_replace($urlroot_path, '', $current_path);
                    $rel_path = rtrim($rel_path, '/');
                    if ($rel_path == '' || $rel_path == '/index.php') $rel_path = '/';
                ?>
                <ul class="nav-links">
                    <li><a href="<?php echo URLROOT; ?>/" class="<?php echo ($rel_path == '/') ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="<?php echo URLROOT; ?>/about-us" class="<?php echo ($rel_path == '/about-us') ? 'active' : ''; ?>">About Us</a></li>
                    <li class="has-dropdown">
                        <a href="<?php echo URLROOT; ?>/services" class="<?php echo (strpos($rel_path, '/services') === 0) ? 'active' : ''; ?>">
                            Services <i class="fas fa-chevron-down dropdown-icon"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo URLROOT; ?>/services/hot-desk">Hot Desk</a></li>
                            <li><a href="<?php echo URLROOT; ?>/services/dedicated-desk">Dedicated Desk</a></li>
                            <li><a href="<?php echo URLROOT; ?>/services/private-office">Private Office</a></li>
                            <li><a href="<?php echo URLROOT; ?>/services/meeting-room">Meeting Room</a></li>
                            <li><a href="<?php echo URLROOT; ?>/services/event-spaces">Event Spaces</a></li>
                        </ul>
                    </li>
                    <li><a href="<?php echo URLROOT; ?>/gallery" class="<?php echo ($rel_path == '/gallery') ? 'active' : ''; ?>">Gallery</a></li>
                    <li><a href="<?php echo URLROOT; ?>/contact-us" class="<?php echo ($rel_path == '/contact-us') ? 'active' : ''; ?>">Contact Us</a></li>
                </ul>
                
                <div class="header-actions">
                    <button class="theme-toggle" aria-label="Toggle Dark Mode">
                        <i class="fas fa-moon"></i>
                    </button>
                    <a href="<?php echo URLROOT; ?>/contact-us#enquiry" class="btn btn-primary">Enquire Now</a>
                </div>
            </nav>
        </div>
    </header>
    
    <main class="main-content">
