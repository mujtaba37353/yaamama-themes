
document.addEventListener('DOMContentLoaded', function () {
    // Get the email from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const email = urlParams.get('email');

    if (email) {
        const confirmationMessage = document.querySelector('.y-l-pass-confirmed p:first-child');
        if (confirmationMessage) {
            const checkIcon = confirmationMessage.querySelector('.fa-circle-check');
            const newText = document.createTextNode(` لقد تم إرسال رسالة إلى البريد الإلكتروني ${email} لإعادة تعيين كلمة المرور.`);

            confirmationMessage.innerHTML = '';
            confirmationMessage.appendChild(checkIcon);
            confirmationMessage.appendChild(newText);
        }
    }
});