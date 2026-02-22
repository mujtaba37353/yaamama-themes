<?php
/**
 * My Account - Orders (Bookings) Page
 *
 * @package MyClinic
 */

defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
$user_id = $current_user->ID;

// Get user data for header
$first_name = get_user_meta($user_id, 'first_name', true) ?: '';
$last_name = get_user_meta($user_id, 'last_name', true) ?: '';
$full_name = trim($first_name . ' ' . $last_name) ?: $current_user->display_name;
$avatar_url = get_avatar_url($user_id, array('size' => 300)) ?: get_template_directory_uri() . '/assets/images/man.jpg';

$current_user_id = get_current_user_id();
$orders = wc_get_orders(array(
    'customer_id' => $current_user_id,
    'limit' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
    'status' => array('wc-processing', 'wc-completed', 'wc-on-hold', 'wc-pending')
));

get_header();
?>

<main>
    <section class="doctor-page clinic-page account-page">
        <div class="container y-u-max-w-1200">
            <div class="bottom">
                <div class="content">
                    <div class="right">
                        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($full_name); ?>">
                    </div>
                    <div class="left">
                        <input type="radio" name="tab" id="doctors-clinic">
                        <input type="radio" name="tab" id="about-clinic" checked>
                        <input type="radio" name="tab" id="rating-clinic">
                        <div class="tabs">
                            <label for="doctors-clinic" onclick="window.location.href='<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>'">البيانات الشخصية</label>
                            <label for="about-clinic" onclick="window.location.href='<?php echo esc_url(wc_get_endpoint_url('orders', '', wc_get_page_permalink('myaccount'))); ?>'">الحجوزات</label>
                            <label for="rating-clinic" onclick="window.location.href='<?php echo esc_url(wc_get_endpoint_url('edit-account', '', wc_get_page_permalink('myaccount'))); ?>'">الاعدادات</label>
                        </div>

<?php if (empty($orders)): ?>
    <div class="about-box-clinic">
        <p style="text-align: center; padding: var(--y-space-32);">لا توجد حجوزات حالياً</p>
    </div>
<?php else: ?>
    <div class="about-box-clinic">
        <?php foreach ($orders as $order): 
            $order_id = $order->get_id();
            $doctor_id = (int) $order->get_meta('_booking_doctor_id');
            $clinic_id = (int) $order->get_meta('_booking_clinic_id');
            $booking_date = $order->get_meta('_booking_date') ?: '';
            
            $booking_title = '';
            $booking_image = '';
            $booking_url = '';
            $booking_specialty = '';
            $booking_address = '';
            $is_doctor = $doctor_id > 0;
            
            if ($is_doctor && $doctor_id) {
                $post = get_post($doctor_id);
                if ($post && $post->post_type === 'doctor') {
                    $meta = my_clinic_get_doctor_meta($doctor_id);
                    $booking_title = get_the_title($doctor_id);
                    $booking_url = get_permalink($doctor_id);
                    $booking_image = get_the_post_thumbnail_url($doctor_id, 'thumbnail') ?: get_template_directory_uri() . '/assets/images/doctor-img.jpg';
                    $booking_specialty = $meta['specialty'] ?: '';
                    $booking_address = $meta['address'] ?: '';
                }
            } elseif ($clinic_id) {
                $post = get_post($clinic_id);
                if ($post && $post->post_type === 'clinic') {
                    $meta = my_clinic_get_clinic_meta($clinic_id);
                    $booking_title = get_the_title($clinic_id);
                    $booking_url = get_permalink($clinic_id);
                    $booking_image = get_the_post_thumbnail_url($clinic_id, 'thumbnail') ?: get_template_directory_uri() . '/assets/images/pro-clinic.jpg';
                    $booking_address = $meta['address'] ?: '';
                }
            }
            
            if (!$booking_title) continue;
            
            // Format date
            $date_display = $booking_date ?: date_i18n('d/m/Y', strtotime($order->get_date_created()));
            $work_schedule = $is_doctor ? (my_clinic_format_work_schedule($meta['work_schedule'] ?? array()) ?: '04:00 مساءً - 07:00 مساءً') : (my_clinic_format_work_schedule($meta['work_schedule'] ?? array()) ?: '04:00 مساءً - 07:00 مساءً');
        ?>
            <div class="box">
                <div class="top">
                    <img src="<?php echo esc_url($booking_image); ?>" alt="<?php echo esc_attr($booking_title); ?>">
                    <div class="content">
                        <?php if ($booking_url): ?>
                            <a href="<?php echo esc_url($booking_url); ?>">
                                <h3><?php echo esc_html($booking_title); ?></h3>
                            </a>
                        <?php else: ?>
                            <h3><?php echo esc_html($booking_title); ?></h3>
                        <?php endif; ?>
                        <?php if ($is_doctor && $booking_specialty): ?>
                            <p><?php echo esc_html($booking_specialty); ?></p>
                        <?php endif; ?>
                        <?php if ($booking_address): ?>
                            <p class="address">
                                <?php if (file_exists(get_template_directory() . '/assets/images/address.svg')): ?>
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/address.svg'); ?>" alt="العنوان">
                                <?php endif; ?>
                                <?php echo esc_html($booking_address); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
                <p><?php echo esc_html($date_display . ($work_schedule ? ' (' . $work_schedule . ')' : '')); ?></p>
                <div class="actions">
                    <button onclick="window.location.href='<?php echo esc_url(wc_get_endpoint_url('view-order', $order_id)); ?>'" class="edit-btn">
                        <?php if (file_exists(get_template_directory() . '/assets/images/edit.svg')): ?>
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/edit.svg'); ?>" alt="تعديل">
                        <?php endif; ?>
                        تعديل
                    </button>
                    <button class="delete" onclick="if(confirm('هل أنت متأكد من إلغاء الحجز؟')) { window.location.href='<?php echo esc_url(wp_nonce_url(add_query_arg('cancel_order', $order_id, wc_get_account_endpoint_url('orders')), 'woocommerce-cancel_order')); ?>'; }">
                        <?php if (file_exists(get_template_directory() . '/assets/images/delete.svg')): ?>
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/delete.svg'); ?>" alt="إلغاء">
                        <?php endif; ?>
                        إلغاء
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
                    
                    <div style="margin-top: var(--y-space-32); padding-top: var(--y-space-24); border-top: 1px solid var(--y-color-border);">
                        <a href="<?php echo esc_url(wp_logout_url(home_url('/login'))); ?>" class="btn" style="background: #dc3232; color: white; border-color: #dc3232; display: inline-block; text-decoration: none; padding: var(--y-space-12) var(--y-space-24); border-radius: var(--y-radius-m);">تسجيل الخروج</a>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
