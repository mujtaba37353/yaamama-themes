<?php
/**
 * Single Product — override
 * Markup from beauty-time/templates/products/single-product.html
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

/**
 * Hook: woocommerce_before_main_content
 */
do_action( 'woocommerce_before_main_content' );

while ( have_posts() ) {
	the_post();
	global $product;
	?>
	<main>
		<section class="panner panner-two">
			<?php woocommerce_breadcrumb(); ?>
		</section>
		<section class="products-section activities-section sub-products-section">
			<div class="container y-u-max-w-1200">
				<div class="products-grid">
					<div class="product-card">
						<div class="product-img">
							<?php
							/**
							 * Hook: woocommerce_before_single_product_summary
							 */
							do_action( 'woocommerce_before_single_product_summary' );
							?>
						</div>
						<div class="product-content">
							<?php
							/**
							 * Hook: woocommerce_single_product_summary
							 */
							do_action( 'woocommerce_single_product_summary' );
							?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
		/**
		 * Hook: woocommerce_after_single_product_summary
		 */
		do_action( 'woocommerce_after_single_product_summary' );
		?>
	</main>
	<?php
}

/**
 * Hook: woocommerce_after_main_content
 */
do_action( 'woocommerce_after_main_content' );

get_footer();
