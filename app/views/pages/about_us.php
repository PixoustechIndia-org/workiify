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
        <div class="split-img premium-img-wrapper" style="flex: 1; max-width: 500px; margin: 0 auto;" data-aos="fade-right" data-aos-duration="900">
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
            <img src="<?php echo URLROOT . htmlspecialchars($about_img); ?>" alt="People working in Workiify" class="premium-img" style="aspect-ratio: 16/9; max-height: 320px;">
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
                <span class="workspace-tile-borders"></span>
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
        <div class="split-img premium-img-wrapper" data-aos="fade-left" data-aos-duration="900">
            <img src="<?php echo URLROOT . htmlspecialchars($data['f']['location_image']); ?>" alt="Workiify building exterior" class="premium-img" style="aspect-ratio: 16/9; max-height: 320px;">
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
            <div class="strip-item community-gallery-item" data-category="events" data-caption="<?php echo htmlspecialchars($p['alt'] ?? 'Community Event'); ?>" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>">
                <img src="<?php echo URLROOT . htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['alt']); ?>" loading="lazy" decoding="async">
                <div class="gallery-caption"><?php echo htmlspecialchars($p['alt'] ?? 'Community Event'); ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <p class="community-note"><?php echo htmlspecialchars($data['f']['community_note']); ?></p>
    </div>
</section>

<!-- Lightbox Modal for Community Section -->
<div class="lightbox" id="aboutLightbox">
    <button class="lightbox-close" aria-label="Close">&times;</button>
    <button class="lightbox-prev" aria-label="Previous photo"><i class="fas fa-chevron-left"></i></button>
    <img class="lightbox-img" id="aboutLightboxImg" src="" alt="" loading="lazy" decoding="async">
    <div class="lightbox-caption" id="aboutLightboxCaption"></div>
    <button class="lightbox-next" aria-label="Next photo"><i class="fas fa-chevron-right"></i></button>
</div>

<!-- 9. Testimonials (Enhanced Continuous Carousel with Edge-to-Edge Border Stream) -->
<section class="testimonials-section section-padding about-testimonials-section">
    <div class="container text-center max-w-1150 mb-4">
        <h2 class="section-title"><?php echo htmlspecialchars($data['f']['about_testimonials_heading']); ?></h2>
        <div class="google-rating">
            <i class="fab fa-google"></i> <span><?php echo htmlspecialchars($data['f']['about_google_rating']); ?></span>
            <div class="stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
        </div>
    </div>

    <!-- Infinite Continuous One-Row Carousel -->
    <div class="about-testimonials-carousel-wrapper">
        <div class="about-testimonials-track">
            <?php 
                // Loop twice to ensure seamless infinite looping marquee
                for ($loop = 0; $loop < 2; $loop++): 
                    foreach ($data['testimonials'] as $i => $t):
                        $photoName = !empty($t->photo) ? trim($t->photo) : '';
                        $hasAvatar = !empty($photoName) && file_exists(APPROOT . '/../public/uploads/testimonials/' . $photoName);
                        $avatarUrl = $hasAvatar ? (URLROOT . '/uploads/testimonials/' . htmlspecialchars($photoName)) : '';
                        $roleCompany = htmlspecialchars($t->designation ?? '');
                        if (!empty($t->designation) && !empty($t->company)) $roleCompany .= ' &middot; ';
                        $roleCompany .= htmlspecialchars($t->company ?? '');
                        $feedbackLen = mb_strlen(trim($t->feedback));
                        $isLong = ($feedbackLen > 115);
            ?>
                <div class="about-testimonial-card compact-card" 
                     data-index="<?php echo $i; ?>"
                     data-author="<?php echo htmlspecialchars($t->full_name); ?>"
                     data-role="<?php echo $roleCompany; ?>"
                     data-rating="<?php echo (int)($t->rating ?? 5); ?>"
                     data-feedback="<?php echo htmlspecialchars($t->feedback); ?>"
                     data-avatar="<?php echo $avatarUrl; ?>">
                    <div class="at-card-inner">
                        <div class="testimonial-card-header">
                            <div class="rating">
                                <?php for ($s = 0; $s < ($t->rating ?? 5); $s++) : ?>
                                    <i class="fas fa-star"></i>
                                <?php endfor; ?>
                                <?php for ($s = ($t->rating ?? 5); $s < 5; $s++) : ?>
                                    <i class="far fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="card-open-hint" title="Click to view full review">
                                <i class="fas fa-arrow-up-right-from-square"></i>
                            </span>
                        </div>

                        <div class="feedback-body">
                            <p class="feedback-text">"<?php echo htmlspecialchars($t->feedback); ?>"</p>
                            <?php if ($isLong): ?>
                                <span class="read-more-trigger" title="Click to read full review">
                                    <span>...</span> <span class="read-more-link">Read more</span>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="client-info">
                            <div class="avatar-placeholder">
                                <?php if ($hasAvatar) : ?>
                                    <img src="<?php echo $avatarUrl; ?>" 
                                         alt="<?php echo htmlspecialchars($t->full_name); ?>" 
                                         class="client-avatar-img">
                                <?php else : ?>
                                    <i class="fas fa-user-tie"></i>
                                <?php endif; ?>
                            </div>
                            <div class="client-details">
                                <h4 class="client-name"><?php echo htmlspecialchars($t->full_name); ?></h4>
                                <?php if (!empty($t->designation) || !empty($t->company)) : ?>
                                    <p class="client-role">
                                        <?php echo htmlspecialchars($t->designation); ?>
                                        <?php if (!empty($t->designation) && !empty($t->company)) echo ' &middot; '; ?>
                                        <?php echo htmlspecialchars($t->company); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                    endforeach; 
                endfor; 
            ?>
        </div>
    </div>

    <div class="container text-center mt-5">
        <a href="<?php echo URLROOT; ?>/testimonials" class="btn btn-primary btn-pill">
            <i class="fas fa-star" style="margin-right: 6px;"></i> View all testimonials
        </a>
    </div>
