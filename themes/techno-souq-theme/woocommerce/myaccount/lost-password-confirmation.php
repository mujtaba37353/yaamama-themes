<?php
/**
 * Lost password confirmation text.
 *
 * @package TechnoSouqTheme
 */

defined('ABSPATH') || exit;

// Enqueue auth styles
$theme_version = wp_get_theme()->get('Version');
$techno_souq_path = get_template_directory_uri() . '/techno-souq';
wp_enqueue_style('techno-souq-auth', $techno_souq_path . '/templates/auth/y-auth.css', array(
    'techno-souq-header',
    'techno-souq-footer',
    'techno-souq-forms',
    'techno-souq-buttons'
), $theme_version);

do_action('woocommerce_before_lost_password_confirmation_message');
?>

<div class="y-l-auth-container" data-y="auth-container">
    <div class="y-c-auth-column y-c-auth-column--single y-c-password-reset-confirmation" data-y="password-reset-confirmation">
        <div class="y-c-password-reset-box" data-y="password-reset-box">
            <div class="y-c-password-reset-icon" data-y="password-reset-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <h2 class="y-c-password-reset-title" data-y="password-reset-title">
                <?php esc_html_e('تم إرسال بريد إعادة تعيين كلمة المرور', 'techno-souq-theme'); ?>
            </h2>
            <p class="y-c-password-reset-message" data-y="password-reset-message">
                <?php echo esc_html(apply_filters('woocommerce_lost_password_confirmation_message', esc_html__('تم إرسال بريد إلكتروني لإعادة تعيين كلمة المرور إلى عنوان البريد الإلكتروني المسجل في حسابك، ولكن قد يستغرق عدة دقائق حتى يظهر في صندوق الوارد الخاص بك. يرجى الانتظار 10 دقائق على الأقل قبل محاولة إعادة التعيين مرة أخرى.', 'techno-souq-theme'))); ?>
            </p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="y-c-btn y-c-btn-primary y-c-btn-full y-c-password-reset-home-btn" data-y="password-reset-home-btn">
                <?php esc_html_e('اذهب الى الرئيسية', 'techno-souq-theme'); ?>
            </a>
        </div>
    </div>
</div>

<?php do_action('woocommerce_after_lost_password_confirmation_message'); ?>
