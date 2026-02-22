<?php
/**
 * Product quantity inputs - Beauty Care design (with +/- buttons)
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 */

defined( 'ABSPATH' ) || exit;

$min_value     = isset( $min_value ) ? $min_value : 0;
$max_value     = isset( $max_value ) ? $max_value : '';
$step          = isset( $step ) ? $step : 1;
$input_value   = isset( $input_value ) ? $input_value : $min_value;
$input_id      = isset( $input_id ) ? $input_id : uniqid( 'quantity_' );
$input_name    = isset( $input_name ) ? $input_name : 'quantity';
$type          = isset( $type ) ? $type : 'number';
$readonly      = isset( $readonly ) ? $readonly : false;
$classes       = isset( $classes ) ? (array) $classes : array( 'input-text', 'qty', 'text' );
$placeholder   = isset( $placeholder ) ? $placeholder : '';
$inputmode     = isset( $inputmode ) ? $inputmode : 'numeric';
$autocomplete  = isset( $autocomplete ) ? $autocomplete : 'off';
?>
<div class="quantity">
	<?php if ( ! $readonly && 'hidden' !== $type ) : ?>
		<button type="button" class="qty-minus" aria-label="<?php esc_attr_e( 'تقليل الكمية', 'beauty-care-theme' ); ?>">-</button>
	<?php endif; ?>
	<?php do_action( 'woocommerce_before_quantity_input_field' ); ?>
	<input
		type="<?php echo esc_attr( $type ); ?>"
		<?php echo $readonly ? 'readonly="readonly"' : ''; ?>
		id="<?php echo esc_attr( $input_id ); ?>"
		class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
		name="<?php echo esc_attr( $input_name ); ?>"
		value="<?php echo esc_attr( $input_value ); ?>"
		aria-label="<?php esc_attr_e( 'الكمية', 'beauty-care-theme' ); ?>"
		min="<?php echo esc_attr( $min_value ); ?>"
		<?php if ( '' !== $max_value && 0 < $max_value ) : ?>
			max="<?php echo esc_attr( $max_value ); ?>"
		<?php endif; ?>
		<?php if ( ! $readonly ) : ?>
			step="<?php echo esc_attr( $step ); ?>"
			placeholder="<?php echo esc_attr( $placeholder ); ?>"
			inputmode="<?php echo esc_attr( $inputmode ); ?>"
			autocomplete="<?php echo esc_attr( $autocomplete ); ?>"
		<?php endif; ?>
	/>
	<?php do_action( 'woocommerce_after_quantity_input_field' ); ?>
	<?php if ( ! $readonly && 'hidden' !== $type ) : ?>
		<button type="button" class="qty-plus" aria-label="<?php esc_attr_e( 'زيادة الكمية', 'beauty-care-theme' ); ?>">+</button>
	<?php endif; ?>
</div>
