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

    <!-- Enquiry Popup: shown once per browser session, shortly after the
         visitor's first page view. Stays open indefinitely once they start
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
            <form action="<?php echo URLROOT; ?>/contact-us" method="POST" class="enquiry-form">
                <input type="hidden" name="source" value="popup">
                <div class="form-row">
                    <div class="form-group" style="margin: 0;">
                        <label for="popupCompanyName">Company Name *</label>
                        <input type="text" id="popupCompanyName" name="companyName" placeholder="Enter your company name" required maxlength="150">
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label for="popupCompanyAddress">Company Address *</label>
                        <input type="text" id="popupCompanyAddress" name="companyAddress" placeholder="Enter your company address" required maxlength="250">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="margin: 0;">
                        <label for="popupContactNo">Contact No. *</label>
                        <input type="tel" id="popupContactNo" name="contactNo" placeholder="+91 98765 43210" required pattern="[6-9][0-9]{9}">
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label for="popupEmail">Organization E-Mail ID *</label>
                        <input type="email" id="popupEmail" name="email" placeholder="you@company.com" required>
                    </div>
                </div>
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="popupReqType">Requirement *</label>
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
                    <label for="popupReqValue">Requirement Value *</label>
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
</body>
</html>
