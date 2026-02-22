<?php
/**
 * Theme Pages Settings
 * 
 * Allows admin to create all theme pages with one click
 * and shows required WooCommerce shortcodes
 *
 * @package Nafhat
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get theme pages configuration
 */
function nafhat_get_theme_pages() {
    return array(
        // Static Pages
        'static' => array(
            array(
                'title' => 'تواصل معنا',
                'slug' => 'contact-us',
                'template' => 'page-contact-us.php',
                'content' => '',
                'description' => 'صفحة التواصل مع نموذج الاتصال ومعلومات التواصل',
            ),
            array(
                'title' => 'من نحن',
                'slug' => 'about-us',
                'template' => '',
                'content' => '',
                'description' => 'صفحة تعريفية عن المتجر',
            ),
            array(
                'title' => 'سياسة الخصوصية والاستخدام',
                'slug' => 'privacy-policy',
                'template' => 'page-privacy-policy.php',
                'content' => '',
                'description' => 'صفحة سياسة الخصوصية وشروط الاستخدام',
            ),
            array(
                'title' => 'سياسة الاسترجاع',
                'slug' => 'refund-policy',
                'template' => 'page-refund-policy.php',
                'content' => '',
                'description' => 'صفحة سياسة الاسترجاع والاستبدال',
            ),
        ),
        // WooCommerce Pages
        'woocommerce' => array(
            array(
                'title' => 'المتجر',
                'slug' => 'shop',
                'template' => '',
                'content' => '',
                'shortcode' => '',
                'wc_option' => 'woocommerce_shop_page_id',
                'description' => 'صفحة عرض المنتجات الرئيسية',
                'note' => 'يتم تعيينها تلقائياً من WooCommerce',
            ),
            array(
                'title' => 'السلة',
                'slug' => 'cart',
                'template' => '',
                'content' => '[woocommerce_cart]',
                'shortcode' => '[woocommerce_cart]',
                'wc_option' => 'woocommerce_cart_page_id',
                'description' => 'صفحة سلة التسوق',
                'note' => 'يجب إضافة الشورت كود في محتوى الصفحة',
            ),
            array(
                'title' => 'إتمام الطلب',
                'slug' => 'checkout',
                'template' => 'page-checkout.php',
                'content' => '[woocommerce_checkout]',
                'shortcode' => '[woocommerce_checkout]',
                'wc_option' => 'woocommerce_checkout_page_id',
                'description' => 'صفحة إتمام الطلب والدفع',
                'note' => 'يستخدم قالب مخصص (page-checkout.php)',
            ),
            array(
                'title' => 'حسابي',
                'slug' => 'my-account',
                'template' => 'page-my-account.php',
                'content' => '[woocommerce_my_account]',
                'shortcode' => '[woocommerce_my_account]',
                'wc_option' => 'woocommerce_myaccount_page_id',
                'description' => 'صفحة حساب العميل',
                'note' => 'يستخدم قالب مخصص (page-my-account.php)',
            ),
            array(
                'title' => 'شكراً لك',
                'slug' => 'thank-you',
                'template' => 'page-thank-you.php',
                'content' => '',
                'shortcode' => '',
                'wc_option' => '',
                'description' => 'صفحة الشكر بعد إتمام الطلب',
                'note' => 'يتم التوجيه إليها تلقائياً بعد الطلب',
            ),
        ),
    );
}

/**
 * Add admin menu
 */
function nafhat_theme_pages_menu() {
    add_theme_page(
        __('صفحات الثيم', 'nafhat'),
        __('صفحات الثيم', 'nafhat'),
        'manage_options',
        'nafhat-theme-pages',
        'nafhat_theme_pages_page'
    );
}
add_action('admin_menu', 'nafhat_theme_pages_menu');

/**
 * Enqueue admin styles
 */
