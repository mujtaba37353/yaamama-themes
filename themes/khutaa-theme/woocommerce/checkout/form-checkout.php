<?php
/**
 * Checkout Form
 *
 * @package KhutaaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'يجب عليك تسجيل الدخول لإتمام عملية الشراء.', 'khutaa-theme' ) ) );
	return;
}
?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="<?php esc_attr_e( 'الدفع', 'khutaa-theme' ); ?>">

	<div class="checkout-wrapper">
		
		<div class="checkout-form-wrapper">
			<?php if ( $checkout->get_checkout_fields() ) : ?>

				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

				<div id="customer_details">
					<div class="payment-form-section">
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
					</div>

					<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>
						<div class="payment-form-section">
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>
					<?php endif; ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

			<?php endif; ?>
		</div>

		<div class="checkout-order-review-wrapper summary">
			<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
			
			<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

			<?php do_action( 'woocommerce_checkout_order_review' ); ?>

			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
		</div>

	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<style>
.checkout-wrapper {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 2rem;
	align-items: start;
	width: 100%;
	direction: rtl;
}

.checkout-form-wrapper {
	width: 100%;
}

.checkout-order-review-wrapper {
	width: 100%;
}

.payment-form-section {
	margin-bottom: 2.5rem;
	padding-bottom: 2rem;
	border-bottom: 1px solid #e0e0e0;
}

.payment-form-section:last-of-type {
	border-bottom: none;
	margin-bottom: 0;
}

/* Payment Form Design - Matching y-c-payment-form.css */
.woocommerce-billing-fields h3,
.woocommerce-shipping-fields h3 {
	margin-bottom: 1rem;
	font-size: 1.5rem;
	font-weight: 700;
	color: #3a2c1c;
}

.woocommerce-billing-fields .note,
.woocommerce-shipping-fields .note {
	margin-bottom: 1.5rem;
	font-size: 0.95rem;
	color: #666;
	line-height: 1.5;
}

/* Form Fields Design - Matching Contact Us Page */
.checkout-wrapper .checkout-form-wrapper .woocommerce-form-row {
	margin-bottom: 1.25rem;
	display: flex;
	flex-direction: column;
	gap: 0.5rem;
}

.checkout-wrapper .checkout-form-wrapper .woocommerce-billing-fields__field-wrapper .woocommerce-form-row label,
.checkout-wrapper .checkout-form-wrapper .woocommerce-shipping-fields__field-wrapper .woocommerce-form-row label {
	display: block;
	color: #3a2c1c !important;
	font-weight: 600 !important;
	font-size: 1rem !important;
	text-align: right !important;
	margin: 0 !important;
	line-height: normal !important;
}

.checkout-wrapper .checkout-form-wrapper .woocommerce-billing-fields__field-wrapper input[type="text"],
.checkout-wrapper .checkout-form-wrapper .woocommerce-billing-fields__field-wrapper input[type="email"],
.checkout-wrapper .checkout-form-wrapper .woocommerce-billing-fields__field-wrapper input[type="tel"],
.checkout-wrapper .checkout-form-wrapper .woocommerce-shipping-fields__field-wrapper input[type="text"],
.checkout-wrapper .checkout-form-wrapper .woocommerce-shipping-fields__field-wrapper input[type="email"],
.checkout-wrapper .checkout-form-wrapper .woocommerce-shipping-fields__field-wrapper input[type="tel"],
.checkout-wrapper .checkout-form-wrapper select,
.checkout-wrapper .checkout-form-wrapper textarea {
	width: 100% !important;
	border: 1px solid #888 !important;
	border-radius: 8px !important;
	padding: 0.8rem !important;
	background-color: transparent !important;
	font-family: inherit !important;
	font-size: 1rem !important;
	outline: none !important;
	transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
	margin: 0 !important;
	box-shadow: none !important;
}

.checkout-wrapper .checkout-form-wrapper input[type="text"]:focus,
.checkout-wrapper .checkout-form-wrapper input[type="email"]:focus,
.checkout-wrapper .checkout-form-wrapper input[type="tel"]:focus,
.checkout-wrapper .checkout-form-wrapper select:focus,
.checkout-wrapper .checkout-form-wrapper textarea:focus {
	border-color: #b18155 !important;
	box-shadow: 0 0 8px rgba(172, 83, 0, 0.2) !important;
}

.checkout-wrapper .checkout-form-wrapper textarea {
	resize: vertical;
	min-height: 100px;
}

/* Payment Methods Design - Matching y-c-payment-form.css */
.woocommerce-checkout-payment {
	margin-top: 2rem;
}

.woocommerce-checkout-payment .wc_payment_methods {
	list-style: none;
	margin: 0;
	padding: 0;
	display: flex;
	flex-direction: column;
	gap: 1rem;
}

.woocommerce-checkout-payment .wc_payment_methods .wc_payment_method {
	border: 2px solid #e0e0e0;
	border-radius: 12px;
	overflow: hidden;
	transition: all 0.3s ease;
	background-color: transparent;
	position: relative;
	margin: 0;
	display: flex;
	align-items: center;
	flex-direction: row-reverse;
	gap: 1rem;
	padding: 1.25rem;
}

