/**
 * Register Form Password Validation
 * 
 * @package TechnoSouqTheme
 */

document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.querySelector('.woocommerce-form-register');
    const passwordField = document.getElementById('reg_password');
    const passwordConfirmField = document.getElementById('reg_password_confirm');
    const passwordError = document.getElementById('password-match-error');
    const submitButton = document.querySelector('.woocommerce-form-register__submit');

    if (!registerForm || !passwordField || !passwordConfirmField || !passwordError) {
        return; // Exit if elements don't exist
    }

    /**
     * Validate password match
     */
    function validatePasswordMatch() {
        const password = passwordField.value;
        const passwordConfirm = passwordConfirmField.value;

        if (passwordConfirm.length > 0 && password !== passwordConfirm) {
            // Show error
            passwordError.style.display = 'block';
            passwordConfirmField.setCustomValidity('كلمتا المرور غير متطابقتين');
            passwordConfirmField.classList.add('y-c-input-error');
            return false;
        } else {
            // Hide error
            passwordError.style.display = 'none';
            passwordConfirmField.setCustomValidity('');
            passwordConfirmField.classList.remove('y-c-input-error');
            return true;
        }
    }

    /**
     * Real-time validation on input
     */
    if (passwordField) {
        passwordField.addEventListener('input', function() {
            if (passwordConfirmField.value.length > 0) {
                validatePasswordMatch();
            }
        });
    }

    if (passwordConfirmField) {
        passwordConfirmField.addEventListener('input', function() {
            validatePasswordMatch();
        });

        passwordConfirmField.addEventListener('blur', function() {
            validatePasswordMatch();
        });
    }

    /**
     * Prevent form submission if passwords don't match
     */
    registerForm.addEventListener('submit', function(e) {
        if (!validatePasswordMatch()) {
            e.preventDefault();
            e.stopPropagation();
            
            // Show error message
            passwordError.style.display = 'block';
            passwordConfirmField.focus();
            
            // Scroll to error
            passwordConfirmField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            return false;
        }
    });
});
