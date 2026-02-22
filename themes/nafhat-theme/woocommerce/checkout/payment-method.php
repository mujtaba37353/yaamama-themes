<?php
/**
 * Payment Method
 *
 * @package Nafhat Theme
 */

defined('ABSPATH') || exit;

$gateway_id = $gateway->id;
$gateway_title = $gateway->get_title();
$gateway_description = $gateway->get_description();
$gateway_icon = $gateway->get_icon();
?>

<li class="wc_payment_method payment_method_<?php echo esc_attr($gateway_id); ?>">
    <label for="payment_method_<?php echo esc_attr($gateway_id); ?>" class="payment-method-label">
        <input id="payment_method_<?php echo esc_attr($gateway_id); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr($gateway_id); ?>" <?php checked($gateway->chosen, true); ?> data-order_button_text="<?php echo esc_attr($gateway->order_button_text); ?>" />
        <span class="payment-method-info">
            <span class="payment-method-title"><?php echo wp_kses_post($gateway_title); ?></span>
            <?php if ($gateway_icon) : ?>
                <span class="payment-method-icon"><?php echo $gateway_icon; ?></span>
            <?php endif; ?>
        </span>
        <span class="payment-method-check">
            <i class="fas fa-check-circle"></i>
        </span>
    </label>
    <?php if ($gateway->has_fields() || $gateway_description) : ?>
        <div class="payment_box payment_method_<?php echo esc_attr($gateway_id); ?>" <?php if (!$gateway->chosen) : ?>style="display:none;"<?php endif; ?>>
            <?php if ($gateway->has_fields()) : ?>
                <?php $gateway->payment_fields(); ?>
            <?php else : ?>
                <p><?php echo wp_kses_post($gateway_description); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</li>
