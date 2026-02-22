<?php
/**
 * Edit address form — Sweet House design (Arabic labels)
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

$page_title = ( 'billing' === $load_address ) ? __( 'العنوان', 'sweet-house-theme' ) : __( 'عنوان الشحن', 'sweet-house-theme' );
$page_title = apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $load_address );

do_action( 'woocommerce_before_edit_account_address_form' );
?>

<?php if ( ! $load_address ) : ?>
	<?php wc_get_template( 'myaccount/my-address.php' ); ?>
<?php else : ?>

<div class="address-empty-state address-edit-form">
	<h2 class="section-title"><?php esc_html_e( 'أدخل العنوان الجديد', 'sweet-house-theme' ); ?> *</h2>

	<div class="address-form">
		<form method="post" novalidate>
			<div class="woocommerce-address-fields">
				<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

				<div class="woocommerce-address-fields__field-wrapper address-form-fields">
					<div class="form-row">
						<?php
						$name_fields = array();
						foreach ( array( 'first_name', 'last_name' ) as $part ) {
							$key = $load_address . '_' . $part;
							if ( isset( $address[ $key ] ) ) {
								$name_fields[ $key ] = $address[ $key ];
							}
						}
						foreach ( $name_fields as $key => $field ) :
							?>
							<div class="form-group">
								<?php woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) ); ?>
							</div>
							<?php
						endforeach;
						?>
					</div>

					<?php if ( isset( $address[ $load_address . '_country' ] ) ) : ?>
						<?php
						$country_field = $address[ $load_address . '_country' ];
						$country_value = wc_get_post_data_by_key( $load_address . '_country', ! empty( $country_field['value'] ) ? $country_field['value'] : 'SA' );
						?>
						<div class="form-group">
							<label><?php esc_html_e( 'الدولة / المنطقة', 'sweet-house-theme' ); ?> *</label>
							<p class="country-text"><?php esc_html_e( 'المملكة العربية السعودية', 'sweet-house-theme' ); ?></p>
							<input type="hidden" name="<?php echo esc_attr( $load_address . '_country' ); ?>" value="<?php echo esc_attr( $country_value ); ?>" />
						</div>
					<?php endif; ?>

					<?php
					$full_width_keys = array( 'address_1', 'address_2', 'city', 'state', 'postcode', 'phone', 'email' );
					foreach ( $full_width_keys as $part ) {
						$key = $load_address . '_' . $part;
						if ( ! isset( $address[ $key ] ) ) {
							continue;
						}
						$field = $address[ $key ];
						$is_optional = ( 'address_2' === $part );
						if ( $is_optional ) {
							$field['required'] = false;
							$field['label'] = '';
							$field['placeholder'] = __( 'رقم الشقة (اختياري)', 'sweet-house-theme' );
						}
						if ( 'address_1' === $part ) {
							$field['placeholder'] = __( 'عنوان الشارع رقم المنزل', 'sweet-house-theme' );
						}
						if ( 'phone' === $part ) {
							$field['label'] = __( 'الهاتف', 'sweet-house-theme' );
						}
						if ( 'email' === $part ) {
							$field['label'] = __( 'البريد الإلكتروني', 'sweet-house-theme' );
						}
						if ( 'state' === $part ) {
							$field['label'] = __( 'المحافظة', 'sweet-house-theme' );
						}
						?>
						<div class="form-group full-width">
							<?php woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) ); ?>
						</div>
						<?php
					}
					?>
				</div>

				<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

				<button type="submit" class="btn-save button" name="save_address" value="<?php esc_attr_e( 'حفظ العنوان', 'sweet-house-theme' ); ?>"><?php esc_html_e( 'حفظ العنوان', 'sweet-house-theme' ); ?></button>
				<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
				<input type="hidden" name="action" value="edit_address" />
			</div>
		</form>
	</div>
</div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>
