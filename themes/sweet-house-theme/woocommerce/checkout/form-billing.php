<?php
/**
 * Checkout billing — Sweet House design (معلومات التوصيل).
 * مطابق لـ: sweet-house/components/payment/y-c-payment-form.html
 *
 * @package Sweet_House_Theme
 */

defined( 'ABSPATH' ) || exit;

$fields = $checkout->get_checkout_fields( 'billing' );
?>
<div class="woocommerce-billing-fields">
	<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	<div class="woocommerce-billing-fields__field-wrapper checkout-design-fields">
		<?php
		$field_order = array( 'billing_first_name', 'billing_email', 'billing_phone', 'billing_address_1', 'billing_country' );
		foreach ( $field_order as $key ) {
			if ( ! isset( $fields[ $key ] ) ) {
				continue;
			}
			$field  = apply_filters( 'sweet_house_checkout_field_args', $fields[ $key ], $key );
			$value  = $checkout->get_value( $key );
			$type   = isset( $field['type'] ) ? $field['type'] : 'text';
			$label  = isset( $field['label'] ) ? $field['label'] : '';
			$ph     = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
			$req    = isset( $field['required'] ) && $field['required'];
			$label .= $req ? ' *' : '';

			if ( 'billing_country' === $key ) {
				$field['default'] = 'SA';
				$value            = $value ? $value : 'SA';
				?>
				<div class="form-field-container billing-country-hidden-wrap" style="display:none;">
					<?php woocommerce_form_field( $key, $field, $value ); ?>
				</div>
				<?php
				continue;
			}

			$input_attrs = array(
				'type'        => ( 'email' === $type ) ? 'email' : ( ( 'tel' === $type ) ? 'tel' : 'text' ),
				'class'       => 'input-text hidden-input',
				'name'        => $key,
				'id'          => $key,
				'placeholder' => $ph,
				'value'       => $value,
				'autocomplete' => isset( $field['autocomplete'] ) ? $field['autocomplete'] : '',
			);
			if ( $req ) {
				$input_attrs['required'] = 'required';
			}
			?>
			<div class="form-field-container">
				<span class="field-label"><?php echo esc_html( $label ); ?></span>
				<input <?php
				foreach ( $input_attrs as $ak => $av ) {
					if ( '' !== (string) $av ) {
						echo esc_attr( $ak ) . '="' . esc_attr( $av ) . '" ';
					}
				}
				?> />
				<span class="field-placeholder"><?php echo esc_html( $ph ); ?></span>
			</div>
			<?php
		}
		?>
	</div>

	<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
</div>

<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
	<div class="woocommerce-account-fields section">
		<?php if ( ! $checkout->is_registration_required() ) : ?>
			<div class="checkbox-container">
				<label for="createaccount" class="checkbox-label"><?php esc_html_e( 'هل تود إنشاء حساب جديد؟', 'sweet-house-theme' ); ?></label>
				<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox checkbox-input" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" />
			</div>
		<?php endif; ?>

		<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

		<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>
			<div class="create-account account-password-fields">
				<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
					<?php
					$field  = apply_filters( 'sweet_house_checkout_field_args', $field, $key );
					$value  = $checkout->get_value( $key );
					$label  = isset( $field['label'] ) ? $field['label'] : '';
					$ph     = isset( $field['placeholder'] ) ? $field['placeholder'] : __( '********', 'sweet-house-theme' );
					$req    = isset( $field['required'] ) && $field['required'];
					$label .= $req ? ' *' : '';
					?>
					<div class="form-field-container">
						<span class="field-label"><?php echo esc_html( $label ); ?></span>
						<input type="password" class="input-text hidden-input" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $ph ); ?>" value="<?php echo esc_attr( $value ); ?>" autocomplete="new-password" <?php echo $req ? ' required' : ''; ?> />
						<span class="field-placeholder"><?php echo esc_html( $ph ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
	</div>
<?php endif; ?>
