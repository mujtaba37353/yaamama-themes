<?php
/**
 * Product card in loop — Sweet House design.
 *
 * @package Sweet_House_Theme
 * @see     design: sweet-house/components/cards/y-c-product-card.html
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! is_a( $product, 'WC_Product' ) || ! $product->is_visible() ) {
	return;
}

$permalink      = get_the_permalink();
$title          = get_the_title();
$image_id       = $product->get_image_id();
$add_to_cart_url = $product->add_to_cart_url();
$in_wishlist    = function_exists( 'sweet_house_get_wishlist_ids' ) && in_array( (int) $product->get_id(), sweet_house_get_wishlist_ids(), true );
$is_simple      = $product->is_type( 'simple' );
$add_to_cart_class = 'add-to-cart-btn' . ( $is_simple ? ' ajax_add_to_cart add_to_cart_button' : '' );
$quick_view_img = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' ) : sweet_house_asset_uri( 'assets/product.png' );
$gallery_ids    = $product->get_gallery_image_ids();
$gallery_urls   = array();
if ( $image_id ) {
	$main_url = wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' );
	if ( $main_url ) {
		$gallery_urls[] = $main_url;
	}
}
foreach ( $gallery_ids as $gid ) {
	$url = wp_get_attachment_image_url( $gid, 'woocommerce_thumbnail' );
	if ( $url ) {
		$gallery_urls[] = $url;
	}
}
$price_display  = $product->get_price();
$avg_rating     = $product->get_average_rating();
$rating_count   = $product->get_rating_count();
?>
<li <?php wc_product_class( 'product-card', $product ); ?>
	data-product-permalink="<?php echo esc_url( $permalink ); ?>"
	data-product-title="<?php echo esc_attr( $title ); ?>"
	data-product-price="<?php echo esc_attr( $price_display !== '' ? $price_display : '—' ); ?>"
	data-product-image="<?php echo esc_url( $quick_view_img ); ?>"
	data-product-add-to-cart="<?php echo esc_url( $add_to_cart_url ); ?>"
	data-product-rating="<?php echo esc_attr( $avg_rating ); ?>"
	data-product-rating-count="<?php echo esc_attr( $rating_count ); ?>"
	data-product-gallery="<?php echo esc_attr( wp_json_encode( $gallery_urls ) ); ?>">
	<div class="product-card-img">
		<label class="favorite-toggle" aria-label="<?php esc_attr_e( 'إضافة للمفضلة', 'sweet-house-theme' ); ?>">
			<input type="checkbox" class="favorite-toggle__checkbox" <?php echo $in_wishlist ? ' checked' : ''; ?> data-product-id="<?php echo esc_attr( (string) $product->get_id() ); ?>">
			<span class="favorite-toggle__icon">
				<i class="fa-regular fa-heart" aria-hidden="true"></i>
				<i class="fa-solid fa-heart" aria-hidden="true"></i>
			</span>
		</label>
		<a href="#" class="js-open-quick-view" aria-label="<?php echo esc_attr( $title ); ?>">
			<?php if ( $image_id ) : ?>
				<?php echo wp_get_attachment_image( $image_id, 'woocommerce_thumbnail', false, array( 'alt' => esc_attr( $title ) ) ); ?>
			<?php else : ?>
				<img src="<?php echo esc_url( sweet_house_asset_uri( 'assets/product.png' ) ); ?>" alt="<?php echo esc_attr( $title ); ?>" />
			<?php endif; ?>
		</a>
	</div>
	<div class="product-card-info">
		<button type="button" class="quick-add-btn" aria-label="<?php esc_attr_e( 'معاينة سريعة', 'sweet-house-theme' ); ?>">
			<i class="fa-solid fa-plus"></i>
		</button>
		<h3 class="product-card-title">
			<a href="#" class="js-open-quick-view"><?php echo esc_html( $title ); ?></a>
		</h3>
	</div>
	<div class="product-card-price">
		<?php
		$price = $product->get_price();
		$regular = $product->get_regular_price();
		if ( $product->is_on_sale() && $regular ) :
			?>
			<span class="price-amount price-sale"><?php echo esc_html( $price ?: $regular ); ?></span>
			<span class="price-regular"><del><?php echo esc_html( $regular ); ?></del></span>
		<?php else : ?>
			<span class="price-amount"><?php echo esc_html( $price ?: '—' ); ?></span>
		<?php endif; ?>
		<img src="<?php echo esc_url( sweet_house_asset_uri( 'assets/ryal.svg' ) ); ?>" alt="<?php esc_attr_e( 'ريال', 'sweet-house-theme' ); ?>" class="riyal-icon" aria-hidden="true" />
	</div>
	<a href="<?php echo esc_url( $add_to_cart_url ); ?>" class="<?php echo esc_attr( $add_to_cart_class ); ?>" data-product_id="<?php echo esc_attr( (string) $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" data-quantity="1">
		<i class="fa-solid fa-bag-shopping" aria-hidden="true"></i>
		<span><?php echo $product->is_purchasable() && $product->is_in_stock() ? esc_html__( 'إضافة إلى السلة', 'sweet-house-theme' ) : esc_html__( 'اختر الخيارات', 'sweet-house-theme' ); ?></span>
	</a>
</li>
