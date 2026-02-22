<?php
/**
 * Checkout billing information form (Custom)
 *
 * @package KhutaaTheme
 * @global WC_Checkout $checkout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$checkout = WC()->checkout();
?>

<div class="woocommerce-billing-fields">
	<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	<div class="woocommerce-billing-fields__field-wrapper">
		<?php
		$fields = $checkout->get_checkout_fields( 'billing' );
		
		// Sort fields by priority
		uasort( $fields, 'wc_checkout_fields_uasort_comparison' );
		
		foreach ( $fields as $key => $field ) {
			// Skip unwanted fields
			if ( in_array( $key, array( 'billing_first_name', 'billing_last_name', 'billing_company' ) ) ) {
				continue;
			}
			
			// Custom field: Full Name
			if ( $key === 'billing_full_name' ) {
				woocommerce_form_field( 
					$key, 
					array(
						'type'        => 'text',
						'label'       => __( 'الاسم الكامل', 'khutaa-theme' ),
						'required'    => true,
						'class'       => array( 'form-row-wide' ),
						'autocomplete' => 'name',
						'priority'    => 10,
					), 
					$checkout->get_value( 'billing_full_name' ) ?: ( $checkout->get_value( 'billing_first_name' ) . ' ' . $checkout->get_value( 'billing_last_name' ) )
				);
				continue;
			}
			
			// Custom handling for country field (convert to text)
			if ( $key === 'billing_country' && isset( $field['type'] ) && $field['type'] === 'text' ) {
				$field_value = $checkout->get_value( 'billing_country' ) ?: __( 'السعودية', 'khutaa-theme' );
				woocommerce_form_field( 
					$key, 
					$field, 
					$field_value
				);
				continue;
			}
			
			// Standard fields
			woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
		}
		?>
	</div>

	<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
</div>

<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
	<div class="woocommerce-account-fields">
		<?php if ( ! $checkout->is_registration_required() ) : ?>
			<p class="form-row form-row-wide create-account">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( 'إنشاء حساب؟', 'khutaa-theme' ); ?></span>
				</label>
			</p>
		<?php endif; ?>

		<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

		<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>
			<div class="create-account">
				<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
					<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
				<?php endforeach; ?>
				<div class="clear"></div>
			</div>
		<?php endif; ?>

		<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
	</div>
<?php endif; ?>