</section>

<!-- Testimonial Full Reader Modal / Popup with Next/Prev Carousel Navigation -->
<div id="testimonial-detail-modal" class="testimonial-detail-modal" role="dialog" aria-modal="true" aria-labelledby="tdmAuthorName" style="display: none;">
    <div class="tdm-backdrop" id="tdmBackdrop"></div>
    <div class="tdm-dialog">
        <!-- Close Button -->
        <button type="button" class="tdm-close-btn" id="tdmCloseBtn" aria-label="Close review dialog">
            <i class="fas fa-times"></i>
        </button>

        <!-- Navigation Arrows (Previous / Next) -->
        <button type="button" class="tdm-nav-arrow tdm-prev-arrow" id="tdmPrevBtn" aria-label="Previous testimonial" title="Previous review (Left Arrow)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button type="button" class="tdm-nav-arrow tdm-next-arrow" id="tdmNextBtn" aria-label="Next testimonial" title="Next review (Right Arrow)">
            <i class="fas fa-chevron-right"></i>
        </button>

        <div class="tdm-header">
            <div class="tdm-avatar-wrap" id="tdmAvatarWrap">
                <i class="fas fa-user-tie" id="tdmDefaultIcon"></i>
                <img id="tdmAvatarImg" src="" alt="Author Avatar" style="display: none;">
            </div>
            <div class="tdm-author-meta">
                <div class="tdm-meta-top">
                    <h3 class="tdm-name" id="tdmAuthorName">Client Name</h3>
                    <span class="tdm-counter" id="tdmCounter">1 / 5</span>
                </div>
                <p class="tdm-role" id="tdmAuthorRole">Designation & Company</p>
                <div class="tdm-rating" id="tdmRatingStars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>

        <div class="tdm-body">
            <i class="fas fa-quote-left tdm-quote-icon"></i>
            <p class="tdm-full-text" id="tdmFeedbackText">Feedback text...</p>
        </div>

        <div class="tdm-footer">
            <span class="tdm-verified-tag"><i class="fas fa-check-circle"></i> Verified Workiify Member Review</span>
            <div class="tdm-footer-actions">
                <button type="button" class="btn btn-outline btn-sm tdm-close-action" id="tdmCloseAction">Close</button>
            </div>
        </div>
    </div>
