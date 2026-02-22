<?php
/**
 * Checkout billing - مطابقة تصميم مولاتي
 */
defined('ABSPATH') || exit;
?>
<div class="delivery-form">
  <div class="delivery-info">
    <h3 class="section-title"><?php esc_html_e('معلومات التوصيل', 'mallati-theme'); ?></h3>
    <p class="section-description"><?php esc_html_e('سوف نستخدم هذا البريد الإلكتروني لإرسال التفاصيل والتحديثات إليك حول طلبك.', 'mallati-theme'); ?></p>
  </div>
  <div class="billing-address">
    <h3 class="section-title"><?php esc_html_e('معلومات التوصيل', 'mallati-theme'); ?></h3>
    <div class="address-section">
      <p class="section-description"><?php esc_html_e('أدخل معلومات التوصيل الخاصة بك.', 'mallati-theme'); ?></p>
      <?php do_action('woocommerce_before_checkout_billing_form', $checkout); ?>
      <div class="woocommerce-billing-fields__field-wrapper">
        <?php
        $fields = $checkout->get_checkout_fields('billing');
        foreach ($fields as $key => $field) {
            woocommerce_form_field($key, $field, $checkout->get_value($key));
        }
        ?>
      </div>
      <?php do_action('woocommerce_after_checkout_billing_form', $checkout); ?>
    </div>
  </div>
</div>

<?php if (!is_user_logged_in() && $checkout->is_registration_enabled()) : ?>
  <div class="woocommerce-account-fields" style="margin-top: 16px;">
    <?php if (!$checkout->is_registration_required()) : ?>
      <a href="<?php echo esc_url(wc_get_page_permalink('myaccount') . '?action=register'); ?>" class="y-u-text-underline" style="color: var(--y-color-secondary); font-size: var(--y-space-16); font-weight: 700;"><?php esc_html_e('هل تود إنشاء حساب جديد؟', 'mallati-theme'); ?></a>
      <p class="form-row form-row-wide create-account" style="margin-top: 12px;">
        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
          <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked($checkout->get_value('createaccount') || apply_filters('woocommerce_create_account_default_checked', false), true); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e('إنشاء حساب', 'mallati-theme'); ?></span>
        </label>
      </p>
    <?php endif; ?>
    <?php do_action('woocommerce_before_checkout_registration_form', $checkout); ?>
    <?php if ($checkout->get_checkout_fields('account')) : ?>
      <div class="create-account" style="margin-top: 12px;">
        <?php foreach ($checkout->get_checkout_fields('account') as $key => $field) : ?>
          <?php
          $field['class'] = array_merge(isset($field['class']) ? (array) $field['class'] : array(), array('form-field-full'));
          $field['input_class'] = array_merge(isset($field['input_class']) ? (array) $field['input_class'] : array(), array('form-input'));
          woocommerce_form_field($key, $field, $checkout->get_value($key));
          ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <?php do_action('woocommerce_after_checkout_registration_form', $checkout); ?>
  </div>
<?php endif; ?>
