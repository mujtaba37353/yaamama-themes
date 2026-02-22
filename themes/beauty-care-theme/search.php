<?php
/**
 * Search results - Beauty Care
 */
defined( 'ABSPATH' ) || exit;

get_header();

$search_query = get_search_query();
$assets_uri   = get_template_directory_uri() . '/beauty-care/assets';
$shop_url     = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

// Query products for search (WooCommerce product search)
$product_query = null;
if ( class_exists( 'WooCommerce' ) ) {
	$product_query = new WP_Query(
		array(
			'post_type'      => 'product',
			's'              => $search_query,
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		)
	);
}

$has_products = $product_query && $product_query->have_posts();
?>

<main>
	<section class="panner">
		<h1 class="y-u-text-center">
			<?php
			/* translators: %s: search query */
			echo esc_html( sprintf( __( 'نتائج البحث عن "%s"', 'beauty-care-theme' ), $search_query ) );
			?>
		</h1>
		<div class="breadcrumbs container y-u-max-w-1200">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-care-theme' ); ?></a>
			<p><?php esc_html_e( 'نتائج البحث', 'beauty-care-theme' ); ?></p>
		</div>
	</section>

	<section class="store-section">
		<div class="container y-u-max-w-1200">
			<?php if ( $has_products ) : ?>
				<ul class="products-grid">
					<?php
					while ( $product_query->have_posts() ) {
						$product_query->the_post();
						wc_get_template_part( 'content', 'product' );
					}
					wp_reset_postdata();
					?>
				</ul>
			<?php else : ?>
				<div class="empty-state-container">
					<div class="empty-state">
						<img src="<?php echo esc_url( $assets_uri . '/empty-fav.png' ); ?>" alt="" onerror="this.style.display='none'">
						<h3><?php esc_html_e( 'لا توجد نتائج للبحث', 'beauty-care-theme' ); ?></h3>
						<a href="<?php echo esc_url( $shop_url ); ?>" class="btn main-button"><?php esc_html_e( 'تسوق الآن', 'beauty-care-theme' ); ?></a>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
