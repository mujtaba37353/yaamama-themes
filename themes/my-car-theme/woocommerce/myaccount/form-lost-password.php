<?php
/**
 * Lost password form - Custom Template
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_lost_password_form');
?>

<div class="y-l-myaccount-login y-l-single-form">
    <div class="y-l-container">
        
        <!-- Page Header -->
        <div class="y-l-myaccount-header">
            <div class="y-c-myaccount-icon y-c-icon-forgot">
                <i class="fa-solid fa-key"></i>
            </div>
            <h1 class="y-c-myaccount-title">نسيت كلمة المرور؟</h1>
            <p class="y-c-myaccount-subtitle">أدخل بريدك الإلكتروني وسنرسل لك رابط لإعادة تعيين كلمة المرور</p>
        </div>

        <div class="y-l-single-form-wrapper">
            
            <div class="y-c-auth-card">
                
                <form method="post" class="woocommerce-ResetPassword lost_reset_password y-c-auth-form">

                    <div class="y-c-form-group">
                        <label for="user_login" class="y-c-form-label">
                            البريد الإلكتروني أو اسم المستخدم
                            <span class="y-c-required">*</span>
                        </label>
                        <div class="y-c-input-wrapper">
                            <i class="fa-solid fa-envelope"></i>
                            <input class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" type="text" name="user_login" id="user_login" autocomplete="username" placeholder="أدخل بريدك الإلكتروني" required />
                        </div>
                    </div>

                    <div class="clear"></div>

                    <?php do_action('woocommerce_lostpassword_form'); ?>

                    <input type="hidden" name="wc_reset_password" value="true" />
                    
                    <button type="submit" class="woocommerce-Button button y-c-submit-btn" value="<?php esc_attr_e('Reset password', 'woocommerce'); ?>">
                        <span>إرسال رابط إعادة التعيين</span>
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>

                    <?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>

                </form>

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

<?php do_action('woocommerce_after_lost_password_form'); ?>
