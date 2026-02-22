<?php
/**
 * Checkout Payment – Stationary theme override
 *
 * Renders payment gateways as .radio-group items and the place-order button.
 * Root element must keep id="payment" class="woocommerce-checkout-payment"
 * for WooCommerce AJAX to locate and replace on update_checkout.
 *
 * @package stationary-theme
 * @version 8.1.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>

<div id="payment" class="woocommerce-checkout-payment" data-block-name="woocommerce/checkout-payment-block">

	<?php if ( WC()->cart->needs_payment() ) : ?>
		<div id="payment-method-group">
			<h2><?php esc_html_e( 'طريقة الدفع', 'stationary-theme' ); ?></h2>

			<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					?>
					<label class="radio-group" for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
						<input
							type="radio"
							name="payment_method"
							id="payment_method_<?php echo esc_attr( $gateway->id ); ?>"
							class="input-radio"
							value="<?php echo esc_attr( $gateway->id ); ?>"
							<?php checked( $gateway->chosen, true ); ?>
							data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>"
						>
						<?php echo wp_kses_post( $gateway->get_title() ); ?>
						<?php echo $gateway->get_icon(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</label>

					<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
						<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php if ( ! $gateway->chosen ) : ?>style="display:none;"<?php endif; ?>>
							<?php $gateway->payment_fields(); ?>
						</div>
					<?php endif; ?>
					<?php
				}
			} else {
				echo '<p class="woocommerce-notice woocommerce-notice--info">';
				echo wp_kses_post( apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'لا توجد طرق دفع متاحة.', 'stationary-theme' ) : esc_html__( 'الرجاء ملء بيانات الفوترة أولاً.', 'stationary-theme' ) ) );
				echo '</p>';
			}
			?>
		</div>
	<?php endif; ?>

	<div class="form-row place-order">
		<noscript>
			<?php esc_html_e( 'يجب تفعيل JavaScript لإتمام الطلب. الرجاء تفعيله في متصفحك.', 'stationary-theme' ); ?>
		</noscript>

		<?php wc_get_template( 'checkout/terms.php' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

		<?php echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput
			'woocommerce_order_button_html',
			'<button type="submit" class="btn secondary-button fw" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>'
		); ?>

		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
	</div>
</div>

<?php
if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
