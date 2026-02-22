<?php
/**
 * Thank You Page - Custom Template
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;
?>

<div class="y-l-thankyou">
    <div class="y-l-container">
        
        <?php if ($order) : ?>
            
            <?php if ($order->has_status('failed')) : ?>
                
                <!-- Order Failed -->
                <div class="y-c-thankyou-failed">
                    <div class="y-c-thankyou-icon y-c-icon-failed">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    <h1 class="y-c-thankyou-title">عذراً، فشل الطلب</h1>
                    <p class="y-c-thankyou-message">
                        <?php esc_html_e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce'); ?>
                    </p>
                    <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="y-c-btn y-c-btn-primary">
                        <span>إعادة المحاولة</span>
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>
                
            <?php else : ?>
                
                <!-- Order Success -->
                <div class="y-c-thankyou-success">
                    
                    <!-- Success Header -->
                    <div class="y-c-thankyou-header">
                        <div class="y-c-thankyou-icon y-c-icon-success">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <h1 class="y-c-thankyou-title">تم تأكيد حجزك بنجاح!</h1>
                        <p class="y-c-thankyou-message">شكراً لك، تم استلام طلبك وسيتم التواصل معك قريباً</p>
                    </div>

                    <!-- Order Info Cards -->
                    <div class="y-c-order-info-cards">
                        <div class="y-c-info-card">
                            <div class="y-c-info-card-icon">
                                <i class="fa-solid fa-hashtag"></i>
                            </div>
                            <div class="y-c-info-card-content">
                                <span class="y-c-info-card-label">رقم الطلب</span>
                                <span class="y-c-info-card-value"><?php echo $order->get_order_number(); ?></span>
                            </div>
                        </div>
                        
                        <div class="y-c-info-card">
                            <div class="y-c-info-card-icon">
                                <i class="fa-solid fa-calendar"></i>
                            </div>
                            <div class="y-c-info-card-content">
                                <span class="y-c-info-card-label">تاريخ الطلب</span>
                                <span class="y-c-info-card-value"><?php echo wc_format_datetime($order->get_date_created()); ?></span>
                            </div>
                        </div>
                        
                        <div class="y-c-info-card">
                            <div class="y-c-info-card-icon">
                                <i class="fa-solid fa-coins"></i>
                            </div>
                            <div class="y-c-info-card-content">
                                <span class="y-c-info-card-label">الإجمالي</span>
                                <span class="y-c-info-card-value"><?php echo $order->get_formatted_order_total(); ?></span>
                            </div>
                        </div>
                        
                        <div class="y-c-info-card">
                            <div class="y-c-info-card-icon">
                                <i class="fa-solid fa-credit-card"></i>
                            </div>
                            <div class="y-c-info-card-content">
                                <span class="y-c-info-card-label">طريقة الدفع</span>
                                <span class="y-c-info-card-value"><?php echo wp_kses_post($order->get_payment_method_title()); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details Section -->
                    <div class="y-c-thankyou-section y-c-booking-details-section">
                        <div class="y-c-section-header">
                            <span class="y-c-section-icon"><i class="fa-solid fa-car"></i></span>
                            <h3 class="y-c-section-title">تفاصيل الحجز</h3>
                        </div>
                        <div class="y-c-section-content">
                            <div class="y-c-booking-dates-display" id="thankyou-booking-dates">
                                <!-- Will be populated by JavaScript -->
                            </div>
                            
                            <div class="y-c-order-items">
                                <?php foreach ($order->get_items() as $item_id => $item) : 
                                    $product = $item->get_product();
                                    if (!$product) continue;
                                ?>
                                    <div class="y-c-order-item">
                                        <div class="y-c-order-item-image">
                                            <?php echo $product->get_image('thumbnail'); ?>
                                        </div>
                                        <div class="y-c-order-item-details">
                                            <h4 class="y-c-order-item-name"><?php echo esc_html($item->get_name()); ?></h4>
                                            <div class="y-c-order-item-meta">
                                                <span class="y-c-order-item-qty">الكمية: <?php echo $item->get_quantity(); ?></span>
                                            </div>
                                        </div>
                                        <div class="y-c-order-item-price">
                                            <?php echo $order->get_formatted_line_subtotal($item); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Details Section -->
                    <div class="y-c-thankyou-section y-c-customer-details-section">
                        <div class="y-c-section-header">
                            <span class="y-c-section-icon"><i class="fa-solid fa-user"></i></span>
                            <h3 class="y-c-section-title">بيانات العميل</h3>
                        </div>
                        <div class="y-c-section-content">
                            <div class="y-c-customer-info-grid">
                                <?php if ($order->get_billing_first_name() || $order->get_billing_last_name()) : ?>
                                    <div class="y-c-customer-info-item">
                                        <i class="fa-solid fa-user"></i>
                                        <span><?php echo esc_html($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($order->get_billing_email()) : ?>
                                    <div class="y-c-customer-info-item">
                                        <i class="fa-solid fa-envelope"></i>
                                        <span><?php echo esc_html($order->get_billing_email()); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($order->get_billing_phone()) : ?>
                                    <div class="y-c-customer-info-item">
                                        <i class="fa-solid fa-phone"></i>
                                        <span><?php echo esc_html($order->get_billing_phone()); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($order->get_billing_address_1()) : ?>
                                    <div class="y-c-customer-info-item">
                                        <i class="fa-solid fa-location-dot"></i>
                                        <span><?php echo wp_kses_post($order->get_formatted_billing_address()); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- What's Next Section -->
                    <div class="y-c-thankyou-section y-c-next-steps-section">
                        <div class="y-c-section-header">
                            <span class="y-c-section-icon"><i class="fa-solid fa-list-check"></i></span>
                            <h3 class="y-c-section-title">الخطوات التالية</h3>
                        </div>
                        <div class="y-c-section-content">
                            <div class="y-c-steps-list">
                                <div class="y-c-step-item">
                                    <div class="y-c-step-number">1</div>
                                    <div class="y-c-step-content">
                                        <h4>تأكيد الحجز</h4>
                                        <p>سيتم إرسال تأكيد الحجز إلى بريدك الإلكتروني</p>
                                    </div>
                                </div>
                                <div class="y-c-step-item">
                                    <div class="y-c-step-number">2</div>
                                    <div class="y-c-step-content">
                                        <h4>التواصل معك</h4>
                                        <p>سيتواصل معك فريقنا لتأكيد تفاصيل الاستلام</p>
                                    </div>
                                </div>
                                <div class="y-c-step-item">
                                    <div class="y-c-step-number">3</div>
                                    <div class="y-c-step-content">
                                        <h4>استلام السيارة</h4>
                                        <p>توجه إلى موقع الاستلام في الموعد المحدد</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="y-c-thankyou-actions">
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" class="y-c-btn y-c-btn-secondary">
                            <i class="fa-solid fa-list"></i>
                            <span>طلباتي</span>
                        </a>
                        <a href="<?php echo esc_url(home_url('/shop/')); ?>" class="y-c-btn y-c-btn-primary">
                            <span>تصفح المزيد</span>
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    </div>

                </div>
                
            <?php endif; ?>

            <?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>
            <?php do_action('woocommerce_thankyou', $order->get_id()); ?>

        <?php else : ?>
            
            <!-- No Order -->
            <div class="y-c-thankyou-empty">
                <div class="y-c-thankyou-icon">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <h1 class="y-c-thankyou-title">لا يوجد طلب</h1>
                <p class="y-c-thankyou-message"><?php esc_html_e('Thank you. Your order has been received.', 'woocommerce'); ?></p>
                <a href="<?php echo esc_url(home_url('/shop/')); ?>" class="y-c-btn y-c-btn-primary">
                    <span>تصفح السيارات</span>
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
            
        <?php endif; ?>

    </div>
</div>
