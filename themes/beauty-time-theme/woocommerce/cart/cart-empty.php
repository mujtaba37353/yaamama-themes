<?php
/**
 * Empty Cart — override
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

?>
<main>
	<section class="panner">
		<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-time-theme' ); ?></a> / <?php esc_html_e( 'سلة المشتريات', 'beauty-time-theme' ); ?></p>
	</section>
	<section class="cart-section">
		<div class="container y-u-max-w-1200 y-u-text-center">
			<p class="cart-empty woocommerce-info"><?php esc_html_e( 'سلة المشتريات فارغة.', 'beauty-time-theme' ); ?></p>
			<p class="return-to-shop">
				<a class="button wc-backward<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
					<?php esc_html_e( 'العودة للمتجر', 'beauty-time-theme' ); ?>
				</a>
			</p>
		</div>
	</section>
</main>
