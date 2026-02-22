<?php
/**
 * Checkout Form – Stationary theme override
 *
 * Two-column layout:
 *   Left  → billing/shipping fields + payment methods + place order
 *   Right → order review (product cards + totals)
 *
 * @package stationary-theme
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'يجب تسجيل الدخول لإتمام الطلب.', 'stationary-theme' ) ) );
	return;
}
?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

	<!-- Left column: billing + shipping + payment methods -->
	<div class="checkout-fields" id="customer_details">
		<h2><?php esc_html_e( 'معلومات الشحن', 'stationary-theme' ); ?></h2>

		<?php do_action( 'woocommerce_checkout_billing' ); ?>

		<?php do_action( 'woocommerce_checkout_shipping' ); ?>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

		<?php
		if ( WC()->cart->needs_payment() ) {
			woocommerce_checkout_payment();
		} else {
			?>
			<div class="form-row place-order">
				<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
				<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="btn secondary-button fw" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( apply_filters( 'woocommerce_order_button_text', __( 'إتمام الطلب', 'stationary-theme' ) ) ) . '" data-value="' . esc_attr( apply_filters( 'woocommerce_order_button_text', __( 'إتمام الطلب', 'stationary-theme' ) ) ) . '">' . esc_html( apply_filters( 'woocommerce_order_button_text', __( 'إتمام الطلب', 'stationary-theme' ) ) ) . '</button>' ); // @codingStandardsIgnoreLine ?>
			</div>
			<?php
		}
		?>
	</div>

	<!-- Right column: order review -->
	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order checkout-review">
		<?php woocommerce_order_review(); ?>
	</div>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
