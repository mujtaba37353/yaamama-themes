<?php
/**
 * Edit Address Form — override
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

$page_title = ( 'billing' === $load_address ) ? esc_html__( 'عنوان الفواتير', 'beauty-time-theme' ) : esc_html__( 'عنوان الشحن', 'beauty-time-theme' );

do_action( 'woocommerce_before_edit_account_address_form' );

if ( ! $load_address ) {
	wc_get_template( 'myaccount/my-address.php' );
} else {
	?>
	<div class="main-content tab-content active" data-content="profile">
		<div class="account-form-card">
			<h2 class="section-title">
				<?php echo apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $load_address ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</h2>
			<?php wc_print_notices(); ?>
			<form method="post" class="account-form">
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
						<button type="submit" class="btn-save button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="save_address" value="<?php esc_attr_e( 'حفظ العنوان', 'beauty-time-theme' ); ?>"><?php esc_html_e( 'حفظ العنوان', 'beauty-time-theme' ); ?></button>
						<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
						<input type="hidden" name="action" value="edit_address" />
					</p>
				</div>
			</form>
		</div>
	</div>
	<?php
}

do_action( 'woocommerce_after_edit_account_address_form' );
