<?php
/**
 * My Account navigation — Beauty Care design
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user = wp_get_current_user();
$logout_url   = wc_logout_url();

do_action( 'woocommerce_before_account_navigation' );
?>

<div class="sidbar">
	<div class="top">
		<div class="content">
			<span><?php esc_html_e( 'أهلاً،', 'beauty-care-theme' ); ?></span>
			<p><?php echo esc_html( $current_user->display_name ); ?></p>
		</div>
	</div>
	<div class="links">
		<?php
		foreach ( wc_get_account_menu_items() as $endpoint => $label ) {
			if ( 'customer-logout' === $endpoint ) {
				continue;
			}
			$url    = ( 'edit-address' === $endpoint )
			? wc_get_endpoint_url( 'edit-address', 'billing', wc_get_page_permalink( 'myaccount' ) )
			: wc_get_account_endpoint_url( $endpoint );
			$active = wc_is_current_account_menu_item( $endpoint );
			$class  = $active ? 'active' : '';
			printf(
				'<a href="%s" class="%s">%s</a>',
				esc_url( $url ),
				esc_attr( $class ),
				esc_html( $label )
			);
		}
		?>
		<a class="logout-link" href="<?php echo esc_url( $logout_url ); ?>"><?php esc_html_e( 'تسجيل الخروج', 'beauty-care-theme' ); ?></a>
	</div>
</div>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