.woocommerce-checkout-payment .wc_payment_methods .wc_payment_method:hover {
	border-color: #ccc;
	box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.woocommerce-checkout-payment .wc_payment_methods .wc_payment_method:has(input:checked) {
	border-color: #b18155;
	background-color: transparent;
	box-shadow: 0 4px 15px rgba(177, 129, 85, 0.15);
}

.woocommerce-checkout-payment .wc_payment_methods .wc_payment_method label {
	padding: 0;
	cursor: pointer;
	margin: 0;
	flex: 1;
	display: flex;
	align-items: center;
	font-weight: 600;
	font-size: 1.1rem;
	color: #3a2c1c;
}

.woocommerce-checkout-payment .wc_payment_methods .wc_payment_method input[type="radio"] {
	appearance: none;
	-webkit-appearance: none;
	width: 20px;
	height: 20px;
	border: 2px solid #999;
	border-radius: 50%;
	margin: 0;
	position: relative;
	outline: none;
	cursor: pointer;
	flex-shrink: 0;
	padding: 0;
	order: 2;
}

.woocommerce-checkout-payment .wc_payment_methods .wc_payment_method input[type="radio"]:checked {
	border-color: #b18155;
}

.woocommerce-checkout-payment .wc_payment_methods .wc_payment_method input[type="radio"]:checked::after {
	content: "";
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	width: 10px;
	height: 10px;
	background-color: #b18155;
	border-radius: 50%;
}

.woocommerce-checkout-payment .payment_box {
	display: none !important;
	padding: 0 1.25rem 1.25rem 1.25rem;
	border-top: 1px solid rgba(0, 0, 0, 0.05);
	margin-top: 0;
	background-color: transparent;
	overflow: hidden;
	animation: slideDown 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
}

@keyframes slideDown {
	from {
		opacity: 0;
		transform: translateY(-10px);
		max-height: 0;
		padding-top: 0;
		padding-bottom: 0;
	}
	to {
		opacity: 1;
		transform: translateY(0);
		max-height: 500px;
		padding-top: 1.25rem;
		padding-bottom: 1.25rem;
	}
}

.woocommerce-checkout-payment .payment_box p {
	margin-bottom: 0.5rem;
	font-size: 0.9rem;
	color: #666;
}

.woocommerce-checkout-payment .payment_box label {
	font-size: 0.9rem;
}

.woocommerce-checkout-payment .payment_box input {
	background-color: transparent;
}

/* Place Order Button Design - Matching y-c-payment-form.css */
.woocommerce-checkout-payment .form-row.place-order {
	margin-top: 2rem;
	padding-top: 1.5rem;
	border-top: 1px solid #eee;
}

.woocommerce-checkout-payment #place_order {
	padding: 0.8rem 2.5rem;
	background: #ac5300;
	color: white;
	border: none;
	border-radius: 25px;
	font-size: 1.1rem;
	font-weight: 700;
	cursor: pointer;
	transition: all 0.3s ease;
	box-shadow: 0 4px 10px rgba(172, 83, 0, 0.2);
	width: 100%;
}

.woocommerce-checkout-payment #place_order:hover {
	background: #b18155;
	transform: translateY(-2px);
	box-shadow: 0 6px 15px rgba(177, 129, 85, 0.3);
}

/* Order Summary Design - Matching Contact Us Page Style */
.checkout-order-review-wrapper {
	background-color: transparent;
}

.checkout-order-review-wrapper .woocommerce-checkout-review-order-table {
	border: none;
	margin: 0;
	width: 100%;
	background-color: transparent;
}

.checkout-order-review-wrapper .woocommerce-checkout-review-order-table thead {
	display: none;
}

.checkout-order-review-wrapper .woocommerce-checkout-review-order-table tbody tr,
.checkout-order-review-wrapper .woocommerce-checkout-review-order-table tfoot tr {
	border: none;
	background-color: transparent;
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 0.75rem 0;
	gap: 1rem;
}

.checkout-order-review-wrapper .woocommerce-checkout-review-order-table tbody td,
.checkout-order-review-wrapper .woocommerce-checkout-review-order-table tfoot th,
.checkout-order-review-wrapper .woocommerce-checkout-review-order-table tfoot td {
	border: none;
	padding: 0;
	color: #3a2c1c;
	font-size: 1rem;
}

.checkout-order-review-wrapper .woocommerce-checkout-review-order-table tbody td.product-name {
	font-weight: normal;
	text-align: right;
	flex: 1;
}

.checkout-order-review-wrapper .woocommerce-checkout-review-order-table tbody td.product-total,
.checkout-order-review-wrapper .woocommerce-checkout-review-order-table tfoot td {
	font-weight: normal;
	text-align: left;
	white-space: nowrap;
}

.checkout-order-review-wrapper .woocommerce-checkout-review-order-table tfoot th {
	font-weight: 600;
	font-size: 1rem;
	color: #3a2c1c;
	text-align: right;
}

