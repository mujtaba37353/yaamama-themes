<?php
/**
 * Edit address form
 *
 * @package TechnoSouqTheme
 */

defined('ABSPATH') || exit;

$page_title = ('billing' === $load_address) ? esc_html__('عنوان الفاتورة', 'techno-souq-theme') : esc_html__('عنوان الشحن', 'techno-souq-theme');

do_action('woocommerce_before_edit_account_address_form');
?>

<?php if (!$load_address) : ?>
    <?php wc_get_template('myaccount/my-address.php'); ?>
<?php else : ?>

    <form class="y-c-account-details-form-container" method="post" novalidate data-y='address-form' id="address-form">
        <h3 class="y-c-section-title"><?php echo apply_filters('woocommerce_my_account_edit_address_title', $page_title, $load_address); ?></h3>

        <div class="woocommerce-address-fields">
            <?php do_action("woocommerce_before_edit_address_form_{$load_address}"); ?>

            <div class="woocommerce-address-fields__field-wrapper">
                <?php
                foreach ($address as $key => $field) {
                    // Skip fields we don't want
                    if (in_array($key, array('billing_address_2', 'billing_state', 'billing_postcode', 'shipping_address_2', 'shipping_state', 'shipping_postcode'))) {
                        continue;
                    }
                    
                    // Custom field rendering
                    $field_value = wc_get_post_data_by_key($key, $field['value']);
                    
                    if (strpos($key, 'first_name') !== false || strpos($key, 'last_name') !== false) {
                        // First name and Last name in a row
                        if (strpos($key, 'first_name') !== false) {
                            echo '<div class="y-c-form-row">';
                        }
                        ?>
                        <div class="y-c-form-field">
                            <label class="y-c-form-label" for="<?php echo esc_attr($key); ?>">
                                <?php echo esc_html($field['label']); ?>
                                <?php if (!empty($field['required'])) : ?>
                                    <span class="y-c-required-mark">*</span>
                                <?php endif; ?>
                            </label>
                            <input type="text" 
                                   class="y-c-form-input" 
                                   id="<?php echo esc_attr($key); ?>" 
                                   name="<?php echo esc_attr($key); ?>" 
                                   value="<?php echo esc_attr($field_value); ?>" 
                                   <?php echo !empty($field['required']) ? 'required' : ''; ?>
                                   data-y='<?php echo esc_attr(str_replace('_', '-', $key)); ?>-input'>
                        </div>
                        <?php
                        if (strpos($key, 'last_name') !== false) {
                            echo '</div>';
                        }
                    } elseif (strpos($key, 'country') !== false) {
                        // Country as text input
                        $country_code = $field_value ?: 'SA';
                        $country_name = $country_code ? WC()->countries->countries[$country_code] : __('السعودية', 'techno-souq-theme');
                        ?>
                        <div class="y-c-form-field" data-y="country-field">
                            <label class="y-c-form-label" for="<?php echo esc_attr($key); ?>_display">
                                <?php echo esc_html($field['label']); ?>
                                <span class="y-c-required-mark">*</span>
                            </label>
                            <input type="text" 
                                   class="y-c-form-input y-c-country-input" 
                                   id="<?php echo esc_attr($key); ?>_display" 
                                   placeholder="<?php esc_attr_e('الدولة', 'techno-souq-theme'); ?>" 
                                   value="<?php echo esc_attr($country_name); ?>" 
                                   required>
                            <input type="hidden" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($country_code); ?>">
                        </div>
                        <?php
                    } elseif (strpos($key, 'address_1') !== false) {
                        // Street address
                        ?>
                        <div class="y-c-form-field" data-y="street-address-field">
                            <label class="y-c-form-label" for="<?php echo esc_attr($key); ?>">
                                <?php echo esc_html($field['label']); ?>
                                <span class="y-c-required-mark">*</span>
                            </label>
                            <input type="text" 
                                   class="y-c-form-input" 
                                   id="<?php echo esc_attr($key); ?>" 
                                   name="<?php echo esc_attr($key); ?>" 
                                   placeholder="<?php esc_attr_e('عنوان الشارع / رقم المنزل', 'techno-souq-theme'); ?>" 
                                   value="<?php echo esc_attr($field_value); ?>" 
                                   required
                                   style="margin-bottom:10px;"
                                   data-y='street-address-input'>
                        </div>
                        <?php
                    } elseif (strpos($key, 'city') !== false) {
                        // City
                        ?>
                        <div class="y-c-form-field" data-y="city-field">
                            <label class="y-c-form-label" for="<?php echo esc_attr($key); ?>">
                                <?php echo esc_html($field['label']); ?>
                                <span class="y-c-required-mark">*</span>
                            </label>
                            <input type="text" 
                                   class="y-c-form-input" 
                                   id="<?php echo esc_attr($key); ?>" 
                                   name="<?php echo esc_attr($key); ?>" 
                                   placeholder="<?php esc_attr_e('المدينة', 'techno-souq-theme'); ?>" 
                                   value="<?php echo esc_attr($field_value); ?>" 
                                   required
                                   data-y='city-input'>
                        </div>
                        <?php
                    } elseif (strpos($key, 'phone') !== false || strpos($key, 'email') !== false) {
                        // Phone or Email
                        ?>
                        <div class="y-c-form-field" data-y="<?php echo esc_attr(str_replace('_', '-', $key)); ?>-field">
                            <label class="y-c-form-label" for="<?php echo esc_attr($key); ?>">
                                <?php echo esc_html($field['label']); ?>
                                <?php if (!empty($field['required'])) : ?>
                                    <span class="y-c-required-mark">*</span>
                                <?php endif; ?>
                            </label>
                            <input type="<?php echo (strpos($key, 'email') !== false) ? 'email' : 'tel'; ?>" 
                                   class="y-c-form-input" 
                                   id="<?php echo esc_attr($key); ?>" 
                                   name="<?php echo esc_attr($key); ?>" 
                                   value="<?php echo esc_attr($field_value); ?>" 
                                   <?php echo !empty($field['required']) ? 'required' : ''; ?>
                                   data-y='<?php echo esc_attr(str_replace('_', '-', $key)); ?>-input'>
                        </div>
                        <?php
                    } else {
                        // Default WooCommerce field rendering
                        woocommerce_form_field($key, $field, $field_value);
                    }
                }
                ?>
            </div>

            <?php do_action("woocommerce_after_edit_address_form_{$load_address}"); ?>

            <div class="y-c-form-actions" data-y='address-submit-container'>
                <button type="submit" class="y-c-btn y-c-btn-primary y-c-btn-outline" name="save_address" value="<?php esc_attr_e('حفظ العنوان', 'techno-souq-theme'); ?>" data-y='address-submit-btn'>
                    <?php esc_html_e('حفظ العنوان', 'techno-souq-theme'); ?>
                </button>
                <?php wp_nonce_field('woocommerce-edit_address', 'woocommerce-edit-address-nonce'); ?>
                <input type="hidden" name="action" value="edit_address" />
                <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address')); ?>" class="y-c-btn y-c-btn-cancel" style="margin-right: 10px;">
                    <?php esc_html_e('إلغاء', 'techno-souq-theme'); ?>
                </a>
            </div>
        </div>
    </form>

<?php endif; ?>

<?php do_action('woocommerce_after_edit_account_address_form'); ?>