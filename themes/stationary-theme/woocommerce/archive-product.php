<?php
defined( 'ABSPATH' ) || exit;

get_header();

$terms = array();
if ( function_exists( 'get_terms' ) ) {
	$terms = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
		'exclude'    => array( (int) get_option( 'default_product_cat', 0 ) ),
		'number'     => 6,
	) );
	if ( is_wp_error( $terms ) ) {
		$terms = array();
	}
}

$current_cat_slug = '';
if ( function_exists( 'is_product_category' ) && is_product_category() ) {
	$q = get_queried_object();
	if ( $q && isset( $q->slug ) ) {
		$current_cat_slug = $q->slug;
	}
}

$search_query = get_search_query();
$shop_url     = stationary_shop_permalink();
$offers_url   = add_query_arg( 'on_sale', '1', $shop_url );

$base_link = $shop_url;
if ( ! empty( $search_query ) ) {
	$base_link = add_query_arg( 's', $search_query, $shop_url );
}

$default_cats = array(
	array( 'name' => 'عروض وتخفيضات', 'slug' => '', 'url' => $offers_url ),
	array( 'name' => 'كراسات و منتجات ورقية', 'slug' => '', 'url' => $shop_url ),
	array( 'name' => 'أقلام و أدوات الكتابة', 'slug' => '', 'url' => $shop_url ),
	array( 'name' => 'آلات حاسبة', 'slug' => '', 'url' => $shop_url ),
	array( 'name' => 'أطقم المكتب', 'slug' => '', 'url' => $shop_url ),
	array( 'name' => 'أدوات مدرسية', 'slug' => '', 'url' => $shop_url ),
);

if ( ! empty( $terms ) ) {
	foreach ( $terms as $i => $t ) {
		if ( isset( $default_cats[ $i ] ) ) {
			$default_cats[ $i ]['name'] = $t->name;
			$default_cats[ $i ]['slug'] = $t->slug;
			$default_cats[ $i ]['url']  = get_term_link( $t );
			if ( is_wp_error( $default_cats[ $i ]['url'] ) ) {
				$default_cats[ $i ]['url'] = $shop_url;
			}
		}
	}
}
?>

<main>
	<section class="panner panner-image y-u-m-b-0 container y-u-max-w-1200">
		<h1 class="y-u-text-center">
			<?php
			if ( ! empty( $search_query ) ) {
				printf( esc_html__( 'نتائج البحث عن "%s"', 'stationary-theme' ), esc_html( $search_query ) );
			} elseif ( isset( $_GET['on_sale'] ) && '1' === $_GET['on_sale'] ) {
				esc_html_e( 'العروض', 'stationary-theme' );
			} else {
				esc_html_e( 'اكتشف منتجاتنا', 'stationary-theme' );
			}
			?>
		</h1>
	</section>
	<section class="breadcrumbs container y-u-max-w-1200 y-u-m-b-0 ">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'stationary-theme' ); ?></a>
		<p><?php esc_html_e( 'المنتجات', 'stationary-theme' ); ?></p>
	</section>

	<section class="products-section store-section">
		<div class="container y-u-max-w-1200">
			<div class="categories">
				<?php
				$on_sale = isset( $_GET['on_sale'] ) && '1' === $_GET['on_sale'];
				foreach ( $default_cats as $cat ) :
					$is_active = false;
					if ( 'عروض وتخفيضات' === $cat['name'] ) {
						$is_active = $on_sale;
					} elseif ( $current_cat_slug && $current_cat_slug === $cat['slug'] ) {
						$is_active = true;
					} elseif ( ! $current_cat_slug && ! $on_sale && 'كراسات و منتجات ورقية' === $cat['name'] ) {
						$is_active = true;
					}
					?>
					<a href="<?php echo esc_url( $cat['url'] ); ?>" class="cat-link <?php echo $is_active ? 'active' : ''; ?>"><?php echo esc_html( $cat['name'] ); ?></a>
				<?php endforeach; ?>
			</div>
			<div class="custom-dropdown sort-dropdown">
				<form method="get" class="woocommerce-ordering">
					<?php if ( ! empty( $search_query ) ) : ?>
						<input type="hidden" name="s" value="<?php echo esc_attr( $search_query ); ?>" />
					<?php endif; ?>
					<?php if ( ! empty( $_GET['on_sale'] ) ) : ?>
						<input type="hidden" name="on_sale" value="1" />
					<?php endif; ?>
					<div class="dropdown-trigger">
						<span class="current-sort"><?php esc_html_e( 'تصنيف حسب', 'stationary-theme' ); ?></span>
						<i class="fa-solid fa-angle-down dropdown-arrow"></i>
					</div>
					<ul class="dropdown-options">
						<li><a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'rating' ), $base_link ) ); ?>"><?php esc_html_e( 'الأعلى تقييما', 'stationary-theme' ); ?></a></li>
						<li><a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'price-desc' ), $base_link ) ); ?>"><?php esc_html_e( 'السعر من الأعلى إلى الأقل', 'stationary-theme' ); ?></a></li>
						<li><a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'price' ), $base_link ) ); ?>"><?php esc_html_e( 'السعر من الأقل إلى الأعلى', 'stationary-theme' ); ?></a></li>
						<li><a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'date' ), $base_link ) ); ?>"><?php esc_html_e( 'الأحدث', 'stationary-theme' ); ?></a></li>
					</ul>
				</form>
			</div>

			<?php if ( woocommerce_product_loop() ) : ?>
				<?php
				// #region agent log
				$log_path = 'c:\\Users\\mujtaba\\Local Sites\\yamama-platform\\.cursor\\debug.log';
				global $wp_query;
				$payload  = array(
					'runId'        => 'initial',
					'hypothesisId' => 'H3',
					'location'     => 'woocommerce/archive-product.php:118',
					'message'      => 'Shop loop probe',
					'data'         => array(
						'path'         => isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '',
						'havePosts'    => have_posts(),
						'foundPosts'   => isset( $wp_query->found_posts ) ? (int) $wp_query->found_posts : null,
						'maxNumPages'  => isset( $wp_query->max_num_pages ) ? (int) $wp_query->max_num_pages : null,
						'isSearch'     => is_search(),
						'onSaleFilter' => isset( $_GET['on_sale'] ) ? sanitize_text_field( wp_unslash( $_GET['on_sale'] ) ) : '',
					),
					'timestamp'    => round( microtime( true ) * 1000 ),
				);
				@file_put_contents( $log_path, wp_json_encode( $payload ) . PHP_EOL, FILE_APPEND );
				// #endregion
				?>
				<ul class="grid">
					<?php
					while ( have_posts() ) {
						the_post();
						$product = wc_get_product( get_the_ID() );
						if ( $product ) {
							get_template_part( 'stationary/partials/product-card', null, array( 'product' => $product, 'show_sale' => $product->is_on_sale() ) );
						}
					}
					?>
				</ul>
				<?php woocommerce_pagination(); ?>
			<?php else : ?>
				<div class="empty-state-container">
					<div class="empty-state">
						<img src="<?php echo esc_url( stationary_asset_url( 'empty-cart.png' ) ); ?>" alt="" onerror="this.style.display='none'">
						<h3><?php esc_html_e( 'لا توجد منتجات لعرضها.', 'stationary-theme' ); ?></h3>
						<a href="<?php echo esc_url( $shop_url ); ?>" class="btn main-button"><?php esc_html_e( 'تسوق الآن', 'stationary-theme' ); ?></a>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
