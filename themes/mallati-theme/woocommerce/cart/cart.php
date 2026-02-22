<?php
defined('ABSPATH') || exit;
$assets = get_template_directory_uri() . '/mallati/assets';
?>
<section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
<div class="y-u-max-w-1200 y-u-w-full cart-page">
  <div class="header y-u-flex y-u-flex-col y-u-py-32 y-u-p-t-24">
    <h1 class="y-u-color-muted y-u-text-2xl"><?php esc_html_e('سلة التسوق', 'mallati-theme'); ?></h1>
    <p class="y-u-text-2xl y-u-text-bold y-u-mb-16 y-u-color-muted breadcrumb">
      <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('الرئيسية', 'mallati-theme'); ?></a> &gt; <?php esc_html_e('سلة التسوق', 'mallati-theme'); ?>
    </p>
  </div>

  <?php do_action('woocommerce_before_cart'); ?>

  <?php if (WC()->cart->is_empty()) : ?>
    <div class="empty-state">
      <i class="fa-solid fa-cart-shopping"></i>
      <h3><?php esc_html_e('لا توجد عناصر', 'mallati-theme'); ?></h3>
      <p><?php esc_html_e('يبدو أنه ليس لديك أي طلبات حتى الآن', 'mallati-theme'); ?></p>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-empty-action"><?php esc_html_e('العودة للرئيسية', 'mallati-theme'); ?></a>
    </div>
  <?php else : ?>
    <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
      <?php do_action('woocommerce_before_cart_table'); ?>
      <div class="cart-grid">
        <div class="cart-items">
          <div class="cart-list">
            <?php
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
              $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
              if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                $permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                $thumb = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail'), $cart_item, $cart_item_key);
                if (empty($thumb)) $thumb = '<img src="' . esc_url($assets . '/tv.png') . '" alt="" />';
                ?>
                <div class="cart-row woocommerce-cart-form__cart-item">
                  <a href="<?php echo esc_url($permalink ?: '#'); ?>" class="img"><?php echo $thumb; ?></a>
                  <div class="content">
                    <div class="info">
                      <div class="right">
                        <h2><?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)); ?></h2>
                        <p><?php echo wp_trim_words($_product->get_short_description(), 25); ?></p>
                      </div>
                      <div class="left">
                        <p><?php esc_html_e('السعر', 'mallati-theme'); ?></p>
                        <p><?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); ?></p>
                      </div>
                    </div>
                    <div class="actions">
                      <div class="main">
                        <?php echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                          '<a href="%s" class="item-remove"><img src="%s" alt="" /> %s</a>',
                          esc_url(wc_get_cart_remove_url($cart_item_key)),
                          esc_url($assets . '/trash.svg'),
                          esc_html__('إزالة', 'mallati-theme')
                        ), $cart_item_key); ?>
                        <?php
                        $pid = $_product->get_id();
                        $fav_ids = is_user_logged_in() ? (array) get_user_meta(get_current_user_id(), 'mallati_favourites', true) : array();
                        $in_fav = in_array($pid, array_map('intval', $fav_ids));
                        ?>
                        <button type="button" class="add-to-wishlist"><label class="product-add-to-wishlist" data-product-id="<?php echo esc_attr($pid); ?>"><input type="checkbox" class="wishlist-checkbox" <?php checked($in_fav); ?> /><i class="<?php echo $in_fav ? 'fas' : 'far'; ?> fa-heart"></i></label> <?php esc_html_e('إضافة المفضلة', 'mallati-theme'); ?></button>
                      </div>
                      <div class="item-qty">
                        <?php
                        if ($_product->is_sold_individually()) {
                          echo '<span>1</span>';
                        } else {
                          $product_quantity = woocommerce_quantity_input(array(
                            'input_name'   => "cart[{$cart_item_key}][qty]",
                            'input_value'  => $cart_item['quantity'],
                            'max_value'    => $_product->get_max_purchase_quantity(),
                            'min_value'    => $_product->get_min_purchase_quantity(),
                            'product_name' => $_product->get_name(),
                          ), $_product, false);
                          echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
              }
            }
            ?>
          </div>
          <div class="cart-actions y-u-flex y-u-gap-16 y-u-mt-24">
            <?php do_action('woocommerce_cart_actions'); ?>
            <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
          </div>
        </div>
        <aside class="cart-summary">
          <div class="summary-card">
            <h2 class="y-u-text-xl y-u-text-bold y-u-m-b-16"><?php esc_html_e('ملخص الطلب', 'mallati-theme'); ?></h2>
            <?php woocommerce_cart_totals(); ?>
            <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="y-c-btn y-c-btn--primary y-u-w-full y-u-text-center"><?php esc_html_e('متابعة الدفع', 'mallati-theme'); ?></a>
          </div>
        </aside>
      </div>
      <?php do_action('woocommerce_after_cart_table'); ?>
    </form>
  <?php endif; ?>
  <?php do_action('woocommerce_after_cart'); ?>
</div>
</section>
