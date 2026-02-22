<?php
/**
 * My Account navigation - Stationary design override.
 *
 * @package stationary-theme
 */

defined( 'ABSPATH' ) || exit;

$current_user = get_user_by( 'id', get_current_user_id() );
$user_name    = $current_user ? $current_user->display_name : '';

do_action( 'woocommerce_before_account_navigation' );
?>

<div class="sidbar">
	<div class="top">
		<div class="content">
			<span><?php esc_html_e( 'أهلاً،', 'stationary-theme' ); ?></span>
			<p><?php echo esc_html( $user_name ); ?></p>
		</div>
	</div>
	<div class="links">
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<?php if ( 'customer-logout' === $endpoint ) : ?>
				<a href="<?php echo esc_url( wc_logout_url() ); ?>" class="logout-link"><?php echo esc_html( $label ); ?></a>
			<?php else : ?>
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="<?php echo wc_is_current_account_menu_item( $endpoint ) ? 'active' : ''; ?>"><?php echo esc_html( $label ); ?></a>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
