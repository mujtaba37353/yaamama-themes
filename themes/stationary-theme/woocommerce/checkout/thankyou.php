<?php
/**
 * Thankyou page – Stationary theme override
 *
 * Shows a success popup modal instead of the default order summary.
 *
 * @package stationary-theme
 * @version 8.1.0
 */

defined( 'ABSPATH' ) || exit;

$orders_url = function_exists( 'wc_get_account_endpoint_url' ) ? wc_get_account_endpoint_url( 'orders' ) : home_url( '/my-account/orders/' );
$home_url   = home_url( '/' );
?>

<div class="stationary-thankyou-overlay" id="thankyouOverlay">
	<div class="status-popup-card">
		<div class="status-popup-icon">
			<svg viewBox="0 0 24 24">
				<path d="M20 6L9 17l-5-5" />
			</svg>
		</div>
		<p class="status-popup-text"><?php esc_html_e( 'تم تسجيل طلبك بنجاح', 'stationary-theme' ); ?></p>

		<div class="stationary-thankyou-buttons">
			<a href="<?php echo esc_url( $home_url ); ?>" class="btn main-button">
				<?php esc_html_e( 'الرئيسية', 'stationary-theme' ); ?>
			</a>
			<a href="<?php echo esc_url( $orders_url ); ?>" class="btn secondary-button">
				<?php esc_html_e( 'طلباتي', 'stationary-theme' ); ?>
			</a>
		</div>
	</div>
</div>
