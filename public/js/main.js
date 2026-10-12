document.addEventListener('DOMContentLoaded', () => {

    // --- Header is permanently sticky (scroll-hide logic removed) ---
    
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
        form.setAttribute('novalidate', 'true');

        const reqType = form.querySelector('[name="reqType"]');
        const reqValue = form.querySelector('[name="reqValue"]');
        if (reqType && reqValue) {
            reqType.addEventListener('change', function() {
                reqValue.disabled = false;
                reqValue.placeholder = this.value === 'space' ? 'Required Space (in sq. ft.)' : 'Seats (in Nos.)';
                validateField(reqType);
                if (reqValue.classList.contains('is-invalid')) {
                    validateField(reqValue);
                }
            });
        }

        function validateField(field) {
            if (!field) return true;
            let isValid = true;
            let message = '';
            const name = field.name;
            const value = (field.value || '').trim();

            if (name === 'companyName') {
                if (!value) {
                    isValid = false;
                    message = 'Please enter your company name.';
                }
            } else if (name === 'companyAddress') {
                if (!value) {
                    isValid = false;
                    message = 'Please enter your company address.';
                }
            } else if (name === 'contactNo') {
                if (!value) {
                    isValid = false;
                    message = 'Please enter a contact number.';
                } else if (!/^[0-9+\s-]{8,15}$/.test(value) || value.replace(/\D/g, '').length < 10) {
                    isValid = false;
                    message = 'Please enter a valid 10-digit contact number.';
                }
            } else if (name === 'email') {
                if (!value) {
                    isValid = false;
                    message = 'Please enter your organization email.';
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    isValid = false;
                    message = 'Please enter a valid organization email.';
                }
            } else if (name === 'reqType') {
                if (!value) {
                    isValid = false;
                    message = 'Please select a requirement type.';
                }
            } else if (name === 'reqValue') {
                if (!field.disabled && (!value || parseInt(value, 10) <= 0)) {
                    isValid = false;
                    message = 'Please enter requirement value.';
                }
            }

            const group = field.closest('.form-group');
            if (group) {
                let errorEl = group.querySelector('.form-error-msg');
                const wrapper = field.closest('.select-wrapper');
                if (!isValid) {
                    field.classList.add('is-invalid');
                    if (wrapper) wrapper.classList.add('is-invalid');
                    if (!errorEl) {
                        errorEl = document.createElement('div');
                        errorEl.className = 'form-error-msg';
                        group.appendChild(errorEl);
                    }
                    errorEl.textContent = message;
                } else {
                    field.classList.remove('is-invalid');
                    if (wrapper) wrapper.classList.remove('is-invalid');
                    if (errorEl) errorEl.remove();
                }
            }

            return isValid;
        }

        // Live input feedback: clear errors as user types
        const inputsToValidate = form.querySelectorAll('input[required], select[required]');
        inputsToValidate.forEach((input) => {
            input.addEventListener('input', () => {
                if (input.classList.contains('is-invalid')) {
                    validateField(input);
                }
            });
            input.addEventListener('change', () => {
                if (input.classList.contains('is-invalid')) {
                    validateField(input);
                }
            });
            input.addEventListener('blur', () => {
                if (input.value.trim() !== '') {
                    validateField(input);
                }
            });
        });

        function clearAllErrors() {
            form.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));
            form.querySelectorAll('.form-error-msg').forEach((el) => el.remove());
        }

        form.addEventListener('submit', (e) => {
            e.preventDefault();

            // Validate all required fields simultaneously
            let isFormValid = true;
            let firstInvalidField = null;

            inputsToValidate.forEach((input) => {
                const valid = validateField(input);
                if (!valid) {
                    isFormValid = false;
                    if (!firstInvalidField) firstInvalidField = input;
                }
            });

            if (!isFormValid) {
                if (firstInvalidField) {
                    firstInvalidField.focus();
                }
                return;
            }

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
                        clearAllErrors();
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

    // --- Testimonial / Feedback Form Validation ---
    function initFeedbackForm(form) {
        if (!form || form.dataset.feedbackBound) return;
        form.dataset.feedbackBound = 'true';
        form.setAttribute('novalidate', 'true');

        function validateFeedbackField(field) {
            if (!field) return true;
            let isValid = true;
            let message = '';
            const name = field.name;
            const value = (field.value || '').trim();

            if (name === 'full_name') {
                if (!value) {
                    isValid = false;
                    message = 'Please enter your full name.';
                }
            } else if (name === 'email') {
                if (!value) {
                    isValid = false;
                    message = 'Please enter your email.';
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    isValid = false;
                    message = 'Please enter a valid email address.';
                }
            } else if (name === 'rating') {
                const checked = form.querySelector('input[name="rating"]:checked');
                if (!checked) {
                    isValid = false;
                    message = 'Please select a star rating.';
                }
            } else if (name === 'feedback') {
                if (!value) {
                    isValid = false;
                    message = 'Please enter your feedback.';
                } else if (value.length < 20) {
                    isValid = false;
                    message = 'Feedback must be at least 20 characters.';
                }
            } else if (name === 'consent') {
                if (!field.checked) {
                    isValid = false;
                    message = 'Please agree to the publication consent.';
                }
            }

            const group = field.closest ? field.closest('.form-group') : form.querySelector('.rating-input')?.closest('.form-group');
            if (group) {
                let errorEl = group.querySelector('.invalid-feedback');
                if (!isValid) {
                    if (field.classList) field.classList.add('is-invalid');
                    if (!errorEl) {
                        errorEl = document.createElement('span');
                        errorEl.className = 'invalid-feedback';
                        group.appendChild(errorEl);
                    }
                    errorEl.textContent = message;
                    errorEl.style.display = 'block';
                } else {
                    if (field.classList) field.classList.remove('is-invalid');
                    if (errorEl) {
                        errorEl.textContent = '';
                        errorEl.style.display = 'none';
                    }
                }
            }

            return isValid;
        }

        const inputs = form.querySelectorAll('input[required], textarea[required]');
        inputs.forEach((input) => {
            input.addEventListener('input', () => {
                if (input.classList.contains('is-invalid') || input.value.trim().length > 0) {
                    validateFeedbackField(input);
                }
            });
            input.addEventListener('change', () => validateFeedbackField(input));
        });

        const ratingRadios = form.querySelectorAll('input[name="rating"]');
        ratingRadios.forEach((r) => {
            r.addEventListener('change', () => {
                validateFeedbackField({ name: 'rating' });
            });
        });

        // Profile photo input single-file circular avatar preview handler
        const photoInput = form.querySelector('input[type="file"][name="photo"], #photo');
        const avatarPreviewImg = form.querySelector('#avatarPreviewImg');
        const avatarDefaultIcon = form.querySelector('#avatarDefaultIcon');
        const labelText = form.querySelector('#photos-label-text');

        if (photoInput) {
            photoInput.addEventListener('change', () => {
                const file = photoInput.files && photoInput.files[0];
                if (!file) {
                    if (avatarPreviewImg) {
                        avatarPreviewImg.style.display = 'none';
                        avatarPreviewImg.src = '';
                    }
                    if (avatarDefaultIcon) avatarDefaultIcon.style.display = 'block';
                    if (labelText) labelText.textContent = 'Choose Profile Photo';
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file (JPG, PNG, or WebP).');
                    photoInput.value = '';
                    return;
                }

                if (labelText) {
                    labelText.textContent = file.name.length > 25 ? file.name.substring(0, 22) + '...' : file.name;
                }

                const reader = new FileReader();
                reader.onload = (ev) => {
                    if (avatarPreviewImg) {
                        avatarPreviewImg.src = ev.target.result;
                        avatarPreviewImg.style.display = 'block';
                    }
                    if (avatarDefaultIcon) {
                        avatarDefaultIcon.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            });
        }

        form.addEventListener('submit', (e) => {
            let isFormValid = true;
            let firstInvalid = null;

            inputs.forEach((input) => {
                const valid = validateFeedbackField(input);
                if (!valid) {
                    isFormValid = false;
                    if (!firstInvalid) firstInvalid = input;
                }
            });

            // Validate rating
            const ratingChecked = form.querySelector('input[name="rating"]:checked');
            if (!ratingChecked) {
                validateFeedbackField({ name: 'rating' });
                isFormValid = false;
                if (!firstInvalid) firstInvalid = form.querySelector('.rating-input');
            }

            if (!isFormValid) {
                e.preventDefault();
                if (firstInvalid) {
                    if (typeof firstInvalid.focus === 'function') firstInvalid.focus();
                    else firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }
    document.querySelectorAll('.feedback-form').forEach(initFeedbackForm);

    // --- Share Your Experience Smooth Scroll & Form Highlight ---
    const btnShareExperience = document.getElementById('btnShareExperience');
    const floatingShareCta = document.getElementById('floatingShareCta');
    const feedbackFormSection = document.getElementById('feedbackFormSection');

    if (btnShareExperience && feedbackFormSection) {
        btnShareExperience.addEventListener('click', (e) => {
            e.preventDefault();
            feedbackFormSection.scrollIntoView({ behavior: 'smooth', block: 'start' });

            // Trigger dynamic running glow animation on the form
            feedbackFormSection.classList.remove('highlight-glow');
            void feedbackFormSection.offsetWidth; // Force DOM reflow
            feedbackFormSection.classList.add('highlight-glow');

            // Focus the first form input after scrolling smoothly
            setTimeout(() => {
                const firstInput = feedbackFormSection.querySelector('input#full_name');
                if (firstInput) firstInput.focus();
            }, 700);

            // Remove glow after 8 seconds of active display
            setTimeout(() => {
                feedbackFormSection.classList.remove('highlight-glow');
            }, 8000);
        });

        // Hide floating CTA button reliably when scrolling near/into the feedback form section
        function updateFloatingCtaVisibility() {
            if (!floatingShareCta || !feedbackFormSection) return;
            const formRect = feedbackFormSection.getBoundingClientRect();
            const windowHeight = window.innerHeight;

            // When the top of the form is within 250px of the viewport bottom or above it
            const isNearOrInsideForm = formRect.top <= (windowHeight - 80);

            if (isNearOrInsideForm) {
                floatingShareCta.classList.add('is-hidden');
            } else {
                floatingShareCta.classList.remove('is-hidden');
            }
        }

        window.addEventListener('scroll', updateFloatingCtaVisibility, { passive: true });
        window.addEventListener('resize', updateFloatingCtaVisibility, { passive: true });
        updateFloatingCtaVisibility(); // Initial check on load
    }

    // --- Testimonial Photos Lightbox / Modal Viewer ---
    function initTestimonialAlbumViewer() {
        const lightbox = document.getElementById('testimonial-lightbox');
        if (!lightbox) return;

        const backdrop = document.getElementById('tl-backdrop');
        const closeBtn = document.getElementById('tl-close-btn');
        const prevBtn = document.getElementById('tl-prev-btn');
        const nextBtn = document.getElementById('tl-next-btn');
        const activeImg = document.getElementById('tl-active-img');
        const counterEl = document.getElementById('tl-counter');
        const captionEl = document.getElementById('tl-author-caption');
        const thumbsEl = document.getElementById('tl-thumbnails');

        let currentPhotos = [];
        let currentIndex = 0;
        let currentAuthor = '';

        function renderActivePhoto() {
            if (!currentPhotos.length) return;
            const photoUrl = currentPhotos[currentIndex];

            activeImg.style.opacity = '0.3';
            const tempImg = new Image();
            tempImg.src = photoUrl;
            tempImg.onload = () => {
                activeImg.src = photoUrl;
                activeImg.style.opacity = '1';
            };

            if (counterEl) {
                counterEl.textContent = `${currentIndex + 1} / ${currentPhotos.length}`;
            }

            if (captionEl) {
                captionEl.textContent = currentAuthor ? `Photos from ${currentAuthor}` : 'Community Feedback Photos';
            }

            // Update thumbnails active state
            if (thumbsEl) {
                const thumbItems = thumbsEl.querySelectorAll('.tl-thumb-item');
                thumbItems.forEach((thumb, idx) => {
                    if (idx === currentIndex) {
                        thumb.classList.add('active');
                        thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                    } else {
                        thumb.classList.remove('active');
                    }
                });
            }

            // Hide nav buttons if only 1 photo
            if (prevBtn) prevBtn.style.display = currentPhotos.length > 1 ? 'flex' : 'none';
            if (nextBtn) nextBtn.style.display = currentPhotos.length > 1 ? 'flex' : 'none';
        }

        function openLightbox(photos, startIndex, author) {
            currentPhotos = photos;
            currentIndex = startIndex >= 0 && startIndex < photos.length ? startIndex : 0;
            currentAuthor = author || '';

            // Build thumbnail strip
            if (thumbsEl) {
                thumbsEl.innerHTML = '';
                if (currentPhotos.length > 1) {
                    thumbsEl.style.display = 'flex';
                    currentPhotos.forEach((src, idx) => {
                        const thumb = document.createElement('div');
                        thumb.className = `tl-thumb-item ${idx === currentIndex ? 'active' : ''}`;
                        thumb.innerHTML = `<img src="${src}" alt="Thumbnail ${idx + 1}">`;
                        thumb.addEventListener('click', () => {
                            currentIndex = idx;
                            renderActivePhoto();
                        });
                        thumbsEl.appendChild(thumb);
                    });
                } else {
                    thumbsEl.style.display = 'none';
                }
            }

            renderActivePhoto();
            lightbox.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            lightbox.style.display = 'none';
            document.body.style.overflow = '';
            if (activeImg) activeImg.src = '';
            currentPhotos = [];
        }

        function prevPhoto() {
            if (currentPhotos.length <= 1) return;
            currentIndex = (currentIndex - 1 + currentPhotos.length) % currentPhotos.length;
            renderActivePhoto();
        }

        function nextPhoto() {
            if (currentPhotos.length <= 1) return;
            currentIndex = (currentIndex + 1) % currentPhotos.length;
            renderActivePhoto();
        }

        if (prevBtn) prevBtn.addEventListener('click', (e) => { e.stopPropagation(); prevPhoto(); });
        if (nextBtn) nextBtn.addEventListener('click', (e) => { e.stopPropagation(); nextPhoto(); });
        if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
        if (backdrop) backdrop.addEventListener('click', closeLightbox);

        document.addEventListener('keydown', (e) => {
            if (lightbox.style.display === 'flex') {
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowLeft') prevPhoto();
                if (e.key === 'ArrowRight') nextPhoto();
            }
        });

        // Delegate click on any testimonial album thumbnail or badge
        document.querySelectorAll('.testimonial-album').forEach((album) => {
            let photos = [];
            try {
                photos = JSON.parse(album.getAttribute('data-photos') || '[]');
            } catch (err) {
                photos = [];
            }
            const author = album.getAttribute('data-author') || '';

            album.querySelectorAll('.album-thumb-item').forEach((item) => {
                item.addEventListener('click', () => {
                    const idx = parseInt(item.getAttribute('data-index') || '0', 10);
                    openLightbox(photos, idx, author);
                });
                item.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        const idx = parseInt(item.getAttribute('data-index') || '0', 10);
                        openLightbox(photos, idx, author);
                    }
                });
            });
        });
    }
    initTestimonialAlbumViewer();

    // --- Enquiry Popup: shown once per browser session, 20s after the visitor arrives ---
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

        // Show once per browser session, POPUP_DELAY_MS after the visitor first
        // arrived on the site. The arrival time is kept in sessionStorage, so
        // browsing between pages doesn't restart the countdown, and the
        // "shown" flag is only set when the popup really appears (a visitor who
        // leaves before the delay hasn't "used up" their popup).
        const POPUP_DELAY_MS = 20000;
        try {
            if (!sessionStorage.getItem('workiifyEnquiryPopupShown')) {
                let arrival = parseInt(sessionStorage.getItem('workiifyArrivalTime'), 10);
                if (!arrival) {
                    arrival = Date.now();
                    sessionStorage.setItem('workiifyArrivalTime', String(arrival));
                }
                const wait = Math.max(0, POPUP_DELAY_MS - (Date.now() - arrival));
                setTimeout(() => {
                    if (sessionStorage.getItem('workiifyEnquiryPopupShown')) return;
                    sessionStorage.setItem('workiifyEnquiryPopupShown', '1');
                    enquiryPopup.classList.add('active');
                    armAutoDismiss();
                }, wait);
            }
        } catch (e) { /* storage blocked (private mode): skip the popup rather than nag on every page */ }

        // Keep the chat button out of the way while the popup is open.
        new MutationObserver(() => {
            document.body.classList.toggle('enquiry-popup-open', enquiryPopup.classList.contains('active'));
        }).observe(enquiryPopup, { attributes: true, attributeFilter: ['class'] });
    }

    // --- Chat button: fade while scrolling on small screens so it never sits on top of text being read ---
    (function () {
        const fab = document.querySelector('.chatbot-fab');
        if (!fab) return;
        let t = null;
        window.addEventListener('scroll', () => {
            if (window.innerWidth > 767) return;
            fab.classList.add('is-scrolling');
            clearTimeout(t);
            t = setTimeout(() => fab.classList.remove('is-scrolling'), 800);
        }, { passive: true });
    })();

    // --- Shared widgets used by the Coimbatore service pages ---
    // Carousel (continuous hardware-accelerated cyclic loop with auto-advance)
    document.querySelectorAll('.sw-carousel-container').forEach(box => {
        const track = box.querySelector('.sw-carousel-track');
        const prev = box.querySelector('.sw-prev');
        const next = box.querySelector('.sw-next');
        if (!track) return;

        let viewport = box.querySelector('.sw-carousel-viewport');
        if (!viewport) {
            viewport = document.createElement('div');
            viewport.className = 'sw-carousel-viewport';
            track.parentNode.insertBefore(viewport, track);
            viewport.appendChild(track);
        }

        const originalSlides = Array.from(track.querySelectorAll('.sw-carousel-slide:not(.sw-slide-clone)'));
        if (originalSlides.length <= 1) {
            if (prev) prev.style.display = 'none';
            if (next) next.style.display = 'none';
            return;
        }

        // Clean any existing clones if re-init
        track.querySelectorAll('.sw-slide-clone').forEach(el => el.remove());

        // Clone first and last slide for seamless infinite looping
        const firstClone = originalSlides[0].cloneNode(true);
        const lastClone = originalSlides[originalSlides.length - 1].cloneNode(true);
        firstClone.classList.add('sw-slide-clone');
        lastClone.classList.add('sw-slide-clone');
        firstClone.setAttribute('aria-hidden', 'true');
        lastClone.setAttribute('aria-hidden', 'true');

        track.appendChild(firstClone);
        track.insertBefore(lastClone, originalSlides[0]);

        const allSlides = Array.from(track.children);
        let currentIndex = 1;
        let isTransitioning = false;
        let autoplayTimer = null;

        const getStep = () => {
            const gap = parseFloat(getComputedStyle(track).gap) || 24;
            return viewport.clientWidth + gap;
        };

        const updateTrackPosition = (animate = true) => {
            const step = getStep();
            if (animate) {
                track.style.transition = 'transform 0.48s cubic-bezier(0.25, 1, 0.5, 1)';
            } else {
                track.style.transition = 'none';
            }
            track.style.transform = `translate3d(${-currentIndex * step}px, 0, 0)`;
        };

        const goToSlide = (index) => {
            if (isTransitioning) return;
            isTransitioning = true;
            currentIndex = index;
            updateTrackPosition(true);
        };

        const nextSlide = () => {
            if (isTransitioning) return;
            goToSlide(currentIndex + 1);
        };

        const prevSlide = () => {
            if (isTransitioning) return;
            goToSlide(currentIndex - 1);
        };

        // Seamless wrap-around jump when hitting clones (zero visible rewind)
        track.addEventListener('transitionend', (e) => {
            if (e.target !== track || e.propertyName !== 'transform') return;
            isTransitioning = false;

            // If we reached the clone of the first slide at the far right
            if (currentIndex >= allSlides.length - 1) {
                currentIndex = 1;
                updateTrackPosition(false);
                void track.offsetHeight;
            }
            // If we reached the clone of the last slide at the far left
            else if (currentIndex <= 0) {
                currentIndex = originalSlides.length;
                updateTrackPosition(false);
                void track.offsetHeight;
            }
        });

        // Initialize at the real first slide (index 1)
        updateTrackPosition(false);
        window.addEventListener('load', () => updateTrackPosition(false));
        window.addEventListener('resize', () => {
            if (!isTransitioning) updateTrackPosition(false);
        });

        // Smooth automatic cyclic progression
        const startAutoplay = () => {
            stopAutoplay();
            autoplayTimer = setInterval(() => {
                nextSlide();
            }, 4500);
        };

        const stopAutoplay = () => {
            if (autoplayTimer) {
                clearInterval(autoplayTimer);
                autoplayTimer = null;
            }
        };

        box.addEventListener('mouseenter', stopAutoplay);
        box.addEventListener('mouseleave', startAutoplay);
        box.addEventListener('focusin', stopAutoplay);
        box.addEventListener('focusout', startAutoplay);

        if (next) {
            next.addEventListener('click', (e) => {
                e.preventDefault();
                nextSlide();
                startAutoplay();
            });
        }

        if (prev) {
            prev.addEventListener('click', (e) => {
                e.preventDefault();
                prevSlide();
                startAutoplay();
            });
        }

        // Touch swiping support
        let touchStartX = 0;
        viewport.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
            stopAutoplay();
        }, { passive: true });

        viewport.addEventListener('touchend', (e) => {
            const diff = e.changedTouches[0].screenX - touchStartX;
            if (Math.abs(diff) > 40) {
                if (diff < 0) nextSlide();
                else prevSlide();
            }
            startAutoplay();
        }, { passive: true });

        // Kick off smooth autoplay
        startAutoplay();
    });

    // Location ticker (rotating highlights)
    document.querySelectorAll('.sw-location-ticker').forEach(wrap => {
        const items = wrap.querySelectorAll('.sw-ticker-item');
        if (items.length < 2) return;
        let i = 0;
        setInterval(() => {
            items[i].classList.remove('active');
            i = (i + 1) % items.length;
            items[i].classList.add('active');
        }, 4000);
    });

    // Tabs (each .sw-amenities-tabs widget is independent)
    document.querySelectorAll('.sw-amenities-tabs').forEach(widget => {
        const tabs = widget.querySelectorAll('.sw-tab-btn');
        const panes = widget.querySelectorAll('.sw-tab-pane');
        tabs.forEach(tab => tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            panes.forEach(p => p.classList.remove('active'));
            tab.classList.add('active');
            const pane = widget.querySelector('#' + tab.getAttribute('data-target'));
            if (pane) pane.classList.add('active');
        }));
    });

    // Audience accordion: hover on desktop, tap on touch screens
    document.querySelectorAll('.sw-audience-accordion').forEach(acc => {
        const cards = acc.querySelectorAll('.sw-aud-card');
        const activate = card => {
            cards.forEach(c => c.classList.remove('active'));
            card.classList.add('active');
        };
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => activate(card));
            card.addEventListener('click', () => activate(card));
        });
    });

});
