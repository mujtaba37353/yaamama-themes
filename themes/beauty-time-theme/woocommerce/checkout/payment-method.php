<?php
/**
 * Payment Method — override
 * Styled to match process.html payment cards
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;
?>
<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">
	<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio payment-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
	<label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>" class="payment-method-card">
		<div class="payment-icon">
			<?php
			$icon = $gateway->get_icon();
			if ( $icon ) {
				echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo '<i class="fas fa-credit-card"></i>';
			}
			?>
		</div>
		<div class="payment-info">
			<h3><?php echo esc_html( $gateway->get_title() ); ?></h3>
			<?php
			$desc = $gateway->get_description();
			if ( $desc ) {
				echo '<p>' . wp_kses_post( $desc ) . '</p>';
			}
			?>
		</div>
		<i class="fas fa-check-circle"></i>
	</label>
	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php if ( ! $gateway->chosen ) : ?>style="display:none;"<?php endif; ?>>
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>
