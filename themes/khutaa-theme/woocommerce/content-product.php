<?php
/**
 * The template for displaying product content within loops
 *
 * @package KhutaaTheme
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure $product is valid
if ( ! is_a( $product, 'WC_Product' ) ) {
	return;
}

$product_id = $product->get_id();
$wishlist_class = 'fa-regular';
$is_in_wishlist = false;
if ( function_exists( 'khutaa_is_product_in_wishlist' ) && khutaa_is_product_in_wishlist( $product_id ) ) {
	$wishlist_class = 'fa-solid';
	$is_in_wishlist = true;
}
?>

<li class="product-card">
	<button class="wishlist-btn <?php echo $is_in_wishlist ? 'active' : ''; ?>" aria-label="<?php esc_attr_e( 'إضافة للمفضلة', 'khutaa-theme' ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>">
		<i class="<?php echo esc_attr( $wishlist_class ); ?> fa-heart"></i>
	</button>
	<div class="product-card-img">
		<a href="<?php echo esc_url( get_permalink() ); ?>">
			<?php echo woocommerce_get_product_thumbnail( 'woocommerce_thumbnail' ); ?>
		</a>
	</div>
	<div class="product-card-info">
		<h3 class="product-card-title">
			<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
		</h3>
		<span class="product-card-price"><?php echo $product->get_price_html(); ?></span>
	</div>
	<?php
	echo sprintf(
		'<a href="%s" data-product_id="%s" class="button btn-primary add_to_cart_button ajax_add_to_cart product_type_%s">%s</a>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( $product_id ),
		esc_attr( $product->get_type() ),
		esc_html__( 'أضف إلى السلة', 'khutaa-theme' )
	);
	?>
</li>
