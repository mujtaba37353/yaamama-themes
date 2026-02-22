<?php
defined( 'ABSPATH' ) || exit;

$shop_url = stationary_shop_permalink();
$au       = stationary_base_uri() . '/assets';
?>
<div class="empty-state-container">
	<div class="empty-state">
		<img src="<?php echo esc_url( $au . '/empty-cart.png' ); ?>" alt="<?php esc_attr_e( 'لا توجد منتجات في السلة', 'stationary-theme' ); ?>" onerror="this.style.display='none'">
		<h3><?php esc_html_e( 'لا توجد منتجات في السلة', 'stationary-theme' ); ?></h3>
		<a href="<?php echo esc_url( $shop_url ); ?>" class="btn main-button"><?php esc_html_e( 'تسوق الآن', 'stationary-theme' ); ?></a>
	</div>
</div>
