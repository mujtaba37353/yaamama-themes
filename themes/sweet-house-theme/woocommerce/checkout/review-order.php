<?php
/**
 * Review order — Sweet House design (payment summary).
 *
 * @see design: sweet-house/components/payment/y-c-payment-summary-card.html
 * @package Sweet_House_Theme
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="order-summary-container">
	<h2 class="order-summary-title"><?php esc_html_e( 'ملخص الطلب', 'sweet-house-theme' ); ?></h2>
	<hr class="title-divider" />

	<div class="order-products-list">
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
				$thumbnail    = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
				?>
				<div class="order-product-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<div class="product-image-wrapper">
						<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span class="quantity-badge"><?php echo esc_html( $cart_item['quantity'] ); ?></span>
					</div>
					<div class="product-details">
						<h3 class="product-name"><?php echo wp_kses_post( $product_name ); ?></h3>
						<p class="product-price"><?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					</div>
				</div>
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</div>

	<hr class="products-divider" />

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
			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
			<?php wc_cart_totals_shipping_html(); ?>
			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
		<div class="summary-row fee">
			<span class="summary-label"><?php echo esc_html( $fee->name ); ?></span>
			<span class="summary-value"><?php wc_cart_totals_fee_html( $fee ); ?></span>
		</div>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
				<div class="summary-row tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<span class="summary-label"><?php echo esc_html( $tax->label ); ?></span>
					<span class="summary-value"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
				</div>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="summary-row tax-total">
					<span class="summary-label"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
					<span class="summary-value"><?php wc_cart_totals_taxes_total_html(); ?></span>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<div class="summary-row total-row">
			<span class="summary-label"><?php esc_html_e( 'الإجمالي المقدر', 'sweet-house-theme' ); ?></span>
			<span class="summary-value"><?php wc_cart_totals_order_total_html(); ?></span>
		</div>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
	</div>
</div>
