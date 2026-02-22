<?php
/**
 * Cart totals
 *
 * @package KhutaaTheme
 */

defined( 'ABSPATH' ) || exit;
?>
<?php do_action( 'woocommerce_before_cart_totals' ); ?>

<div class="cart-totals-content">
		<div class="cart-totals-row">
			<p><?php esc_html_e( 'المجموع', 'khutaa-theme' ); ?></p>
			<p><?php wc_cart_totals_subtotal_html(); ?></p>
		</div>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<hr />
			<div class="cart-totals-row">
				<p><?php wc_cart_totals_coupon_label( $coupon ); ?></p>
				<p><?php wc_cart_totals_coupon_html( $coupon ); ?></p>
			</div>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
			<hr />
			<div class="cart-totals-row">
				<p><?php esc_html_e( 'الشحن', 'khutaa-theme' ); ?></p>
				<p><?php wc_cart_totals_shipping_html(); ?></p>
			</div>
			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>
			<hr />
			<div class="cart-totals-row">
				<p><?php esc_html_e( 'الشحن', 'khutaa-theme' ); ?></p>
				<p><?php woocommerce_shipping_calculator(); ?></p>
			</div>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<hr />
			<div class="cart-totals-row">
				<p><?php echo esc_html( $fee->name ); ?></p>
				<p><?php wc_cart_totals_fee_html( $fee ); ?></p>
			</div>
		<?php endforeach; ?>

		<?php
		if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
				$estimated_text = sprintf( ' <small>' . esc_html__( '(مقدر لـ %s)', 'khutaa-theme' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
			}

			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
				foreach ( WC()->cart->get_tax_totals() as $code => $tax ) {
					?>
					<hr />
					<div class="cart-totals-row">
						<p><?php echo esc_html( $tax->label ) . $estimated_text; ?></p>
						<p><?php echo wp_kses_post( $tax->formatted_amount ); ?></p>
					</div>
					<?php
				}
			} else {
				?>
				<hr />
				<div class="cart-totals-row">
					<p><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; ?></p>
					<p><?php wc_cart_totals_taxes_total_html(); ?></p>
				</div>
				<?php
			}
		} elseif ( wc_tax_enabled() && WC()->cart->display_prices_including_tax() ) {
			// Show VAT when prices include tax
			?>
			<hr />
			<div class="cart-totals-row">
				<p><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></p>
				<p><?php wc_cart_totals_taxes_total_html(); ?></p>
			</div>
			<?php
		}
		?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<hr />
		<div class="cart-totals-row">
			<p><?php esc_html_e( 'الإجمالي المقدر', 'khutaa-theme' ); ?></p>
			<p><?php wc_cart_totals_order_total_html(); ?></p>
		</div>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
	</div>

<?php do_action( 'woocommerce_after_cart_totals' ); ?>

<div class="wc-proceed-to-checkout">
	<?php
	$checkout_url = wc_get_checkout_url();
	?>
	<a href="<?php echo esc_url( $checkout_url ); ?>" class="checkout-button button alt wc-forward">
		<?php esc_html_e( 'متابعة للدفع', 'khutaa-theme' ); ?>
	</a>
</div>

<style>
.cart-totals-content {
	width: 100%;
	display: flex;
	flex-direction: column;
}

.cart-totals-content hr {
	border: none;
	border-top: 1px solid #a8a8a8;
	margin: 1.5rem 0;
	opacity: 0.5;
	width: 100%;
}

.cart-totals-row {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 1rem;
	width: 100%;
	flex-wrap: nowrap;
}

.cart-totals-row:last-of-type {
	margin-bottom: 0;
}

.cart-totals-row p {
	margin: 0;
	font-size: 1.1rem;
	font-weight: 600;
	color: #3a2c1c;
	white-space: nowrap;
	flex-shrink: 0;
	line-height: 1.5;
}

.cart-totals-row p:first-child {
	text-align: right;
}

.cart-totals-row p:last-child {
	text-align: left;
}

.wc-proceed-to-checkout {
	margin-top: 1rem;
}

.wc-proceed-to-checkout .button,
.wc-proceed-to-checkout .checkout-button {
	width: 100%;
	text-align: center;
	background-color: #ac5300;
	color: #fff;
	padding: 1rem;
	border-radius: 8px;
	font-size: 1.1rem;
	font-weight: 700;
	text-decoration: none;
	display: block;
	transition: background-color 0.3s ease;
	border: none;
	cursor: pointer;
}

.wc-proceed-to-checkout .button:hover,
.wc-proceed-to-checkout .checkout-button:hover {
	background-color: #b18155;
}

@media (max-width: 820px) {
	.cart-totals-row p {
		font-size: 1rem;
	}

	.wc-proceed-to-checkout .button,
	.wc-proceed-to-checkout .checkout-button {
		font-size: 1rem;
		padding: 0.875rem;
	}
}

@media (max-width: 576px) {
	.cart-totals-row p {
		font-size: 0.9rem;
	}

	.cart-totals-content hr {
		margin: 1.25rem 0;
	}

	.cart-totals-row {
		margin-bottom: 0.875rem;
	}

	.wc-proceed-to-checkout .button,
	.wc-proceed-to-checkout .checkout-button {
		font-size: 0.95rem;
		padding: 0.75rem;
		border-radius: 6px;
	}
}
</style>
