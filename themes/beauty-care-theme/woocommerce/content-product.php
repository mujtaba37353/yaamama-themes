<?php
defined( 'ABSPATH' ) || exit;

global $product;

$assets_uri   = get_template_directory_uri() . '/beauty-care/assets';
$thumb_id     = $product->get_image_id();
$img_url      = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'woocommerce_thumbnail' ) : $assets_uri . '/pro1.jpg';
$product_id   = $product->get_id();
$is_simple    = $product->is_type( 'simple' );
$is_purchasable = $product->is_purchasable() && $product->is_in_stock();
$add_via_ajax = $is_simple && $is_purchasable;
?>
<li class="product-card <?php echo esc_attr( implode( ' ', wc_get_product_class( '', $product ) ) ); ?>">
	<div class="product-img">
		<a href="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php echo esc_attr( $product->get_name() ); ?>">
			<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
		</a>
		<?php
		$in_wishlist = function_exists( 'beauty_care_get_wishlist_ids' ) && in_array( (int) $product_id, beauty_care_get_wishlist_ids(), true );
		?>
		<label class="favorite-toggle" aria-label="<?php esc_attr_e( 'إضافة للمفضلة', 'beauty-care-theme' ); ?>">
			<input type="checkbox" class="favorite-toggle__checkbox" <?php echo $in_wishlist ? ' checked' : ''; ?> data-product-id="<?php echo esc_attr( (string) $product_id ); ?>">
			<span class="favorite-toggle__icon">
				<i class="fa-solid fa-heart" aria-hidden="true"></i>
				<i class="fa-regular fa-heart" aria-hidden="true"></i>
			</span>
		</label>
		<?php if ( $add_via_ajax ) : ?>
			<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="add-to-cart-btn button add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" data-quantity="1" aria-label="<?php esc_attr_e( 'أضف للسلة', 'beauty-care-theme' ); ?>">
				<img src="<?php echo esc_url( $assets_uri . '/add-to-cart.svg' ); ?>" alt="<?php esc_attr_e( 'أضف للسلة', 'beauty-care-theme' ); ?>">
			</a>
		<?php else : ?>
			<a href="<?php echo esc_url( get_permalink() ); ?>" class="add-to-cart-btn button" aria-label="<?php echo esc_attr( $product->add_to_cart_text() ); ?>">
				<img src="<?php echo esc_url( $assets_uri . '/add-to-cart.svg' ); ?>" alt="<?php esc_attr_e( 'أضف للسلة', 'beauty-care-theme' ); ?>">
			</a>
		<?php endif; ?>
	</div>
	<div class="product-content">
		<a href="<?php echo esc_url( get_permalink() ); ?>">
			<p class="product-title"><?php echo esc_html( $product->get_name() ); ?></p>
		</a>
		<p class="product-price"><?php echo esc_html( $product->get_price() ); ?> <img src="<?php echo esc_url( $assets_uri . '/ryal.svg' ); ?>" alt=""></p>
	</div>
</li>
