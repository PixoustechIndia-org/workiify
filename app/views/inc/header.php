<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- Basic SEO tags -->
    <title><?php echo isset($data['title']) ? htmlspecialchars($data['title']) : SITENAME; ?></title>
    <meta name="description" content="<?php echo isset($data['description']) ? htmlspecialchars($data['description']) : 'Premium Co Working Space'; ?>">

    <!-- Favicon (Animated Workiify Logo) -->
    <link rel="icon" type="image/svg+xml" href="<?php echo URLROOT; ?>/images/favicon-animated.svg">
    <link rel="alternate icon" href="<?php echo URLROOT; ?>/favicon.svg">
    <script src="<?php echo URLROOT; ?>/js/favicon-animator.js?v=<?php echo @filemtime(APPROOT . "/../public/js/favicon-animator.js"); ?>" defer></script>
    
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
        <?php
            $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $urlroot_path = parse_url(URLROOT, PHP_URL_PATH);
            // Get the path relative to the app root
            $rel_path = str_replace($urlroot_path, '', $current_path);
            $rel_path = rtrim($rel_path, '/');
            if ($rel_path == '' || $rel_path == '/index.php') $rel_path = '/';
        ?>
        <div class="container header-container">
            <?php $logoBust = base_convert((string)(int)(microtime(true) * 1000), 10, 36); // fresh logo per page view: the animated SVG starts blank, a cached copy can stay on that frame ?>
            <a href="<?php echo URLROOT; ?>/" class="brand-logo">
                <div class="brand-icon">
                    <img src="<?php echo URLROOT; ?>/images/logo-light.svg?v=<?php echo $logoBust; ?>" alt="Workiify" class="logo-light">
                    <img src="<?php echo URLROOT; ?>/images/logo-dark.svg?v=<?php echo $logoBust; ?>" alt="Workiify" class="logo-dark">
                </div>
                <div class="brand-text">
                    <span class="brand-name">WORKIIFY</span>
                    <span class="tagline">Workspace Simplified</span>
                </div>
            </a>
            
            <!-- Mobile Actions (Download Brochure + Menu Toggle) -->
            <div class="mobile-nav-actions">
                <a href="<?php echo URLROOT; ?>/Workiify_Brochure.pdf" download="Workiify_Brochure.pdf" class="btn btn-brochure btn-mobile-header" target="_blank" title="Download Brochure">
                    <i class="fas fa-file-arrow-down"></i>
                    <span>Brochure</span>
                </a>

                <button class="mobile-menu-toggle" aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <nav class="main-nav">
                <ul class="nav-links">
                    <li><a href="<?php echo URLROOT; ?>/" class="<?php echo ($rel_path == '/') ? 'active' : ''; ?>"><i class="fas fa-house nav-icon"></i>Home</a></li>
                    <li><a href="<?php echo URLROOT; ?>/about-us" class="<?php echo ($rel_path == '/about-us') ? 'active' : ''; ?>"><i class="fas fa-circle-info nav-icon"></i>About Us</a></li>
                    <li class="has-dropdown">
                        <div class="nav-link-row">
                            <a href="<?php echo URLROOT; ?>/services" class="<?php echo (strpos($rel_path, '/services') === 0) ? 'active' : ''; ?>">
                                <i class="fas fa-briefcase nav-icon"></i>Services <i class="fas fa-chevron-down dropdown-icon-desktop"></i>
                            </a>
                            <button type="button" class="dropdown-toggle" aria-label="Toggle Services submenu" aria-expanded="false">
                                <i class="fas fa-chevron-down dropdown-icon"></i>
                            </button>
                        </div>
                        <div class="dropdown-menu-wrap">
                            <ul class="dropdown-menu">
                                <?php
                                    // Menu entries come from Admin > Services (Show in menu).
                                    require_once APPROOT . '/models/Service_model.php';
                                    $navServices = (new Service_model())->navItems();
                                    foreach ($navServices as $navSvc):
                                        $navLabel = $navSvc['nav_label'] !== '' ? $navSvc['nav_label'] : $navSvc['title'];
                                        $navIcon = $navSvc['nav_icon'] !== '' ? $navSvc['nav_icon'] : 'fa-briefcase';
                                        $svcPath = '/services/' . $navSvc['slug'];
                                        $isSvcActive = ($rel_path === $svcPath);
                                ?>
                                <li class="<?php echo $isSvcActive ? 'active' : ''; ?>"><a href="<?php echo URLROOT; ?><?php echo $svcPath; ?>" class="<?php echo $isSvcActive ? 'active' : ''; ?>"><span class="dd-icon"><i class="fas <?php echo htmlspecialchars($navIcon); ?>"></i></span><span class="dd-text"><strong><?php echo htmlspecialchars($navLabel); ?></strong><?php if ($navSvc['nav_blurb'] !== ''): ?><small><?php echo htmlspecialchars($navSvc['nav_blurb']); ?></small><?php endif; ?></span></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                    <li><a href="<?php echo URLROOT; ?>/gallery" class="<?php echo ($rel_path == '/gallery') ? 'active' : ''; ?>"><i class="fas fa-images nav-icon"></i>Gallery</a></li>
                    <li><a href="<?php echo URLROOT; ?>/contact-us" class="<?php echo ($rel_path == '/contact-us') ? 'active' : ''; ?>"><i class="fas fa-envelope nav-icon"></i>Contact Us</a></li>
                </ul>
                
                <div class="header-actions">
                    <a href="<?php echo URLROOT; ?>/Workiify_Brochure.pdf" download="Workiify_Brochure.pdf" class="btn btn-brochure" target="_blank" title="Download Brochure">
                        <i class="fas fa-file-arrow-down"></i>
                        <span>Download Brochure</span>
                    </a>
                </div>
            </nav>
            <div class="nav-backdrop"></div>
        </div>
    </header>
    
    <main class="main-content">
