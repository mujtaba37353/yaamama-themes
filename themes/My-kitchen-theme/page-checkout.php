<?php
get_header();

$is_order_received = function_exists('is_order_received_page') && is_order_received_page();
$order_id = $is_order_received ? absint(get_query_var('order-received')) : 0;
$order = $order_id ? wc_get_order($order_id) : null;
?>

<?php if ($is_order_received) : ?>
  <main data-y="main" class="not-found-container">
    <div class="y-main-container">
      <?php if ($order && $order->has_status('failed')) : ?>
        <div class="not-found-content">
          <p class="not-found-text">عذرًا، لم يكتمل الدفع. يمكنك المحاولة مرة أخرى.</p>
          <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="btn-back">
            العودة للدفع <i class="fa-solid fa-credit-card"></i>
          </a>
        </div>
      <?php else : ?>
        <div class="not-found-content">
          <p class="not-found-text">تم الدفع بنجاح وتم استلام طلبك.</p>
          <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-back">
            العودة للرئيسية <i class="fa-solid fa-house"></i>
          </a>
        </div>
      <?php endif; ?>
    </div>
  </main>
<?php else : ?>
  <header data-y="design-header"></header>
  <?php
  $cart = function_exists('WC') ? WC()->cart : null;
  $cart_items = $cart ? $cart->get_cart() : array();
  $subtotal = $cart ? (float) $cart->get_subtotal() : 0.0;
  $discount = $cart ? (float) $cart->get_discount_total() : 0.0;
  $tax = $cart ? (float) $cart->get_total_tax() : 0.0;
  $total = $cart ? (float) $cart->get_total('edit') : 0.0;
  $decimals = (int) wc_get_price_decimals();
  ?>

  <main data-y="main">
    <div class="main-container">
      <div data-y="breadcrumb"></div>
    </div>
    <div class="main-container">
      <div data-y="payment" data-real-checkout="1">
        <?php echo do_shortcode('[woocommerce_checkout]'); ?>
      </div>
      <div data-y="payment-summary" class="summary" data-real-summary="1">
        <div class="order-summary-container">
          <h2 class="order-summary-title">ملخص الطلب</h2>
          <hr class="title-divider" />

          <div class="order-products-list">
            <?php foreach ($cart_items as $cart_item_key => $cart_item) : ?>
              <?php
              $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
              if (!$_product || !$_product->exists() || $cart_item['quantity'] <= 0) {
                  continue;
              }
              $thumbnail = $_product->get_image('woocommerce_thumbnail', array('class' => 'product-image'));
              $line_price = WC()->cart->get_product_subtotal($_product, $cart_item['quantity']);
              ?>
              <div class="order-product-item">
                <div class="product-image-wrapper">
                  <?php echo $thumbnail; ?>
                  <span class="quantity-badge"><?php echo esc_html($cart_item['quantity']); ?></span>
                </div>
                <div class="product-details">
                  <h3 class="product-name"><?php echo esc_html($_product->get_name()); ?></h3>
                  <p class="product-price"><?php echo wp_kses_post($line_price); ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <hr class="products-divider" />

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
          </div>
        </div>
      </div>
    </div>
  </main>
<?php endif; ?>

<?php get_footer(); ?>
