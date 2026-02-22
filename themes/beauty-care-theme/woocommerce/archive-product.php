<?php
defined( 'ABSPATH' ) || exit;

get_header();

$assets_uri = get_template_directory_uri() . '/beauty-care/assets';

$product_categories = array();
if ( function_exists( 'get_terms' ) ) {
	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'exclude'    => array( (int) get_option( 'default_product_cat', 0 ) ),
			'number'     => 4,
		)
	);
	if ( ! is_wp_error( $terms ) ) {
		$product_categories = $terms;
	}
}

if ( empty( $product_categories ) ) {
	$product_categories = array(
		(object) array( 'name' => 'العناية بالبشرة', 'slug' => 'skin-care' ),
		(object) array( 'name' => 'العناية بالشعر', 'slug' => 'hair-care' ),
		(object) array( 'name' => 'العناية بالجسم', 'slug' => 'body-care' ),
		(object) array( 'name' => 'منتجات طبيعية', 'slug' => 'natural-products' ),
	);
}

$current_cat_slug = '';
if ( function_exists( 'is_product_category' ) && is_product_category() ) {
	$q = get_queried_object();
	if ( $q && isset( $q->slug ) ) {
		$current_cat_slug = $q->slug;
	}
}

$search_query    = get_search_query();
$shop_url       = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$assets_uri     = get_template_directory_uri() . '/beauty-care/assets';

// قاعدة لروابط التصفية مع الحفاظ على البحث
$base_link_args = array();
if ( ! empty( $search_query ) ) {
	$base_link_args['s'] = $search_query;
}
?>

<main>
	<section class="panner">
		<h1 class="y-u-text-center">
			<?php
			if ( ! empty( $search_query ) ) {
				/* translators: %s: search query */
				echo esc_html( sprintf( __( 'نتائج البحث عن "%s"', 'beauty-care-theme' ), $search_query ) );
			} else {
				esc_html_e( 'المتجر', 'beauty-care-theme' );
			}
			?>
		</h1>
		<div class="breadcrumbs container y-u-max-w-1200">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-care-theme' ); ?></a>
			<p><?php echo ! empty( $search_query ) ? esc_html( sprintf( __( 'نتائج البحث عن "%s"', 'beauty-care-theme' ), $search_query ) ) : esc_html__( 'المتجر', 'beauty-care-theme' ); ?></p>
		</div>
	</section>

	<section class="store-section">
		<div class="container y-u-max-w-1200 services-grid y-u-grid y-u-grid-auto-300 y-u-row-gap-24 y-u-col-gap-24">
			<div class="categories">
				<button class="<?php echo empty( $current_cat_slug ) ? 'active' : ''; ?>" data-cat=""><?php esc_html_e( 'الكل', 'beauty-care-theme' ); ?></button>
				<?php foreach ( $product_categories as $cat ) : ?>
					<button class="<?php echo ( ! empty( $current_cat_slug ) && $current_cat_slug === $cat->slug ) ? 'active' : ''; ?>" data-cat="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></button>
				<?php endforeach; ?>
			</div>

			<div class="custom-dropdown sort-dropdown">
				<form method="get" class="woocommerce-ordering">
					<?php if ( ! empty( $search_query ) ) : ?>
						<input type="hidden" name="s" value="<?php echo esc_attr( $search_query ); ?>" />
					<?php endif; ?>
					<div class="dropdown-trigger btn-white">
						<span class="current-sort"><?php esc_html_e( 'تصنيف حسب', 'beauty-care-theme' ); ?></span>
						<i class="fa-solid fa-chevron-down dropdown-arrow"></i>
					</div>
					<ul class="dropdown-options">
						<li><a href="<?php echo esc_url( add_query_arg( array_merge( $base_link_args, array( 'orderby' => 'date' ) ) ) ); ?>"><?php esc_html_e( 'الأحدث', 'beauty-care-theme' ); ?></a></li>
						<li><a href="<?php echo esc_url( add_query_arg( array_merge( $base_link_args, array( 'orderby' => 'popularity' ) ) ) ); ?>"><?php esc_html_e( 'الأكثر مبيعاً', 'beauty-care-theme' ); ?></a></li>
						<li><a href="<?php echo esc_url( add_query_arg( array_merge( $base_link_args, array( 'orderby' => 'price-desc' ) ) ) ); ?>"><?php esc_html_e( 'الأعلى سعراً', 'beauty-care-theme' ); ?></a></li>
						<li><a href="<?php echo esc_url( add_query_arg( array_merge( $base_link_args, array( 'orderby' => 'price' ) ) ) ); ?>"><?php esc_html_e( 'الأقل سعراً', 'beauty-care-theme' ); ?></a></li>
					</ul>
				</form>
			</div>

			<?php if ( woocommerce_product_loop() ) : ?>
				<ul class="products-grid">
					<?php
					while ( have_posts() ) {
						the_post();
						wc_get_template_part( 'content', 'product' );
					}
					?>
				</ul>

				<?php
				woocommerce_pagination();
			else :
				$empty_message = ! empty( $search_query )
					? __( 'لا توجد منتجات مطابقة لبحثك.', 'beauty-care-theme' )
					: __( 'لا توجد منتجات لعرضها.', 'beauty-care-theme' );
				$empty_btn_text = ! empty( $search_query )
					? __( 'تصفح المنتجات', 'beauty-care-theme' )
					: __( 'تسوق الآن', 'beauty-care-theme' );
				?>
				<div class="empty-state-container">
					<div class="empty-state">
						<img src="<?php echo esc_url( $assets_uri . '/empty-cart.png' ); ?>" alt="" onerror="this.style.display='none'">
						<h3><?php echo esc_html( $empty_message ); ?></h3>
						<a href="<?php echo esc_url( $shop_url ); ?>" class="btn main-button"><?php echo esc_html( $empty_btn_text ); ?></a>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
