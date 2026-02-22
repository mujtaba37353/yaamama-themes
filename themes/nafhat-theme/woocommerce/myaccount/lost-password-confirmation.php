<?php
/**
 * Lost password confirmation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/lost-password-confirmation.php.
 *
 * @package Nafhat Theme
 */

defined('ABSPATH') || exit;
?>

<div class="auth-page">
    <div class="container">
        <div class="auth-form-wrapper lost-password-confirmation">
            <div class="form-icon success">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            
            <h2><?php esc_html_e('تم إرسال رابط إعادة التعيين', 'nafhat'); ?></h2>
            
            <p class="form-description">
                <?php esc_html_e('تم إرسال بريد إلكتروني لإعادة تعيين كلمة المرور إلى عنوان البريد الإلكتروني المسجل في حسابك.', 'nafhat'); ?>
            </p>
            
            <p class="form-note">
                <?php esc_html_e('قد يستغرق وصول البريد الإلكتروني بضع دقائق. يرجى التحقق من مجلد البريد العشوائي (Spam) إذا لم تجده في صندوق الوارد.', 'nafhat'); ?>
            </p>

            <div class="separator"><span><?php esc_html_e('أو', 'nafhat'); ?></span></div>

            <div class="switch-auth">
                <p>
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">
                        <i class="fas fa-arrow-right"></i>
                        <?php esc_html_e('العودة لتسجيل الدخول', 'nafhat'); ?>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
