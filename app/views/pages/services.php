<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- 1. Page Banner -->
<section class="page-banner" style="--banner-img: url('<?php echo URLROOT; ?>/images/services_bg.jpg'); color: #fff;">
    <div class="banner-overlay" style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(30, 58, 138, 0.85) 0%, rgba(37, 99, 235, 0.7) 100%); z-index: 1;"></div>
    <div class="container banner-content text-center" style="position: relative; z-index: 10;">
        <h1 style="margin-bottom: 0.5rem;">Our Services</h1>
        <p class="tagline">Serviced offices and coworking facilities tailored to meet your needs.</p>
        <div class="breadcrumb" style="margin-top: 1.5rem;">
            <a href="<?php echo URLROOT; ?>/">Home</a> <span>&gt;</span> <span>Services</span>
        </div>
    </div>
</section>

<!-- Category Jump Nav -->
<nav class="services-subnav">
    <div class="container">
        <a href="#workspaces">Workspaces</a>
        <a href="#meetings-events">Meetings &amp; Events</a>
        <a href="#virtual-day-pass">Virtual &amp; Day Pass</a>
        <a href="#whats-included">What's Included</a>
        <a href="#how-to-join">How to Join</a>
        <a href="#faq">FAQ</a>
    </div>
</nav>

<!-- 2. Workspaces -->
<section class="services-showcase-section section-padding" id="workspaces">
    <div class="container text-center">
        <span class="category-eyebrow">Workspaces</span>
        <h2 class="section-title">A Desk, or a Whole Office, on Your Terms</h2>
        <p class="section-subtitle">From a single desk to a fully private office, find the plan that fits how you work.</p>

        <div class="service-feature-grid">
            <div class="service-feature-card" data-aos="fade-up" data-aos-delay="0">
                <div class="sfc-photo placeholder-img">Hot Desk Photo</div>
                <div class="sfc-body">
                    <span class="plan-badge">Plan: Weekly</span>
                    <h3>Hot Desk</h3>
                    <p>Perfect for freelancers and remote workers who need a vibrant and flexible workspace every day.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Any open desk</li>
                        <li><i class="fas fa-check-circle"></i> High-speed Wi-Fi</li>
                        <li><i class="fas fa-check-circle"></i> 24/7 access</li>
                    </ul>
                    <a href="<?php echo URLROOT; ?>/services/hot-desk" class="sfc-cta">Enquire about Hot Desk <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="service-feature-card" data-aos="fade-up" data-aos-delay="100">
                <div class="sfc-photo placeholder-img">Dedicated Desk Photo</div>
                <div class="sfc-body">
                    <span class="plan-badge">Plan: Yearly</span>
                    <h3>Dedicated Desk</h3>
                    <p>Your own reserved workstation in a collaborative environment with professional amenities.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Reserved desk and locker</li>
                        <li><i class="fas fa-check-circle"></i> 24/7 access and Wi-Fi</li>
                        <li><i class="fas fa-check-circle"></i> 2 hours free boardroom use per month</li>
                    </ul>
                    <a href="<?php echo URLROOT; ?>/services/dedicated-desk" class="sfc-cta">Enquire about Dedicated Desk <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="service-feature-card is-featured" data-aos="fade-up" data-aos-delay="200">
                <div class="sfc-photo placeholder-img">Private Office Photo</div>
                <div class="sfc-body">
                    <span class="plan-badge">Plan: Yearly</span>
                    <h3>Private Office</h3>
                    <p>Secure and fully furnished office spaces ideal for startups, teams, and established businesses.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Furnished lockable office</li>
                        <li><i class="fas fa-check-circle"></i> 6 hours free boardroom use per month</li>
                        <li><i class="fas fa-check-circle"></i> Reception services and business address</li>
                    </ul>
                    <a href="<?php echo URLROOT; ?>/services/private-office" class="sfc-cta">Enquire about Private Office <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Meetings & Events -->
