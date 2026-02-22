<?php
/**
 * Checkout Form - Custom Template
 *
 * @package MyCarTheme
 */

if (!defined('ABSPATH')) {
    exit;
}

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}
?>

<div class="y-l-checkout">
    <div class="y-l-container">
        
        <!-- Page Title -->
        <div class="y-l-checkout-header">
            <h1 class="y-c-checkout-title">إتمام الحجز</h1>
            <p class="y-c-checkout-subtitle">أكمل بياناتك لإتمام عملية الحجز</p>
        </div>

        <?php do_action('woocommerce_before_checkout_form', $checkout); ?>

        <form name="checkout" method="post" class="checkout woocommerce-checkout y-c-checkout-form" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

            <div class="y-l-checkout-wrapper">
                
                <!-- Right Column (in RTL): Billing & Additional Info -->
                <div class="y-l-checkout-main">
                    
                    <!-- Billing Details Section -->
                    <div class="y-c-checkout-section y-c-billing-section">
                        <div class="y-c-section-header">
                            <span class="y-c-section-icon"><i class="fa-solid fa-user"></i></span>
                            <h3 class="y-c-section-title">بيانات العميل</h3>
                        </div>
                        <div class="y-c-section-content">
                            <?php do_action('woocommerce_checkout_billing'); ?>
                        </div>
                    </div>

                    <!-- Shipping if needed -->
                    <?php do_action('woocommerce_checkout_shipping'); ?>

                </div>

                <!-- Left Column (in RTL): Order Summary & Payment -->
                <div class="y-l-checkout-sidebar">
                    
                    <!-- Booking Summary Section -->
                    <div class="y-c-checkout-section y-c-booking-summary-section">
                        <div class="y-c-section-header">
                            <span class="y-c-section-icon"><i class="fa-solid fa-car"></i></span>
                            <h3 class="y-c-section-title">ملخص الحجز</h3>
                        </div>
                        <div class="y-c-booking-summary" id="checkout-booking-summary">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Order Review Section -->
                    <div class="y-c-checkout-section y-c-order-section">
                        <div class="y-c-section-header">
                            <span class="y-c-section-icon"><i class="fa-solid fa-receipt"></i></span>
                            <h3 class="y-c-section-title">تفاصيل الطلب</h3>
                        </div>
                        <div class="y-c-section-content">
                            <div id="order_review" class="woocommerce-checkout-review-order">
                                <?php do_action('woocommerce_checkout_order_review'); ?>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </form>

        <?php do_action('woocommerce_after_checkout_form', $checkout); ?>

    </div>
</div>
