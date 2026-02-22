<?php
/**
 * My Account Dashboard - Stationary design override.
 *
 * @package stationary-theme
 */

defined( 'ABSPATH' ) || exit;

$orders_url   = wc_get_endpoint_url( 'orders', '', wc_get_page_permalink( 'myaccount' ) );
$address_url  = wc_get_endpoint_url( 'edit-address', '', wc_get_page_permalink( 'myaccount' ) );
$account_url  = wc_get_endpoint_url( 'edit-account', '', wc_get_page_permalink( 'myaccount' ) );
?>

<p>
	<?php
	printf(
		/* translators: 1: user display name 2: logout url */
		wp_kses_post( __( 'مرحباً %1$s (ليس أنت؟ <a href="%2$s">تسجيل الخروج</a>)', 'stationary-theme' ) ),
		'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
		esc_url( wc_logout_url() )
	);
	?>
</p>

<p>
	<?php
	$dashboard_desc = __( 'من لوحة التحكم يمكنك عرض <a href="%1$s">طلباتك الأخيرة</a>، إدارة <a href="%2$s">عناوين الفواتير والشحن</a>، و<a href="%3$s">تعديل كلمة المرور وتفاصيل حسابك</a>.', 'stationary-theme' );
	printf(
		wp_kses( $dashboard_desc, array( 'a' => array( 'href' => array() ) ) ),
		esc_url( $orders_url ),
		esc_url( $address_url ),
		esc_url( $account_url )
	);
	?>
</p>

<?php
do_action( 'woocommerce_account_dashboard' );
do_action( 'woocommerce_before_my_account' );
do_action( 'woocommerce_after_my_account' );
