<?php
/**
 * Orders
 *
 * @package Nafhat Theme
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_account_orders', $has_orders); ?>

<h2><?php esc_html_e('طلباتي', 'nafhat'); ?></h2>

<?php if ($has_orders) : ?>

    <div class="orders-list">
        <?php
        foreach ($customer_orders->orders as $customer_order) {
            $order      = wc_get_order($customer_order);
            $item_count = $order->get_item_count() - $order->get_item_count_refunded();
            $order_date = $order->get_date_created();
            $order_items = $order->get_items();
            $first_item = reset($order_items);
            $product = $first_item ? $first_item->get_product() : null;
            $product_image = $product ? wp_get_attachment_image_url($product->get_image_id(), 'thumbnail') : wc_placeholder_img_src('thumbnail');
            
            // Get order status
            $status = $order->get_status();
            $status_class = 'status-' . $status;
            $status_labels = array(
                'pending'    => 'قيد الانتظار',
                'processing' => 'قيد التجهيز',
                'on-hold'    => 'معلق',
                'completed'  => 'مكتمل',
                'cancelled'  => 'ملغي',
                'refunded'   => 'مسترد',
                'failed'     => 'فشل',
                'shipped'    => 'تم الشحن',
            );
            $status_label = isset($status_labels[$status]) ? $status_labels[$status] : wc_get_order_status_name($status);
            ?>
            
            <div class="order-card">
                <div class="order-content">
                    <div class="order-image-wrapper">
                        <img src="<?php echo esc_url($product_image); ?>" alt="<?php esc_attr_e('صورة المنتج', 'nafhat'); ?>" class="order-image" />
                    </div>
                    <div class="order-info">
                        <div class="order-header">
                            <span class="order-id">
                                <i class="fas fa-cube"></i>
                                <?php echo esc_html(_x('#', 'hash before order number', 'nafhat') . $order->get_order_number()); ?>
                            </span>
                            <span class="order-status <?php echo esc_attr($status_class); ?>">
                                <?php echo esc_html($status_label); ?>
                            </span>
                        </div>
                        <div class="order-meta">
                            <span class="meta-item">
                                <i class="far fa-calendar"></i>
                                <?php echo esc_html(wc_format_datetime($order_date)); ?>
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-dollar-sign"></i>
                                <?php echo wp_kses_post($order->get_formatted_order_total()); ?>
                            </span>
                            <span class="meta-item">
                                <?php 
                                /* translators: %d: number of items */
                                printf(_n('%d منتج', '%d منتجات', $item_count, 'nafhat'), $item_count); 
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="order-actions">
                    <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="btn-outline">
                        <?php esc_html_e('عرض التفاصيل', 'nafhat'); ?>
                    </a>
                </div>
            </div>
            
        <?php } ?>
    </div>

    <?php do_action('woocommerce_before_account_orders_pagination'); ?>

    <?php if (1 < $customer_orders->max_num_pages) : ?>
        <div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
            <?php if (1 !== $current_page) : ?>
                <a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url(wc_get_endpoint_url('orders', $current_page - 1)); ?>"><?php esc_html_e('السابق', 'nafhat'); ?></a>
            <?php endif; ?>

            <?php if (intval($customer_orders->max_num_pages) !== $current_page) : ?>
                <a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url(wc_get_endpoint_url('orders', $current_page + 1)); ?>"><?php esc_html_e('التالي', 'nafhat'); ?></a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

<?php else : ?>
    
    <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
        <i class="fas fa-shopping-bag" style="font-size: 48px; color: var(--y-color-muted); margin-bottom: 16px; display: block;"></i>
        <?php esc_html_e('لم يتم إجراء أي طلب بعد.', 'nafhat'); ?>
        <a class="woocommerce-Button button" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" style="margin-top: 16px; display: inline-block;">
            <?php esc_html_e('تصفح المنتجات', 'nafhat'); ?>
        </a>
    </div>

<?php endif; ?>

<?php do_action('woocommerce_after_account_orders', $has_orders); ?>
