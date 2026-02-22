<?php
/**
 * Cross-sells — Sweet House design.
 *
 * @package Sweet_House_Theme
 */

defined( 'ABSPATH' ) || exit;

if ( $cross_sells ) : ?>

	<div class="cross-sells">
		<?php woocommerce_product_loop_start(); ?>

			<?php foreach ( $cross_sells as $cross_sell ) : ?>
				<?php
					$post_object = get_post( $cross_sell->get_id() );
					setup_postdata( $GLOBALS['post'] = $post_object ); // phpcs:ignore
					wc_get_template_part( 'content', 'product' );
				?>
			<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>

	</div>
	<?php
endif;

wp_reset_postdata();
