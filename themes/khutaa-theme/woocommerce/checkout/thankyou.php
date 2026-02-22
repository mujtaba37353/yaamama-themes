<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order thankyou-page">

	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<div class="thankyou-error">
				<div class="error-icon">
					<i class="fas fa-times-circle"></i>
				</div>
				<p class="error-message">
					<?php esc_html_e( 'عذراً، لا يمكن معالجة طلبك لأن البنك/التاجر الأصلي رفض معاملتك. يرجى المحاولة مرة أخرى.', 'khutaa-theme' ); ?>
				</p>
				<div class="error-actions">
					<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="btn-pay">
						<?php esc_html_e( 'الدفع', 'khutaa-theme' ); ?>
					</a>
					<?php if ( is_user_logged_in() ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn-account">
							<?php esc_html_e( 'حسابي', 'khutaa-theme' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>

		<?php else : ?>

			<?php wc_get_template( 'checkout/order-received.php', array( 'order' => $order ) ); ?>

			<div class="order-summary-card">
				<h3 class="summary-title"><?php esc_html_e( 'تفاصيل الطلب', 'khutaa-theme' ); ?></h3>
				
				<ul class="woocommerce-order-overview order-details-list">
					<li class="order-detail-item">
						<span class="detail-label"><?php esc_html_e( 'رقم الطلب:', 'khutaa-theme' ); ?></span>
						<strong class="detail-value"><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>

					<li class="order-detail-item">
						<span class="detail-label"><?php esc_html_e( 'التاريخ:', 'khutaa-theme' ); ?></span>
						<strong class="detail-value"><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>

					<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
						<li class="order-detail-item">
							<span class="detail-label"><?php esc_html_e( 'البريد الإلكتروني:', 'khutaa-theme' ); ?></span>
							<strong class="detail-value"><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
						</li>
					<?php endif; ?>

					<li class="order-detail-item order-total-item">
						<span class="detail-label"><?php esc_html_e( 'المجموع:', 'khutaa-theme' ); ?></span>
						<strong class="detail-value total-amount"><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>

					<?php if ( $order->get_payment_method_title() ) : ?>
						<li class="order-detail-item">
							<span class="detail-label"><?php esc_html_e( 'طريقة الدفع:', 'khutaa-theme' ); ?></span>
							<strong class="detail-value"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
						</li>
					<?php endif; ?>
				</ul>
			</div>

			<?php
			/**
			 * Order details section
			 */
			do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );
			do_action( 'woocommerce_thankyou', $order->get_id() );
			?>

			<div class="thankyou-actions">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button btn-home">
					<i class="fas fa-home"></i>
					<?php esc_html_e( 'العودة للرئيسية', 'khutaa-theme' ); ?>
				</a>
			</div>

		<?php endif; ?>

	<?php else : ?>

		<?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>

		<div class="thankyou-actions">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button btn-home">
				<i class="fas fa-home"></i>
				<?php esc_html_e( 'العودة للرئيسية', 'khutaa-theme' ); ?>
			</a>
		</div>

	<?php endif; ?>

</div>
