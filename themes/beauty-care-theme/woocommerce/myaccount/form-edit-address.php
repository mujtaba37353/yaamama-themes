<?php
/**
 * Edit address form — Beauty Care design
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

$title = ( 'billing' === $load_address ) ? __( 'عنوان الفواتير', 'beauty-care-theme' ) : __( 'عنوان الشحن', 'beauty-care-theme' );
$title = apply_filters( 'woocommerce_my_account_edit_address_title', $title, $load_address );

do_action( 'woocommerce_before_edit_account_address_form' );
?>

<?php if ( ! $load_address ) : ?>
	<?php wc_get_template( 'myaccount/my-address.php' ); ?>
<?php else : ?>
<div class="billing-address-form">
	<h3><?php echo esc_html( $title ); ?></h3>
	<form method="post" id="address-edit-form" class="woocommerce-address-fields" novalidate>
		<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>
		<div class="woocommerce-address-fields__field-wrapper">
			<?php foreach ( $address as $key => $field ) : ?>
				<?php woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) ); ?>
			<?php endforeach; ?>
		</div>
		<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>
		<div class="form-actions">
			<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
			<input type="hidden" name="action" value="edit_address" />
			<button type="submit" class="btn main-button btn-black" name="save_address" value="<?php esc_attr_e( 'حفظ العنوان', 'beauty-care-theme' ); ?>"><?php esc_html_e( 'حفظ العنوان', 'beauty-care-theme' ); ?></button>
		</div>
	</form>
</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>
