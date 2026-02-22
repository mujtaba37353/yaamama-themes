<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<aside class="account-sidebar woocommerce-MyAccount-navigation" aria-label="<?php esc_attr_e( 'Account pages', 'woocommerce' ); ?>">
	<?php
	$menu_items = wc_get_account_menu_items();
	
	// Remove dashboard and downloads from menu
	unset( $menu_items['dashboard'] );
	unset( $menu_items['downloads'] );
	
	// Translate menu items to Arabic
	$arabic_labels = array(
		'orders'          => __( 'الطلبات', 'khutaa-theme' ),
		'edit-address'    => __( 'العناوين', 'khutaa-theme' ),
		'payment-methods' => __( 'طرق الدفع', 'khutaa-theme' ),
		'edit-account'    => __( 'تفاصيل الحساب', 'khutaa-theme' ),
		'customer-logout' => __( 'تسجيل الخروج', 'khutaa-theme' ),
	);
	
	$current_endpoint = wc_get_account_menu_item_classes( '' );
	
	foreach ( $menu_items as $endpoint => $label ) :
		// Use Arabic label if available
		if ( isset( $arabic_labels[ $endpoint ] ) ) {
			$label = $arabic_labels[ $endpoint ];
		}
		$classes = wc_get_account_menu_item_classes( $endpoint );
		$is_active = strpos( $classes, 'is-active' ) !== false;
		$icon = '';
		
		// Set icons based on endpoint
		switch ( $endpoint ) {
			case 'dashboard':
				$icon = 'fa-user';
				break;
			case 'orders':
				$icon = 'fa-shopping-bag';
				break;
			case 'downloads':
				$icon = 'fa-download';
				break;
			case 'edit-address':
				$icon = 'fa-map-marker-alt';
				break;
			case 'payment-methods':
				$icon = 'fa-credit-card';
				break;
			case 'edit-account':
				$icon = 'fa-user-edit';
				break;
			case 'customer-logout':
				$icon = 'fa-sign-out-alt';
				break;
			default:
				$icon = 'fa-circle';
		}
		?>
		<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" 
		   class="sidebar-item <?php echo esc_attr( $classes ); ?> <?php echo $is_active ? 'active' : ''; ?>"
		   <?php echo wc_is_current_account_menu_item( $endpoint ) ? 'aria-current="page"' : ''; ?>>
			<i class="fas <?php echo esc_attr( $icon ); ?>"></i>
			<span><?php echo esc_html( $label ); ?></span>
		</a>
	<?php endforeach; ?>
</aside>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
