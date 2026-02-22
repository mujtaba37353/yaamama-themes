<?php
/**
 * My Addresses - Unified Addresses Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

// Get all addresses (billing and shipping)
$get_addresses = array();
if ( wc_shipping_enabled() ) {
	$get_addresses['billing'] = __( 'عنوان الفوترة', 'khutaa-theme' );
	if ( ! wc_ship_to_billing_address_only() ) {
		$get_addresses['shipping'] = __( 'عنوان الشحن', 'khutaa-theme' );
	}
} else {
	$get_addresses['billing'] = __( 'العنوان', 'khutaa-theme' );
}

$get_addresses = apply_filters( 'woocommerce_my_account_get_addresses', $get_addresses, $customer_id );
?>

<div class="content-section" id="addresses-content">
	<h2 class="section-title"><?php esc_html_e( 'العناوين', 'khutaa-theme' ); ?></h2>
	
	<p class="address-description">
		<?php echo apply_filters( 'woocommerce_my_account_my_address_description', esc_html__( 'العناوين التالية سيتم استخدامها افتراضياً في صفحة الدفع.', 'khutaa-theme' ) ); ?>
	</p>

	<div class="addresses-list">
		<?php foreach ( $get_addresses as $name => $address_title ) : ?>
			<?php
				$address = wc_get_account_formatted_address( $name );
			?>
			<div class="address-card woocommerce-Address">
				<header class="address-header">
					<h3 class="address-title"><?php echo esc_html( $address_title ); ?></h3>
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="btn-edit">
						<?php
							printf(
								/* translators: %s: Address title */
								$address ? esc_html__( 'تعديل %s', 'khutaa-theme' ) : esc_html__( 'إضافة %s', 'khutaa-theme' ),
								esc_html( $address_title )
							);
						?>
					</a>
				</header>
				<div class="address-content">
					<?php
						if ( $address ) {
							echo wp_kses_post( $address );
						} else {
							echo '<p class="no-address">' . esc_html__( 'لم تقم بإعداد هذا النوع من العنوان بعد.', 'khutaa-theme' ) . '</p>';
						}

						/**
						 * Used to output content after core address fields.
						 *
						 * @param string $name Address type.
						 * @since 8.7.0
						 */
						do_action( 'woocommerce_my_account_after_my_address', $name );
					?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
