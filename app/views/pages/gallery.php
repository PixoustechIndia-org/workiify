<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- 1. Page Banner -->
<section class="page-banner" style="--banner-img: url('<?php echo URLROOT . htmlspecialchars($data['f']['gallery_banner_image']); ?>'); color: #fff;">
    <div class="banner-overlay"></div>
    <div class="container banner-content text-center" style="position: relative; z-index: 10;">
        <span class="category-eyebrow" style="color: rgba(255,255,255,0.85);"><?php echo htmlspecialchars($data['f']['gallery_banner_eyebrow']); ?></span>
        <h1><?php echo htmlspecialchars($data['f']['gallery_banner_heading']); ?></h1>
        <p class="tagline"><?php echo htmlspecialchars($data['f']['gallery_banner_tagline']); ?></p>
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
            <?php foreach ($data['photos'] as $photo): $p = $photo['data']; ?>
            <div class="gallery-page-item has-photo" data-category="<?php echo htmlspecialchars($p['category']); ?>" data-caption="<?php echo htmlspecialchars($p['caption']); ?>">
                <img src="<?php echo URLROOT . htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['caption']); ?>" loading="lazy" decoding="async">
                <div class="gallery-caption"><?php echo htmlspecialchars($p['caption']); ?></div>
            </div>
            <?php endforeach; ?>
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
    <img class="lightbox-img" id="lightboxImg" src="" alt="" loading="lazy" decoding="async">
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
