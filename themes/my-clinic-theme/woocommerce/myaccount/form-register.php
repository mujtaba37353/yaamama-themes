<?php
/**
 * Registration Form
 *
 * @package MyClinic
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Check if registration is enabled
if ('yes' !== get_option('woocommerce_enable_myaccount_registration')) {
    wp_safe_redirect(wc_get_page_permalink('myaccount'));
    exit;
}

// Enqueue auth styles
$theme_version = wp_get_theme()->get('Version');
$theme_uri = get_template_directory_uri();
wp_enqueue_style('my-clinic-auth', $theme_uri . '/assets/css/components/auth.css', array(
    'my-clinic-header',
    'my-clinic-footer',
    'my-clinic-buttons'
), $theme_version);

// Enqueue register validation script if exists
if (file_exists(get_template_directory() . '/assets/js/register-validation.js')) {
    wp_enqueue_script('my-clinic-register-validation', $theme_uri . '/assets/js/register-validation.js', array('jquery'), $theme_version, true);
}

do_action('woocommerce_before_customer_register_form');
?>

<div class="y-l-auth-container" data-y="auth-container">
    <div class="y-c-auth-column y-c-auth-column--single" data-y="register-column">
        <h2 class="y-c-auth-title" data-y="register-title"><?php esc_html_e('إنشاء حساب', 'my-clinic'); ?></h2>

        <?php
        // Display WooCommerce notices (errors and success messages)
        // Use standard WooCommerce notice display - this is the recommended way
        if (function_exists('wc_print_notices')) {
            wc_print_notices();
        } elseif (function_exists('woocommerce_output_all_notices')) {
            woocommerce_output_all_notices();
        }
        
        // Also manually get and display notices if standard methods didn't work
        if (function_exists('wc_get_notices')) {
            $all_notices = wc_get_notices();
            if (!empty($all_notices)) {
                echo '<div class="woocommerce-notices-wrapper">';
                foreach ($all_notices as $notice_type => $notices) {
                    if (!empty($notices) && is_array($notices)) {
                        echo '<ul class="woocommerce-' . esc_attr($notice_type) . '">';
                        foreach ($notices as $notice) {
                            $notice_text = '';
                            if (is_array($notice)) {
                                $notice_text = isset($notice['notice']) ? $notice['notice'] : (isset($notice['message']) ? $notice['message'] : '');
                            } else {
                                $notice_text = $notice;
                            }
                            if (!empty($notice_text)) {
                                echo '<li>' . wp_kses_post($notice_text) . '</li>';
                            }
                        }
                        echo '</ul>';
                    }
                }
                echo '</div>';
            }
        }
        ?>

        <form method="post" class="woocommerce-form woocommerce-form-register register y-c-auth-form" <?php do_action('woocommerce_register_form_tag'); ?> data-y="register-form">
            <?php do_action('woocommerce_register_form_start'); ?>
            
            <?php
            // Get stored form data from session if available
            $stored_email = '';
            $stored_phone = '';
            if (function_exists('WC') && WC()->session) {
                $form_data = WC()->session->get('registration_form_data');
                if ($form_data && is_array($form_data)) {
                    $stored_email = isset($form_data['email']) ? $form_data['email'] : '';
                    $stored_phone = isset($form_data['phone']) ? $form_data['phone'] : '';
                    // Clear session data after use
                    WC()->session->__unset('registration_form_data');
                }
            }
            // Fallback to POST data if session data not available
            if (empty($stored_email) && !empty($_POST['email'])) {
                $stored_email = $_POST['email'];
            }
            if (empty($stored_phone) && !empty($_POST['phone'])) {
                $stored_phone = $_POST['phone'];
            }
            ?>

            <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
                <div class="y-c-form-field" data-y="reg-username-field">
                    <label for="reg_username" class="y-c-form-label">
                        <?php esc_html_e('اسم المستخدم', 'my-clinic'); ?>
                        <span class="y-c-required-mark">*</span>
                    </label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" />
                </div>
            <?php endif; ?>

            <div class="y-c-form-field" data-y="reg-email-field">
                <label for="reg_email" class="y-c-form-label">
                    <?php esc_html_e('البريد الإلكتروني', 'my-clinic'); ?>
                    <span class="y-c-required-mark">*</span>
                </label>
                <input type="email" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="email" id="reg_email" autocomplete="email" value="<?php echo esc_attr($stored_email); ?>" required aria-required="true" />
            </div>

            <div class="y-c-form-field" data-y="reg-phone-field">
                <label for="reg_phone" class="y-c-form-label">
                    <?php esc_html_e('رقم الجوال', 'my-clinic'); ?>
                    <span class="y-c-required-mark">*</span>
                </label>
                <input type="tel" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="phone" id="reg_phone" pattern="^05\d{8}$" placeholder="05xxxxxxxx" maxlength="10" value="<?php echo esc_attr($stored_phone); ?>" required aria-required="true" />
                <small style="display: block; margin-top: var(--y-spacing-xs); color: var(--y-color-text-secondary); font-size: var(--y-font-size-sm);">
                    <?php esc_html_e('يجب أن يبدأ بـ 05 ويتبعه 8 أرقام', 'my-clinic'); ?>
                </small>
                <span class="y-c-phone-error" id="phone-format-error" style="display: none; color: var(--y-color-error); font-size: var(--y-font-size-sm); margin-top: var(--y-spacing-xs);">
                    <?php esc_html_e('رقم الجوال يجب أن يبدأ بـ 05 ويتبعه 8 أرقام', 'my-clinic'); ?>
                </span>
            </div>

            <div class="y-c-form-field" data-y="reg-gender-field">
                <label class="y-c-form-label">
                    <?php esc_html_e('النوع', 'my-clinic'); ?>
                    <span class="y-c-required-mark">*</span>
                </label>
                <div class="y-c-gender-selection" style="display: flex; gap: var(--y-spacing-md); margin-top: var(--y-spacing-xs);">
                    <label style="display: flex; align-items: center; gap: var(--y-spacing-xs); cursor: pointer;">
                        <input type="radio" name="gender" value="male" id="reg_gender_male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'male') ? 'checked' : ''; ?> required aria-required="true" />
                        <span><?php esc_html_e('ذكر', 'my-clinic'); ?></span>
                    </label>
                    <label style="display: flex; align-items: center; gap: var(--y-spacing-xs); cursor: pointer;">
                        <input type="radio" name="gender" value="female" id="reg_gender_female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'female') ? 'checked' : ''; ?> required aria-required="true" />
                        <span><?php esc_html_e('أنثى', 'my-clinic'); ?></span>
                    </label>
                </div>
            </div>

            <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>
                <div class="y-c-form-field" data-y="reg-password-field">
                    <label for="reg_password" class="y-c-form-label">
                        <?php esc_html_e('كلمة المرور', 'my-clinic'); ?>
                        <span class="y-c-required-mark">*</span>
                    </label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
                </div>
                
                <div class="y-c-form-field" data-y="reg-password-confirm-field">
                    <label for="reg_password_confirm" class="y-c-form-label">
                        <?php esc_html_e('تأكيد كلمة المرور', 'my-clinic'); ?>
                        <span class="y-c-required-mark">*</span>
                    </label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="password_confirm" id="reg_password_confirm" autocomplete="new-password" required aria-required="true" />
                    <span class="y-c-password-error" id="password-match-error" style="display: none; color: var(--y-color-error); font-size: var(--y-font-size-sm); margin-top: var(--y-spacing-xs);">
                        <?php esc_html_e('كلمتا المرور غير متطابقتين', 'my-clinic'); ?>
                    </span>
                </div>
            <?php else : ?>
                <p class="y-c-auth-info" data-y="reg-info">
                    <?php esc_html_e('سيتم إرسال رابط لإنشاء كلمة مرور جديدة إلى بريدك الإلكتروني.', 'my-clinic'); ?>
                </p>
            <?php endif; ?>

            <?php do_action('woocommerce_register_form'); ?>
            
            <?php
            // Add privacy policy text in Arabic
            $privacy_policy_url = get_privacy_policy_url();
            if ($privacy_policy_url) {
                echo '<p class="y-c-auth-info" data-y="privacy-policy">';
                printf(
                    esc_html__('سيتم استخدام بياناتك الشخصية لدعم تجربتك في هذا الموقع، وإدارة الوصول إلى حسابك، ولأغراض أخرى موضحة في %s.', 'my-clinic'),
                    '<a href="' . esc_url($privacy_policy_url) . '" target="_blank">' . esc_html__('سياسة الخصوصية', 'my-clinic') . '</a>'
                );
                echo '</p>';
            }
            ?>

            <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>

            <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit y-c-btn y-c-btn-primary y-c-btn-full" name="register" value="<?php esc_attr_e('إنشاء حساب', 'my-clinic'); ?>" data-y="register-submit">
                <?php esc_html_e('إنشاء حساب', 'my-clinic'); ?>
            </button>

            <p class="y-c-auth-link y-c-auth-link--login" data-y="login-link">
                <?php esc_html_e('لديك حساب بالفعل؟', 'my-clinic'); ?> 
                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"><?php esc_html_e('تسجيل الدخول', 'my-clinic'); ?></a>
            </p>

            <?php do_action('woocommerce_register_form_end'); ?>
        </form>
    </div>
</div>

<?php do_action('woocommerce_after_customer_register_form'); ?>
