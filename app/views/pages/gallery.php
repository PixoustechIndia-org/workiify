<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- 1. Page Banner -->
<section class="page-banner" style="--banner-img: url('<?php echo URLROOT; ?>/images/coworking_lounge_1791350901804.jpg'); color: #fff;">
    <div class="banner-overlay" style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(30, 58, 138, 0.85) 0%, rgba(37, 99, 235, 0.7) 100%); z-index: 1;"></div>
    <div class="container banner-content text-center" style="position: relative; z-index: 10;">
        <span class="category-eyebrow" style="color: rgba(255,255,255,0.85);">Photo Gallery</span>
        <h1>Workspace Gallery</h1>
        <p class="tagline">Explore our thoughtfully designed workspaces, where comfort, creativity, and productivity come together.</p>
        <div class="breadcrumb">
            <a href="<?php echo URLROOT; ?>/">Home</a> &gt; <span>Gallery</span>
        </div>
    </div>
</section>

<!-- 2. Filter Tabs -->
<section class="section-padding" style="padding-bottom: 0;">
    <div class="container">
        <div class="gallery-filters">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="workspaces">Workspaces</button>
            <button class="filter-btn" data-filter="meeting-rooms">Meeting Rooms</button>
            <button class="filter-btn" data-filter="common-areas">Common Areas</button>
            <button class="filter-btn" data-filter="events">Events</button>
        </div>
    </div>
</section>

<!-- 3. Photo Grid -->
<section class="section-padding">
    <div class="container">
        <div class="gallery-page-grid" id="galleryGrid">

            <div class="gallery-page-item has-photo" data-category="common-areas" data-caption="Workiify Building Exterior">
                <img src="<?php echo URLROOT; ?>/images/gallery-building-exterior.png" alt="Workiify Building Exterior">
                <div class="gallery-caption">Workiify Building Exterior</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="events" data-caption="Pongal Celebration 2026">
                <img src="<?php echo URLROOT; ?>/images/gallery-pongal-celebration-2026.png" alt="Pongal Celebration 2026">
                <div class="gallery-caption">Pongal Celebration 2026</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="events" data-caption="Office Inauguration Ceremony">
                <img src="<?php echo URLROOT; ?>/images/gallery-office-inauguration.png" alt="Office Inauguration Ceremony">
                <div class="gallery-caption">Office Inauguration Ceremony</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="common-areas" data-caption="Dining Hall – View 1">
                <img src="<?php echo URLROOT; ?>/images/gallery-dining-hall-1.png" alt="Dining Hall – View 1">
                <div class="gallery-caption">Dining Hall – View 1</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="common-areas" data-caption="Dining Hall – View 2">
                <img src="<?php echo URLROOT; ?>/images/gallery-dining-hall-2.png" alt="Dining Hall – View 2">
                <div class="gallery-caption">Dining Hall – View 2</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="common-areas" data-caption="Front Office Lounge">
                <img src="<?php echo URLROOT; ?>/images/gallery-front-office-lounge.png" alt="Front Office Lounge">
                <div class="gallery-caption">Front Office Lounge</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="common-areas" data-caption="Front Office Reception">
                <img src="<?php echo URLROOT; ?>/images/gallery-front-office-reception.png" alt="Front Office Reception">
                <div class="gallery-caption">Front Office Reception</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="common-areas" data-caption="Lounge Seating Area">
                <img src="<?php echo URLROOT; ?>/images/coworking_lounge_1791350901804.jpg" alt="Lounge Seating Area">
                <div class="gallery-caption">Lounge Seating Area</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="common-areas" data-caption="Locker Area &amp; Corridor">
                <img src="<?php echo URLROOT; ?>/images/gallery-locker-corridor.png" alt="Locker Area & Corridor">
                <div class="gallery-caption">Locker Area &amp; Corridor</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="common-areas" data-caption="Main Corridor">
                <img src="<?php echo URLROOT; ?>/images/gallery-main-corridor.png" alt="Main Corridor">
                <div class="gallery-caption">Main Corridor</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="common-areas" data-caption="Community Coworking">
                <img src="<?php echo URLROOT; ?>/images/coworking_private_1791350889843.jpg" alt="Community Coworking">
                <div class="gallery-caption">Community Coworking</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="meeting-rooms" data-caption="Executive Boardroom">
                <img src="<?php echo URLROOT; ?>/images/gallery-boardroom.png" alt="Executive Boardroom">
                <div class="gallery-caption">Executive Boardroom</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="meeting-rooms" data-caption="Main Conference Room">
                <img src="<?php echo URLROOT; ?>/images/meeting_room_enhanced_1791353342929.jpg" alt="Main Conference Room">
                <div class="gallery-caption">Main Conference Room</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="workspaces" data-caption="Open Desk Area">
                <img src="<?php echo URLROOT; ?>/images/gallery-open-desk-area.png" alt="Open Desk Area">
                <div class="gallery-caption">Open Desk Area</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="workspaces" data-caption="Reception Workstations">
                <img src="<?php echo URLROOT; ?>/images/gallery-reception-workstations.png" alt="Reception Workstations">
                <div class="gallery-caption">Reception Workstations</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="workspaces" data-caption="Shared Workspace Floor">
                <img src="<?php echo URLROOT; ?>/images/gallery-shared-workspace-floor.png" alt="Shared Workspace Floor">
                <div class="gallery-caption">Shared Workspace Floor</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="workspaces" data-caption="Open Workstation Area">
                <img src="<?php echo URLROOT; ?>/images/gallery-open-workstation-area.png" alt="Open Workstation Area">
                <div class="gallery-caption">Open Workstation Area</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="workspaces" data-caption="Collaborative Space">
                <img src="<?php echo URLROOT; ?>/images/coworking_main_1791350864870.jpg" alt="Collaborative Space">
                <div class="gallery-caption">Collaborative Space</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="workspaces" data-caption="Hot Desk Zone">
                <img src="<?php echo URLROOT; ?>/images/hot_desk_enhanced_1791353156473.jpg" alt="Hot Desk Zone">
                <div class="gallery-caption">Hot Desk Zone</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="workspaces" data-caption="Dedicated Desk Area">
                <img src="<?php echo URLROOT; ?>/images/dedicated_desk_enhanced_1791353327423.jpg" alt="Dedicated Desk Area">
                <div class="gallery-caption">Dedicated Desk Area</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="workspaces" data-caption="Private Office">
                <img src="<?php echo URLROOT; ?>/images/private_office_enhanced_1791353356099.jpg" alt="Private Office">
                <div class="gallery-caption">Private Office</div>
            </div>

        </div>

        <!-- 4. Pagination -->
        <div class="gallery-pagination" id="galleryPagination"></div>

        <!-- 5. Video / Virtual Tour -->
        <p class="text-center mt-4" style="color: var(--text-muted);"><em>[Client to provide a virtual tour video, if available]</em></p>
    </div>
