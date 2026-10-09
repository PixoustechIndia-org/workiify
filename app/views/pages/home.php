<?php require APPROOT . '/views/inc/header.php'; ?>
<?php
function premiumHighlight($text) {
    $text = htmlspecialchars($text);
    if (strpos($text, '*') !== false) {
        return preg_replace('/\*(.*?)\*/', '<span style="color: var(--primary-color); font-weight: inherit;">$1</span>', $text);
    }
    $words = explode(' ', $text);
    if (count($words) > 1) {
        $lastWord = array_pop($words);
        return implode(' ', $words) . ' <span style="color: var(--primary-color); font-weight: inherit;">' . $lastWord . '</span>';
    }
    return $text;
}
?>
<!-- Preload the first hero slide image for LCP / UX -->
<?php if (!empty($data['hero'][0])): ?>
<link rel="preload" as="image" href="<?php echo URLROOT . htmlspecialchars($data['hero'][0]['data']['bg_image']); ?>">
<?php endif; ?>

<!-- 1. Hero Section -->
<section class="hero-section" id="hero-slider">
    <?php foreach ($data['hero'] as $i => $slide): $h = $slide['data']; ?>
    <div class="slide<?php echo $i === 0 ? ' active' : ''; ?>">
        <div class="slide-bg" style="background-image: url('<?php echo URLROOT . htmlspecialchars($h['bg_image']); ?>');"></div>
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <h1>
                    <?php echo htmlspecialchars($h['heading_line1']); ?><br>
                    <span class="highlight" style="color: #4ade80;"><?php echo htmlspecialchars($h['heading_highlight']); ?></span>
                </h1>
                <p><?php echo htmlspecialchars($h['paragraph']); ?></p>
                <div class="hero-buttons">
                    <?php if (!empty($h['btn1_text'])): ?>
                        <a href="<?php echo (strpos($h['btn1_link'], 'http') === 0) ? $h['btn1_link'] : URLROOT . $h['btn1_link']; ?>" class="btn btn-primary"><?php echo htmlspecialchars($h['btn1_text']); ?></a>
                    <?php endif; ?>
                    <?php if (!empty($h['btn2_text'])): ?>
                        <a href="<?php echo htmlspecialchars($h['btn2_link']); ?>" class="btn btn-whatsapp" target="_blank"><i class="fab fa-whatsapp"></i> <?php echo htmlspecialchars($h['btn2_text']); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Slider Controls -->
    <button class="slider-btn prev-btn" id="hero-prev"><i class="fas fa-chevron-left"></i></button>
    <button class="slider-btn next-btn" id="hero-next"><i class="fas fa-chevron-right"></i></button>
    <!-- Hero Counters (Inside Hero) -->
    <div class="hero-counters-wrapper" id="stats">
        <div class="container">
            <?php
                // Access / Floors / Per-floor sq ft / Seats, always in this order
                // (see Hero Counters in the CMS -- locked to exactly these 4).
                $access = $data['counters'][0]['data'] ?? ['value' => '', 'suffix' => '', 'label' => ''];
                $floors = $data['counters'][1]['data'] ?? ['value' => '', 'suffix' => '', 'label' => ''];
                $sqft = $data['counters'][2]['data'] ?? ['value' => '', 'suffix' => '', 'label' => ''];
                $seats = $data['counters'][3]['data'] ?? ['value' => '', 'suffix' => '', 'label' => ''];
            ?>
            <div class="counters-grid">
                <?php if (!empty($data['f']['contact_phone'])): ?>
                <div class="counter-card counter-card-phone">
                    <h3>
                        <a href="tel:<?php echo htmlspecialchars($data['f']['contact_phone_link']); ?>">
                            <i class="fas fa-phone"></i> <?php echo htmlspecialchars($data['f']['contact_phone']); ?>
                        </a>
                    </h3>
                    <p>Call Us</p>
                </div>
                <?php endif; ?>
                <div class="counter-card">
                    <div class="counter-line"></div>
                    <h3><span class="odometer" data-target="<?php echo htmlspecialchars($access['value']); ?>">0</span><?php echo htmlspecialchars($access['suffix']); ?></h3>
                    <p><?php echo htmlspecialchars($access['label']); ?></p>
                </div>
                <div class="counter-card counter-card-combo">
                    <div class="counter-combo-row">
                        <span class="counter-combo-value"><span class="odometer" data-target="<?php echo htmlspecialchars($floors['value']); ?>">0</span><?php echo htmlspecialchars($floors['suffix']); ?></span>
                        <span class="counter-combo-label"><?php echo htmlspecialchars($floors['label']); ?></span>
                    </div>
                    <div class="counter-combo-divider"></div>
                    <div class="counter-combo-row">
                        <span class="counter-combo-value"><span class="odometer" data-target="<?php echo htmlspecialchars($sqft['value']); ?>">0</span><?php echo htmlspecialchars($sqft['suffix']); ?></span>
                        <span class="counter-combo-label"><?php echo htmlspecialchars($sqft['label']); ?></span>
                    </div>
                </div>
                <div class="counter-card">
                    <div class="counter-line"></div>
                    <h3><span class="odometer" data-target="<?php echo htmlspecialchars($seats['value']); ?>">0</span><?php echo htmlspecialchars($seats['suffix']); ?></h3>
                    <p><?php echo htmlspecialchars($seats['label']); ?></p>
                </div>
            </div>
            <p class="counters-note">Fully customisable workspaces</p>
        </div>
    </div>
