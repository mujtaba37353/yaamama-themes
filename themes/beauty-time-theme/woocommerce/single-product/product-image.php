<?php
/**
 * Single Product Image — override
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

global $product;

$image_id = $product->get_image_id();
$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_single' ) : wc_placeholder_img_src();
?>
<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