<section class="services-showcase-section section-padding bg-alt" id="meetings-events">
    <div class="container text-center">
        <span class="category-eyebrow">Meetings &amp; Events</span>
        <h2 class="section-title">Spaces to Host, Present and Celebrate</h2>
        <p class="section-subtitle">Polished, well-equipped rooms for everything from a client call to a full workshop.</p>

        <div class="service-feature-grid">
            <div class="service-feature-card" data-aos="fade-up" data-aos-delay="0">
                <div class="sfc-photo placeholder-img">Meeting Room Photo</div>
                <div class="sfc-body">
                    <span class="plan-badge">Booking: Hourly (max 2 hrs)</span>
                    <h3>Meeting Room</h3>
                    <p>Host client meetings, team discussions, and presentations in a polished and well-equipped setting.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Audio-visual equipment and Wi-Fi</li>
                        <li><i class="fas fa-check-circle"></i> AC and refreshment service</li>
                        <li><i class="fas fa-check-circle"></i> Minimum 1 hour, maximum 2 hours</li>
                    </ul>
                    <a href="<?php echo URLROOT; ?>/services/meeting-room" class="sfc-cta">Check availability <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="service-feature-card" data-aos="fade-up" data-aos-delay="150">
                <div class="sfc-photo placeholder-img">Event Space Photo <em>[Client to provide]</em></div>
                <div class="sfc-body">
                    <span class="plan-badge">Custom setup</span>
                    <h3>Event Spaces</h3>
                    <p>Space for workshops, seminars, training sessions and meetups, customisable to your event.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Workshops and seminars</li>
                        <li><i class="fas fa-check-circle"></i> Training sessions and meetups</li>
                        <li><i class="fas fa-check-circle"></i> Flexible, customisable layout</li>
                    </ul>
                    <a href="<?php echo URLROOT; ?>/services/event-spaces" class="sfc-cta">Plan an event <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. Virtual & Day Pass -->
<section class="services-showcase-section section-padding" id="virtual-day-pass">
    <div class="container text-center">
        <span class="category-eyebrow">Virtual &amp; Day Pass</span>
        <h2 class="section-title">Not Ready for a Full Commitment?</h2>
        <p class="section-subtitle">A professional address or a one-off visit, without signing up for a plan.</p>

        <div class="service-feature-grid">
            <div class="service-feature-card" data-aos="fade-up" data-aos-delay="0">
                <div class="sfc-photo placeholder-img">Virtual Office Photo</div>
                <div class="sfc-body">
                    <span class="plan-badge">Business address</span>
                    <h3>Virtual Office</h3>
                    <p>A virtual office lets you work remotely while presenting your business from a well-established, professional business address.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Business address for GST / company registration</li>
                        <li><i class="fas fa-check-circle"></i> Dedicated phone number</li>
                        <li><i class="fas fa-check-circle"></i> Calls answered in your company's name</li>
                    </ul>
                    <a href="<?php echo URLROOT; ?>/services/virtual-office" class="sfc-cta">Get a business address <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="service-feature-card" data-aos="fade-up" data-aos-delay="150">
                <div class="sfc-photo placeholder-img">Day Pass Photo</div>
                <div class="sfc-body">
                    <span class="plan-badge">Conference hall only</span>
                    <h3>Day Pass</h3>
                    <p>Use Workiify for a single day without a monthly plan – ideal for freelancers, travellers, or anyone trying the space before joining.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> No monthly commitment</li>
                        <li><i class="fas fa-check-circle"></i> Try the space before you join</li>
                        <li><i class="fas fa-check-circle"></i> Available in the conference hall</li>
                    </ul>
                    <a href="<?php echo URLROOT; ?>/services/day-pass" class="sfc-cta">Book a day pass <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. What's Included -->
<section class="rental-includes-section section-padding bg-alt" id="whats-included">
    <div class="container text-center">
        <span class="category-eyebrow">What's Included</span>
        <h2 class="section-title">Office Rental Includes</h2>
        <p class="section-subtitle">Everything you need to run your day, already taken care of.</p>

        <div class="included-groups-grid">
            <div class="included-group-card" data-aos="fade-up" data-aos-delay="0">
                <div class="included-group-icon"><i class="fas fa-building"></i></div>
                <h3>The Space</h3>
                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> Reception area</li>
                    <li><i class="fas fa-check-circle"></i> Fully furnished offices</li>
                    <li><i class="fas fa-check-circle"></i> Boardroom and meeting rooms with AV equipment</li>
                    <li><i class="fas fa-check-circle"></i> Booth for informal catch-ups</li>
                    <li><i class="fas fa-check-circle"></i> Kitchen with coffee, tea, milk and sugar</li>
                    <li><i class="fas fa-check-circle"></i> Cafeteria</li>
                </ul>
            </div>

            <div class="included-group-card" data-aos="fade-up" data-aos-delay="150">
                <div class="included-group-icon"><i class="fas fa-briefcase"></i></div>
                <h3>Business Services</h3>
                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> Prime business address</li>
                    <li><i class="fas fa-check-circle"></i> Reception services (meet and greet visitors, calls answered and messages taken)</li>
                    <li><i class="fas fa-check-circle"></i> Business telephone number</li>
                    <li><i class="fas fa-check-circle"></i> In-house printing at affordable cost</li>
                    <li><i class="fas fa-check-circle"></i> Listing in the member directory</li>
                    <li><i class="fas fa-check-circle"></i> Meeting room refreshments</li>
                </ul>
            </div>

            <div class="included-group-card" data-aos="fade-up" data-aos-delay="300">
                <div class="included-group-icon"><i class="fas fa-headset"></i></div>
                <h3>Everyday Support</h3>
                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> High-speed fibre internet</li>
                    <li><i class="fas fa-check-circle"></i> IT support</li>
                    <li><i class="fas fa-check-circle"></i> 24-hour security and access</li>
                    <li><i class="fas fa-check-circle"></i> Parking (limited)</li>
                    <li><i class="fas fa-check-circle"></i> Cleaning services</li>
                    <li><i class="fas fa-check-circle"></i> Community and networking events</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- 6. How to Become a Workiify Member -->
