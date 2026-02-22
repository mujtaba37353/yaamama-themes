<?php
/**
 * Stationary override: Edit address form.
 *
 * @package stationary-theme
 */

defined( 'ABSPATH' ) || exit;

$page_title = ( 'billing' === $load_address ) ? esc_html__( 'عنوان الفوترة', 'stationary-theme' ) : esc_html__( 'عنوان الشحن', 'stationary-theme' );

do_action( 'woocommerce_before_edit_account_address_form' );
?>

<?php if ( ! $load_address ) : ?>
	<?php wc_get_template( 'myaccount/my-address.php' ); ?>
<?php else : ?>
	<?php
	// #region agent log
	$payload = array(
		'runId'        => 'initial',
		'hypothesisId' => 'A3',
		'location'     => 'woocommerce/myaccount/form-edit-address.php:1',
		'message'      => 'Address form override rendered',
		'data'         => array(
			'path'        => isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '',
			'loadAddress' => $load_address,
			'fieldsCount' => is_array( $address ) ? count( $address ) : 0,
		),
		'timestamp'    => round( microtime( true ) * 1000 ),
	);
	@file_put_contents( 'c:\\Users\\mujtaba\\Local Sites\\yamama-platform\\.cursor\\debug.log', wp_json_encode( $payload ) . PHP_EOL, FILE_APPEND );
	// #endregion
	?>
	<div class="billing-address-form">
		<h3><?php echo esc_html( apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $load_address ) ); ?></h3>
		<form method="post" novalidate class="woocommerce-AddressForm address-form">
			<div class="woocommerce-address-fields">
				<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>
				<div class="woocommerce-address-fields__field-wrapper">
					<?php
					foreach ( $address as $key => $field ) {
						woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
					}
					?>
				</div>
				<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>
				<p class="form-actions">
					<button type="submit" class="button btn secondary-button" name="save_address" value="<?php esc_attr_e( 'حفظ العنوان', 'stationary-theme' ); ?>">
						<?php esc_html_e( 'حفظ العنوان', 'stationary-theme' ); ?>
					</button>
					<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
					<input type="hidden" name="action" value="edit_address" />
				</p>
			</div>
		</form>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>
