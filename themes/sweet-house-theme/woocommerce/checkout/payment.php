<?php
/**
 * Checkout Payment — Sweet House design.
 *
 * @package Sweet_House_Theme
 * @see design: sweet-house/components/payment/y-c-payment-form.html
 */

defined( 'ABSPATH' ) || exit;

if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>
<div id="payment" class="woocommerce-checkout-payment payment-container">
	<?php if ( WC()->cart && WC()->cart->needs_payment() ) : ?>
		<div class="payment-methods-section">
			<div class="payment-options">
				<?php
				if ( ! empty( $available_gateways ) ) {
					foreach ( $available_gateways as $gateway ) {
						wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
					}
				} else {
					echo '<li class="wc_payment_method">';
					wc_print_notice( apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ), 'notice' ); // phpcs:ignore
					echo '</li>';
				}
				?>
			</div>
		</div>
	<?php endif; ?>

	<?php
	// ملاحظات الطلب (بعد طرق الدفع حسب التصميم)
	$checkout = WC()->checkout();
	if ( $checkout && apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) {
		$order_fields = $checkout->get_checkout_fields( 'order' );
		if ( ! empty( $order_fields ) ) {
			do_action( 'woocommerce_before_order_notes', $checkout );
			echo '<div class="section order-notes-section">';
			foreach ( $order_fields as $key => $field ) {
				$field = apply_filters( 'sweet_house_checkout_field_args', $field, $key );
				$value = $checkout->get_value( $key );
				$ph    = isset( $field['placeholder'] ) ? $field['placeholder'] : __( 'أي ملاحظات إضافية...', 'sweet-house-theme' );
				echo '<div class="form-field-container textarea-container order-notes-field">';
				echo '<textarea name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" class="input-text order-notes-textarea" placeholder="' . esc_attr( $ph ) . '" rows="3">' . esc_textarea( $value ) . '</textarea>';
				echo '</div>';
			}
			echo '</div>';
			do_action( 'woocommerce_after_order_notes', $checkout );
		}
	}
	?>

	<div class="section terms-section">
		<?php wc_get_template( 'checkout/terms.php' ); ?>
	</div>

	<div class="form-row place-order form-actions">
		<noscript>
			<?php
			printf(
				esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order.', 'woocommerce' ),
				'<em>',
				'</em>'
			);
			?>
			<br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
		</noscript>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

		<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="back-to-cart"><i class="fa fa-arrow-right" aria-hidden="true"></i> <?php esc_html_e( 'العودة إلى سلة المشتريات', 'sweet-house-theme' ); ?></a>
		<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt btn-primary btn-auth" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // phpcs:ignore ?>

		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
	</div>
</div>
<?php
if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
