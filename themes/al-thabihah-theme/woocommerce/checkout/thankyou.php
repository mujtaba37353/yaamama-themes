<?php
/**
 * Thankyou page – مودال النجاح فقط (بدون معلومات الطلب)
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined('ABSPATH') || exit;
?>

<?php
if ($order && !$order->has_status('failed')) :
    /* طلب ناجح – عرض المودال فقط */
    do_action('woocommerce_before_thankyou', $order->get_id());
    do_action('woocommerce_thankyou_' . ($order ? $order->get_payment_method() : ''), $order ? $order->get_id() : 0);
    do_action('woocommerce_thankyou', $order ? $order->get_id() : 0);
    ?>
    <style id="y-thankyou-inline-critical">
    body.y-order-success-modal .y-c-page-title,body.y-order-success-modal .y-c-woo-notices{display:none!important;}
    body.y-order-success-modal main.y-l-page{min-height:calc(100vh - 350px);}
    .y-c-order-success-modal-wrapper{position:fixed!important;top:0;left:0;width:100%;min-height:100vh;display:flex;align-items:center;justify-content:center;z-index:9999;padding:1.5rem;box-sizing:border-box;}
    .y-c-order-success-backdrop{position:absolute;top:0;left:0;width:100%;height:100%;background-color:rgba(0,0,0,.5);cursor:pointer;}
    .y-c-order-success-card{position:relative;z-index:1;background-color:#fff!important;padding:2rem;border-radius:12px;text-align:center;display:flex;flex-direction:column;align-items:center;gap:1.5rem;box-shadow:0 4px 24px rgba(0,0,0,.12);max-width:440px;width:100%;margin:auto;}
    .y-c-order-success-icon{width:120px;height:120px;border-radius:50%;background-color:#2e7d32!important;color:#fff!important;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
    .y-c-order-success-icon i{font-size:3.5rem!important;color:#fff!important;}
    .y-c-order-success-card .y-c-order-success-btn{display:inline-flex!important;width:auto!important;min-width:180px;max-width:280px;padding:.5rem 1.5rem;justify-content:center;}
    </style>
    <div class="y-c-order-success-modal-wrapper" id="order-success-modal" role="dialog" aria-modal="true" aria-labelledby="order-success-title">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="y-c-order-success-backdrop" aria-label="العودة للرئيسية"></a>
        <div class="y-c-order-success-card">
            <div class="y-c-order-success-icon">
                <i class="fas fa-check" aria-hidden="true"></i>
            </div>
            <h2 class="y-c-order-success-title" id="order-success-title">تم تسجيل طلبك بنجاح</h2>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="y-c-order-success-btn y-c-outline-btn">العودة للرئيسية</a>
        </div>
    </div>
<?php
elseif ($order && $order->has_status('failed')) :
    /* طلب فاشل – عرض رسالة الخطأ */
    ?>
    <main class="y-l-thankyou-page">
        <div class="y-u-container">
            <div class="y-c-thankyou-error">
                <div class="y-c-thankyou-icon y-c-icon-error">
                    <i class="fas fa-times-circle" aria-hidden="true"></i>
                </div>
                <h2 class="y-c-thankyou-title">عذراً، لم يتم إتمام الطلب</h2>
                <p class="y-c-thankyou-message">
                    <?php esc_html_e('للأسف لا يمكن معالجة طلبك لأن البنك/التاجر رفض المعاملة. يرجى المحاولة مرة أخرى أو اختيار طريقة دفع أخرى.', 'woocommerce'); ?>
                </p>
                <div class="y-c-thankyou-actions">
                    <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="y-c-outline-btn y-c-btn-pay">إعادة المحاولة للدفع</a>
                    <?php if (is_user_logged_in()) : ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="y-c-outline-btn">حسابي</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
<?php
else :
    /* لا يوجد طلب – عرض مودال بسيط */
    do_action('woocommerce_before_thankyou', 0);
    ?>
    <style id="y-thankyou-inline-critical">
    body.y-order-success-modal .y-c-page-title,body.y-order-success-modal .y-c-woo-notices{display:none!important;}
    body.y-order-success-modal main.y-l-page{min-height:calc(100vh - 350px);}
    .y-c-order-success-modal-wrapper{position:fixed!important;top:0;left:0;width:100%;min-height:100vh;display:flex;align-items:center;justify-content:center;z-index:9999;padding:1.5rem;box-sizing:border-box;}
    .y-c-order-success-backdrop{position:absolute;top:0;left:0;width:100%;height:100%;background-color:rgba(0,0,0,.5);cursor:pointer;}
    .y-c-order-success-card{position:relative;z-index:1;background-color:#fff!important;padding:2rem;border-radius:12px;text-align:center;display:flex;flex-direction:column;align-items:center;gap:1.5rem;box-shadow:0 4px 24px rgba(0,0,0,.12);max-width:440px;width:100%;margin:auto;}
    .y-c-order-success-icon{width:120px;height:120px;border-radius:50%;background-color:#2e7d32!important;color:#fff!important;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
    .y-c-order-success-icon i{font-size:3.5rem!important;color:#fff!important;}
    .y-c-order-success-card .y-c-order-success-btn{display:inline-flex!important;width:auto!important;min-width:180px;max-width:280px;padding:.5rem 1.5rem;justify-content:center;}
    </style>
    <div class="y-c-order-success-modal-wrapper" id="order-success-modal" role="dialog" aria-modal="true" aria-labelledby="order-success-title">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="y-c-order-success-backdrop" aria-label="العودة للرئيسية"></a>
        <div class="y-c-order-success-card">
            <div class="y-c-order-success-icon">
                <i class="fas fa-check" aria-hidden="true"></i>
            </div>
            <h2 class="y-c-order-success-title" id="order-success-title">تم تسجيل طلبك بنجاح</h2>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="y-c-order-success-btn y-c-outline-btn">العودة للرئيسية</a>
        </div>
    </div>
<?php endif; ?>