</section>

<!-- Lightbox -->
<div class="lightbox" id="lightbox">
    <button class="lightbox-close" aria-label="Close">&times;</button>
    <button class="lightbox-prev" aria-label="Previous photo"><i class="fas fa-chevron-left"></i></button>
    <img class="lightbox-img" id="lightboxImg" src="" alt="">
    <div class="lightbox-caption" id="lightboxCaption"></div>
    <button class="lightbox-next" aria-label="Next photo"><i class="fas fa-chevron-right"></i></button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const PER_PAGE = 12;
        const filterBtns = document.querySelectorAll('.filter-btn');
        const items = Array.from(document.querySelectorAll('.gallery-page-item'));
        const paginationEl = document.getElementById('galleryPagination');
        const grid = document.getElementById('galleryGrid');

        let currentFilter = 'all';
        let currentPage = 1;

        function getFiltered() {
            return items.filter(item => currentFilter === 'all' || item.dataset.category === currentFilter);
        }

        function render() {
            const filtered = getFiltered();
            const totalPages = Math.max(1, Math.ceil(filtered.length / PER_PAGE));
            if (currentPage > totalPages) currentPage = totalPages;

            items.forEach(item => { item.style.display = 'none'; });
            filtered.slice((currentPage - 1) * PER_PAGE, currentPage * PER_PAGE).forEach(item => {
                item.style.display = '';
            });

            paginationEl.innerHTML = '';
            if (totalPages > 1) {
                for (let i = 1; i <= totalPages; i++) {
                    const btn = document.createElement('button');
                    btn.className = 'page-btn' + (i === currentPage ? ' active' : '');
                    btn.textContent = i;
                    btn.addEventListener('click', function() {
                        currentPage = i;
                        render();
                        grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    });
                    paginationEl.appendChild(btn);
                }
            }
        }

        filterBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                filterBtns.forEach(function(b) { b.classList.remove('active'); });
                btn.classList.add('active');
                currentFilter = btn.dataset.filter;
                currentPage = 1;
                render();
            });
        });

        render();

        // --- Lightbox ---
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightboxImg');
        const lightboxCaption = document.getElementById('lightboxCaption');
        let lightboxItems = [];
        let lightboxIndex = 0;

        function updateLightbox() {
            const item = lightboxItems[lightboxIndex];
            lightboxImg.src = item.querySelector('img').src;
            lightboxCaption.textContent = item.dataset.caption || '';
        }

        function openLightbox(item) {
            lightboxItems = getFiltered().filter(function(i) { return i.classList.contains('has-photo'); });
            lightboxIndex = lightboxItems.indexOf(item);
            if (lightboxIndex === -1) { lightboxItems = [item]; lightboxIndex = 0; }
            updateLightbox();
            lightbox.classList.add('active');
        }

        function closeLightbox() {
            lightbox.classList.remove('active');
        }

        items.forEach(function(item) {
            if (item.classList.contains('has-photo')) {
                item.addEventListener('click', function() { openLightbox(item); });
            }
        });

        document.querySelector('.lightbox-close').addEventListener('click', closeLightbox);
        document.querySelector('.lightbox-next').addEventListener('click', function() {
            lightboxIndex = (lightboxIndex + 1) % lightboxItems.length;
            updateLightbox();
        });
        document.querySelector('.lightbox-prev').addEventListener('click', function() {
            lightboxIndex = (lightboxIndex - 1 + lightboxItems.length) % lightboxItems.length;
            updateLightbox();
        });
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) closeLightbox();
        });
        document.addEventListener('keydown', function(e) {
            if (!lightbox.classList.contains('active')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowRight') document.querySelector('.lightbox-next').click();
            if (e.key === 'ArrowLeft') document.querySelector('.lightbox-prev').click();
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
