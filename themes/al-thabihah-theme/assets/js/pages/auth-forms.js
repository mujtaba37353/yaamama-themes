document.addEventListener('DOMContentLoaded', function () {
    const passwordToggles = document.querySelectorAll('.y-c-password-toggle');
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function () {
            const passwordWrapper = this.closest('.y-l-password-wrapper');
            if (!passwordWrapper) return;
            const passwordInput = passwordWrapper.querySelector('.y-c-form-input');
            if (!passwordInput) return;
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            if (type === 'text') {
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    });
});
