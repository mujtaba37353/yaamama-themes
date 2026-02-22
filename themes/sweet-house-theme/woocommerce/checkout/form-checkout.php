<?php
/**
 * Checkout Form — Sweet House design.
 *
 * @see design: sweet-house/templates/payment/layout.html
 * @package Sweet_House_Theme
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}
?>

<div class="y-u-my-10 checkout-design-wrap">
	<div class="main-container">
		<?php if ( function_exists( 'woocommerce_breadcrumb' ) ) : ?>
		<nav data-y="breadcrumb" class="woocommerce-breadcrumb-wrap" aria-label="<?php esc_attr_e( 'مسار الصفحة', 'sweet-house-theme' ); ?>">
			<?php woocommerce_breadcrumb(); ?>
		</nav>
		<?php endif; ?>
	</div>
	<div class="main-container checkout-main-grid">
		<div data-y="payment" class="checkout-form-col">
			<form name="checkout" method="post" class="checkout woocommerce-checkout payment-form" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__( 'إتمام الطلب', 'sweet-house-theme' ); ?>">

				<?php if ( $checkout->get_checkout_fields() ) : ?>

					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

					<div id="customer_details" class="payment-form-fields-wrap">
						<div class="section">
							<h3><?php esc_html_e( 'معلومات التوصيل', 'sweet-house-theme' ); ?></h3>
							<p class="note"><?php esc_html_e( 'سوف نستخدم هذا البريد الإلكتروني لإرسال التفاصيل والتحديثات إليك حول طلبك.', 'sweet-house-theme' ); ?></p>
							<?php do_action( 'woocommerce_checkout_billing' ); ?>
						</div>
						<?php if ( WC()->cart->needs_shipping() && ! wc_ship_to_billing_address_only() ) : ?>
						<div class="section">
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>
						<?php endif; ?>
						<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
					</div>

				<?php endif; ?>

				<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

				<div class="section2 payment-methods-wrap">
					<?php do_action( 'sweet_house_checkout_payment_section' ); ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

			</form>
		</div>

		<div data-y="payment-summary" class="summary order-summary-col" id="order_review">
			<?php do_action( 'sweet_house_checkout_order_summary' ); ?>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
