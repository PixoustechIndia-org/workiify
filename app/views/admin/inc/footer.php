        </main>
    </div>

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
            const isOpen = toggle.classList.toggle('open');
            target.style.display = isOpen ? 'block' : 'none';
        });

        function adminPickAsset(fieldKey, path, imgEl) {
            document.getElementById('existing_' + fieldKey).value = path;
            const preview = document.getElementById('preview_' + fieldKey);
            preview.src = ADMIN_URLROOT + path;
            preview.style.display = 'block';
            const fileInput = document.getElementById('upload_' + fieldKey);
            if (fileInput) fileInput.value = '';
            imgEl.parentElement.querySelectorAll('.admin-media-picker-thumb').forEach(function (el) { el.classList.remove('selected'); });
            imgEl.classList.add('selected');
        }
    </script>
</body>
</html>
