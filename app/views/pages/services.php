<?php require APPROOT . '/views/inc/header.php'; ?>
<?php
$e = function ($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); };
$groups = $data['groups'] ?? [];
$mainGroups = array_values(array_filter($groups, function ($g) { return $g['key'] !== 'guides'; }));
$guideGroups = array_values(array_filter($groups, function ($g) { return $g['key'] === 'guides'; }));

// One category section: eyebrow, heading and a card per service (all driven by Admin > Services).
$renderGroup = function (array $group, $alt) use ($e) {
    ob_start(); ?>
<section class="services-showcase-section section-padding<?php echo $alt ? ' bg-alt' : ''; ?>" id="<?php echo $e($group['key'] === 'guides' ? 'coimbatore-guides' : $group['key']); ?>">
    <div class="container text-center">
        <span class="category-eyebrow"><?php echo $e($group['eyebrow']); ?></span>
        <h2 class="section-title"><?php echo $e($group['heading']); ?></h2>
        <p class="section-subtitle"><?php echo $e($group['subtitle']); ?></p>

        <?php
            // keep the last row tidy: 5-6 cards sit in 3 columns, 8 in 4
            $n = count($group['items']);
            $gridCls = $n <= 4 ? '' : (($n % 4 === 0) ? ' sfg-4' : ' sfg-3');
        ?>
        <div class="service-feature-grid<?php echo $gridCls; ?>">
            <?php foreach ($group['items'] as $i => $svc):
                $photo = $svc['card_image'] ?: ($svc['banner_image'] ?: $svc['detail_image']);
                $cta = $svc['card_cta'] !== '' ? $svc['card_cta'] : 'Enquire about ' . $svc['title'];
            ?>
            <div class="service-feature-card" data-aos="fade-up" data-aos-delay="<?php echo ($i % 4) * 100; ?>">
                <?php if ($photo !== ''): ?>
                <div class="sfc-photo"><img src="<?php echo $e(URLROOT . $photo); ?>" alt="<?php echo $e($svc['title']); ?>" loading="lazy" decoding="async" style="width:100%; height:200px; object-fit:cover; display:block;"></div>
                <?php endif; ?>
                <div class="sfc-body">
                    <?php if ($svc['card_badge'] !== ''): ?><span class="plan-badge"><?php echo $e($svc['card_badge']); ?></span><?php endif; ?>
                    <h3><?php echo $e($svc['title']); ?></h3>
                    <?php if ($svc['card_summary'] !== ''): ?><p><?php echo $e($svc['card_summary']); ?></p><?php endif; ?>
                    <?php if (!empty($svc['card_features'])): ?>
                    <ul class="feature-list">
                        <?php foreach ($svc['card_features'] as $feature): ?>
                        <li><i class="fas fa-check-circle"></i> <?php echo $e($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <a href="<?php echo URLROOT; ?>/services/<?php echo $e($svc['slug']); ?>" class="sfc-cta"><?php echo $e($cta); ?> <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php
    return ob_get_clean();
};
?>
<!-- Preload the main banner image to improve LCP / UX -->
<link rel="preload" as="image" href="<?php echo URLROOT; ?>/images/services_bg.jpg">

<!-- 1. Page Banner -->
<section class="page-banner" style="--banner-img: url('<?php echo URLROOT; ?>/images/services_bg.jpg'); color: #fff;">
    <div class="banner-overlay"></div>
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
        <?php foreach ($mainGroups as $g): ?>
        <a href="#<?php echo $e($g['key']); ?>"><?php echo $e($g['label']); ?></a>
        <?php endforeach; ?>
        <a href="#whats-included">What's Included</a>
        <?php foreach ($guideGroups as $g): ?>
        <a href="#coimbatore-guides"><?php echo $e($g['label']); ?></a>
        <?php endforeach; ?>
        <a href="#faq">FAQ</a>
    </div>
</nav>

<!-- 2. Service categories (managed in Admin > Services) -->
<?php foreach ($mainGroups as $gi => $group) { echo $renderGroup($group, $gi % 2 === 1); } ?>
<?php if (empty($groups)): ?>
<section class="section-padding text-center"><div class="container"><p class="section-subtitle">Our services are being updated. Please check back shortly, or <a href="<?php echo URLROOT; ?>/contact-us#enquiry">get in touch</a>.</p></div></section>
<?php endif; ?>

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

<!-- 6. Coimbatore guides (a category in Admin > Services) -->
<?php foreach ($guideGroups as $group) { echo $renderGroup($group, false); } ?>

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
                <!-- Search and Categories -->
                <div class="faq-controls" style="grid-column: 1 / -1; margin-bottom: 1rem;">
                    <div class="faq-search" style="margin-bottom: 1rem; position: relative;">
                        <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                        <input type="text" id="faq-search-input" placeholder="Search for answers..." style="width: 100%; padding: 12px 20px 12px 45px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-main); color: var(--text-main); font-size: 1rem; outline: none; transition: border-color 0.3s;">
                    </div>
                    <div class="faq-categories" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        <button class="faq-cat-btn active" data-filter="all">All</button>
                        <button class="faq-cat-btn" data-filter="general">General</button>
                        <button class="faq-cat-btn" data-filter="plans">Plans &amp; Spaces</button>
                        <button class="faq-cat-btn" data-filter="events">Meetings &amp; Events</button>
                    </div>
                </div>

                <div class="faq-item" data-category="general">
                    <button class="faq-question">
                        Is the space open 24/7?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes. Members have 24/7 access, with 24-hour security.</p>
                    </div>
                </div>
                <div class="faq-item" data-category="general">
                    <button class="faq-question">
                        Is parking available?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes, limited car and bike parking is available. Please check availability when you enquire.</p>
                    </div>
                </div>
                <div class="faq-item" data-category="general">
                    <button class="faq-question">
                        Can I visit before joining?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes. Book a free guided tour through the enquiry form or WhatsApp.</p>
                    </div>
                </div>
                <div class="faq-item" data-category="events">
                    <button class="faq-question">
                        Can I book a meeting room for a few hours?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes. Meeting rooms are booked hourly – minimum 1 hour, maximum 2 hours per booking.</p>
                    </div>
                </div>
                <div class="faq-item" data-category="plans">
                    <button class="faq-question">
                        What plans do you offer?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Hot Desk weekly, Dedicated Desk yearly, Private Office yearly, Meeting Room hourly, plus Day Pass and Virtual Office.</p>
                    </div>
                </div>
                <div class="faq-item" data-category="plans">
                    <button class="faq-question">
                        Can I use Workiify for just one day?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes, with a Day Pass, available in the conference hall.</p>
                    </div>
                </div>
                <div class="faq-item" data-category="plans">
                    <button class="faq-question">
                        Do you offer a virtual office or business address?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes. You get a business address, a dedicated phone number and call handling, with meeting room access at a small extra fee.</p>
                    </div>
                </div>
                <div class="faq-item" data-category="events">
                    <button class="faq-question">
                        Can I host an event at Workiify?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes. We host workshops, seminars, training sessions and meetups. Contact us with your date and number of guests.</p>
                    </div>
                </div>
                <div class="faq-item" data-category="plans">
                    <button class="faq-question">
                        Can the office layout be customised?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes. Each floor is 10,000 sq ft with up to 120 seats and can be customised to your team.</p>
                    </div>
                </div>
                <div class="faq-item" data-category="plans">
                    <button class="faq-question">
                        What is included in an office rental?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Furnished office, Wi-Fi, reception services, kitchen, cleaning, meeting room hours, IT support and more – see "Office rental includes" above.</p>
                    </div>
                </div>
                <div class="faq-item" data-category="general">
                    <button class="faq-question">
                        Why are prices not shown on the website?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Every requirement is different, so we send a quotation after understanding your needs.</p>
                    </div>
                </div>
                <div class="faq-item" data-category="general">
                    <button class="faq-question">
                        How do I become a member?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Enquire, book a tour, receive a quotation, sign the agreement, and move in.</p>
                    </div>
                </div>
                
                <div id="faq-no-results" style="display: none; grid-column: 1 / -1; text-align: center; padding: 2rem; color: var(--text-muted);">No FAQs found matching your criteria.</div>
            </div>
        </div>
    </div>
</section>

<?php require APPROOT . '/views/inc/footer.php'; ?>
