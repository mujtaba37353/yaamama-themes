<?php
/**
 * Template Name: الدفع (Payment / Checkout)
 * Elegance - Payment page (static design; use WooCommerce checkout when active)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$checkout_url = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '#';

elegance_enqueue_page_css( 'payment' );
elegance_enqueue_component_css( array( 'payment-details', 'status-popup' ) );
wp_enqueue_script( 'elegance-validation', ELEGANCE_ELEGANCE_URI . '/js/validation.js', array(), ELEGANCE_THEME_VERSION, true );

get_header();

$assets = ELEGANCE_ELEGANCE_URI . '/assets';
$cart_url = elegance_cart_url();
$signup_url = elegance_page_url( 'register', '/register/' );
?>
<main>
  <section class="container y-u-max-w-1200 payment-section">
    <div class="header">
      <a href="<?php echo esc_url( $cart_url ); ?>"><i class="fa-solid fa-right-to-bracket"></i> الرجوع لعربة التسوق</a>
    </div>
    <div class="bottom-section">
      <form action="<?php echo $checkout_url ? esc_url( $checkout_url ) : '#'; ?>" method="post" id="payment-form">
        <h2>معلومات التوصيل</h2>
        <div class="form-group full-width">
          <input type="text" id="full-name" name="full-name" placeholder="الاسم الكامل">
        </div>
        <div class="form-group full-width">
          <input type="email" id="email" name="email" placeholder="البريد الإلكتروني">
        </div>
        <div class="form-group full-width">
          <input type="text" id="phone" name="phone" placeholder="رقم الجوال">
        </div>
        <div class="form-group full-width">
          <input type="text" id="address" name="address" placeholder="العنوان بالتفصيل">
        </div>
        <a href="<?php echo esc_url( $signup_url ); ?>" style="text-decoration: underline; color: var(--y-color-primary); font-size: var(--y-space-16); font-weight: 700;">هل تود إنشاء حساب جديد ؟</a>
        <h2>طريقة الدفع</h2>
        <div id="payment-method-group">
          <div class="radio-group">
            <input type="radio" name="payment" id="cod" value="cod">
            <label for="cod">الدفع عند الاستلام (COD)</label>
          </div>
          <div class="radio-group">
            <input type="radio" name="payment" id="bank-transfer" value="bank">
            <label for="bank-transfer">تحويل بنكي</label>
          </div>
          <div class="radio-group">
            <input type="radio" name="payment" id="visa" value="card">
            <label for="visa">
              <img src="<?php echo esc_url( $assets . '/payment-methods.png' ); ?>" alt="Visa" style="height: 20px; margin-right: 10px;">
            </label>
          </div>
        </div>
        <div class="card">
          <div class="card-field">
            <label for="card-number">رقم البطاقة</label>
            <input type="text" id="card-number" name="card-number" placeholder="XXXX XXXX XXXX XXXX" maxlength="19">
          </div>
          <div class="card-field">
            <label for="cardholder-name">اسم حامل البطاقة</label>
            <input type="text" id="cardholder-name" name="cardholder-name" placeholder="الاسم على البطاقة">
          </div>
          <div class="card-row">
            <div class="card-field expiry-field">
              <label for="expiry-month">تاريخ الإنتهاء</label>
              <div class="expiry-inputs">
                <input type="text" id="expiry-month" name="expiry-month" placeholder="الشهر" maxlength="2">
                <input type="text" id="expiry-year" name="expiry-year" placeholder="السنة" maxlength="2">
              </div>
            </div>
            <div class="card-field cvv-field">
              <div class="cvv-header">
                <button type="button" class="cvv-info" aria-label="معلومات CVV"><i class="fa-solid fa-question"></i></button>
                <label for="cvv">CVV</label>
              </div>
              <input type="text" id="cvv" name="cvv" placeholder="XXX" maxlength="4">
            </div>
          </div>
        </div>
        <button type="submit" class="btn main-button fw" id="complete-order-btn">اكمل الطلب</button>
      </form>
      <?php if ( function_exists( 'WC' ) && WC()->cart && WC()->cart->get_cart_contents_count() > 0 ) : ?>
        <div class="products">
          <?php wc_get_template( 'cart/cart-totals.php' ); ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php
get_footer();
