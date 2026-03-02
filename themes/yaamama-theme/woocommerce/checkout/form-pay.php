<?php
/**
 * Pay for order form (custom layout).
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.2.0
 */
defined( 'ABSPATH' ) || exit;

$totals      = $order->get_order_item_totals(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
$items       = $order->get_items();
$first_item  = $items ? reset( $items ) : null;
$product     = $first_item ? $first_item->get_product() : null;
$title_text  = $first_item ? $first_item->get_name() : 'تفاصيل الطلب';
$subtitle    = $product ? $product->get_short_description() : '';
$billing_name = trim( (string) $order->get_formatted_billing_full_name() );
$billing_name = $billing_name !== '' ? $billing_name : '-';
$billing_email = $order->get_billing_email() ? $order->get_billing_email() : '-';
$billing_phone = $order->get_billing_phone() ? $order->get_billing_phone() : '';
if ( $billing_phone === '' && is_user_logged_in() ) {
	$user_id = get_current_user_id();
	$billing_phone = (string) get_user_meta( $user_id, 'billing_phone', true );
	if ( $billing_phone === '' ) {
		$billing_phone = (string) get_user_meta( $user_id, 'phone', true );
	}
}
$button_text   = $order_button_text;
$button_text   = strtolower( trim( (string) $button_text ) ) === 'pay for order' ? 'إتمام الدفع' : $order_button_text;

function yaamama_translate_total_label( $label ) {
	$translations = array(
		'Subtotal'       => 'المجموع الفرعي',
		'Total'          => 'الإجمالي',
		'Payment method' => 'طريقة الدفع',
		'Payment Method' => 'طريقة الدفع',
	);

	return str_ireplace( array_keys( $translations ), array_values( $translations ), $label );
}
?>
<div class="payment-grid">
	<div class="payment-forms">
		<div class="border-card y-u-m-b-24">
			<h3 class="card-title y-u-m-b-24">بيانات العميل</h3>
			<div class="form-group">
				<label class="form-label" for="billing-name">الاسم كامل</label>
				<input type="text" class="form-input" id="billing-name" value="<?php echo esc_attr( $billing_name ); ?>" readonly>
			</div>
			<div class="form-group">
				<label class="form-label" for="billing-email">البريد الإلكتروني</label>
				<input type="email" class="form-input" id="billing-email" value="<?php echo esc_attr( $billing_email ); ?>" readonly>
			</div>
			<?php
$phone_readonly = is_user_logged_in() && $billing_phone !== '';
			?>
			<div class="form-group">
				<label class="form-label" for="billing-phone">رقم الجوال</label>
				<input type="tel" class="form-input" id="billing-phone" name="billing_phone" value="<?php echo esc_attr( $billing_phone ); ?>" placeholder="05XXXXXXXX" <?php echo $phone_readonly ? 'readonly' : ''; ?>>
			</div>
		</div>

		<div class="border-card">
			<h3 class="card-title y-u-m-b-24">طريقة الدفع</h3>
			<form id="order_review" method="post">
				<input type="hidden" name="billing_phone" id="billing-phone-hidden" value="<?php echo esc_attr( $billing_phone ); ?>">
				<?php
				/**
				 * Triggered from within the checkout/form-pay.php template, immediately before the payment section.
				 *
				 * @since 8.2.0
				 */
				do_action( 'woocommerce_pay_order_before_payment' );
				?>

				<div id="payment">
					<?php if ( $order->needs_payment() ) : ?>
						<ul class="wc_payment_methods payment_methods methods payment-methods-tabs y-u-grid y-u-grid-2 y-u-gap-16 y-u-m-b-24">
							<?php
							if ( ! empty( $available_gateways ) ) {
								foreach ( $available_gateways as $gateway ) {
									wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
								}
							} else {
								echo '<li>';
								wc_print_notice( apply_filters( 'woocommerce_no_available_payment_methods_message', esc_html__( 'Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) ), 'notice' ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment
								echo '</li>';
							}
							?>
						</ul>
					<?php endif; ?>
					<div class="form-row">
						<input type="hidden" name="woocommerce_pay" value="1" />

						<?php wc_get_template( 'checkout/terms.php' ); ?>

						<?php do_action( 'woocommerce_pay_order_before_submit' ); ?>

						<?php echo apply_filters( 'woocommerce_pay_order_button_html', '<button type="submit" class="btn main-button fw" id="place_order" value="' . esc_attr( $button_text ) . '" data-value="' . esc_attr( $button_text ) . '"><i class="fa-solid fa-basket-shopping"></i>' . esc_html( $button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

						<?php do_action( 'woocommerce_pay_order_after_submit' ); ?>

						<?php wp_nonce_field( 'woocommerce-pay', 'woocommerce-pay-nonce' ); ?>
					</div>
					<script>
						document.addEventListener('DOMContentLoaded', function () {
							var placeOrder = document.getElementById('place_order');
							if (!placeOrder) return;

							var terms = document.getElementById('terms');
							var updateState = function () {
								placeOrder.style.display = 'inline-flex';
								var termsVisible = terms && terms.offsetParent !== null;
								if (!terms || !termsVisible || terms.checked) {
									placeOrder.disabled = false;
								} else {
									placeOrder.disabled = true;
								}
							};

							updateState();
							if (terms) {
								terms.addEventListener('change', updateState);
							}

							var observer = new MutationObserver(function () {
								updateState();
							});
							observer.observe(placeOrder, { attributes: true, attributeFilter: ['style', 'disabled'] });

							var orderForm = document.getElementById('order_review');
							if (!orderForm) return;

							var phoneInput = document.getElementById('billing-phone');

							var phonePattern = /^(?:\+?966|0)5\d{8}$/;

							var setValidity = function (input, message) {
								if (!input) return true;
								input.setCustomValidity(message || '');
								return message === '';
							};

							var validateInputs = function () {
								var isValid = true;
								if (phoneInput) {
									var phoneValue = phoneInput.value.trim();
									if (phoneValue === '') {
										isValid = setValidity(phoneInput, 'رقم الجوال مطلوب') && isValid;
									} else if (!phonePattern.test(phoneValue)) {
										isValid = setValidity(phoneInput, 'رقم الجوال يجب أن يبدأ بـ 05 ويتكون من 10 أرقام') && isValid;
									} else {
										setValidity(phoneInput, '');
									}
								}
								return isValid;
							};

							if (phoneInput) {
								phoneInput.addEventListener('input', validateInputs);
								phoneInput.addEventListener('blur', validateInputs);
							}

							orderForm.addEventListener('submit', function (event) {
							if (!validateInputs()) {
								event.preventDefault();
								if (phoneInput) phoneInput.reportValidity();
								return;
							}

							var hiddenPhone = document.getElementById('billing-phone-hidden');
							if (hiddenPhone && phoneInput) hiddenPhone.value = phoneInput.value;
						});
					</script>
				</div>
			</form>
		</div>
	</div>

	<div class="order-summary">
		<div class="border-card summary-card y-u-p-0">
			<div class="summary-header">
				<h2 class="y-u-font-bold y-u-text-l y-u-m-b-4"><?php echo esc_html( $title_text ); ?></h2>
				<?php if ( $subtitle ) : ?>
					<p class="y-u-text-s"><?php echo wp_kses_post( $subtitle ); ?></p>
				<?php else : ?>
					<p class="y-u-text-s">تفاصيل الطلب والدفع</p>
				<?php endif; ?>
			</div>

			<div class="summary-body y-u-p-24">
				<div class="price-box y-u-m-b-24">
					<div class="y-u-flex y-u-items-center y-u-gap-8">
						<span class="price"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span>
						<span class="period">الإجمالي</span>
					</div>
					<p class="y-u-text-xs y-u-m-t-4">يشمل الضريبة والشحن إن وجدت</p>
				</div>

				<div class="divider y-u-m-b-24"></div>

				<h4 class="y-u-font-bold y-u-text-s y-u-m-b-16">تفاصيل الطلب:</h4>
				<ul class="features-list y-u-m-b-24">
					<?php foreach ( $items as $item ) : ?>
						<li>
							<i class="fa-solid fa-circle-check"></i>
							<span><?php echo esc_html( sprintf( '%s × %s', $item->get_name(), $item->get_quantity() ) ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>

				<?php if ( $totals ) : ?>
					<div class="divider y-u-m-b-24"></div>
					<?php foreach ( $totals as $total ) : ?>
						<div class="y-u-flex y-u-justify-between y-u-m-b-8">
							<span class="y-u-text-s"><?php echo wp_kses_post( yaamama_translate_total_label( $total['label'] ) ); ?></span>
							<span class="y-u-text-s"><?php echo wp_kses_post( $total['value'] ); ?></span>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
