<?php
defined('ABSPATH') || exit;
$user = wp_get_current_user();
$first = get_user_meta($user->ID, 'billing_first_name', true) ?: $user->first_name;
$last = get_user_meta($user->ID, 'billing_last_name', true) ?: $user->last_name;
$fullname = trim($first . ' ' . $last) ?: $user->display_name;
$email = $user->user_email;
$phone = get_user_meta($user->ID, 'billing_phone', true) ?: '';
?>
<section class="profile-content">
  <h2><?php esc_html_e('الملف الشخصي', 'mallati-theme'); ?></h2>
  <form class="profile-form woocommerce-EditAccountForm edit-account" method="post">
    <?php do_action('woocommerce_edit_account_form_start'); ?>
    <input type="hidden" name="action" value="save_account_details" />
    <input type="hidden" name="account_last_name" id="account_last_name" value="<?php echo esc_attr($fullname); ?>" />
    <div class="form-row">
      <div class="form-group y-c-field">
        <label class="y-c-label" for="account_first_name"><?php esc_html_e('الاسم بالكامل', 'mallati-theme'); ?></label>
        <input type="text" name="account_first_name" id="account_first_name" class="y-c-input" value="<?php echo esc_attr($fullname); ?>" placeholder="<?php esc_attr_e('الاسم بالكامل', 'mallati-theme'); ?>" required />
      </div>
      <div class="form-group y-c-field">
        <label class="y-c-label" for="account_email"><?php esc_html_e('البريد الإلكتروني', 'mallati-theme'); ?></label>
        <input type="email" name="account_email" id="account_email" class="y-c-input" value="<?php echo esc_attr($email); ?>" placeholder="<?php esc_attr_e('example@email.com', 'mallati-theme'); ?>" />
      </div>
    </div>
    <div class="form-group y-c-field">
      <label class="y-c-label" for="billing_phone"><?php esc_html_e('رقم الجوال', 'mallati-theme'); ?></label>
      <input type="tel" name="billing_phone" id="billing_phone" class="y-c-input" value="<?php echo esc_attr($phone); ?>" placeholder="05xxxxxxxx" pattern="^05\d{8}$" dir="rtl" />
    </div>
    <hr class="form-divider" />
    <fieldset>
      <legend><?php esc_html_e('تغيير كلمة المرور', 'mallati-theme'); ?></legend>
      <div class="form-group y-c-field">
        <label class="y-c-label" for="password_current"><?php esc_html_e('كلمة المرور الحالية', 'mallati-theme'); ?></label>
        <div class="password-input-wrapper">
          <input type="password" name="password_current" id="password_current" class="y-c-input" autocomplete="current-password" />
          <button type="button" class="password-toggle" aria-label="<?php esc_attr_e('إظهار/إخفاء', 'mallati-theme'); ?>"><i class="fa-regular fa-eye"></i></button>
        </div>
      </div>
      <div class="form-group y-c-field">
        <label class="y-c-label" for="password_1"><?php esc_html_e('كلمة المرور الجديدة', 'mallati-theme'); ?></label>
        <div class="password-input-wrapper">
          <input type="password" name="password_1" id="password_1" class="y-c-input" autocomplete="new-password" />
          <button type="button" class="password-toggle" aria-label="<?php esc_attr_e('إظهار/إخفاء', 'mallati-theme'); ?>"><i class="fa-regular fa-eye"></i></button>
        </div>
      </div>
      <div class="form-group y-c-field">
        <label class="y-c-label" for="password_2"><?php esc_html_e('تأكيد كلمة المرور الجديدة', 'mallati-theme'); ?></label>
        <div class="password-input-wrapper">
          <input type="password" name="password_2" id="password_2" class="y-c-input" autocomplete="new-password" />
          <button type="button" class="password-toggle" aria-label="<?php esc_attr_e('إظهار/إخفاء', 'mallati-theme'); ?>"><i class="fa-regular fa-eye"></i></button>
        </div>
      </div>
    </fieldset>
    <?php do_action('woocommerce_edit_account_form'); ?>
    <div class="form-footer">
      <?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
      <button type="submit" name="save_account_details" value="<?php esc_attr_e('حفظ التغييرات', 'mallati-theme'); ?>" class="y-c-btn y-c-btn--primary"><?php esc_html_e('حفظ التغييرات', 'mallati-theme'); ?></button>
    </div>
    <?php do_action('woocommerce_edit_account_form_end'); ?>
  </form>
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var form = document.querySelector('form.edit-account');
  if (form) form.addEventListener('submit', function() {
    var first = document.getElementById('account_first_name');
    var last = document.getElementById('account_last_name');
    if (first && last) last.value = first.value;
  });
});
</script>
