fetch("../../components/payment/y-c-payment-form.html")
    .then((response) => response.text())
    .then((data) => {
        document.querySelector('[data-y="payment"]').innerHTML = data;
        initializePayment();
    });

function initializePayment() {
    const creditCardRadio = document.getElementById('payment-method-1');
    const stcPayRadio = document.getElementById('payment-method-2');
    const creditCardFields = document.getElementById('credit-card-fields');

    function handlePaymentSelection() {
        if (creditCardRadio.checked) {
            creditCardFields.style.display = 'flex';
        } else {
            creditCardFields.style.display = 'none';
        }
    }

    if (creditCardRadio) {
        creditCardRadio.addEventListener('change', handlePaymentSelection);
    }

    if (stcPayRadio) {
        stcPayRadio.addEventListener('change', handlePaymentSelection);
    }

    handlePaymentSelection();

    const cardNumberInput = document.getElementById('card-number');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\s/g, '');
            value = value.replace(/(.{4})/g, '$1 ');
            e.target.value = value.trim();
        });

        cardNumberInput.addEventListener('focus', function () {
            this.style.opacity = '1';
            this.style.background = '#f9f9f9';
        });

        cardNumberInput.addEventListener('blur', function () {
            if (!this.value) {
                this.style.opacity = '0';
                this.style.background = 'transparent';
            }
        });
    }

    const expiryInput = document.getElementById('expiry-date');
    if (expiryInput) {
        expiryInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + ' / ' + value.substring(2, 4);
            }
            e.target.value = value;
        });

        expiryInput.addEventListener('focus', function () {
            this.style.opacity = '1';
            this.style.background = '#f9f9f9';
        });

        expiryInput.addEventListener('blur', function () {
            if (!this.value) {
                this.style.opacity = '0';
                this.style.background = 'transparent';
            }
        });
    }

    const allInputs = document.querySelectorAll('.hidden-input, .hidden-textarea');
    allInputs.forEach(input => {
        input.addEventListener('focus', function () {
            this.style.opacity = '1';
            this.style.background = '#f9f9f9';
        });

        input.addEventListener('blur', function () {
            if (!this.value) {
                this.style.opacity = '0';
                this.style.background = 'transparent';
            }
        });
    });

    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('05')) {
                e.target.value = value.substring(0, 10);
            }
        });
    }

    const paymentBtn = document.getElementById('payment-btn');
    const modal = document.getElementById('payment-success-modal');
    const returnHomeBtn = document.getElementById('return-home-btn');

    if (paymentBtn) {
        paymentBtn.addEventListener('click', function (e) {
            e.preventDefault();

            paymentBtn.textContent = 'جاري الدفع...';
            paymentBtn.disabled = true;

            setTimeout(() => {
                showSuccessModal();
                paymentBtn.textContent = 'الدفع';
                paymentBtn.disabled = false;
            }, 1500);
        });
    }

    if (returnHomeBtn) {
        returnHomeBtn.addEventListener('click', function () {
            window.location.href = '../../templates/home/layout.html';
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