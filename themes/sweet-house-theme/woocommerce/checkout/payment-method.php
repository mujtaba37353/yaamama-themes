<?php
/**
 * Payment method — Sweet House design.
 *
 * @package Sweet_House_Theme
 * @see design: sweet-house/components/payment/y-c-payment-form.html (payment-option)
 */

defined( 'ABSPATH' ) || exit;
?>
<label class="payment-option wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">
	<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
	<div class="option-text">
		<span class="option-sub"><?php echo $gateway->get_title(); /* phpcs:ignore */ ?></span>
	</div>
	<?php if ( $gateway->get_icon() ) : ?>
		<div class="option-logos"><?php echo $gateway->get_icon(); /* phpcs:ignore */ ?></div>
	<?php endif; ?>
</label>
<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
	<div class="payment-form-fields payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php if ( ! $gateway->chosen ) : ?>style="display:none;"<?php endif; ?>>
		<?php $gateway->payment_fields(); ?>
	</div>
<?php endif; ?>
