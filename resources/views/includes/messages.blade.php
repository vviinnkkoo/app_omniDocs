<div id="popup-alert-container" style="position: fixed; top: 1rem; right: 1rem; z-index: 1055;"></div>

<script>
    function showPopupAlert(message, type = 'success') {
        const alertId = 'alert-' + Date.now();
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.setAttribute('role', 'alert');
        alert.id = alertId;

        alert.style.boxShadow = `0 0 20px rgba(0, 0, 0, 0.25)`;
        alert.style.fontSize = '16px';

        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.getElementById('popup-alert-container').appendChild(alert);

        setTimeout(() => {
            const alertToRemove = document.getElementById(alertId);
            if (alertToRemove) {
                alertToRemove.classList.remove('show');
                setTimeout(() => alertToRemove.remove(), 300); // fade out
            }
        }, 5000);
    }

    @if(session('success'))
        showPopupAlert(`{{ session('success') }}`, 'success');
    @endif

    @if(session('error'))
        showPopupAlert(`{{ session('error') }}`, 'danger');
    @endif

    @if(session('warning'))
        showPopupAlert(`{{ session('warning') }}`, 'warning');
    @endif

    @if(session('info'))
        showPopupAlert(`{{ session('info') }}`, 'info');
    @endif
</script>
