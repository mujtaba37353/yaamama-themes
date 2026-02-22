<?php
/**
 * Edit address form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-address.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

$page_title = esc_html__( 'العناوين', 'khutaa-theme' );
$address_type_label = ( 'billing' === $load_address ) ? esc_html__( 'عنوان الفوترة', 'khutaa-theme' ) : esc_html__( 'عنوان الشحن', 'khutaa-theme' );

do_action( 'woocommerce_before_edit_account_address_form' ); ?>

<?php if ( ! $load_address ) : ?>
	<?php wc_get_template( 'myaccount/my-address.php' ); ?>
<?php else : ?>

<div class="content-section" id="edit-address-content">
	<div class="payment-form-section address-edit-section">
		<h3 class="section-title"><?php echo esc_html( $page_title ); ?></h3>
		<?php if ( $load_address ) : ?>
			<p class="address-type-label"><?php echo esc_html( $address_type_label ); ?></p>
		<?php endif; ?>

		<form method="post" class="address-form checkout-form-style" novalidate>

			<div class="woocommerce-address-fields">
				<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

				<div class="woocommerce-address-fields__field-wrapper">
					<?php
					// Arabic translations for address fields
					$arabic_labels = array(
						'first_name'  => __( 'الاسم الأول', 'khutaa-theme' ),
						'last_name'   => __( 'اسم العائلة', 'khutaa-theme' ),
						'company'      => __( 'اسم الشركة', 'khutaa-theme' ),
						'country'      => __( 'الدولة', 'khutaa-theme' ),
						'address_1'    => __( 'عنوان الشارع', 'khutaa-theme' ),
						'address_2'    => __( 'عنوان إضافي (اختياري)', 'khutaa-theme' ),
						'city'         => __( 'المدينة', 'khutaa-theme' ),
						'state'        => __( 'المنطقة / المحافظة', 'khutaa-theme' ),
						'postcode'     => __( 'الرمز البريدي', 'khutaa-theme' ),
						'phone'        => __( 'رقم الهاتف', 'khutaa-theme' ),
						'email'        => __( 'البريد الإلكتروني', 'khutaa-theme' ),
					);
					
					$arabic_placeholders = array(
						'address_1'    => __( 'رقم المنزل واسم الشارع', 'khutaa-theme' ),
						'address_2'    => __( 'عنوان إضافي (اختياري)', 'khutaa-theme' ),
						'city'         => __( 'المدينة', 'khutaa-theme' ),
						'state'        => __( 'المنطقة / المحافظة', 'khutaa-theme' ),
						'postcode'     => __( 'الرمز البريدي', 'khutaa-theme' ),
					);
					
					foreach ( $address as $key => $field ) {
						$field_value = wc_get_post_data_by_key( $key, $field['value'] );
						
						// Get field name without prefix (billing_ or shipping_)
						$field_name = str_replace( array( 'billing_', 'shipping_' ), '', $key );
						
						// Translate label to Arabic
						if ( isset( $arabic_labels[ $field_name ] ) ) {
							$field['label'] = $arabic_labels[ $field_name ];
						}
						
						// Translate placeholder to Arabic
						if ( isset( $arabic_placeholders[ $field_name ] ) && empty( $field['placeholder'] ) ) {
							$field['placeholder'] = $arabic_placeholders[ $field_name ];
						}
						
						// Convert country field from select to text input
						if ( ( $key === 'billing_country' || $key === 'shipping_country' ) && isset( $field['type'] ) && $field['type'] === 'country' ) {
							$field['type'] = 'text';
							$field['class'] = array( 'form-row-wide', 'address-field' );
							// Get country name if value exists, otherwise default to Saudi Arabia
							if ( $field_value ) {
								$countries = WC()->countries->get_countries();
								$field_value = isset( $countries[ $field_value ] ) ? $countries[ $field_value ] : $field_value;
							} else {
								$field_value = __( 'السعودية', 'khutaa-theme' );
							}
						}
						
						// Convert state field from select to text input if it's a select
						if ( ( $key === 'billing_state' || $key === 'shipping_state' ) && isset( $field['type'] ) && $field['type'] === 'state' ) {
							$field['type'] = 'text';
							$field['class'] = array( 'form-row-wide', 'address-field' );
						}
						
						woocommerce_form_field( $key, $field, $field_value );
					}
					?>
				</div>

				<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

				<div class="form-actions address-form-actions">
					<button type="submit" class="btn-save btn-primary" name="save_address" value="<?php esc_attr_e( 'حفظ العنوان', 'khutaa-theme' ); ?>">
						<?php esc_html_e( 'حفظ العنوان', 'khutaa-theme' ); ?>
					</button>
					<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
					<input type="hidden" name="action" value="edit_address" />
				</div>
			</div>

		</form>
	</div>
</div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>
