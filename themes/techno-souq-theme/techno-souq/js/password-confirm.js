document.getElementById('forgetPasswordForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const email = document.getElementById('email').value;

    if (email) {
        // Show success popup
        showSuccessPopup();
    }
});

function showSuccessPopup() {
    // Create overlay
    const overlay = document.createElement('div');
    overlay.className = 'y-c-popup-overlay';
    overlay.setAttribute('data-y', 'popup-overlay');

    // Create popup content
    overlay.innerHTML = `
                <div class="y-c-popup-content" data-y="popup-content">
                    <i class="fas fa-check-circle" data-y="popup-icon"></i>
                    <h2 data-y="popup-title">تم تعيين كلمة السر</h2>
                    <p class="y-c-popup-subtitle" data-y="popup-subtitle">لقد تم إرسال رسالة إلى البريد الإلكتروني لإعادة تعيين كلمة المرور.</p>
                    <p class="y-c-popup-description" data-y="popup-description">لقد تم إرسال رسالة إعادة تعيين كلمة المرور إلى عنوان البريد الإلكتروني الموجود في ملف حسابك. ولكن قد تستغرق عدة دقائق لتظهر في صندوق البريد الوارد لديك. يرجى الانتظار لمدة 10 دقائق على الأقل قبل محاولة إعادة تعيين آخر.</p>
                    <button class="y-c-basic-button" onclick="closePopup()" data-y="popup-close-btn">
                        حسناً
                    </button>
                </div>
            `;

    // Append to body
    document.body.appendChild(overlay);
}

// Function to close popup
function closePopup() {
    const overlay = document.querySelector('.y-c-popup-overlay');
    if (overlay) {
        overlay.remove();
    }
}