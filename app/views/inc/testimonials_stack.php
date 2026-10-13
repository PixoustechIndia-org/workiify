<?php
/**
 * Shared animated "stacked cards" testimonials widget for the Home and About Us
 * pages. Expects in scope: $data['testimonials'], $testimonialsHeading (pre-escaped
 * HTML), $testimonialsGoogleRating (pre-escaped), and optionally
 * $testimonialsSectionExtraClass for page-specific CSS hooks.
 */
$testimonialsSectionExtraClass = $testimonialsSectionExtraClass ?? '';
?>
<section class="testimonials-section section-padding bg-alt <?php echo htmlspecialchars($testimonialsSectionExtraClass); ?> about-testimonials-section">
    <div class="container text-center max-w-1150 mb-4">
        <h2 class="section-title"><?php echo $testimonialsHeading; ?></h2>
        <div class="google-rating">
            <i class="fab fa-google"></i> <span><?php echo $testimonialsGoogleRating; ?></span>
            <div class="stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
        </div>
    </div>

    <!-- Animated Coverflow Testimonial Cards -->
    <div class="testimonial-stack-wrapper">
      <div class="testimonial-coverflow-row">
        <button type="button" class="tcf-arrow tcf-arrow-left" id="tcfPrevBtn" aria-label="Previous testimonial">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div class="testimonial-coverflow-clip">
        <div class="testimonial-stack" id="testimonialStack">
            <?php foreach ($data['testimonials'] as $i => $t):
                $photoName = !empty($t->photo) ? trim($t->photo) : '';
                $hasAvatar = !empty($photoName) && file_exists(APPROOT . '/../public/uploads/testimonials/' . $photoName);
                $avatarUrl = $hasAvatar ? (URLROOT . '/uploads/testimonials/' . htmlspecialchars($photoName)) : '';
                $roleCompany = htmlspecialchars($t->designation ?? '');
                if (!empty($t->designation) && !empty($t->company)) { $roleCompany .= ' &middot; '; }
                $roleCompany .= htmlspecialchars($t->company ?? '');
                $feedbackLen = mb_strlen(trim($t->feedback));
                $isLong = ($feedbackLen > 180);
            ?>
                <div class="testimonial-stack-card"
                     data-index="<?php echo $i; ?>"
                     data-author="<?php echo htmlspecialchars($t->full_name); ?>"
                     data-role="<?php echo $roleCompany; ?>"
                     data-rating="<?php echo (int)($t->rating ?? 5); ?>"
                     data-feedback="<?php echo htmlspecialchars($t->feedback); ?>"
                     data-avatar="<?php echo $avatarUrl; ?>">
                    <div class="at-card-inner">
                        <div class="testimonial-card-header">
                            <div class="rating">
                                <?php for ($s = 0; $s < ($t->rating ?? 5); $s++) : ?>
                                    <i class="fas fa-star"></i>
                                <?php endfor; ?>
                                <?php for ($s = ($t->rating ?? 5); $s < 5; $s++) : ?>
                                    <i class="far fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="card-open-hint" title="Click to view full review">
                                <i class="fas fa-arrow-up-right-from-square"></i>
                            </span>
                        </div>

                        <div class="feedback-body">
                            <p class="feedback-text">"<?php echo htmlspecialchars($t->feedback); ?>"</p>
                            <?php if ($isLong): ?>
                                <span class="read-more-trigger" title="Click to read full review">
                                    <span>...</span> <span class="read-more-link">Read more</span>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="client-info">
                            <div class="avatar-placeholder">
                                <?php if ($hasAvatar) : ?>
                                    <img src="<?php echo $avatarUrl; ?>"
                                         alt="<?php echo htmlspecialchars($t->full_name); ?>"
                                         class="client-avatar-img">
                                <?php else : ?>
                                    <i class="fas fa-user-tie"></i>
                                <?php endif; ?>
                            </div>
                            <div class="client-details">
                                <h4 class="client-name"><?php echo htmlspecialchars($t->full_name); ?></h4>
                                <?php if (!empty($t->designation) || !empty($t->company)) : ?>
                                    <p class="client-role">
                                        <?php echo htmlspecialchars($t->designation); ?>
                                        <?php if (!empty($t->designation) && !empty($t->company)) { echo ' &middot; '; } ?>
                                        <?php echo htmlspecialchars($t->company); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        </div>
        <button type="button" class="tcf-arrow tcf-arrow-right" id="tcfNextBtn" aria-label="Next testimonial">
            <i class="fas fa-chevron-right"></i>
        </button>
      </div>

        <?php if (count($data['testimonials']) > 1): ?>
        <div class="testimonial-stack-dots" id="testimonialStackDots">
            <?php foreach ($data['testimonials'] as $i => $t): ?>
                <button type="button" class="stack-dot<?php echo $i === 0 ? ' is-active' : ''; ?>" data-goto="<?php echo $i; ?>" aria-label="Show testimonial <?php echo $i + 1; ?>"></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="container text-center testimonial-stack-cta">
        <a href="<?php echo URLROOT; ?>/testimonials" class="btn btn-primary btn-pill">
            <i class="fas fa-star" style="margin-right: 6px;"></i> View all testimonials
        </a>
    </div>
