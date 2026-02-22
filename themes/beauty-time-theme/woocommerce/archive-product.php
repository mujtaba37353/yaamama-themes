<?php
/**
 * WooCommerce Shop Archive — override
 * Uses beauty-time product-card markup. See beauty-time/components/product-card.html
 *
 * @package Beauty_Time_Theme
 * @see https://woocommerce.github.io/code-reference/files/woocommerce-templates-archive-product.html
 */

defined( 'ABSPATH' ) || exit;

get_header();

/**
 * Hook: woocommerce_before_main_content
 */
do_action( 'woocommerce_before_main_content' );
?>
<main>
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<section class="panner">
			<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
		</section>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_archive_description
	 */
	do_action( 'woocommerce_archive_description' );
	?>

	<?php if ( woocommerce_product_loop() ) : ?>
		<?php
		/**
		 * Hook: woocommerce_before_shop_loop
		 */
		do_action( 'woocommerce_before_shop_loop' );
		?>

		<section class="products-section">
			<div class="container y-u-max-w-1200">
				<div class="products-grid">
					<?php
					if ( wc_get_loop_prop( 'is_shortcode' ) ) {
						$columns = absint( wc_get_loop_prop( 'columns' ) );
						wc_set_loop_prop( 'columns', $columns );
					}

					while ( have_posts() ) {
						the_post();
						wc_get_template_part( 'content', 'product' );
					}
					?>
				</div>
			</div>
		</section>

		<?php
		/**
		 * Hook: woocommerce_after_shop_loop
		 */
		do_action( 'woocommerce_after_shop_loop' );
		?>

		<?php
		/**
		 * Hook: woocommerce_pagination
		 */
		do_action( 'woocommerce_pagination' );
		?>

	<?php else : ?>
		<?php
		/**
		 * Hook: woocommerce_no_products_found
		 */
		do_action( 'woocommerce_no_products_found' );
		?>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_after_main_content
	 */
	do_action( 'woocommerce_after_main_content' );
	?>
</main>

<?php
get_footer();
