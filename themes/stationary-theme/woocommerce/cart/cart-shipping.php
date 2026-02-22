<?php
/**
 * Shipping Methods Display – Stationary theme override
 *
 * Replaces <tr>/<td> table markup with <div>-based layout
 * so it works inside our div-based review-order and cart templates.
 *
 * @package stationary-theme
 * @version 7.6.0
 */

defined( 'ABSPATH' ) || exit;

$formatted_destination    = isset( $formatted_destination ) ? $formatted_destination : WC()->countries->get_formatted_address( $package['destination'], ', ' );
$has_calculated_shipping  = ! empty( $has_calculated_shipping );
$show_shipping_calculator = ! empty( $show_shipping_calculator );
$calculator_text          = '';
?>

<div class="woocommerce-shipping-totals shipping" data-title="<?php echo esc_attr( $package_name ); ?>">
	<p class="shipping-label">
		<?php echo wp_kses_post( $package_name ); ?>
		<span>
			<?php if ( $available_methods ) : ?>
				<?php if ( 1 === count( $available_methods ) ) : ?>
					<?php
					$method = current( $available_methods );
					echo wp_kses_post( wc_cart_totals_shipping_method_label( $method ) );
					?>
					<input type="hidden" name="shipping_method[<?php echo esc_attr( $index ); ?>]" data-index="<?php echo esc_attr( $index ); ?>" id="shipping_method_<?php echo esc_attr( $index ); ?>_<?php echo esc_attr( sanitize_title( $method->id ) ); ?>" value="<?php echo esc_attr( $method->id ); ?>" class="shipping_method">
				<?php else : ?>
					&mdash;
				<?php endif; ?>
			<?php elseif ( ! $has_calculated_shipping || ! $formatted_destination ) : ?>
				<?php echo wp_kses_post( apply_filters( 'woocommerce_shipping_not_enabled_on_cart_html', __( 'أدخل عنوانك لحساب الشحن.', 'stationary-theme' ) ) ); ?>
			<?php else : ?>
				<?php echo wp_kses_post( apply_filters( 'woocommerce_no_shipping_available_html', __( 'لا تتوفر طرق شحن لعنوانك.', 'stationary-theme' ) ) ); ?>
			<?php endif; ?>
		</span>
	</p>

	<?php if ( $available_methods && count( $available_methods ) > 1 ) : ?>
		<ul id="shipping_method" class="woocommerce-shipping-methods">
			<?php foreach ( $available_methods as $method ) : ?>
				<li>
					<label class="radio-group" for="shipping_method_<?php echo esc_attr( $index ); ?>_<?php echo esc_attr( sanitize_title( $method->id ) ); ?>">
						<input
							type="radio"
							name="shipping_method[<?php echo esc_attr( $index ); ?>]"
							data-index="<?php echo esc_attr( $index ); ?>"
							id="shipping_method_<?php echo esc_attr( $index ); ?>_<?php echo esc_attr( sanitize_title( $method->id ) ); ?>"
							value="<?php echo esc_attr( $method->id ); ?>"
							class="shipping_method"
							<?php checked( $method->id, $chosen_method ); ?>
						>
						<?php echo wp_kses_post( wc_cart_totals_shipping_method_label( $method ) ); ?>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php if ( $show_shipping_calculator ) : ?>
		<?php woocommerce_shipping_calculator( $calculator_text ); ?>
	<?php endif; ?>
</div>
