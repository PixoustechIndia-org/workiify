    </main>

    <?php if (empty($data['hideTourBand'])): ?>
    <!-- Free Tour Band (FR-GEN-07) -->
    <section class="free-tour-band">
        <div class="container text-center">
            <h2>Book your free coworking tour</h2>
            <p>Whether you're a freelancer, startup or growing team, see the space before you decide.</p>
            <div class="tour-actions">
                <a href="<?php echo URLROOT; ?>/contact-us#enquiry" class="btn btn-primary">Enquire Now</a>
                <a href="https://api.whatsapp.com/send/?phone=919655500001&text=Hi%20Workiify,%20I'd%20like%20to%20book%20a%20free%20tour&type=phone_number&app_absent=0" class="btn btn-whatsapp" target="_blank">
                    <i class="fab fa-whatsapp"></i> WhatsApp Us
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php 
    // Show footer testimonial section on pages other than /testimonials (where the form is already on the page)
    $isTestimonialsUrl = (strpos($_SERVER['REQUEST_URI'] ?? '', '/testimonials') !== false);
    if (empty($data['hideFooterTestimonial']) && !$isTestimonialsUrl): 
    ?>
    <!-- Footer Testimonial Form Section -->
    <section class="footer-testimonial-section" id="footer-feedback">
        <div class="container max-w-1000">
            <div class="footer-testimonial-card" data-aos="fade-up">
                <div class="footer-testimonial-header text-center">
                    <span class="ft-eyebrow"><i class="fas fa-heart"></i> Share Your Experience</span>
                    <h3 class="ft-title">Help Our Coworking Community Grow</h3>
                    <p class="ft-subtitle">Worked from Workiify? Tell us what you loved about our workspace, amenities, or team.</p>
                </div>

                <div id="footerFeedbackAlert" class="alert" style="display: none;"></div>

                <form action="<?php echo URLROOT; ?>/testimonials" method="POST" enctype="multipart/form-data" class="feedback-form footer-feedback-form" id="footerFeedbackForm" novalidate>
                    <input type="hidden" name="is_ajax" value="1">
                    <input type="hidden" name="honeypot" value="" style="display: none;">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="ft_full_name">Full Name <span class="required-star">*</span></label>
                            <input type="text" name="full_name" id="ft_full_name" required maxlength="100" placeholder="Your name">
                            <span class="invalid-feedback" id="ft_full_name_err"></span>
                        </div>
                        <div class="form-group">
                            <label for="ft_email">Email <span class="required-star">*</span> <small>(never published)</small></label>
                            <input type="email" name="email" id="ft_email" required placeholder="you@company.com">
                            <span class="invalid-feedback" id="ft_email_err"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="ft_company">Company <small>(Optional)</small></label>
                            <input type="text" name="company" id="ft_company" maxlength="150" placeholder="Where you work">
                        </div>
                        <div class="form-group">
                            <label for="ft_designation">Designation <small>(Optional)</small></label>
                            <input type="text" name="designation" id="ft_designation" maxlength="100" placeholder="Your role / title">
                        </div>
                    </div>

                    <div class="form-group ft-rating-group">
                        <label class="ft-rating-label">Rating <span class="required-star">*</span></label>
                        <div class="rating-input ft-rating-input">
                            <input type="radio" id="ft_star5" name="rating" value="5" checked required>
                            <label for="ft_star5" title="5 stars"><i class="fas fa-star"></i></label>
                            <input type="radio" id="ft_star4" name="rating" value="4">
                            <label for="ft_star4" title="4 stars"><i class="fas fa-star"></i></label>
                            <input type="radio" id="ft_star3" name="rating" value="3">
                            <label for="ft_star3" title="3 stars"><i class="fas fa-star"></i></label>
                            <input type="radio" id="ft_star2" name="rating" value="2">
                            <label for="ft_star2" title="2 stars"><i class="fas fa-star"></i></label>
                            <input type="radio" id="ft_star1" name="rating" value="1">
                            <label for="ft_star1" title="1 star"><i class="fas fa-star"></i></label>
                        </div>
                        <span class="invalid-feedback" id="ft_rating_err"></span>
                    </div>

                    <div class="form-group">
                        <label for="ft_feedback">Your Feedback <span class="required-star">*</span></label>
                        <textarea name="feedback" id="ft_feedback" rows="3" required minlength="20" maxlength="1000" placeholder="Share a few sentences about your experience at Workiify (min. 20 characters)..."></textarea>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <small class="field-hint">Between 20 and 1000 characters.</small>
                            <small class="field-hint" id="ftCharCount">0 / 1000</small>
                        </div>
                        <span class="invalid-feedback" id="ft_feedback_err"></span>
                    </div>

                    <div class="form-group">
                        <label for="ft_photo">Profile Photo <small>(Optional)</small></label>
                        <div class="avatar-upload-row ft-avatar-row">
                            <div class="avatar-preview-box" id="ftAvatarPreviewBox">
                                <i class="fas fa-user-circle default-avatar-icon" id="ftAvatarDefaultIcon"></i>
                                <img id="ftAvatarPreviewImg" src="" alt="Profile preview" style="display: none;">
                            </div>
                            <div class="avatar-upload-action">
                                <div class="file-input-wrapper">
                                    <input type="file" name="photo" id="ft_photo" accept="image/jpeg, image/png, image/webp">
                                    <label for="ft_photo" class="file-input-label" id="ft_photos_label">
                                        <i class="fas fa-camera"></i> <span id="ft_photos_label_text">Choose Profile Photo</span>
                                    </label>
                                </div>
                                <small class="field-hint">Square portrait photo recommended (JPG, PNG, WebP up to 5MB).</small>
                            </div>
                        </div>
                        <span class="invalid-feedback" id="ft_photo_err"></span>
                    </div>

                    <div class="form-group consent-group">
                        <label class="consent-label">
                            <input type="checkbox" name="consent" id="ft_consent" value="1" checked required>
                            <span>I agree that Workiify may publish my name, company and feedback on its website. <span class="required-star">*</span></span>
                        </label>
                        <span class="invalid-feedback" id="ft_consent_err"></span>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-pill ft-submit-btn" id="ftSubmitBtn">
                            <span>Submit Your Testimonial</span>
                            <i class="fas fa-paper-plane ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer (FR-GEN-03) -->
    <footer class="site-footer-mini">
        <div class="container">
            <div class="footer-mini-inner">
                <!-- Logo -->
                <a href="<?php echo URLROOT; ?>/" class="footer-mini-logo">
                    <div class="brand-icon">
                        <img src="<?php echo URLROOT; ?>/images/logo-light.svg" alt="Workiify" class="logo-light">
                        <img src="<?php echo URLROOT; ?>/images/logo-dark.svg" alt="Workiify" class="logo-dark">
                    </div>
                    <div class="brand-text">
                        <span class="brand-name">WORKIIFY</span>
                        <span class="tagline">Workspace Simplified</span>
                    </div>
                </a>

                <!-- Nav links -->
                <nav class="footer-mini-nav">
                    <a href="<?php echo URLROOT; ?>/">Home</a>
                    <a href="<?php echo URLROOT; ?>/about-us">About</a>
                    <a href="<?php echo URLROOT; ?>/services">Services</a>
                    <a href="<?php echo URLROOT; ?>/gallery">Gallery</a>
                    <a href="<?php echo URLROOT; ?>/testimonials">Testimonials</a>
                    <a href="<?php echo URLROOT; ?>/contact-us">Contact</a>
                    <a href="<?php echo URLROOT; ?>/privacy-policy">Privacy</a>
                </nav>

                <!-- Socials -->
                <div class="footer-mini-socials">
                    <a href="https://www.instagram.com/workiifyspaces/" aria-label="Instagram" style="background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); color: #fff; border:none;" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.facebook.com/profile.php?id=61569320400768" aria-label="Facebook" style="background: #1877F2; color: #fff; border:none;" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://api.whatsapp.com/send/?phone=919655500001&text&type=phone_number&app_absent=0" aria-label="WhatsApp" target="_blank" style="background: #25D366; color: #fff; border:none;"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <div class="footer-mini-bottom">
                <div class="footer-address">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>AV Info Tech Park, S.F.No:360/4 & 360/5, Keeranatham Road, Near KGISL campus, Saravanampatti, Coimbatore – 641 035</span>
                </div>
                <span class="footer-mini-copy">&copy; <?php echo date('Y'); ?> Workiify. All rights reserved. &middot; <a href="<?php echo URLROOT; ?>/admin" style="color:inherit; opacity:0.6;">Admin</a></span>
            </div>
        </div>
    </footer>

    <!-- Enquiry Popup: shown once per browser session, 20 seconds after the
         visitor first arrives on the site. Stays open indefinitely once they start
         filling it in; otherwise auto-dismisses after 15s. Always closable. -->
    <div class="enquiry-popup" id="enquiryPopup" role="dialog" aria-label="Quick enquiry" aria-hidden="true">
        <div class="enquiry-popup-backdrop"></div>
        <div class="enquiry-popup-panel">
            <button type="button" class="enquiry-popup-close" aria-label="Close">
                <i class="fas fa-xmark"></i>
            </button>
            <div class="enquiry-popup-header">
                <i class="fas fa-paper-plane"></i>
                <div>
                    <h3>Looking for a workspace?</h3>
                    <p>Send us your requirement and we'll get back to you with the right plan.</p>
                </div>
            </div>
            <form action="<?php echo URLROOT; ?>/contact-us" method="POST" class="enquiry-form" novalidate>
                <input type="hidden" name="source" value="popup">
                <div class="form-row">
                    <div class="form-group" style="margin: 0;">
                        <label for="popupCompanyName">Company Name <span class="required-star">*</span></label>
                        <input type="text" id="popupCompanyName" name="companyName" placeholder="Company Name" required maxlength="150">
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label for="popupCompanyAddress">Company Address <span class="required-star">*</span></label>
                        <input type="text" id="popupCompanyAddress" name="companyAddress" placeholder="Company Address" required maxlength="250">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="margin: 0;">
                        <label for="popupContactNo">Contact No. <span class="required-star">*</span></label>
                        <input type="tel" id="popupContactNo" name="contactNo" placeholder="Contact No." required pattern="[6-9][0-9]{9}">
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label for="popupEmail">Organization E-Mail ID <span class="required-star">*</span></label>
                        <input type="email" id="popupEmail" name="email" placeholder="Organization E-Mail ID" required>
                    </div>
                </div>
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="popupReqType">Requirement <span class="required-star">*</span></label>
                    <div class="select-wrapper">
                        <select id="popupReqType" name="reqType" required style="color: var(--text-muted);" onchange="this.style.color='var(--text-main)';">
                            <option value="" disabled selected>Select Requirement Type</option>
                            <option value="space">Required Space (in sq. ft.)</option>
                            <option value="seats">Seats (in Nos.)</option>
                        </select>
                        <i class="fas fa-chevron-down select-arrow"></i>
                    </div>
                </div>
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="popupReqValue">Requirement Value <span class="required-star">*</span></label>
                    <input type="number" id="popupReqValue" name="reqValue" required min="1" disabled placeholder="Select a type first">
                </div>
                <input type="hidden" name="additional" value="">
                <div class="enquiry-result" role="status" aria-live="polite"></div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-paper-plane"></i> Submit Enquiry
                </button>
            </form>
        </div>
    </div>

    <!-- Chat Assistant: a quick-reply widget (no AI backend) that answers the
         most common questions on the spot, with WhatsApp always one tap away
         for anything that needs a real person. -->
    <button type="button" class="chatbot-fab" id="chatbotFab" aria-label="Open chat assistant" aria-expanded="false">
        <img src="<?php echo URLROOT; ?>/images/workiify-chatbot-icon.svg" alt="" width="60" height="60">
    </button>

    <div class="chatbot-panel" id="chatbotPanel" role="dialog" aria-label="Workiify chat assistant" aria-hidden="true">
        <div class="chatbot-panel-header">
            <img src="<?php echo URLROOT; ?>/images/workiify-chatbot-icon.svg" alt="" width="36" height="36">
            <div class="chatbot-panel-title">
                <strong>Workiify Assistant</strong>
                <span>Quick answers, any time</span>
            </div>
            <button type="button" class="chatbot-panel-close" id="chatbotClose" aria-label="Close chat">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        <div class="chatbot-messages" id="chatbotMessages">
            <div class="chatbot-bubble bot">
                Hi! 👋 I'm the Workiify assistant. Type your question below, or pick a topic to get started.
            </div>
            <div class="chatbot-quick-replies" id="chatbotQuickReplies">
                <button type="button" class="chatbot-chip" data-topic="pricing">💰 Pricing &amp; Plans</button>
                <button type="button" class="chatbot-chip" data-topic="location">📍 Our Location</button>
                <button type="button" class="chatbot-chip" data-topic="services">🏢 Services We Offer</button>
                <button type="button" class="chatbot-chip" data-topic="tour">📅 Book a Tour</button>
            </div>
        </div>

        <form class="chatbot-input-row" id="chatbotForm">
            <input type="text" id="chatbotInput" placeholder="Type your question..." autocomplete="off" maxlength="200">
            <button type="submit" class="chatbot-send" aria-label="Send">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>

    <!-- JS -->
    <script>const SITE_URLROOT = <?php echo json_encode(URLROOT); ?>;</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="<?php echo URLROOT; ?>/js/main.js?v=<?php echo time(); ?>"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            offset: 80,
            once: true
        });
    </script>
    <script>
        // Footer Testimonial Form Handler
        document.addEventListener('DOMContentLoaded', function() {
            const ftForm = document.getElementById('footerFeedbackForm');
            if (!ftForm) return;

            const ftFeedback = document.getElementById('ft_feedback');
            const ftCharCount = document.getElementById('ftCharCount');
            const ftPhotoInput = document.getElementById('ft_photo');
            const ftAvatarImg = document.getElementById('ftAvatarPreviewImg');
            const ftAvatarDefault = document.getElementById('ftAvatarDefaultIcon');
            const ftLabelText = document.getElementById('ft_photos_label_text');
            const ftAlert = document.getElementById('footerFeedbackAlert');
            const ftSubmitBtn = document.getElementById('ftSubmitBtn');

            // Live character count
            if (ftFeedback && ftCharCount) {
                ftFeedback.addEventListener('input', function() {
                    const len = this.value.length;
                    ftCharCount.textContent = len + ' / 1000';
                    ftCharCount.style.color = (len >= 20 && len <= 1000) ? 'var(--text-muted)' : '#ef4444';
                });
            }

            // Photo preview
            if (ftPhotoInput) {
                ftPhotoInput.addEventListener('change', function() {
                    const file = this.files && this.files[0];
                    if (file) {
                        if (ftLabelText) ftLabelText.textContent = file.name;
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            if (ftAvatarImg) {
                                ftAvatarImg.src = e.target.result;
                                ftAvatarImg.style.display = 'block';
                            }
                            if (ftAvatarDefault) ftAvatarDefault.style.display = 'none';
                        };
                        reader.readAsDataURL(file);
                    } else {
                        if (ftLabelText) ftLabelText.textContent = 'Choose Profile Photo';
                        if (ftAvatarImg) {
                            ftAvatarImg.src = '';
                            ftAvatarImg.style.display = 'none';
                        }
                        if (ftAvatarDefault) ftAvatarDefault.style.display = 'block';
                    }
                });
            }

            // AJAX Form submission
            ftForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Clear previous errors
                ftForm.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
                ftForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                if (ftAlert) ftAlert.style.display = 'none';

                const fullName = document.getElementById('ft_full_name');
                const email = document.getElementById('ft_email');
                const feedback = document.getElementById('ft_feedback');
                const consent = document.getElementById('ft_consent');

                let hasErr = false;
                if (!fullName.value.trim()) {
                    fullName.classList.add('is-invalid');
                    const errEl = document.getElementById('ft_full_name_err');
                    if (errEl) errEl.textContent = 'Please enter your full name.';
                    hasErr = true;
                }
                if (!email.value.trim() || !email.value.includes('@')) {
                    email.classList.add('is-invalid');
                    const errEl = document.getElementById('ft_email_err');
                    if (errEl) errEl.textContent = 'Please enter a valid email address.';
                    hasErr = true;
                }
                if (feedback.value.trim().length < 20) {
                    feedback.classList.add('is-invalid');
                    const errEl = document.getElementById('ft_feedback_err');
                    if (errEl) errEl.textContent = 'Feedback must be at least 20 characters.';
                    hasErr = true;
                }
                if (!consent.checked) {
                    consent.classList.add('is-invalid');
                    const errEl = document.getElementById('ft_consent_err');
                    if (errEl) errEl.textContent = 'You must agree to publish your feedback.';
                    hasErr = true;
                }

                if (hasErr) return;

                const originalBtnHtml = ftSubmitBtn.innerHTML;
                ftSubmitBtn.disabled = true;
                ftSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Submitting...';

                const formData = new FormData(ftForm);

                fetch(ftForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    ftSubmitBtn.disabled = false;
                    ftSubmitBtn.innerHTML = originalBtnHtml;

                    if (data.status === 'success') {
                        ftForm.reset();
                        if (ftCharCount) ftCharCount.textContent = '0 / 1000';
                        if (ftAvatarImg) {
                            ftAvatarImg.src = '';
                            ftAvatarImg.style.display = 'none';
                        }
                        if (ftAvatarDefault) ftAvatarDefault.style.display = 'block';
                        if (ftLabelText) ftLabelText.textContent = 'Choose Profile Photo';

                        if (ftAlert) {
                            ftAlert.className = 'alert alert-success';
                            ftAlert.innerHTML = '<i class="fas fa-check-circle me-2"></i> ' + (data.message || 'Thank you! Your feedback has been submitted successfully and is pending review.');
                            ftAlert.style.display = 'block';
                            ftAlert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }
                    } else if (data.status === 'error' && data.errors) {
                        for (const [key, msg] of Object.entries(data.errors)) {
                            const errEl = document.getElementById('ft_' + key + '_err');
                            const inputEl = document.getElementById('ft_' + key);
                            if (errEl) errEl.textContent = msg;
                            if (inputEl) inputEl.classList.add('is-invalid');
                        }
                        if (data.errors.general && ftAlert) {
                            ftAlert.className = 'alert alert-danger';
                            ftAlert.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i> ' + data.errors.general;
                            ftAlert.style.display = 'block';
                        }
                    }
                })
                .catch(() => {
                    ftSubmitBtn.disabled = false;
                    ftSubmitBtn.innerHTML = originalBtnHtml;
                    ftForm.submit();
                });
            });
        });
    </script>
</body>
</html>
