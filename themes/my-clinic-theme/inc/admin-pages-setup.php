<?php
/**
 * Admin Page for Setting Up Required Pages
 *
 * @package MyClinic
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register Admin Menu for Pages Setup
 */
function my_clinic_add_pages_setup_admin_menu() {
    add_submenu_page(
        'themes.php',
        __('إنشاء صفحات الموقع', 'my-clinic'),
        __('إنشاء صفحات الموقع', 'my-clinic'),
        'manage_options',
        'pages-setup',
        'my_clinic_render_pages_setup_admin_page'
    );
}
add_action('admin_menu', 'my_clinic_add_pages_setup_admin_menu');

/**
 * Render Pages Setup Admin Page
 */
function my_clinic_render_pages_setup_admin_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('ليس لديك صلاحية للوصول إلى هذه الصفحة.', 'my-clinic'));
    }

    // Handle form submission
    $message = '';
    $message_type = '';

    if (isset($_POST['setup_pages']) && wp_verify_nonce($_POST['pages_setup_nonce'], 'pages_setup_action')) {
        $result = my_clinic_setup_required_pages();
        if ($result['success']) {
            $message = $result['message'];
            $message_type = 'success';
        } else {
            $message = $result['message'];
            $message_type = 'error';
        }
    }

    // Get current pages status
    $pages_status = my_clinic_get_pages_status();

    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('إنشاء صفحات الموقع', 'my-clinic'); ?></h1>
        
        <?php if ($message): ?>
            <div class="notice notice-<?php echo esc_attr($message_type); ?> is-dismissible">
                <p><?php echo wp_kses_post($message); ?></p>
            </div>
        <?php endif; ?>

        <div class="card" style="max-width: 900px; margin-top: 20px;">
            <h2><?php echo esc_html__('حالة الصفحات المطلوبة', 'my-clinic'); ?></h2>
            <p><?php echo esc_html__('سيتم إنشاء أو تحديث جميع الصفحات الضرورية للموقع.', 'my-clinic'); ?></p>
            
            <table class="widefat" style="margin-top: 20px;">
                <thead>
                    <tr>
                        <th><?php echo esc_html__('اسم الصفحة', 'my-clinic'); ?></th>
                        <th><?php echo esc_html__('الرابط', 'my-clinic'); ?></th>
                        <th><?php echo esc_html__('الحالة', 'my-clinic'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pages_status as $page): ?>
                        <tr>
                            <td><strong><?php echo esc_html($page['title']); ?></strong></td>
                            <td>
                                <?php if ($page['exists']): ?>
                                    <a href="<?php echo esc_url($page['url']); ?>" target="_blank"><?php echo esc_html($page['url']); ?></a>
                                <?php else: ?>
                                    <span style="color: #dc3232;"><?php echo esc_html__('غير موجودة', 'my-clinic'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($page['exists']): ?>
                                    <span style="color: #46b450;">✓ <?php echo esc_html__('موجودة', 'my-clinic'); ?></span>
                                <?php else: ?>
                                    <span style="color: #dc3232;">✗ <?php echo esc_html__('غير موجودة', 'my-clinic'); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <form method="post" action="" style="margin-top: 30px;">
                <?php wp_nonce_field('pages_setup_action', 'pages_setup_nonce'); ?>
                
                <p>
                    <input type="submit" name="setup_pages" class="button button-primary button-large" 
                           value="<?php echo esc_attr__('إنشاء/تحديث جميع الصفحات', 'my-clinic'); ?>">
                </p>
            </form>
        </div>
    </div>
    <?php
}

/**
 * Get Pages Status
 */
function my_clinic_get_pages_status() {
    $required_pages = my_clinic_get_required_pages();
    $status = array();

    foreach ($required_pages as $page_slug => $page_data) {
        $page = get_page_by_path($page_slug);
        
        $status[] = array(
            'title' => $page_data['title'],
            'slug' => $page_slug,
            'exists' => $page ? true : false,
            'url' => $page ? get_permalink($page->ID) : home_url('/' . $page_slug . '/'),
        );
    }

    return $status;
}

