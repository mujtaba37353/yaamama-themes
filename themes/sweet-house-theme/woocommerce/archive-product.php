<?php
/**
 * Shop / Product archive — Sweet House layout.
 *
 * @package Sweet_House_Theme
 * @see     design: sweet-house/templates/products/layout.html
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * woocommerce_before_main_content: wrapper + breadcrumb (theme overrides wrapper).
 */
do_action( 'woocommerce_before_main_content' );
?>

<main data-y="main" class="main-page">
	<div class="main-container">
		<?php do_action( 'woocommerce_archive_description' ); ?>

		<?php if ( function_exists( 'woocommerce_breadcrumb' ) ) : ?>
			<nav data-y="breadcrumb" class="woocommerce-breadcrumb-wrap" aria-label="<?php esc_attr_e( 'مسار الصفحة', 'sweet-house-theme' ); ?>">
				<?php woocommerce_breadcrumb(); ?>
			</nav>
		<?php endif; ?>

		<?php if ( woocommerce_product_loop() ) : ?>
			<?php
			$current_orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : '';
			$sort_labels = array(
				'rating'     => __( 'الأعلى تقييماً', 'sweet-house-theme' ),
				'price-desc' => __( 'السعر من الأعلى إلى الأقل', 'sweet-house-theme' ),
				'price'      => __( 'السعر من الأقل إلى الأعلى', 'sweet-house-theme' ),
			);
			$current_sort_label = ! empty( $current_orderby ) && isset( $sort_labels[ $current_orderby ] )
				? $sort_labels[ $current_orderby ]
				: __( 'ترتيب حسب', 'sweet-house-theme' );
			?>
			<section data-y="filter" class="new-filter-bar">
				<div class="filter-right">
					<div class="custom-dropdown products-dropdown">
						<div class="dropdown-trigger products-trigger">
							<?php echo esc_html__( 'المنتجات', 'sweet-house-theme' ); ?>
						</div>
					</div>
				</div>
				<div class="filter-left">
					<div class="custom-dropdown sort-dropdown" id="shop-sort-dropdown">
						<div class="dropdown-trigger sort-trigger">
							<span class="current-sort"><?php echo esc_html( $current_sort_label ); ?></span>
							<i class="fa-solid fa-angle-down dropdown-arrow"></i>
						</div>
						<ul class="dropdown-options">
							<li data-sort="rating"<?php echo $current_orderby === 'rating' ? ' class="selected"' : ''; ?>><?php echo esc_html__( 'الأعلى تقييماً', 'sweet-house-theme' ); ?></li>
							<li data-sort="price-desc"<?php echo $current_orderby === 'price-desc' ? ' class="selected"' : ''; ?>><?php echo esc_html__( 'السعر من الأعلى إلى الأقل', 'sweet-house-theme' ); ?></li>
							<li data-sort="price"<?php echo $current_orderby === 'price' ? ' class="selected"' : ''; ?>><?php echo esc_html__( 'السعر من الأقل إلى الأعلى', 'sweet-house-theme' ); ?></li>
						</ul>
					</div>
				</div>
			</section>

			<?php do_action( 'woocommerce_before_shop_loop' ); ?>

			<div class="section section1">
				<?php woocommerce_product_loop_start(); ?>

				<?php if ( wc_get_loop_prop( 'total' ) ) : ?>
					<?php while ( have_posts() ) : ?>
						<?php the_post(); ?>
						<?php do_action( 'woocommerce_shop_loop' ); ?>
						<?php wc_get_template_part( 'content', 'product' ); ?>
					<?php endwhile; ?>
				<?php endif; ?>

				<?php woocommerce_product_loop_end(); ?>
			</div>

			<?php do_action( 'woocommerce_after_shop_loop' ); ?>
		<?php else : ?>
			<?php do_action( 'woocommerce_no_products_found' ); ?>
		<?php endif; ?>
	</div>
</main>

<?php
do_action( 'woocommerce_after_main_content' );
get_footer( 'shop' );
