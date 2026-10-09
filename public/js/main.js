document.addEventListener('DOMContentLoaded', () => {

    // --- Hide Header on Scroll Down, Reveal on Scroll Up ---
    const siteHeader = document.querySelector('.site-header');
    if (siteHeader) {
        let lastScrollY = window.scrollY;
        window.addEventListener('scroll', () => {
            const currentScrollY = window.scrollY;
            if (currentScrollY > lastScrollY && currentScrollY > 100) {
                siteHeader.classList.add('header-hidden');
                document.body.classList.add('header-hidden');
            } else {
                siteHeader.classList.remove('header-hidden');
                document.body.classList.remove('header-hidden');
            }
            lastScrollY = currentScrollY;
        });
    }
    
    // --- Mobile Menu Toggle ---
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mainNav = document.querySelector('.main-nav');
    const navBackdrop = document.querySelector('.nav-backdrop');

    function closeMobileNav() {
        mainNav.classList.remove('active');
        if (navBackdrop) navBackdrop.classList.remove('active');
        document.body.classList.remove('nav-open');
        const icon = mobileMenuToggle.querySelector('i');
        icon.classList.remove('fa-times');
        icon.classList.add('fa-bars');
    }

    if (mobileMenuToggle && mainNav) {
        mobileMenuToggle.addEventListener('click', () => {
            const isOpen = mainNav.classList.toggle('active');
            if (navBackdrop) navBackdrop.classList.toggle('active', isOpen);
            document.body.classList.toggle('nav-open', isOpen);
            const icon = mobileMenuToggle.querySelector('i');
            if (isOpen) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }

    if (navBackdrop) {
        navBackdrop.addEventListener('click', closeMobileNav);
    }

    // --- Mobile Nav: tap-to-expand Services submenu ---
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    if (dropdownToggle) {
        dropdownToggle.addEventListener('click', () => {
            const parentLi = dropdownToggle.closest('.has-dropdown');
            const isOpen = parentLi.classList.toggle('mobile-open');
            dropdownToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
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

    // --- Chat Assistant: quick-reply widget (no AI backend) ---
    const chatbotFab = document.querySelector('.chatbot-fab');
    const chatbotPanel = document.getElementById('chatbotPanel');
    const chatbotClose = document.getElementById('chatbotClose');
    const chatbotMessages = document.getElementById('chatbotMessages');
    const chatbotForm = document.getElementById('chatbotForm');
    const chatbotInput = document.getElementById('chatbotInput');
    const root = (typeof SITE_URLROOT !== 'undefined' && SITE_URLROOT) ? SITE_URLROOT : '';

    const CHATBOT_TOPICS = {
        pricing: {
            label: '💰 Pricing & Plans',
            keywords: ['price', 'pricing', 'cost', 'plan', 'plans', 'fee', 'fees', 'rate', 'rates', 'charges', 'budget'],
            reply: "Pricing depends on the workspace type and how long you need it — hot desks, dedicated desks, private offices and meeting rooms each have their own plans. The quickest way to get an exact quote is to send us your requirement.",
            cta: { text: 'Enquire Now', href: root + '/contact-us#enquiry' }
        },
        location: {
            label: '📍 Our Location',
            keywords: ['location', 'address', 'where', 'directions', 'map', 'situated', 'reach'],
            reply: "We're at AV Info Tech Park, Keeranatham Road, near KGiSL Campus, Saravanampatti, Coimbatore – 641035.",
            cta: { text: 'Get Directions', href: 'https://maps.google.com/?q=AV+Info+Tech+Park,+Saravanampatti,+Coimbatore', external: true }
        },
        services: {
            label: '🏢 Services We Offer',
            keywords: ['service', 'services', 'offer', 'offerings', 'desk', 'office', 'room', 'rooms', 'amenities', 'facilities'],
            reply: "We offer Hot Desks, Dedicated Desks, Private Offices, Meeting Rooms, Virtual Offices, Day Passes and Event Spaces.",
            cta: { text: 'View All Services', href: root + '/services' }
        },
        tour: {
            label: '📅 Book a Tour',
            keywords: ['tour', 'visit', 'book', 'schedule', 'demo', 'walkthrough', 'appointment', 'meet'],
            reply: "We'd love to show you around! Message us on WhatsApp with a time that works for you and we'll confirm your free tour.",
            cta: { text: 'WhatsApp Us', href: 'https://api.whatsapp.com/send/?phone=919655500001&text=Hi%20Workiify,%20I%27d%20like%20to%20book%20a%20free%20tour&type=phone_number&app_absent=0', external: true }
        }
    };

    const CHATBOT_FALLBACK = {
        reply: "I don't have an exact answer for that yet — try one of the topics below, or message our team directly on WhatsApp.",
        cta: { text: 'Chat on WhatsApp', href: 'https://api.whatsapp.com/send/?phone=919655500001&text&type=phone_number&app_absent=0', external: true }
    };

    function matchTopic(text) {
        const lower = text.toLowerCase();
        for (const key in CHATBOT_TOPICS) {
            const topic = CHATBOT_TOPICS[key];
            if (topic.keywords.some((kw) => lower.includes(kw))) return topic;
        }
        return null;
    }

    if (chatbotFab && chatbotPanel) {
        function openChatbot() {
            chatbotPanel.classList.add('active');
            chatbotFab.classList.add('is-active');
            chatbotPanel.setAttribute('aria-hidden', 'false');
            chatbotFab.setAttribute('aria-expanded', 'true');
        }
        function closeChatbot() {
            chatbotPanel.classList.remove('active');
            chatbotFab.classList.remove('is-active');
            chatbotPanel.setAttribute('aria-hidden', 'true');
            chatbotFab.setAttribute('aria-expanded', 'false');
        }

        chatbotFab.addEventListener('click', openChatbot);
        if (chatbotClose) chatbotClose.addEventListener('click', closeChatbot);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && chatbotPanel.classList.contains('active')) closeChatbot();
        });
        document.addEventListener('click', (e) => {
            if (chatbotPanel.classList.contains('active')
                && !chatbotPanel.contains(e.target)
                && !chatbotFab.contains(e.target)) {
                closeChatbot();
            }
        });

        function addUserBubble(text) {
            const userBubble = document.createElement('div');
            userBubble.className = 'chatbot-bubble user';
            userBubble.textContent = text;
            chatbotMessages.appendChild(userBubble);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }

        function addBotReply(topic) {
            const typing = document.createElement('div');
            typing.className = 'chatbot-typing';
            typing.innerHTML = '<span></span><span></span><span></span>';
            chatbotMessages.appendChild(typing);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;

            setTimeout(() => {
                typing.remove();
                const botBubble = document.createElement('div');
                botBubble.className = 'chatbot-bubble bot';
                botBubble.textContent = topic.reply;
                if (topic.cta) {
                    const link = document.createElement('a');
                    link.href = topic.cta.href;
                    link.className = 'btn btn-primary';
                    link.textContent = topic.cta.text;
                    if (topic.cta.external) link.target = '_blank';
                    botBubble.appendChild(document.createElement('br'));
                    botBubble.appendChild(link);
                }
                chatbotMessages.appendChild(botBubble);
                chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
            }, 650);
        }

        chatbotMessages.addEventListener('click', (e) => {
            const chip = e.target.closest('.chatbot-chip');
            if (!chip || chip.disabled) return;
            const topic = CHATBOT_TOPICS[chip.dataset.topic];
            if (!topic) return;

            addUserBubble(chip.textContent);
            addBotReply(topic);
        });

        if (chatbotForm && chatbotInput) {
            chatbotForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const text = chatbotInput.value.trim();
                if (!text) return;

                addUserBubble(text);
                chatbotInput.value = '';
                addBotReply(matchTopic(text) || CHATBOT_FALLBACK);
            });
        }
        
        // Hide chatbot when reaching footer
        const siteFooter = document.querySelector('.site-footer-mini') || document.querySelector('footer');
        if (siteFooter) {
            const footerObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        chatbotFab.classList.add('is-hidden');
                        // Also close panel if it's open when reaching footer
                        if (chatbotPanel.classList.contains('active')) {
                            closeChatbot();
                        }
                    } else {
                        chatbotFab.classList.remove('is-hidden');
                    }
                });
            }, { rootMargin: "0px", threshold: 0.1 });
            
            footerObserver.observe(siteFooter);
        }
    }

    // --- Enquiry Form (shared by the Home/Contact inline forms and the popup) ---
    function initEnquiryForm(form) {
        if (!form || form.dataset.enquiryBound) return;
        form.dataset.enquiryBound = 'true';

        const reqType = form.querySelector('[name="reqType"]');
        const reqValue = form.querySelector('[name="reqValue"]');
        if (reqType && reqValue) {
            reqType.addEventListener('change', function() {
                reqValue.disabled = false;
                reqValue.placeholder = this.value === 'space' ? 'Required Space (in sq. ft.)' : 'Seats (in Nos.)';
            });
        }

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            const resultEl = form.querySelector('.enquiry-result');
            const originalHtml = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            }
            if (resultEl) {
                resultEl.textContent = '';
                resultEl.className = 'enquiry-result';
            }

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then((r) => r.json())
                .then((data) => {
                    if (resultEl) {
                        resultEl.textContent = data.message;
                        resultEl.className = 'enquiry-result ' + (data.success ? 'success' : 'error');
                    }
                    if (data.success) {
                        form.reset();
                        if (reqValue) reqValue.disabled = true;
                        form.dispatchEvent(new CustomEvent('enquiry:success'));
                    }
                })
                .catch(() => {
                    if (resultEl) {
                        resultEl.textContent = 'Network error. Please check your connection and try again.';
                        resultEl.className = 'enquiry-result error';
                    }
                })
                .finally(() => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalHtml;
                    }
                });
        });
    }
    document.querySelectorAll('.enquiry-form').forEach(initEnquiryForm);

    // --- Enquiry Popup: shown once per browser session on first page view ---
    const enquiryPopup = document.getElementById('enquiryPopup');
    if (enquiryPopup) {
        const popupForm = enquiryPopup.querySelector('.enquiry-form');
        const popupClose = enquiryPopup.querySelector('.enquiry-popup-close');
        const AUTO_DISMISS_MS = 15000;
        let dismissTimer = null;

        function closePopup() {
            enquiryPopup.classList.remove('active');
            if (dismissTimer) clearTimeout(dismissTimer);
        }

        function armAutoDismiss() {
            if (dismissTimer) clearTimeout(dismissTimer);
            dismissTimer = setTimeout(closePopup, AUTO_DISMISS_MS);
        }

        function cancelAutoDismiss() {
            if (dismissTimer) {
                clearTimeout(dismissTimer);
                dismissTimer = null;
            }
        }

        if (popupClose) popupClose.addEventListener('click', closePopup);
        if (popupForm) {
            // Any interaction with the form means the visitor is paying
            // attention to it, so stop the countdown for good.
            popupForm.addEventListener('focusin', cancelAutoDismiss, { once: true });
            popupForm.addEventListener('input', cancelAutoDismiss, { once: true });
            popupForm.addEventListener('enquiry:success', () => {
                setTimeout(closePopup, 2500);
            });
        }

        if (!sessionStorage.getItem('workiifyEnquiryPopupShown')) {
            sessionStorage.setItem('workiifyEnquiryPopupShown', '1');
            setTimeout(() => {
                enquiryPopup.classList.add('active');
                armAutoDismiss();
            }, 1500);
        }
    }

});