/**
 * Get Required Pages Configuration
 */
function my_clinic_get_required_pages() {
    return array(
        'doctors' => array(
            'title' => 'الأطباء',
            'content' => '',
            'template' => '',
        ),
        'clinics' => array(
            'title' => 'العيادات',
            'content' => '',
            'template' => '',
        ),
        'about-us' => array(
            'title' => 'من نحن',
            'content' => '<p>مرحباً بكم في موقعنا الطبي المتخصص في حجز المواعيد الطبية.</p>',
            'template' => 'page-about-us.php',
        ),
        'contact' => array(
            'title' => 'تواصل معنا',
            'content' => '<p>نحن هنا لمساعدتك. تواصل معنا عبر النموذج أدناه.</p>',
            'template' => 'page-contact.php',
        ),
        'privacy-policy' => array(
            'title' => 'سياسة الخصوصية',
            'content' => '<h2>سياسة الخصوصية</h2><p>نحن ملتزمون بحماية خصوصيتك. هذه السياسة توضح كيفية جمع واستخدام معلوماتك الشخصية.</p>',
            'template' => '',
        ),
        'return-policy' => array(
            'title' => 'سياسة الاسترجاع',
            'content' => '<h2>سياسة الاسترجاع</h2><p>يمكنك إلغاء الحجز قبل 24 ساعة من موعد الحجز.</p>',
            'template' => '',
        ),
        'account-deleted' => array(
            'title' => 'حسابي المحذوف',
            'content' => '',
            'template' => 'account-deleted.php',
        ),
    );
}

/**
 * Setup Required Pages
 */
function my_clinic_setup_required_pages() {
    $required_pages = my_clinic_get_required_pages();
    $created = 0;
    $updated = 0;
    $errors = array();

    foreach ($required_pages as $page_slug => $page_data) {
        $existing_page = get_page_by_path($page_slug);
        
        $page_args = array(
            'post_title' => $page_data['title'],
            'post_content' => $page_data['content'],
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => $page_slug,
        );

        if ($existing_page) {
            // Update existing page
            $page_args['ID'] = $existing_page->ID;
            $result = wp_update_post($page_args);
            
            if (is_wp_error($result)) {
                $errors[] = sprintf(__('خطأ في تحديث صفحة "%s": %s', 'my-clinic'), $page_data['title'], $result->get_error_message());
            } else {
                $updated++;
                
                // Update page template if specified
                if (!empty($page_data['template'])) {
                    update_post_meta($existing_page->ID, '_wp_page_template', $page_data['template']);
                }
            }
        } else {
            // Create new page
            $page_id = wp_insert_post($page_args);
            
            if (is_wp_error($page_id)) {
                $errors[] = sprintf(__('خطأ في إنشاء صفحة "%s": %s', 'my-clinic'), $page_data['title'], $page_id->get_error_message());
            } else {
                $created++;
                
                // Set page template if specified
                if (!empty($page_data['template'])) {
                    update_post_meta($page_id, '_wp_page_template', $page_data['template']);
                }
            }
        }
    }

    // Setup WooCommerce pages
    $woocommerce_result = my_clinic_setup_woocommerce_pages();
    if ($woocommerce_result['created'] > 0) {
        $created += $woocommerce_result['created'];
    }
    if ($woocommerce_result['updated'] > 0) {
        $updated += $woocommerce_result['updated'];
    }
    if (!empty($woocommerce_result['errors'])) {
        $errors = array_merge($errors, $woocommerce_result['errors']);
    }

    // Setup homepage
    $homepage_result = my_clinic_setup_homepage();
    if ($homepage_result['success']) {
        if ($homepage_result['created']) {
            $created++;
        } else {
            $updated++;
        }
    } else {
        $errors[] = $homepage_result['message'];
    }

    $message = '';
    if ($created > 0 || $updated > 0) {
        $message = sprintf(__('تم إنشاء %d صفحة وتحديث %d صفحة بنجاح.', 'my-clinic'), $created, $updated);
    }
    
    if (!empty($errors)) {
        $message .= ' ' . __('الأخطاء:', 'my-clinic') . ' ' . implode(', ', $errors);
    }

    return array(
        'success' => true,
        'message' => $message ?: __('تمت العملية بنجاح.', 'my-clinic'),
        'created' => $created,
        'updated' => $updated,
        'errors' => $errors,
    );
}

