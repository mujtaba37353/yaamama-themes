<?php
/**
 * Custom Checkout Template
 *
 * @package MyClinic
 */

defined('ABSPATH') || exit;

// Handle login/registration
$login_error = '';
$register_error = '';
$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';

// Process checkout form: create WooCommerce order from cart and redirect to thank-you
if (is_user_logged_in()
    && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'
    && !empty($_POST['woocommerce-process-checkout-nonce'])
    && wp_verify_nonce($_POST['woocommerce-process-checkout-nonce'], 'woocommerce-process_checkout')
    && !isset($_POST['checkout_login'])
    && !isset($_POST['checkout_register'])
    && function_exists('WC')
) {
    $payment = isset($_POST['payment_method']) ? sanitize_text_field($_POST['payment_method']) : '';
    $email = isset($_POST['billing_email']) ? sanitize_email($_POST['billing_email']) : '';

    // Get cart items
    $cart = WC()->cart;
    if (!$cart->is_empty()) {
        try {
            // Create order from cart
            $order = wc_create_order(array('customer_id' => get_current_user_id()));
            
            if ($order && !is_wp_error($order)) {
                // Add cart items to order
                foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
                    $product = $cart_item['data'];
                    $order->add_product($product, $cart_item['quantity']);
                    
                    // Get booking data from cart item meta
                    $doctor_id = isset($cart_item['booking_doctor_id']) ? absint($cart_item['booking_doctor_id']) : 0;
                    $clinic_id = isset($cart_item['booking_clinic_id']) ? absint($cart_item['booking_clinic_id']) : 0;
                    $date = isset($cart_item['booking_date']) ? sanitize_text_field($cart_item['booking_date']) : '';
                    $patient = isset($cart_item['booking_patient_name']) ? sanitize_text_field($cart_item['booking_patient_name']) : '';
                    $phone = isset($cart_item['booking_patient_phone']) ? sanitize_text_field($cart_item['booking_patient_phone']) : '';
                    
                    // Save booking data to order meta
                    if ($doctor_id > 0) {
                        $order->update_meta_data('_booking_doctor_id', $doctor_id);
                    }
                    if ($clinic_id > 0) {
                        $order->update_meta_data('_booking_clinic_id', $clinic_id);
                    }
                    if ($date) {
                        $order->update_meta_data('_booking_date', $date);
                    }
                    if ($patient) {
                        $order->update_meta_data('_booking_patient_name', $patient);
                    }
                    if ($phone) {
                        $order->update_meta_data('_booking_patient_phone', $phone);
                    }
                }
                
                // Set order details
                $order->set_billing_email($email ?: wp_get_current_user()->user_email);
                $order->set_payment_method($payment ?: 'cod');
                $order->calculate_totals();
                $order->set_status('processing');
                $order->save();
                
                // Clear cart
                $cart->empty_cart();
                
                // Redirect to thank you page
                wp_redirect(home_url('/thank-you?order_id=' . $order->get_id()));
                exit;
            }
        } catch (Exception $e) {
            $checkout_error = __('تعذر إنشاء الطلب. حاول مرة أخرى.', 'my-clinic');
        }
    } else {
        $checkout_error = __('السلة فارغة. يرجى العودة إلى صفحة الحجز.', 'my-clinic');
    }
}

$checkout_error = isset($checkout_error) ? $checkout_error : '';

