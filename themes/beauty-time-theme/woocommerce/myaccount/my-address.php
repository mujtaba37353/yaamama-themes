<?php
/**
 * My Addresses — override
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing'  => __( 'عنوان الفواتير', 'beauty-time-theme' ),
			'shipping' => __( 'عنوان الشحن', 'beauty-time-theme' ),
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __( 'عنوان الفواتير', 'beauty-time-theme' ),
		),
		$customer_id
	);
}

$oldcol = 1;
$col    = 1;
?>
<div class="main-content tab-content active" data-content="profile">
	<div class="account-form-card">
		<h2 class="section-title"><?php esc_html_e( 'العناوين', 'beauty-time-theme' ); ?></h2>
		<p>
			<?php echo apply_filters( 'woocommerce_my_account_my_address_description', esc_html__( 'يمكنك تعديل عناوين الفواتير والشحن أدناه.', 'beauty-time-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</p>

		<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
			<div class="u-columns woocommerce-Addresses col2-set addresses">
		<?php endif; ?>

		<?php foreach ( $get_addresses as $name => $address_title ) : ?>
			<div class="u-column<?php echo ( ( $col = $col * -1 ) < 0 ) ? 1 : 2; ?> col-<?php echo ( ( $oldcol = $oldcol * -1 ) < 0 ) ? 1 : 2; ?> woocommerce-Address">
				<header class="woocommerce-Address-title title">
					<h3><?php echo esc_html( $address_title ); ?></h3>
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="edit"><?php esc_html_e( 'تعديل', 'beauty-time-theme' ); ?></a>
				</header>
				<address>
					<?php
					$address = wc_get_account_formatted_address( $name );
					echo $address ? wp_kses_post( $address ) : esc_html_e( 'لم يتم تعيين عنوان حتى الآن.', 'beauty-time-theme' );
					?>
				</address>
			</div>
		<?php endforeach; ?>

		<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
			</div>
		<?php endif; ?>
	</div>
</div>
