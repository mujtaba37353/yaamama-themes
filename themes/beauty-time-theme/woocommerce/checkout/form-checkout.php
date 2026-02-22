<?php
/**
 * Checkout Form — override
 * Uses beauty-time styling. Payment methods match process.html (cash, online, STC, Apple Pay, Tabby, Tamara).
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'يجب عليك تسجيل الدخول لإتمام الطلب.', 'beauty-time-theme' ) ) );
	return;
}
?>
<main>
	<section class="panner">
		<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-time-theme' ); ?></a> / <?php esc_html_e( 'إتمام الطلب', 'beauty-time-theme' ); ?></p>
	</section>

	<section class="checkout-section">
		<div class="container y-u-max-w-1200">
			<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
				<?php if ( $checkout->get_checkout_fields() ) : ?>
					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

					<div class="col2-set" id="customer_details">
						<div class="col-1">
							<?php do_action( 'woocommerce_checkout_billing' ); ?>
						</div>

						<div class="col-2">
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>
					</div>

					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
				<?php endif; ?>

				<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

				<h3 id="order_review_heading"><?php esc_html_e( 'طلبك', 'beauty-time-theme' ); ?></h3>

				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
			</form>
		</div>
	</section>
</main>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
