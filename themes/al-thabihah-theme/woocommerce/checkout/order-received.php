<?php
/**
 * "Order received" message – شكراً، تم استلام طلبك
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.8.0
 *
 * @var WC_Order|false $order
 */

defined('ABSPATH') || exit;
?>

<p class="y-c-order-received-message">
    <?php
    $message = apply_filters(
        'woocommerce_thankyou_order_received_text',
        esc_html__('شكراً لك! تم استلام طلبك بنجاح وسيتم التواصل معك قريباً.', 'woocommerce'),
        $order
    );
    echo $message; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    ?>
</p>
