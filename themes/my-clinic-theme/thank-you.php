<?php
/**
 * Thank You Page – بعد نجاح إنشاء الطلب
 *
 * @package MyClinic
 */

defined('ABSPATH') || exit;

$order_id = isset($_GET['order_id']) ? absint($_GET['order_id']) : 0;
$order = $order_id && function_exists('wc_get_order') ? wc_get_order($order_id) : null;

if (!$order || !$order->get_id()) {
    wp_redirect(home_url('/'));
    exit;
}

$doctor_id = (int) $order->get_meta('_booking_doctor_id');
$clinic_id = (int) $order->get_meta('_booking_clinic_id');
$date     = $order->get_meta('_booking_date');
$phone    = $order->get_meta('_booking_patient_phone');

$booking_title = '';
$contact_phone = '';
$contact_whatsapp = '';
$booking_url = '';
$is_doctor = $doctor_id > 0;

if ($is_doctor && $doctor_id) {
    $post = get_post($doctor_id);
    if ($post && $post->post_type === 'doctor') {
        $meta = my_clinic_get_doctor_meta($doctor_id);
        $booking_title = get_the_title($doctor_id);
        $booking_url = get_permalink($doctor_id);
        $contact_phone = $meta['phone'] ?? '';
        $contact_whatsapp = $meta['whatsapp'] ?? '';
    }
} elseif ($clinic_id) {
    $post = get_post($clinic_id);
    if ($post && $post->post_type === 'clinic') {
        $meta = my_clinic_get_clinic_meta($clinic_id);
        $booking_title = get_the_title($clinic_id);
        $booking_url = get_permalink($clinic_id);
        $contact_phone = $meta['phone'] ?? '';
        $contact_whatsapp = $meta['whatsapp'] ?? '';
    }
}

if (!$booking_title) {
    wp_redirect(home_url('/'));
    exit;
}

$date_display = $date ? $date : date_i18n('d/m/Y');
$phone_clean = $contact_phone ? preg_replace('/[^0-9+]/', '', $contact_phone) : '';
$whatsapp_clean = $contact_whatsapp ? preg_replace('/[^0-9]/', '', $contact_whatsapp) : '';

get_header();
?>

<main>
    <section class="thank-you-page">
        <div class="container y-u-max-w-1200">
            <div class="thank-you-content">
                <div class="thank-you-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h1 class="thank-you-title">تم الحجز بنجاح</h1>

                <div class="thank-you-booking">
                    <p class="booking-label"><?php echo $is_doctor ? esc_html__('الطبيب', 'my-clinic') : esc_html__('العيادة', 'my-clinic'); ?></p>
                    <?php if ($booking_url): ?>
                        <a href="<?php echo esc_url($booking_url); ?>" class="booking-name"><?php echo esc_html($booking_title); ?></a>
                    <?php else: ?>
                        <span class="booking-name"><?php echo esc_html($booking_title); ?></span>
                    <?php endif; ?>
                    <p class="booking-date"><?php echo esc_html__('التاريخ', 'my-clinic'); ?>: <?php echo esc_html($date_display); ?></p>
                </div>

                <div class="thank-you-contact">
                    <?php if ($contact_whatsapp && $whatsapp_clean): ?>
                        <a href="https://wa.me/<?php echo esc_attr($whatsapp_clean); ?>" target="_blank" rel="noopener" class="contact-btn contact-whatsapp">
                            <i class="fa-brands fa-whatsapp"></i>
                            <span>واتساب</span>
                        </a>
                    <?php endif; ?>
                    <?php if ($contact_phone && $phone_clean): ?>
                        <a href="tel:<?php echo esc_attr($phone_clean); ?>" class="contact-btn contact-phone">
                            <i class="fas fa-phone"></i>
                            <span>اتصال</span>
                        </a>
                    <?php endif; ?>
                </div>

                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn main-button fw">العودة للرئيسية</a>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
