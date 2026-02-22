<?php
/**
 * My Account Page - Main Template
 *
 * @package MyClinic
 */

defined('ABSPATH') || exit;

// If user is not logged in, show WooCommerce login form
if (!is_user_logged_in()) {
    // Enqueue auth styles
    $theme_version = wp_get_theme()->get('Version');
    $theme_uri = get_template_directory_uri();
    wp_enqueue_style('my-clinic-auth', $theme_uri . '/assets/css/components/auth.css', array(
        'my-clinic-header',
        'my-clinic-footer',
        'my-clinic-buttons'
    ), $theme_version);
    
    get_header();
    
    // Check if we need to show registration or lost password form
    $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    
    // Check for lost password or reset password endpoints
    if (strpos($request_uri, 'lost-password') !== false || $action === 'lostpassword') {
        // Load lost password form
        wc_get_template('myaccount/form-lost-password.php');
    } elseif (strpos($request_uri, 'reset-password') !== false || $action === 'rp') {
        // Load reset password form
        $key = isset($_GET['key']) ? sanitize_text_field($_GET['key']) : '';
        $login = isset($_GET['login']) ? sanitize_text_field($_GET['login']) : '';
        wc_get_template('myaccount/form-reset-password.php', array('key' => $key, 'login' => $login));
    } elseif ($action === 'register') {
        // Load registration form
        wc_get_template('myaccount/form-register.php');
    } else {
        // Load login form (default)
        wc_get_template('myaccount/form-login.php');
    }
    
    get_footer();
    return;
}

// User is logged in - proceed with account page
$current_user = wp_get_current_user();
$user_id = $current_user->ID;

// Enqueue account styles
$theme_version = wp_get_theme()->get('Version');
$theme_uri = get_template_directory_uri();
wp_enqueue_style('my-clinic-my-account', $theme_uri . '/assets/css/components/my-account.css', array(
    'my-clinic-header',
    'my-clinic-footer',
    'my-clinic-buttons'
), $theme_version);

// Add body class to prevent scrolling on mobile
add_filter('body_class', function($classes) {
    $classes[] = 'account-page';
    $classes[] = 'woocommerce-account';
    return $classes;
});

// Add html class to prevent scrolling on mobile
add_action('wp_footer', function() {
    echo '<script>
    (function() {
        if (window.innerWidth <= 768) {
            document.documentElement.classList.add("account-page", "woocommerce-account");
        }
    })();
    </script>';
}, 999);

// Get current endpoint
$current_endpoint = '';
if (function_exists('WC')) {
    $current_endpoint = WC()->query->get_current_endpoint();
}
if (empty($current_endpoint)) {
    $current_endpoint = 'dashboard';
}

// Get account page URL
$account_url = wc_get_page_permalink('myaccount');

// Get endpoint from passed args or detect from URL
$endpoint = isset($args['endpoint']) ? $args['endpoint'] : 'dashboard';
if (!$endpoint) {
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($request_uri, '/orders') !== false) {
        $endpoint = 'orders';
    } elseif (strpos($request_uri, '/edit-account') !== false) {
        $endpoint = 'edit-account';
    } else {
        $endpoint = 'dashboard';
    }
}

// Get user data from args or fetch
$full_name = isset($args['full_name']) ? $args['full_name'] : '';
$phone = isset($args['phone']) ? $args['phone'] : '';
$email = isset($args['email']) ? $args['email'] : $current_user->user_email;
$gender = isset($args['gender']) ? $args['gender'] : '';
$birthdate = isset($args['birthdate']) ? $args['birthdate'] : '';
$national_id = isset($args['national_id']) ? $args['national_id'] : '';
$full_address = isset($args['full_address']) ? $args['full_address'] : '';
$avatar_url = isset($args['avatar_url']) ? $args['avatar_url'] : '';

if (!$full_name) {
    $first_name = get_user_meta($user_id, 'first_name', true) ?: '';
    $last_name = get_user_meta($user_id, 'last_name', true) ?: '';
    $full_name = trim($first_name . ' ' . $last_name) ?: $current_user->display_name;
}
if (!$phone) {
    $phone = get_user_meta($user_id, 'billing_phone', true) ?: '';
}
if (!$gender) {
    $gender = get_user_meta($user_id, 'gender', true) ?: '';
}
if (!$birthdate) {
    $birthdate = get_user_meta($user_id, 'birthdate', true) ?: '';
}
if (!$national_id) {
    $national_id = get_user_meta($user_id, 'national_id', true) ?: '';
}
if (!$full_address) {
    $address = get_user_meta($user_id, 'billing_address_1', true) ?: '';
    $city = get_user_meta($user_id, 'billing_city', true) ?: '';
    $full_address = trim($address . ($city ? ', ' . $city : ''));
}
if (!$avatar_url) {
    $avatar_url = get_avatar_url($user_id, array('size' => 300)) ?: get_template_directory_uri() . '/assets/images/man.jpg';
}

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
                        <input type="radio" name="tab" id="doctors-clinic" <?php echo ($endpoint === 'dashboard') ? 'checked' : ''; ?>>
                        <input type="radio" name="tab" id="about-clinic" <?php echo ($endpoint === 'orders') ? 'checked' : ''; ?>>
                        <input type="radio" name="tab" id="rating-clinic" <?php echo ($endpoint === 'edit-account') ? 'checked' : ''; ?>>
                        <div class="tabs">
                            <label for="doctors-clinic" onclick="window.location.href='<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>'">البيانات الشخصية</label>
                            <label for="about-clinic" onclick="window.location.href='<?php echo esc_url(wc_get_endpoint_url('orders', '', wc_get_page_permalink('myaccount'))); ?>'">الحجوزات</label>
                            <label for="rating-clinic" onclick="window.location.href='<?php echo esc_url(wc_get_endpoint_url('edit-account', '', wc_get_page_permalink('myaccount'))); ?>'">الاعدادات</label>
                        </div>

                        <?php if ($endpoint === 'dashboard'): ?>
                            <div class="doctors-box-clinic">
                                <p>الاسم الكامل : <?php echo esc_html($full_name); ?></p>
                                <?php if ($phone): ?>
                                    <p>رقم الجوال : <?php echo esc_html($phone); ?></p>
                                <?php endif; ?>
                                <p>البريد الإلكتروني : <?php echo esc_html($email); ?></p>
                                <?php if ($gender): ?>
                                    <p>الجنس : <?php echo esc_html($gender === 'male' ? 'ذكر' : ($gender === 'female' ? 'أنثى' : $gender)); ?></p>
                                <?php endif; ?>
                                <?php if ($birthdate): ?>
                                    <p>تاريخ الميلاد : <?php echo esc_html($birthdate); ?></p>
                                <?php endif; ?>
                                <?php if ($national_id): ?>
                                    <p>رقم الهوية : <?php echo esc_html($national_id); ?></p>
                                <?php endif; ?>
                                <?php if ($full_address): ?>
                                    <p>العنوان : <?php echo esc_html($full_address); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php elseif ($endpoint === 'orders'): ?>
                            <?php 
                            $orders_template = locate_template('woocommerce/myaccount/orders.php');
                            if ($orders_template) {
                                include $orders_template;
                            }
                            ?>
                        <?php elseif ($endpoint === 'edit-account'): ?>
                            <?php 
                            $edit_template = locate_template('woocommerce/myaccount/edit-account.php');
                            if ($edit_template) {
                                include $edit_template;
                            }
                            ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
