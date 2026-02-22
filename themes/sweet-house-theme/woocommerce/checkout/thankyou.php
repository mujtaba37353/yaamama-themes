<?php
/**
 * Thankyou page — Sweet House design.
 *
 * @package Sweet_House_Theme
 * @see design: sweet-house/templates/thank-you/
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="thank-you-design-wrap">
	<header class="thank-you-design-header">
		<img src="<?php echo esc_url( sweet_house_asset_uri( 'assets/panner.png' ) ); ?>" alt="<?php esc_attr_e( 'بانر سويت هاوس - متجر الحلويات والمخبوزات', 'sweet-house-theme' ); ?>" class="panner-img" />
	</header>

	<div class="main-container thank-you-container">
		<?php if ( function_exists( 'woocommerce_breadcrumb' ) ) : ?>
		<nav class="woocommerce-breadcrumb-wrap" aria-label="<?php esc_attr_e( 'مسار الصفحة', 'sweet-house-theme' ); ?>">
			<?php woocommerce_breadcrumb(); ?>
		</nav>
		<?php endif; ?>

		<div class="woocommerce-order thank-you-content">
			<?php
			if ( $order ) :
				do_action( 'woocommerce_before_thankyou', $order->get_id() );
				?>

				<?php if ( $order->has_status( 'failed' ) ) : ?>

					<div class="thank-you-card thank-you-card--error">
						<div class="thank-you-icon"><i class="fa-solid fa-xmark"></i></div>
						<h2 class="thank-you-title"><?php esc_html_e( 'فشل معالجة الطلب', 'sweet-house-theme' ); ?></h2>
						<p class="thank-you-message"><?php esc_html_e( 'للأسف لا يمكن معالجة طلبك لأن البنك أو وسيلة الدفع رفضت المعاملة. يرجى المحاولة مرة أخرى.', 'sweet-house-theme' ); ?></p>
						<div class="thank-you-actions">
							<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="btn-auth"><?php esc_html_e( 'إعادة المحاولة', 'sweet-house-theme' ); ?></a>
							<?php if ( is_user_logged_in() ) : ?>
								<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn-auth btn-auth--outline"><?php esc_html_e( 'حسابي', 'sweet-house-theme' ); ?></a>
							<?php endif; ?>
						</div>
					</div>

				<?php else : ?>

					<div class="thank-you-card thank-you-card--success">
						<div class="thank-you-icon"><i class="fa-solid fa-circle-check"></i></div>
						<h2 class="thank-you-title"><?php esc_html_e( 'شكراً لطلبك!', 'sweet-house-theme' ); ?></h2>
						<?php wc_get_template( 'checkout/order-received.php', array( 'order' => $order ) ); ?>

						<ul class="thank-you-order-details">
							<li>
								<span class="label"><?php esc_html_e( 'رقم الطلب:', 'sweet-house-theme' ); ?></span>
								<strong><?php echo esc_html( $order->get_order_number() ); ?></strong>
							</li>
							<li>
								<span class="label"><?php esc_html_e( 'التاريخ:', 'sweet-house-theme' ); ?></span>
								<strong><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></strong>
							</li>
							<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
							<li>
								<span class="label"><?php esc_html_e( 'البريد الإلكتروني:', 'sweet-house-theme' ); ?></span>
								<strong><?php echo esc_html( $order->get_billing_email() ); ?></strong>
							</li>
							<?php endif; ?>
							<li>
								<span class="label"><?php esc_html_e( 'المجموع:', 'sweet-house-theme' ); ?></span>
								<strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
							</li>
							<?php if ( $order->get_payment_method_title() ) : ?>
							<li>
								<span class="label"><?php esc_html_e( 'طريقة الدفع:', 'sweet-house-theme' ); ?></span>
								<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
							</li>
							<?php endif; ?>
						</ul>
					</div>

				<?php endif; ?>

				<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
				<?php
				remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
				do_action( 'woocommerce_thankyou', $order->get_id() );
				?>

			<?php else : ?>

				<div class="thank-you-card thank-you-card--success">
					<div class="thank-you-icon"><i class="fa-solid fa-circle-check"></i></div>
					<?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>
				</div>

			<?php endif; ?>
		</div>

		<div class="thank-you-footer-actions">
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-auth"><?php esc_html_e( 'تسوق المزيد', 'sweet-house-theme' ); ?></a>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn-auth btn-auth--outline"><?php esc_html_e( 'حسابي', 'sweet-house-theme' ); ?></a>
		</div>
	</div>
</div>
