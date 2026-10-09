<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- 1. Page Banner -->
<section class="page-banner" style="--banner-img: url('<?php echo URLROOT; ?>/images/gallery-front-office-reception.png');">
    <div class="banner-overlay"></div>
    <div class="container banner-content text-center">
        <h1>Virtual Office</h1>
        <div class="breadcrumb">
            <a href="<?php echo URLROOT; ?>/">Home</a> &gt;
            <a href="<?php echo URLROOT; ?>/services">Services</a> &gt;
            <span>Virtual Office</span>
        </div>
    </div>
</section>

<!-- 2. Virtual Office Detail -->
<section class="service-detail-section section-padding">
    <div class="container text-center">
        <h2 class="section-title">Virtual Office</h2>
        <p class="section-subtitle">A virtual office lets you work remotely while presenting your business from a well-established, professional business address.</p>

        <div class="benefits-grid text-start">
            <div>
                <h4>Includes</h4>
                <ul class="feature-list mt-3">
                    <li><i class="fas fa-check-circle"></i> Business address (for GST / company registration)</li>
                    <li><i class="fas fa-check-circle"></i> Dedicated phone number</li>
                    <li><i class="fas fa-check-circle"></i> Calls answered in your company's name</li>
                    <li><i class="fas fa-check-circle"></i> Messages passed on by email</li>
                    <li><i class="fas fa-check-circle"></i> Meeting room access at a small extra fee</li>
                </ul>
            </div>
            <div>
                <h4>Benefits</h4>
                <ul class="feature-list mt-3">
                    <li><i class="fas fa-check-circle"></i> Professional image</li>
                    <li><i class="fas fa-check-circle"></i> Never miss a client call</li>
                    <li><i class="fas fa-check-circle"></i> No commute or lease costs</li>
                </ul>
            </div>
        </div>

        <a href="<?php echo URLROOT; ?>/contact-us#enquiry" class="btn btn-primary mt-4">Enquire Now</a>
    </div>
</section>

<!-- 3. Explore Other Services -->
<section class="services-showcase-section section-padding bg-alt">
    <div class="container text-center">
        <h2 class="section-title">Explore Other Services</h2>
        <div class="services-grid">
            <a href="<?php echo URLROOT; ?>/services/hot-desk" class="service-card">
                <img src="<?php echo URLROOT; ?>/images/hot_desk_enhanced_1791353156473.jpg" alt="Hot Desk" class="service-img" loading="lazy" decoding="async" style="object-fit: cover; width: 100%; height: 200px;">
                <div class="service-content">
                    <h3>Hot Desk</h3>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/services/dedicated-desk" class="service-card">
                <img src="<?php echo URLROOT; ?>/images/dedicated_desk_enhanced_1791353327423.jpg" alt="Dedicated Desk" class="service-img" loading="lazy" decoding="async" style="object-fit: cover; width: 100%; height: 200px;">
                <div class="service-content">
                    <h3>Dedicated Desk</h3>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/services/private-office" class="service-card">
                <img src="<?php echo URLROOT; ?>/images/private_office_enhanced_1791353356099.jpg" alt="Private Office" class="service-img" loading="lazy" decoding="async" style="object-fit: cover; width: 100%; height: 200px;">
                <div class="service-content">
                    <h3>Private Office</h3>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/services/meeting-room" class="service-card">
                <img src="<?php echo URLROOT; ?>/images/meeting_room_enhanced_1791353342929.jpg" alt="Meeting Room" class="service-img" loading="lazy" decoding="async" style="object-fit: cover; width: 100%; height: 200px;">
                <div class="service-content">
                    <h3>Meeting Room</h3>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/services/day-pass" class="service-card">
                <img src="<?php echo URLROOT; ?>/images/gallery-boardroom.png" alt="Day Pass" class="service-img" loading="lazy" decoding="async" style="object-fit: cover; width: 100%; height: 200px;">
                <div class="service-content">
                    <h3>Day Pass</h3>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/services/event-spaces" class="service-card">
                <img src="<?php echo URLROOT; ?>/images/gallery-building-exterior.png" alt="Event Spaces" class="service-img" loading="lazy" decoding="async" style="object-fit: cover; width: 100%; height: 200px;">
                <div class="service-content">
                    <h3>Event Spaces</h3>
                </div>
            </a>
        </div>
        <a href="<?php echo URLROOT; ?>/services" class="btn btn-primary mt-4">View All Services</a>
    </div>
</section>

<?php require APPROOT . '/views/inc/footer.php'; ?>
