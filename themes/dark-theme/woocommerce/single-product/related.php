<?php
/**
 * Related Products — dark-theme: عنوان "تسوق أكثر" مع class section-title
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $related_products ) :
	if ( function_exists( 'wp_increase_content_media_count' ) ) {
		$content_media_count = wp_increase_content_media_count( 0 );
		if ( $content_media_count < wp_omit_loading_attr_threshold() ) {
			wp_increase_content_media_count( wp_omit_loading_attr_threshold() - $content_media_count );
		}
	}
	?>

	<section class="related products">
		<?php
		$heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'تسوق أكثر', 'woocommerce' ) );
		if ( $heading ) :
			?>
			<h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<?php woocommerce_product_loop_start(); ?>
			<?php foreach ( $related_products as $related_product ) : ?>
				<?php
				$post_object = get_post( $related_product->get_id() );
				setup_postdata( $GLOBALS['post'] = $post_object ); // phpcs:ignore
				if ( function_exists( 'dark_theme_render_product_card' ) ) {
					dark_theme_render_product_card( $related_product );
				} else {
					wc_get_template_part( 'content', 'product' );
				}
				?>
			<?php endforeach; ?>
		<?php woocommerce_product_loop_end(); ?>
	</section>
	<?php
endif;

wp_reset_postdata();
