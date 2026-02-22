<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.7.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hook - woocommerce_before_edit_account_form.
 *
 * @since 2.6.0
 */
do_action( 'woocommerce_before_edit_account_form' );

// Get current user data
$user = wp_get_current_user();
?>

<div class="content-section" id="edit-account-content">
	<h2 class="section-title"><?php esc_html_e( 'تفاصيل الحساب', 'khutaa-theme' ); ?></h2>

	<form class="woocommerce-EditAccountForm edit-account profile-form" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

		<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

		<div class="form-row">
			<div class="form-group">
				<label for="account_first_name"><?php esc_html_e( 'الاسم الأول', 'khutaa-theme' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" aria-required="true" />
			</div>
			<div class="form-group">
				<label for="account_last_name"><?php esc_html_e( 'اسم العائلة', 'khutaa-theme' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" aria-required="true" />
			</div>
		</div>

		<div class="form-group">
			<label for="account_display_name"><?php esc_html_e( 'اسم العرض', 'khutaa-theme' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" aria-required="true" />
			<small><?php esc_html_e( 'هذا هو الاسم الذي سيظهر في قسم الحساب والمراجعات', 'khutaa-theme' ); ?></small>
		</div>

		<div class="form-group">
			<label for="account_email"><?php esc_html_e( 'البريد الإلكتروني', 'khutaa-theme' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" aria-required="true" />
		</div>

		<?php
		/**
		 * Hook where additional fields should be rendered.
		 *
		 * @since 8.7.0
		 */
		do_action( 'woocommerce_edit_account_form_fields' );
		?>

		<fieldset class="password-change">
			<legend><?php esc_html_e( 'تغيير كلمة المرور', 'khutaa-theme' ); ?></legend>

			<div class="form-group">
				<label for="password_current"><?php esc_html_e( 'كلمة المرور الحالية (اتركها فارغة للبقاء كما هي)', 'khutaa-theme' ); ?></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="off" />
			</div>
			<div class="form-group">
				<label for="password_1"><?php esc_html_e( 'كلمة المرور الجديدة (اتركها فارغة للبقاء كما هي)', 'khutaa-theme' ); ?></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="off" />
			</div>
			<div class="form-group">
				<label for="password_2"><?php esc_html_e( 'تأكيد كلمة المرور الجديدة', 'khutaa-theme' ); ?></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="off" />
			</div>
		</fieldset>

		<?php
		/**
		 * My Account edit account form.
		 *
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_edit_account_form' );
		?>

		<p>
			<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
			<button type="submit" class="btn-save woocommerce-Button button" name="save_account_details" value="<?php esc_attr_e( 'حفظ التغييرات', 'khutaa-theme' ); ?>"><?php esc_html_e( 'حفظ', 'khutaa-theme' ); ?></button>
			<input type="hidden" name="action" value="save_account_details" />
		</p>

		<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
	</form>
</div>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
