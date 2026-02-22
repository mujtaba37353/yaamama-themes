<?php
/**
 * Single Product Price — override
 * Markup matches beauty-time single-product.html
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product ) {
	return;
}

$price = $product->get_price();
?>
<div class="price">
	<p><?php esc_html_e( 'السعر', 'beauty-time-theme' ); ?></p>
	<p><?php echo esc_html( $price ); ?> <img src="<?php echo esc_url( beauty_time_asset( 'assets/ryal-prim.svg' ) ); ?>" alt="sar"></p>
</div>
