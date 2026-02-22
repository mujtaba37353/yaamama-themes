<?php
/**
 * Cart totals — Sweet House design.
 *
 * @package Sweet_House_Theme
 * @see     design: sweet-house/components/cards/y-c-cart-summary-card.html
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="cart-summary cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<div class="order-summary-box">
		<h3 class="summary-box-title"><?php esc_html_e( 'إجمالي الطلبات', 'sweet-house-theme' ); ?></h3>

		<div class="summary-row">
			<span class="summary-label"><?php esc_html_e( 'المجموع', 'sweet-house-theme' ); ?></span>
			<span class="summary-value"><?php wc_cart_totals_subtotal_html(); ?></span>
		</div>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
		<div class="summary-row cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
			<span class="summary-label"><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
			<span class="summary-value"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
		</div>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
			<?php wc_cart_totals_shipping_html(); ?>
			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>
		<div class="summary-row shipping">
			<span class="summary-label"><?php esc_html_e( 'الشحن', 'woocommerce' ); ?></span>
			<span class="summary-value"><?php woocommerce_shipping_calculator(); ?></span>
		</div>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
		<div class="summary-row fee">
			<span class="summary-label"><?php echo esc_html( $fee->name ); ?></span>
			<span class="summary-value"><?php wc_cart_totals_fee_html( $fee ); ?></span>
		</div>
		<?php endforeach; ?>

		<?php
		if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
				foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					?>
		<div class="summary-row tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
			<span class="summary-label"><?php echo esc_html( $tax->label ); ?></span>
			<span class="summary-value"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
		</div>
					<?php
				endforeach;
			} else {
				?>
		<div class="summary-row tax-total">
			<span class="summary-label"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
			<span class="summary-value"><?php wc_cart_totals_taxes_total_html(); ?></span>
		</div>
				<?php
			}
		}
		?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<div class="summary-row total-row">
			<span class="summary-label"><?php esc_html_e( 'الإجمالي المقدر', 'sweet-house-theme' ); ?></span>
			<span class="summary-value"><?php wc_cart_totals_order_total_html(); ?></span>
		</div>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

		<div class="wc-proceed-to-checkout">
			<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
		</div>
	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
