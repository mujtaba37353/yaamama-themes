<?php
/**
 * Checkout shipping information form
 *
 * @package TechnoSouqTheme
 */

defined('ABSPATH') || exit;
?>
<div class="woocommerce-shipping-fields">
    <?php if (true === WC()->cart->needs_shipping_address()) : ?>
        <h3 id="ship-to-different-address">
            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                <input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked(apply_filters('woocommerce_ship_to_different_address_checked', 'shipping' === get_option('woocommerce_ship_to_destination') ? 1 : 0), 1); ?> type="checkbox" name="ship_to_different_address" value="1" />
                <span><?php esc_html_e('الشحن إلى عنوان مختلف؟', 'techno-souq-theme'); ?></span>
            </label>
        </h3>
        
        <div class="shipping_address">
            <?php do_action('woocommerce_before_checkout_shipping_form', $checkout); ?>
            
            <div class="woocommerce-shipping-fields__field-wrapper">
                <?php
                $fields = $checkout->get_checkout_fields('shipping');
                
                // Name fields in a row
                if (isset($fields['shipping_first_name']) || isset($fields['shipping_last_name'])) {
                    ?>
                    <div class="y-c-form-row" data-y="name-row">
                        <?php if (isset($fields['shipping_first_name'])) : ?>
                            <div class="y-c-form-field" data-y="first-name-field">
                                <?php
                                woocommerce_form_field('shipping_first_name', array(
                                    'type'        => 'text',
                                    'label'       => '',
                                    'required'    => true,
                                    'class'       => array('y-c-form-input'),
                                    'placeholder' => __('الاسم الأول', 'techno-souq-theme'),
                                ), $checkout->get_value('shipping_first_name'));
                                ?>
                            </div>
                            <?php unset($fields['shipping_first_name']); ?>
                        <?php endif; ?>
                        
                        <?php if (isset($fields['shipping_last_name'])) : ?>
                            <div class="y-c-form-field" data-y="last-name-field">
                                <?php
                                woocommerce_form_field('shipping_last_name', array(
                                    'type'        => 'text',
                                    'label'       => '',
                                    'required'    => true,
                                    'class'       => array('y-c-form-input'),
                                    'placeholder' => __('اسم العائلة', 'techno-souq-theme'),
                                ), $checkout->get_value('shipping_last_name'));
                                ?>
                            </div>
                            <?php unset($fields['shipping_last_name']); ?>
                        <?php endif; ?>
                    </div>
                    <?php
                }
                
                // Country field - convert to text input
                if (isset($fields['shipping_country'])) {
                    $country_value = $checkout->get_value('shipping_country');
                    $country_display = $country_value ? WC()->countries->countries[$country_value] : 'السعودية';
                    if (!$country_display) {
                        $country_display = 'السعودية';
                    }
                    ?>
                    <div class="y-c-form-field" data-y="country-field">
                        <input type="hidden" name="shipping_country" id="shipping_country" value="<?php echo esc_attr($country_value ? $country_value : 'SA'); ?>" class="address-field update_totals_on_change">
                        <input type="text" class="y-c-form-input y-c-country-input" id="shipping_country_display" placeholder="<?php esc_attr_e('الدولة', 'techno-souq-theme'); ?>" value="<?php echo esc_attr($country_display); ?>" autocomplete="country">
                    </div>
                    <?php
                    unset($fields['shipping_country']);
                }
                
                // Street address
                if (isset($fields['shipping_address_1'])) {
                    ?>
                    <div class="y-c-form-field" data-y="street-address-field">
                        <?php
                        woocommerce_form_field('shipping_address_1', array(
                            'type'        => 'text',
                            'label'       => '',
                            'required'    => true,
                            'class'       => array('y-c-form-input', 'address-field'),
                            'placeholder' => __('عنوان الشارع / رقم المنزل', 'techno-souq-theme'),
                        ), $checkout->get_value('shipping_address_1'));
                        ?>
                    </div>
                    <?php
                    unset($fields['shipping_address_1']);
                }
                
                // Address line 2 - removed
                if (isset($fields['shipping_address_2'])) {
                    unset($fields['shipping_address_2']);
                }
                
                // City field only (state removed)
                if (isset($fields['shipping_city'])) {
                    ?>
                    <div class="y-c-form-field" data-y="city-field">
                        <?php
                        woocommerce_form_field('shipping_city', array(
                            'type'        => 'text',
                            'label'       => '',
                            'required'    => true,
                            'class'       => array('y-c-form-input', 'address-field'),
                            'placeholder' => __('المدينة', 'techno-souq-theme'),
                        ), $checkout->get_value('shipping_city'));
                        ?>
                    </div>
                    <?php
                    unset($fields['shipping_city']);
                }
                
                // State field - removed
                if (isset($fields['shipping_state'])) {
                    unset($fields['shipping_state']);
                }
                
                // Postal code field - removed
                if (isset($fields['shipping_postcode'])) {
                    unset($fields['shipping_postcode']);
                }
                
                // Any remaining fields
                foreach ($fields as $key => $field) {
                    woocommerce_form_field($key, $field, $checkout->get_value($key));
                }
                ?>
            </div>
            
            <?php do_action('woocommerce_after_checkout_shipping_form', $checkout); ?>
        </div>
    <?php endif; ?>
</div>

<div class="woocommerce-additional-fields">
    <?php do_action('woocommerce_before_order_notes', $checkout); ?>
    
    <?php if (apply_filters('woocommerce_enable_order_notes_field', 'yes' === get_option('woocommerce_enable_order_comments', 'yes'))) : ?>
        <?php if (!WC()->cart->needs_shipping() || wc_ship_to_billing_address_only()) : ?>
            <h3><?php esc_html_e('معلومات إضافية', 'techno-souq-theme'); ?></h3>
        <?php endif; ?>
        
        <div class="woocommerce-additional-fields__field-wrapper">
            <?php foreach ($checkout->get_checkout_fields('order') as $key => $field) : ?>
                <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <?php do_action('woocommerce_after_order_notes', $checkout); ?>
</div>