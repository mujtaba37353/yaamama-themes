<?php
/**
 * Empty cart — Sweet House design.
 *
 * @package Sweet_House_Theme
 * @see     design: sweet-house/templates/empty-cart/layout.html
 */

defined( 'ABSPATH' ) || exit;

// Remove default empty cart message; we use custom design.
remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );

// Design header — same as cart page.
echo '<header data-y="design-header" class="cart-design-header"><img src="' . esc_url( sweet_house_asset_uri( 'assets/panner.png' ) ) . '" alt="' . esc_attr__( 'بانر سويت هاوس - متجر الحلويات والمخبوزات', 'sweet-house-theme' ) . '" class="panner-img" /></header>';

// Notices (e.g. item removed).
do_action( 'woocommerce_cart_is_empty' );
?>

<div class="y-u-my-10">
	<div class="main-container">
		<nav data-y="breadcrumb" class="woocommerce-breadcrumb-wrap y-breadcrumb-container" aria-label="<?php esc_attr_e( 'مسار الصفحة', 'sweet-house-theme' ); ?>">
			<ol class="y-breadcrumb">
				<li class="y-breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'sweet-house-theme' ); ?></a></li>
				<li class="y-breadcrumb-item active"><?php esc_html_e( 'سلة المشتريات', 'sweet-house-theme' ); ?></li>
			</ol>
		</nav>
	</div>
</div>

<div class="not-found-container">
	<div class="main-container">
		<div class="not-found-content">
			<img src="<?php echo esc_url( sweet_house_asset_uri( 'assets/empty-cart.png' ) ); ?>" alt="<?php esc_attr_e( 'السلة فارغة', 'sweet-house-theme' ); ?>" class="not-found-img" />
			<p class="not-found-text">
				<?php esc_html_e( 'السلة فارغة، لم تقم بإضافة أي منتجات إلى السلة الخاصة بك بعد.', 'sweet-house-theme' ); ?>
			</p>
			<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
			<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="btn-back"><?php esc_html_e( 'تصفح المنتجات', 'sweet-house-theme' ); ?> <i class="fa-solid fa-store"></i></a>
			<?php endif; ?>
		</div>
		<?php
		$args     = array(
			'post_type'      => 'product',
			'posts_per_page' => 8,
			'post_status'    => 'publish',
		);
		$featured = get_posts( array_merge( $args, array( 'meta_key' => '_featured', 'meta_value' => 'yes', 'orderby' => 'menu_order title', 'order' => 'ASC' ) ) );
		if ( empty( $featured ) ) {
			$featured = get_posts( array_merge( $args, array( 'orderby' => 'date', 'order' => 'DESC' ) ) );
		}
		if ( ! empty( $featured ) ) :
			?>
		<div class="pro">
			<h1 class="section-title"><?php esc_html_e( 'تسوق أكثر', 'sweet-house-theme' ); ?></h1>
			<ul class="products">
				<?php
				foreach ( $featured as $post ) {
					$product = wc_get_product( $post->ID );
					if ( $product && $product->is_visible() ) {
						$GLOBALS['product'] = $product;
						wc_get_template( 'content-product.php' );
					}
				}
				wp_reset_postdata();
				?>
			</ul>
		</div>
		<?php endif; ?>
	</div>
</div>
