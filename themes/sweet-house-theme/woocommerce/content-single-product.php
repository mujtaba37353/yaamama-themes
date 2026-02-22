<?php
/**
 * Single Product Content — design from beauty-care-theme/beauty-care (product-details).
 *
 * @package Sweet_House_Theme
 * @see     beauty-care-theme/beauty-care/templates/product-details/product-details.html
 */

defined( 'ABSPATH' ) || exit;

global $product;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

$asset_uri   = function_exists( 'sweet_house_asset_uri' ) ? sweet_house_asset_uri( '' ) : get_template_directory_uri() . '/sweet-house/';
$shop_url    = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$terms       = get_the_terms( $product->get_id(), 'product_cat' );
$cat_name    = ( $terms && ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->name : __( 'المتجر', 'sweet-house-theme' );
$cat_link    = ( $terms && ! is_wp_error( $terms ) && ! empty( $terms ) ) ? get_term_link( $terms[0] ) : $shop_url;
$image_id    = $product->get_image_id();
$gallery_ids = $product->get_gallery_image_ids();
$all_images  = $image_id ? array_merge( array( $image_id ), $gallery_ids ) : $gallery_ids;
$main_img    = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_single' ) : $asset_uri . 'assets/product.png';
$title       = $product->get_name();
$description = $product->get_short_description() ? $product->get_short_description() : $product->get_description();
$price       = $product->get_price();
$checkout_url = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '';
$is_simple   = $product->is_type( 'simple' );
$is_purchasable = $product->is_purchasable() && $product->is_in_stock();
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
	<div class="container y-u-max-w-1200">
		<div class="breadcrumbs mobile">
			<a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'المتجر', 'sweet-house-theme' ); ?></a>
			/
			<a href="<?php echo esc_url( is_string( $cat_link ) ? $cat_link : $shop_url ); ?>"><?php echo esc_html( $cat_name ); ?></a>
			/
			<a href="#" class="active"><?php echo esc_html( $title ); ?></a>
		</div>
		<div class="imgs-section">
			<div class="imgs">
				<?php
				if ( ! empty( $all_images ) ) {
					foreach ( array_slice( $all_images, 0, 3 ) as $aid ) {
						$url = wp_get_attachment_image_url( $aid, 'woocommerce_thumbnail' );
						if ( $url ) {
							$full = wp_get_attachment_image_url( $aid, 'woocommerce_single' );
							echo '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( $title ) . '" data-full="' . esc_url( $full ?: $url ) . '">';
						}
					}
				} else {
					echo '<img src="' . esc_url( $main_img ) . '" alt="' . esc_attr( $title ) . '">';
					echo '<img src="' . esc_url( $main_img ) . '" alt="' . esc_attr( $title ) . '">';
					echo '<img src="' . esc_url( $main_img ) . '" alt="' . esc_attr( $title ) . '">';
				}
				?>
			</div>
			<div class="main-img">
				<label class="favorite-toggle">
					<input type="checkbox" class="favorite-toggle__checkbox" aria-label="<?php esc_attr_e( 'إضافة للمفضلة', 'sweet-house-theme' ); ?>">
					<span class="favorite-toggle__icon">
						<i class="fa-solid fa-heart" aria-hidden="true"></i>
						<i class="fa-regular fa-heart" aria-hidden="true"></i>
					</span>
				</label>
				<img src="<?php echo esc_url( $main_img ); ?>" alt="<?php echo esc_attr( $title ); ?>" id="single-product-main-img">
			</div>
		</div>
		<div class="content">
			<div class="breadcrumbs">
				<a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'المتجر', 'sweet-house-theme' ); ?></a>
				/
				<a href="<?php echo esc_url( is_string( $cat_link ) ? $cat_link : $shop_url ); ?>"><?php echo esc_html( $cat_name ); ?></a>
				/
				<a href="#" class="active"><?php echo esc_html( $title ); ?></a>
			</div>
			<h3><?php echo esc_html( $title ); ?></h3>
			<?php if ( $description ) : ?>
				<p><?php echo wp_kses_post( $description ); ?></p>
			<?php endif; ?>
			<div class="price">
				<p><?php echo esc_html( $price ?: '—' ); ?></p>
				<img src="<?php echo esc_url( $asset_uri . 'assets/ryal.svg' ); ?>" alt="<?php esc_attr_e( 'ريال', 'sweet-house-theme' ); ?>">
			</div>
			<?php if ( $is_simple && $is_purchasable ) : ?>
			<form class="cart" method="post" enctype="multipart/form-data" action="<?php echo esc_url( $product->add_to_cart_url() ); ?>">
				<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
				<div class="quantity">
					<button type="button" class="qty-minus" aria-label="<?php esc_attr_e( 'تقليل الكمية', 'sweet-house-theme' ); ?>"><i class="fa-solid fa-minus"></i></button>
					<input type="number" name="quantity" class="qty-input" value="<?php echo esc_attr( $product->get_min_purchase_quantity() ); ?>" min="<?php echo esc_attr( $product->get_min_purchase_quantity() ); ?>" max="<?php echo esc_attr( 0 < $product->get_max_purchase_quantity() ? $product->get_max_purchase_quantity() : 999 ); ?>" step="1">
					<button type="button" class="qty-plus" aria-label="<?php esc_attr_e( 'زيادة الكمية', 'sweet-house-theme' ); ?>"><i class="fa-solid fa-plus"></i></button>
				</div>
				<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>">
				<div class="buttons">
					<?php if ( $checkout_url ) : ?>
					<button type="submit" name="sweet_house_buy_now" value="1"><?php esc_html_e( 'اشتري الآن', 'sweet-house-theme' ); ?></button>
					<?php endif; ?>
					<button type="submit"><?php esc_html_e( 'إضافة إلى العربة', 'sweet-house-theme' ); ?></button>
				</div>
				<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
			</form>
			<?php else : ?>
				<?php do_action( 'woocommerce_single_product_summary' ); ?>
			<?php endif; ?>
		</div>
	</div>
</div>

<script>
(function() {
	var qty = document.querySelector('.details-section .qty-input');
	var minus = document.querySelector('.details-section .qty-minus');
	var plus = document.querySelector('.details-section .qty-plus');
	if (qty && minus && plus) {
		minus.addEventListener('click', function() {
			var v = parseInt(qty.value, 10) || 1;
			var min = parseInt(qty.getAttribute('min'), 10) || 1;
			if (v > min) qty.value = v - 1;
		});
		plus.addEventListener('click', function() {
			var v = parseInt(qty.value, 10) || 0;
			var max = parseInt(qty.getAttribute('max'), 10) || 999;
			if (v < max) qty.value = v + 1;
		});
	}
	var mainImg = document.getElementById('single-product-main-img');
	var thumbs = document.querySelectorAll('.details-section .imgs img');
	if (mainImg && thumbs.length) {
		thumbs.forEach(function(thumb) {
			thumb.addEventListener('click', function() {
				var src = thumb.getAttribute('data-full') || thumb.src;
				mainImg.src = src;
			});
		});
	}
})();
</script>
<?php
do_action( 'woocommerce_after_single_product' );
do_action( 'woocommerce_after_single_product_summary' );
?>