// Process registration only (no login option)
if (isset($_POST['checkout_register']) && wp_verify_nonce($_POST['checkout_register_nonce'], 'checkout_register')) {
    $email = isset($_POST['register_email']) ? sanitize_email($_POST['register_email']) : '';
    $password = isset($_POST['register_password']) ? $_POST['register_password'] : '';
    $password_confirm = isset($_POST['register_password_confirm']) ? $_POST['register_password_confirm'] : '';
    $phone = isset($_POST['register_phone']) ? sanitize_text_field($_POST['register_phone']) : '';
    $gender = isset($_POST['register_gender']) ? sanitize_text_field($_POST['register_gender']) : '';
    
    if (empty($email) || empty($password) || empty($password_confirm)) {
        $register_error = 'يرجى ملء جميع الحقول المطلوبة';
    } elseif ($password !== $password_confirm) {
        $register_error = 'كلمات المرور غير متطابقة';
    } elseif (strlen($password) < 6) {
        $register_error = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
    } elseif (email_exists($email)) {
        $register_error = 'البريد الإلكتروني مستخدم بالفعل';
    } elseif (!empty($phone) && !preg_match('/^05\d{8}$/', $phone)) {
        $register_error = 'رقم الجوال يجب أن يبدأ بـ 05 ويتبعه 8 أرقام';
    } elseif (empty($gender) || !in_array($gender, array('male', 'female'))) {
        $register_error = 'يرجى اختيار النوع (ذكر أو أنثى)';
    } else {
        $user_id = wp_create_user($email, $password, $email);
        if (is_wp_error($user_id)) {
            $register_error = $user_id->get_error_message();
        } else {
            // Save user meta
            if (!empty($phone)) {
                update_user_meta($user_id, 'billing_phone', $phone);
            }
            if (!empty($gender)) {
                update_user_meta($user_id, 'gender', $gender);
            }
            
            // Auto login after registration
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            // Redirect to checkout page
            $redirect_url = home_url('/checkout');
            if (is_ssl()) {
                $redirect_url = str_replace('http://', 'https://', $redirect_url);
            }
            wp_safe_redirect($redirect_url);
            exit;
        }
    }
}

// Get booking data from cart (if available)
$booking_data = array();
if (function_exists('WC') && !WC()->cart->is_empty()) {
    foreach (WC()->cart->get_cart() as $cart_item) {
        if (isset($cart_item['booking_doctor_id']) || isset($cart_item['booking_clinic_id'])) {
            $booking_data = array(
                'doctor_id' => isset($cart_item['booking_doctor_id']) ? absint($cart_item['booking_doctor_id']) : 0,
                'clinic_id' => isset($cart_item['booking_clinic_id']) ? absint($cart_item['booking_clinic_id']) : 0,
                'date' => isset($cart_item['booking_date']) ? sanitize_text_field($cart_item['booking_date']) : '',
                'patient_name' => isset($cart_item['booking_patient_name']) ? sanitize_text_field($cart_item['booking_patient_name']) : '',
                'patient_phone' => isset($cart_item['booking_patient_phone']) ? sanitize_text_field($cart_item['booking_patient_phone']) : '',
                'patient_email' => isset($cart_item['booking_patient_email']) ? sanitize_email($cart_item['booking_patient_email']) : ''
            );
            break; // Only one booking at a time
        }
    }
}

$is_user_logged_in = is_user_logged_in();
$current_user = wp_get_current_user();
$user_email = $is_user_logged_in ? $current_user->user_email : '';

// If user is not logged in, force registration (no choice)
if (!$is_user_logged_in) {
    $action = 'register';
}

get_header();
?>

