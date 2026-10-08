<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- 1. Page Banner -->
<section class="page-banner" style="--banner-img: url('<?php echo URLROOT; ?>/images/gallery-building-exterior.png');">
    <div class="banner-overlay"></div>
    <div class="container banner-content text-center">
        <h1>Event Spaces</h1>
        <div class="breadcrumb">
            <a href="<?php echo URLROOT; ?>/">Home</a> &gt;
            <a href="<?php echo URLROOT; ?>/services">Services</a> &gt;
            <span>Event Spaces</span>
        </div>
    </div>
</section>

<!-- 2. Event Spaces Detail -->
<section class="service-detail-section section-padding">
    <div class="container split-layout">
        <div class="split-img"><img src="<?php echo URLROOT; ?>/images/event_spaces.png" alt="Event Spaces" style="width:100%; height:100%; min-height:300px; object-fit:cover; border-radius:8px;"></div>
        <div class="split-content">
            <h2 class="section-title">Event Spaces</h2>
            <p>Space for workshops, seminars, training sessions and meetups, customisable to your event.</p>

            <a href="<?php echo URLROOT; ?>/contact-us#enquiry" class="btn btn-primary mt-4">Enquire Now</a>
        </div>
    </div>
</section>

<!-- 3. Explore Other Services -->
<section class="services-showcase-section section-padding bg-alt">
    <div class="container text-center">
        <h2 class="section-title">Explore Other Services</h2>
        <div class="services-grid">
            <a href="<?php echo URLROOT; ?>/services/hot-desk" class="service-card">
                <img src="<?php echo URLROOT; ?>/images/hot_desk_enhanced_1791353156473.jpg" alt="Hot Desk" class="service-img" style="object-fit: cover; width: 100%; height: 200px;">
                <div class="service-content">
                    <h3>Hot Desk</h3>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/services/dedicated-desk" class="service-card">
                <img src="<?php echo URLROOT; ?>/images/dedicated_desk_enhanced_1791353327423.jpg" alt="Dedicated Desk" class="service-img" style="object-fit: cover; width: 100%; height: 200px;">
                <div class="service-content">
                    <h3>Dedicated Desk</h3>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/services/private-office" class="service-card">
                <img src="<?php echo URLROOT; ?>/images/private_office_enhanced_1791353356099.jpg" alt="Private Office" class="service-img" style="object-fit: cover; width: 100%; height: 200px;">
                <div class="service-content">
                    <h3>Private Office</h3>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/services/meeting-room" class="service-card">
                <img src="<?php echo URLROOT; ?>/images/meeting_room_enhanced_1791353342929.jpg" alt="Meeting Room" class="service-img" style="object-fit: cover; width: 100%; height: 200px;">
                <div class="service-content">
                    <h3>Meeting Room</h3>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/services/virtual-office" class="service-card">
                <img src="<?php echo URLROOT; ?>/images/gallery-front-office-reception.png" alt="Virtual Office" class="service-img" style="object-fit: cover; width: 100%; height: 200px;">
                <div class="service-content">
                    <h3>Virtual Office</h3>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/services/day-pass" class="service-card">
                <img src="<?php echo URLROOT; ?>/images/gallery-boardroom.png" alt="Day Pass" class="service-img" style="object-fit: cover; width: 100%; height: 200px;">
                <div class="service-content">
                    <h3>Day Pass</h3>
                </div>
            </a>
        </div>
        <a href="<?php echo URLROOT; ?>/services" class="btn btn-primary mt-4">View All Services</a>
    </div>
</section>

<?php require APPROOT . '/views/inc/footer.php'; ?>
