<?php
/**
 * Edit Account Form — override
 * Uses profile.html "تفاصيل الحساب" form structure
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' );
$user = wp_get_current_user();
?>
<div class="main-content tab-content active" data-content="profile">
	<div class="account-form-card">
		<h2 class="section-title"><?php esc_html_e( 'المعلومات الشخصية', 'beauty-time-theme' ); ?></h2>
		<form class="woocommerce-EditAccountForm edit-account account-form" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >
			<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
			<div class="form-group">
				<div class="group">
					<label for="account_first_name">
						<i class="fas fa-user"></i>
						<?php esc_html_e( 'الاسم الأول', 'beauty-time-theme' ); ?>
					</label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" placeholder="<?php esc_attr_e( 'أدخل الاسم الأول', 'beauty-time-theme' ); ?>" />
				</div>
				<div class="group">
					<label for="account_last_name">
						<i class="fas fa-user"></i>
						<?php esc_html_e( 'اسم العائلة', 'beauty-time-theme' ); ?>
					</label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" placeholder="<?php esc_attr_e( 'أدخل اسم العائلة', 'beauty-time-theme' ); ?>" />
				</div>
			</div>
			<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="account_display_name">
					<i class="fas fa-user"></i>
					<?php esc_html_e( 'اسم العرض', 'beauty-time-theme' ); ?>&nbsp;<span class="required">*</span>
				</label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" />
			</div>
			<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="account_email">
					<i class="fas fa-envelope"></i>
					<?php esc_html_e( 'البريد الإلكتروني', 'beauty-time-theme' ); ?>&nbsp;<span class="required">*</span>
				</label>
				<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" placeholder="example@email.com" />
			</div>
			<fieldset>
				<legend><?php esc_html_e( 'تغيير كلمة المرور', 'beauty-time-theme' ); ?></legend>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="password_current"><?php esc_html_e( 'كلمة المرور الحالية (اتركها فارغة إذا لم تكن تريد تغييرها)', 'beauty-time-theme' ); ?></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="off" />
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="password_1"><?php esc_html_e( 'كلمة المرور الجديدة (اتركها فارغة إذا لم تكن تريد تغييرها)', 'beauty-time-theme' ); ?></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="off" />
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="password_2"><?php esc_html_e( 'تأكيد كلمة المرور الجديدة', 'beauty-time-theme' ); ?></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="off" />
				</p>
			</fieldset>
			<div class="clear"></div>
			<?php do_action( 'woocommerce_edit_account_form' ); ?>
			<p class="form-actions">
				<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
				<button type="submit" class="btn-save woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="save_account_details" value="<?php esc_attr_e( 'حفظ التغييرات', 'beauty-time-theme' ); ?>">
					<i class="fas fa-save"></i>
					<?php esc_html_e( 'حفظ التغييرات', 'beauty-time-theme' ); ?>
				</button>
				<input type="hidden" name="action" value="save_account_details" />
			</p>
			<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
		</form>
	</div>
</div>
<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
