<?php
/**
 * Empty cart page - Beauty Care design override
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 */

defined( 'ABSPATH' ) || exit;

$assets_uri = get_template_directory_uri() . '/beauty-care/assets';
$shop_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
?>
<div class="empty-state-container">
	<div class="empty-state">
		<img src="<?php echo esc_url( $assets_uri . '/empty-cart.png' ); ?>" alt="<?php esc_attr_e( 'لا توجد منتجات في السلة', 'beauty-care-theme' ); ?>" onerror="this.style.display='none'">
		<h3><?php esc_html_e( 'لا توجد منتجات في السلة', 'beauty-care-theme' ); ?></h3>
		<a href="<?php echo esc_url( $shop_url ); ?>" class="btn main-button"><?php esc_html_e( 'تسوق الآن', 'beauty-care-theme' ); ?></a>
	</div>
</div>