</div>

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

        // --- Community Gallery Lightbox ---
        const lightbox = document.getElementById('aboutLightbox');
        const lightboxImg = document.getElementById('aboutLightboxImg');
        const lightboxCaption = document.getElementById('aboutLightboxCaption');
        const closeBtn = lightbox.querySelector('.lightbox-close');
        const prevBtn = lightbox.querySelector('.lightbox-prev');
        const nextBtn = lightbox.querySelector('.lightbox-next');
        const items = Array.from(document.querySelectorAll('.community-gallery-item'));

        let currentIndex = 0;

        function updateLightbox() {
            const item = items[currentIndex];
            if (!item) return;
            const img = item.querySelector('img');
            lightboxImg.src = img.src;
            lightboxImg.alt = img.alt || '';
            lightboxCaption.textContent = item.dataset.caption || img.alt || '';
        }

        function openLightbox(index) {
            currentIndex = index;
            updateLightbox();
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        }

        items.forEach((item, index) => {
            item.addEventListener('click', () => openLightbox(index));
        });

        closeBtn.addEventListener('click', closeLightbox);
        nextBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            currentIndex = (currentIndex + 1) % items.length;
            updateLightbox();
        });
        prevBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            currentIndex = (currentIndex - 1 + items.length) % items.length;
            updateLightbox();
        });

        // --- Testimonial Detail Reader Modal Popup with Next/Prev Carousel Navigation ---
        const tdmModal = document.getElementById('testimonial-detail-modal');
        const tdmBackdrop = document.getElementById('tdmBackdrop');
        const tdmCloseBtn = document.getElementById('tdmCloseBtn');
        const tdmCloseAction = document.getElementById('tdmCloseAction');
        const tdmPrevBtn = document.getElementById('tdmPrevBtn');
        const tdmNextBtn = document.getElementById('tdmNextBtn');
        const tdmAvatarImg = document.getElementById('tdmAvatarImg');
        const tdmDefaultIcon = document.getElementById('tdmDefaultIcon');
        const tdmAuthorName = document.getElementById('tdmAuthorName');
        const tdmAuthorRole = document.getElementById('tdmAuthorRole');
        const tdmRatingStars = document.getElementById('tdmRatingStars');
        const tdmFeedbackText = document.getElementById('tdmFeedbackText');
        const tdmCounter = document.getElementById('tdmCounter');
        const track = document.querySelector('.about-testimonials-track');

        // Collect unique testimonials data from the DOM
        const uniqueCards = Array.from(document.querySelectorAll('.about-testimonial-card'))
            .filter((c, idx, arr) => arr.findIndex(item => item.dataset.author === c.dataset.author) === idx);
        
        let currentTestimonialIndex = 0;

        function renderTestimonialData(index) {
            if (!uniqueCards.length) return;
            // Wrap around circularly
            currentTestimonialIndex = (index + uniqueCards.length) % uniqueCards.length;
            const card = uniqueCards[currentTestimonialIndex];

            const author = card.dataset.author || '';
            const role = card.dataset.role || '';
            const feedback = card.dataset.feedback || '';
            const avatar = card.dataset.avatar || '';
            const rating = parseInt(card.dataset.rating, 10) || 5;

            tdmAuthorName.textContent = author;
            tdmAuthorRole.textContent = role;
            tdmFeedbackText.textContent = `"${feedback}"`;

            if (tdmCounter) {
                tdmCounter.textContent = `${currentTestimonialIndex + 1} / ${uniqueCards.length}`;
            }

            // Avatar
            if (avatar && avatar.trim() !== '') {
                tdmAvatarImg.src = avatar;
                tdmAvatarImg.style.display = 'block';
                tdmDefaultIcon.style.display = 'none';
            } else {
                tdmAvatarImg.style.display = 'none';
                tdmDefaultIcon.style.display = 'block';
            }

            // Rating Stars
            tdmRatingStars.innerHTML = '';
            for (let s = 0; s < rating; s++) {
                tdmRatingStars.innerHTML += '<i class="fas fa-star"></i>';
            }
            for (let s = rating; s < 5; s++) {
                tdmRatingStars.innerHTML += '<i class="far fa-star"></i>';
            }
        }

        function openTestimonialModal(card) {
            if (!tdmModal || !card) return;
            const matchedIndex = uniqueCards.findIndex(c => c.dataset.author === card.dataset.author);
            renderTestimonialData(matchedIndex >= 0 ? matchedIndex : 0);

            // Pause marquee running animation when modal opens
            if (track) track.style.animationPlayState = 'paused';

            tdmModal.style.display = 'flex';
            requestAnimationFrame(() => {
                tdmModal.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            });
        }

        function closeTestimonialModal() {
            if (!tdmModal) return;
            tdmModal.classList.remove('is-open');
            document.body.style.overflow = '';
            setTimeout(() => {
                tdmModal.style.display = 'none';
                // Resume marquee running animation
                if (track) track.style.animationPlayState = 'running';
            }, 250);
        }

        function nextTestimonial() {
            renderTestimonialData(currentTestimonialIndex + 1);
        }

        function prevTestimonial() {
            renderTestimonialData(currentTestimonialIndex - 1);
        }

        // Attach click listeners to all about-testimonial-cards
        document.querySelectorAll('.about-testimonial-card').forEach(card => {
            card.addEventListener('click', () => {
                openTestimonialModal(card);
            });
        });

        if (tdmPrevBtn) {
            tdmPrevBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                prevTestimonial();
            });
        }

        if (tdmNextBtn) {
            tdmNextBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                nextTestimonial();
            });
        }

        if (tdmCloseBtn) tdmCloseBtn.addEventListener('click', closeTestimonialModal);
        if (tdmCloseAction) tdmCloseAction.addEventListener('click', closeTestimonialModal);
        if (tdmBackdrop) tdmBackdrop.addEventListener('click', closeTestimonialModal);

        // Keyboard navigation (Esc to close, Left/Right arrows to browse)
        document.addEventListener('keydown', (e) => {
            if (!tdmModal || !tdmModal.classList.contains('is-open')) return;
            if (e.key === 'Escape') {
                closeTestimonialModal();
            } else if (e.key === 'ArrowRight') {
                nextTestimonial();
            } else if (e.key === 'ArrowLeft') {
                prevTestimonial();
            }
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
