<?php
/**
 * Orders
 *
 * @package TechnoSouqTheme
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_account_orders', $has_orders);
?>

<?php if ($has_orders) : ?>
    <!-- Orders List -->
    <div class="y-l-orders-list" data-y="orders-list">
        <?php
        foreach ($customer_orders->orders as $customer_order) {
            $order = wc_get_order($customer_order);
            $item_count = $order->get_item_count() - $order->get_item_count_refunded();
            
            // Get first product image
            $first_item = null;
            $items = $order->get_items();
            foreach ($items as $item) {
                $first_item = $item;
                break;
            }
            
            $product_image = '';
            $product_count = count($items);
            if ($first_item) {
                $product = $first_item->get_product();
                if ($product) {
                    $image_id = $product->get_image_id();
                    if ($image_id) {
                        $product_image = wp_get_attachment_image_url($image_id, 'woocommerce_thumbnail');
                    } else {
                        $product_image = wc_placeholder_img_src();
                    }
                }
            }
            
            // Get order status in Arabic
            $status = $order->get_status();
            $status_class = '';
            $status_text = '';
            switch ($status) {
                case 'completed':
                    $status_class = 'y-c-status-delivered';
                    $status_text = __('تم التوصيل', 'techno-souq-theme');
                    break;
                case 'processing':
                    $status_class = 'y-c-status-processing';
                    $status_text = __('قيد المعالجة', 'techno-souq-theme');
                    break;
                case 'on-hold':
                    $status_class = 'y-c-status-pending';
                    $status_text = __('قيد الانتظار', 'techno-souq-theme');
                    break;
                case 'shipped':
                case 'wc-shipped':
                    $status_class = 'y-c-status-shipped';
                    $status_text = __('تم الشحن', 'techno-souq-theme');
                    break;
                case 'cancelled':
                    $status_class = 'y-c-status-cancelled';
                    $status_text = __('ملغي', 'techno-souq-theme');
                    break;
                default:
                    $status_class = 'y-c-status-pending';
                    $status_text = wc_get_order_status_name($status);
            }
            
            // Format order date
            $order_date = $order->get_date_created();
            $formatted_date = $order_date ? $order_date->date_i18n(get_option('date_format')) : '';
            ?>
            <div class="y-c-order-card" data-order-id="<?php echo esc_attr($order->get_id()); ?>">
                <div class="y-c-order-img-wrapper">
                    <?php if ($product_image) : ?>
                        <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($order->get_order_number()); ?>">
                    <?php endif; ?>
                    <?php if ($product_count > 1) : ?>
                        <div class="y-c-order-overlay">
                            <span>+<?php echo esc_html($product_count - 1); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="y-c-order-content">
                    <div class="y-c-order-header">
                        <div class="y-c-order-id-wrapper">
                            <i class="fas fa-cube"></i>
                            <span class="y-c-order-id"><?php echo esc_html(sprintf(__('أوردر-%s', 'techno-souq-theme'), $order->get_order_number())); ?></span>
                        </div>
                        <span class="y-c-order-status <?php echo esc_attr($status_class); ?>"><?php echo esc_html($status_text); ?></span>
                    </div>

                    <div class="y-c-order-meta">
                        <div class="y-c-order-date">
                            <i class="far fa-calendar"></i>
                            <span><?php echo esc_html($formatted_date); ?></span>
                        </div>
                        <span class="y-c-order-price"><?php echo wp_kses_post($order->get_formatted_order_total()); ?></span>
                        <span class="y-c-order-count"><?php echo esc_html(sprintf(_n('%d منتج', '%d منتجات', $item_count, 'techno-souq-theme'), $item_count)); ?></span>
                    </div>
                </div>
                <div class="y-c-order-actions">
                    <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="y-c-btn y-c-btn-show js-show-order-details"><?php esc_html_e('عرض التفاصيل', 'techno-souq-theme'); ?></a>
                </div>
            </div>
            <?php
        }
        ?>
    </div>

    <?php do_action('woocommerce_before_account_orders_pagination'); ?>

    <?php if (1 < $customer_orders->max_num_pages) : ?>
        <div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
            <?php if (1 !== $current_page) : ?>
                <a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url(wc_get_endpoint_url('orders', $current_page - 1)); ?>"><?php esc_html_e('السابق', 'techno-souq-theme'); ?></a>
            <?php endif; ?>

            <?php if (intval($customer_orders->max_num_pages) !== $current_page) : ?>
                <a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url(wc_get_endpoint_url('orders', $current_page + 1)); ?>"><?php esc_html_e('التالي', 'techno-souq-theme'); ?></a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

<?php else : ?>
    <!-- Empty Orders State -->
    <div class="y-c-empty-state" data-y="empty-orders-container">
        <div class="y-c-empty-icon-wrapper">
            <i class="fas fa-shopping-cart y-c-empty-icon"></i>
        </div>
        <h3 class="y-c-empty-title"><?php esc_html_e('لا يوجد طلبات', 'techno-souq-theme'); ?></h3>
        <a href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>" class="y-c-btn y-c-btn-primary y-c-empty-btn"><?php esc_html_e('عودة للتسوق', 'techno-souq-theme'); ?></a>
    </div>
<?php endif; ?>

<?php do_action('woocommerce_after_account_orders', $has_orders); ?>