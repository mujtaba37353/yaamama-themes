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

    const confirmPasswordInput = document.getElementById('confirm-password');
    const passwordInput = document.getElementById('password');

    if (confirmPasswordInput && passwordInput) {
        confirmPasswordInput.addEventListener('input', function () {
            if (this.value !== passwordInput.value && this.value !== '') {
                this.setCustomValidity('كلمات المرور غير متطابقة');
            } else {
                this.setCustomValidity('');
            }
        });

        passwordInput.addEventListener('input', function () {
            if (confirmPasswordInput.value !== this.value && confirmPasswordInput.value !== '') {
                confirmPasswordInput.setCustomValidity('كلمات المرور غير متطابقة');
            } else {
                confirmPasswordInput.setCustomValidity('');
            }
        });
    }
});