<?php
/**
 * Edit account form
 *
 * @package Nafhat Theme
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_edit_account_form'); ?>

<h2><?php esc_html_e('الملف الشخصي', 'nafhat'); ?></h2>

<form class="woocommerce-EditAccountForm edit-account profile-form" action="" method="post" <?php do_action('woocommerce_edit_account_form_tag'); ?>>

    <?php do_action('woocommerce_edit_account_form_start'); ?>

    <div class="form-row">
        <div class="form-group woocommerce-form-row woocommerce-form-row--first">
            <label for="account_first_name"><?php esc_html_e('الاسم الأول', 'nafhat'); ?>&nbsp;<span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr($user->first_name); ?>" />
        </div>
        <div class="form-group woocommerce-form-row woocommerce-form-row--last">
            <label for="account_last_name"><?php esc_html_e('الاسم الأخير', 'nafhat'); ?>&nbsp;<span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr($user->last_name); ?>" />
        </div>
    </div>

    <div class="form-group woocommerce-form-row woocommerce-form-row--wide">
        <label for="account_display_name"><?php esc_html_e('الاسم المعروض', 'nafhat'); ?>&nbsp;<span class="required">*</span></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr($user->display_name); ?>" />
        <span class="description"><?php esc_html_e('سيظهر هذا الاسم في حسابك وفي المراجعات', 'nafhat'); ?></span>
    </div>

    <div class="form-group woocommerce-form-row woocommerce-form-row--wide">
        <label for="account_email"><?php esc_html_e('البريد الإلكتروني', 'nafhat'); ?>&nbsp;<span class="required">*</span></label>
        <input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr($user->user_email); ?>" />
    </div>

    <hr class="form-divider" />

    <h3><?php esc_html_e('تغيير كلمة المرور', 'nafhat'); ?></h3>

    <div class="form-group woocommerce-form-row woocommerce-form-row--wide">
        <label for="password_current"><?php esc_html_e('كلمة المرور الحالية (اتركها فارغة إذا لم ترد تغييرها)', 'nafhat'); ?></label>
        <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="off" placeholder="********" />
    </div>

    <div class="form-row">
        <div class="form-group woocommerce-form-row woocommerce-form-row--first">
            <label for="password_1"><?php esc_html_e('كلمة المرور الجديدة (اتركها فارغة إذا لم ترد تغييرها)', 'nafhat'); ?></label>
            <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="off" placeholder="********" />
        </div>
        <div class="form-group woocommerce-form-row woocommerce-form-row--last">
            <label for="password_2"><?php esc_html_e('تأكيد كلمة المرور الجديدة', 'nafhat'); ?></label>
            <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="off" placeholder="********" />
        </div>
    </div>

    <?php do_action('woocommerce_edit_account_form'); ?>

    <hr class="form-divider" />

    <div class="form-footer">
        <?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
        <button type="submit" class="woocommerce-Button button" name="save_account_details" value="<?php esc_attr_e('حفظ التغييرات', 'nafhat'); ?>"><?php esc_html_e('حفظ التغييرات', 'nafhat'); ?></button>
        <input type="hidden" name="action" value="save_account_details" />
    </div>

    <?php do_action('woocommerce_edit_account_form_end'); ?>
</form>

<?php do_action('woocommerce_after_edit_account_form'); ?>