<section class="membership-steps-section section-padding" id="how-to-join">
    <div class="container text-center">
        <span class="category-eyebrow">Getting Started</span>
        <h2 class="section-title">How to Become a Workiify Member</h2>
        <div class="steps-grid mt-4">
            <div class="step-card" data-aos="fade-up" data-aos-delay="0">
                <div class="step-number">1</div>
                <p>Enquire with us (form, WhatsApp or info@workiify.com)</p>
            </div>
            <div class="step-card" data-aos="fade-up" data-aos-delay="100">
                <div class="step-number">2</div>
                <p>Book an optional guided tour</p>
            </div>
            <div class="step-card" data-aos="fade-up" data-aos-delay="200">
                <div class="step-number">3</div>
                <p>We assess your needs and send a quotation</p>
            </div>
            <div class="step-card" data-aos="fade-up" data-aos-delay="300">
                <div class="step-number">4</div>
                <p>Approve the quotation, sign the agreement and pay the deposit with the first month's rental</p>
            </div>
            <div class="step-card is-final" data-aos="fade-up" data-aos-delay="400">
                <div class="step-number">5</div>
                <p>Pack your laptop bag, grab your team, and move in</p>
            </div>
        </div>
    </div>
</section>

<!-- 7. FAQ -->
<section class="faq-section section-padding bg-alt" id="faq">
    <div class="container">
        <div class="faq-layout">
            <div class="faq-intro" data-aos="fade-right">
                <span class="category-eyebrow">FAQ</span>
                <h2 class="section-title">Frequently Asked Questions</h2>
                <p>Can't find what you need? Message us on WhatsApp and we'll reply shortly.</p>
                <a href="https://api.whatsapp.com/send/?phone=919655500001&text=Hi%20Workiify,%20I%20have%20a%20question&type=phone_number&app_absent=0" class="btn btn-primary" target="_blank">Ask a Question</a>
            </div>

            <div class="faq-accordion" data-aos="fade-left">
                <div class="faq-item">
                    <button class="faq-question">
                        Is the space open 24/7?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes. Members have 24/7 access, with 24-hour security.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">
                        Is parking available?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes, limited car and bike parking is available. Please check availability when you enquire.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">
                        Can I visit before joining?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes. Book a free guided tour through the enquiry form or WhatsApp.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">
                        Can I book a meeting room for a few hours?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes. Meeting rooms are booked hourly – minimum 1 hour, maximum 2 hours per booking.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">
                        What plans do you offer?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Hot Desk weekly, Dedicated Desk yearly, Private Office yearly, Meeting Room hourly, plus Day Pass and Virtual Office.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">
                        Can I use Workiify for just one day?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes, with a Day Pass, available in the conference hall.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">
                        Do you offer a virtual office or business address?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes. You get a business address, a dedicated phone number and call handling, with meeting room access at a small extra fee.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">
                        Can I host an event at Workiify?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes. We host workshops, seminars, training sessions and meetups. Contact us with your date and number of guests.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">
                        Can the office layout be customised?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes. Each floor is 10,000 sq ft with up to 120 seats and can be customised to your team.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">
                        What is included in an office rental?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Furnished office, Wi-Fi, reception services, kitchen, cleaning, meeting room hours, IT support and more – see "Office rental includes" above.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">
                        Why are prices not shown on the website?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Every requirement is different, so we send a quotation after understanding your needs.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">
                        How do I become a member?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Enquire, book a tour, receive a quotation, sign the agreement, and move in.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require APPROOT . '/views/inc/footer.php'; ?>
