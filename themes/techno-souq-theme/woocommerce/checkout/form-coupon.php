<?php
/**
 * Checkout coupon form
 *
 * @package TechnoSouqTheme
 */

defined('ABSPATH') || exit;

if (!wc_coupons_enabled()) { // @codingStandardsIgnoreLine.
    return;
}

?>
<div class="woocommerce-form-coupon-toggle y-c-coupon-toggle">
    <?php
    $coupon_message = esc_html__('هل لديك كوبون؟', 'techno-souq-theme') . ' <a href="#" role="button" aria-label="' . esc_attr__('أدخل رمز الكوبون الخاص بك', 'techno-souq-theme') . '" aria-controls="woocommerce-checkout-form-coupon" aria-expanded="false" class="showcoupon y-c-coupon-link">' . esc_html__('انقر هنا لإدخال الرمز', 'techno-souq-theme') . '</a>';
    wc_print_notice(apply_filters('woocommerce_checkout_coupon_message', $coupon_message), 'notice');
    ?>
</div>

<form class="checkout_coupon woocommerce-form-coupon y-c-coupon-form" method="post" style="display:none" id="woocommerce-checkout-form-coupon">
    <div class="y-c-coupon-form-container">
        <div class="y-c-form-field">
            <label for="coupon_code" class="screen-reader-text"><?php esc_html_e('كوبون:', 'techno-souq-theme'); ?></label>
            <input type="text" name="coupon_code" class="y-c-form-input" placeholder="<?php esc_attr_e('رمز الكوبون', 'techno-souq-theme'); ?>" id="coupon_code" value="" />
        </div>
        
        <button type="submit" class="y-c-btn y-c-btn-outline y-c-coupon-btn" name="apply_coupon" value="<?php esc_attr_e('تطبيق الكوبون', 'techno-souq-theme'); ?>">
            <?php esc_html_e('تطبيق الكوبون', 'techno-souq-theme'); ?>
        </button>
    </div>
</form>