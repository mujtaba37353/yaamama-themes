<?php
/**
 * Template for booking page
 *
 * @package MyClinic
 */

defined('ABSPATH') || exit;

// Process booking form: add product to cart and redirect to checkout
$booking_error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' 
    && isset($_POST['booking_submit']) 
    && isset($_POST['booking_nonce'])
    && wp_verify_nonce($_POST['booking_nonce'], 'booking_form')
) {
    $doctor_id = isset($_POST['doctor_id']) ? absint($_POST['doctor_id']) : 0;
    $clinic_id = isset($_POST['clinic_id']) ? absint($_POST['clinic_id']) : 0;
    $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';
    $patient_name = isset($_POST['patient_name']) ? sanitize_text_field($_POST['patient_name']) : '';
    $patient_phone = isset($_POST['patient_phone']) ? sanitize_text_field($_POST['patient_phone']) : '';
    $patient_email = isset($_POST['patient_email']) ? sanitize_email($_POST['patient_email']) : '';

    // Validate required fields
    if (empty($patient_name) || empty($patient_phone) || empty($patient_email)) {
        $booking_error = 'يرجى ملء جميع الحقول المطلوبة';
    } elseif (!preg_match('/^05\d{8}$/', $patient_phone)) {
        $booking_error = 'رقم الجوال يجب أن يبدأ بـ 05 ويتبعه 8 أرقام';
    } elseif (empty($doctor_id) && empty($clinic_id)) {
        $booking_error = 'خطأ: لم يتم تحديد طبيب أو عيادة. يرجى المحاولة مرة أخرى.';
    } elseif (!function_exists('WC')) {
        $booking_error = 'خطأ: WooCommerce غير مفعل. يرجى الاتصال بالدعم.';
    } elseif (($doctor_id > 0 || $clinic_id > 0) && function_exists('WC')) {
        // Get product ID
        $product_id = 0;
        if ($doctor_id > 0) {
            $product_id = (int) get_post_meta($doctor_id, '_doctor_product_id', true);
        } elseif ($clinic_id > 0) {
            $product_id = (int) get_post_meta($clinic_id, '_clinic_product_id', true);
        }

        // If product doesn't exist, try to create it
        if ($product_id == 0) {
            // Try to create product on the fly
            if ($doctor_id > 0 && function_exists('my_clinic_sync_doctor_product')) {
                $product_id = my_clinic_sync_doctor_product($doctor_id);
            } elseif ($clinic_id > 0 && function_exists('my_clinic_sync_clinic_product')) {
                $product_id = my_clinic_sync_clinic_product($clinic_id);
            }
        }
        
        if ($product_id > 0) {
            // Verify product exists
            $product = wc_get_product($product_id);
            if (!$product || !$product->is_purchasable()) {
                $booking_error = 'المنتج غير متاح. يرجى المحاولة مرة أخرى.';
            } else {
                // Clear cart first (only one booking at a time)
                WC()->cart->empty_cart();

                // Store booking data in session to be added via filter
                WC()->session->set('booking_doctor_id', $doctor_id);
                WC()->session->set('booking_clinic_id', $clinic_id);
                WC()->session->set('booking_date', $date);
                WC()->session->set('booking_patient_name', $patient_name);
                WC()->session->set('booking_patient_phone', $patient_phone);
                WC()->session->set('booking_patient_email', $patient_email);

                // Add product to cart (booking data will be added via filter)
                $cart_item_key = WC()->cart->add_to_cart($product_id, 1);

                if ($cart_item_key && !is_wp_error($cart_item_key)) {
                    // Ensure cart is persisted
                    WC()->cart->calculate_totals();
                    
                    // Get checkout URL with fallback
                    $checkout_url = '';
                    if (function_exists('wc_get_checkout_url')) {
                        $checkout_url = wc_get_checkout_url();
                    }
                    if (empty($checkout_url)) {
                        // Try to get checkout page ID
                        if (function_exists('wc_get_page_id')) {
                            $checkout_page_id = wc_get_page_id('checkout');
                            if ($checkout_page_id) {
                                $checkout_url = get_permalink($checkout_page_id);
                            }
                        }
                    }
                    if (empty($checkout_url)) {
                        $checkout_url = home_url('/checkout');
                    }
                    
                    // Ensure URL is absolute
                    if (strpos($checkout_url, 'http') !== 0) {
                        $checkout_url = home_url($checkout_url);
                    }
                    
                    // Redirect to checkout
                    wp_safe_redirect(esc_url_raw($checkout_url));
                    exit;
                } else {
                    $error_msg = is_wp_error($cart_item_key) ? $cart_item_key->get_error_message() : '';
                    $booking_error = 'تعذر إضافة المنتج إلى السلة. ' . ($error_msg ? $error_msg : 'يرجى المحاولة مرة أخرى.');
                }
            }
        } else {
            $booking_error = 'لم يتم العثور على منتج مرتبط. يرجى المحاولة مرة أخرى.';
        }
    } else {
        $booking_error = 'خطأ في البيانات المرسلة. يرجى المحاولة مرة أخرى.';
    }
}

get_header();

// Get parameters from URL
$doctor_id = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;
$clinic_id = isset($_GET['clinic_id']) ? intval($_GET['clinic_id']) : 0;
$date = isset($_GET['date']) ? sanitize_text_field($_GET['date']) : '';

// Determine if booking is for doctor or clinic
$is_clinic = $clinic_id > 0 && $doctor_id == 0;
$is_doctor = $doctor_id > 0;

