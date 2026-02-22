<?php
/**
 * Template Name: المفضلة
 *
 * صفحة المنتجات المفضلة — نفس تصميم المتجر بدون شريط تصنيفات.
 * حالة فارغة: نفس واجهة السلة الفارغة + زر تصفح المنتجات.
 *
 * @package beauty-care-theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

$wishlist_ids = function_exists( 'beauty_care_get_wishlist_ids' ) ? beauty_care_get_wishlist_ids() : array();
$wishlist_products = array();

if ( ! empty( $wishlist_ids ) && function_exists( 'wc_get_products' ) ) {
	$wishlist_products = wc_get_products( array(
		'include'  => $wishlist_ids,
		'status'    => 'publish',
		'orderby'   => 'post__in',
		'order'     => 'ASC',
	) );
	// ترتيب حسب ترتيب wishlist_ids
	$id_order = array_flip( $wishlist_ids );
	usort( $wishlist_products, function ( $a, $b ) use ( $id_order ) {
		$ia = $id_order[ $a->get_id() ] ?? 999;
		$ib = $id_order[ $b->get_id() ] ?? 999;
		return $ia - $ib;
	} );
}

$assets_uri   = get_template_directory_uri() . '/beauty-care/assets';
$shop_url     = beauty_care_shop_permalink();
?>
<main class="bc-wishlist" data-current-page="wishlist">
	<section class="panner">
		<h1 class="y-u-text-center"><?php esc_html_e( 'المفضلة', 'beauty-care-theme' ); ?></h1>
		<div class="breadcrumbs container y-u-max-w-1200">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-care-theme' ); ?></a>
			<p><?php esc_html_e( 'المفضلة', 'beauty-care-theme' ); ?></p>
		</div>
	</section>

	<?php if ( ! empty( $wishlist_products ) ) : ?>
		<section class="store-section bc-wishlist-section">
			<div class="container y-u-max-w-1200">
				<ul class="products-grid" data-y="wishlist-products">
					<?php
					foreach ( $wishlist_products as $wc_product ) {
						$GLOBALS['product'] = $wc_product;
						wc_get_template_part( 'content', 'product' );
					}
					?>
				</ul>
			</div>
		</section>
	<?php else : ?>
		<div class="empty-state-container" data-y="wishlist-empty">
			<div class="empty-state">
				<img src="<?php echo esc_url( $assets_uri . '/empty-cart.png' ); ?>" alt="<?php esc_attr_e( 'لا توجد منتجات في المفضلة', 'beauty-care-theme' ); ?>" onerror="this.style.display='none'">
				<h3><?php esc_html_e( 'لا توجد منتجات في المفضلة', 'beauty-care-theme' ); ?></h3>
				<a href="<?php echo esc_url( $shop_url ); ?>" class="btn main-button"><?php esc_html_e( 'تصفح المنتجات', 'beauty-care-theme' ); ?></a>
			</div>
		</div>
	<?php endif; ?>
</main>

<?php get_footer(); ?>
