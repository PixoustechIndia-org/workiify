<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- 1. Page Banner -->
<section class="page-banner" style="--banner-img: url('<?php echo URLROOT . htmlspecialchars($data['f']['about_banner_image']); ?>'); color: #fff;">
    <div class="banner-overlay"></div>
    <div class="container banner-content text-center" style="position: relative; z-index: 10;">
        <h1 style="margin-bottom: 0.5rem;"><?php echo htmlspecialchars($data['f']['about_banner_heading']); ?></h1>
        <p class="tagline"><?php echo htmlspecialchars($data['f']['about_banner_tagline']); ?></p>
        <div class="breadcrumb" style="margin-top: 1.5rem;">
            <a href="<?php echo URLROOT; ?>/">Home</a> <span>&gt;</span> <span>About Us</span>
        </div>
    </div>
</section>

<!-- 2. Introducing Workiify -->
<section class="our-story-section section-padding">
    <div class="container split-layout max-w-1100" style="align-items: center; gap: 4rem; padding-bottom: 2rem;">
        <div class="split-img" style="flex: 1; max-width: 500px; margin: 0 auto;" data-aos="fade-right" data-aos-duration="900">
            <?php 
            $db_img = isset($data['f']['about_story_image']) ? $data['f']['about_story_image'] : '';
            $about_img = '/images/gallery-open-workstation-area.png';
            if (!empty($db_img)) {
                $local_path = dirname(APPROOT) . '/public' . $db_img;
                if (file_exists($local_path)) {
                    $about_img = $db_img;
                }
            }
            ?>
            <img src="<?php echo URLROOT . htmlspecialchars($about_img); ?>" alt="People working in Workiify" style="width: 100%; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); object-fit: cover; aspect-ratio: 16/9; max-height: 320px;">
        </div>
        <div class="split-content" style="flex: 1;" data-aos="fade-left" data-aos-duration="900">
            <span class="eyebrow" style="color: var(--primary-color); font-weight: 700; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;"><?php echo htmlspecialchars($data['f']['about_story_eyebrow']); ?></span>
            <h2 class="section-title" style="margin-top: 0.5rem;"><?php echo htmlspecialchars($data['f']['about_story_heading']); ?></h2>
            <p><?php echo htmlspecialchars($data['f']['about_story_text']); ?></p>
        </div>
    </div>
</section>


<!-- 4. Mission & Vision -->
<section class="mission-vision-section purpose-section section-padding animated-gradient-bg">
    <div class="purpose-blob blob-a"></div>
    <div class="purpose-blob blob-b"></div>
    <div class="container max-w-1100 text-center">
        <span class="category-eyebrow">Our Purpose</span>
        <div class="mission-vision-grid mt-4">
            <div class="purpose-card">
                <div class="purpose-card-icon"><i class="fas fa-bullseye"></i></div>
                <h3><?php echo htmlspecialchars($data['f']['purpose_mission_heading']); ?></h3>
                <p><?php echo htmlspecialchars($data['f']['purpose_mission_text']); ?></p>
            </div>
            <div class="purpose-card">
                <div class="purpose-card-icon"><i class="fas fa-eye"></i></div>
                <h3><?php echo htmlspecialchars($data['f']['purpose_vision_heading']); ?></h3>
                <p><?php echo htmlspecialchars($data['f']['purpose_vision_text']); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- 5. Your Ideal & Flexible Workspace Solution -->
<section class="workspace-solution-section section-padding">
    <div class="container text-center">
        <span class="category-eyebrow"><?php echo htmlspecialchars($data['f']['workspace_eyebrow']); ?></span>
        <h2 class="section-title"><?php echo htmlspecialchars($data['f']['workspace_heading']); ?></h2>
        <p class="section-subtitle"><?php echo htmlspecialchars($data['f']['workspace_subtitle']); ?></p>
        <div class="workspace-tile-grid grid-fixed-4 mt-4">
            <?php
                $tileClasses = ['tile-sme', 'tile-corporate', 'tile-freelance', 'tile-student'];
                foreach ($data['workspaceTiles'] as $i => $tile): $t = $tile['data'];
            ?>
            <a href="<?php echo URLROOT . htmlspecialchars($t['link']); ?>" class="workspace-tile <?php echo $tileClasses[$i % 4]; ?>" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>">
                <div class="workspace-tile-icon"><i class="fas <?php echo htmlspecialchars($t['icon']); ?>"></i></div>
                <h3><?php echo htmlspecialchars($t['title']); ?></h3>
                <p><?php echo htmlspecialchars($t['description']); ?></p>
                <span class="workspace-tile-cta">Explore <i class="fas fa-arrow-right"></i></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 6. Why Choose Workiify -->
