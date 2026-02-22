<?php
/**
 * My Address — Sweet House design (عنوان واحد: العنوان)
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

$customer_id   = get_current_user_id();
$address_type  = 'billing';
$address_title = __( 'العنوان', 'sweet-house-theme' );
$address       = wc_get_account_formatted_address( $address_type );
$edit_url      = wc_get_endpoint_url( 'edit-address', $address_type );
?>

<div class="address-filled-state">
	<p class="address-form note" style="margin-bottom: 1.5rem;">
		<?php echo apply_filters( 'woocommerce_my_account_my_address_description', esc_html__( 'سيتم استخدام هذا العنوان في صفحة الدفع افتراضياً.', 'sweet-house-theme' ), $customer_id ); ?>
	</p>

	<div class="address-block" style="margin-bottom: 2rem;">
		<div class="address-header">
			<h2 class="address-card-title"><?php echo esc_html( $address_title ); ?></h2>
			<div class="buttons">
				<?php if ( $address ) : ?>
					<form method="post" class="address-clear-form" style="display: inline;" onsubmit="return confirm('<?php echo esc_attr( __( 'هل أنت متأكد من مسح العنوان؟', 'sweet-house-theme' ) ); ?>');">
						<?php wp_nonce_field( 'sweet_house_clear_address', 'sweet_house_clear_address_nonce' ); ?>
						<input type="hidden" name="sweet_house_action" value="clear_address" />
						<button type="submit" class="btn-clear"><?php esc_html_e( 'مسح العنوان', 'sweet-house-theme' ); ?></button>
					</form>
					<a href="<?php echo esc_url( $edit_url ); ?>" class="btn-edit"><?php esc_html_e( 'تعديل العنوان', 'sweet-house-theme' ); ?></a>
				<?php else : ?>
					<a href="<?php echo esc_url( $edit_url ); ?>" class="btn-edit"><?php esc_html_e( 'إضافة العنوان', 'sweet-house-theme' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
		<?php if ( $address ) : ?>
			<div class="address-display-card">
				<div class="address-info">
					<?php echo wp_kses_post( $address ); ?>
				</div>
				<?php do_action( 'woocommerce_my_account_after_my_address', $address_type ); ?>
			</div>
		<?php else : ?>
			<p class="address-empty-message"><?php esc_html_e( 'لم تقم بإضافة العنوان بعد.', 'sweet-house-theme' ); ?></p>
		<?php endif; ?>
	</div>
</div>
