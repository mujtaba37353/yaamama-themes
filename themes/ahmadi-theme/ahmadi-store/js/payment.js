document.addEventListener('DOMContentLoaded', function () {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const cardDetails = document.querySelector('.y-c-payment-card-details');
    const stcPayDetails = document.querySelector('.y-c-stc-pay-details');
    const cardOption = document.querySelector('.y-c-radio-option:nth-of-type(1)');
    const stcPayOption = document.querySelector('.y-c-radio-option:nth-of-type(2)');

    paymentMethods.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === 'online') {
                cardDetails.style.display = 'flex';
                stcPayDetails.style.display = 'none';
                cardOption.classList.add('y-c-active');
                stcPayOption.classList.remove('y-c-active');
            } else if (this.value === 'stc') {
                cardDetails.style.display = 'none';
                stcPayDetails.style.display = 'flex';
                stcPayOption.classList.add('y-c-active');
                cardOption.classList.remove('y-c-active');
            }
        });
    });

    // Set initial state based on the checked radio button
    const initialChecked = document.querySelector('input[name="payment_method"]:checked');
    if (initialChecked) {
        if (initialChecked.value === 'online') {
            cardDetails.style.display = 'flex';
        } else if (initialChecked.value === 'stc') {
            stcPayDetails.style.display = 'flex';
        }
    }

    // Coupon toggle functionality
    const couponToggle = document.querySelector('[data-y="coupon-toggle"]');
    const couponField = document.querySelector('[data-y="coupon-field"]');
    const couponIcon = document.querySelector('[data-y="coupon-icon"] i');

    if (couponToggle && couponField) {
        couponToggle.addEventListener('click', () => {
            const isVisible = couponField.style.display !== 'none';
            couponField.style.display = isVisible ? 'none' : 'block';
            couponIcon.className = isVisible ? 'fas fa-chevron-down' : 'fas fa-chevron-up';
        });
    }

    // Apply coupon button
    const applyCouponBtn = document.querySelector('[data-y="apply-coupon-btn"]');
    if (applyCouponBtn) {
        applyCouponBtn.addEventListener('click', () => {
            const couponInput = document.querySelector('[data-y="coupon-input"]');
            const couponCode = couponInput.value.trim();

            if (couponCode) {
                console.log('Applying coupon:', couponCode);
                // Add your coupon validation and application logic here
                alert('تم تطبيق القسيمة: ' + couponCode);
            } else {
                alert('الرجاء إدخال رمز القسيمة');
            }
        });
    }
});