function nafhat_theme_pages_scripts($hook) {
    if ($hook !== 'appearance_page_nafhat-theme-pages') {
        return;
    }
    
    wp_add_inline_style('wp-admin', '
        .nafhat-theme-pages {
            max-width: 1000px;
            margin: 20px 0;
        }
        .nafhat-theme-pages .page-header {
            background: linear-gradient(135deg, #8B7355 0%, #A08060 100%);
            color: white;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .nafhat-theme-pages .page-header h1 {
            margin: 0 0 10px;
            color: white;
        }
        .nafhat-theme-pages .page-header p {
            margin: 0;
            opacity: 0.9;
        }
        .nafhat-theme-pages .section {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .nafhat-theme-pages .section-header {
            background: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nafhat-theme-pages .section-header h2 {
            margin: 0;
            font-size: 16px;
        }
        .nafhat-theme-pages .section-content {
            padding: 0;
        }
        .nafhat-theme-pages .page-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            gap: 15px;
        }
        .nafhat-theme-pages .page-item:last-child {
            border-bottom: none;
        }
        .nafhat-theme-pages .page-status {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .nafhat-theme-pages .page-status.exists {
            background: #46b450;
        }
        .nafhat-theme-pages .page-status.missing {
            background: #dc3232;
        }
        .nafhat-theme-pages .page-info {
            flex: 1;
        }
        .nafhat-theme-pages .page-title {
            font-weight: 600;
            margin-bottom: 3px;
        }
        .nafhat-theme-pages .page-slug {
            color: #666;
            font-size: 12px;
            font-family: monospace;
            background: #f5f5f5;
            padding: 2px 6px;
            border-radius: 3px;
            display: inline-block;
        }
        .nafhat-theme-pages .page-description {
            color: #888;
            font-size: 12px;
            margin-top: 5px;
        }
        .nafhat-theme-pages .page-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .nafhat-theme-pages .shortcode-box {
            background: #f0f0f0;
            padding: 8px 12px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nafhat-theme-pages .shortcode-box code {
            background: #fff;
            padding: 4px 8px;
            border-radius: 3px;
            border: 1px solid #ddd;
        }
        .nafhat-theme-pages .copy-btn {
            background: #8B7355;
            color: white;
            border: none;
            padding: 4px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 11px;
        }
        .nafhat-theme-pages .copy-btn:hover {
            background: #6d5a43;
        }
        .nafhat-theme-pages .btn-create {
            background: #8B7355;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }
        .nafhat-theme-pages .btn-create:hover {
            background: #6d5a43;
        }
        .nafhat-theme-pages .btn-create:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        .nafhat-theme-pages .btn-view {
            background: #0073aa;
            color: white;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
        }
        .nafhat-theme-pages .btn-view:hover {
            background: #005a87;
            color: white;
        }
        .nafhat-theme-pages .btn-edit {
            background: #f0f0f0;
            color: #333;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
        }
        .nafhat-theme-pages .btn-edit:hover {
            background: #e0e0e0;
            color: #333;
        }
        .nafhat-theme-pages .create-all-section {
            background: #fff;
            border: 2px dashed #8B7355;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin-bottom: 20px;
        }
        .nafhat-theme-pages .create-all-section h3 {
            margin: 0 0 10px;
            color: #8B7355;
        }
        .nafhat-theme-pages .create-all-section p {
            margin: 0 0 20px;
            color: #666;
        }
        .nafhat-theme-pages .btn-create-all {
            background: #8B7355;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
        }
        .nafhat-theme-pages .btn-create-all:hover {
            background: #6d5a43;
        }
        .nafhat-theme-pages .note {
            background: #fff8e5;
            border-right: 4px solid #ffb900;
            padding: 10px 15px;
            margin: 10px 20px;
            font-size: 13px;
            color: #826200;
            border-radius: 0 4px 4px 0;
        }
        .nafhat-theme-pages .info-section {
            background: #f0f6fc;
            border: 1px solid #c8d7e1;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .nafhat-theme-pages .info-section h3 {
            margin: 0 0 15px;
            color: #0073aa;
        }
        .nafhat-theme-pages .info-section table {
            width: 100%;
            border-collapse: collapse;
        }
        .nafhat-theme-pages .info-section th,
        .nafhat-theme-pages .info-section td {
            padding: 10px;
            text-align: right;
            border-bottom: 1px solid #ddd;
        }
        .nafhat-theme-pages .info-section th {
            background: #e8f4fc;
            font-weight: 600;
        }
        .nafhat-theme-pages .info-section code {
            background: #fff;
            padding: 3px 8px;
            border-radius: 3px;
            border: 1px solid #ddd;
            font-size: 12px;
        }
        .nafhat-theme-pages .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .nafhat-theme-pages .error-message {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    ');
}
add_action('admin_enqueue_scripts', 'nafhat_theme_pages_scripts');

/**
 * Check if page exists by slug
 */
function nafhat_page_exists_by_slug($slug) {
    $page = get_page_by_path($slug);
    return $page ? $page : false;
}

/**
 * Create a single page
 */
function nafhat_create_theme_page($page_data) {
    // Check if page already exists
    $existing = nafhat_page_exists_by_slug($page_data['slug']);
    if ($existing) {
        return array(
            'success' => false,
            'message' => sprintf(__('الصفحة "%s" موجودة بالفعل', 'nafhat'), $page_data['title']),
            'page_id' => $existing->ID,
        );
    }
    
    // Create page
    $page_args = array(
        'post_title'   => $page_data['title'],
        'post_name'    => $page_data['slug'],
        'post_content' => $page_data['content'],
        'post_status'  => 'publish',
        'post_type'    => 'page',
    );
    
    $page_id = wp_insert_post($page_args);
    
    if (is_wp_error($page_id)) {
        return array(
            'success' => false,
            'message' => $page_id->get_error_message(),
        );
    }
    
    // Set page template if specified
    if (!empty($page_data['template'])) {
        update_post_meta($page_id, '_wp_page_template', $page_data['template']);
    }
    
    // Set WooCommerce option if specified
    if (!empty($page_data['wc_option']) && class_exists('WooCommerce')) {
        update_option($page_data['wc_option'], $page_id);
    }
    
    return array(
        'success' => true,
        'message' => sprintf(__('تم إنشاء صفحة "%s" بنجاح', 'nafhat'), $page_data['title']),
        'page_id' => $page_id,
    );
}

/**
 * Handle page creation AJAX
 */
function nafhat_ajax_create_page() {
    check_ajax_referer('nafhat_create_page', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error(__('غير مصرح لك بهذا الإجراء', 'nafhat'));
    }
    
    $slug = sanitize_text_field($_POST['slug'] ?? '');
    $type = sanitize_text_field($_POST['type'] ?? 'static');
    
    if (empty($slug)) {
        wp_send_json_error(__('الـ slug مطلوب', 'nafhat'));
    }
    
    $pages = nafhat_get_theme_pages();
    $all_pages = array_merge($pages['static'], $pages['woocommerce']);
    
    $page_data = null;
    foreach ($all_pages as $page) {
        if ($page['slug'] === $slug) {
            $page_data = $page;
            break;
        }
    }
    
    if (!$page_data) {
        wp_send_json_error(__('الصفحة غير موجودة في التكوين', 'nafhat'));
    }
    
    $result = nafhat_create_theme_page($page_data);
    
    if ($result['success']) {
        wp_send_json_success($result);
    } else {
        wp_send_json_error($result['message']);
    }
}
add_action('wp_ajax_nafhat_create_page', 'nafhat_ajax_create_page');

/**
 * Handle create all pages AJAX
 */
function nafhat_ajax_create_all_pages() {
    check_ajax_referer('nafhat_create_all_pages', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error(__('غير مصرح لك بهذا الإجراء', 'nafhat'));
    }
    
    $pages = nafhat_get_theme_pages();
    $all_pages = array_merge($pages['static'], $pages['woocommerce']);
    
    $created = 0;
    $skipped = 0;
    $errors = array();
    
    foreach ($all_pages as $page_data) {
        $result = nafhat_create_theme_page($page_data);
        
        if ($result['success']) {
            $created++;
        } else {
            if (strpos($result['message'], 'موجودة بالفعل') !== false) {
                $skipped++;
            } else {
                $errors[] = $result['message'];
            }
        }
    }
    
    wp_send_json_success(array(
        'created' => $created,
        'skipped' => $skipped,
        'errors' => $errors,
        'message' => sprintf(
            __('تم إنشاء %d صفحة، تم تخطي %d صفحة موجودة', 'nafhat'),
            $created,
            $skipped
        ),
    ));
}
add_action('wp_ajax_nafhat_create_all_pages', 'nafhat_ajax_create_all_pages');

/**
 * Settings page content
 */
function nafhat_theme_pages_page() {
    $pages = nafhat_get_theme_pages();
    
    // Count missing pages
    $missing_count = 0;
    foreach (array_merge($pages['static'], $pages['woocommerce']) as $page) {
        if (!nafhat_page_exists_by_slug($page['slug'])) {
            $missing_count++;
        }
    }
    ?>
    <div class="wrap nafhat-theme-pages">
        <div class="page-header">
            <h1><?php esc_html_e('صفحات الثيم', 'nafhat'); ?></h1>
            <p><?php esc_html_e('إدارة وإنشاء صفحات الثيم المطلوبة لعمل المتجر بشكل صحيح', 'nafhat'); ?></p>
        </div>
        
        <?php if (isset($_GET['created'])) : ?>
            <div class="success-message">
                <?php esc_html_e('تم إنشاء الصفحات بنجاح!', 'nafhat'); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($missing_count > 0) : ?>
        <!-- Create All Section -->
        <div class="create-all-section">
            <h3><?php esc_html_e('إنشاء جميع الصفحات المفقودة', 'nafhat'); ?></h3>
            <p><?php printf(esc_html__('يوجد %d صفحة مفقودة. انقر على الزر أدناه لإنشائها جميعاً بنقرة واحدة.', 'nafhat'), $missing_count); ?></p>
            <button type="button" class="btn-create-all" id="create-all-pages">
                <?php esc_html_e('إنشاء جميع الصفحات المفقودة', 'nafhat'); ?>
            </button>
        </div>
        <?php endif; ?>
        
        <!-- WooCommerce Shortcodes Info -->
        <div class="info-section">
            <h3><?php esc_html_e('الشورت كودات المطلوبة لصفحات WooCommerce', 'nafhat'); ?></h3>
            <p><?php esc_html_e('هذه الشورت كودات يجب إضافتها في محتوى صفحات WooCommerce إذا كنت تستخدم محرر المكونات:', 'nafhat'); ?></p>
            <table>
                <thead>
                    <tr>
                        <th><?php esc_html_e('الصفحة', 'nafhat'); ?></th>
                        <th><?php esc_html_e('الشورت كود', 'nafhat'); ?></th>
                        <th><?php esc_html_e('ملاحظات', 'nafhat'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php esc_html_e('السلة', 'nafhat'); ?></td>
                        <td><code>[woocommerce_cart]</code></td>
                        <td><?php esc_html_e('لعرض محتويات السلة', 'nafhat'); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('إتمام الطلب', 'nafhat'); ?></td>
                        <td><code>[woocommerce_checkout]</code></td>
                        <td><?php esc_html_e('لعرض نموذج الدفع', 'nafhat'); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('حسابي', 'nafhat'); ?></td>
                        <td><code>[woocommerce_my_account]</code></td>
                        <td><?php esc_html_e('لعرض لوحة تحكم العميل', 'nafhat'); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('تتبع الطلب', 'nafhat'); ?></td>
                        <td><code>[woocommerce_order_tracking]</code></td>
                        <td><?php esc_html_e('اختياري - لتتبع الطلبات', 'nafhat'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Static Pages Section -->
        <div class="section">
            <div class="section-header">
                <h2><?php esc_html_e('الصفحات الثابتة', 'nafhat'); ?></h2>
            </div>
            <div class="section-content">
                <?php foreach ($pages['static'] as $page) : 
                    $existing = nafhat_page_exists_by_slug($page['slug']);
                ?>
                <div class="page-item">
                    <div class="page-status <?php echo $existing ? 'exists' : 'missing'; ?>"></div>
                    <div class="page-info">
                        <div class="page-title"><?php echo esc_html($page['title']); ?></div>
                        <span class="page-slug">/<?php echo esc_html($page['slug']); ?>/</span>
                        <div class="page-description"><?php echo esc_html($page['description']); ?></div>
                    </div>
                    <div class="page-actions">
                        <?php if ($existing) : ?>
                            <a href="<?php echo esc_url(get_permalink($existing->ID)); ?>" class="btn-view" target="_blank">
                                <?php esc_html_e('عرض', 'nafhat'); ?>
                            </a>
                            <a href="<?php echo esc_url(get_edit_post_link($existing->ID)); ?>" class="btn-edit">
                                <?php esc_html_e('تحرير', 'nafhat'); ?>
                            </a>
                        <?php else : ?>
                            <button type="button" class="btn-create" data-slug="<?php echo esc_attr($page['slug']); ?>" data-type="static">
                                <?php esc_html_e('إنشاء', 'nafhat'); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- WooCommerce Pages Section -->
        <div class="section">
            <div class="section-header">
                <h2><?php esc_html_e('صفحات WooCommerce', 'nafhat'); ?></h2>
            </div>
            <div class="section-content">
                <?php if (!class_exists('WooCommerce')) : ?>
                    <div class="note">
                        <?php esc_html_e('تنبيه: WooCommerce غير مفعل. يجب تفعيله لاستخدام هذه الصفحات.', 'nafhat'); ?>
                    </div>
                <?php endif; ?>
                
                <?php foreach ($pages['woocommerce'] as $page) : 
                    $existing = nafhat_page_exists_by_slug($page['slug']);
                ?>
                <div class="page-item">
                    <div class="page-status <?php echo $existing ? 'exists' : 'missing'; ?>"></div>
                    <div class="page-info">
                        <div class="page-title"><?php echo esc_html($page['title']); ?></div>
                        <span class="page-slug">/<?php echo esc_html($page['slug']); ?>/</span>
                        <div class="page-description"><?php echo esc_html($page['description']); ?></div>
                        <?php if (!empty($page['note'])) : ?>
                            <div class="page-description" style="color: #0073aa;">
                                <strong><?php esc_html_e('ملاحظة:', 'nafhat'); ?></strong> <?php echo esc_html($page['note']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="page-actions">
                        <?php if (!empty($page['shortcode'])) : ?>
                            <div class="shortcode-box">
                                <code><?php echo esc_html($page['shortcode']); ?></code>
                                <button type="button" class="copy-btn" data-copy="<?php echo esc_attr($page['shortcode']); ?>">
                                    <?php esc_html_e('نسخ', 'nafhat'); ?>
                                </button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($existing) : ?>
                            <a href="<?php echo esc_url(get_permalink($existing->ID)); ?>" class="btn-view" target="_blank">
                                <?php esc_html_e('عرض', 'nafhat'); ?>
                            </a>
                            <a href="<?php echo esc_url(get_edit_post_link($existing->ID)); ?>" class="btn-edit">
                                <?php esc_html_e('تحرير', 'nafhat'); ?>
                            </a>
                        <?php else : ?>
                            <button type="button" class="btn-create" data-slug="<?php echo esc_attr($page['slug']); ?>" data-type="woocommerce">
                                <?php esc_html_e('إنشاء', 'nafhat'); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Template Files Info -->
        <div class="info-section">
            <h3><?php esc_html_e('ملفات القوالب المخصصة', 'nafhat'); ?></h3>
            <p><?php esc_html_e('هذا الثيم يستخدم قوالب مخصصة للصفحات التالية:', 'nafhat'); ?></p>
            <table>
                <thead>
                    <tr>
                        <th><?php esc_html_e('الصفحة', 'nafhat'); ?></th>
                        <th><?php esc_html_e('ملف القالب', 'nafhat'); ?></th>
                        <th><?php esc_html_e('الوصف', 'nafhat'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php esc_html_e('تواصل معنا', 'nafhat'); ?></td>
                        <td><code>page-contact-us.php</code></td>
                        <td><?php esc_html_e('نموذج اتصال مخصص مع معلومات التواصل', 'nafhat'); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('سياسة الخصوصية', 'nafhat'); ?></td>
                        <td><code>page-privacy-policy.php</code></td>
                        <td><?php esc_html_e('صفحة سياسة الخصوصية والاستخدام', 'nafhat'); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('سياسة الاسترجاع', 'nafhat'); ?></td>
                        <td><code>page-refund-policy.php</code></td>
                        <td><?php esc_html_e('صفحة سياسة الاسترجاع والاستبدال', 'nafhat'); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('حسابي', 'nafhat'); ?></td>
                        <td><code>page-my-account.php</code></td>
                        <td><?php esc_html_e('لوحة تحكم العميل المخصصة', 'nafhat'); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('إتمام الطلب', 'nafhat'); ?></td>
                        <td><code>page-checkout.php</code></td>
                        <td><?php esc_html_e('صفحة الدفع المخصصة', 'nafhat'); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('شكراً لك', 'nafhat'); ?></td>
                        <td><code>page-thank-you.php</code></td>
                        <td><?php esc_html_e('صفحة الشكر بعد الطلب', 'nafhat'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Create single page
        $('.btn-create').on('click', function() {
            var $btn = $(this);
            var slug = $btn.data('slug');
            var type = $btn.data('type');
            
            $btn.prop('disabled', true).text('<?php esc_html_e('جاري الإنشاء...', 'nafhat'); ?>');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'nafhat_create_page',
                    nonce: '<?php echo wp_create_nonce('nafhat_create_page'); ?>',
                    slug: slug,
                    type: type
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data);
                        $btn.prop('disabled', false).text('<?php esc_html_e('إنشاء', 'nafhat'); ?>');
                    }
                },
                error: function() {
                    alert('<?php esc_html_e('حدث خطأ أثناء الإنشاء', 'nafhat'); ?>');
                    $btn.prop('disabled', false).text('<?php esc_html_e('إنشاء', 'nafhat'); ?>');
                }
            });
        });
        
        // Create all pages
        $('#create-all-pages').on('click', function() {
            var $btn = $(this);
            
            if (!confirm('<?php esc_html_e('هل أنت متأكد من إنشاء جميع الصفحات المفقودة؟', 'nafhat'); ?>')) {
                return;
            }
            
            $btn.prop('disabled', true).text('<?php esc_html_e('جاري الإنشاء...', 'nafhat'); ?>');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'nafhat_create_all_pages',
                    nonce: '<?php echo wp_create_nonce('nafhat_create_all_pages'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.data.message);
                        location.reload();
                    } else {
                        alert(response.data);
                        $btn.prop('disabled', false).text('<?php esc_html_e('إنشاء جميع الصفحات المفقودة', 'nafhat'); ?>');
                    }
                },
                error: function() {
                    alert('<?php esc_html_e('حدث خطأ أثناء الإنشاء', 'nafhat'); ?>');
                    $btn.prop('disabled', false).text('<?php esc_html_e('إنشاء جميع الصفحات المفقودة', 'nafhat'); ?>');
                }
            });
        });
        
        // Copy shortcode
        $('.copy-btn').on('click', function() {
            var $btn = $(this);
            var text = $btn.data('copy');
            
            navigator.clipboard.writeText(text).then(function() {
                var originalText = $btn.text();
                $btn.text('<?php esc_html_e('تم النسخ!', 'nafhat'); ?>');
                setTimeout(function() {
                    $btn.text(originalText);
                }, 1500);
            });
        });
    });
    </script>
    <?php
}
