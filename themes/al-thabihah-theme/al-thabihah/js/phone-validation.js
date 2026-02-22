/**
 * Phone validation for forget password
 */
document.addEventListener('DOMContentLoaded', function () {
    const phoneInput = document.getElementById('phone');
    const form = document.querySelector('[data-y="forget-password-form"]');

    if (!phoneInput || !form) return;

    // Format the phone number as user types
    phoneInput.addEventListener('input', function () {
        // Strip all non-numeric characters
        let cleaned = this.value.replace(/\D/g, '');

        // Ensure it starts with 5
        if (cleaned.length > 0 && cleaned[0] !== '5') {
            cleaned = '5' + cleaned.substring(1);
        }

        // Limit to 9 digits (as per KSA mobile format)
        cleaned = cleaned.substring(0, 9);

        // Update the input value
        this.value = cleaned;
    });

    // Validate before submission
    form.addEventListener('submit', function (e) {
        const phone = phoneInput.value;

        // Check if the phone number is valid (starting with 5 and has 9 digits)
        const isValid = /^5\d{8}$/.test(phone);

        if (!isValid) {
            e.preventDefault();
            alert('الرجاء إدخال رقم هاتف سعودي صالح يبدأ بـ 5 متبوعًا بـ 8 أرقام');
            phoneInput.focus();
        }
    });
});