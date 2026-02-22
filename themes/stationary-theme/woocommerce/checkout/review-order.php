<?php
/**
 * Review Order (checkout right column) – Stationary theme override
 *
 * Renders product cards + totals matching templates/payment/payment.css:
 *   .product-grid > .product-item   (product cards)
 *   .total-card                     (order totals)
 *
 * Root element must keep class "woocommerce-checkout-review-order-table"
 * so WooCommerce AJAX can find and replace it on update_checkout.
 *
 * @package stationary-theme
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-checkout-review-order-table">

	<?php do_action( 'woocommerce_review_order_before_cart_contents' ); ?>

	<!-- Product cards -->
	<div class="product-grid">
		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 ) {
				continue;
			}

			if ( ! apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				continue;
			}

			$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
			$image_id     = $_product->get_image_id();
			$image_url    = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' ) : wc_placeholder_img_src( 'woocommerce_thumbnail' );
			$subtotal     = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
			$unit_price   = WC()->cart->get_product_price( $_product );
			?>
			<div class="product-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
				<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( wp_strip_all_tags( $product_name ) ); ?>">
				<div class="content">
					<h2><?php echo wp_kses_post( $product_name ); ?></h2>
					<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
					<p>&times; <?php echo esc_html( $cart_item['quantity'] ); ?></p>
					<div class="price">
						<p><?php echo wp_kses_post( $subtotal ); ?></p>
						<?php if ( $cart_item['quantity'] > 1 ) : ?>
							<p><?php echo wp_kses_post( $unit_price ); ?> <?php esc_html_e( 'للقطعة', 'stationary-theme' ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>

	<?php do_action( 'woocommerce_review_order_after_cart_contents' ); ?>

	<!-- Order totals -->
	<div class="total-card">
		<p>
			<?php esc_html_e( 'المجموع الفرعي', 'stationary-theme' ); ?>
			<span><?php wc_cart_totals_subtotal_html(); ?></span>
		</p>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<p>
				<?php echo esc_html( sprintf( __( 'خصم (%s)', 'stationary-theme' ), $code ) ); ?>
				<span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
			</p>
		<?php endforeach; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<p>
				<?php echo esc_html( $fee->name ); ?>
				<span><?php wc_cart_totals_fee_html( $fee ); ?></span>
			</p>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
			<?php wc_cart_totals_shipping_html(); ?>
			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
		<?php endif; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php
			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
				foreach ( WC()->cart->get_tax_totals() as $tax ) {
					?>
					<p>
						<?php echo esc_html( $tax->label ); ?>
						<span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
					</p>
					<?php
				}
			} else {
				?>
				<p>
					<?php echo esc_html( WC()->countries->tax_or_vat() ); ?>
					<span><?php wc_cart_totals_taxes_total_html(); ?></span>
				</p>
				<?php
			}
			?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<p class="estimated-total">
			<?php esc_html_e( 'الإجمالي', 'stationary-theme' ); ?>
			<span><?php wc_cart_totals_order_total_html(); ?></span>
		</p>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
	</div>

</div>
