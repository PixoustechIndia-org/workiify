<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- 1. Hero Section -->
<section class="hero-section" id="hero-slider">
    <!-- Slide 1 -->
    <div class="slide active">
        <div class="slide-bg" style="background-image: url('<?php echo URLROOT; ?>/images/coworking_main_1791350864870.jpg');"></div>
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <h1>
                    Premium Coworking Space<br>
                    for <span class="highlight" style="color: #4ade80;">Modern Professionals</span>
                </h1>
                <p>Experience stylish, flexible, and productive workspaces in Saravanampatti, Coimbatore. Designed for startups, freelancers, remote teams, and growing businesses.</p>
                <div class="hero-buttons">
                    <a href="<?php echo URLROOT; ?>/contact-us#enquiry" class="btn btn-primary">Book an Enquiry</a>
                    <a href="https://wa.me/919655500001?text=Hi%20Workiify" class="btn btn-whatsapp" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 2 -->
    <div class="slide">
        <div class="slide-bg" style="background-image: url('<?php echo URLROOT; ?>/images/coworking_private_1791350889843.jpg');"></div>
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <h1>
                    Private Offices for<br>
                    <span class="highlight" style="color: #4ade80;">Growing Teams</span>
                </h1>
                <p>Focus and scale your business with fully serviced private suites tailored to your team's needs.</p>
                <div class="hero-buttons">
                    <a href="<?php echo URLROOT; ?>/services" class="btn btn-primary">View Private Offices</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 3 -->
    <div class="slide">
        <div class="slide-bg" style="background-image: url('<?php echo URLROOT; ?>/images/coworking_lounge_1791350901804.jpg');"></div>
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <h1>
                    Creative Lounges to<br>
                    <span class="highlight" style="color: #4ade80;">Connect & Collaborate</span>
                </h1>
                <p>Network with like-minded professionals in our vibrant, high-end community spaces.</p>
                <div class="hero-buttons">
                    <a href="<?php echo URLROOT; ?>/contact-us#enquiry" class="btn btn-primary">Book a Tour</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Slider Controls -->
    <button class="slider-btn prev-btn" id="hero-prev"><i class="fas fa-chevron-left"></i></button>
    <button class="slider-btn next-btn" id="hero-next"><i class="fas fa-chevron-right"></i></button>
    <!-- Hero Counters (Inside Hero) -->
    <div class="hero-counters-wrapper" id="stats">
        <div class="container">
            <div class="counters-grid">
                <div class="counter-card">
                    <div class="counter-line"></div>
                    <h3><span class="odometer" data-target="24">0</span>/7</h3>
                    <p>Access available</p>
                </div>
                <div class="counter-card">
                    <div class="counter-line"></div>
                    <h3><span class="odometer" data-target="5">0</span></h3>
                    <p>Floors</p>
                </div>
                <div class="counter-card">
                    <div class="counter-line"></div>
                    <h3><span class="odometer" data-target="10000">0</span> sq ft</h3>
                    <p>Per floor</p>
                </div>
                <div class="counter-card">
                    <div class="counter-line"></div>
                    <h3><span class="odometer" data-target="120">0</span></h3>
                    <p>Seats per floor</p>
                </div>
            </div>
            <p class="counters-note">Fully customisable workspaces</p>
        </div>
    </div>
</section>

