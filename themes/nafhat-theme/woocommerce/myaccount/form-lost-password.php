<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * @package Nafhat Theme
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_lost_password_form');
?>

<div class="auth-page">
    <div class="container">
        <div class="auth-form-wrapper lost-password-form">
            <div class="form-icon">
                <i class="fas fa-lock"></i>
            </div>
            
            <h2><?php esc_html_e('نسيت كلمة المرور؟', 'nafhat'); ?></h2>
            
            <p class="form-description">
                <?php esc_html_e('أدخل بريدك الإلكتروني أو اسم المستخدم وسنرسل لك رابطاً لإعادة تعيين كلمة المرور.', 'nafhat'); ?>
            </p>

            <form method="post" class="woocommerce-ResetPassword lost_reset_password">

                <div class="form-group">
                    <label for="user_login"><?php esc_html_e('البريد الإلكتروني أو اسم المستخدم', 'nafhat'); ?>&nbsp;<span class="required">*</span></label>
                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="username" placeholder="example@gmail.com" required />
                </div>

                <?php do_action('woocommerce_lostpassword_form'); ?>

                <input type="hidden" name="wc_reset_password" value="true" />
                
                <button type="submit" class="woocommerce-Button button btn btn-primary" value="<?php esc_attr_e('إرسال رابط إعادة التعيين', 'nafhat'); ?>">
                    <?php esc_html_e('إرسال رابط إعادة التعيين', 'nafhat'); ?>
                </button>

                <?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>

            </form>

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

<?php
do_action('woocommerce_after_lost_password_form');
