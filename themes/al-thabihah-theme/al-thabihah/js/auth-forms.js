// Al Thabihah/js/auth-forms.js

document.addEventListener('DOMContentLoaded', function () {
    // Handle floating labels for all auth forms
    const floatingInputs = document.querySelectorAll('.y-c-floating-input');

    floatingInputs.forEach(input => {
        // Check if input has value on page load
        checkInputValue(input);

        // Handle input events
        input.addEventListener('input', function () {
            checkInputValue(this);
        });

        input.addEventListener('focus', function () {
            this.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', function () {
            this.parentElement.classList.remove('focused');
            checkInputValue(this);
        });
    });

    function checkInputValue(input) {
        if (input.value.trim() !== '') {
            input.classList.add('has-value');
        } else {
            input.classList.remove('has-value');
        }
    }

    // --- Password Show/Hide Toggle Logic ---
    const passwordToggles = document.querySelectorAll('.y-c-password-toggle');

    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function () {
            // Find the input field within the same wrapper
            const passwordWrapper = this.closest('.y-l-password-wrapper');
            if (!passwordWrapper) return;

            // Check for both types of password inputs
            const passwordInput = passwordWrapper.querySelector('.y-c-form-input[type="password"], .y-c-form-input[type="text"]');
            if (!passwordInput) return;

            // Check the current type
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle the icon class (Assumes Font Awesome classes)
            if (type === 'text') {
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    });

    // --- FORM SUBMISSION HANDLING ---
    // Handle login form submission
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            
            if (window.validationUtils && window.validationUtils.validateContainer(loginForm)) {
                // Simulate successful login and redirect to home
                window.location.href = '../home/layout.html';
            }
        });
    }

    // Handle signup form submission
    const signupForm = document.getElementById('signup-form');
    if (signupForm) {
        signupForm.addEventListener('submit', function (e) {
            e.preventDefault();

            if (window.validationUtils && window.validationUtils.validateContainer(signupForm)) {
                // Simulate successful signup and redirect to login
                window.location.href = '../login/layout.html';
            }
        });
    }

    // --- FORGET PASSWORD POPUP ---
    const forgetForm = document.getElementById('forgetPasswordForm');
    const successPopup = document.getElementById('successPopup');
    const closePopupBtn = document.getElementById('closePopupBtn');

    if (forgetForm && successPopup && closePopupBtn) {
        forgetForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Stop the form from submitting normally

            if (window.validationUtils && window.validationUtils.validateContainer(forgetForm)) {
                // Show the popup - UPDATED to use 'active'
                successPopup.classList.add('active');
            }
        });

        closePopupBtn.addEventListener('click', function () {
            // Hide the popup - UPDATED to use 'active'
            successPopup.classList.remove('active');

            // Redirect to login page
            window.location.href = '../login/layout.html';
        });
    }
});
