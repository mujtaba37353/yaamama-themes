<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_checkout_form', $checkout);

if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo '<p class="y-u-color-muted">' . esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('يجب تسجيل الدخول لإتمام الطلب.', 'woocommerce'))) . '</p>';
    return;
}
?>
<section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
  <div class="y-u-max-w-1200 y-u-w-full payment-page">
    <div class="header y-u-flex y-u-flex-col y-u-py-32 y-u-p-t-24">
      <h1 class="y-u-color-muted y-u-text-2xl"><?php esc_html_e('الدفع', 'mallati-theme'); ?></h1>
    </div>

    <form name="checkout" method="post" class="checkout woocommerce-checkout checkout-form" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data" aria-label="<?php esc_attr_e('الدفع', 'mallati-theme'); ?>">
      <div class="checkout-grid">
        <div class="checkout-main">
          <?php if ($checkout->get_checkout_fields()) : ?>
            <?php do_action('woocommerce_checkout_before_customer_details'); ?>
            <div class="col2-set" id="customer_details">
              <div class="col-1">
                <?php do_action('woocommerce_checkout_billing'); ?>
              </div>
              <div class="col-2">
                <?php do_action('woocommerce_checkout_shipping'); ?>
              </div>
            </div>
            <?php do_action('woocommerce_checkout_after_customer_details'); ?>
          <?php endif; ?>
        </div>

        <aside class="checkout-sidebar">
          <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
          <h3 id="order_review_heading" class="y-u-text-xl y-u-text-bold y-u-m-b-16"><?php esc_html_e('ملخص الطلب', 'mallati-theme'); ?></h3>
          <?php do_action('woocommerce_checkout_before_order_review'); ?>
          <div id="order_review" class="woocommerce-checkout-review-order">
            <?php do_action('woocommerce_checkout_order_review'); ?>
          </div>
          <?php do_action('woocommerce_checkout_after_order_review'); ?>
        </aside>
      </div>
    </form>
  </div>
</section>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
