<?php
/**
 * Contact Settings Page
 * 
 * Allows admin to manage contact information and email settings
 *
 * @package Nafhat
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get default contact settings
 */
function nafhat_get_default_contact_settings() {
    return array(
        // Email Settings
        'email_type' => 'default', // default, gmail, smtp
        'contact_email' => get_option('admin_email'),
        
        // Gmail Settings
        'gmail_email' => '',
        'gmail_app_password' => '',
        
        // SMTP Settings
        'smtp_host' => '',
        'smtp_port' => '587',
        'smtp_username' => '',
        'smtp_password' => '',
        'smtp_encryption' => 'tls', // tls, ssl, none
        'smtp_from_email' => '',
        'smtp_from_name' => get_bloginfo('name'),
        
        // Contact Information
        'address' => 'الرياض، المملكة العربية السعودية',
        'phone' => '+966 50 000 0000',
        'display_email' => 'support@nafhat.com',
        
        // Social Media
        'instagram' => '',
        'facebook' => '',
        'snapchat' => '',
        'twitter' => '',
        'tiktok' => '',
        
        // WhatsApp
        'whatsapp_number' => '',
        'whatsapp_message' => 'مرحباً، أريد الاستفسار عن...',
        'whatsapp_enabled' => false,
        
        // Google Maps
        'map_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3624.006470365758!2d46.675296!3d24.713551!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2f042f4f9b7a23%3A0x9af0b5a24b!2sRiyadh!5e0!3m2!1sar!2ssa!4v1688570000000',
    );
}

/**
 * Get contact settings
 */
function nafhat_get_contact_settings() {
    $defaults = nafhat_get_default_contact_settings();
    $settings = get_option('nafhat_contact_settings', array());
    return wp_parse_args($settings, $defaults);
}

/**
 * Add admin menu
 */
function nafhat_contact_settings_menu() {
    add_theme_page(
        __('إعدادات التواصل', 'nafhat'),
        __('إعدادات التواصل', 'nafhat'),
        'manage_options',
        'nafhat-contact-settings',
        'nafhat_contact_settings_page'
    );
}
add_action('admin_menu', 'nafhat_contact_settings_menu');

/**
 * Enqueue admin scripts
 */
