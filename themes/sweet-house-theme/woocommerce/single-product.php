<?php
/**
 * Single Product — design from beauty-care-theme/beauty-care (product-details).
 *
 * @package Sweet_House_Theme
 * @see     beauty-care-theme/beauty-care/templates/product-details/product-details.html
 */

defined( 'ABSPATH' ) || exit;

get_header();

do_action( 'woocommerce_before_main_content' );
?>

<header data-y="design-header" class="single-product-design-header">
	<img src="<?php echo esc_url( function_exists( 'sweet_house_asset_uri' ) ? sweet_house_asset_uri( 'assets/panner.png' ) : get_template_directory_uri() . '/sweet-house/assets/panner.png' ); ?>" alt="<?php esc_attr_e( 'بانر سويت هاوس - المنتج', 'sweet-house-theme' ); ?>" class="panner-img" />
</header>

<main data-y="main">
	<section class="details-section">
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<?php wc_get_template_part( 'content', 'single-product' ); ?>
		<?php endwhile; ?>
	</section>

	<section class="products-section">
		<div class="container y-u-max-w-1200">
			<h2><?php esc_html_e( 'منتجاتنا المميزة', 'sweet-house-theme' ); ?></h2>
			<?php woocommerce_output_related_products(); ?>
		</div>
	</section>
</main>

<?php
do_action( 'woocommerce_after_main_content' );
do_action( 'woocommerce_sidebar' );
get_footer();
