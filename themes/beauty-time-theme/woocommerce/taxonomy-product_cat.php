<?php
/**
 * Product Category Template — override
 * Uses beauty-time/templates/services/sub-services.html structure
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

/**
 * Hook: woocommerce_before_main_content
 */
do_action( 'woocommerce_before_main_content' );

$current_category = get_queried_object();
if ( ! $current_category || ! isset( $current_category->term_id ) ) {
	return;
}
$category_id      = $current_category->term_id;
$category_name    = $current_category->name;
$category_desc    = $current_category->description;
$category_image_id = get_term_meta( $category_id, 'thumbnail_id', true );
$category_image   = $category_image_id ? wp_get_attachment_image_url( $category_image_id, 'full' ) : '';
$home_url         = home_url( '/' );
$services_page    = get_page_by_path( 'services' );
$services_url     = $services_page ? get_permalink( $services_page->ID ) : home_url( '/services' );
?>
<main>
	<section class="panner panner-two">
		<p><?php 
		// Use category description if available, otherwise use default message
		if ( $category_desc ) {
			echo esc_html( $category_desc );
		} else {
			// Default promotional message
			esc_html_e( 'استمتعي باشراقة مثالية وبشرة ناعمة وصحية مع بيوتي تايم للعناية بالبشرة', 'beauty-time-theme' );
		}
		?></p>
	</section>

	<?php
	/**
	 * Hook: woocommerce_archive_description
	 */
	do_action( 'woocommerce_archive_description' );
	?>

	<?php if ( $category_name || $category_desc || $category_image ) : ?>
		<section class="sub-services-section">
			<div class="container y-u-max-w-1200">
				<div class="right">
					<?php if ( $category_name ) : ?>
						<h2><?php echo esc_html( $category_name ); ?></h2>
					<?php endif; ?>
					<?php if ( $category_desc ) : ?>
						<p><?php echo wp_kses_post( $category_desc ); ?></p>
					<?php endif; ?>
				</div>
				<?php if ( $category_image ) : ?>
					<img src="<?php echo esc_url( $category_image ); ?>" alt="<?php echo esc_attr( $category_name ); ?>">
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( woocommerce_product_loop() ) : ?>
		<?php
		/**
		 * Hook: woocommerce_before_shop_loop
		 */
		do_action( 'woocommerce_before_shop_loop' );
		?>

		<section class="sub-services-section-grid">
			<div class="container y-u-max-w-1200">
				<div class="grid">
					<?php
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
