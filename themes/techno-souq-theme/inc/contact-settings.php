<?php
/**
 * Contact Settings Management
 *
 * @package TechnoSouqTheme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add Contact Settings menu page
 */
function techno_souq_add_contact_settings_menu() {
    add_theme_page(
        __('إعدادات التواصل', 'techno-souq-theme'),
        __('إعدادات التواصل', 'techno-souq-theme'),
        'edit_theme_options',
        'techno-souq-contact-settings',
        'techno_souq_contact_settings_page'
    );
}
add_action('admin_menu', 'techno_souq_add_contact_settings_menu');

/**
 * Contact Settings Admin Page
 */
function techno_souq_contact_settings_page() {
    // Handle form submission
    if (isset($_POST['techno_souq_save_contact_settings']) && check_admin_referer('techno_souq_contact_settings_nonce', 'techno_souq_contact_settings_nonce')) {
        // Save professional email
        if (isset($_POST['professional_email'])) {
            update_option('techno_souq_professional_email', sanitize_email($_POST['professional_email']));
        }
        
        // Save Gmail
        if (isset($_POST['gmail_email'])) {
            update_option('techno_souq_gmail_email', sanitize_email($_POST['gmail_email']));
        }
        
        // Save SMTP settings
        if (isset($_POST['smtp_host'])) {
            update_option('techno_souq_smtp_host', sanitize_text_field($_POST['smtp_host']));
        }
        if (isset($_POST['smtp_port'])) {
            update_option('techno_souq_smtp_port', intval($_POST['smtp_port']));
        }
        if (isset($_POST['smtp_username'])) {
            update_option('techno_souq_smtp_username', sanitize_text_field($_POST['smtp_username']));
        }
        if (isset($_POST['smtp_password'])) {
            update_option('techno_souq_smtp_password', sanitize_text_field($_POST['smtp_password']));
        }
        if (isset($_POST['smtp_encryption'])) {
            update_option('techno_souq_smtp_encryption', sanitize_text_field($_POST['smtp_encryption']));
        }
        if (isset($_POST['smtp_from_email'])) {
            update_option('techno_souq_smtp_from_email', sanitize_email($_POST['smtp_from_email']));
        }
        if (isset($_POST['smtp_from_name'])) {
            update_option('techno_souq_smtp_from_name', sanitize_text_field($_POST['smtp_from_name']));
        }
        
        // Save WhatsApp number
        if (isset($_POST['whatsapp_number'])) {
            update_option('techno_souq_whatsapp_number', sanitize_text_field($_POST['whatsapp_number']));
        }
        
        // Save contact form recipient (which email to use)
        if (isset($_POST['contact_form_recipient'])) {
            update_option('techno_souq_contact_form_recipient', sanitize_text_field($_POST['contact_form_recipient']));
        }
        
        echo '<div class="notice notice-success"><p>' . __('تم حفظ الإعدادات بنجاح', 'techno-souq-theme') . '</p></div>';
    }
    
    // Handle test email
    if (isset($_POST['techno_souq_test_email']) && check_admin_referer('techno_souq_test_email_nonce', 'techno_souq_test_email_nonce')) {
        $test_email = isset($_POST['test_email_address']) ? sanitize_email($_POST['test_email_address']) : get_option('admin_email');
        
        if ($test_email) {
            $subject = __('رسالة اختبار من إعدادات التواصل', 'techno-souq-theme');
            $message = __('هذه رسالة اختبار من إعدادات التواصل في الثيم.', 'techno-souq-theme');
            
            // Configure SMTP if enabled
            techno_souq_configure_smtp();
            
            if (wp_mail($test_email, $subject, $message)) {
                echo '<div class="notice notice-success"><p>' . sprintf(__('تم إرسال رسالة الاختبار بنجاح إلى %s', 'techno-souq-theme'), esc_html($test_email)) . '</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>' . __('فشل إرسال رسالة الاختبار. يرجى التحقق من إعدادات SMTP.', 'techno-souq-theme') . '</p></div>';
            }
        }
    }
    
    // Get current values
    $professional_email = get_option('techno_souq_professional_email', '');
    $gmail_email = get_option('techno_souq_gmail_email', '');
    $smtp_host = get_option('techno_souq_smtp_host', '');
    $smtp_port = get_option('techno_souq_smtp_port', 587);
    $smtp_username = get_option('techno_souq_smtp_username', '');
    $smtp_password = get_option('techno_souq_smtp_password', '');
    $smtp_encryption = get_option('techno_souq_smtp_encryption', 'tls');
    $smtp_from_email = get_option('techno_souq_smtp_from_email', get_option('admin_email'));
    $smtp_from_name = get_option('techno_souq_smtp_from_name', get_bloginfo('name'));
    $whatsapp_number = get_option('techno_souq_whatsapp_number', '');
    $contact_form_recipient = get_option('techno_souq_contact_form_recipient', 'professional');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('إعدادات التواصل', 'techno-souq-theme'); ?></h1>
        
        <form method="post" action="">
            <?php wp_nonce_field('techno_souq_contact_settings_nonce', 'techno_souq_contact_settings_nonce'); ?>
            
            <h2><?php echo esc_html__('إيميلات الاستقبال', 'techno-souq-theme'); ?></h2>
            <table class="form-table">
                <tr>
                    <th><label for="professional_email"><?php echo esc_html__('الإيميل الاحترافي', 'techno-souq-theme'); ?></label></th>
                    <td>
                        <input type="email" id="professional_email" name="professional_email" value="<?php echo esc_attr($professional_email); ?>" class="regular-text" />
                        <p class="description"><?php echo esc_html__('الإيميل الاحترافي الذي يستقبل رسائل فورم التواصل', 'techno-souq-theme'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="gmail_email"><?php echo esc_html__('إيميل Gmail', 'techno-souq-theme'); ?></label></th>
                    <td>
                        <input type="email" id="gmail_email" name="gmail_email" value="<?php echo esc_attr($gmail_email); ?>" class="regular-text" />
                        <p class="description"><?php echo esc_html__('إيميل Gmail الذي يستقبل رسائل فورم التواصل', 'techno-souq-theme'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="contact_form_recipient"><?php echo esc_html__('إيميل استقبال فورم التواصل', 'techno-souq-theme'); ?></label></th>
                    <td>
                        <select id="contact_form_recipient" name="contact_form_recipient">
                            <option value="professional" <?php selected($contact_form_recipient, 'professional'); ?>><?php echo esc_html__('الإيميل الاحترافي', 'techno-souq-theme'); ?></option>
                            <option value="gmail" <?php selected($contact_form_recipient, 'gmail'); ?>><?php echo esc_html__('إيميل Gmail', 'techno-souq-theme'); ?></option>
                        </select>
                        <p class="description"><?php echo esc_html__('اختر الإيميل الذي سيستقبل رسائل فورم التواصل', 'techno-souq-theme'); ?></p>
                    </td>
                </tr>
            </table>
            
            <h2><?php echo esc_html__('إعدادات SMTP', 'techno-souq-theme'); ?></h2>
            <table class="form-table">
                <tr>
                    <th><label for="smtp_host"><?php echo esc_html__('SMTP Host', 'techno-souq-theme'); ?></label></th>
                    <td>
                        <input type="text" id="smtp_host" name="smtp_host" value="<?php echo esc_attr($smtp_host); ?>" class="regular-text" placeholder="smtp.gmail.com" />
                        <p class="description"><?php echo esc_html__('مثال: smtp.gmail.com أو smtp.yourdomain.com', 'techno-souq-theme'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="smtp_port"><?php echo esc_html__('SMTP Port', 'techno-souq-theme'); ?></label></th>
                    <td>
                        <input type="number" id="smtp_port" name="smtp_port" value="<?php echo esc_attr($smtp_port); ?>" class="small-text" />
                        <p class="description"><?php echo esc_html__('عادة 587 لـ TLS أو 465 لـ SSL', 'techno-souq-theme'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="smtp_encryption"><?php echo esc_html__('نوع التشفير', 'techno-souq-theme'); ?></label></th>
                    <td>
                        <select id="smtp_encryption" name="smtp_encryption">
                            <option value="none" <?php selected($smtp_encryption, 'none'); ?>><?php echo esc_html__('لا يوجد', 'techno-souq-theme'); ?></option>
                            <option value="ssl" <?php selected($smtp_encryption, 'ssl'); ?>>SSL</option>
                            <option value="tls" <?php selected($smtp_encryption, 'tls'); ?>>TLS</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="smtp_username"><?php echo esc_html__('اسم المستخدم', 'techno-souq-theme'); ?></label></th>
                    <td>
                        <input type="text" id="smtp_username" name="smtp_username" value="<?php echo esc_attr($smtp_username); ?>" class="regular-text" />
                        <p class="description"><?php echo esc_html__('عادة هو الإيميل الكامل', 'techno-souq-theme'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="smtp_password"><?php echo esc_html__('كلمة المرور', 'techno-souq-theme'); ?></label></th>
                    <td>
                        <input type="password" id="smtp_password" name="smtp_password" value="<?php echo esc_attr($smtp_password); ?>" class="regular-text" />
                        <p class="description"><?php echo esc_html__('كلمة مرور الإيميل أو App Password لـ Gmail', 'techno-souq-theme'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="smtp_from_email"><?php echo esc_html__('إيميل المرسل', 'techno-souq-theme'); ?></label></th>
                    <td>
                        <input type="email" id="smtp_from_email" name="smtp_from_email" value="<?php echo esc_attr($smtp_from_email); ?>" class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th><label for="smtp_from_name"><?php echo esc_html__('اسم المرسل', 'techno-souq-theme'); ?></label></th>
                    <td>
                        <input type="text" id="smtp_from_name" name="smtp_from_name" value="<?php echo esc_attr($smtp_from_name); ?>" class="regular-text" />
                    </td>
                </tr>
            </table>
            
            <h2><?php echo esc_html__('رقم الواتساب', 'techno-souq-theme'); ?></h2>
            <table class="form-table">
                <tr>
                    <th><label for="whatsapp_number"><?php echo esc_html__('رقم الواتساب', 'techno-souq-theme'); ?></label></th>
                    <td>
                        <input type="text" id="whatsapp_number" name="whatsapp_number" value="<?php echo esc_attr($whatsapp_number); ?>" class="regular-text" placeholder="966501234567" />
                        <p class="description"><?php echo esc_html__('أدخل الرقم بصيغة دولية (مثال: 966501234567) بدون + أو 0', 'techno-souq-theme'); ?></p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(__('حفظ الإعدادات', 'techno-souq-theme'), 'primary', 'techno_souq_save_contact_settings'); ?>
        </form>
        
        <hr>
        
        <h2><?php echo esc_html__('اختبار الإيميل', 'techno-souq-theme'); ?></h2>
        <form method="post" action="">
            <?php wp_nonce_field('techno_souq_test_email_nonce', 'techno_souq_test_email_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="test_email_address"><?php echo esc_html__('إيميل الاختبار', 'techno-souq-theme'); ?></label></th>
                    <td>
                        <input type="email" id="test_email_address" name="test_email_address" value="<?php echo esc_attr(get_option('admin_email')); ?>" class="regular-text" />
                        <p class="description"><?php echo esc_html__('الإيميل الذي سيتم إرسال رسالة الاختبار إليه', 'techno-souq-theme'); ?></p>
                    </td>
                </tr>
            </table>
            <?php submit_button(__('إرسال رسالة اختبار', 'techno-souq-theme'), 'secondary', 'techno_souq_test_email'); ?>
        </form>
    </div>
    <?php
}

/**
 * Configure SMTP settings
 */
function techno_souq_configure_smtp() {
    $smtp_host = get_option('techno_souq_smtp_host');
    $smtp_port = get_option('techno_souq_smtp_port', 587);
    $smtp_username = get_option('techno_souq_smtp_username');
    $smtp_password = get_option('techno_souq_smtp_password');
    $smtp_encryption = get_option('techno_souq_smtp_encryption', 'tls');
    $smtp_from_email = get_option('techno_souq_smtp_from_email', get_option('admin_email'));
    $smtp_from_name = get_option('techno_souq_smtp_from_name', get_bloginfo('name'));
    
    if (empty($smtp_host) || empty($smtp_username) || empty($smtp_password)) {
        return; // SMTP not configured
    }
    
    // Configure PHPMailer
    add_action('phpmailer_init', function($phpmailer) use ($smtp_host, $smtp_port, $smtp_username, $smtp_password, $smtp_encryption, $smtp_from_email, $smtp_from_name) {
        $phpmailer->isSMTP();
        $phpmailer->Host = $smtp_host;
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = $smtp_port;
        $phpmailer->Username = $smtp_username;
        $phpmailer->Password = $smtp_password;
        
        if ($smtp_encryption === 'ssl') {
            $phpmailer->SMTPSecure = 'ssl';
        } elseif ($smtp_encryption === 'tls') {
            $phpmailer->SMTPSecure = 'tls';
        }
        
        $phpmailer->From = $smtp_from_email;
        $phpmailer->FromName = $smtp_from_name;
    });
    
    // Set from email and name
    add_filter('wp_mail_from', function() use ($smtp_from_email) {
        return $smtp_from_email;
    });
    
    add_filter('wp_mail_from_name', function() use ($smtp_from_name) {
        return $smtp_from_name;
    });
}

/**
 * Get contact form recipient email
 */
function techno_souq_get_contact_form_email() {
    $recipient = get_option('techno_souq_contact_form_recipient', 'professional');
    
    if ($recipient === 'gmail') {
        $email = get_option('techno_souq_gmail_email', '');
    } else {
        $email = get_option('techno_souq_professional_email', '');
    }
    
    // Fallback to admin email if no email is set
    if (empty($email)) {
        $email = get_option('admin_email');
    }
    
    return $email;
}

/**
 * Get WhatsApp number
 */
function techno_souq_get_whatsapp_number() {
    return get_option('techno_souq_whatsapp_number', '');
}
