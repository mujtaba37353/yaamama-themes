window.darkThemeAssetUrl = window.darkThemeAssetUrl || function (path) {
    const base = window.DarkTheme && window.DarkTheme.baseUrl ? window.DarkTheme.baseUrl.replace(/\/$/, "") : "";
    return base ? `${base}/${path}` : path;
};

fetch(window.darkThemeAssetUrl("components/auth/y-c-reset-password.html"))
    .then((response) => response.text())
    .then((data) => {
        const host = document.querySelector("[data-y='reset-password']");
        if (host && !host.children.length) {
            host.innerHTML = data;
            initializeResetPassword();
        }
    });

function initializeResetPassword() {
    const resetBtn = document.getElementById('reset-password-btn');
    const modal = document.getElementById('reset-success-modal');
    const returnHomeBtn = document.getElementById('return-home-btn-reset');

    if (resetBtn) {
        resetBtn.addEventListener('click', function (e) {
            e.preventDefault();

            resetBtn.textContent = 'جاري الإرسال...';
            resetBtn.classList.add('disabled');

            setTimeout(() => {
                showSuccessModal();
                resetBtn.textContent = 'التالي';
                resetBtn.classList.remove('disabled');
            }, 1500);
        });
    }

    if (returnHomeBtn) {
        returnHomeBtn.addEventListener('click', function () {
            const homeUrl = (window.DarkTheme && window.DarkTheme.urls && window.DarkTheme.urls.home) || '/';
            window.location.href = homeUrl;
        });
    }

    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                hideSuccessModal();
            }
        });
    }

    function showSuccessModal() {
        if (modal) {
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
        }
    }

    function hideSuccessModal() {
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    }
}