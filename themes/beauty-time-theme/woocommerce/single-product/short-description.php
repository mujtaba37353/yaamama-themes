<?php
/**
 * Single Product Short Description — override
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->get_short_description() ) {
	return;
}
?>
<p class="product-title"><?php echo wp_kses_post( $product->get_short_description() ); ?></p>
