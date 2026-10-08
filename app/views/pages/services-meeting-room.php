<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- 1. Page Banner -->
<section class="page-banner">
    <div class="banner-overlay"></div>
    <div class="container banner-content text-center">
        <h1>Meeting Room</h1>
        <div class="breadcrumb">
            <a href="<?php echo URLROOT; ?>/">Home</a> &gt;
            <a href="<?php echo URLROOT; ?>/services">Services</a> &gt;
            <span>Meeting Room</span>
        </div>
    </div>
</section>

<!-- 2. Meeting Room Detail -->
<section class="service-detail-section section-padding">
    <div class="container split-layout reverse">
        <div class="split-content">
            <h2 class="section-title">Meeting Room</h2>
            <p>Host client meetings, team discussions, and presentations in a polished and well-equipped setting.</p>

            <span class="plan-badge"><i class="fas fa-clock"></i> Booking: Hourly – minimum 1 hour, maximum 2 hours</span>

            <h4 class="mt-3">Includes</h4>
            <ul class="feature-list mt-3">
                <li><i class="fas fa-check-circle"></i> Audio-visual equipment</li>
                <li><i class="fas fa-check-circle"></i> Wi-Fi</li>
                <li><i class="fas fa-check-circle"></i> AC</li>
                <li><i class="fas fa-check-circle"></i> Refreshment service</li>
            </ul>

            <a href="<?php echo URLROOT; ?>/contact-us#enquiry" class="btn btn-primary mt-4">Enquire Now</a>
        </div>
        <div class="split-img placeholder-img">Meeting Room Photo</div>
    </div>
</section>

<!-- 3. Explore Other Services -->
<section class="services-showcase-section section-padding bg-alt">
    <div class="container text-center">
        <h2 class="section-title">Explore Other Services</h2>
        <div class="services-grid">
            <a href="<?php echo URLROOT; ?>/services/hot-desk" class="service-card">
                <div class="service-img placeholder-img">Hot Desk Image</div>
                <div class="service-content">
                    <h3>Hot Desk</h3>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/services/dedicated-desk" class="service-card">
                <div class="service-img placeholder-img">Dedicated Desk Image</div>
                <div class="service-content">
                    <h3>Dedicated Desk</h3>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/services/private-office" class="service-card">
                <div class="service-img placeholder-img">Private Office Image</div>
                <div class="service-content">
                    <h3>Private Office</h3>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/services/virtual-office" class="service-card">
                <div class="service-img placeholder-img">Virtual Office Image</div>
                <div class="service-content">
                    <h3>Virtual Office</h3>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/services/day-pass" class="service-card">
                <div class="service-img placeholder-img">Day Pass Image</div>
                <div class="service-content">
                    <h3>Day Pass</h3>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/services/event-spaces" class="service-card">
                <div class="service-img placeholder-img">Event Spaces Image</div>
                <div class="service-content">
                    <h3>Event Spaces</h3>
                </div>
            </a>
        </div>
        <a href="<?php echo URLROOT; ?>/services" class="btn btn-primary mt-4">View All Services</a>
    </div>
</section>

<?php require APPROOT . '/views/inc/footer.php'; ?>
