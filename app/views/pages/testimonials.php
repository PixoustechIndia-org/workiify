<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- 1. Page Banner -->
<section class="page-banner" style="--banner-img: url('<?php echo URLROOT; ?>/images/services_bg.jpg'); color: #fff;">
    <div class="banner-overlay"></div>
    <div class="container banner-content text-center" style="position: relative; z-index: 10;">
        <h1 style="margin-bottom: 0.5rem;">Testimonials & Feedback</h1>
        <p class="tagline">See what our community says about Workiify</p>
        <div class="breadcrumb" style="margin-top: 1.5rem;">
            <a href="<?php echo URLROOT; ?>/">Home</a> <span>&gt;</span>
            <span>Testimonials</span>
        </div>
    </div>
</section>

<!-- 2. Testimonials Section -->
<section class="section-padding testimonials-showcase-bg">
    <div class="container max-w-1100">
        <div class="text-center mb-5">
            <span class="category-eyebrow">Our Community</span>
            <h2 class="section-title">What They Say About Us</h2>
        </div>

        <?php
            $tPerPage = 9;
            $tTotal = count($data['testimonials']);
            $tTotalPages = (int) ceil($tTotal / $tPerPage);
        ?>
        <?php if (!empty($data['testimonials'])) : ?>
            <div class="testimonials-grid" id="testimonialsGrid">
                <?php foreach ($data['testimonials'] as $tIndex => $testimonial) :
                    $tPage = (int) floor($tIndex / $tPerPage) + 1;
                    $photoName0 = !empty($testimonial->photo) ? trim($testimonial->photo) : '';
                    $hasAvatar0 = !empty($photoName0) && file_exists(APPROOT . '/../public/uploads/testimonials/' . $photoName0);
                    $avatarUrl0 = $hasAvatar0 ? (URLROOT . '/uploads/testimonials/' . htmlspecialchars($photoName0)) : '';
                    $roleCompany0 = htmlspecialchars($testimonial->designation ?? '');
                    if (!empty($testimonial->designation) && !empty($testimonial->company)) $roleCompany0 .= ' &middot; ';
                    $roleCompany0 .= htmlspecialchars($testimonial->company ?? '');
                ?>
                    <div class="testimonial-card"
                         data-tpage="<?php echo $tPage; ?>"<?php echo $tPage !== 1 ? ' style="display:none;"' : ''; ?>
                         data-author="<?php echo htmlspecialchars($testimonial->full_name); ?>"
                         data-role="<?php echo $roleCompany0; ?>"
                         data-rating="<?php echo (int) $testimonial->rating; ?>"
                         data-feedback="<?php echo htmlspecialchars($testimonial->feedback); ?>"
                         data-avatar="<?php echo $avatarUrl0; ?>">
                        <div class="testimonial-card-header">
                            <div class="rating">
                                <?php for ($i = 0; $i < $testimonial->rating; $i++) : ?>
                                    <i class="fas fa-star"></i>
                                <?php endfor; ?>
                                <?php for ($i = $testimonial->rating; $i < 5; $i++) : ?>
                                    <i class="far fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="card-open-hint" title="Click to view full review">
                                <i class="fas fa-arrow-up-right-from-square"></i>
                            </span>
                        </div>

                        <p class="feedback-text">"<?php echo htmlspecialchars($testimonial->feedback); ?>"</p>

                        <div class="client-info">
                            <div class="avatar-placeholder">
                                <?php 
                                    $photoName = !empty($testimonial->photo) ? trim($testimonial->photo) : '';
                                    $hasAvatar = !empty($photoName) && file_exists(APPROOT . '/../public/uploads/testimonials/' . $photoName);
                                ?>
                                <?php if ($hasAvatar) : ?>
                                    <img src="<?php echo URLROOT; ?>/uploads/testimonials/<?php echo htmlspecialchars($photoName); ?>" 
                                         alt="<?php echo htmlspecialchars($testimonial->full_name); ?>" 
                                         class="client-avatar-img">
                                <?php else : ?>
                                    <i class="fas fa-user-tie"></i>
                                <?php endif; ?>
                            </div>
                            <div class="client-details">
                                <h4 class="client-name"><?php echo htmlspecialchars($testimonial->full_name); ?></h4>
                                <?php if (!empty($testimonial->designation) || !empty($testimonial->company)) : ?>
                                    <p class="client-role">
                                        <?php echo htmlspecialchars($testimonial->designation); ?>
                                        <?php if (!empty($testimonial->designation) && !empty($testimonial->company)) echo ' &middot; '; ?>
                                        <?php echo htmlspecialchars($testimonial->company); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Testimonial Full Reader Modal / Popup with Next/Prev Navigation -->
            <div id="testimonial-detail-modal" class="testimonial-detail-modal" role="dialog" aria-modal="true" aria-labelledby="tdmAuthorName" style="display: none;">
                <div class="tdm-backdrop" id="tdmBackdrop"></div>
                <div class="tdm-dialog">
                    <button type="button" class="tdm-close-btn" id="tdmCloseBtn" aria-label="Close review dialog">
                        <i class="fas fa-times"></i>
                    </button>
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

            <?php if ($tTotalPages > 1) : ?>
            <nav class="testimonials-pagination" id="testimonialsPagination" aria-label="Testimonials pages">
                <button type="button" class="tpg-btn tpg-prev" data-tpg-prev aria-label="Previous page"><i class="fas fa-chevron-left"></i></button>
                <?php for ($p = 1; $p <= $tTotalPages; $p++) : ?>
                    <button type="button" class="tpg-btn<?php echo $p === 1 ? ' is-active' : ''; ?>" data-tpg-page="<?php echo $p; ?>"><?php echo $p; ?></button>
                <?php endfor; ?>
                <button type="button" class="tpg-btn tpg-next" data-tpg-next aria-label="Next page"><i class="fas fa-chevron-right"></i></button>
            </nav>
            <?php endif; ?>

            <!-- Floating Center CTA Button: Share Your Experience -->
            <div class="floating-cta-wrapper" id="floatingShareCta">
                <a href="#feedbackFormSection" class="btn btn-share-experience" id="btnShareExperience">
                    <i class="fas fa-pen-to-square"></i>
                    <span>Share Your Experience</span>
                    <i class="fas fa-chevron-down btn-chevron"></i>
                </a>
            </div>
        <?php else : ?>
            <p class="text-center" style="margin-bottom: 4rem; color: var(--text-muted);">Be the first to share your experience with us!</p>
        <?php endif; ?>
    </div>
