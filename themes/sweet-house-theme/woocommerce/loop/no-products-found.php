<?php
/**
 * No products found — Sweet House design (same style as empty wishlist).
 *
 * @package Sweet_House_Theme
 * @see     design: sweet-house/templates/empty-favourite/layout.html
 */

defined( 'ABSPATH' ) || exit;

$shop_url  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$empty_img = function_exists( 'sweet_house_asset_uri' ) ? sweet_house_asset_uri( 'assets/empty-fav.png' ) : get_template_directory_uri() . '/sweet-house/assets/empty-fav.png';
$is_search = isset( $_GET['s'] ) && trim( sanitize_text_field( wp_unslash( $_GET['s'] ) ) ) !== '';
$message   = $is_search
	? __( 'لم نجد أي منتجات تطابق بحثك. جرّب كلمات أخرى أو تصفّح المتجر.', 'sweet-house-theme' )
	: __( 'لا توجد منتجات متاحة حالياً.', 'sweet-house-theme' );
?>
<div class="not-found-container">
	<div class="not-found-content">
			<img src="<?php echo esc_url( $empty_img ); ?>" alt="<?php esc_attr_e( 'لا توجد منتجات', 'sweet-house-theme' ); ?>" class="not-found-img" />
			<p class="not-found-text"><?php echo esc_html( $message ); ?></p>
			<a href="<?php echo esc_url( $shop_url ); ?>" class="btn-back">
				<?php esc_html_e( 'تصفح المنتجات', 'sweet-house-theme' ); ?> <i class="fa-solid fa-store"></i>
			</a>
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
