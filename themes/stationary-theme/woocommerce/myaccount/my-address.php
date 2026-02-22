<?php
/**
 * Stationary override: My addresses list.
 *
 * @package stationary-theme
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();
$address     = wc_get_account_formatted_address( 'billing' );
?>

<?php
// #region agent log
$payload = array(
	'runId'        => 'initial',
	'hypothesisId' => 'A4',
	'location'     => 'woocommerce/myaccount/my-address.php:1',
	'message'      => 'Address list override rendered',
	'data'         => array(
		'path'          => isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '',
		'customerId'    => (int) $customer_id,
		'hasAddress'    => ! empty( $address ),
		'addressLength' => is_string( $address ) ? strlen( $address ) : 0,
	),
	'timestamp'    => round( microtime( true ) * 1000 ),
);
@file_put_contents( 'c:\\Users\\mujtaba\\Local Sites\\yamama-platform\\.cursor\\debug.log', wp_json_encode( $payload ) . PHP_EOL, FILE_APPEND );
// #endregion
?>

<div class="billing-address-container">
	<div class="billing-header">
		<h3><?php esc_html_e( 'عنوان الفوترة', 'stationary-theme' ); ?></h3>
		<a class="edit-address-link" href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'billing' ) ); ?>">
			<?php esc_html_e( 'تعديل العنوان', 'stationary-theme' ); ?>
		</a>
	</div>
	<div class="billing-details">
		<?php if ( ! empty( $address ) ) : ?>
			<div class="detail-row">
				<div class="value"><?php echo wp_kses_post( nl2br( $address ) ); ?></div>
			</div>
		<?php else : ?>
			<div class="detail-row">
				<span class="value"><?php esc_html_e( 'لا يوجد عنوان محفوظ حاليا.', 'stationary-theme' ); ?></span>
			</div>
		<?php endif; ?>
	</div>
</div>
