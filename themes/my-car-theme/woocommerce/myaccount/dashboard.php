<?php
/**
 * My Account Dashboard - Custom Template
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
$orders_count = wc_get_customer_order_count($current_user->ID);
$recent_orders = wc_get_orders(array(
    'customer_id' => $current_user->ID,
    'limit' => 3,
    'orderby' => 'date',
    'order' => 'DESC',
));
?>

<!-- Dashboard Content -->
<div class="y-c-dashboard-content">
    
    <!-- Welcome Section -->
    <div class="y-c-welcome-section">
        <div class="y-c-welcome-text">
            <h2>لوحة التحكم</h2>
            <p>من هنا يمكنك إدارة حسابك ومتابعة طلباتك</p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="y-c-stats-grid">
        <div class="y-c-stat-card">
            <div class="y-c-stat-icon">
                <i class="fa-solid fa-box"></i>
            </div>
            <div class="y-c-stat-info">
                <span class="y-c-stat-value"><?php echo esc_html($orders_count); ?></span>
                <span class="y-c-stat-label">إجمالي الطلبات</span>
            </div>
        </div>
        
        <div class="y-c-stat-card">
            <div class="y-c-stat-icon">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div class="y-c-stat-info">
                <span class="y-c-stat-value">
                    <?php 
                    $pending_orders = wc_get_orders(array(
                        'customer_id' => $current_user->ID,
                        'status' => array('pending', 'processing', 'on-hold'),
                        'return' => 'ids',
                    ));
                    echo count($pending_orders);
                    ?>
                </span>
                <span class="y-c-stat-label">طلبات قيد التنفيذ</span>
            </div>
        </div>
        
        <div class="y-c-stat-card">
            <div class="y-c-stat-icon">
                <i class="fa-solid fa-check-circle"></i>
            </div>
            <div class="y-c-stat-info">
                <span class="y-c-stat-value">
                    <?php 
                    $completed_orders = wc_get_orders(array(
                        'customer_id' => $current_user->ID,
                        'status' => 'completed',
                        'return' => 'ids',
                    ));
                    echo count($completed_orders);
                    ?>
                </span>
                <span class="y-c-stat-label">طلبات مكتملة</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="y-c-quick-actions">
        <h3 class="y-c-section-title">
            <i class="fa-solid fa-bolt"></i>
            إجراءات سريعة
        </h3>
        <div class="y-c-actions-grid">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" class="y-c-action-card">
                <div class="y-c-action-icon"><i class="fa-solid fa-list"></i></div>
                <span>عرض الطلبات</span>
            </a>
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>" class="y-c-action-card">
                <div class="y-c-action-icon"><i class="fa-solid fa-user-pen"></i></div>
                <span>تعديل الحساب</span>
            </a>
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-address')); ?>" class="y-c-action-card">
                <div class="y-c-action-icon"><i class="fa-solid fa-location-dot"></i></div>
                <span>العناوين</span>
            </a>
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="y-c-action-card">
                <div class="y-c-action-icon"><i class="fa-solid fa-car"></i></div>
                <span>تصفح السيارات</span>
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <?php if (!empty($recent_orders)) : ?>
    <div class="y-c-recent-orders">
        <div class="y-c-section-header-inline">
            <h3 class="y-c-section-title">
                <i class="fa-solid fa-clock-rotate-left"></i>
                آخر الطلبات
            </h3>
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" class="y-c-view-all">
                عرض الكل
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>
        
        <div class="y-c-orders-list">
            <?php foreach ($recent_orders as $order) : ?>
                <div class="y-c-order-card">
                    <div class="y-c-order-header">
                        <span class="y-c-order-number">#<?php echo esc_html($order->get_order_number()); ?></span>
                        <span class="y-c-order-status y-c-status-<?php echo esc_attr($order->get_status()); ?>">
                            <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>
                        </span>
                    </div>
                    <div class="y-c-order-details">
                        <div class="y-c-order-date">
                            <i class="fa-regular fa-calendar"></i>
                            <?php echo esc_html(wc_format_datetime($order->get_date_created())); ?>
                        </div>
                        <div class="y-c-order-total">
                            <i class="fa-solid fa-tag"></i>
                            <?php echo wp_kses_post($order->get_formatted_order_total()); ?>
                        </div>
                    </div>
                    <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="y-c-order-view-btn">
                        عرض التفاصيل
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php else : ?>
    <div class="y-c-no-orders">
        <div class="y-c-no-orders-icon">
            <i class="fa-solid fa-box-open"></i>
        </div>
        <h3>لا توجد طلبات بعد</h3>
        <p>ابدأ بحجز سيارتك الأولى الآن</p>
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
