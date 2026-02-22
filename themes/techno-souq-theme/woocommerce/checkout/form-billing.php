<?php
/**
 * Checkout billing information form
 *
 * @package TechnoSouqTheme
 */

defined('ABSPATH') || exit;
?>
<div class="woocommerce-billing-fields">
    <?php do_action('woocommerce_before_checkout_billing_form', $checkout); ?>
    
    <div class="woocommerce-billing-fields__field-wrapper">
        <?php
        $fields = $checkout->get_checkout_fields('billing');
        
        // Email field first
        if (isset($fields['billing_email'])) {
            $email_field = $fields['billing_email'];
            ?>
            <div class="y-c-form-field" data-y="shipping-email-field">
                <label class="y-c-form-label" for="billing_email" data-y="shipping-email-label">
                    <?php esc_html_e('سوف نستخدم هذا البريد الإلكتروني لإرسال التفاصيل والتحديثات إليك حول طلبك.', 'techno-souq-theme'); ?>
                    <span class="y-c-required-mark">*</span>
                </label>
                <?php
                woocommerce_form_field('billing_email', array(
                    'type'        => 'email',
                    'label'       => '',
                    'required'    => true,
                    'class'       => array('y-c-form-input'),
                    'placeholder' => '******@gmail.com',
                ), $checkout->get_value('billing_email'));
                ?>
            </div>
            <?php
            unset($fields['billing_email']);
        }
        ?>
        
        <div class="y-c-form-field" data-y="billing-address-field">
            <label class="y-c-form-label" data-y="billing-address-label">
                <?php esc_html_e('أدخل عنوان الفاتورة الذي يتطابق مع طريقة الدفع الخاصة بك.', 'techno-souq-theme'); ?>
                <span class="y-c-required-mark">*</span>
            </label>
        </div>
        
        <?php
        // Name fields in a row
        if (isset($fields['billing_first_name']) || isset($fields['billing_last_name'])) {
            ?>
            <div class="y-c-form-row" data-y="name-row">
                <?php if (isset($fields['billing_first_name'])) : ?>
                    <div class="y-c-form-field" data-y="first-name-field">
                        <?php
                        woocommerce_form_field('billing_first_name', array(
                            'type'        => 'text',
                            'label'       => '',
                            'required'    => true,
                            'class'       => array('y-c-form-input'),
                            'placeholder' => __('الاسم الأول', 'techno-souq-theme'),
                        ), $checkout->get_value('billing_first_name'));
                        ?>
                    </div>
                    <?php unset($fields['billing_first_name']); ?>
                <?php endif; ?>
                
                <?php if (isset($fields['billing_last_name'])) : ?>
                    <div class="y-c-form-field" data-y="last-name-field">
                        <?php
                        woocommerce_form_field('billing_last_name', array(
                            'type'        => 'text',
                            'label'       => '',
                            'required'    => true,
                            'class'       => array('y-c-form-input'),
                            'placeholder' => __('اسم العائلة', 'techno-souq-theme'),
                        ), $checkout->get_value('billing_last_name'));
                        ?>
                    </div>
                    <?php unset($fields['billing_last_name']); ?>
                <?php endif; ?>
            </div>
            <?php
        }
        
        // Country field - convert to text input
        if (isset($fields['billing_country'])) {
            $country_value = $checkout->get_value('billing_country');
            $country_display = $country_value ? WC()->countries->countries[$country_value] : 'السعودية';
            if (!$country_display) {
                $country_display = 'السعودية';
            }
            ?>
            <div class="y-c-form-field" data-y="country-field">
                <input type="hidden" name="billing_country" id="billing_country" value="<?php echo esc_attr($country_value ? $country_value : 'SA'); ?>" class="address-field update_totals_on_change">
                <input type="text" class="y-c-form-input y-c-country-input" id="billing_country_display" placeholder="<?php esc_attr_e('الدولة', 'techno-souq-theme'); ?>" value="<?php echo esc_attr($country_display); ?>" autocomplete="country">
            </div>
            <?php
            unset($fields['billing_country']);
        }
        
        // Street address
        if (isset($fields['billing_address_1'])) {
            ?>
            <div class="y-c-form-field" data-y="street-address-field">
                <?php
                woocommerce_form_field('billing_address_1', array(
                    'type'        => 'text',
                    'label'       => '',
                    'required'    => true,
                    'class'       => array('y-c-form-input', 'address-field'),
                    'placeholder' => __('عنوان الشارع / رقم المنزل', 'techno-souq-theme'),
                ), $checkout->get_value('billing_address_1'));
                ?>
            </div>
            <?php
            unset($fields['billing_address_1']);
        }
        
        // Address line 2 (apartment) - removed
        if (isset($fields['billing_address_2'])) {
            unset($fields['billing_address_2']);
        }
        
        // City field only (state removed)
        if (isset($fields['billing_city'])) {
            ?>
            <div class="y-c-form-field" data-y="city-field">
                <?php
                woocommerce_form_field('billing_city', array(
                    'type'        => 'text',
                    'label'       => '',
                    'required'    => true,
                    'class'       => array('y-c-form-input', 'address-field'),
                    'placeholder' => __('المدينة', 'techno-souq-theme'),
                ), $checkout->get_value('billing_city'));
                ?>
            </div>
            <?php
            unset($fields['billing_city']);
        }
        
        // State field - removed
        if (isset($fields['billing_state'])) {
            unset($fields['billing_state']);
        }
        
        // Phone field only (postal code removed)
        if (isset($fields['billing_phone'])) {
            ?>
            <div class="y-c-form-field" data-y="phone-field">
                <?php
                woocommerce_form_field('billing_phone', array(
                    'type'        => 'tel',
                    'label'       => '',
                    'required'    => false,
                    'class'       => array('y-c-form-input'),
                    'placeholder' => __('رقم الجوال', 'techno-souq-theme'),
                ), $checkout->get_value('billing_phone'));
                ?>
            </div>
            <?php
            unset($fields['billing_phone']);
        }
        
        // Postal code field - removed
        if (isset($fields['billing_postcode'])) {
            unset($fields['billing_postcode']);
        }
        
        // Any remaining fields
        foreach ($fields as $key => $field) {
            woocommerce_form_field($key, $field, $checkout->get_value($key));
        }
        ?>
    </div>
    
    <?php do_action('woocommerce_after_checkout_billing_form', $checkout); ?>
</div>

<?php if (!is_user_logged_in() && $checkout->is_registration_enabled()) : ?>
    <div class="woocommerce-account-fields">
        <?php if (!$checkout->is_registration_required()) : ?>
            <p class="form-row form-row-wide create-account">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                    <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked((true === $checkout->get_value('createaccount') || (true === apply_filters('woocommerce_create_account_default_checked', false))), true); ?> type="checkbox" name="createaccount" value="1" />
                    <span><?php esc_html_e('إنشاء حساب؟', 'techno-souq-theme'); ?></span>
                </label>
            </p>
        <?php endif; ?>
        
        <?php do_action('woocommerce_before_checkout_registration_form', $checkout); ?>
        
        <?php if ($checkout->get_checkout_fields('account')) : ?>
            <div class="create-account">
                <?php foreach ($checkout->get_checkout_fields('account') as $key => $field) : ?>
                    <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
                <?php endforeach; ?>
                <div class="clear"></div>
            </div>
        <?php endif; ?>
        
        <?php do_action('woocommerce_after_checkout_registration_form', $checkout); ?>
    </div>
<?php endif; ?>