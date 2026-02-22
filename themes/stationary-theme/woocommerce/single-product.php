<?php
defined( 'ABSPATH' ) || exit;

get_header();

global $product;
if ( ! $product && function_exists( 'wc_get_product' ) ) {
	$product = wc_get_product( get_the_ID() );
}
if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
	$queried_id = get_queried_object_id();
	if ( $queried_id ) {
		$product = wc_get_product( $queried_id );
	}
}
if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
	?>
	<main>
		<section class="container y-u-max-w-1200 empty-state-container">
			<div class="empty-state">
				<img src="<?php echo esc_url( stationary_asset_url( 'empty-cart.png' ) ); ?>" alt="">
				<h3><?php esc_html_e( 'تعذر تحميل تفاصيل المنتج.', 'stationary-theme' ); ?></h3>
				<a href="<?php echo esc_url( stationary_shop_permalink() ); ?>" class="btn main-button"><?php esc_html_e( 'العودة للمتجر', 'stationary-theme' ); ?></a>
			</div>
		</section>
	</main>
	<?php
	get_footer();
	return;
}

$au         = stationary_base_uri() . '/assets';
$shop_url   = stationary_shop_permalink();
$gallery_ids = $product->get_gallery_image_ids();
$thumb_id   = $product->get_image_id();
$main_img   = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'woocommerce_single' ) : ( $au . '/product-1.png' );
$min_qty    = $product->get_min_purchase_quantity();
$max_qty    = $product->get_max_purchase_quantity();
?>

<main>
	<section class="breadcrumbs container y-u-max-w-1200 y-u-m-b-0 ">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'stationary-theme' ); ?></a>
		<a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'المنتجات', 'stationary-theme' ); ?></a>
		<p><?php echo esc_html( $product->get_name() ); ?></p>
	</section>
	<section class="details-section">
		<div class="container y-u-max-w-1200">
			<div class="bottom">
				<div class="imgs-section">
					<div class="imgs">
						<?php
						if ( ! empty( $gallery_ids ) ) {
							foreach ( array_slice( $gallery_ids, 0, 3 ) as $gid ) {
								$url = wp_get_attachment_image_url( $gid, 'thumbnail' );
								if ( $url ) {
									echo '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( $product->get_name() ) . '">';
								}
							}
						}
						if ( empty( $gallery_ids ) ) {
							for ( $i = 0; $i < 3; $i++ ) {
								echo '<img src="' . esc_url( $main_img ) . '" alt="' . esc_attr( $product->get_name() ) . '">';
							}
						}
						?>
					</div>
					<div class="main-img">
						<label class="favorite-toggle">
							<input type="checkbox" class="favorite-toggle__checkbox" aria-label="<?php esc_attr_e( 'إضافة إلى المفضلة', 'stationary-theme' ); ?>" data-product-id="<?php echo esc_attr( (int) $product->get_id() ); ?>">
							<span class="favorite-toggle__icon">
								<i class="fa-solid fa-heart" aria-hidden="true"></i>
								<i class="fa-regular fa-heart" aria-hidden="true"></i>
							</span>
						</label>
						<img src="<?php echo esc_url( $main_img ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
					</div>
				</div>
				<div class="content">
					<h3><?php echo esc_html( $product->get_name() ); ?></h3>
					<div class="price">
						<p><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
					</div>
					<?php if ( $product->is_type( 'variable' ) ) : ?>
						<div class="buttons"><?php woocommerce_template_single_add_to_cart(); ?></div>
					<?php else : ?>
						<form class="cart" method="post" enctype="multipart/form-data">
							<div class="buttons">
								<div class="quantity">
									<button type="button" class="qty-minus" aria-label="<?php esc_attr_e( 'تقليل', 'stationary-theme' ); ?>">-</button>
									<input type="number" name="quantity" value="<?php echo esc_attr( max( $min_qty, 1 ) ); ?>" min="<?php echo esc_attr( $min_qty ); ?>" <?php echo ( $max_qty > 0 ) ? 'max="' . esc_attr( $max_qty ) . '"' : ''; ?> step="1" inputmode="numeric">
									<button type="button" class="qty-plus" aria-label="<?php esc_attr_e( 'زيادة', 'stationary-theme' ); ?>">+</button>
								</div>
								<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="button"><?php esc_html_e( 'أضف إلى السلة', 'stationary-theme' ); ?></button>
							</div>
						</form>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<?php get_template_part( 'stationary/components/payment-details' ); ?>

	<section class="description-section">
		<div class="container y-u-max-w-1200">
			<div class="description">
				<p><?php esc_html_e( 'وصف المنتج', 'stationary-theme' ); ?></p>
				<p><?php echo wp_kses_post( $product->get_description() ?: __( 'منتج عالي الجودة.', 'stationary-theme' ) ); ?></p>
			</div>
		</div>
	</section>

	<section class="products-section">
		<div class="container y-u-max-w-1200">
			<div class="header">
				<h2><?php esc_html_e( 'المنتجات المشابهة', 'stationary-theme' ); ?></h2>
			</div>
			<ul class="grid">
				<?php
				if ( function_exists( 'wc_get_products' ) ) {
					$related = wc_get_products( array( 'limit' => 4, 'exclude' => array( $product->get_id() ), 'status' => 'publish' ) );
					foreach ( $related as $rel ) {
						get_template_part( 'stationary/partials/product-card', null, array( 'product' => $rel, 'show_sale' => $rel->is_on_sale() ) );
					}
				}
				?>
			</ul>
		</div>
	</section>
</main>

<script>
(function(){
	var section = document.querySelector('.details-section');
	if (!section) return;
	var qty = section.querySelector('.quantity input');
	var minus = section.querySelector('.quantity button:first-of-type');
	var plus = section.querySelector('.quantity button:last-of-type');
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
	}
})();
</script>
<?php
get_footer();
