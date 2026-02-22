<?php
/**
 * Edit account form — Beauty Care design
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.7.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' );
?>

<form class="woocommerce-EditAccountForm edit-account" action="" method="post" id="profile-form" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> novalidate>
	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
	<div class="form-row">
		<div class="form-group">
			<label for="account_first_name"><?php esc_html_e( 'الاسم بالكامل', 'beauty-care-theme' ); ?> <span class="required">*</span></label>
			<input type="text" name="account_first_name" id="account_first_name" autocomplete="name" value="<?php echo esc_attr( trim( $user->first_name . ' ' . $user->last_name ) ); ?>" />
			<input type="hidden" name="account_last_name" value="" />
		</div>
	</div>
	<div class="form-row">
		<div class="form-group">
			<label for="account_email"><?php esc_html_e( 'البريد الإلكتروني', 'beauty-care-theme' ); ?> <span class="required">*</span></label>
			<input type="email" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
		</div>
	</div>
	<?php do_action( 'woocommerce_edit_account_form_fields' ); ?>
	<div class="form-row">
		<div class="form-group">
			<label><?php esc_html_e( 'تغيير كلمة المرور', 'beauty-care-theme' ); ?></label>
			<div class="password-input-wrapper">
				<input type="password" name="password_current" id="password_current" autocomplete="off" placeholder="<?php esc_attr_e( 'كلمة المرور الحالية', 'beauty-care-theme' ); ?>" />
				<button type="button" class="password-toggle" aria-label="<?php esc_attr_e( 'إظهار/إخفاء كلمة المرور', 'beauty-care-theme' ); ?>">
					<i class="fa-regular fa-eye"></i>
				</button>
			</div>
			<div class="password-input-wrapper">
				<input type="password" name="password_1" id="password_1" autocomplete="off" placeholder="<?php esc_attr_e( 'كلمة المرور الجديدة', 'beauty-care-theme' ); ?>" />
				<button type="button" class="password-toggle" aria-label="<?php esc_attr_e( 'إظهار/إخفاء كلمة المرور', 'beauty-care-theme' ); ?>">
					<i class="fa-regular fa-eye"></i>
				</button>
			</div>
			<div class="password-input-wrapper">
				<input type="password" name="password_2" id="password_2" autocomplete="off" placeholder="<?php esc_attr_e( 'تأكيد كلمة المرور الجديدة', 'beauty-care-theme' ); ?>" />
				<button type="button" class="password-toggle" aria-label="<?php esc_attr_e( 'إظهار/إخفاء كلمة المرور', 'beauty-care-theme' ); ?>">
					<i class="fa-regular fa-eye"></i>
				</button>
			</div>
		</div>
	</div>
	<?php do_action( 'woocommerce_edit_account_form' ); ?>
	<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
	<button type="submit" class="btn main-button btn-black" name="save_account_details" value="<?php esc_attr_e( 'حفظ التعديلات', 'beauty-care-theme' ); ?>"><?php esc_html_e( 'حفظ التعديلات', 'beauty-care-theme' ); ?></button>
	<input type="hidden" name="action" value="save_account_details" />
	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
