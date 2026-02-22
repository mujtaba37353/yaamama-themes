<?php
/**
 * My Account page - Stationary design override.
 *
 * @package stationary-theme
 */

defined( 'ABSPATH' ) || exit;

$current_user = get_user_by( 'id', get_current_user_id() );
$user_name    = $current_user ? $current_user->display_name : '';
?>

<div class="profile-section">
	<div class="container y-u-max-w-1200">
		<?php do_action( 'woocommerce_account_navigation' ); ?>
		<div class="content tab-content">
			<?php do_action( 'woocommerce_account_content' ); ?>
		</div>
	</div>
</div>
