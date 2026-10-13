        </main>
    </div>

    <?php require APPROOT . '/views/admin/inc/confirm_modal.php'; ?>

    <script>
        const ADMIN_URLROOT = <?php echo json_encode(URLROOT); ?>;

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.admin-toggle-library');
            if (!btn) return;
            const target = document.getElementById(btn.dataset.target);
            if (target) target.style.display = (target.style.display === 'none' || !target.style.display) ? 'grid' : 'none';
        });

        document.addEventListener('click', function (e) {
            const toggle = e.target.closest('.admin-nav-page-toggle:not(.disabled)');
            if (!toggle) return;
            const target = document.getElementById(toggle.dataset.target);
            if (!target) return;
            toggle.classList.toggle('open');
            target.classList.toggle('expanded');
            toggle.setAttribute('aria-expanded', toggle.classList.contains('open') ? 'true' : 'false');
        });

        // ---- Mobile / tablet: the sidebar slides in over the page ----
        (function () {
            const btn = document.getElementById('adminMenuToggle');
            const backdrop = document.getElementById('adminSidebarBackdrop');
            if (!btn) return;
            function setOpen(open) {
                document.body.classList.toggle('admin-nav-open', open);
                btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            }
            btn.addEventListener('click', function () { setOpen(!document.body.classList.contains('admin-nav-open')); });
            if (backdrop) backdrop.addEventListener('click', function () { setOpen(false); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setOpen(false); });
            // keep the current page's link in view inside the sidebar (without scrolling the page itself)
            const sidebar = document.getElementById('adminSidebar');
            const active = sidebar && sidebar.querySelector('.admin-nav-link.active');
            if (active) {
                const top = active.getBoundingClientRect().top - sidebar.getBoundingClientRect().top + sidebar.scrollTop;
                if (top > sidebar.clientHeight - 60) sidebar.scrollTop = top - sidebar.clientHeight / 2;
            }
        })();

        // ---- Sidebar: filter the service list ----
        (function () {
            const input = document.getElementById('adminNavSvcFilter');
            if (!input) return;
            input.addEventListener('input', function () {
                const q = input.value.trim().toLowerCase();
                let shown = 0;
                document.querySelectorAll('#admin-nav-svc-list li[data-name]').forEach(function (li) {
                    const ok = !q || li.dataset.name.indexOf(q) !== -1;
                    li.hidden = !ok;
                    if (ok) shown++;
                });
                document.getElementById('adminNavSvcEmpty').hidden = shown !== 0;
            });
        })();

        function adminPickAsset(fieldKey, path, imgEl) {
            const hidden = document.getElementById('existing_' + fieldKey);
            hidden.value = path;
            const preview = document.getElementById('preview_' + fieldKey);
            preview.src = ADMIN_URLROOT + path;
            preview.style.display = 'block';
            const fileInput = document.getElementById('upload_' + fieldKey);
            if (fileInput) { fileInput.value = ''; adminClearFieldError(fileInput); }
            imgEl.closest('.admin-media-picker').querySelectorAll('.admin-media-picker-thumb').forEach(function (el) { el.classList.remove('selected'); });
            imgEl.classList.add('selected');
            hidden.dispatchEvent(new Event('change', { bubbles: true }));
        }

        function adminUpdateIconPreview(fieldKey) {
            const input = document.getElementById(fieldKey);
            const preview = document.getElementById('iconPreview_' + fieldKey);
            if (input && preview) {
                preview.className = 'fas ' + (input.value.trim() || 'fa-icons');
            }
        }

        function adminPickIcon(fieldKey, iconClass, btnEl) {
            const input = document.getElementById(fieldKey);
            if (input) {
                input.value = iconClass;
                input.dispatchEvent(new Event('input', { bubbles: true }));
            }
            adminUpdateIconPreview(fieldKey);
            btnEl.closest('.admin-icon-picker').querySelectorAll('.admin-icon-picker-tile').forEach(function (el) { el.classList.remove('selected'); });
            btnEl.classList.add('selected');
        }

        // ---- Validation: the same format rules the server applies (Home_content_model::checkRule) ----
        const ADMIN_RULES = {
            email: [/^[^\s@]+@[^\s@]+\.[^\s@]+$/, 'Enter a valid email address (e.g. info@workiify.com).'],
            phone: [/^\+?[0-9][0-9 \-]{6,18}$/, 'Enter a valid phone number (digits only, e.g. +919655500001).'],
            mobile: [/^(\+91|0)?[\s\-]?[6-9][0-9]{4}[\s\-]?[0-9]{5}$/, 'Enter a valid 10-digit mobile number (starting with 6-9).'],
            link: [/^(\/|#|https?:\/\/|mailto:|tel:)\S*$/i, 'Enter a page path starting with "/" (e.g. /contact-us), a full https:// address, or a tel:/mailto: link, with no spaces.'],
            number: [/^[0-9]+(\.[0-9]+)?$/, 'Enter a number only (e.g. 120).'],
            rating: [/^[0-5](\.[0-9])?$/, 'Enter a rating from 0 to 5 (e.g. 4.8).'],
            icon: [/^fa-[a-z0-9-]+$/, 'Pick an icon, or type a Font Awesome name such as fa-wifi.']
        };
        const ADMIN_MAX_UPLOAD = 5 * 1024 * 1024;

        // Where a field's message goes: after the field's own wrapper when it has one.
        function adminErrorAnchor(el) {
            return el.closest('.admin-icon-preview-row, .svc-slug-input, .admin-image-field, .admin-toggle-row, .admin-rating-input') || el;
        }
        function adminClearFieldError(el) {
            el.classList.remove('is-invalid');
            el.removeAttribute('aria-invalid');
            const anchor = adminErrorAnchor(el);
            const next = anchor.nextElementSibling;
            if (next && next.classList.contains('admin-field-error') && next.dataset.for === (el.name || el.id)) next.remove();
        }
        function adminShowFieldError(el, msg) {
            adminClearFieldError(el);
            el.classList.add('is-invalid');
            el.setAttribute('aria-invalid', 'true');
            const div = document.createElement('div');
            div.className = 'admin-field-error';
            div.dataset.for = el.name || el.id;
            div.innerHTML = '<i class="fas fa-circle-exclamation"></i> ';
            div.appendChild(document.createTextNode(msg));
            adminErrorAnchor(el).insertAdjacentElement('afterend', div);
        }
        function adminFieldMessage(el) {
            if (el.disabled || el.type === 'hidden' || el.type === 'submit' || el.type === 'button') return null;
            const value = (el.value || '').trim();
            const label = el.dataset.label || 'This field';
            if (el.type === 'file') {
                const f = el.files && el.files[0];
                if (!f) return null;
                const types = (el.getAttribute('accept') || '').split(',').map(function (s) { return s.trim(); }).filter(Boolean);
                if (types.length && types.indexOf(f.type) === -1) return 'This file type is not allowed. Choose a ' + (el.dataset.types || 'JPG, PNG, WebP or GIF') + ' image.';
                if (f.size > ADMIN_MAX_UPLOAD) return 'This file is ' + (f.size / 1048576).toFixed(1) + ' MB; the limit is 5 MB.';
                return null;
            }
            if (el.required && !value && el.type !== 'checkbox') return el.dataset.requiredMsg || (label + ' is required.');
            if (!value) return null;
            const rule = el.dataset.rule && ADMIN_RULES[el.dataset.rule];
            if (rule && !rule[0].test(value)) return rule[1];
            if (el.dataset.rule === 'rating' && parseFloat(value) > 5) return ADMIN_RULES.rating[1];
            if (el.minLength > 0 && value.length < el.minLength) return label + ' must be at least ' + el.minLength + ' characters (currently ' + value.length + ').';
            if (el.type === 'number') {
                if (!/^-?[0-9]+$/.test(value)) return label + ' must be a whole number.';
                if (el.min !== '' && Number(value) < Number(el.min)) return label + ' must be at least ' + el.min + '.';
                if (el.max !== '' && Number(value) > Number(el.max)) return label + ' must be ' + el.max + ' or less.';
            }
            if (el.type === 'date' && el.dataset.after) {
                const other = document.getElementById(el.dataset.after);
                if (other && other.value && value < other.value) return 'The end date must be on or after the start date.';
            }
            return null;
        }

        document.querySelectorAll('form[data-validate]').forEach(function (form) {
            form.setAttribute('novalidate', '');
            form.addEventListener('input', function (e) { if (e.target.classList.contains('is-invalid')) adminClearFieldError(e.target); });
            form.addEventListener('change', function (e) {
                const el = e.target;
                if (el.type === 'file' && el.files && el.files[0]) {
                    const msg = adminFieldMessage(el);
                    if (msg) { adminShowFieldError(el, msg); el.value = ''; return; }
                    adminClearFieldError(el);
                    // show the chosen file in the field's preview straight away
                    const key = el.id.replace(/^upload_/, '');
                    const preview = document.getElementById('preview_' + key) || document.getElementById(el.dataset.preview || '');
                    if (preview && preview.tagName === 'IMG') { preview.src = URL.createObjectURL(el.files[0]); preview.style.display = 'block'; }
                } else if (el.classList.contains('is-invalid')) {
                    adminClearFieldError(el);
                }
            });
            form.addEventListener('submit', function (e) {
                let first = null;
                form.querySelectorAll('input, textarea, select').forEach(function (el) {
                    const msg = adminFieldMessage(el);
                    if (msg) { adminShowFieldError(el, msg); if (!first) first = el; }
                    else if (el.classList.contains('is-invalid') && !el.dataset.serverError) adminClearFieldError(el);
                });
                if (first) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    first.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    first.focus({ preventScroll: true });
                }
            });
        });

        // ---- Lock a form once it is really being sent, so a double click can't save twice ----
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (e.defaultPrevented || form.dataset.noLock !== undefined) return;
            form.dataset.submitting = '1';
            setTimeout(function () {   // after the browser has read the clicked button's name/value
                form.querySelectorAll('button[type="submit"], button:not([type])').forEach(function (b) {
                    b.disabled = true;
                    if (b === e.submitter && !b.classList.contains('svc-pill') && !b.closest('.svc-order, .admin-item-row-actions, .admin-table')) {
                        b.dataset.label = b.innerHTML;
                        b.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> ' + (b.dataset.busy || 'Saving…');
                    }
                });
            }, 0);
        });
        // Coming back via the browser's Back button: re-enable anything we locked.
        window.addEventListener('pageshow', function (e) {
            if (!e.persisted) return;
            document.querySelectorAll('form[data-submitting]').forEach(function (form) {
                delete form.dataset.submitting;
                form.querySelectorAll('button[disabled]').forEach(function (b) {
                    b.disabled = false;
                    if (b.dataset.label) b.innerHTML = b.dataset.label;
                });
            });
        });

        // ---- Warn before leaving a form with unsaved edits ----
        document.querySelectorAll('form[data-dirty-guard]').forEach(function (form) {
            let dirty = false;
            form.addEventListener('input', function () { dirty = true; });
            form.addEventListener('change', function () { dirty = true; });
            form.addEventListener('submit', function () { dirty = false; });
            window.addEventListener('beforeunload', function (e) {
                if (dirty && !form.dataset.submitting) { e.preventDefault(); e.returnValue = ''; }
            });
        });

        // ---- Live character counters ("12 / 40 characters") ----
        document.addEventListener('input', function (e) {
            if (!e.target.id) return;
            const counter = document.querySelector('.admin-char-count[data-for="' + e.target.id + '"]');
            if (counter) counter.textContent = e.target.value.length;
        });

        // ---- Filter dropdowns apply as soon as they change ----
        document.addEventListener('change', function (e) {
            if (e.target.matches('[data-autosubmit]')) e.target.form.requestSubmit ? e.target.form.requestSubmit() : e.target.form.submit();
        });
    </script>
</body>
</html>
