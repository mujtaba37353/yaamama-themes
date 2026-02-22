// Al Thabihah/js/contact-us.js

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[data-y="contact-form"]');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (window.validationUtils && window.validationUtils.validateContainer(form)) {
                // Determine if we are in productUtils context (though contact us might be standalone)
                // For now, simple success feedback
                
                // Replace form with success message or show alert
                const container = document.querySelector('[data-y="contact-form-container"]');
                if (container) {
                    container.innerHTML = `
                        <div class="y-c-success-message" style="text-align: center; padding: 40px;">
                            <i class="fas fa-check-circle" style="font-size: 48px; color: var(--y-color-success); margin-bottom: 20px;"></i>
                            <h2 style="margin-bottom: 10px;">تم استلام رسالتك بنجاح</h2>
                            <p>سنتواصل معك في أقرب وقت ممكن.</p>
                            <button class="y-c-outline-btn" style="margin-top: 20px;" onclick="window.location.reload()">إرسال رسالة أخرى</button>
                        </div>
                    `;
                } else {
                    alert('تم استلام رسالتك بنجاح');
                    form.reset();
                }
            }
        });
    }
});
