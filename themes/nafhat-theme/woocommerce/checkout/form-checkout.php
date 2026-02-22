<?php
/**
 * Checkout Form
 *
 * @package Nafhat Theme
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('يجب عليك تسجيل الدخول لإتمام عملية الشراء.', 'nafhat')));
    return;
}
?>

<div class="checkout-page">
    <div class="container">
        <div class="checkout-header">
            <h1><?php esc_html_e('إتمام الطلب', 'nafhat'); ?></h1>
        </div>

        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

            <div class="checkout-layout">
                <!-- Order Summary - Mobile (Top) -->
                <div class="checkout-order-summary mobile-only">
                    <div class="order-summary-toggle">
                        <span class="toggle-text">
                            <i class="fas fa-shopping-cart"></i>
                            <?php esc_html_e('عرض ملخص الطلب', 'nafhat'); ?>
                            <span class="item-count">(<?php echo WC()->cart->get_cart_contents_count(); ?>)</span>
                        </span>
                        <span class="toggle-total"><?php wc_cart_totals_order_total_html(); ?></span>
                        <i class="fas fa-chevron-down toggle-icon"></i>
                    </div>
                    <div class="order-summary-content" style="display: none;">
                        <?php wc_get_template('checkout/review-order.php', array('checkout' => $checkout)); ?>
                    </div>
                </div>

                <!-- Billing Details -->
                <div class="checkout-billing">
                    <div class="checkout-section">
                        <h3><?php esc_html_e('معلومات الشحن', 'nafhat'); ?></h3>
                        
                        <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                        <div class="checkout-fields">
                            <!-- Full Name -->
                            <div class="form-group form-group-wide">
                                <label for="billing_full_name"><?php esc_html_e('الاسم الكامل', 'nafhat'); ?> <span class="required">*</span></label>
                                <input type="text" class="input-text" name="billing_full_name" id="billing_full_name" placeholder="<?php esc_attr_e('أدخل اسمك الكامل', 'nafhat'); ?>" value="<?php echo esc_attr($checkout->get_value('billing_full_name')); ?>" required />
                            </div>

                            <!-- Country -->
                            <div class="form-group">
                                <label for="billing_country"><?php esc_html_e('الدولة', 'nafhat'); ?> <span class="required">*</span></label>
                                <input type="text" class="input-text" name="billing_country" id="billing_country" value="السعودية" required />
                            </div>

                            <!-- City -->
                            <div class="form-group">
                                <label for="billing_city"><?php esc_html_e('المدينة', 'nafhat'); ?> <span class="required">*</span></label>
                                <input type="text" class="input-text" name="billing_city" id="billing_city" placeholder="<?php esc_attr_e('أدخل المدينة', 'nafhat'); ?>" value="<?php echo esc_attr($checkout->get_value('billing_city')); ?>" required />
                            </div>

                            <!-- Address Description -->
                            <div class="form-group form-group-wide">
                                <label for="billing_address_1"><?php esc_html_e('وصف العنوان', 'nafhat'); ?> <span class="required">*</span></label>
                                <textarea class="input-text" name="billing_address_1" id="billing_address_1" placeholder="<?php esc_attr_e('مثال: حي النرجس، شارع الملك فهد، بجوار مسجد...', 'nafhat'); ?>" rows="3" required><?php echo esc_textarea($checkout->get_value('billing_address_1')); ?></textarea>
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label for="billing_email"><?php esc_html_e('البريد الإلكتروني', 'nafhat'); ?> <span class="required">*</span></label>
                                <input type="email" class="input-text" name="billing_email" id="billing_email" placeholder="example@gmail.com" value="<?php echo esc_attr($checkout->get_value('billing_email')); ?>" required />
                            </div>

                            <!-- Phone -->
                            <div class="form-group">
                                <label for="billing_phone"><?php esc_html_e('رقم الجوال', 'nafhat'); ?> <span class="required">*</span></label>
                                <input type="tel" class="input-text" name="billing_phone" id="billing_phone" placeholder="05xxxxxxxx" value="<?php echo esc_attr($checkout->get_value('billing_phone')); ?>" required />
                            </div>

                            <?php if (!is_user_logged_in() && $checkout->is_registration_enabled()) : ?>
                            <!-- Create Account -->
                            <div class="form-group form-group-wide create-account-section">
                                <div class="create-account-toggle">
                                    <label class="checkbox-label">
                                        <input type="checkbox" class="input-checkbox" name="createaccount" id="createaccount" value="1" <?php checked(true, $checkout->get_value('createaccount')); ?> />
                                        <span><?php esc_html_e('إنشاء حساب جديد', 'nafhat'); ?></span>
                                    </label>
                                    <p class="create-account-hint"><?php esc_html_e('أنشئ حساباً لتتبع طلباتك بسهولة', 'nafhat'); ?></p>
                                </div>
                                
                                <div class="create-account-fields" style="display: none;">
                                    <div class="form-group">
                                        <label for="account_password"><?php esc_html_e('كلمة المرور', 'nafhat'); ?> <span class="required">*</span></label>
                                        <input type="password" class="input-text" name="account_password" id="account_password" placeholder="********" />
                                        <span class="password-strength-meter" id="checkout-password-strength"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="account_password_confirm"><?php esc_html_e('تأكيد كلمة المرور', 'nafhat'); ?> <span class="required">*</span></label>
                                        <input type="password" class="input-text" id="account_password_confirm" placeholder="********" />
                                        <span class="password-match-message" id="checkout-password-match"></span>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php do_action('woocommerce_checkout_after_customer_details'); ?>
                    </div>

                    <!-- Payment Methods -->
                    <div class="checkout-section">
                        <h3><?php esc_html_e('طريقة الدفع', 'nafhat'); ?></h3>
                        
                        <div class="payment-methods">
                            <?php if (WC()->cart->needs_payment()) : ?>
                                <ul class="wc_payment_methods payment_methods methods">
                                    <?php
                                    if (!empty($available_gateways = WC()->payment_gateways->get_available_payment_gateways())) {
                                        foreach ($available_gateways as $gateway) {
                                            wc_get_template('checkout/payment-method.php', array('gateway' => $gateway));
                                        }
                                    } else {
                                        echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . esc_html__('عذراً، لا توجد طرق دفع متاحة حالياً.', 'nafhat') . '</li>';
                                    }
                                    ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="checkout-section">
                        <h3><?php esc_html_e('ملاحظات الطلب', 'nafhat'); ?> <span class="optional">(<?php esc_html_e('اختياري', 'nafhat'); ?>)</span></h3>
                        <div class="form-group form-group-wide">
                            <textarea class="input-text" name="order_comments" id="order_comments" placeholder="<?php esc_attr_e('ملاحظات حول طلبك، مثل ملاحظات خاصة بالتوصيل...', 'nafhat'); ?>" rows="3"><?php echo esc_textarea($checkout->get_value('order_comments')); ?></textarea>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <div class="checkout-section place-order-section">
                        <?php do_action('woocommerce_review_order_before_submit'); ?>

                        <?php echo apply_filters('woocommerce_order_button_html', '<button type="submit" class="button alt btn btn-primary btn-checkout" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr__('إتمام الطلب', 'nafhat') . '" data-value="' . esc_attr__('إتمام الطلب', 'nafhat') . '"><i class="fas fa-check-circle"></i> ' . esc_html__('إتمام الطلب', 'nafhat') . '</button>'); ?>

                        <?php do_action('woocommerce_review_order_after_submit'); ?>

                        <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
                    </div>
                </div>

                <!-- Order Summary - Desktop (Sidebar) -->
                <div class="checkout-order-summary desktop-only">
                    <div class="order-summary-sticky">
                        <h3><?php esc_html_e('ملخص الطلب', 'nafhat'); ?></h3>
                        <?php wc_get_template('checkout/review-order.php', array('checkout' => $checkout)); ?>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle order summary on mobile
    const summaryToggle = document.querySelector('.order-summary-toggle');
    const summaryContent = document.querySelector('.order-summary-content');
    const toggleIcon = document.querySelector('.toggle-icon');
    
    if (summaryToggle && summaryContent) {
        summaryToggle.addEventListener('click', function() {
            const isHidden = summaryContent.style.display === 'none';
            summaryContent.style.display = isHidden ? 'block' : 'none';
            toggleIcon.classList.toggle('rotated', isHidden);
        });
    }

    // Toggle create account fields
    const createAccountCheckbox = document.getElementById('createaccount');
    const createAccountFields = document.querySelector('.create-account-fields');
    
    if (createAccountCheckbox && createAccountFields) {
        createAccountCheckbox.addEventListener('change', function() {
            createAccountFields.style.display = this.checked ? 'block' : 'none';
        });
    }

    // Password validation for account creation
    const accountPassword = document.getElementById('account_password');
    const accountPasswordConfirm = document.getElementById('account_password_confirm');
    const passwordStrength = document.getElementById('checkout-password-strength');
    const passwordMatch = document.getElementById('checkout-password-match');

    if (accountPassword && accountPasswordConfirm) {
        accountPassword.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let message = '';
            let color = '';
            
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            
            if (password.length === 0) {
                message = '';
            } else if (strength < 3) {
                message = 'ضعيفة';
                color = '#dc3545';
            } else if (strength < 5) {
                message = 'متوسطة';
                color = '#ffc107';
            } else {
                message = 'قوية';
                color = '#28a745';
            }
            
            passwordStrength.textContent = message;
            passwordStrength.style.color = color;
            
            if (accountPasswordConfirm.value) {
                checkPasswordMatch();
            }
        });

        accountPasswordConfirm.addEventListener('input', checkPasswordMatch);

        function checkPasswordMatch() {
            const pass1 = accountPassword.value;
            const pass2 = accountPasswordConfirm.value;
            
            if (pass2.length === 0) {
                passwordMatch.textContent = '';
                return;
            }
            
            if (pass1 === pass2) {
                passwordMatch.textContent = 'كلمة المرور متطابقة ✓';
                passwordMatch.style.color = '#28a745';
            } else {
                passwordMatch.textContent = 'كلمة المرور غير متطابقة';
                passwordMatch.style.color = '#dc3545';
            }
        }
    }
});
</script>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