<section class="why-choose-cards section-padding bg-alt">
    <div class="container text-center max-w-1100">
        <h2 class="section-title"><?php echo htmlspecialchars($data['f']['whychoose_heading']); ?></h2>
        <div class="why-choose-grid mt-4">
            <?php foreach ($data['whyChooseCards'] as $i => $card): $c = $card['data']; ?>
            <div class="why-choose-card" data-aos="fade-up" data-aos-delay="<?php echo $i * 150; ?>">
                <div class="why-choose-icon"><i class="fas <?php echo htmlspecialchars($c['icon']); ?>"></i></div>
                <h3><?php echo htmlspecialchars($c['title']); ?></h3>
                <p><?php echo htmlspecialchars($c['text']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="icon-cards-grid mt-4">
            <?php foreach ($data['whyChooseIcons'] as $i => $icon): $ic = $icon['data']; ?>
            <div class="icon-card" data-aos="zoom-in" data-aos-delay="<?php echo $i * 100; ?>">
                <i class="fas <?php echo htmlspecialchars($ic['icon']); ?>"></i>
                <p><?php echo htmlspecialchars($ic['text']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 7. Our Location -->
<section class="our-location-section section-padding">
    <div class="container split-layout reverse max-w-1100">
        <div class="split-content" data-aos="fade-right" data-aos-duration="900">
            <h2 class="section-title"><?php echo htmlspecialchars($data['f']['location_heading']); ?></h2>
            <p><?php echo htmlspecialchars($data['f']['location_text']); ?></p>
        </div>
        <div class="split-img" data-aos="fade-left" data-aos-duration="900">
            <img src="<?php echo URLROOT . htmlspecialchars($data['f']['location_image']); ?>" alt="Workiify building exterior" style="width: 100%; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); object-fit: cover; aspect-ratio: 16/9; max-height: 320px;">
        </div>
    </div>
</section>

<!-- 8. Our Commitment to Community -->
<section class="community-events-section section-padding bg-alt">
    <div class="container text-center">
        <h2 class="section-title"><?php echo htmlspecialchars($data['f']['community_heading']); ?></h2>
        <p class="section-subtitle"><?php echo htmlspecialchars($data['f']['community_subtitle']); ?></p>

        <blockquote class="community-quote max-w-700" style="margin: 0 auto;" data-aos="zoom-in">"<?php echo htmlspecialchars($data['f']['community_quote']); ?>"</blockquote>

        <div class="photo-strip mt-4">
            <?php foreach ($data['communityPhotos'] as $i => $photo): $p = $photo['data']; ?>
            <div class="strip-item" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>">
                <img src="<?php echo URLROOT . htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['alt']); ?>" style="width:100%; height:200px; object-fit:cover; border-radius:8px;">
            </div>
            <?php endforeach; ?>
        </div>

        <p class="community-note"><?php echo htmlspecialchars($data['f']['community_note']); ?></p>
    </div>
</section>

<!-- 9. Testimonials -->
<section class="testimonials-section section-padding">
    <div class="container text-center max-w-1150">
        <h2 class="section-title"><?php echo htmlspecialchars($data['f']['about_testimonials_heading']); ?></h2>
        <div class="google-rating">
            <i class="fab fa-google"></i> <span><?php echo htmlspecialchars($data['f']['about_google_rating']); ?></span>
            <div class="stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
        </div>

        <div class="testimonials-grid mt-4">
            <?php foreach ($data['testimonials'] as $i => $t): ?>
            <div class="testimonial-card" data-aos="fade-up" data-aos-delay="<?php echo $i * 150; ?>">
                <p>"<?php echo htmlspecialchars($t->feedback); ?>"</p>
                <h4><?php echo htmlspecialchars($t->full_name); ?></h4>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="mt-4" data-aos="fade-up">
            <a href="<?php echo URLROOT; ?>/testimonials" class="btn btn-primary btn-pill">View all testimonials</a>
        </div>
    </div>
</section>

<!-- 10. Client Logos -->
<section class="client-logos-section section-padding bg-alt">
    <div class="container text-center max-w-1150">
        <h2 class="section-title"><?php echo htmlspecialchars($data['f']['client_logos_heading']); ?></h2>
        <div class="logo-strip mt-4" data-aos="fade-up">
            <?php foreach ($data['clientLogos'] as $logo): $l = $logo['data']; ?>
            <div class="logo-item"><img src="<?php echo URLROOT . htmlspecialchars($l['image']); ?>" alt="<?php echo htmlspecialchars($l['alt']); ?>"></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const purposeSection = document.querySelector('.purpose-section');
        if (purposeSection) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        purposeSection.classList.add('is-visible');
                    }
                });
            }, { threshold: 0.3 });
            observer.observe(purposeSection);
        }
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
