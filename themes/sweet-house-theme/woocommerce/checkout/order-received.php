<?php
/**
 * "Order received" message — Sweet House design.
 *
 * @package Sweet_House_Theme
 * @var WC_Order|false $order
 */

defined( 'ABSPATH' ) || exit;

$message = apply_filters(
	'woocommerce_thankyou_order_received_text',
	esc_html__( 'تم استلام طلبك بنجاح. سنرسل لك تفاصيل الطلب والدفعة عبر البريد الإلكتروني.', 'sweet-house-theme' ),
	$order
);
?>
<p class="thank-you-message"><?php echo wp_kses_post( $message ); ?></p>
