<?php
defined( 'ABSPATH' ) || exit;

get_header();

if ( ! isset( $product ) || ! is_a( $product, 'WC_Product' ) ) {
	$product = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : null;
}
if ( ! $product ) {
	return;
}

$assets_uri   = get_template_directory_uri() . '/beauty-care/assets';
$shop_url     = beauty_care_shop_permalink();
$checkout_url = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : home_url( '/checkout' );
$gallery_ids  = $product->get_gallery_image_ids();
$thumb_id     = $product->get_image_id();
$main_img     = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'woocommerce_single' ) : $assets_uri . '/pro2.jpg';
$terms        = get_the_terms( $product->get_id(), 'product_cat' );
$cat_name     = ( $terms && ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->name : __( 'المتجر', 'beauty-care-theme' );
$cat_link     = ( $terms && ! is_wp_error( $terms ) && ! empty( $terms ) ) ? get_term_link( $terms[0] ) : $shop_url;
?>

<main>
	<section class="details-section">
		<div class="container y-u-max-w-1200">
			<div class="breadcrumbs mobile">
				<a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'المتجر', 'beauty-care-theme' ); ?></a>
				/
				<a href="<?php echo esc_url( is_string( $cat_link ) ? $cat_link : $shop_url ); ?>"><?php echo esc_html( $cat_name ); ?></a>
				/
				<a href="#" class="active"><?php echo esc_html( $product->get_name() ); ?></a>
			</div>
			<div class="imgs-section">
				<div class="imgs">
					<?php
					if ( ! empty( $gallery_ids ) ) {
						foreach ( array_slice( $gallery_ids, 0, 3 ) as $gid ) {
							$url = wp_get_attachment_image_url( $gid, 'thumbnail' );
							if ( $url ) {
								echo '<img src="' . esc_url( $url ) . '" alt="">';
							}
						}
					}
					if ( empty( $gallery_ids ) ) {
						echo '<img src="' . esc_url( $main_img ) . '" alt="">';
						echo '<img src="' . esc_url( $main_img ) . '" alt="">';
						echo '<img src="' . esc_url( $main_img ) . '" alt="">';
					}
					?>
				</div>
				<?php
				$product_id  = $product->get_id();
				$in_wishlist = function_exists( 'beauty_care_get_wishlist_ids' ) && in_array( (int) $product_id, beauty_care_get_wishlist_ids(), true );
				?>
				<div class="main-img">
					<label class="favorite-toggle" aria-label="<?php esc_attr_e( 'إضافة للمفضلة', 'beauty-care-theme' ); ?>">
						<input type="checkbox" class="favorite-toggle__checkbox" <?php echo $in_wishlist ? ' checked' : ''; ?> data-product-id="<?php echo esc_attr( (string) $product_id ); ?>">
						<span class="favorite-toggle__icon">
							<i class="fa-solid fa-heart" aria-hidden="true"></i>
							<i class="fa-regular fa-heart" aria-hidden="true"></i>
						</span>
					</label>
					<img src="<?php echo esc_url( $main_img ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
				</div>
			</div>
			<div class="content">
				<div class="breadcrumbs">
					<a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'المتجر', 'beauty-care-theme' ); ?></a>
					/
					<a href="<?php echo esc_url( is_string( $cat_link ) ? $cat_link : $shop_url ); ?>"><?php echo esc_html( $cat_name ); ?></a>
					/
					<a href="#" class="active"><?php echo esc_html( $product->get_name() ); ?></a>
				</div>
				<h3><?php echo esc_html( $product->get_name() ); ?></h3>
				<?php echo wp_kses_post( $product->get_description() ? $product->get_description() : '<p>' . __( 'منتج عالي الجودة للعناية بالبشرة.', 'beauty-care-theme' ) . '</p>' ); ?>
				<div class="price">
					<p><?php echo esc_html( $product->get_price() ); ?></p>
					<img src="<?php echo esc_url( $assets_uri . '/ryal.svg' ); ?>" alt="">
				</div>
				<?php
				$max_qty = $product->get_max_purchase_quantity();
				$min_qty = $product->get_min_purchase_quantity();
				?>
				<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype="multipart/form-data">
					<div class="quantity">
						<button type="button" class="qty-minus" aria-label="<?php esc_attr_e( 'تقليل الكمية', 'beauty-care-theme' ); ?>"><i class="fa-solid fa-minus"></i></button>
						<input type="number" name="quantity" value="<?php echo esc_attr( max( $min_qty, 1 ) ); ?>" min="<?php echo esc_attr( $min_qty ); ?>" <?php echo ( $max_qty > 0 ) ? 'max="' . esc_attr( $max_qty ) . '"' : ''; ?> class="qty-input" step="1" inputmode="numeric">
						<button type="button" class="qty-plus" aria-label="<?php esc_attr_e( 'زيادة الكمية', 'beauty-care-theme' ); ?>"><i class="fa-solid fa-plus"></i></button>
					</div>
					<div class="buttons">
						<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>">
						<button type="submit" name="beauty_care_buy_now" value="1" class="button"><?php esc_html_e( 'اشتري الآن', 'beauty-care-theme' ); ?></button>
						<button type="submit" class="button"><?php esc_html_e( 'أضف إلى السلة', 'beauty-care-theme' ); ?></button>
					</div>
				</form>
			</div>
		</div>
	</section>

	<?php if ( $product->get_short_description() ) : ?>
	<section class="ingredients-section">
		<div class="container y-u-max-w-1200">
			<div class="details">
				<h2><?php esc_html_e( 'المكونات', 'beauty-care-theme' ); ?></h2>
				<div class="grid-items">
					<?php echo wp_kses_post( $product->get_short_description() ); ?>
				</div>
			</div>
			<?php if ( file_exists( get_template_directory() . '/beauty-care/assets/ingredients.jpg' ) ) : ?>
			<img src="<?php echo esc_url( $assets_uri . '/ingredients.jpg' ); ?>" alt="">
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

	<section class="products-section">
		<div class="container y-u-max-w-1200">
			<h2><?php esc_html_e( 'منتجاتنا المميزة', 'beauty-care-theme' ); ?></h2>
			<div class="products-grid">
				<?php
				if ( function_exists( 'wc_get_products' ) ) {
					$related = wc_get_products( array( 'limit' => 3, 'exclude' => array( $product->get_id() ), 'status' => 'publish' ) );
					foreach ( $related as $rel ) {
						$r_thumb     = $rel->get_image_id();
						$r_img       = $r_thumb ? wp_get_attachment_image_url( $r_thumb, 'woocommerce_thumbnail' ) : $assets_uri . '/pro2.jpg';
						$r_id        = $rel->get_id();
						$r_in_wishlist = function_exists( 'beauty_care_get_wishlist_ids' ) && in_array( (int) $r_id, beauty_care_get_wishlist_ids(), true );
						?>
						<a href="<?php echo esc_url( $rel->get_permalink() ); ?>">
							<div class="product-card">
								<div class="product-img">
									<label class="favorite-toggle" aria-label="<?php esc_attr_e( 'إضافة للمفضلة', 'beauty-care-theme' ); ?>">
										<input type="checkbox" class="favorite-toggle__checkbox" <?php echo $r_in_wishlist ? ' checked' : ''; ?> data-product-id="<?php echo esc_attr( (string) $r_id ); ?>">
										<span class="favorite-toggle__icon">
											<i class="fa-solid fa-heart" aria-hidden="true"></i>
											<i class="fa-regular fa-heart" aria-hidden="true"></i>
										</span>
									</label>
									<img src="<?php echo esc_url( $r_img ); ?>" alt="<?php echo esc_attr( $rel->get_name() ); ?>">
									<button type="button"><img src="<?php echo esc_url( $assets_uri . '/add-to-cart.svg' ); ?>" alt=""></button>
								</div>
								<div class="product-content">
									<p class="product-title"><?php echo esc_html( $rel->get_name() ); ?></p>
									<p class="product-price"><?php echo esc_html( $rel->get_price() ); ?> <img src="<?php echo esc_url( $assets_uri . '/ryal.svg' ); ?>" alt=""></p>
								</div>
							</div>
						</a>
						<?php
					}
				}
				?>
			</div>
		</div>
	</section>