</section>

<!-- 3. Our Services -->
<section class="services-overview section-padding">
    <div class="container" style="background: #FFF5F3; border-radius: 0; padding: 5rem 3rem; text-align: left;">
        <div class="services-header" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; flex-wrap: wrap; gap: 2rem;">
            <div class="services-header-left" style="max-width: 600px;">
                <span class="category-eyebrow" style="margin-left: 0; display: inline-block; position: relative;">
                    <span style="display: inline-block; width: 20px; height: 2px; background: var(--services-accent); margin-right: 10px; vertical-align: middle;"></span>
                    What We Offer
                </span>
                <h2 class="section-title" style="margin-top: 1rem; margin-bottom: 0; text-align: left; font-size: 2.5rem; line-height: 1.2;"><?php echo premiumHighlight($data['f']['services_heading']); ?></h2>
                <p class="section-subtitle" style="margin-top: 1rem; margin-bottom: 0; text-align: left;"><?php echo htmlspecialchars($data['f']['services_subtitle']); ?></p>
            </div>
            <!-- Optional badge matching the mockup -->
            <div class="services-header-right" style="display: inline-flex; align-items: center; gap: 0.65rem; white-space: nowrap; flex-shrink: 0;">
                <div class="services-badge-icon" style="width: 34px; height: 34px; min-width: 34px; border-radius: 50%; background: #FF6B4A; display: inline-flex; align-items: center; justify-content: center; color: white; font-size: 0.85rem; flex-shrink: 0; box-shadow: 0 4px 10px rgba(255, 107, 74, 0.25);">
                    <i class="fas fa-gem"></i>
                </div>
                <div class="services-badge-text" style="color: var(--services-heading); font-weight: 600; font-size: 1rem; white-space: nowrap; line-height: 1;">
                    Premium Workspaces
                </div>
            </div>
        </div>

            <div class="services-grid grid-fixed-4">
                <?php foreach ($data['services'] as $i => $svc): $s = $svc['data']; ?>
                <a href="<?php echo URLROOT . htmlspecialchars($s['link']); ?>" class="service-card" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>">
                    <div class="service-img-wrap">
                        <img src="<?php echo URLROOT . htmlspecialchars($s['image']); ?>" alt="<?php echo htmlspecialchars($s['title']); ?>" class="service-img" loading="lazy" decoding="async" width="400" height="200" style="object-fit: cover; width: 100%; height: 200px;">
                    </div>
                    <div class="service-content">
                        <h3><?php echo htmlspecialchars($s['title']); ?></h3>
                        <p><?php echo htmlspecialchars($s['description']); ?></p>
                        <div class="service-link-wrapper">
                            <div class="service-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <a href="<?php echo URLROOT . htmlspecialchars($data['f']['services_cta_link']); ?>" class="btn btn-primary mt-4"><?php echo htmlspecialchars($data['f']['services_cta_text']); ?></a>
    </div>