</section>

<!-- 3. Share Your Experience Section -->
<section class="section-padding share-experience-bg">
    <div class="container max-w-1100">
        <!-- Feedback Form -->
        <div class="feedback-form-container" id="feedbackFormSection">
            <div class="feedback-form-header">
                <span class="category-eyebrow">We'd Love Your Feedback</span>
                <h3>Share Your Experience</h3>
                <p>Tell us how Workiify has worked for you — it helps other members and our team alike.</p>
            </div>

            <?php if (!empty($data['success_msg'])) : ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $data['success_msg']; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($data['general_err'])) : ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $data['general_err']; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo URLROOT; ?>/testimonials" method="POST" enctype="multipart/form-data" class="feedback-form" novalidate>
                <input type="hidden" name="honeypot" value="" style="display: none;">

                <div class="form-row">
                    <div class="form-group">
                        <label for="full_name">Full Name <span class="required-star">*</span></label>
                        <input type="text" name="full_name" id="full_name" class="<?php echo (!empty($data['full_name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['full_name']; ?>" maxlength="100" required placeholder="Your name">
                        <span class="invalid-feedback"><?php echo $data['full_name_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="required-star">*</span> <small>(never published)</small></label>
                        <input type="email" name="email" id="email" class="<?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>" required placeholder="you@company.com">
                        <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="company">Company</label>
                        <input type="text" name="company" id="company" value="<?php echo $data['company']; ?>" maxlength="150" placeholder="Where you work">
                    </div>
                    <div class="form-group">
                        <label for="designation">Designation</label>
                        <input type="text" name="designation" id="designation" value="<?php echo $data['designation']; ?>" maxlength="100" placeholder="Your role">
                    </div>
                </div>

                <div class="form-group">
                    <label>Rating <span class="required-star">*</span></label>
                    <div class="rating-input">
                        <input type="radio" id="star5" name="rating" value="5" required <?php echo ($data['rating'] == '5') ? 'checked' : ''; ?>>
                        <label for="star5" title="5 stars"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star4" name="rating" value="4" <?php echo ($data['rating'] == '4') ? 'checked' : ''; ?>>
                        <label for="star4" title="4 stars"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star3" name="rating" value="3" <?php echo ($data['rating'] == '3') ? 'checked' : ''; ?>>
                        <label for="star3" title="3 stars"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star2" name="rating" value="2" <?php echo ($data['rating'] == '2') ? 'checked' : ''; ?>>
                        <label for="star2" title="2 stars"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star1" name="rating" value="1" <?php echo ($data['rating'] == '1') ? 'checked' : ''; ?>>
                        <label for="star1" title="1 star"><i class="fas fa-star"></i></label>
                    </div>
                    <span class="invalid-feedback"><?php echo $data['rating_err']; ?></span>
                </div>

                <div class="form-group">
                    <label for="feedback">Your Feedback <span class="required-star">*</span></label>
                    <textarea name="feedback" id="feedback" rows="4" class="<?php echo (!empty($data['feedback_err'])) ? 'is-invalid' : ''; ?>" required minlength="20" maxlength="1000" placeholder="Tell us about your experience at Workiify..."><?php echo $data['feedback']; ?></textarea>
                    <small class="field-hint">Between 20 and 1000 characters.</small>
                    <span class="invalid-feedback"><?php echo $data['feedback_err']; ?></span>
                </div>

                <div class="form-group">
                    <label for="photo">Profile Photo (Optional)</label>
                    <div class="avatar-upload-row">
                        <div class="avatar-preview-box" id="avatarPreviewBox">
                            <i class="fas fa-user-circle default-avatar-icon" id="avatarDefaultIcon"></i>
                            <img id="avatarPreviewImg" src="" alt="Profile preview" style="display: none;">
                        </div>
                        <div class="avatar-upload-action">
                            <div class="file-input-wrapper">
                                <input type="file" name="photo" id="photo" accept="image/jpeg, image/png, image/webp" class="<?php echo (!empty($data['photo_err'])) ? 'is-invalid' : ''; ?>">
                                <label for="photo" class="file-input-label" id="photos-input-label">
                                    <i class="fas fa-camera"></i> <span id="photos-label-text">Choose Profile Photo</span>
                                </label>
                            </div>
                            <small class="field-hint">JPG, PNG, WebP up to 5MB. A square portrait photo works best.</small>
                        </div>
                    </div>
                    <span class="invalid-feedback"><?php echo $data['photo_err']; ?></span>
                </div>

                <div class="form-group consent-group">
                    <label class="consent-label">
                        <input type="checkbox" name="consent" id="consent" required <?php echo ($data['consent'] == 1) ? 'checked' : ''; ?>>
                        <span>I agree that Workiify may publish my name, company and feedback on its website. <span class="required-star">*</span></span>
                    </label>
                    <span class="invalid-feedback"><?php echo $data['consent_err']; ?></span>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-paper-plane"></i> Submit Feedback
                </button>
            </form>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var grid = document.getElementById('testimonialsGrid');
        if (!grid) return;

        var cards = Array.from(grid.querySelectorAll('.testimonial-card'));

        // --- Testimonials grid pagination (9 per page, 3x3) ---
        var pager = document.getElementById('testimonialsPagination');
        if (pager) {
            var pageBtns = Array.from(pager.querySelectorAll('[data-tpg-page]'));
            var prevBtn = pager.querySelector('[data-tpg-prev]');
            var nextBtn = pager.querySelector('[data-tpg-next]');
            var totalPages = pageBtns.length;
            var currentPage = 1;

            var showPage = function (page) {
                page = Math.min(Math.max(page, 1), totalPages);
                currentPage = page;

                cards.forEach(function (card) {
                    var cardPage = parseInt(card.dataset.tpage, 10);
                    card.style.display = (cardPage === page) ? '' : 'none';
                });

                pageBtns.forEach(function (btn) {
                    btn.classList.toggle('is-active', parseInt(btn.dataset.tpgPage, 10) === page);
                });

                if (prevBtn) prevBtn.disabled = (page === 1);
                if (nextBtn) nextBtn.disabled = (page === totalPages);
            };

            pageBtns.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    showPage(parseInt(btn.dataset.tpgPage, 10));
                    grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });

            if (prevBtn) prevBtn.addEventListener('click', function () {
                showPage(currentPage - 1);
                grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
            if (nextBtn) nextBtn.addEventListener('click', function () {
                showPage(currentPage + 1);
                grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });

            showPage(1);
        }

        // --- Testimonial Full Reader Modal: click a card to view the full review ---
        var tdmModal = document.getElementById('testimonial-detail-modal');
        if (!tdmModal) return;

        var tdmBackdrop = document.getElementById('tdmBackdrop');
        var tdmCloseBtn = document.getElementById('tdmCloseBtn');
        var tdmCloseAction = document.getElementById('tdmCloseAction');
        var tdmPrevBtn = document.getElementById('tdmPrevBtn');
        var tdmNextBtn = document.getElementById('tdmNextBtn');
        var tdmAvatarImg = document.getElementById('tdmAvatarImg');
        var tdmDefaultIcon = document.getElementById('tdmDefaultIcon');
        var tdmAuthorName = document.getElementById('tdmAuthorName');
        var tdmAuthorRole = document.getElementById('tdmAuthorRole');
        var tdmRatingStars = document.getElementById('tdmRatingStars');
        var tdmFeedbackText = document.getElementById('tdmFeedbackText');
        var tdmCounter = document.getElementById('tdmCounter');

        var currentTestimonialIndex = 0;

        function renderTestimonialData(index) {
            if (!cards.length) return;
            currentTestimonialIndex = (index + cards.length) % cards.length;
            var card = cards[currentTestimonialIndex];

            var author = card.dataset.author || '';
            var role = card.dataset.role || '';
            var feedback = card.dataset.feedback || '';
            var avatar = card.dataset.avatar || '';
            var rating = parseInt(card.dataset.rating, 10) || 5;

            tdmAuthorName.textContent = author;
            tdmAuthorRole.textContent = role;
            tdmFeedbackText.textContent = '"' + feedback + '"';

            if (tdmCounter) {
                tdmCounter.textContent = (currentTestimonialIndex + 1) + ' / ' + cards.length;
            }

            if (avatar && avatar.trim() !== '') {
                tdmAvatarImg.src = avatar;
                tdmAvatarImg.style.display = 'block';
                tdmDefaultIcon.style.display = 'none';
            } else {
                tdmAvatarImg.style.display = 'none';
                tdmDefaultIcon.style.display = 'block';
            }

            tdmRatingStars.innerHTML = '';
            for (var s = 0; s < rating; s++) {
                tdmRatingStars.innerHTML += '<i class="fas fa-star"></i>';
            }
            for (var s2 = rating; s2 < 5; s2++) {
                tdmRatingStars.innerHTML += '<i class="far fa-star"></i>';
            }
        }

        function openTestimonialModal(card) {
            var matchedIndex = cards.indexOf(card);
            renderTestimonialData(matchedIndex >= 0 ? matchedIndex : 0);

            tdmModal.style.display = 'flex';
            requestAnimationFrame(function () {
                tdmModal.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            });
        }

        function closeTestimonialModal() {
            tdmModal.classList.remove('is-open');
            document.body.style.overflow = '';
            setTimeout(function () {
                tdmModal.style.display = 'none';
            }, 250);
        }

        cards.forEach(function (card) {
            card.addEventListener('click', function () {
                openTestimonialModal(card);
            });
        });

        if (tdmCloseBtn) tdmCloseBtn.addEventListener('click', closeTestimonialModal);
        if (tdmCloseAction) tdmCloseAction.addEventListener('click', closeTestimonialModal);
        if (tdmBackdrop) tdmBackdrop.addEventListener('click', closeTestimonialModal);

        if (tdmNextBtn) tdmNextBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            renderTestimonialData(currentTestimonialIndex + 1);
        });
        if (tdmPrevBtn) tdmPrevBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            renderTestimonialData(currentTestimonialIndex - 1);
        });

        document.addEventListener('keydown', function (e) {
            if (!tdmModal.classList.contains('is-open')) return;
            if (e.key === 'Escape') {
                closeTestimonialModal();
            } else if (e.key === 'ArrowRight') {
                renderTestimonialData(currentTestimonialIndex + 1);
            } else if (e.key === 'ArrowLeft') {
                renderTestimonialData(currentTestimonialIndex - 1);
            }
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