// Get booking information
$booking_title = '';
$booking_image = '';
$booking_url = '';
$booking_specialty = '';
$booking_clinic_name = '';
$booking_date_text = '';
$booking_time = '';

if ($is_doctor && $doctor_id > 0) {
    $doctor = get_post($doctor_id);
    if ($doctor && $doctor->post_type === 'doctor') {
        $doctor_meta = my_clinic_get_doctor_meta($doctor_id);
        $booking_title = get_the_title($doctor_id);
        $booking_image = get_the_post_thumbnail_url($doctor_id, 'medium') ?: get_template_directory_uri() . '/assets/images/doctor-img.jpg';
        $booking_url = get_permalink($doctor_id);
        $booking_specialty = $doctor_meta['specialty'] ?: '';
        $booking_time = $doctor_meta['work_schedule'] ? my_clinic_format_work_schedule($doctor_meta['work_schedule']) : '04:00 مساءً - 07:00 مساءً';
        
        // Get clinic name if doctor has clinic
        if (!empty($doctor_meta['clinic_id'])) {
            $clinic = get_post($doctor_meta['clinic_id']);
            if ($clinic) {
                $booking_clinic_name = get_the_title($doctor_meta['clinic_id']);
            }
        }
    }
} elseif ($is_clinic && $clinic_id > 0) {
    $clinic = get_post($clinic_id);
    if ($clinic && $clinic->post_type === 'clinic') {
        $clinic_meta = my_clinic_get_clinic_meta($clinic_id);
        $booking_title = get_the_title($clinic_id);
        $booking_image = get_the_post_thumbnail_url($clinic_id, 'medium') ?: get_template_directory_uri() . '/assets/images/pro-clinic.jpg';
        $booking_url = get_permalink($clinic_id);
        $booking_time = $clinic_meta['work_schedule'] ? my_clinic_format_work_schedule($clinic_meta['work_schedule']) : '04:00 مساءً - 07:00 مساءً';
    }
}

// Format date text
if ($date) {
    $booking_date_text = $date;
} else {
    $today = date_i18n('d/m/Y');
    $booking_date_text = 'اليوم | ' . $today;
}

// If no valid booking found, redirect to home
if (!$booking_title) {
    wp_redirect(home_url('/'));
    exit;
}

// Format full date and time text
$full_date_time = $booking_date_text . ' (' . $booking_time . ')';
?>

<main>
    <section class="doctor-page booking <?php echo $is_clinic ? 'booking-clinc' : 'booking-doctor'; ?>">
        <div class="container y-u-max-w-1200">
            <div class="bottom">
                <div class="content">
                    <h2>اكمل حجزك</h2>
                    <div class="right">
                        <div class="box">
                            <img src="<?php echo esc_url($booking_image); ?>" alt="<?php echo esc_attr($booking_title); ?>">
                            <a href="<?php echo esc_url($booking_url); ?>">
                                <h3><?php echo esc_html($booking_title); ?></h3>
                            </a>
                            <?php if ($is_doctor && $booking_specialty): ?>
                                <p><?php echo esc_html($booking_specialty); ?></p>
                            <?php endif; ?>
                            <?php if ($is_doctor && $booking_clinic_name): ?>
                                <p class="small-text"><?php echo esc_html($booking_clinic_name); ?></p>
                            <?php endif; ?>
                            <p class="small-text"><?php echo esc_html($full_date_time); ?></p>
                        </div>
                    </div>
                    <div class="left">
                        <h2>اكمل حجزك</h2>
                        <?php if (!empty($booking_error)): ?>
                            <div class="error-message" style="color: red; margin-bottom: var(--y-space-16); padding: var(--y-space-12); background: #ffebee; border-radius: var(--y-radius-m);">
                                <?php echo esc_html($booking_error); ?>
                            </div>
                        <?php endif; ?>
                        <form action="" method="POST" id="booking-form">
                            <?php wp_nonce_field('booking_form', 'booking_nonce'); ?>
                            <input type="hidden" name="booking_submit" value="1">
                            <input type="hidden" name="doctor_id" value="<?php echo esc_attr($doctor_id); ?>">
                            <input type="hidden" name="clinic_id" value="<?php echo esc_attr($clinic_id); ?>">
                            <input type="hidden" name="date" value="<?php echo esc_attr($date); ?>">
                            
                            <label for="patient_name">اسم المريض</label>
                            <input type="text" name="patient_name" id="patient_name" value="<?php echo isset($_POST['patient_name']) ? esc_attr($_POST['patient_name']) : ''; ?>" required>
                            
                            <label for="patient_phone">رقم الجوال</label>
                            <input type="tel" name="patient_phone" id="patient_phone" pattern="^05[0-9]{8}$" placeholder="05XXXXXXXX" maxlength="10" value="<?php echo isset($_POST['patient_phone']) ? esc_attr($_POST['patient_phone']) : ''; ?>" required>
                            <span class="phone-error" id="phone-error" style="display: none; color: red; font-size: 14px; margin-top: -20px; margin-bottom: 20px;">رقم الجوال يجب أن يبدأ بـ 05 ويتبعه 8 أرقام</span>
                            
                            <label for="patient_email">البريد الإلكتروني</label>
                            <input type="email" name="patient_email" id="patient_email" value="<?php echo isset($_POST['patient_email']) ? esc_attr($_POST['patient_email']) : ''; ?>" required>
                            
                            <div class="buttons">
                                <button type="submit" class="btn main-button fw">احجز</button>
                                <a href="<?php echo esc_url($booking_url); ?>" class="btn white-button fw">الغاء</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
?>