<!-- 3. Our Services -->
<section class="services-overview section-padding">
    <div class="container text-center">
        <h2 class="section-title">Our Services</h2>
        <p class="section-subtitle">Flexible workspace solutions crafted for productivity, comfort, and business growth.</p>
        
        <div class="services-grid grid-fixed-4">
            <a href="<?php echo URLROOT; ?>/services/hot-desk" class="service-card" data-aos="fade-up" data-aos-delay="0">
                <img src="<?php echo URLROOT; ?>/images/hot_desk_enhanced_1791353156473.jpg" alt="Hot Desk" class="service-img" style="object-fit: cover; width: 100%; height: 200px;">
                <div class="service-content">
                    <h3>Hot Desk</h3>
                    <p>Perfect for freelancers and remote workers who need a vibrant and flexible workspace every day.</p>
                    <div class="service-arrow"><i class="fas fa-arrow-right"></i></div>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/services/dedicated-desk" class="service-card" data-aos="fade-up" data-aos-delay="100">
                <img src="<?php echo URLROOT; ?>/images/dedicated_desk_enhanced_1791353327423.jpg" alt="Dedicated Desk" class="service-img" style="object-fit: cover; width: 100%; height: 200px;">
                <div class="service-content">
                    <h3>Dedicated Desk</h3>
                    <p>Your own reserved workstation in a collaborative environment with professional amenities.</p>
                    <div class="service-arrow"><i class="fas fa-arrow-right"></i></div>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/services/private-office" class="service-card" data-aos="fade-up" data-aos-delay="200">
                <img src="<?php echo URLROOT; ?>/images/private_office_enhanced_1791353356099.jpg" alt="Private Office" class="service-img" style="object-fit: cover; width: 100%; height: 200px;">
                <div class="service-content">
                    <h3>Private Office</h3>
                    <p>Secure and fully furnished office spaces ideal for startups, teams, and established businesses.</p>
                    <div class="service-arrow"><i class="fas fa-arrow-right"></i></div>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/services/meeting-room" class="service-card" data-aos="fade-up" data-aos-delay="300">
                <img src="<?php echo URLROOT; ?>/images/meeting_room_enhanced_1791353342929.jpg" alt="Meeting Room" class="service-img" style="object-fit: cover; width: 100%; height: 200px;">
                <div class="service-content">
                    <h3>Meeting Room</h3>
                    <p>Host client meetings, team discussions, and presentations in a polished and well-equipped setting.</p>
                    <div class="service-arrow"><i class="fas fa-arrow-right"></i></div>
                </div>
            </a>
        </div>
        <a href="<?php echo URLROOT; ?>/services" class="btn btn-primary mt-4">View All Services</a>
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
        <h2 class="section-title amenities-title" style="color: var(--text-main);">AMENITIES</h2>
        <p class="section-subtitle amenities-subtitle" style="color: var(--text-muted);">Everything your workspace needs to keep moving.</p>
        
        <div class="amenities-grid">
            <div class="amenity-item" data-context="Always open, always active">
                <div class="amenity-icon-wrapper"><i class="fas fa-clock"></i></div>
                <p>24/7 Access &amp; Security</p>
                <div class="amenity-context">Always active</div>
            </div>
            <div class="amenity-item" data-context="1 Gbps Dedicated Fiber">
                <div class="amenity-icon-wrapper"><i class="fas fa-wifi"></i></div>
                <p>High-speed Fibre Wi-Fi</p>
                <div class="amenity-context">1 Gbps Fiber</div>
            </div>
            <div class="amenity-item" data-context="Limited spots available">
                <div class="amenity-icon-wrapper"><i class="fas fa-parking"></i></div>
                <p>Parking (limited)</p>
                <div class="amenity-context">Limited spots</div>
            </div>
            <div class="amenity-item" data-context="Fresh meals & coffee">
                <div class="amenity-icon-wrapper"><i class="fas fa-utensils"></i></div>
                <p>Cafeteria</p>
                <div class="amenity-context">Fresh daily</div>
            </div>
            <div class="amenity-item" data-context="Tea & coffee on tap">
                <div class="amenity-icon-wrapper"><i class="fas fa-mug-hot"></i></div>
                <p>Kitchen with Tea &amp; Coffee</p>
                <div class="amenity-context">On tap all day</div>
            </div>
            <div class="amenity-item" data-context="Optimal climate control">
                <div class="amenity-icon-wrapper"><i class="fas fa-snowflake"></i></div>
                <p>AC</p>
                <div class="amenity-context">Auto-regulated</div>
            </div>
            <div class="amenity-item" data-context="24 hr coverage">
                <div class="amenity-icon-wrapper"><i class="fas fa-bolt"></i></div>
                <p>Power Backup</p>
                <div class="amenity-context">24 hr coverage</div>
            </div>
            <div class="amenity-item" data-context="Daily professional cleaning">
                <div class="amenity-icon-wrapper"><i class="fas fa-broom"></i></div>
                <p>Housekeeping</p>
                <div class="amenity-context">Sanitized daily</div>
            </div>
            <div class="amenity-item" data-context="Secure personal storage">
                <div class="amenity-icon-wrapper"><i class="fas fa-lock"></i></div>
                <p>Lockers</p>
                <div class="amenity-context">Secure access</div>
            </div>
            <div class="amenity-item" data-context="Wireless printing & copying">
                <div class="amenity-icon-wrapper"><i class="fas fa-print"></i></div>
                <p>Printing &amp; Copying</p>
                <div class="amenity-context">Wireless active</div>
            </div>
            <div class="amenity-item" data-context="24/7 Monitored">
                <div class="amenity-icon-wrapper"><i class="fas fa-video"></i></div>
                <p>CCTV</p>
                <div class="amenity-context">24/7 Monitored</div>
            </div>
            <div class="amenity-item" data-context="Front desk support">
                <div class="amenity-icon-wrapper"><i class="fas fa-concierge-bell"></i></div>
                <p>Reception</p>
                <div class="amenity-context">Front desk support</div>
            </div>
        </div>
    </div>
