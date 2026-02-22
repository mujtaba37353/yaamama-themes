<?php
defined('ABSPATH') || exit;

$page_title = esc_html__('عنواني', 'mallati-theme');
do_action('woocommerce_before_edit_account_address_form');
?>

<?php if (!$load_address) : ?>
  <?php wc_get_template('myaccount/my-address.php'); ?>
<?php else : ?>
  <form method="post" novalidate class="profile-form">
    <h2><?php echo esc_html($page_title); ?></h2>
    <p class="y-u-text-muted y-u-m-b-16"><?php esc_html_e('العناوين التالية سيتم استخدامها في صفحة الدفع', 'mallati-theme'); ?></p>
    <div class="woocommerce-address-fields">
      <?php do_action("woocommerce_before_edit_address_form_{$load_address}"); ?>
      <div class="woocommerce-address-fields__field-wrapper y-u-flex y-u-flex-col y-u-gap-16">
        <?php
        foreach ($address as $key => $field) {
            $field['class'] = array_merge(isset($field['class']) ? (array) $field['class'] : array(), array('form-group', 'y-c-field'));
            $field['input_class'] = array_merge(isset($field['input_class']) ? (array) $field['input_class'] : array(), array('y-c-input'));
            woocommerce_form_field($key, $field, wc_get_post_data_by_key($key, $field['value']));
        }
        ?>
      </div>
      <?php do_action("woocommerce_after_edit_address_form_{$load_address}"); ?>
      <div class="form-footer">
        <?php wp_nonce_field('woocommerce-edit_address', 'woocommerce-edit-address-nonce'); ?>
        <input type="hidden" name="action" value="edit_address" />
        <button type="submit" name="save_address" value="<?php esc_attr_e('حفظ العنوان', 'mallati-theme'); ?>" class="y-c-btn y-c-btn--primary"><?php esc_html_e('حفظ العنوان', 'mallati-theme'); ?></button>
      </div>
    </div>
  </form>
<?php endif; ?>

<?php do_action('woocommerce_after_edit_account_address_form'); ?>