function nafhat_contact_settings_scripts($hook) {
    if ($hook !== 'appearance_page_nafhat-contact-settings') {
        return;
    }
    
    // Add inline styles
    wp_add_inline_style('wp-admin', '
        .nafhat-contact-settings {
            max-width: 900px;
            margin: 20px 0;
        }
        .nafhat-contact-settings .nav-tab-wrapper {
            margin-bottom: 20px;
        }
        .nafhat-contact-settings .settings-section {
            background: #fff;
            padding: 20px;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .nafhat-contact-settings .settings-section h2 {
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .nafhat-contact-settings .form-table th {
            width: 200px;
            padding: 15px 10px 15px 0;
        }
        .nafhat-contact-settings .form-table td {
            padding: 15px 10px;
        }
        .nafhat-contact-settings .form-table input[type="text"],
        .nafhat-contact-settings .form-table input[type="email"],
        .nafhat-contact-settings .form-table input[type="password"],
        .nafhat-contact-settings .form-table input[type="number"],
        .nafhat-contact-settings .form-table input[type="url"],
        .nafhat-contact-settings .form-table textarea {
            width: 100%;
            max-width: 400px;
        }
        .nafhat-contact-settings .form-table select {
            min-width: 200px;
        }
        .nafhat-contact-settings .description {
            color: #666;
            font-style: italic;
            margin-top: 5px;
        }
        .nafhat-contact-settings .email-type-section {
            display: none;
        }
        .nafhat-contact-settings .email-type-section.active {
            display: block;
        }
        .nafhat-contact-settings .notice-inline {
            padding: 10px 15px;
            margin: 10px 0;
        }
        .nafhat-contact-settings .test-email-result {
            margin-top: 10px;
            padding: 10px;
            border-radius: 4px;
        }
        .nafhat-contact-settings .test-email-result.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .nafhat-contact-settings .test-email-result.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .nafhat-contact-settings .tab-content {
            display: none;
        }
        .nafhat-contact-settings .tab-content.active {
            display: block;
        }
        .nafhat-contact-settings .whatsapp-preview {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            background: #25D366;
            color: white;
            border-radius: 50px;
            margin-top: 10px;
        }
        .nafhat-contact-settings .whatsapp-preview svg {
            width: 24px;
            height: 24px;
            fill: white;
        }
    ');
}
add_action('admin_enqueue_scripts', 'nafhat_contact_settings_scripts');

/**
 * Save settings
 */
function nafhat_save_contact_settings() {
    if (!isset($_POST['nafhat_contact_settings_nonce']) || 
        !wp_verify_nonce($_POST['nafhat_contact_settings_nonce'], 'nafhat_contact_settings_save')) {
        return;
    }
    
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $settings = array(
        // Email Settings
        'email_type' => sanitize_text_field($_POST['email_type'] ?? 'default'),
        'contact_email' => sanitize_email($_POST['contact_email'] ?? ''),
        
        // Gmail Settings
        'gmail_email' => sanitize_email($_POST['gmail_email'] ?? ''),
        'gmail_app_password' => sanitize_text_field($_POST['gmail_app_password'] ?? ''),
        
        // SMTP Settings
        'smtp_host' => sanitize_text_field($_POST['smtp_host'] ?? ''),
        'smtp_port' => sanitize_text_field($_POST['smtp_port'] ?? '587'),
        'smtp_username' => sanitize_text_field($_POST['smtp_username'] ?? ''),
        'smtp_password' => sanitize_text_field($_POST['smtp_password'] ?? ''),
        'smtp_encryption' => sanitize_text_field($_POST['smtp_encryption'] ?? 'tls'),
        'smtp_from_email' => sanitize_email($_POST['smtp_from_email'] ?? ''),
        'smtp_from_name' => sanitize_text_field($_POST['smtp_from_name'] ?? ''),
        
        // Contact Information
        'address' => sanitize_text_field($_POST['address'] ?? ''),
        'phone' => sanitize_text_field($_POST['phone'] ?? ''),
        'display_email' => sanitize_email($_POST['display_email'] ?? ''),
        
        // Social Media
        'instagram' => esc_url_raw($_POST['instagram'] ?? ''),
        'facebook' => esc_url_raw($_POST['facebook'] ?? ''),
        'snapchat' => esc_url_raw($_POST['snapchat'] ?? ''),
        'twitter' => esc_url_raw($_POST['twitter'] ?? ''),
        'tiktok' => esc_url_raw($_POST['tiktok'] ?? ''),
        
        // WhatsApp
        'whatsapp_number' => sanitize_text_field($_POST['whatsapp_number'] ?? ''),
        'whatsapp_message' => sanitize_text_field($_POST['whatsapp_message'] ?? ''),
        'whatsapp_enabled' => isset($_POST['whatsapp_enabled']) ? true : false,
        
        // Google Maps
        'map_embed_url' => esc_url_raw($_POST['map_embed_url'] ?? ''),
    );
    
    update_option('nafhat_contact_settings', $settings);
    
    // Redirect with success message
    wp_redirect(add_query_arg(array(
        'page' => 'nafhat-contact-settings',
        'settings-updated' => 'true'
    ), admin_url('themes.php')));
    exit;
}
add_action('admin_post_nafhat_save_contact_settings', 'nafhat_save_contact_settings');

/**
 * Reset settings
 */
function nafhat_reset_contact_settings() {
    if (!isset($_POST['nafhat_contact_reset_nonce']) || 
        !wp_verify_nonce($_POST['nafhat_contact_reset_nonce'], 'nafhat_contact_settings_reset')) {
        return;
    }
    
    if (!current_user_can('manage_options')) {
        return;
    }
    
    delete_option('nafhat_contact_settings');
    
    wp_redirect(add_query_arg(array(
        'page' => 'nafhat-contact-settings',
        'settings-reset' => 'true'
    ), admin_url('themes.php')));
    exit;
}
add_action('admin_post_nafhat_reset_contact_settings', 'nafhat_reset_contact_settings');

/**
 * Test email
 */
function nafhat_test_contact_email() {
    check_ajax_referer('nafhat_test_email', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error(__('غير مصرح لك بهذا الإجراء', 'nafhat'));
    }
    
    $test_email = sanitize_email($_POST['test_email'] ?? '');
    
    if (empty($test_email)) {
        wp_send_json_error(__('يرجى إدخال بريد إلكتروني للاختبار', 'nafhat'));
    }
    
    $subject = __('اختبار إعدادات البريد الإلكتروني - نفحات', 'nafhat');
    $message = __('هذه رسالة اختبار من موقع نفحات. إذا وصلتك هذه الرسالة، فإن إعدادات البريد الإلكتروني تعمل بشكل صحيح.', 'nafhat');
    
    $sent = wp_mail($test_email, $subject, $message);
    
    if ($sent) {
        wp_send_json_success(__('تم إرسال رسالة الاختبار بنجاح! تحقق من بريدك الإلكتروني.', 'nafhat'));
    } else {
        wp_send_json_error(__('فشل إرسال رسالة الاختبار. تحقق من إعدادات البريد الإلكتروني.', 'nafhat'));
    }
}
add_action('wp_ajax_nafhat_test_contact_email', 'nafhat_test_contact_email');

/**
 * Settings page content
 */
function nafhat_contact_settings_page() {
    $settings = nafhat_get_contact_settings();
    $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'email';
    ?>
    <div class="wrap nafhat-contact-settings">
        <h1><?php esc_html_e('إعدادات التواصل', 'nafhat'); ?></h1>
        
        <?php if (isset($_GET['settings-updated'])) : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e('تم حفظ الإعدادات بنجاح!', 'nafhat'); ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['settings-reset'])) : ?>
            <div class="notice notice-info is-dismissible">
                <p><?php esc_html_e('تم إعادة الإعدادات إلى القيم الافتراضية.', 'nafhat'); ?></p>
            </div>
        <?php endif; ?>
        
        <nav class="nav-tab-wrapper">
            <a href="?page=nafhat-contact-settings&tab=email" class="nav-tab <?php echo $active_tab === 'email' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('إعدادات البريد', 'nafhat'); ?>
            </a>
            <a href="?page=nafhat-contact-settings&tab=contact" class="nav-tab <?php echo $active_tab === 'contact' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('معلومات التواصل', 'nafhat'); ?>
            </a>
            <a href="?page=nafhat-contact-settings&tab=social" class="nav-tab <?php echo $active_tab === 'social' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('التواصل الاجتماعي', 'nafhat'); ?>
            </a>
            <a href="?page=nafhat-contact-settings&tab=whatsapp" class="nav-tab <?php echo $active_tab === 'whatsapp' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('واتساب', 'nafhat'); ?>
            </a>
        </nav>
        
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="nafhat_save_contact_settings">
            <?php wp_nonce_field('nafhat_contact_settings_save', 'nafhat_contact_settings_nonce'); ?>
            
            <!-- Email Settings Tab -->
            <div class="tab-content <?php echo $active_tab === 'email' ? 'active' : ''; ?>" id="tab-email">
                <div class="settings-section">
                    <h2><?php esc_html_e('إعدادات البريد الإلكتروني', 'nafhat'); ?></h2>
                    <p class="description"><?php esc_html_e('اختر طريقة إرسال البريد الإلكتروني لنموذج التواصل.', 'nafhat'); ?></p>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="email_type"><?php esc_html_e('نوع البريد', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <select name="email_type" id="email_type">
                                    <option value="default" <?php selected($settings['email_type'], 'default'); ?>>
                                        <?php esc_html_e('البريد الافتراضي (WordPress)', 'nafhat'); ?>
                                    </option>
                                    <option value="gmail" <?php selected($settings['email_type'], 'gmail'); ?>>
                                        <?php esc_html_e('Gmail', 'nafhat'); ?>
                                    </option>
                                    <option value="smtp" <?php selected($settings['email_type'], 'smtp'); ?>>
                                        <?php esc_html_e('SMTP (بريد رسمي)', 'nafhat'); ?>
                                    </option>
                                </select>
                                <p class="description"><?php esc_html_e('اختر "Gmail" لاستخدام حساب Gmail، أو "SMTP" لاستخدام بريد رسمي مع إعدادات SMTP.', 'nafhat'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="contact_email"><?php esc_html_e('البريد المستلم', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="email" name="contact_email" id="contact_email" value="<?php echo esc_attr($settings['contact_email']); ?>">
                                <p class="description"><?php esc_html_e('البريد الإلكتروني الذي ستصل إليه رسائل نموذج التواصل.', 'nafhat'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Gmail Settings -->
                <div class="settings-section email-type-section" id="gmail-settings">
                    <h2><?php esc_html_e('إعدادات Gmail', 'nafhat'); ?></h2>
                    <div class="notice notice-info notice-inline">
                        <p>
                            <?php esc_html_e('لاستخدام Gmail، يجب إنشاء "كلمة مرور التطبيق" من إعدادات حساب Google الخاص بك.', 'nafhat'); ?>
                            <a href="https://myaccount.google.com/apppasswords" target="_blank"><?php esc_html_e('إنشاء كلمة مرور التطبيق', 'nafhat'); ?></a>
                        </p>
                    </div>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="gmail_email"><?php esc_html_e('بريد Gmail', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="email" name="gmail_email" id="gmail_email" value="<?php echo esc_attr($settings['gmail_email']); ?>" placeholder="example@gmail.com">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="gmail_app_password"><?php esc_html_e('كلمة مرور التطبيق', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="password" name="gmail_app_password" id="gmail_app_password" value="<?php echo esc_attr($settings['gmail_app_password']); ?>">
                                <p class="description"><?php esc_html_e('كلمة مرور التطبيق من Google (16 حرف بدون مسافات).', 'nafhat'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- SMTP Settings -->
                <div class="settings-section email-type-section" id="smtp-settings">
                    <h2><?php esc_html_e('إعدادات SMTP', 'nafhat'); ?></h2>
                    <p class="description"><?php esc_html_e('أدخل إعدادات SMTP الخاصة بمزود البريد الإلكتروني الرسمي.', 'nafhat'); ?></p>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="smtp_host"><?php esc_html_e('خادم SMTP', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="smtp_host" id="smtp_host" value="<?php echo esc_attr($settings['smtp_host']); ?>" placeholder="mail.example.com">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="smtp_port"><?php esc_html_e('المنفذ', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="number" name="smtp_port" id="smtp_port" value="<?php echo esc_attr($settings['smtp_port']); ?>" placeholder="587">
                                <p class="description"><?php esc_html_e('عادةً 587 لـ TLS أو 465 لـ SSL.', 'nafhat'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="smtp_encryption"><?php esc_html_e('التشفير', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <select name="smtp_encryption" id="smtp_encryption">
                                    <option value="tls" <?php selected($settings['smtp_encryption'], 'tls'); ?>>TLS</option>
                                    <option value="ssl" <?php selected($settings['smtp_encryption'], 'ssl'); ?>>SSL</option>
                                    <option value="none" <?php selected($settings['smtp_encryption'], 'none'); ?>><?php esc_html_e('بدون تشفير', 'nafhat'); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="smtp_username"><?php esc_html_e('اسم المستخدم', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="smtp_username" id="smtp_username" value="<?php echo esc_attr($settings['smtp_username']); ?>" placeholder="user@example.com">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="smtp_password"><?php esc_html_e('كلمة المرور', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="password" name="smtp_password" id="smtp_password" value="<?php echo esc_attr($settings['smtp_password']); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="smtp_from_email"><?php esc_html_e('البريد المرسل', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="email" name="smtp_from_email" id="smtp_from_email" value="<?php echo esc_attr($settings['smtp_from_email']); ?>" placeholder="noreply@example.com">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="smtp_from_name"><?php esc_html_e('اسم المرسل', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="smtp_from_name" id="smtp_from_name" value="<?php echo esc_attr($settings['smtp_from_name']); ?>" placeholder="<?php echo esc_attr(get_bloginfo('name')); ?>">
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Test Email -->
                <div class="settings-section">
                    <h2><?php esc_html_e('اختبار البريد الإلكتروني', 'nafhat'); ?></h2>
                    <p class="description"><?php esc_html_e('أرسل رسالة اختبار للتأكد من عمل الإعدادات. احفظ الإعدادات أولاً قبل الاختبار.', 'nafhat'); ?></p>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="test_email"><?php esc_html_e('بريد الاختبار', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="email" id="test_email" value="<?php echo esc_attr($settings['contact_email']); ?>">
                                <button type="button" class="button" id="send_test_email"><?php esc_html_e('إرسال رسالة اختبار', 'nafhat'); ?></button>
                                <div id="test_email_result"></div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Contact Information Tab -->
            <div class="tab-content <?php echo $active_tab === 'contact' ? 'active' : ''; ?>" id="tab-contact">
                <div class="settings-section">
                    <h2><?php esc_html_e('معلومات التواصل', 'nafhat'); ?></h2>
                    <p class="description"><?php esc_html_e('هذه المعلومات ستظهر في صفحة التواصل والفوتر.', 'nafhat'); ?></p>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="address"><?php esc_html_e('العنوان', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="address" id="address" value="<?php echo esc_attr($settings['address']); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="phone"><?php esc_html_e('رقم الهاتف', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="phone" id="phone" value="<?php echo esc_attr($settings['phone']); ?>" placeholder="+966 50 000 0000">
                                <p class="description"><?php esc_html_e('أدخل الرقم بالصيغة الدولية.', 'nafhat'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="display_email"><?php esc_html_e('البريد الإلكتروني للعرض', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="email" name="display_email" id="display_email" value="<?php echo esc_attr($settings['display_email']); ?>">
                                <p class="description"><?php esc_html_e('البريد الإلكتروني الذي سيظهر للزوار في صفحة التواصل.', 'nafhat'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="map_embed_url"><?php esc_html_e('رابط خريطة Google', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <textarea name="map_embed_url" id="map_embed_url" rows="3"><?php echo esc_textarea($settings['map_embed_url']); ?></textarea>
                                <p class="description"><?php esc_html_e('رابط تضمين خريطة Google Maps. يمكنك الحصول عليه من Google Maps > مشاركة > تضمين خريطة.', 'nafhat'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Social Media Tab -->
            <div class="tab-content <?php echo $active_tab === 'social' ? 'active' : ''; ?>" id="tab-social">
                <div class="settings-section">
                    <h2><?php esc_html_e('روابط التواصل الاجتماعي', 'nafhat'); ?></h2>
                    <p class="description"><?php esc_html_e('أضف روابط حسابات التواصل الاجتماعي الخاصة بك.', 'nafhat'); ?></p>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="instagram"><?php esc_html_e('Instagram', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="url" name="instagram" id="instagram" value="<?php echo esc_attr($settings['instagram']); ?>" placeholder="https://instagram.com/username">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="facebook"><?php esc_html_e('Facebook', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="url" name="facebook" id="facebook" value="<?php echo esc_attr($settings['facebook']); ?>" placeholder="https://facebook.com/page">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="snapchat"><?php esc_html_e('Snapchat', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="url" name="snapchat" id="snapchat" value="<?php echo esc_attr($settings['snapchat']); ?>" placeholder="https://snapchat.com/add/username">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="twitter"><?php esc_html_e('Twitter / X', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="url" name="twitter" id="twitter" value="<?php echo esc_attr($settings['twitter']); ?>" placeholder="https://twitter.com/username">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="tiktok"><?php esc_html_e('TikTok', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="url" name="tiktok" id="tiktok" value="<?php echo esc_attr($settings['tiktok']); ?>" placeholder="https://tiktok.com/@username">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- WhatsApp Tab -->
            <div class="tab-content <?php echo $active_tab === 'whatsapp' ? 'active' : ''; ?>" id="tab-whatsapp">
                <div class="settings-section">
                    <h2><?php esc_html_e('زر واتساب العائم', 'nafhat'); ?></h2>
                    <p class="description"><?php esc_html_e('أضف زر واتساب عائم يظهر في جميع صفحات الموقع.', 'nafhat'); ?></p>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="whatsapp_enabled"><?php esc_html_e('تفعيل الزر', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <label>
                                    <input type="checkbox" name="whatsapp_enabled" id="whatsapp_enabled" value="1" <?php checked($settings['whatsapp_enabled'], true); ?>>
                                    <?php esc_html_e('عرض زر واتساب العائم', 'nafhat'); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="whatsapp_number"><?php esc_html_e('رقم الواتساب', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="whatsapp_number" id="whatsapp_number" value="<?php echo esc_attr($settings['whatsapp_number']); ?>" placeholder="966500000000">
                                <p class="description"><?php esc_html_e('أدخل الرقم بالصيغة الدولية بدون علامة + أو مسافات. مثال: 966500000000', 'nafhat'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="whatsapp_message"><?php esc_html_e('الرسالة الافتراضية', 'nafhat'); ?></label>
                            </th>
                            <td>
                                <textarea name="whatsapp_message" id="whatsapp_message" rows="3"><?php echo esc_textarea($settings['whatsapp_message']); ?></textarea>
                                <p class="description"><?php esc_html_e('الرسالة التي ستظهر تلقائياً عند فتح المحادثة.', 'nafhat'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('معاينة', 'nafhat'); ?></th>
                            <td>
                                <div class="whatsapp-preview">
                                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    <span><?php esc_html_e('تواصل معنا', 'nafhat'); ?></span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e('حفظ الإعدادات', 'nafhat'); ?>">
            </p>
        </form>
        
        <!-- Reset Form -->
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display: inline;">
            <input type="hidden" name="action" value="nafhat_reset_contact_settings">
            <?php wp_nonce_field('nafhat_contact_settings_reset', 'nafhat_contact_reset_nonce'); ?>
            <input type="submit" name="reset" class="button" value="<?php esc_attr_e('إعادة تعيين الإعدادات', 'nafhat'); ?>" onclick="return confirm('<?php esc_attr_e('هل أنت متأكد من إعادة تعيين جميع الإعدادات؟', 'nafhat'); ?>');">
        </form>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Toggle email type sections
        function toggleEmailSections() {
            var type = $('#email_type').val();
            $('.email-type-section').removeClass('active');
            if (type === 'gmail') {
                $('#gmail-settings').addClass('active');
            } else if (type === 'smtp') {
                $('#smtp-settings').addClass('active');
            }
        }
        
        $('#email_type').on('change', toggleEmailSections);
        toggleEmailSections();
        
        // Test email
        $('#send_test_email').on('click', function() {
            var $btn = $(this);
            var $result = $('#test_email_result');
            var email = $('#test_email').val();
            
            if (!email) {
                $result.html('<div class="test-email-result error"><?php esc_html_e('يرجى إدخال بريد إلكتروني', 'nafhat'); ?></div>');
                return;
            }
            
            $btn.prop('disabled', true).text('<?php esc_html_e('جاري الإرسال...', 'nafhat'); ?>');
            $result.html('');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'nafhat_test_contact_email',
                    nonce: '<?php echo wp_create_nonce('nafhat_test_email'); ?>',
                    test_email: email
                },
                success: function(response) {
                    if (response.success) {
                        $result.html('<div class="test-email-result success">' + response.data + '</div>');
                    } else {
                        $result.html('<div class="test-email-result error">' + response.data + '</div>');
                    }
                },
                error: function() {
                    $result.html('<div class="test-email-result error"><?php esc_html_e('حدث خطأ أثناء الإرسال', 'nafhat'); ?></div>');
                },
                complete: function() {
                    $btn.prop('disabled', false).text('<?php esc_html_e('إرسال رسالة اختبار', 'nafhat'); ?>');
                }
            });
        });
    });
    </script>
    <?php
}

/**
 * Configure PHPMailer based on settings
 */
function nafhat_configure_phpmailer($phpmailer) {
    $settings = nafhat_get_contact_settings();
    
    if ($settings['email_type'] === 'gmail' && !empty($settings['gmail_email']) && !empty($settings['gmail_app_password'])) {
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.gmail.com';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 587;
        $phpmailer->SMTPSecure = 'tls';
        $phpmailer->Username = $settings['gmail_email'];
        $phpmailer->Password = $settings['gmail_app_password'];
        $phpmailer->setFrom($settings['gmail_email'], $settings['smtp_from_name'] ?: get_bloginfo('name'));
    } elseif ($settings['email_type'] === 'smtp' && !empty($settings['smtp_host'])) {
        $phpmailer->isSMTP();
        $phpmailer->Host = $settings['smtp_host'];
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = $settings['smtp_port'];
        
        if ($settings['smtp_encryption'] !== 'none') {
            $phpmailer->SMTPSecure = $settings['smtp_encryption'];
        }
        
        $phpmailer->Username = $settings['smtp_username'];
        $phpmailer->Password = $settings['smtp_password'];
        
        if (!empty($settings['smtp_from_email'])) {
            $phpmailer->setFrom($settings['smtp_from_email'], $settings['smtp_from_name'] ?: get_bloginfo('name'));
        }
    }
}
add_action('phpmailer_init', 'nafhat_configure_phpmailer');

/**
 * Add WhatsApp floating button
 */
function nafhat_whatsapp_floating_button() {
    $settings = nafhat_get_contact_settings();
    
    if (!$settings['whatsapp_enabled'] || empty($settings['whatsapp_number'])) {
        return;
    }
    
    $whatsapp_url = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $settings['whatsapp_number']);
    if (!empty($settings['whatsapp_message'])) {
        $whatsapp_url .= '?text=' . urlencode($settings['whatsapp_message']);
    }
    ?>
    <a href="<?php echo esc_url($whatsapp_url); ?>" class="nafhat-whatsapp-float" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('تواصل معنا عبر واتساب', 'nafhat'); ?>">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>
    <style>
        .nafhat-whatsapp-float {
            position: fixed;
            bottom: 20px;
            left: 20px;
            width: 60px;
            height: 60px;
            background: #25D366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
            z-index: 9999;
            transition: all 0.3s ease;
        }
        .nafhat-whatsapp-float:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.5);
        }
        .nafhat-whatsapp-float svg {
            width: 32px;
            height: 32px;
            fill: white;
        }
        @media (max-width: 767px) {
            .nafhat-whatsapp-float {
                bottom: 15px;
                left: 15px;
                width: 50px;
                height: 50px;
            }
            .nafhat-whatsapp-float svg {
                width: 26px;
                height: 26px;
            }
        }
    </style>
    <?php
}
add_action('wp_footer', 'nafhat_whatsapp_floating_button');
