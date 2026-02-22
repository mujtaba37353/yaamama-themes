<?php
/**
 * Thankyou page
 *
 * Custom thank you page after successful checkout
 *
 * @package Nafhat Theme
 */

defined('ABSPATH') || exit;
?>

<div class="thankyou-page">
    <div class="container">
        <?php if ($order) : ?>
            <?php if ($order->has_status('failed')) : ?>
                <div class="thankyou-content order-failed">
                    <div class="thankyou-icon failed">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <h1><?php esc_html_e('فشل الطلب', 'nafhat'); ?></h1>
                    <p class="thankyou-message"><?php esc_html_e('للأسف، لم يتم إتمام طلبك. يرجى المحاولة مرة أخرى أو التواصل معنا للمساعدة.', 'nafhat'); ?></p>
                    <div class="thankyou-actions">
                        <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="btn btn-primary">
                            <i class="fas fa-redo"></i>
                            <?php esc_html_e('إعادة المحاولة', 'nafhat'); ?>
                        </a>
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-outline">
                            <?php esc_html_e('العودة للمتجر', 'nafhat'); ?>
                        </a>
                    </div>
                </div>
            <?php else : ?>
                <div class="thankyou-content order-success">
                    <div class="thankyou-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h1><?php esc_html_e('شكراً لك!', 'nafhat'); ?></h1>
                    <p class="thankyou-message"><?php esc_html_e('تم استلام طلبك بنجاح وسيتم معالجته في أقرب وقت.', 'nafhat'); ?></p>
                    
                    <div class="order-confirmation-box">
                        <div class="confirmation-header">
                            <span class="confirmation-label"><?php esc_html_e('رقم الطلب', 'nafhat'); ?></span>
                            <span class="confirmation-value order-number">#<?php echo $order->get_order_number(); ?></span>
                        </div>
                        <div class="confirmation-details">
                            <div class="detail-item">
                                <span class="detail-label"><?php esc_html_e('التاريخ', 'nafhat'); ?></span>
                                <span class="detail-value"><?php echo wc_format_datetime($order->get_date_created()); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><?php esc_html_e('الإجمالي', 'nafhat'); ?></span>
                                <span class="detail-value"><?php echo $order->get_formatted_order_total(); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><?php esc_html_e('طريقة الدفع', 'nafhat'); ?></span>
                                <span class="detail-value"><?php echo wp_kses_post($order->get_payment_method_title()); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><?php esc_html_e('حالة الطلب', 'nafhat'); ?></span>
                                <span class="detail-value status-badge status-<?php echo esc_attr($order->get_status()); ?>">
                                    <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <?php if ($order->get_payment_method() === 'bacs') : ?>
                        <div class="bank-details-section">
                            <h3><i class="fas fa-university"></i> <?php esc_html_e('تفاصيل التحويل البنكي', 'nafhat'); ?></h3>
                            <p class="bank-note"><?php esc_html_e('يرجى تحويل المبلغ إلى الحساب البنكي التالي واستخدام رقم الطلب كمرجع للدفع:', 'nafhat'); ?></p>
                            <?php do_action('woocommerce_thankyou_bacs', $order->get_id()); ?>
                        </div>
                    <?php endif; ?>

                    <div class="order-items-section">
                        <h3><i class="fas fa-shopping-bag"></i> <?php esc_html_e('تفاصيل الطلب', 'nafhat'); ?></h3>
                        <div class="order-items-list">
                            <?php foreach ($order->get_items() as $item_id => $item) : 
                                $product = $item->get_product();
                                $product_image = $product ? $product->get_image('thumbnail') : '';
                            ?>
                                <div class="order-item">
                                    <div class="item-image">
                                        <?php echo $product_image; ?>
                                    </div>
                                    <div class="item-details">
                                        <span class="item-name"><?php echo esc_html($item->get_name()); ?></span>
                                        <span class="item-quantity"><?php echo esc_html($item->get_quantity()); ?> × <?php echo $order->get_formatted_line_subtotal($item); ?></span>
                                    </div>
                                    <div class="item-total">
                                        <?php echo $order->get_formatted_line_subtotal($item); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="order-totals-summary">
                            <div class="totals-row">
                                <span class="label"><?php esc_html_e('المجموع الفرعي', 'nafhat'); ?></span>
                                <span class="value"><?php echo wc_price($order->get_subtotal()); ?></span>
                            </div>
                            <?php if ($order->get_shipping_total() > 0) : ?>
                                <div class="totals-row">
                                    <span class="label"><?php esc_html_e('الشحن', 'nafhat'); ?></span>
                                    <span class="value"><?php echo wc_price($order->get_shipping_total()); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($order->get_total_discount() > 0) : ?>
                                <div class="totals-row discount">
                                    <span class="label"><?php esc_html_e('الخصم', 'nafhat'); ?></span>
                                    <span class="value">-<?php echo wc_price($order->get_total_discount()); ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="totals-row total">
                                <span class="label"><?php esc_html_e('الإجمالي', 'nafhat'); ?></span>
                                <span class="value"><?php echo $order->get_formatted_order_total(); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="shipping-details-section">
                        <h3><i class="fas fa-map-marker-alt"></i> <?php esc_html_e('عنوان الشحن', 'nafhat'); ?></h3>
                        <div class="shipping-address">
                            <?php 
                            $full_name = get_post_meta($order->get_id(), '_billing_full_name', true);
                            $custom_country = get_post_meta($order->get_id(), '_custom_billing_country', true);
                            ?>
                            <p class="customer-name"><strong><?php echo esc_html($full_name ? $full_name : $order->get_billing_first_name() . ' ' . $order->get_billing_last_name()); ?></strong></p>
                            <p><?php echo esc_html($order->get_billing_address_1()); ?></p>
                            <p><?php echo esc_html($order->get_billing_city()); ?>, <?php echo esc_html($custom_country ? $custom_country : $order->get_billing_country()); ?></p>
                            <p><i class="fas fa-phone"></i> <?php echo esc_html($order->get_billing_phone()); ?></p>
                            <p><i class="fas fa-envelope"></i> <?php echo esc_html($order->get_billing_email()); ?></p>
                        </div>
                    </div>

                    <div class="thankyou-note">
                        <i class="fas fa-info-circle"></i>
                        <p><?php esc_html_e('سيتم إرسال تفاصيل الطلب إلى بريدك الإلكتروني. إذا كان لديك أي استفسار، لا تتردد في التواصل معنا.', 'nafhat'); ?></p>
                    </div>

                    <div class="thankyou-actions">
                        <?php if (is_user_logged_in()) : ?>
                            <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" class="btn btn-primary">
                                <i class="fas fa-list"></i>
                                <?php esc_html_e('متابعة طلباتي', 'nafhat'); ?>
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-outline">
                            <i class="fas fa-shopping-bag"></i>
                            <?php esc_html_e('متابعة التسوق', 'nafhat'); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <?php do_action('woocommerce_thankyou', $order->get_id()); ?>

        <?php else : ?>
            <div class="thankyou-content no-order">
                <div class="thankyou-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h1><?php esc_html_e('لا يوجد طلب', 'nafhat'); ?></h1>
                <p class="thankyou-message"><?php esc_html_e('لم يتم العثور على أي طلب. يرجى التأكد من الرابط أو تصفح منتجاتنا.', 'nafhat'); ?></p>
                <div class="thankyou-actions">
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i>
                        <?php esc_html_e('تصفح المنتجات', 'nafhat'); ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
