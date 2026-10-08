document.addEventListener('DOMContentLoaded', () => {

    // --- Floating Header on Scroll ---
    const siteHeader = document.querySelector('.site-header');
    if (siteHeader) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                siteHeader.classList.add('scrolled');
            } else {
                siteHeader.classList.remove('scrolled');
            }
        });
    }
    
    // --- Mobile Menu Toggle ---
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mainNav = document.querySelector('.main-nav');
    
    if (mobileMenuToggle && mainNav) {
        mobileMenuToggle.addEventListener('click', () => {
            mainNav.classList.toggle('active');
            const icon = mobileMenuToggle.querySelector('i');
            if (mainNav.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }

    // --- Light/Dark Theme Toggle (FR-GEN-08) ---
    const themeToggleBtn = document.querySelector('.theme-toggle');
    const htmlElement = document.documentElement;

    if (themeToggleBtn) {
        const themeIcon = themeToggleBtn.querySelector('i');

        function updateThemeIcon(theme) {
            if (!themeIcon) return;
            if (theme === 'dark') {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            } else {
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            }
        }

        // Check local storage for saved theme
        const savedTheme = localStorage.getItem('workiify_theme');
        if (savedTheme) {
            htmlElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
        } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            // Optionally check OS preference
            htmlElement.setAttribute('data-theme', 'dark');
            updateThemeIcon('dark');
        }

        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            htmlElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('workiify_theme', newTheme);
            updateThemeIcon(newTheme);
        });
    }

    // --- FAQ Accordion ---
    document.querySelectorAll('.faq-item').forEach((item) => {
        const question = item.querySelector('.faq-question');
        if (!question) return;
        question.addEventListener('click', () => {
            const wasActive = item.classList.contains('active');
            item.parentElement.querySelectorAll('.faq-item').forEach((i) => i.classList.remove('active'));
            if (!wasActive) {
                item.classList.add('active');
            }
        });
    });

    // --- Gallery Scroll Reveal Animations ---
    const galleryObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                // Optional: stop observing once revealed
                // galleryObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });

    document.querySelectorAll('.gallery-img').forEach((img) => {
        galleryObserver.observe(img);
    });

    // --- FAQ Search and Categories ---
    const faqSearchInput = document.getElementById('faq-search-input');
    const faqCatBtns = document.querySelectorAll('.faq-cat-btn');
    const faqItems = document.querySelectorAll('.faq-item');
    const faqNoResults = document.getElementById('faq-no-results');

    function filterFaqs() {
        if (!faqSearchInput) return;
        const query = faqSearchInput.value.toLowerCase();
        const activeBtn = document.querySelector('.faq-cat-btn.active');
        const activeCategory = activeBtn ? activeBtn.getAttribute('data-filter') : 'all';
        let visibleCount = 0;

        faqItems.forEach(item => {
            const questionElement = item.querySelector('.faq-question');
            const answerElement = item.querySelector('.faq-answer');
            if(!questionElement || !answerElement) return;
            
            const questionText = questionElement.textContent.toLowerCase();
            const answerText = answerElement.textContent.toLowerCase();
            const itemCategory = item.getAttribute('data-category');
            
            const matchesSearch = questionText.includes(query) || answerText.includes(query);
            const matchesCategory = (activeCategory === 'all' || itemCategory === activeCategory);

            if (matchesSearch && matchesCategory) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        if (faqNoResults) {
            faqNoResults.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    if (faqSearchInput) {
        faqSearchInput.addEventListener('input', filterFaqs);
    }
    
    faqCatBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            faqCatBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            filterFaqs();
        });
    });

});