</section>

<!-- 5. Welcome to Workiify -->
<section class="welcome-section section-padding" style="background-color: #ffffff;">
    <div class="container split-layout max-w-1100" style="align-items: center; gap: 4rem;">
        <div class="split-img" style="flex: 1.2;" data-aos="fade-right" data-aos-duration="900">
            <img src="<?php echo URLROOT; ?>/images/welcome-office.png" alt="Welcome to Workiify" style="width: 100%; border-radius: 0; box-shadow: 0 10px 30px rgba(0,0,0,0.08); object-fit: cover;">
        </div>
        <div class="split-content" data-aos="fade-left" data-aos-duration="900">
            <h2 class="section-title">Welcome to Workiify</h2>
            <p>Workiify is designed to deliver a premium coworking experience with elegant interiors, flexible membership options, and a professional business atmosphere. Located near the KGISL campus in Coimbatore, our space supports individuals and teams looking for a smart and inspiring environment to work and grow.</p>
            <a href="<?php echo URLROOT; ?>/about-us" class="btn btn-primary mt-4">Read More</a>
        </div>
    </div>
</section>

<!-- 6. Why Choose Us -->
<section class="why-choose-section section-padding bg-alt">
    <div class="container split-layout reverse max-w-1100">
        <div class="split-content" data-aos="fade-right" data-aos-duration="900">
            <h2 class="section-title">Why Choose Us</h2>
            <ul class="feature-list">
                <li><i class="fas fa-check-circle"></i> Prime business location</li>
                <li><i class="fas fa-check-circle"></i> Premium interior ambiance</li>
                <li><i class="fas fa-check-circle"></i> Flexible workspace options</li>
                <li><i class="fas fa-check-circle"></i> Ideal for startups, consultants, and remote teams</li>
            </ul>
        </div>
        <div class="split-img placeholder-img" data-aos="fade-left" data-aos-duration="900">Why Choose Us Image</div>
    </div>
</section>

