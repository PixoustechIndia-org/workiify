<?php /* Shared confirmation dialog: any <form data-confirm-title="…" data-confirm-text="…" data-confirm-button="…"> asks first. */ ?>
<!-- Confirmation dialog (used for Trash / Delete forever) -->
<div class="svc-modal" id="svcModal" hidden>
    <div class="svc-modal-backdrop" data-close></div>
    <div class="svc-modal-box" role="alertdialog" aria-modal="true" aria-labelledby="svcModalTitle" aria-describedby="svcModalText">
        <div class="svc-modal-icon" id="svcModalIcon"><i class="fas fa-triangle-exclamation"></i></div>
        <h3 id="svcModalTitle"></h3>
        <p id="svcModalText"></p>
        <div class="svc-modal-actions">
            <button type="button" class="admin-btn admin-btn-secondary" data-close>Cancel</button>
            <button type="button" class="admin-btn admin-btn-danger" id="svcModalConfirm">Confirm</button>
        </div>
    </div>
</div>

<script>
(function () {
    var modal = document.getElementById('svcModal');
    var pending = null;
    var lastFocus = null;

    function open(form) {
        pending = form;
        lastFocus = document.activeElement;
        document.getElementById('svcModalTitle').textContent = form.dataset.confirmTitle || 'Are you sure?';
        document.getElementById('svcModalText').textContent = form.dataset.confirmText || '';
        var btn = document.getElementById('svcModalConfirm');
        btn.textContent = form.dataset.confirmButton || 'Confirm';
        var safe = form.dataset.confirmTone === 'primary';
        btn.className = 'admin-btn ' + (safe ? '' : 'admin-btn-danger');
        btn.disabled = false;
        document.getElementById('svcModalIcon').className = 'svc-modal-icon' + (safe ? ' is-primary' : '');
        document.getElementById('svcModalIcon').innerHTML = '<i class="fas ' + (safe ? 'fa-circle-question' : 'fa-triangle-exclamation') + '"></i>';
        modal.hidden = false;
        btn.focus();
    }
    function close() {
        modal.hidden = true;
        pending = null;
        if (lastFocus) lastFocus.focus();
    }

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (form.dataset && form.dataset.confirmTitle && !form.dataset.confirmed) {
            e.preventDefault();
            open(form);
        }
    });
    modal.addEventListener('click', function (e) { if (e.target.hasAttribute('data-close')) close(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !modal.hidden) close(); });
    document.getElementById('svcModalConfirm').addEventListener('click', function () {
        if (!pending) return;
        pending.dataset.confirmed = '1';
        this.disabled = true;
        pending.submit();
    });
})();
</script>