</section>

<!-- Testimonial Full Reader Modal / Popup with Next/Prev Carousel Navigation -->
<div id="testimonial-detail-modal" class="testimonial-detail-modal" role="dialog" aria-modal="true" aria-labelledby="tdmAuthorName" style="display: none;">
    <div class="tdm-backdrop" id="tdmBackdrop"></div>
    <div class="tdm-dialog">
        <!-- Close Button -->
        <button type="button" class="tdm-close-btn" id="tdmCloseBtn" aria-label="Close review dialog">
            <i class="fas fa-times"></i>
        </button>

        <!-- Navigation Arrows (Previous / Next) -->
        <button type="button" class="tdm-nav-arrow tdm-prev-arrow" id="tdmPrevBtn" aria-label="Previous testimonial" title="Previous review (Left Arrow)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button type="button" class="tdm-nav-arrow tdm-next-arrow" id="tdmNextBtn" aria-label="Next testimonial" title="Next review (Right Arrow)">
            <i class="fas fa-chevron-right"></i>
        </button>

        <div class="tdm-header">
            <div class="tdm-avatar-wrap" id="tdmAvatarWrap">
                <i class="fas fa-user-tie" id="tdmDefaultIcon"></i>
                <img id="tdmAvatarImg" src="" alt="Author Avatar" style="display: none;">
            </div>
            <div class="tdm-author-meta">
                <div class="tdm-meta-top">
                    <h3 class="tdm-name" id="tdmAuthorName">Client Name</h3>
                    <span class="tdm-counter" id="tdmCounter">1 / 5</span>
                </div>
                <p class="tdm-role" id="tdmAuthorRole">Designation & Company</p>
                <div class="tdm-rating" id="tdmRatingStars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>

        <div class="tdm-body">
            <i class="fas fa-quote-left tdm-quote-icon"></i>
            <p class="tdm-full-text" id="tdmFeedbackText">Feedback text...</p>
        </div>

        <div class="tdm-footer">
            <span class="tdm-verified-tag"><i class="fas fa-check-circle"></i> Verified Workiify Member Review</span>
            <div class="tdm-footer-actions">
                <button type="button" class="btn btn-outline btn-sm tdm-close-action" id="tdmCloseAction">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Coverflow Testimonial Cards: center + peeking prev/next, autoplay, arrows ---
        const stack = document.getElementById('testimonialStack');
        if (!stack) return;

        const cards = Array.from(stack.querySelectorAll('.testimonial-stack-card'));
        const n = cards.length;
        const dotsWrap = document.getElementById('testimonialStackDots');
        const dots = dotsWrap ? Array.from(dotsWrap.querySelectorAll('.stack-dot')) : [];
        const tcfPrevBtn = document.getElementById('tcfPrevBtn');
        const tcfNextBtn = document.getElementById('tcfNextBtn');
        const AUTOPLAY_MS = 7000; // cycle to the next card every 7 seconds

        // --- Testimonial Detail Reader Modal elements (looked up early so the
        // coverflow layout logic below can safely reference tdmModal) ---
        const tdmModal = document.getElementById('testimonial-detail-modal');
        const tdmBackdrop = document.getElementById('tdmBackdrop');
        const tdmCloseBtn = document.getElementById('tdmCloseBtn');
        const tdmCloseAction = document.getElementById('tdmCloseAction');
        const tdmPrevBtn = document.getElementById('tdmPrevBtn');
        const tdmNextBtn = document.getElementById('tdmNextBtn');
        const tdmAvatarImg = document.getElementById('tdmAvatarImg');
        const tdmDefaultIcon = document.getElementById('tdmDefaultIcon');
        const tdmAuthorName = document.getElementById('tdmAuthorName');
        const tdmAuthorRole = document.getElementById('tdmAuthorRole');
        const tdmRatingStars = document.getElementById('tdmRatingStars');
        const tdmFeedbackText = document.getElementById('tdmFeedbackText');
        const tdmCounter = document.getElementById('tdmCounter');

        let currentIndex = 0;
        let timer = null;

        // Signed circular distance of `idx` from currentIndex, in range (-n/2, n/2],
        // so cards resolve to a side consistently as the set wraps around.
        function relativeDelta(idx) {
            let d = idx - currentIndex;
            while (d > n / 2) d -= n;
            while (d <= -n / 2) d += n;
            return d;
        }

        function layout() {
            cards.forEach((card, idx) => {
                const d = relativeDelta(idx);
                let pos;
                if (d === 0) pos = 'center';
                else if (d === -1) pos = 'left';
                else if (d === 1) pos = 'right';
                else pos = d < 0 ? 'hidden-left' : 'hidden-right';
                card.dataset.coverflowPos = pos;
            });
            dots.forEach((d, i) => d.classList.toggle('is-active', i === currentIndex));
            if (tdmModal && tdmModal.classList.contains('is-open')) {
                renderTestimonialData(currentIndex);
            }
        }

        function goTo(idx) {
            currentIndex = ((idx % n) + n) % n;
            layout();
            resetTimer();
        }

        function next() { goTo(currentIndex + 1); }
        function prev() { goTo(currentIndex - 1); }

        function resetTimer() {
            clearInterval(timer);
            if (n > 1) timer = setInterval(next, AUTOPLAY_MS);
        }

        layout();
        resetTimer();

        stack.addEventListener('mouseenter', () => clearInterval(timer));
        stack.addEventListener('mouseleave', resetTimer);

        if (tcfPrevBtn) tcfPrevBtn.addEventListener('click', prev);
        if (tcfNextBtn) tcfNextBtn.addEventListener('click', next);

        dots.forEach(dot => {
            dot.addEventListener('click', () => goTo(parseInt(dot.dataset.goto, 10)));
        });

        // --- Testimonial Detail Reader Modal Popup with Next/Prev Carousel Navigation ---
        function renderTestimonialData(index) {
            if (!n) return;
            const card = cards[index];

            const author = card.dataset.author || '';
            const role = card.dataset.role || '';
            const feedback = card.dataset.feedback || '';
            const avatar = card.dataset.avatar || '';
            const rating = parseInt(card.dataset.rating, 10) || 5;

            tdmAuthorName.textContent = author;
            tdmAuthorRole.textContent = role;
            tdmFeedbackText.textContent = `"${feedback}"`;

            if (tdmCounter) {
                tdmCounter.textContent = `${index + 1} / ${n}`;
            }

            // Avatar
            if (avatar && avatar.trim() !== '') {
                tdmAvatarImg.src = avatar;
                tdmAvatarImg.style.display = 'block';
                tdmDefaultIcon.style.display = 'none';
            } else {
                tdmAvatarImg.style.display = 'none';
                tdmDefaultIcon.style.display = 'block';
            }

            // Rating Stars
            tdmRatingStars.innerHTML = '';
            for (let s = 0; s < rating; s++) {
                tdmRatingStars.innerHTML += '<i class="fas fa-star"></i>';
            }
            for (let s = rating; s < 5; s++) {
                tdmRatingStars.innerHTML += '<i class="far fa-star"></i>';
            }
        }

        function openTestimonialModal() {
            if (!tdmModal) return;
            renderTestimonialData(currentIndex);

            // Pause autoplay while the modal is open
            clearInterval(timer);

            tdmModal.style.display = 'flex';
            requestAnimationFrame(() => {
                tdmModal.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            });
        }

        function closeTestimonialModal() {
            if (!tdmModal) return;
            tdmModal.classList.remove('is-open');
            document.body.style.overflow = '';
            setTimeout(() => {
                tdmModal.style.display = 'none';
                // Resume autoplay
                resetTimer();
            }, 250);
        }

        // Clicking the center card opens the full review; clicking a peeking
        // card to either side navigates the coverflow that direction.
        cards.forEach((card, idx) => {
            card.addEventListener('click', () => {
                const pos = card.dataset.coverflowPos;
                if (pos === 'center') {
                    openTestimonialModal();
                } else if (pos === 'left') {
                    prev();
                } else if (pos === 'right') {
                    next();
                }
            });
        });

        // Close triggers
        if (tdmCloseBtn) tdmCloseBtn.addEventListener('click', closeTestimonialModal);
        if (tdmCloseAction) tdmCloseAction.addEventListener('click', closeTestimonialModal);
        if (tdmBackdrop) tdmBackdrop.addEventListener('click', closeTestimonialModal);

        // Modal's own arrow navigation also moves the coverflow behind it
        if (tdmNextBtn) tdmNextBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            next();
        });
        if (tdmPrevBtn) tdmPrevBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            prev();
        });

        // Keyboard navigation (Escape to close, Left/Right arrows to cycle)
        document.addEventListener('keydown', (e) => {
            if (!tdmModal || !tdmModal.classList.contains('is-open')) return;
            if (e.key === 'Escape') {
                closeTestimonialModal();
            } else if (e.key === 'ArrowRight') {
                next();
            } else if (e.key === 'ArrowLeft') {
                prev();
            }
        });
    });
</script>
