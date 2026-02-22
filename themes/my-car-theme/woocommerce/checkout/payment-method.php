<?php
/**
 * Payment Method - Custom Template
 *
 * @package MyCarTheme
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<li class="wc_payment_method payment_method_<?php echo esc_attr($gateway->id); ?> y-c-payment-method">
    <label class="y-c-payment-method-label" for="payment_method_<?php echo esc_attr($gateway->id); ?>">
        <input id="payment_method_<?php echo esc_attr($gateway->id); ?>" type="radio" class="input-radio y-c-payment-radio" name="payment_method" value="<?php echo esc_attr($gateway->id); ?>" <?php checked($gateway->chosen, true); ?> data-order_button_text="<?php echo esc_attr($gateway->order_button_text); ?>" />
        
        <span class="y-c-payment-method-content">
            <span class="y-c-payment-method-icon">
                <?php 
                // Custom icons based on payment method
                switch ($gateway->id) {
                    case 'bacs':
                        echo '<i class="fa-solid fa-building-columns"></i>';
                        break;
                    case 'cod':
                        echo '<i class="fa-solid fa-money-bill-wave"></i>';
                        break;
                    case 'paypal':
                        echo '<i class="fa-brands fa-paypal"></i>';
                        break;
                    case 'stripe':
                        echo '<i class="fa-brands fa-stripe"></i>';
                        break;
                    default:
                        echo '<i class="fa-solid fa-credit-card"></i>';
                }
                ?>
            </span>
            <span class="y-c-payment-method-info">
                <span class="y-c-payment-method-title"><?php echo $gateway->get_title(); ?></span>
                <?php if ($gateway->has_fields() || $gateway->get_description()) : ?>
                    <span class="y-c-payment-method-desc"><?php echo wp_kses_post($gateway->get_description()); ?></span>
                <?php endif; ?>
            </span>
        </span>
        
        <span class="y-c-payment-check">
            <i class="fa-solid fa-circle-check"></i>
        </span>
    </label>
    
    <?php if ($gateway->has_fields()) : ?>
        <div class="payment_box payment_method_<?php echo esc_attr($gateway->id); ?> y-c-payment-box" <?php if (!$gateway->chosen) : ?>style="display:none;"<?php endif; ?>>
            <?php $gateway->payment_fields(); ?>
        </div>
    <?php endif; ?>
</li>
