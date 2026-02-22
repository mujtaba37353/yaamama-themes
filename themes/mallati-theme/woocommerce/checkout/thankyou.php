<?php
defined('ABSPATH') || exit;
?>

<div class="woocommerce-order">

<?php
if ($order) :
    do_action('woocommerce_before_thankyou', $order->get_id());
    ?>

    <?php if ($order->has_status('failed')) : ?>
        <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e('للأسف لا يمكن معالجة طلبك. يرجى المحاولة مرة أخرى أو اختيار طريقة دفع أخرى.', 'mallati-theme'); ?></p>
        <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
            <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="button pay"><?php esc_html_e('الدفع', 'mallati-theme'); ?></a>
            <?php if (is_user_logged_in()) : ?>
                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="button pay"><?php esc_html_e('حسابي', 'mallati-theme'); ?></a>
            <?php endif; ?>
        </p>
    <?php else : ?>
        <?php wc_get_template('checkout/order-received.php', array('order' => $order)); ?>
        <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
            <li class="woocommerce-order-overview__order order">
                <?php esc_html_e('رقم الطلب:', 'mallati-theme'); ?>
                <strong><?php echo $order->get_order_number(); ?></strong>
            </li>
            <li class="woocommerce-order-overview__date date">
                <?php esc_html_e('التاريخ:', 'mallati-theme'); ?>
                <strong><?php echo wc_format_datetime($order->get_date_created()); ?></strong>
            </li>
            <?php if (is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email()) : ?>
            <li class="woocommerce-order-overview__email email">
                <?php esc_html_e('البريد:', 'mallati-theme'); ?>
                <strong><?php echo $order->get_billing_email(); ?></strong>
            </li>
            <?php endif; ?>
            <li class="woocommerce-order-overview__total total">
                <?php esc_html_e('الإجمالي:', 'mallati-theme'); ?>
                <strong><?php echo $order->get_formatted_order_total(); ?></strong>
            </li>
            <?php if ($order->get_payment_method_title()) : ?>
            <li class="woocommerce-order-overview__payment-method method">
                <?php esc_html_e('طريقة الدفع:', 'mallati-theme'); ?>
                <strong><?php echo wp_kses_post($order->get_payment_method_title()); ?></strong>
            </li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>

    <?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>
    <?php do_action('woocommerce_thankyou', $order->get_id()); ?>

<?php else : ?>
    <?php wc_get_template('checkout/order-received.php', array('order' => false)); ?>
<?php endif; ?>

</div>

<?php if ($order && !$order->has_status('failed')) : ?>
<div class="payment-success-modal" id="payment-success-modal" aria-hidden="true">
    <div class="modal-backdrop" data-success-dismiss></div>
    <div class="success-card" role="dialog" aria-modal="true" tabindex="-1">
        <div class="success-icon" aria-hidden="true"><i class="fas fa-check"></i></div>
        <p class="success-message"><?php esc_html_e('تمت العملية بنجاح', 'mallati-theme'); ?></p>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="success-primary-btn"><?php esc_html_e('العودة للرئيسية', 'mallati-theme'); ?></a>
    </div>
</div>
<script>
(function(){
  var modal = document.getElementById('payment-success-modal');
  if (!modal) return;
  modal.classList.add('is-open');
  modal.setAttribute('aria-hidden', 'false');
  document.body.style.overflow = 'hidden';
  function close() {
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }
  modal.querySelector('[data-success-dismiss]')?.addEventListener('click', close);
  document.addEventListener('keydown', function(e) { if (e.key === 'Escape') close(); });
})();
</script>
<?php endif; ?>