<main>
    <section class="doctor-page checkout-custom">
        <div class="container y-u-max-w-1200">
            <div class="bottom">
                <div class="content">
                    <h2 class="checkout-title">الدفع</h2>
                    <div class="checkout-form-area">
                    <?php if ($is_user_logged_in): ?>
                        <!-- Logged in user: show checkout form -->
                        <?php if (!empty($checkout_error)): ?>
                            <div class="error-message" style="margin-bottom: var(--y-space-24);"><?php echo esc_html($checkout_error); ?></div>
                        <?php endif; ?>
                        <?php if (function_exists('WC') && WC()->cart->is_empty()): ?>
                            <div class="error-message" style="margin-bottom: var(--y-space-24);">
                                <?php echo esc_html__('السلة فارغة. يرجى العودة إلى صفحة الحجز.', 'my-clinic'); ?>
                                <br><a href="<?php echo esc_url(home_url('/')); ?>" class="btn main-button" style="margin-top: var(--y-space-16); display: inline-block;">العودة للرئيسية</a>
                            </div>
                        <?php else: ?>
                        <form id="checkout-form" method="POST" action="<?php echo esc_url(home_url('/checkout')); ?>">
                            <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
                            
                            <!-- Email Section -->
                            <div class="checkout-section">
                                <h3>البريد الإلكتروني</h3>
                                <div class="form-group">
                                    <label for="billing_email">البريد الإلكتروني</label>
                                    <input type="email" name="billing_email" id="billing_email" value="<?php echo esc_attr($user_email); ?>" readonly>
                                </div>
                            </div>
                            
                            <!-- Payment Methods Section -->
                            <div class="checkout-section">
                                <h3>طرق الدفع</h3>
                                <div id="payment-methods">
                                    <?php
                                    if (function_exists('WC')) {
                                        $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
                                        if ($available_gateways) {
                                            $first = true;
                                            foreach ($available_gateways as $gateway) {
                                                $is_chosen = $gateway->chosen || $first;
                                                $first = false;
                                                ?>
                                                <div class="payment-method">
                                                    <label>
                                                        <input type="radio" name="payment_method" value="<?php echo esc_attr($gateway->id); ?>" <?php checked($is_chosen, true); ?> required>
                                                        <span><?php echo esc_html($gateway->get_title()); ?></span>
                                                    </label>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            echo '<p>لا توجد طرق دفع متاحة حالياً</p>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            
                            <div class="checkout-buttons">
                                <button type="submit" class="btn main-button fw">ادفع</button>
                                <a href="<?php echo esc_url(wp_get_referer() ?: home_url('/booking')); ?>" class="btn white-button fw">الغاء</a>
                            </div>
                        </form>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (!$is_user_logged_in): ?>
                        <!-- Registration Form (Required for checkout) -->
                        <div class="checkout-section">
                            <h3>إنشاء حساب للمتابعة</h3>
                            <p style="margin-bottom: var(--y-space-24); color: #666;">يجب إنشاء حساب جديد للمتابعة مع الدفع</p>
                            
                            <?php if ($register_error): ?>
                                <div class="error-message" style="color: red; margin-bottom: var(--y-space-16); padding: var(--y-space-12); background: #ffebee; border-radius: var(--y-radius-m);">
                                    <?php echo esc_html($register_error); ?>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="" id="register-form">
                                <?php wp_nonce_field('checkout_register', 'checkout_register_nonce'); ?>
                                
                                <div class="form-group">
                                    <label for="register_email">البريد الإلكتروني</label>
                                    <input type="email" name="register_email" id="register_email" value="<?php echo isset($_POST['register_email']) ? esc_attr($_POST['register_email']) : ''; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="register_phone">رقم الجوال</label>
                                    <input type="tel" name="register_phone" id="register_phone" value="<?php echo isset($_POST['register_phone']) ? esc_attr($_POST['register_phone']) : ''; ?>" pattern="^05\d{8}$" placeholder="05xxxxxxxx" maxlength="10" required>
                                    <small>يجب أن يبدأ بـ 05 ويتبعه 8 أرقام</small>
                                </div>
                                
                                <div class="form-group">
                                    <label>النوع</label>
                                    <div class="gender-selection">
                                        <label>
                                            <input type="radio" name="register_gender" value="male" <?php echo (isset($_POST['register_gender']) && $_POST['register_gender'] === 'male') ? 'checked' : ''; ?> required>
                                            <span>ذكر</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="register_gender" value="female" <?php echo (isset($_POST['register_gender']) && $_POST['register_gender'] === 'female') ? 'checked' : ''; ?> required>
                                            <span>أنثى</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="register_password">كلمة المرور</label>
                                    <input type="password" name="register_password" id="register_password" autocomplete="new-password" required minlength="6">
                                </div>
                                
                                <div class="form-group">
                                    <label for="register_password_confirm">تأكيد كلمة المرور</label>
                                    <input type="password" name="register_password_confirm" id="register_password_confirm" autocomplete="new-password" required minlength="6">
                                    <span class="password-error" id="register-password-error" style="display: none; color: red; font-size: 14px; margin-top: 8px;">كلمات المرور غير متطابقة</span>
                                </div>
                                
                                <div class="checkout-buttons">
                                    <button type="submit" name="checkout_register" class="btn main-button fw">إنشاء حساب والمتابعة</button>
                                    <a href="<?php echo esc_url(home_url('/booking')); ?>" class="btn white-button fw">الغاء</a>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
?>
