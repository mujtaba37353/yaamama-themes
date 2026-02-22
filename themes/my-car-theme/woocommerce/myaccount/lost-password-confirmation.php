<?php
/**
 * Lost password confirmation - Custom Template
 *
 * @package MyCarTheme
 * @version 3.9.0
 */

defined('ABSPATH') || exit;
?>

<div class="y-l-myaccount-login y-l-single-form">
    <div class="y-l-container">
        
        <!-- Page Header -->
        <div class="y-l-myaccount-header">
            <div class="y-c-myaccount-icon y-c-icon-success">
                <i class="fa-solid fa-envelope-circle-check"></i>
            </div>
            <h1 class="y-c-myaccount-title">تم إرسال الرابط بنجاح</h1>
            <p class="y-c-myaccount-subtitle">تحقق من بريدك الإلكتروني</p>
        </div>

        <div class="y-l-single-form-wrapper">
            
            <div class="y-c-auth-card">
                
                <div class="y-c-success-message">
                    <div class="y-c-success-icon">
                        <i class="fa-solid fa-paper-plane"></i>
                    </div>
                    <h3>تم إرسال رابط إعادة تعيين كلمة المرور</h3>
                    <p>
                        تم إرسال بريد إلكتروني يحتوي على رابط إعادة تعيين كلمة المرور إلى عنوان البريد الإلكتروني المسجل في حسابك.
                    </p>
                    <p class="y-c-note">
                        <i class="fa-solid fa-clock"></i>
                        قد يستغرق وصول البريد بضع دقائق. يرجى الانتظار 10 دقائق على الأقل قبل محاولة إعادة الإرسال.
                    </p>
                    <p class="y-c-spam-note">
                        <i class="fa-solid fa-exclamation-triangle"></i>
                        إذا لم تجد الرسالة، تحقق من مجلد البريد غير المرغوب فيه (Spam).
                    </p>
                </div>

                <!-- Link to Login -->
                <div class="y-c-auth-footer">
                    <p>تذكرت كلمة المرور؟</p>
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="y-c-auth-link">
                        <span>العودة لتسجيل الدخول</span>
                        <i class="fa-solid fa-right-to-bracket"></i>
                    </a>
                </div>

            </div>

        </div>

    </div>
</div>
