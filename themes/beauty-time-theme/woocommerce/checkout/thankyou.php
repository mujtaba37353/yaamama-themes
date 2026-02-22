<?php
/**
 * Thank You Page — override
 * Similar to booking-success.html structure
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

if ( ! $order ) {
	return;
}

do_action( 'woocommerce_before_thankyou', $order->get_id() );
?>
<main>
	<section class="panner">
		<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-time-theme' ); ?></a> / <?php esc_html_e( 'شكراً لك', 'beauty-time-theme' ); ?></p>
	</section>
	<section class="profile-section">
		<div class="container y-u-max-w-1200">
			<div class="content">
				<div class="success-content">
					<div class="appointment-confirmation">
						<div class="celebration-icon">🎉</div>
						<h2 class="congratulations"><?php esc_html_e( 'تهانينا!', 'beauty-time-theme' ); ?></h2>
						<p class="appointment-id"><?php esc_html_e( 'رقم الطلب', 'beauty-time-theme' ); ?> <strong>#<?php echo esc_html( $order->get_order_number() ); ?></strong></p>
						<?php wc_get_template( 'checkout/order-receipt.php', array( 'order' => $order ) ); ?>
						<div class="appointment-footer">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'العودة للرئيسية', 'beauty-time-theme' ); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>
<?php do_action( 'woocommerce_after_thankyou', $order->get_id() ); ?>
