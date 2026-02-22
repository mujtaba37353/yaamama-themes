<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

// Only show this template if user is logged in
if ( ! is_user_logged_in() ) {
	return; // This should not happen as form-login.php should be shown instead
}

$theme_uri = get_template_directory_uri();
$khutaa_uri = $theme_uri . '/khutaa';
?>

<div class="account-main">
	<div class="account-container">
		<?php
		$current_user = wp_get_current_user();
		$display_name = $current_user->display_name ?: $current_user->user_login;
		?>
		<h1 class="account-welcome"><?php printf( esc_html__( 'مرحبا %s', 'khutaa-theme' ), esc_html( $display_name ) ); ?></h1>

		<div class="account-layout">
			<?php
			/**
			 * My Account navigation.
			 *
			 * @since 2.6.0
			 */
			do_action( 'woocommerce_account_navigation' );
			?>

			<div class="woocommerce-MyAccount-content account-content">
				<?php
				/**
				 * My Account content.
				 *
				 * @since 2.6.0
				 */
				do_action( 'woocommerce_account_content' );
				?>
			</div>
		</div>
	</div>
</div>