</main>

<script>
(function() {
	var qty = document.querySelector('.details-section .qty-input');
	var minus = document.querySelector('.details-section .qty-minus');
	var plus = document.querySelector('.details-section .qty-plus');
	if (qty && minus && plus) {
		var min = parseInt(qty.getAttribute('min'), 10) || 1;
		minus.addEventListener('click', function() {
			var v = parseInt(qty.value, 10) || min;
			if (v > min) qty.value = v - 1;
		});
		plus.addEventListener('click', function() {
			var v = parseInt(qty.value, 10) || 0;
			var maxAttr = qty.getAttribute('max');
			var max = (maxAttr !== null && maxAttr !== '' && parseInt(maxAttr, 10) > 0) ? parseInt(maxAttr, 10) : 999999;
			if (v < max) qty.value = v + 1;
		});
		qty.addEventListener('change', function() {
			var v = parseInt(qty.value, 10);
			var maxAttr = qty.getAttribute('max');
			var maxVal = (maxAttr !== null && maxAttr !== '' && parseInt(maxAttr, 10) > 0) ? parseInt(maxAttr, 10) : 999999;
			if (isNaN(v) || v < min) qty.value = min;
			else if (v > maxVal) qty.value = maxVal;
		});
	}
})();
</script>
<?php
get_footer();