.checkout-order-review-wrapper .woocommerce-checkout-review-order-table tfoot .cart-subtotal th,
.checkout-order-review-wrapper .woocommerce-checkout-review-order-table tfoot .cart-subtotal td {
	font-weight: 600;
}

.checkout-order-review-wrapper .woocommerce-checkout-review-order-table tfoot .order-total th,
.checkout-order-review-wrapper .woocommerce-checkout-review-order-table tfoot .order-total td {
	font-weight: 600;
	font-size: 1rem;
}

/* Terms and Conditions - Matching y-c-payment-form.css */
.woocommerce-terms-and-conditions-wrapper {
	margin-top: 1rem;
}

.woocommerce-terms-and-conditions-wrapper .woocommerce-privacy-policy-text {
	font-size: 0.85rem;
	color: #777;
	text-align: center;
	margin-top: 1rem;
}

.woocommerce-terms-and-conditions-wrapper .woocommerce-privacy-policy-text a {
	color: #ac5300;
	text-decoration: none;
	font-weight: 600;
}

.woocommerce-terms-and-conditions-wrapper .woocommerce-privacy-policy-text a:hover {
	text-decoration: underline;
}

@media (max-width: 992px) {
	.checkout-wrapper {
		grid-template-columns: 1fr;
		gap: 2rem;
	}

	.checkout-order-review-wrapper {
		max-width: 600px;
		justify-self: center;
	}

	.woocommerce-billing-fields h3,
	.woocommerce-shipping-fields h3 {
		font-size: 1.4rem;
	}

	.payment-form-section {
		margin-bottom: 2rem;
		padding-bottom: 1.5rem;
	}
}

@media (max-width: 820px) {
	.checkout-wrapper {
		gap: 1.5rem;
		justify-items: center;
	}

	.checkout-order-review-wrapper {
		width: 95%;
	}

	.woocommerce-billing-fields h3,
	.woocommerce-shipping-fields h3 {
		font-size: 1.3rem;
	}

	.woocommerce-form-row input[type="text"],
	.woocommerce-form-row input[type="email"],
	.woocommerce-form-row input[type="tel"],
	.woocommerce-form-row select,
	.woocommerce-form-row textarea {
		font-size: 0.95rem;
		padding: 0.7rem 0.875rem;
	}

	.woocommerce-form-row label {
		font-size: 0.9rem;
	}
}

@media (max-width: 768px) {
	.checkout-wrapper {
		gap: 1.5rem;
		padding: 0 1rem;
	}

	.checkout-order-review-wrapper {
		width: 100%;
	}

	.woocommerce-billing-fields h3,
	.woocommerce-shipping-fields h3 {
		font-size: 1.5rem;
		margin-bottom: 1rem;
	}

	.payment-form-section {
		margin-bottom: 2rem;
		padding-bottom: 1.5rem;
	}

	.woocommerce-billing-fields .note,
	.woocommerce-shipping-fields .note {
		font-size: 1rem;
		margin-bottom: 1.25rem;
	}

	.woocommerce-form-row {
		margin-bottom: 1.25rem;
	}

	.woocommerce-form-row label {
		font-size: 1.1rem;
		margin-bottom: 0.5rem;
	}

	.woocommerce-form-row input[type="text"],
	.woocommerce-form-row input[type="email"],
	.woocommerce-form-row input[type="tel"],
	.woocommerce-form-row select,
	.woocommerce-form-row textarea {
		font-size: 1.1rem;
		padding: 1rem;
		border-radius: 8px;
	}

	.woocommerce-form-row textarea {
		min-height: 120px;
	}

	.woocommerce-checkout-payment #place_order {
		padding: 1rem 2.5rem;
		font-size: 1.2rem;
		border-radius: 25px;
	}

	.woocommerce-checkout-payment .wc_payment_methods .wc_payment_method {
		margin-bottom: 1rem;
		padding: 1.5rem;
	}

	.woocommerce-checkout-payment .wc_payment_methods .wc_payment_method label {
		padding: 0;
		font-size: 1.2rem;
	}

	.woocommerce-checkout-payment .wc_payment_methods .wc_payment_method input[type="radio"] {
		width: 22px;
		height: 22px;
		margin: 0;
	}

	.woocommerce-checkout-payment .wc_payment_methods .wc_payment_method input[type="radio"]:checked::after {
		width: 11px;
		height: 11px;
	}

	.woocommerce-checkout-payment .payment_box {
		display: none !important;
		padding: 1.25rem 1.5rem 1.5rem;
	}

	.woocommerce-checkout-payment .payment_box label {
		font-size: 1rem;
	}

	.checkout-order-review-wrapper .woocommerce-checkout-review-order-table tbody td,
	.checkout-order-review-wrapper .woocommerce-checkout-review-order-table tfoot th,
	.checkout-order-review-wrapper .woocommerce-checkout-review-order-table tfoot td {
		font-size: 1.1rem;
	}

	.checkout-order-review-wrapper .woocommerce-checkout-review-order-table tfoot th {
		font-size: 1.1rem;
	}
}
</style>
