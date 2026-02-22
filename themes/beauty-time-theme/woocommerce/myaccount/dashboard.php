<?php
/**
 * My Account Dashboard — override
 * Uses profile.html "تفاصيل الحساب" tab structure
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

$current_user = wp_get_current_user();
?>
<div class="main-content tab-content active" data-content="profile">
	<div class="account-form-card">
		<h2 class="section-title"><?php esc_html_e( 'المعلومات الشخصية', 'beauty-time-theme' ); ?></h2>
		<?php
		do_action( 'woocommerce_account_dashboard' );
		?>
		<?php
		do_action( 'woocommerce_account_dashboard_content' );
		?>
	</div>
</div>