</section>

<!-- 4. Smart Workspace Amenities -->
<section class="amenities-section section-padding" id="smart-amenities" style="position: relative; background-image: url('<?php echo URLROOT; ?>/images/amenities-bg.png'); background-size: cover; background-position: center; background-attachment: fixed;">
    <!-- Very light masking layer for a clean, modern look -->
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(248, 250, 252, 0.92); z-index: 0;"></div>
    <!-- Ambient Background Layer -->
    <div class="amenities-ambient-bg"></div>

    <!-- Canvas Network Layer -->
    <canvas id="amenities-network" class="amenities-network-canvas"></canvas>

    <div class="container text-center position-relative z-10">
        <h2 class="section-title amenities-title" style="color: var(--text-main);"><?php echo premiumHighlight($data['f']['amenities_heading']); ?></h2>
        <p class="section-subtitle amenities-subtitle" style="color: var(--text-muted);"><?php echo htmlspecialchars($data['f']['amenities_subtitle']); ?></p>

        <div class="amenities-grid">
            <?php foreach ($data['amenities'] as $am): $a = $am['data']; ?>
            <div class="amenity-item" data-context="<?php echo htmlspecialchars($a['context']); ?>">
                <div class="amenity-icon-wrapper"><i class="fas <?php echo htmlspecialchars($a['icon']); ?>"></i></div>
                <p><?php echo htmlspecialchars($a['label']); ?></p>
                <div class="amenity-context"><?php echo htmlspecialchars($a['context']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 5. Welcome to Workiify -->
<section class="welcome-section section-padding" style="background-color: #ffffff;">
    <div class="container split-layout max-w-1100" style="align-items: center; gap: 4rem;">
        <div class="split-img" data-aos="fade-right" data-aos-duration="900">
            <img src="<?php echo URLROOT; ?>/images/welcome-office.png" alt="Welcome to Workiify" loading="lazy" decoding="async" width="800" height="600" style="width: 100%; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); object-fit: cover; aspect-ratio: 4/3;">
        </div>
        <div class="split-content" data-aos="fade-left" data-aos-duration="900">
            <h2 class="section-title"><?php echo premiumHighlight($data['f']['welcome_heading']); ?></h2>
            <p><?php echo htmlspecialchars($data['f']['welcome_text']); ?></p>
            <a href="<?php echo URLROOT . htmlspecialchars($data['f']['welcome_btn_link']); ?>" class="btn btn-primary mt-4"><?php echo htmlspecialchars($data['f']['welcome_btn_text']); ?></a>
        </div>
    </div>
</section>

<!-- 6. Why Choose Us -->
<section class="why-choose-section section-padding bg-alt">
    <div class="container split-layout reverse max-w-1100" style="align-items: center; gap: 4rem;">
        <div class="split-content" data-aos="fade-right" data-aos-duration="900">
            <h2 class="section-title"><?php echo premiumHighlight($data['f']['whychoose_heading']); ?></h2>
            <ul class="feature-list">
                <?php foreach ($data['whyChoosePoints'] as $i => $p): ?>
                <li data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($p['data']['text']); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="split-img" data-aos="fade-left" data-aos-duration="900">
            <img src="<?php echo URLROOT . htmlspecialchars($data['f']['whychoose_image']); ?>" alt="Why Choose Workiify" loading="lazy" decoding="async" width="800" height="600" style="width: 100%; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); object-fit: cover; aspect-ratio: 4/3;">
        </div>
    </div>
</section>

<!-- 7. Who It's For -->
<section class="trusted-section section-padding">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title" data-aos="fade-up"><?php echo premiumHighlight($data['f']['audience_heading']); ?></h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100"><?php echo htmlspecialchars($data['f']['audience_subtitle']); ?></p>
        </div>
        <div class="teams-grid">
            <?php 
                $tileColors = [
                    '#0ea5e9', // Blue
                    '#8b5cf6', // Purple
                    '#14b8a6', // Teal
                    '#6366f1', // Indigo
                ];
            ?>
            <?php foreach ($data['audience'] as $i => $tile): 
                $t = $tile['data']; 
                $accentColor = $tileColors[$i % 4];
            ?>
            <div class="team-tile" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>" style="--accent: <?php echo $accentColor; ?>;">
                <div class="tile-glow"></div>
                <div class="team-tile-icon"><i class="fas <?php echo htmlspecialchars($t['icon']); ?>"></i></div>
                <h3><?php echo htmlspecialchars($t['title']); ?></h3>
                <p><?php echo htmlspecialchars($t['description']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 8. Testimonials -->
<section class="testimonials-section section-padding bg-alt home-testimonials">
    <div class="container text-center max-w-1150">
        <h2 class="section-title"><?php echo premiumHighlight($data['f']['testimonials_heading']); ?></h2>
        <div class="google-rating">
            <i class="fab fa-google"></i> <span><?php echo htmlspecialchars($data['f']['google_rating']); ?></span>
            <div class="stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
        </div>

        <div class="testimonials-slider-wrapper mt-4">
            <button type="button" class="testimonial-nav-btn prev" id="testimonial-prev" aria-label="Previous testimonial"><i class="fas fa-chevron-left"></i></button>
            <div class="testimonials-grid" id="testimonials-track">
                <?php foreach ($data['testimonials'] as $i => $t): ?>
                <div class="testimonial-card" data-aos="fade-up" data-aos-delay="<?php echo $i * 150; ?>">
                    <p>"<?php echo htmlspecialchars($t->feedback); ?>"</p>
                    <h4><?php echo htmlspecialchars($t->full_name); ?></h4>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="testimonial-nav-btn next" id="testimonial-next" aria-label="Next testimonial"><i class="fas fa-chevron-right"></i></button>
        </div>
        
        <div class="mt-4" data-aos="fade-up">
            <a href="<?php echo URLROOT; ?>/testimonials" class="btn btn-primary btn-pill">View all testimonials</a>
        </div>
    </div>
</section>

<!-- 9. Workspace Gallery -->
<section class="gallery-preview section-padding">
    <div class="container">
        <div class="gallery-row">
            <div class="gallery-scroll-viewport">
                <div class="gallery-track">
                    <?php foreach ([1, 2] as $pass): ?>
                        <?php foreach ($data['gallery'] as $g): $gd = $g['data']; ?>
                        <div class="gallery-img" <?php echo $pass === 1 ? '' : 'aria-hidden="true"'; ?>>
                            <img src="<?php echo URLROOT . htmlspecialchars($gd['image']); ?>" alt="<?php echo $pass === 1 ? htmlspecialchars($gd['alt']) : ''; ?>" loading="lazy" decoding="async" width="480" height="320">
                        </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="gallery-collage-cta" data-aos="fade-up">
                <i class="fas fa-images"></i>
                <h2><?php echo htmlspecialchars($data['f']['gallery_heading']); ?></h2>
                <p><?php echo htmlspecialchars($data['f']['gallery_subtitle']); ?></p>
                <a href="<?php echo URLROOT . htmlspecialchars($data['f']['gallery_cta_link']); ?>" class="btn btn-light btn-pill"><?php echo htmlspecialchars($data['f']['gallery_cta_text']); ?></a>
            </div>
        </div>
    </div>
</section>

<!-- 10. Get In Touch (Enquiry Form) -->
<section id="enquiry" class="contact-section section-padding bg-alt">
    <div class="contact-bg-blob blob-1"></div>
    <div class="contact-bg-blob blob-2"></div>

    <div class="container split-layout contact-grid max-w-1150" style="align-items: stretch; gap: 0;">
        <div class="contact-info" style="display: flex;" data-aos="fade-right" data-aos-duration="900">
            <div class="contact-info-inner" style="width: 100%; display: flex; flex-direction: column;">
                <div class="contact-badge" style="color: var(--primary-color); font-weight: 700; font-size: 0.85rem; letter-spacing: 1px; margin-bottom: 1rem; text-transform: uppercase; display: flex; align-items: center; gap: 10px;">
                    <span style="display: block; width: 40px; height: 1px; background: var(--primary-color);"></span>
                    <?php echo htmlspecialchars($data['f']['contact_badge']); ?>
                    <span style="display: block; width: 40px; height: 1px; background: var(--primary-color);"></span>
                </div>

                <h2 class="section-title contact-heading" style="line-height: 1.2; margin-bottom: 1rem;">
                    <?php echo premiumHighlight($data['f']['contact_heading_line1']); ?><br>
                    <span style="color: var(--primary-color);"><?php echo premiumHighlight($data['f']['contact_heading_highlight']); ?></span>
                </h2>
                <p style="font-size: 1.05rem; color: var(--text-muted); max-width: 500px; margin-bottom: 2.5rem; line-height: 1.6;"><?php echo htmlspecialchars($data['f']['contact_subtitle']); ?></p>

                <div class="contact-details" style="display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 2.5rem;">
                    <div class="contact-item" style="display: flex; gap: 1rem; align-items: center;">
                        <span class="contact-icon" style="background: #e0f2fe; color: var(--primary-color); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.2rem; flex-shrink: 0;"><i class="fas fa-map-marker-alt"></i></span>
                        <div>
                            <h4 style="font-size:0.85rem; margin:0 0 5px 0; color: var(--text-main); font-weight: 700;">Located at</h4>
                            <p style="margin:0; font-size:0.9rem; color: var(--text-muted); line-height: 1.4;"><?php echo $data['f']['contact_address']; ?></p>
                        </div>
                    </div>
                    <div class="contact-item" style="display: flex; gap: 1rem; align-items: center;">
                        <span class="contact-icon" style="background: #e0f2fe; color: var(--primary-color); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.2rem; flex-shrink: 0;"><i class="fas fa-phone"></i></span>
                        <div>
                            <h4 style="font-size:0.85rem; margin:0 0 5px 0; color: var(--text-main); font-weight: 700;">Call us</h4>
                            <p style="margin:0; font-size:0.9rem; color: var(--text-muted);"><a href="tel:<?php echo htmlspecialchars($data['f']['contact_phone_link']); ?>" style="color: inherit;"><?php echo htmlspecialchars($data['f']['contact_phone']); ?></a></p>
                        </div>
                    </div>
                    <div class="contact-item" style="display: flex; gap: 1rem; align-items: center;">
                        <span class="contact-icon" style="background: #e0f2fe; color: var(--primary-color); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.2rem; flex-shrink: 0;"><i class="fas fa-envelope"></i></span>
                        <div>
                            <h4 style="font-size:0.85rem; margin:0 0 5px 0; color: var(--text-main); font-weight: 700;">Email</h4>
                            <p style="margin:0; font-size:0.9rem; color: var(--text-muted);"><a href="mailto:<?php echo htmlspecialchars($data['f']['contact_email']); ?>" style="color: inherit;"><?php echo htmlspecialchars($data['f']['contact_email']); ?></a></p>
                        </div>
                    </div>
                </div>

                <div class="contact-map" style="position: relative;">
                    <iframe src="https://www.google.com/maps?q=AV+Info+Tech+Park,+Saravanampatti,+Coimbatore&z=16&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <a href="https://maps.google.com/?q=AV+Info+Tech+Park,+Saravanampatti,+Coimbatore" class="btn btn-primary" target="_blank" style="position: absolute; bottom: 15px; left: 50%; transform: translateX(-50%); padding: 8px 20px; font-size: 0.9rem; border-radius: 20px; box-shadow: 0 4px 12px rgba(37, 99, 235,0.3); z-index: 10;">
                        <i class="fas fa-directions"></i> Get Directions
                    </a>
                </div>

                <div class="contact-social" style="display: flex; align-items: center; gap: 1.5rem; border-top: none; padding-top: 1.5rem; margin-top: auto;">
                    <span style="font-weight:600; margin:0; font-size: 0.95rem; color: var(--text-main);">Follow us</span>
                    <div class="social-icons" style="display: flex; gap: 10px;">
                        <a href="#" aria-label="Instagram" style="background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: #fff; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Facebook" style="background: #1877F2; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: #fff; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="WhatsApp" style="background: #25D366; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: #fff; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="enquiry-form-container" style="border-left: 1px solid rgba(37, 99, 235, 0.15); display: flex; flex-direction: column;" data-aos="fade-left" data-aos-duration="900">
            <div class="form-header" style="display: flex; gap: 15px; align-items: flex-start; margin-bottom: 2rem;">
                <div style="background: #e0f2fe; color: var(--primary-color); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.2rem; flex-shrink: 0;">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div>
                    <h3 style="margin: 0 0 5px 0; font-size: 1.6rem; color: var(--text-main);">Send Us a Message</h3>
                    <p style="margin: 0; color: var(--text-muted); font-size: 0.95rem;">Fill in your details and we'll get back to you with the right plan.</p>
                </div>
            </div>

            <form id="enquiryForm" action="<?php echo URLROOT; ?>/contact-us" method="POST" class="enquiry-form">
                <input type="hidden" name="source" value="home">
                <div class="form-row">
                    <div class="form-group" style="margin: 0;">
                        <label for="companyName">Company Name *</label>
                        <input type="text" id="companyName" name="companyName" placeholder="Enter your company name" required maxlength="150" style="background: #fff;">
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label for="companyAddress">Company Address *</label>
                        <input type="text" id="companyAddress" name="companyAddress" placeholder="Enter your company address" required maxlength="250" style="background: #fff;">
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label for="contactNo">Contact No. *</label>
                        <input type="tel" id="contactNo" name="contactNo" placeholder="+91 98765 43210" required pattern="[6-9][0-9]{9}" style="background: #fff;">
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label for="email">Organization E-Mail ID *</label>
                        <input type="email" id="email" name="email" placeholder="you@company.com" required style="background: #fff;">
                    </div>
                </div>
                <div class="form-row" style="margin-top: 1.5rem;">
                    <div class="form-group" style="margin: 0;">
                        <label for="reqType">Requirement *</label>
                        <div class="select-wrapper">
                            <select id="reqType" name="reqType" required style="background: #fff; color: var(--text-muted);" onchange="this.style.color='var(--text-main)';">
                                <option value="" disabled selected>Select Requirement Type</option>
                                <option value="space">Required Space (in sq. ft.)</option>
                                <option value="seats">Seats (in Nos.)</option>
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label for="reqValue">Requirement Value *</label>
                        <input type="number" id="reqValue" name="reqValue" required min="1" disabled placeholder="Select a type first" style="background: #f8fafc;">
                    </div>
                </div>
                <div class="form-group" style="margin-top: 1.5rem; margin-bottom: 2rem;">
                    <label for="additional">Any Additional Requirements?</label>
                    <textarea id="additional" name="additional" placeholder="Tell us more about your requirements..." maxlength="1000" rows="3" style="background: #fff;"></textarea>
                </div>
                <div class="enquiry-result" role="status" aria-live="polite"></div>
                <button type="submit" class="btn btn-primary w-100" style="padding: 1.2rem; font-size: 1.05rem; border-radius: 12px; font-weight: 600;">
                    <i class="fas fa-paper-plane me-2"></i> Submit Enquiry
                </button>
            </form>
        </div>
    </div>
</section>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Intersection Observer for Smart Amenities
        const amenitiesSection = document.getElementById('smart-amenities');
        if (amenitiesSection) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        amenitiesSection.classList.add('is-visible');
                    }
                });
            }, { threshold: 0.2 });
            observer.observe(amenitiesSection);
        }

        // Hero Slider Logic
        const slides = document.querySelectorAll('#hero-slider .slide');
        const nextBtn = document.getElementById('hero-next');
        const prevBtn = document.getElementById('hero-prev');
        let currentSlide = 0;
        let slideInterval;

        if (slides.length > 0) {
            function showSlide(index) {
                slides.forEach(slide => slide.classList.remove('active'));

                currentSlide = index;
                if (currentSlide >= slides.length) currentSlide = 0;
                if (currentSlide < 0) currentSlide = slides.length - 1;

                slides[currentSlide].classList.add('active');
            }

            function nextSlide() {
                showSlide(currentSlide + 1);
            }

            function prevSlide() {
                showSlide(currentSlide - 1);
            }

            function resetInterval() {
                clearInterval(slideInterval);
                slideInterval = setInterval(nextSlide, 7000); // 7s auto-slide
            }

            nextBtn.addEventListener('click', () => {
                nextSlide();
                resetInterval();
            });

            prevBtn.addEventListener('click', () => {
                prevSlide();
                resetInterval();
            });

            // Swipe support -- the arrow buttons are hidden on mobile since no
            // fixed position stays clear of the per-slide text, so touch users
            // need another way to change slides manually.
            const heroSlider = document.getElementById('hero-slider');
            let touchStartX = 0;
            let touchEndX = 0;

            heroSlider.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            heroSlider.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                const delta = touchEndX - touchStartX;
                if (Math.abs(delta) > 40) {
                    if (delta < 0) nextSlide(); else prevSlide();
                    resetInterval();
                }
            }, { passive: true });

            // Start auto slide
            resetInterval();
        }

        // Odometer Logic for Counters
        const statsSection = document.getElementById('stats');
        const odometers = document.querySelectorAll('.odometer');
        let hasCounted = false;

        if (statsSection && odometers.length > 0) {
            const statsObserver = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting && !hasCounted) {
                    hasCounted = true;
                    odometers.forEach(odometer => {
                        const target = +odometer.getAttribute('data-target');
                        const duration = 2000; // 2 seconds
                        const increment = target / (duration / 16); // 60fps

                        let current = 0;
                        const updateCounter = () => {
                            current += increment;
                            if (current < target) {
                                odometer.innerText = Math.ceil(current).toLocaleString();
                                requestAnimationFrame(updateCounter);
                            } else {
                                odometer.innerText = target.toLocaleString();
                            }
                        };
                        updateCounter();
                    });
                }
            }, { threshold: 0.5 });
            statsObserver.observe(statsSection);
        }

        // Testimonials: arrow-controlled slider (one card per row on mobile)
        const testimonialsTrack = document.getElementById('testimonials-track');
        const testimonialPrev = document.getElementById('testimonial-prev');
        const testimonialNext = document.getElementById('testimonial-next');

        if (testimonialsTrack && testimonialPrev && testimonialNext) {
            testimonialNext.addEventListener('click', () => {
                testimonialsTrack.scrollBy({ left: testimonialsTrack.clientWidth, behavior: 'smooth' });
            });
            testimonialPrev.addEventListener('click', () => {
                testimonialsTrack.scrollBy({ left: -testimonialsTrack.clientWidth, behavior: 'smooth' });
            });
        }
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>



