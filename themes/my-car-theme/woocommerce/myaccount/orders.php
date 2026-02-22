<?php
/**
 * Orders - Custom Template
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;

$customer_orders = wc_get_orders(
    apply_filters(
        'woocommerce_my_account_my_orders_query',
        array(
            'customer' => get_current_user_id(),
            'page'     => $current_page,
            'paginate' => true,
        )
    )
);

$has_orders = 0 < $customer_orders->total;
?>

<!-- Orders Content -->
<div class="y-c-orders-content">
    
    <div class="y-c-page-header">
        <h2>طلباتي</h2>
        <p>عرض وإدارة جميع طلباتك</p>
    </div>

    <?php if ($has_orders) : ?>

        <div class="y-c-orders-table-wrapper">
            <?php foreach ($customer_orders->orders as $customer_order) :
                $order = wc_get_order($customer_order);
                $item_count = $order->get_item_count() - $order->get_item_count_refunded();
            ?>
                <div class="y-c-order-row">
                    <div class="y-c-order-main">
                        <div class="y-c-order-info">
                            <span class="y-c-order-id">#<?php echo esc_html($order->get_order_number()); ?></span>
                            <span class="y-c-order-date">
                                <i class="fa-regular fa-calendar"></i>
                                <?php echo esc_html(wc_format_datetime($order->get_date_created())); ?>
                            </span>
                        </div>
                        <div class="y-c-order-meta">
                            <span class="y-c-order-items">
                                <i class="fa-solid fa-box"></i>
                                <?php echo esc_html($item_count); ?> <?php echo $item_count > 1 ? 'منتجات' : 'منتج'; ?>
                            </span>
                            <span class="y-c-order-total">
                                <i class="fa-solid fa-tag"></i>
                                <?php echo wp_kses_post($order->get_formatted_order_total()); ?>
                            </span>
                        </div>
                    </div>
                    <div class="y-c-order-status-col">
                        <span class="y-c-order-status y-c-status-<?php echo esc_attr($order->get_status()); ?>">
                            <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>
                        </span>
                    </div>
                    <div class="y-c-order-actions">
                        <?php
                        $actions = wc_get_account_orders_actions($order);
                        if (!empty($actions)) :
                            foreach ($actions as $key => $action) :
                        ?>
                            <a href="<?php echo esc_url($action['url']); ?>" class="y-c-order-action-btn y-c-action-<?php echo esc_attr($key); ?>">
                                <?php
                                switch ($key) {
                                    case 'view':
                                        echo '<i class="fa-solid fa-eye"></i>';
                                        echo '<span>عرض</span>';
                                        break;
                                    case 'pay':
                                        echo '<i class="fa-solid fa-credit-card"></i>';
                                        echo '<span>دفع</span>';
                                        break;
                                    case 'cancel':
                                        echo '<i class="fa-solid fa-times"></i>';
                                        echo '<span>إلغاء</span>';
                                        break;
                                    default:
                                        echo '<span>' . esc_html($action['name']) . '</span>';
                                }
                                ?>
                            </a>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (1 < $customer_orders->max_num_pages) : ?>
            <div class="y-c-pagination">
                <?php if ($current_page > 1) : ?>
                    <a class="y-c-page-btn y-c-prev" href="<?php echo esc_url(wc_get_endpoint_url('orders', $current_page - 1)); ?>">
                        <i class="fa-solid fa-chevron-right"></i>
                        السابق
                    </a>
                <?php endif; ?>

                <span class="y-c-page-info">
                    صفحة <?php echo esc_html($current_page); ?> من <?php echo esc_html($customer_orders->max_num_pages); ?>
                </span>

                <?php if ($current_page < $customer_orders->max_num_pages) : ?>
                    <a class="y-c-page-btn y-c-next" href="<?php echo esc_url(wc_get_endpoint_url('orders', $current_page + 1)); ?>">
                        التالي
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else : ?>
        
        <div class="y-c-no-orders">
            <div class="y-c-no-orders-icon">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <h3>لا توجد طلبات بعد</h3>
            <p>لم تقم بأي طلبات حتى الآن. ابدأ بحجز سيارتك الأولى!</p>
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="y-c-browse-btn">
                <span>تصفح السيارات</span>
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>

    <?php endif; ?>

</div>

        </div>
    </div>
</div>