<!-- 7. Who It's For -->
<section class="trusted-section section-padding">
    <div class="container text-center">
        <h2 class="section-title" data-aos="fade-up">Who It's For</h2>
        <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Built for freelancers, startups, consultants, and modern businesses.</p>
        <div class="teams-grid">
            <!-- Card 1: SMEs -->
            <div class="team-tile tile-1" data-aos="fade-up" data-aos-delay="0">
                <div class="team-tile-icon"><i class="fas fa-rocket"></i></div>
                <h3>SMEs &amp; Entrepreneurs</h3>
                <p>Growing businesses that need flexible, scalable space.</p>
            </div>
            
            <!-- Card 2: Corporates -->
            <div class="team-tile tile-2" data-aos="fade-up" data-aos-delay="100">
                <div class="team-tile-icon"><i class="fas fa-building"></i></div>
                <h3>Corporates</h3>
                <p>Established companies looking for a professional satellite office.</p>
            </div>
            
            <!-- Card 3: Freelancers -->
            <div class="team-tile tile-3" data-aos="fade-up" data-aos-delay="200">
                <div class="team-tile-icon"><i class="fas fa-briefcase"></i></div>
                <h3>Freelancers &amp; Self-Employed</h3>
                <p>Independent professionals who need a productive, inspiring base.</p>
            </div>
            
            <!-- Card 4: Students -->
            <div class="team-tile tile-4" data-aos="fade-up" data-aos-delay="300">
                <div class="team-tile-icon"><i class="fas fa-graduation-cap"></i></div>
                <h3>Students</h3>
                <p>Students who need a focused space to study and collaborate.</p>
            </div>
        </div>
    </div>
</section>

<!-- 8. Testimonials -->
<section class="testimonials-section section-padding bg-alt">
    <div class="container text-center max-w-1150">
        <h2 class="section-title">What Our Members Say</h2>
        <div class="google-rating">
            <i class="fab fa-google"></i> <span>5.0</span>
            <div class="stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
        </div>
        
        <div class="testimonials-grid mt-4">
            <div class="testimonial-card" data-aos="fade-up" data-aos-delay="0">
                <p>"Workiify gives our team the right mix of professionalism, comfort, and convenience. The location and ambiance are excellent."</p>
                <h4>Ravi K., Founder, TechStart</h4>
            </div>
            <div class="testimonial-card" data-aos="fade-up" data-aos-delay="150">
                <p>"A very polished coworking environment for meetings and focused work. Perfect for consultants and hybrid teams."</p>
                <h4>Priya S., Consultant</h4>
            </div>
            <div class="testimonial-card" data-aos="fade-up" data-aos-delay="300">
                <p>"Flexible options, premium look, and a strong business vibe. It feels like a modern office, not just a desk rental."</p>
                <h4>Arun M., Startup Founder</h4>
            </div>
        </div>
    </div>
</section>

