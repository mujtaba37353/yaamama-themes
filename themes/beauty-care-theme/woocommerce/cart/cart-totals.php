<?php
/**
 * Cart totals - Beauty Care design override
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 */

defined( 'ABSPATH' ) || exit;

$assets_uri = get_template_directory_uri() . '/beauty-care/assets';
$shop_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
?>
<div class="cart_totals left <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h3><?php esc_html_e( 'إجمالي سلة المشتريات', 'beauty-care-theme' ); ?></h3>
	<div class="total-card">

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<p class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<?php wc_cart_totals_coupon_label( $coupon ); ?>
				<span class="y-u-flex y-u-items-center y-u-gap-8"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
			</p>
		<?php endforeach; ?>

		<p class="cart-subtotal">
			<?php esc_html_e( 'المجموع', 'beauty-care-theme' ); ?>
			<span class="y-u-flex y-u-items-center y-u-gap-8"><?php wc_cart_totals_subtotal_html(); ?></span>
		</p>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<p class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<?php echo esc_html( $tax->label ); ?>
						<span class="y-u-flex y-u-items-center y-u-gap-8"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
					</p>
				<?php endforeach; ?>
			<?php else : ?>
				<p class="tax-total">
					<?php echo esc_html( WC()->countries->tax_or_vat() ); ?>
					<span class="y-u-flex y-u-items-center y-u-gap-8"><?php wc_cart_totals_taxes_total_html(); ?></span>
				</p>
			<?php endif; ?>
		<?php endif; ?>

		<p class="estimated-total order-total">
			<?php esc_html_e( 'الإجمالى المقدر', 'beauty-care-theme' ); ?>
			<span class="y-u-flex y-u-items-center y-u-gap-8"><?php wc_cart_totals_order_total_html(); ?></span>
		</p>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
	</div>

	<div class="wc-proceed-to-checkout">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>
	<a href="<?php echo esc_url( $shop_url ); ?>" class="btn outline"><?php esc_html_e( 'مواصلة التسوق', 'beauty-care-theme' ); ?></a>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>
</div>
