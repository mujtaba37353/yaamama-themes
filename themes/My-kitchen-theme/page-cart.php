<?php
get_header();

$is_empty = function_exists('WC') && WC()->cart && WC()->cart->is_empty();
$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '/my-kitchen/shop/';
?>

<header data-y="design-header"></header>

<?php if ($is_empty) : ?>
  <main data-y="main" class="not-found-container">
    <div class="y-main-container">
      <div data-y="breadcrumb"></div>
      <div class="not-found-content">
        <img src="<?php echo esc_url(MYK_ASSETS_URI . '/assets/empty-cart.png'); ?>" alt="السلة فارغة" class="not-found-img" />
        <p class="not-found-text">
          السلة فارغة، لم تقم بإضافة أي منتجات إلى السلة الخاصة بك بعد.
        </p>
        <a href="<?php echo esc_url($shop_url); ?>" class="btn-back">
          لا يوجد منتجات، تصفح المنتجات <i class="fa-solid fa-cart-shopping"></i>
        </a>
      </div>
    </div>
  </main>
<?php else : ?>
  <?php
  $cart = function_exists('WC') ? WC()->cart : null;
  $cart_items = $cart ? $cart->get_cart() : array();
  $subtotal = $cart ? (float) $cart->get_subtotal() : 0.0;
  $discount = $cart ? (float) $cart->get_discount_total() : 0.0;
  $tax = $cart ? (float) $cart->get_total_tax() : 0.0;
  $total = $cart ? (float) $cart->get_total('edit') : 0.0;
  $decimals = (int) wc_get_price_decimals();
  ?>
  <main data-y="main" class="y-u-my-10">
    <div class="main-container">
      <div data-y="breadcrumb"></div>
    </div>
    <div class="main-container">
      <div class="woocommerce-cart-wrapper" data-real-cart="1">
        <?php do_action('woocommerce_before_cart'); ?>
        <form class="woocommerce-cart-form myk-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
          <?php do_action('woocommerce_before_cart_table'); ?>
          <ul class="cart-list">
            <?php foreach ($cart_items as $cart_item_key => $cart_item) : ?>
              <?php
              $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
              $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
              if (!$_product || !$_product->exists() || $cart_item['quantity'] <= 0 || !apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                  continue;
              }
              $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
              $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
              $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail'), $cart_item, $cart_item_key);
              $price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
              $subtotal_line = apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
              ?>
              <li class="cart-item">
                <div class="product-info">
                  <div class="product-img">
                    <?php if ($product_permalink) : ?>
                      <a href="<?php echo esc_url($product_permalink); ?>">
                        <?php echo $thumbnail; ?>
                      </a>
                    <?php else : ?>
                      <?php echo $thumbnail; ?>
                    <?php endif; ?>
                  </div>
                  <div class="product-details">
                    <h5 class="product-title">
                      <?php if ($product_permalink) : ?>
                        <a href="<?php echo esc_url($product_permalink); ?>">
                          <?php echo wp_kses_post($product_name); ?>
                        </a>
                      <?php else : ?>
                        <?php echo wp_kses_post($product_name); ?>
                      <?php endif; ?>
                    </h5>
                    <h4 class="y-u-d-flex y-u-align-items-center">
                      <?php echo wp_kses_post($price); ?>
                    </h4>
                    <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                  </div>
                </div>

                <div class="qnt" data-qty-control>
                  <button type="button" class="btn btn-outline" data-qty-minus>-</button>
                  <?php
                  if ($_product->is_sold_individually()) {
                      $min_quantity = 1;
                      $max_quantity = 1;
                  } else {
                      $min_quantity = 0;
                      $max_quantity = $_product->get_max_purchase_quantity();
                  }
                  $product_quantity = woocommerce_quantity_input(
                      array(
                          'input_name' => "cart[{$cart_item_key}][qty]",
                          'input_value' => $cart_item['quantity'],
                          'max_value' => $max_quantity,
                          'min_value' => $min_quantity,
                          'product_name' => $product_name,
                      ),
                      $_product,
                      false
                  );
                  echo $product_quantity;
                  ?>
                  <button type="button" class="btn btn-outline" data-qty-plus>+</button>
                </div>

                <div class="product-total">
                  <p class="y-u-d-flex y-u-align-items-center">
                    المجموع: <?php echo wp_kses_post($subtotal_line); ?>
                  </p>
                </div>
                <div class="remove">
                  <?php
                  echo apply_filters(
                      'woocommerce_cart_item_remove_link',
                      sprintf(
                          '<a role="button" href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fa-solid fa-xmark"></i></a>',
                          esc_url(wc_get_cart_remove_url($cart_item_key)),
                          esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
                          esc_attr($product_id),
                          esc_attr($_product->get_sku())
                      ),
                      $cart_item_key
                  );
                  ?>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>

          <div class="myk-cart-actions">
            <button type="submit" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>" class="myk-cart-update">
              <?php esc_html_e('Update cart', 'woocommerce'); ?>
            </button>
            <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
          </div>
          <?php do_action('woocommerce_after_cart_table'); ?>
        </form>

        <div class="cart-summary">
          <div class="order-summary-box">
            <h3 class="summary-box-title">إجمالي الطلبات</h3>
            <div class="summary-row">
              <span class="summary-label">المجموع</span>
              <span class="summary-value"><?php echo esc_html(wc_format_decimal($subtotal, $decimals)); ?></span>
            </div>
            <?php if ($discount > 0) : ?>
              <div class="summary-row">
                <span class="summary-label">الخصم</span>
                <span class="summary-value"><?php echo esc_html(wc_format_decimal($discount, $decimals)); ?></span>
              </div>
            <?php endif; ?>
            <?php if ($tax > 0) : ?>
              <div class="summary-row">
                <span class="summary-label">ضريبة القيمة المضافة</span>
                <span class="summary-value"><?php echo esc_html(wc_format_decimal($tax, $decimals)); ?></span>
              </div>
            <?php endif; ?>
            <div class="summary-row total-row">
              <span class="summary-label">الإجمالي المقدر</span>
              <span class="summary-value"><?php echo esc_html(wc_format_decimal($total, $decimals)); ?></span>
            </div>
            <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="btn-auth">المتابعة لإتمام الطلب</a>
          </div>
        </div>
        <?php do_action('woocommerce_after_cart'); ?>
      </div>
    </div>
  </main>
<?php endif; ?>

<?php get_footer(); ?>
