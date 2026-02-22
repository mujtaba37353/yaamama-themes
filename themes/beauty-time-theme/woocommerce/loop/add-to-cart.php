<?php
/**
 * Loop Add to Cart — override
 * Button matches beauty-time: "احجز الآن" with book-now icon
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product ) {
	return;
}

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product );

if ( $product->is_in_stock() ) :
	?>
	<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="btn full add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" aria-label="<?php echo esc_attr( $product->add_to_cart_description() ); ?>" rel="nofollow">
		<img src="<?php echo esc_url( beauty_time_asset( 'assets/book-now.svg' ) ); ?>" alt="book-now"><?php echo esc_html( $product->add_to_cart_text() ); ?>
	</a>
<?php endif; ?>
