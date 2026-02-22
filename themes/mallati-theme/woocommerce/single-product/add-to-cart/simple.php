<?php
defined('ABSPATH') || exit;
global $product;
$assets = get_template_directory_uri() . '/mallati/assets';

if (!$product->is_purchasable()) return;

echo wc_get_stock_html($product);

if ($product->is_in_stock()) : ?>
  <?php do_action('woocommerce_before_add_to_cart_form'); ?>
  <form class="cart pd-actions-form y-u-flex y-u-flex-col y-u-gap-12" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype="multipart/form-data" style="display:contents">
    <?php do_action('woocommerce_before_add_to_cart_button'); ?>
    <?php
    woocommerce_quantity_input(array(
      'min_value'   => $product->get_min_purchase_quantity(),
      'max_value'   => $product->get_max_purchase_quantity(),
      'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(),
      'input_class' => 'qty-input',
    ));
    ?>
    <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="y-c-btn y-c-btn--primary single_add_to_cart_button"><?php echo esc_html($product->single_add_to_cart_text()); ?> <img src="<?php echo esc_url($assets); ?>/cart-details.svg" alt="" /></button>
    <?php do_action('woocommerce_after_add_to_cart_button'); ?>
  </form>
  <?php do_action('woocommerce_after_add_to_cart_form'); ?>
<?php endif; ?>