/**
 * Setup WooCommerce Pages
 */
function my_clinic_setup_woocommerce_pages() {
    if (!class_exists('WooCommerce')) {
        return array(
            'created' => 0,
            'updated' => 0,
            'errors' => array(__('WooCommerce غير مثبت.', 'my-clinic')),
        );
    }

    $created = 0;
    $updated = 0;
    $errors = array();

    // My Account page
    $myaccount_page_id = wc_get_page_id('myaccount');
    if (!$myaccount_page_id || !get_post($myaccount_page_id)) {
        $page_id = wp_insert_post(array(
            'post_title' => 'حسابي',
            'post_content' => '[woocommerce_my_account]',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => 'my-account',
        ));
        
        if (!is_wp_error($page_id)) {
            update_option('woocommerce_myaccount_page_id', $page_id);
            $created++;
        } else {
            $errors[] = __('خطأ في إنشاء صفحة حسابي', 'my-clinic');
        }
    } else {
        $updated++;
    }

    // Checkout page
    $checkout_page_id = wc_get_page_id('checkout');
    if (!$checkout_page_id || !get_post($checkout_page_id)) {
        $page_id = wp_insert_post(array(
            'post_title' => 'الدفع',
            'post_content' => '[woocommerce_checkout]',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => 'checkout',
        ));
        
        if (!is_wp_error($page_id)) {
            update_option('woocommerce_checkout_page_id', $page_id);
            $created++;
        } else {
            $errors[] = __('خطأ في إنشاء صفحة الدفع', 'my-clinic');
        }
    } else {
        $updated++;
    }

    // Cart page
    $cart_page_id = wc_get_page_id('cart');
    if (!$cart_page_id || !get_post($cart_page_id)) {
        $page_id = wp_insert_post(array(
            'post_title' => 'السلة',
            'post_content' => '[woocommerce_cart]',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => 'cart',
        ));
        
        if (!is_wp_error($page_id)) {
            update_option('woocommerce_cart_page_id', $page_id);
            $created++;
        } else {
            $errors[] = __('خطأ في إنشاء صفحة السلة', 'my-clinic');
        }
    } else {
        $updated++;
    }

    // Shop page (if needed)
    $shop_page_id = wc_get_page_id('shop');
    if (!$shop_page_id || !get_post($shop_page_id)) {
        $page_id = wp_insert_post(array(
            'post_title' => 'المتجر',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => 'shop',
        ));
        
        if (!is_wp_error($page_id)) {
            update_option('woocommerce_shop_page_id', $page_id);
            $created++;
        }
    }

    return array(
        'created' => $created,
        'updated' => $updated,
        'errors' => $errors,
    );
}

/**
 * Setup Homepage
 */
function my_clinic_setup_homepage() {
    // Check if homepage exists
    $homepage = get_page_by_path('home');
    
    if (!$homepage) {
        // Create homepage
        $page_id = wp_insert_post(array(
            'post_title' => 'الرئيسية',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => 'home',
        ));
        
        if (is_wp_error($page_id)) {
            return array(
                'success' => false,
                'message' => __('خطأ في إنشاء الصفحة الرئيسية', 'my-clinic'),
                'created' => false,
            );
        }
        
        // Set as homepage
        update_option('show_on_front', 'page');
        update_option('page_on_front', $page_id);
        
        return array(
            'success' => true,
            'message' => __('تم إنشاء الصفحة الرئيسية', 'my-clinic'),
            'created' => true,
        );
    } else {
        // Update homepage settings
        update_option('show_on_front', 'page');
        update_option('page_on_front', $homepage->ID);
        
        return array(
            'success' => true,
            'message' => __('تم تحديث إعدادات الصفحة الرئيسية', 'my-clinic'),
            'created' => false,
        );
    }
}
