document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('input[name="payment_method"]');
    const options = document.querySelectorAll('.y-c-payment-option');
    const cardDetailsForm = document.getElementById('card-details-form');
    const methodsContainer = document.querySelector('.y-c-payment-methods');

    /* تحقق: كل طريقة دفع تعرض اسم عربي في .y-c-radio-label */
    if (methodsContainer && options.length) {
        var allHaveLabel = true;
        options.forEach(function (opt) {
            var labelEl = opt.querySelector('.y-c-radio-label');
            var text = labelEl ? (labelEl.textContent || '').trim() : '';
            if (!text) {
                allHaveLabel = false;
            }
        });
        methodsContainer.setAttribute('data-y-payment-labels-ok', allHaveLabel ? 'true' : 'false');
    }

    var selectedOption = document.querySelector('.y-c-payment-option.selected');
    if (cardDetailsForm && selectedOption && selectedOption.getAttribute('data-is-card') === '1') {
        cardDetailsForm.classList.add('active');
    }

    radios.forEach(radio => {
        radio.addEventListener('change', function () {
            options.forEach(opt => opt.classList.remove('selected'));
            const option = this.closest('.y-c-payment-option');
            if (option) option.classList.add('selected');
            if (cardDetailsForm && option) {
                cardDetailsForm.classList.toggle('active', option.getAttribute('data-is-card') === '1');
            }
        });
    });

    const createAccountToggle = document.getElementById('create-account-toggle');
    const createAccountContent = document.getElementById('create-account-password');
    const createAccountCheckbox = document.getElementById('createaccount');
    const passwordInput = createAccountContent ? createAccountContent.querySelector('input') : null;
    const toggleIcon = createAccountContent ? createAccountContent.querySelector('.y-c-password-toggle') : null;

    if (createAccountToggle && createAccountContent) {
        createAccountToggle.addEventListener('click', function () {
            const isVisible = createAccountContent.style.display !== 'none';
            createAccountContent.style.display = isVisible ? 'none' : 'block';
            this.classList.toggle('active', !isVisible);
            if (isVisible && passwordInput) {
                passwordInput.value = '';
            }
            if (createAccountCheckbox) {
                createAccountCheckbox.checked = !isVisible;
            }
        });
    }

    if (toggleIcon && passwordInput) {
        toggleIcon.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }
});
