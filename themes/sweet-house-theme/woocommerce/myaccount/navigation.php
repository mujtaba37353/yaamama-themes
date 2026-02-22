<?php
/**
 * My Account navigation — Sweet House design (sidebar with icons)
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$endpoint_icons = array(
	'dashboard'       => 'fa-regular fa-user',
	'orders'          => 'fa-regular fa-file-lines',
	'edit-address'    => 'fa-solid fa-location-dot',
	'customer-logout' => 'fa-solid fa-right-from-bracket',
);

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation sweet-house-account-nav" aria-label="<?php esc_attr_e( 'صفحات الحساب', 'sweet-house-theme' ); ?>">
	<div class="sidebar">
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<?php
			$classes = wc_get_account_menu_item_classes( $endpoint );
			$is_active = wc_is_current_account_menu_item( $endpoint );
			$icon = isset( $endpoint_icons[ $endpoint ] ) ? $endpoint_icons[ $endpoint ] : 'fa-regular fa-circle';
			?>
			<div class="sidebar-item <?php echo $is_active ? 'active' : ''; ?>">
				<i class="<?php echo esc_attr( $icon ); ?>"></i>
				<span>
					<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="y-t-text-decoration-none y-u-me-2 y-t-text-dark" <?php echo $is_active ? 'aria-current="page"' : ''; ?>><?php echo esc_html( $label ); ?></a>
				</span>
			</div>
		<?php endforeach; ?>
	</div>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
