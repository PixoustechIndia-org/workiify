<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- 1. Page Banner -->
<section class="page-banner" style="--banner-img: url('<?php echo URLROOT; ?>/images/gallery-boardroom.png');">
    <div class="banner-overlay"></div>
    <div class="container banner-content text-center">
        <h1>Day Pass</h1>
        <div class="breadcrumb">
            <a href="<?php echo URLROOT; ?>/">Home</a> &gt;
            <a href="<?php echo URLROOT; ?>/services">Services</a> &gt;
            <span>Day Pass</span>
        </div>
    </div>
</section>

<!-- 2. Day Pass Detail -->
<section class="service-detail-section section-padding">
    <div class="container text-center" style="max-width: 760px;">
        <h2 class="section-title">Day Pass</h2>
        <p>Use Workiify for a single day without a monthly plan – ideal for freelancers, travellers, or anyone trying the space before joining. Day Pass is available in the conference hall only.</p>

        <span class="plan-badge mt-3"><i class="fas fa-map-marker-alt"></i> Available in the conference hall only</span>
        <br>

        <a href="<?php echo URLROOT; ?>/contact-us#enquiry" class="btn btn-primary mt-4">Enquire Now</a>
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
            <a href="<?php echo URLROOT; ?>/services/event-spaces" class="service-card">
                <img src="<?php echo URLROOT; ?>/images/gallery-building-exterior.png" alt="Event Spaces" class="service-img" style="object-fit: cover; width: 100%; height: 200px;">
                <div class="service-content">
                    <h3>Event Spaces</h3>
                </div>
            </a>
        </div>
        <a href="<?php echo URLROOT; ?>/services" class="btn btn-primary mt-4">View All Services</a>
    </div>
</section>

<?php require APPROOT . '/views/inc/footer.php'; ?>
