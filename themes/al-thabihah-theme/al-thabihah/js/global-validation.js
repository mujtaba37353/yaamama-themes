// Al Thabihah/js/global-validation.js

(function () {

    // --- Helpers ---
    function showError(input, message) {
        let errorSpan = input.parentNode.querySelector('.y-c-validation-error');
        if (!errorSpan) {
            errorSpan = document.createElement('span');
            errorSpan.className = 'y-c-validation-error';
            input.parentNode.appendChild(errorSpan);
        }
        errorSpan.textContent = message;
        errorSpan.classList.add('active');
        input.classList.add('y-c-input-error');
        input.setCustomValidity(message);
    }

    function clearError(input) {
        const errorSpan = input.parentNode.querySelector('.y-c-validation-error');
        if (errorSpan) {
            errorSpan.classList.remove('active');
        }
        input.classList.remove('y-c-input-error');
        input.setCustomValidity('');
    }

    function validateInput(input) {
        const val = input.value.trim();
        let isValid = true;

        // 1. Required Check
        if (input.hasAttribute('required') && !val) {
            showError(input, 'هذا الحقل مطلوب');
            return false;
        }

        // 2. Specific Validations based on Data Attributes or Type

        // --- Full Name ---
        if (input.matches('[data-y="full-name"]')) {
            if (val.length < 3) {
                showError(input, 'الاسم قصير جداً');
                isValid = false;
            } else if (val.split(' ').length < 2) {
                // Optional: require at least two names? simplified for now
                // showError(input, 'الرجاء إدخال الاسم الثلاثي');
                clearError(input);
            } else {
                clearError(input);
            }
        }

        // --- Address ---
        else if (input.matches('[data-y="address-input"]')) {
            if (val.length < 5) {
                showError(input, 'العنوان قصير جداً');
                isValid = false;
            } else {
                clearError(input);
            }
        }

        // --- Phone Validation ---
        else if (input.matches('input[type="tel"], [data-y="phone-input"], #phone')) {
            if (val.length > 0) {
                if (!val.startsWith('05')) {
                    showError(input, 'رقم الجوال يجب أن يبدأ بـ 05');
                    isValid = false;
                } else if (val.length < 10) {
                    showError(input, 'رقم الجوال يجب أن يتكون من 10 أرقام');
                    isValid = false;
                } else {
                    clearError(input);
                }
            } else if (input.hasAttribute('required')) {
                showError(input, 'هذا الحقل مطلوب');
                isValid = false;
            } else {
                clearError(input);
            }
        }

        // --- Email Validation ---
        else if (input.matches('input[type="email"], [data-y="email-input"]')) {
            if (val.length > 0) {
                const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (/[\u0600-\u06FF]/.test(val)) {
                    showError(input, 'البريد الإلكتروني يجب أن يكون باللغة الإنجليزية');
                    isValid = false;
                } else if (!emailPattern.test(val)) {
                    showError(input, 'الرجاء إدخال بريد إلكتروني صحيح');
                    isValid = false;
                } else {
                    clearError(input);
                }
            } else if (input.hasAttribute('required')) {
                showError(input, 'هذا الحقل مطلوب');
                isValid = false;
            } else {
                clearError(input);
            }
        }

        // --- Card Name ---
        else if (input.matches('[data-y="card-name"]')) {
            if (val.length < 3) {
                showError(input, 'الاسم على البطاقة قصير جداً');
                isValid = false;
            } else {
                clearError(input);
            }
        }

        // --- Card Number ---
        else if (input.matches('[data-y="card-number"]')) {
            const rawVal = val.replace(/\s/g, '');
            if (rawVal.length < 15 || rawVal.length > 19) {
                showError(input, 'رقم البطاقة غير صحيح');
                isValid = false;
            } else {
                clearError(input);
            }
        }

        // --- Card Month ---
        else if (input.matches('[data-y="card-month"]')) {
            const num = parseInt(val, 10);
            if (isNaN(num) || num < 1 || num > 12) {
                showError(input, 'شهر غير صحيح');
                isValid = false;
            } else {
                clearError(input);
            }
        }

        // --- Card Year ---
        else if (input.matches('[data-y="card-year"]')) {
            const num = parseInt(val, 10);
            if (isNaN(num) || val.length !== 2) { // Expecting 2 digits
                showError(input, 'سنة غير صحيحة');
                isValid = false;
            } else {
                clearError(input);
            }
        }

        // --- CVV ---
        else if (input.matches('[data-y="card-cvv"]')) {
            if (val.length < 3) {
                showError(input, 'رمز CVV غير صحيح');
                isValid = false;
            } else {
                clearError(input);
            }
        }

        // --- Password (Complexity Check) ---
        else if (input.matches('[data-y="password-input"]')) {
            if (val.length > 0) {
                const hasUpper = /[A-Z]/.test(val);
                const hasLower = /[a-z]/.test(val);
                const hasNumber = /\d/.test(val);
                const hasSpecial = /[^A-Za-z0-9]/.test(val);
                const isValidLength = val.length >= 8;

                if (!isValidLength || !hasUpper || !hasLower || !hasNumber || !hasSpecial) {
                    showError(input, 'يجب أن تحتوي كلمة المرور على 8 أحرف على الأقل، وحرف كبير، وحرف صغير، ورقم، ورمز خاص');
                    isValid = false;
                } else {
                    clearError(input);
                }

                // Re-validate confirm password if it exists
                const form = input.closest('form');
                if (form) {
                    const confirmInput = form.querySelector('[data-y="confirm-password-input"]');
                    if (confirmInput && confirmInput.value) {
                        validateInput(confirmInput);
                    }
                }
            }
        }

        // --- Login Password (Simple Check) ---
        else if (input.matches('[data-y="login-password-input"]')) {
            if (val.length > 0) {
                if (val.length < 8) {
                    showError(input, 'كلمة المرور يجب أن تكون 8 أحرف على الأقل');
                    isValid = false;
                } else {
                    clearError(input);
                }
            }
        }

        // --- Confirm Password ---
        else if (input.matches('[data-y="confirm-password-input"]')) {
            const form = input.closest('form');
            if (form) {
                const passwordInput = form.querySelector('[data-y="password-input"]');
                if (passwordInput && val !== passwordInput.value) {
                    showError(input, 'كلمات المرور غير متطابقة');
                    isValid = false;
                } else {
                    clearError(input);
                }
            }
        }

        return isValid;
    }

    // --- Public API ---
    window.validationUtils = {
        validateInput: validateInput,
        validateContainer: function (container) {
            const inputs = container.querySelectorAll('input, select, textarea');
            let allValid = true;
            inputs.forEach(input => {
                // Only validate visible inputs or inputs that don't have a hidden parent
                // (Quick check: offsetParent is null if hidden)
                if (input.offsetParent !== null) {
                    if (!validateInput(input)) {
                        allValid = false;
                    }
                }
            });
            return allValid;
        }
    };

    // --- Input Formatting & Real-time Listeners ---
    document.addEventListener('DOMContentLoaded', function () {

        document.body.addEventListener('input', function (e) {
            const input = e.target;

            // Clear error on input if it becomes valid or user is typing
            // (Optional: debounce validation here if strictly needed, but clearing is good UX)
            // clearError(input); 
            // Note: We don't fully validate on every keystroke to avoid annoyance, 
            // but we do formatting.

            // --- Phone Formatting ---
            if (input.matches('input[type="tel"], [data-y="phone-input"]')) {
                let val = input.value.replace(/\D/g, '');
                if (val.length > 10) val = val.slice(0, 10);
                if (val.length === 1 && val === '5') val = '05';
                input.value = val;
            }

            // --- Card Number Formatting ---
            if (input.matches('[data-y="card-number"]')) {
                let val = input.value.replace(/\D/g, '');
                let formatted = '';
                for (let i = 0; i < val.length; i++) {
                    if (i > 0 && i % 4 === 0) formatted += ' ';
                    formatted += val[i];
                }
                input.value = formatted;
            }

            // --- Card Month Formatting ---
            if (input.matches('[data-y="card-month"]')) {
                let val = input.value.replace(/\D/g, '');
                if (val.length > 0) {
                    const num = parseInt(val, 10);
                    if (num > 12) val = '12';
                    if (val === '00') val = '01';
                }
                input.value = val;
            }

            // --- Date/CVV Formatting ---
            if (input.matches('[data-y="card-year"], [data-y="card-cvv"]')) {
                input.value = input.value.replace(/\D/g, '');
            }

            // --- Email Formatting (block arabic) ---
            if (input.matches('input[type="email"], [data-y="email-input"]')) {
                if (/[\u0600-\u06FF]/.test(input.value)) {
                    input.value = input.value.replace(/[\u0600-\u06FF]/g, '');
                }
            }
        });

        // --- Validate on Blur ---
        document.body.addEventListener('focusout', function (e) {
            const input = e.target;

            // Pad month on blur
            if (input.matches('[data-y="card-month"]')) {
                let val = input.value;
                if (val.length > 0) {
                    const num = parseInt(val, 10);
                    if (num === 0) {
                        input.value = '01';
                    } else if (val.length === 1) {
                        input.value = '0' + val;
                    }
                }
            }

            validateInput(e.target);
        });

    });

})();
