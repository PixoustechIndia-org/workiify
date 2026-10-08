<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- 1. Page Banner -->
<section class="page-banner" style="--banner-img: url('<?php echo URLROOT; ?>/images/contact_bg.jpg'); color: #fff;">
    <div class="banner-overlay" style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(30, 58, 138, 0.85) 0%, rgba(37, 99, 235, 0.7) 100%); z-index: 1;"></div>
    <div class="container banner-content text-center" style="position: relative; z-index: 10;">
        <h1 style="margin-bottom: 0.5rem;">Get In Touch</h1>
        <p class="tagline">Have questions or want to book a workspace? Send us a message.</p>
        <div class="breadcrumb" style="margin-top: 1.5rem;">
            <a href="<?php echo URLROOT; ?>/">Home</a> <span>&gt;</span> <span>Contact Us</span>
        </div>
    </div>
</section>

<!-- 2 & 3. Contact Details + Enquiry Form -->
<section id="enquiry" class="contact-section section-padding bg-alt">
    <div class="contact-bg-blob blob-1"></div>
    <div class="contact-bg-blob blob-2"></div>

    <div class="container split-layout contact-grid max-w-1150" style="align-items: stretch; gap: 0;">
        <div class="contact-info" style="display: flex;" data-aos="fade-right" data-aos-duration="900">
            <div class="contact-info-inner" style="width: 100%; display: flex; flex-direction: column;">
                <div class="contact-badge" style="color: var(--primary-color); font-weight: 700; font-size: 0.85rem; letter-spacing: 1px; margin-bottom: 1.5rem; text-transform: uppercase; display: flex; align-items: center; gap: 10px;">
                    <span style="display: block; width: 40px; height: 1px; background: var(--primary-color);"></span>
                    CONTACT DETAILS
                    <span style="display: block; width: 40px; height: 1px; background: var(--primary-color);"></span>
                </div>

                <div class="contact-details" style="display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 2.5rem;">
                    <div class="contact-item" style="display: flex; gap: 1rem; align-items: center;">
                        <span class="contact-icon" style="background: #e0f2fe; color: var(--primary-color); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.2rem; flex-shrink: 0;"><i class="fas fa-map-marker-alt"></i></span>
                        <div>
                            <h4 style="font-size:0.85rem; margin:0 0 5px 0; color: var(--text-main); font-weight: 700;">Located at</h4>
                            <p style="margin:0; font-size:0.9rem; color: var(--text-muted); line-height: 1.4;">AV Info Tech Park, S.F.No:360/4 &amp; 360/5, Keeranatham Road,<br>Near KGISL campus, Saravanampatti, Coimbatore – 641 035, Tamil Nadu, India</p>
                        </div>
                    </div>
                    <div class="contact-item" style="display: flex; gap: 1rem; align-items: center;">
                        <span class="contact-icon" style="background: #e0f2fe; color: var(--primary-color); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.2rem; flex-shrink: 0;"><i class="fas fa-phone"></i></span>
                        <div>
                            <h4 style="font-size:0.85rem; margin:0 0 5px 0; color: var(--text-main); font-weight: 700;">Contact us at</h4>
                            <p style="margin:0; font-size:0.9rem; color: var(--text-muted);"><a href="tel:+919655500001" style="color: inherit;">96555 00001</a></p>
                        </div>
                    </div>
                    <div class="contact-item" style="display: flex; gap: 1rem; align-items: center;">
                        <span class="contact-icon" style="background: #e0f2fe; color: var(--primary-color); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.2rem; flex-shrink: 0;"><i class="fas fa-envelope"></i></span>
                        <div>
                            <h4 style="font-size:0.85rem; margin:0 0 5px 0; color: var(--text-main); font-weight: 700;">Email</h4>
                            <p style="margin:0; font-size:0.9rem; color: var(--text-muted);">
                                <a href="mailto:info@workiify.com" style="color: inherit;">info@workiify.com</a><br>
                                <a href="mailto:spaces@workiify.com" style="color: inherit;">spaces@workiify.com</a>
                            </p>
                        </div>
                    </div>

                    <div class="contact-item" style="display: flex; gap: 1rem; align-items: center;">
                        <span class="contact-icon" style="background: #e0f2fe; color: var(--primary-color); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.2rem; flex-shrink: 0;"><i class="fas fa-clock"></i></span>
                        <div>
                            <h4 style="font-size:0.85rem; margin:0 0 5px 0; color: var(--text-main); font-weight: 700;">Access</h4>
                            <p style="margin:0; font-size:0.9rem; color: var(--text-muted);">24/7 for members</p>
                        </div>
                    </div>
                </div>

                <div class="contact-map" style="position: relative;">
                    <iframe src="https://www.google.com/maps?q=AV+Info+Tech+Park,+Saravanampatti,+Coimbatore&z=16&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <a href="https://maps.google.com/?q=AV+Info+Tech+Park,+Saravanampatti,+Coimbatore" class="btn btn-primary" target="_blank" style="position: absolute; bottom: 15px; left: 50%; transform: translateX(-50%); padding: 8px 20px; font-size: 0.9rem; border-radius: 20px; box-shadow: 0 4px 12px rgba(37,99,235,0.3); z-index: 10;">
                        <i class="fas fa-directions"></i> Get Directions
                    </a>
                </div>

                <div class="contact-social" style="display: flex; align-items: center; gap: 1.5rem; border-top: none; padding-top: 1.5rem; margin-top: auto;">
                    <span style="font-weight:600; margin:0; font-size: 0.95rem; color: var(--text-main);">Follow us</span>
                    <div class="social-icons" style="display: flex; gap: 10px;">
                        <a href="https://www.instagram.com/workiifyspaces/" target="_blank" aria-label="Instagram" style="background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: #fff; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.facebook.com/profile.php?id=61569320400768" target="_blank" aria-label="Facebook" style="background: #1877F2; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: #fff; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://api.whatsapp.com/send/?phone=919655500001&text&type=phone_number&app_absent=0" aria-label="WhatsApp" style="background: #25D366; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: #fff; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" target="_blank"><i class="fab fa-whatsapp"></i></a>
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
                <div class="form-group" style="margin-top: 1.5rem;">
                    <label for="reqType">Requirement *</label>
                    <div class="select-wrapper">
                        <select id="reqType" name="reqType" required style="background: #fff;">
                            <option value="" disabled selected>Select Requirement Type</option>
                            <option value="space">Required Space (in sq. ft.)</option>
                            <option value="seats">Seats (in Nos.)</option>
                        </select>
                        <i class="fas fa-chevron-down select-arrow"></i>
                    </div>
                </div>
                <div class="form-group" style="margin-top: 1.5rem;">
                    <label for="reqValue">Requirement Value *</label>
                    <input type="number" id="reqValue" name="reqValue" required min="1" disabled placeholder="Select a type first" style="background: #f8fafc;">
                </div>
                <div class="form-group" style="margin-top: 1.5rem; margin-bottom: 2rem;">
                    <label for="additional">Any Additional Requirements?</label>
                    <textarea id="additional" name="additional" placeholder="Tell us more about your requirements..." maxlength="1000" rows="3" style="background: #fff;"></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100" style="padding: 1.2rem; font-size: 1.05rem; border-radius: 12px; font-weight: 600;">
                    <i class="fas fa-paper-plane me-2"></i> Submit Enquiry
                </button>
            </form>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reqType = document.getElementById('reqType');
        const reqValue = document.getElementById('reqValue');

        if (reqType && reqValue) {
            reqType.addEventListener('change', function() {
                reqValue.disabled = false;
                if (this.value === 'space') {
                    reqValue.placeholder = "Required Space (in sq. ft.)";
                } else if (this.value === 'seats') {
                    reqValue.placeholder = "Seats (in Nos.)";
                }
            });
        }
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
