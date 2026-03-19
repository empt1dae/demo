document.addEventListener('DOMContentLoaded', function () {
    const alert = document.querySelector('[data-alert]');

    if (alert) {
        setTimeout(function () {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-6px)';
            alert.style.transition = '0.35s ease';

            setTimeout(function () {
                alert.remove();
            }, 350);
        }, 2600);
    }
});
