<?php
/**
 * "Order received" message.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/order-received.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.8.0
 *
 * @var WC_Order|false $order
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="thankyou-message">
	<div class="thankyou-icon">
		<i class="fas fa-check-circle"></i>
	</div>
	<p class="thankyou-text">
		<?php
		/**
		 * Filter the message shown after a checkout is complete.
		 *
		 * @since 2.2.0
		 *
		 * @param string         $message The message.
		 * @param WC_Order|false $order   The order created during checkout, or false if order data is not available.
		 */
		$message = apply_filters(
			'woocommerce_thankyou_order_received_text',
			esc_html__( 'شكراً لك! تم استلام طلبك بنجاح.', 'khutaa-theme' ),
			$order
		);

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $message;
		?>
	</p>
</div>