<!-- 9. Workspace Gallery -->
<section class="gallery-preview section-padding">
    <div class="container">
        <div class="gallery-header">
            <div>
                <h2 class="section-title">Workspace Gallery</h2>
                <p class="section-subtitle">A glimpse into the premium Workiify experience.</p>
            </div>
            <a href="<?php echo URLROOT; ?>/gallery" class="btn btn-outline btn-pill">View More Photos</a>
        </div>

        <div class="gallery-grid mt-4">
            <div class="gallery-img placeholder-img">Lounge</div>
            <div class="gallery-img placeholder-img">Open Desks</div>
            <div class="gallery-img placeholder-img">Meeting Room</div>
            <div class="gallery-img placeholder-img">Cafeteria</div>
            <div class="gallery-img placeholder-img">Private Office</div>
            <div class="gallery-img placeholder-img">Reception</div>
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
                    GET IN TOUCH
                    <span style="display: block; width: 40px; height: 1px; background: var(--primary-color);"></span>
                </div>
                
                <h2 class="section-title contact-heading" style="line-height: 1.2; margin-bottom: 1rem;">
                    We're Here to Help You<br>
                    <span style="color: var(--primary-color);">Find Your Perfect Workspace</span>
                </h2>
                <p style="font-size: 1.05rem; color: var(--text-muted); max-width: 500px; margin-bottom: 2.5rem; line-height: 1.6;">Have questions or want to book a workspace? Send us a message and our team will get back to you shortly.</p>

                <div class="contact-details" style="display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 2.5rem;">
                    <div class="contact-item" style="display: flex; gap: 1rem; align-items: center;">
                        <span class="contact-icon" style="background: #e0f2fe; color: var(--primary-color); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.2rem; flex-shrink: 0;"><i class="fas fa-map-marker-alt"></i></span>
                        <div>
                            <h4 style="font-size:0.85rem; margin:0 0 5px 0; color: var(--text-main); font-weight: 700;">Located at</h4>
                            <p style="margin:0; font-size:0.9rem; color: var(--text-muted); line-height: 1.4;">AV Info Tech Park,<br>Saravanampatti, Coimbatore.</p>
                        </div>
                    </div>
                    <div class="contact-item" style="display: flex; gap: 1rem; align-items: center;">
                        <span class="contact-icon" style="background: #e0f2fe; color: var(--primary-color); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.2rem; flex-shrink: 0;"><i class="fas fa-phone"></i></span>
                        <div>
                            <h4 style="font-size:0.85rem; margin:0 0 5px 0; color: var(--text-main); font-weight: 700;">Call us</h4>
                            <p style="margin:0; font-size:0.9rem; color: var(--text-muted);"><a href="tel:+919655500001" style="color: inherit;">96555 00001</a></p>
                        </div>
                    </div>
                    <div class="contact-item" style="display: flex; gap: 1rem; align-items: center;">
                        <span class="contact-icon" style="background: #e0f2fe; color: var(--primary-color); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.2rem; flex-shrink: 0;"><i class="fas fa-envelope"></i></span>
                        <div>
                            <h4 style="font-size:0.85rem; margin:0 0 5px 0; color: var(--text-main); font-weight: 700;">Email</h4>
                            <p style="margin:0; font-size:0.9rem; color: var(--text-muted);"><a href="mailto:info@workiify.com" style="color: inherit;">info@workiify.com</a></p>
                        </div>
                    </div>
                </div>

                <div class="contact-map">
                    <iframe src="https://www.google.com/maps?q=AV+Info+Tech+Park,+Saravanampatti,+Coimbatore&z=16&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <a href="https://maps.google.com/?q=AV+Info+Tech+Park,+Saravanampatti,+Coimbatore" class="btn btn-primary w-100 mt-3" target="_blank">
                    <i class="fas fa-directions"></i> Get Directions
                </a>

                <div class="contact-social" style="display: flex; align-items: center; gap: 1.5rem; border-top: none; padding-top: 1.5rem; margin-top: auto;">
                    <span style="font-weight:600; margin:0; font-size: 0.95rem; color: var(--text-main);">Follow us</span>
                    <div class="social-icons" style="display: flex; gap: 10px;">
                        <a href="#" aria-label="Instagram" style="background: #f1f5f9; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: var(--text-main); transition: 0.3s;"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Facebook" style="background: #f1f5f9; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: var(--text-main); transition: 0.3s;"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="WhatsApp" style="background: #f1f5f9; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: var(--text-main); transition: 0.3s;"><i class="fab fa-whatsapp"></i></a>
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
        // Simple script to handle Requirement dropdown logic
        const reqType = document.getElementById('reqType');
        const reqValue = document.getElementById('reqValue');
        
        if(reqType && reqValue) {
            reqType.addEventListener('change', function() {
                reqValue.disabled = false;
                if(this.value === 'space') {
                    reqValue.placeholder = "Required Space (in sq. ft.)";
                } else if (this.value === 'seats') {
                    reqValue.placeholder = "Seats (in Nos.)";
                }
            });
        }

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

        // Intersection Observer for Services Overview gradient reveal
        const servicesCard = document.querySelector('.services-overview .container');
        if (servicesCard) {
            const servicesObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        servicesCard.classList.add('is-visible');
                    }
                });
            }, { threshold: 0.2 });
            servicesObserver.observe(servicesCard);
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
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
