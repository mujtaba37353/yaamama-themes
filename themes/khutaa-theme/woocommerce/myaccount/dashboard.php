<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>

<div class="content-section" id="dashboard-content">
	<h2 class="section-title"><?php esc_html_e( 'لوحة التحكم', 'khutaa-theme' ); ?></h2>
	
	<div class="dashboard-welcome">
		<p>
			<?php
			printf(
				/* translators: 1: user display name 2: logout url */
				wp_kses( __( 'مرحبا %1$s (ليس %1$s? <a href="%2$s">تسجيل الخروج</a>)', 'khutaa-theme' ), $allowed_html ),
				'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
				esc_url( wc_logout_url() )
			);
			?>
		</p>
	</div>

	<div class="dashboard-links">
		<p>
			<?php
			/* translators: 1: Orders URL 2: Address URL 3: Account URL. */
			$dashboard_desc = __( 'من لوحة تحكم حسابك يمكنك عرض <a href="%1$s">الطلبات الأخيرة</a>، إدارة <a href="%2$s">عنوان الفوترة</a>، و <a href="%3$s">تعديل كلمة المرور وتفاصيل الحساب</a>.', 'khutaa-theme' );
			if ( wc_shipping_enabled() ) {
				/* translators: 1: Orders URL 2: Addresses URL 3: Account URL. */
				$dashboard_desc = __( 'من لوحة تحكم حسابك يمكنك عرض <a href="%1$s">الطلبات الأخيرة</a>، إدارة <a href="%2$s">عناوين الشحن والفوترة</a>، و <a href="%3$s">تعديل كلمة المرور وتفاصيل الحساب</a>.', 'khutaa-theme' );
			}
			printf(
				wp_kses( $dashboard_desc, $allowed_html ),
				esc_url( wc_get_endpoint_url( 'orders' ) ),
				esc_url( wc_get_endpoint_url( 'edit-address' ) ),
				esc_url( wc_get_endpoint_url( 'edit-account' ) )
			);
			?>
		</p>
	</div>

	<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );
	?>
</div>
