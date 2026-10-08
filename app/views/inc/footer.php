    </main>

    <!-- Free Tour Band (FR-GEN-07) -->
    <section class="free-tour-band">
        <div class="container text-center">
            <h2>Book your free coworking tour</h2>
            <p>Whether you're a freelancer, startup or growing team, see the space before you decide.</p>
            <div class="tour-actions">
                <a href="<?php echo URLROOT; ?>/contact-us#enquiry" class="btn btn-primary">Enquire Now</a>
                <a href="https://wa.me/919655500001?text=Hi%20Workiify,%20I'd%20like%20to%20book%20a%20free%20tour" class="btn btn-whatsapp" target="_blank">
                    <i class="fab fa-whatsapp"></i> WhatsApp Us
                </a>
            </div>
        </div>
    </section>

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
                    <span class="brand-name">WORKIIFY</span>
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
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://wa.me/919655500001" aria-label="WhatsApp" target="_blank"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <div class="footer-mini-bottom">
                <div class="footer-address">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>AV Info Tech Park, S.F.No:360/4 & 360/5, Keeranatham Road, Near KGISL campus, Saravanampatti, Coimbatore – 641 035</span>
                </div>
                <span class="footer-mini-copy">&copy; <?php echo date('Y'); ?> Workiify. All rights reserved.</span>
            </div>
        </div>
    </footer>
    
    <!-- Floating WhatsApp Button (FR-GEN-04) -->
    <a href="https://wa.me/919655500001?text=Hi%20Workiify" class="floating-whatsapp" target="_blank" aria-label="Chat on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    
    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="<?php echo URLROOT; ?>/js/main.js"></script>
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
