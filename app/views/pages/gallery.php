<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- 1. Page Banner -->
<section class="page-banner" style="background-image: url('<?php echo URLROOT; ?>/images/coworking_lounge_1791350901804.jpg'); background-size: cover; background-position: center; color: #fff;">
    <div class="banner-overlay"></div>
    <div class="container banner-content text-center">
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

            <div class="gallery-page-item placeholder-img" data-category="common-areas">Workiify Building Exterior <em>[Client to provide]</em></div>

            <div class="gallery-page-item placeholder-img" data-category="events">Pongal Celebration 2026 <em>[Client to provide]</em></div>

            <div class="gallery-page-item placeholder-img" data-category="common-areas">Dining Hall – View 1 <em>[Client to provide]</em></div>

            <div class="gallery-page-item placeholder-img" data-category="common-areas">Dining Hall – View 2 <em>[Client to provide]</em></div>

            <div class="gallery-page-item has-photo" data-category="meeting-rooms" data-caption="Conference Room">
                <img src="<?php echo URLROOT; ?>/images/meeting_room_enhanced_1791353342929.jpg" alt="Conference Room">
                <div class="gallery-caption">Conference Room</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="common-areas" data-caption="Front Office Lounge">
                <img src="<?php echo URLROOT; ?>/images/coworking_lounge_1791350901804.jpg" alt="Front Office Lounge">
                <div class="gallery-caption">Front Office Lounge</div>
            </div>

            <div class="gallery-page-item placeholder-img" data-category="common-areas">Front Office Reception <em>[Client to provide]</em></div>

            <div class="gallery-page-item has-photo" data-category="workspaces" data-caption="Workspace Views">
                <img src="<?php echo URLROOT; ?>/images/coworking_private_1791350889843.jpg" alt="Workspace Views">
                <div class="gallery-caption">Workspace Views</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="workspaces" data-caption="Open Workstation Area">
                <img src="<?php echo URLROOT; ?>/images/coworking_main_1791350864870.jpg" alt="Open Workstation Area">
                <div class="gallery-caption">Open Workstation Area</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="workspaces" data-caption="Hot Desk">
                <img src="<?php echo URLROOT; ?>/images/hot_desk_enhanced_1791353156473.jpg" alt="Hot Desk">
                <div class="gallery-caption">Hot Desk</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="workspaces" data-caption="Dedicated Desk">
                <img src="<?php echo URLROOT; ?>/images/dedicated_desk_enhanced_1791353327423.jpg" alt="Dedicated Desk">
                <div class="gallery-caption">Dedicated Desk</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="workspaces" data-caption="Private Office">
                <img src="<?php echo URLROOT; ?>/images/private_office_enhanced_1791353356099.jpg" alt="Private Office">
                <div class="gallery-caption">Private Office</div>
            </div>

            <div class="gallery-page-item has-photo" data-category="meeting-rooms" data-caption="Meeting Room">
                <img src="<?php echo URLROOT; ?>/images/meeting_room_enhanced_1791353342929.jpg" alt="Meeting Room">
                <div class="gallery-caption">Meeting Room</div>
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
