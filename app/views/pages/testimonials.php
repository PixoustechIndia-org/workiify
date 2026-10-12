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
<section class="section-padding bg-main">
    <div class="container max-w-1100">
        <div class="text-center mb-5">
            <span class="category-eyebrow">Our Community</span>
            <h2 class="section-title">What They Say About Us</h2>
        </div>

        <?php if (!empty($data['testimonials'])) : ?>
            <div class="testimonials-grid">
                <?php foreach ($data['testimonials'] as $testimonial) : ?>
                    <div class="testimonial-card">
                        <div class="testimonial-card-header">
                            <div class="rating">
                                <?php for ($i = 0; $i < $testimonial->rating; $i++) : ?>
                                    <i class="fas fa-star"></i>
                                <?php endfor; ?>
                                <?php for ($i = $testimonial->rating; $i < 5; $i++) : ?>
                                    <i class="far fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <i class="fas fa-quote-left quote-icon"></i>
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

<?php require APPROOT . '/views/inc/footer.php'; ?>
