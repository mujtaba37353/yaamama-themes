<?php
get_header();

// عرض المنتجات التي لها سعر مخفّض (on sale) فقط
$sale_ids = array();
if ( function_exists( 'wc_get_product_ids_on_sale' ) ) {
	$sale_ids = wc_get_product_ids_on_sale();
	$sale_ids = array_filter( array_map( 'absint', (array) $sale_ids ) );
}

// إذا الكاش فارغ أو قليل، استعلام بديل عن المنتجات التي لديها _sale_price
if ( empty( $sale_ids ) && function_exists( 'wc_get_product_ids_on_sale' ) ) {
	delete_transient( 'wc_products_onsale' );
	$sale_ids = wc_get_product_ids_on_sale();
	$sale_ids = array_filter( array_map( 'absint', (array) $sale_ids ) );
}

// استعلام بديل عند عدم وجود نتائج أو عند وجود نتيجة واحدة فقط (كاش وووكومرس قد يكون غير محدّث)
$use_fallback = empty( $sale_ids ) || count( $sale_ids ) < 2;
if ( $use_fallback ) {
	$fallback = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 50,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'meta_query'     => array(
				array(
					'key'     => '_sale_price',
					'value'   => 0,
					'compare' => '>',
					'type'    => 'NUMERIC',
				),
			),
		)
	);
	if ( $fallback->have_posts() ) {
		$sale_ids = array_unique( array_merge( $sale_ids, wp_list_pluck( $fallback->posts, 'ID' ) ) );
		wp_reset_postdata();
	}
}

$sale_query = new WP_Query(
	array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'post__in'       => $sale_ids ? $sale_ids : array( 0 ),
		'posts_per_page' => $sale_ids ? max( count( $sale_ids ), 12 ) : 12,
		'orderby'        => 'post__in',
		'no_found_rows'  => true,
	)
);
?>

<header data-y="design-header">
	<img src="<?php echo esc_url( dark_theme_asset_uri( 'assets/header2.png' ) ); ?>" alt="" class="design-img y-u-w-100" />
</header>

<main data-y="main" class="">
	<div class="y-main-container">
		<div data-y="breadcrumb">
			<nav aria-label="breadcrumb" class="y-breadcrumb-container">
				<ol class="y-breadcrumb"></ol>
			</nav>
		</div>
		<section data-y="top-products-logo"></section>
		<section data-y="filter"></section>
		<ul class="products">
			<?php if ( $sale_query->have_posts() ) : ?>
				<?php while ( $sale_query->have_posts() ) : ?>
					<?php $sale_query->the_post(); ?>
					<?php dark_theme_render_product_card( wc_get_product( get_the_ID() ) ); ?>
				<?php endwhile; ?>
			<?php else : ?>
				<p>لا توجد عروض حالياً.</p>
			<?php endif; ?>
		</ul>
	</div>
</main>

<?php
wp_reset_postdata();
get_footer();
?>
