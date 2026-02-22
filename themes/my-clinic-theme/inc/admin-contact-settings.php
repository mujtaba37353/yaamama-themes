<?php
/**
 * Admin Page for Contact Settings
 *
 * @package MyClinic
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register Admin Menu for Contact Settings
 */
function my_clinic_add_contact_settings_menu() {
    add_submenu_page(
        'themes.php',
        __('إعدادات تواصل معنا', 'my-clinic'),
        __('إعدادات تواصل معنا', 'my-clinic'),
        'manage_options',
        'contact-settings',
        'my_clinic_render_contact_settings_page'
    );
}
add_action('admin_menu', 'my_clinic_add_contact_settings_menu');

/**
 * Handle Email Test
 */
function my_clinic_handle_email_test() {
    if (isset($_POST['test_email']) && wp_verify_nonce($_POST['test_email_nonce'], 'test_email')) {
        $test_email = isset($_POST['test_email_address']) ? sanitize_email($_POST['test_email_address']) : '';
        
        if (empty($test_email) || !is_email($test_email)) {
            return array('success' => false, 'message' => 'يرجى إدخال بريد إلكتروني صحيح');
        }
        
        $from_email = get_option('smtp_from_email', get_option('admin_email'));
        $from_name = get_option('smtp_from_name', get_bloginfo('name'));
        
        $subject = 'رسالة اختبار من ' . get_bloginfo('name');
        $message = 'هذه رسالة اختبار من صفحة إعدادات تواصل معنا. إذا وصلتك هذه الرسالة، فالإعدادات تعمل بشكل صحيح.';
        
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $from_name . ' <' . $from_email . '>'
        );
        
        $result = wp_mail($test_email, $subject, nl2br(esc_html($message)), $headers);
        
        if ($result) {
            return array('success' => true, 'message' => 'تم إرسال رسالة الاختبار بنجاح. يرجى التحقق من صندوق الوارد (والبريد المزعج أيضاً).');
        } else {
            global $phpmailer;
            $error_message = 'فشل إرسال رسالة الاختبار.';
            if (isset($phpmailer) && is_object($phpmailer) && isset($phpmailer->ErrorInfo)) {
                $error_message .= ' الخطأ: ' . $phpmailer->ErrorInfo;
            }
            return array('success' => false, 'message' => $error_message);
        }
    }
    return null;
}

/**
 * Handle Reset Contact Settings
 */
function my_clinic_handle_reset_contact_settings() {
    if (isset($_POST['reset_contact_settings']) && wp_verify_nonce($_POST['reset_contact_settings_nonce'], 'reset_contact_settings')) {
        // Reset email settings
        delete_option('contact_receive_email');
        delete_option('contact_email_type');
        delete_option('smtp_host');
        delete_option('smtp_port');
        delete_option('smtp_encryption');
        delete_option('smtp_username');
        delete_option('smtp_password');
        delete_option('smtp_from_email');
        delete_option('smtp_from_name');
        
        // Reset footer contact settings
        delete_option('footer_contact_phone');
        delete_option('footer_contact_email');
        delete_option('footer_contact_whatsapp');
        delete_option('footer_contact_address');
        delete_option('footer_contact_map_link');
        
        // Reset footer main settings
        delete_option('footer_description');
        delete_option('footer_logo');
        
        return true;
    }
    return false;
}

/**
 * Render Contact Settings Page
 */
function my_clinic_render_contact_settings_page() {
    // Handle reset
    if (my_clinic_handle_reset_contact_settings()) {
        echo '<div class="notice notice-success is-dismissible"><p>' . __('تم استعادة الإعدادات الافتراضية بنجاح', 'my-clinic') . '</p></div>';
    }
    
    // Handle form submission
    $test_result = my_clinic_handle_email_test();
    
    if (isset($_POST['save_contact_settings']) && wp_verify_nonce($_POST['contact_settings_nonce'], 'save_contact_settings')) {
        // Email Settings
        if (isset($_POST['contact_receive_email'])) {
            update_option('contact_receive_email', sanitize_email($_POST['contact_receive_email']));
        }
        
        if (isset($_POST['contact_email_type'])) {
            update_option('contact_email_type', sanitize_text_field($_POST['contact_email_type']));
        }
        
        // SMTP Settings
        if (isset($_POST['smtp_host'])) {
            update_option('smtp_host', sanitize_text_field($_POST['smtp_host']));
        }
        if (isset($_POST['smtp_port'])) {
            update_option('smtp_port', absint($_POST['smtp_port']));
        }
        if (isset($_POST['smtp_encryption'])) {
            update_option('smtp_encryption', sanitize_text_field($_POST['smtp_encryption']));
        }
        if (isset($_POST['smtp_username'])) {
            update_option('smtp_username', sanitize_text_field($_POST['smtp_username']));
        }
        if (isset($_POST['smtp_password'])) {
            // Only update if password is provided
            if (!empty($_POST['smtp_password'])) {
                update_option('smtp_password', base64_encode($_POST['smtp_password']));
            }
        }
        if (isset($_POST['smtp_from_email'])) {
            update_option('smtp_from_email', sanitize_email($_POST['smtp_from_email']));
        }
        if (isset($_POST['smtp_from_name'])) {
            update_option('smtp_from_name', sanitize_text_field($_POST['smtp_from_name']));
        }
        
        // Footer Contact Settings
        if (isset($_POST['footer_contact_phone'])) {
            update_option('footer_contact_phone', sanitize_text_field($_POST['footer_contact_phone']));
        }
        if (isset($_POST['footer_contact_email'])) {
            update_option('footer_contact_email', sanitize_email($_POST['footer_contact_email']));
        }
        if (isset($_POST['footer_contact_whatsapp'])) {
            update_option('footer_contact_whatsapp', sanitize_text_field($_POST['footer_contact_whatsapp']));
        }
        if (isset($_POST['footer_contact_address'])) {
            update_option('footer_contact_address', sanitize_text_field($_POST['footer_contact_address']));
        }
        if (isset($_POST['footer_contact_map_link'])) {
            update_option('footer_contact_map_link', esc_url_raw($_POST['footer_contact_map_link']));
        }
        
        // Footer Main Section
        if (isset($_POST['footer_description'])) {
            update_option('footer_description', wp_kses_post($_POST['footer_description']));
        }
        if (isset($_POST['footer_logo'])) {
            update_option('footer_logo', esc_url_raw($_POST['footer_logo']));
        }
        
        echo '<div class="notice notice-success is-dismissible"><p>' . __('تم حفظ الإعدادات بنجاح', 'my-clinic') . '</p></div>';
    }
    
    // Get current values
    $contact_receive_email = get_option('contact_receive_email', get_option('admin_email'));
    $contact_email_type = get_option('contact_email_type', 'professional');
    $smtp_host = get_option('smtp_host', '');
    $smtp_port = get_option('smtp_port', 587);
    $smtp_encryption = get_option('smtp_encryption', 'tls');
    $smtp_username = get_option('smtp_username', '');
    $smtp_password = get_option('smtp_password', '');
    $smtp_from_email = get_option('smtp_from_email', get_option('admin_email'));
    $smtp_from_name = get_option('smtp_from_name', get_bloginfo('name'));
    
    $footer_contact_phone = get_option('footer_contact_phone', '+966 12 345 6789');
    $footer_contact_email = get_option('footer_contact_email', 'Customercare@myclinic.com');
    $footer_contact_address = get_option('footer_contact_address', 'الرياض , المملكة العربية السعودية');
    $footer_contact_map_link = get_option('footer_contact_map_link', 'https://maps.app.goo.gl/j9xwz9xwz9xwz9xwz9xwz9xw');
    
    $footer_description = get_option('footer_description', 'موقعنا هو منصّة متكاملة لحجز المواعيد الطبية بسهولة وسرعة. بنساعدك تلاقي أفضل الأطباء والعيادات في مختلف التخصصات، وتقارن بينهم حسب التقييمات والأسعار والموقع الجغرافي. هدفنا إننا نسهّل تجربة الرعاية الصحية ونخلّيها مريحة ومضمونة من أول خطوة للحجز لحد ما توصل للدكتور.');
    $footer_logo = get_option('footer_logo', get_template_directory_uri() . '/assets/images/footer-icon.png');
    
    wp_enqueue_media();
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <?php if ($test_result): ?>
            <div class="notice notice-<?php echo $test_result['success'] ? 'success' : 'error'; ?> is-dismissible">
                <p><?php echo esc_html($test_result['message']); ?></p>
            </div>
        <?php endif; ?>
        
        <form method="post" action="">
            <?php wp_nonce_field('save_contact_settings', 'contact_settings_nonce'); ?>
            
            <div style="margin-top: 20px;">
                <h2 class="nav-tab-wrapper">
                    <a href="#email-settings" class="nav-tab nav-tab-active">إعدادات البريد الإلكتروني</a>
                    <a href="#footer-contact" class="nav-tab">معلومات التواصل في الفوتر</a>
                    <a href="#footer-main" class="nav-tab">إعدادات الفوتر الرئيسية</a>
                </h2>
            </div>
            
            <!-- Email Settings Tab -->
            <div id="email-settings" class="tab-content" style="display: block;">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="contact_receive_email"><?php _e('البريد الإلكتروني المستلم', 'my-clinic'); ?></label>
                        </th>
                        <td>
                            <input type="email" id="contact_receive_email" name="contact_receive_email" value="<?php echo esc_attr($contact_receive_email); ?>" class="regular-text" required>
                            <p class="description">البريد الإلكتروني الذي سيتم استقبال رسائل التواصل عليه</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="contact_email_type"><?php _e('نوع البريد الإلكتروني', 'my-clinic'); ?></label>
                        </th>
                        <td>
                            <select id="contact_email_type" name="contact_email_type">
                                <option value="professional" <?php selected($contact_email_type, 'professional'); ?>>إيميل احترافي</option>
                                <option value="gmail" <?php selected($contact_email_type, 'gmail'); ?>>Gmail</option>
                            </select>
                            <p class="description">اختر نوع البريد الإلكتروني المستخدم</p>
                        </td>
                    </tr>
                </table>
                
                <h3>إعدادات SMTP</h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="smtp_host"><?php _e('SMTP Host', 'my-clinic'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="smtp_host" name="smtp_host" value="<?php echo esc_attr($smtp_host); ?>" class="regular-text" placeholder="<?php echo $contact_email_type === 'gmail' ? 'smtp.gmail.com' : 'mail.yourdomain.com'; ?>">
                            <p class="description"><?php echo $contact_email_type === 'gmail' ? 'لـ Gmail: smtp.gmail.com' : 'مثال: mail.yourdomain.com'; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="smtp_port"><?php _e('SMTP Port', 'my-clinic'); ?></label>
                        </th>
                        <td>
                            <input type="number" id="smtp_port" name="smtp_port" value="<?php echo esc_attr($smtp_port); ?>" class="small-text">
                            <p class="description"><?php echo $contact_email_type === 'gmail' ? 'لـ Gmail: 587 (TLS) أو 465 (SSL)' : 'عادة: 587 (TLS) أو 465 (SSL) أو 25'; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="smtp_encryption"><?php _e('التشفير', 'my-clinic'); ?></label>
                        </th>
                        <td>
                            <select id="smtp_encryption" name="smtp_encryption">
                                <option value="none" <?php selected($smtp_encryption, 'none'); ?>>لا يوجد</option>
                                <option value="ssl" <?php selected($smtp_encryption, 'ssl'); ?>>SSL</option>
                                <option value="tls" <?php selected($smtp_encryption, 'tls'); ?>>TLS</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="smtp_username"><?php _e('اسم المستخدم', 'my-clinic'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="smtp_username" name="smtp_username" value="<?php echo esc_attr($smtp_username); ?>" class="regular-text">
                            <p class="description"><?php echo $contact_email_type === 'gmail' ? 'البريد الإلكتروني الكامل لـ Gmail' : 'اسم المستخدم أو البريد الإلكتروني'; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="smtp_password"><?php _e('كلمة المرور', 'my-clinic'); ?></label>
                        </th>
                        <td>
                            <input type="password" id="smtp_password" name="smtp_password" value="" class="regular-text" placeholder="اتركه فارغاً للحفاظ على القيمة الحالية">
                            <p class="description"><?php echo $contact_email_type === 'gmail' ? 'كلمة مرور التطبيق (App Password) لـ Gmail' : 'كلمة مرور البريد الإلكتروني'; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="smtp_from_email"><?php _e('البريد الإلكتروني المرسل', 'my-clinic'); ?></label>
                        </th>
                        <td>
                            <input type="email" id="smtp_from_email" name="smtp_from_email" value="<?php echo esc_attr($smtp_from_email); ?>" class="regular-text">
                            <p class="description">البريد الإلكتروني الذي سيظهر كمرسل</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="smtp_from_name"><?php _e('اسم المرسل', 'my-clinic'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="smtp_from_name" name="smtp_from_name" value="<?php echo esc_attr($smtp_from_name); ?>" class="regular-text">
                        </td>
                    </tr>
                </table>
                
                <h3>اختبار البريد الإلكتروني</h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="test_email_address"><?php _e('إرسال رسالة اختبار إلى', 'my-clinic'); ?></label>
                        </th>
                        <td>
                            <form method="post" action="" style="display: inline-block; margin-bottom: 10px;">
                                <?php wp_nonce_field('test_email', 'test_email_nonce'); ?>
                                <input type="email" id="test_email_address" name="test_email_address" value="<?php echo esc_attr($contact_receive_email); ?>" class="regular-text" required style="margin-right: 10px;">
                                <button type="submit" name="test_email" class="button button-secondary">إرسال رسالة اختبار</button>
                            </form>
                            <p class="description">أرسل رسالة اختبار للتحقق من إعدادات SMTP. تأكد من حفظ الإعدادات أولاً قبل الاختبار.</p>
                            <?php if ($contact_email_type === 'gmail'): ?>
                                <p class="description" style="color: #d63638; margin-top: 10px;">
                                    <strong>ملاحظة مهمة لـ Gmail:</strong> يجب استخدام "كلمة مرور التطبيق" (App Password) وليس كلمة المرور العادية. يمكنك إنشاء واحدة من <a href="https://myaccount.google.com/apppasswords" target="_blank">إعدادات حساب Google</a>.
                                </p>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Footer Contact Tab -->
            <div id="footer-contact" class="tab-content" style="display: none;">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="footer_contact_phone"><?php _e('رقم الهاتف', 'my-clinic'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="footer_contact_phone" name="footer_contact_phone" value="<?php echo esc_attr($footer_contact_phone); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="footer_contact_email"><?php _e('البريد الإلكتروني', 'my-clinic'); ?></label>
                        </th>
                        <td>
                            <input type="email" id="footer_contact_email" name="footer_contact_email" value="<?php echo esc_attr($footer_contact_email); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="footer_contact_whatsapp"><?php _e('رقم الواتساب', 'my-clinic'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="footer_contact_whatsapp" name="footer_contact_whatsapp" value="<?php echo esc_attr(get_option('footer_contact_whatsapp', '+966 12 345 6789')); ?>" class="regular-text">
                            <p class="description">رقم الواتساب الذي سيظهر في زر الواتساب العائم في الفوتر</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="footer_contact_address"><?php _e('العنوان', 'my-clinic'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="footer_contact_address" name="footer_contact_address" value="<?php echo esc_attr($footer_contact_address); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="footer_contact_map_link"><?php _e('رابط الخريطة', 'my-clinic'); ?></label>
                        </th>
                        <td>
                            <input type="url" id="footer_contact_map_link" name="footer_contact_map_link" value="<?php echo esc_url($footer_contact_map_link); ?>" class="regular-text">
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Footer Main Tab -->
            <div id="footer-main" class="tab-content" style="display: none;">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="footer_logo"><?php _e('شعار الفوتر', 'my-clinic'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="footer_logo" name="footer_logo" value="<?php echo esc_url($footer_logo); ?>" class="regular-text">
                            <button type="button" class="button" id="upload_footer_logo">اختر صورة</button>
                            <p class="description">شعار الفوتر</p>
                            <?php if ($footer_logo): ?>
                                <p><img src="<?php echo esc_url($footer_logo); ?>" style="max-width: 200px; height: auto; margin-top: 10px;"></p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="footer_description"><?php _e('الوصف', 'my-clinic'); ?></label>
                        </th>
                        <td>
                            <textarea id="footer_description" name="footer_description" rows="5" class="large-text"><?php echo esc_textarea($footer_description); ?></textarea>
                            <p class="description">الفقرة النصية التي تظهر في الفوتر</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <p class="submit">
                <input type="submit" name="save_contact_settings" class="button button-primary" value="<?php _e('حفظ الإعدادات', 'my-clinic'); ?>">
                <button type="button" id="reset-contact-settings" class="button button-secondary" style="margin-right: 10px; background-color: #dc3232; color: white; border-color: #dc3232;">
                    <?php _e('استعادة الإعدادات الافتراضية', 'my-clinic'); ?>
                </button>
            </p>
        </form>
        
        <!-- Reset Form (hidden) -->
        <form method="post" action="" id="reset-contact-form" style="display: none;">
            <?php wp_nonce_field('reset_contact_settings', 'reset_contact_settings_nonce'); ?>
            <input type="hidden" name="reset_contact_settings" value="1">
        </form>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Tab switching
        $('.nav-tab').on('click', function(e) {
            e.preventDefault();
            var target = $(this).attr('href');
            
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            $('.tab-content').hide();
            $(target).show();
        });
        
        // Auto-fill Gmail settings
        $('#contact_email_type').on('change', function() {
            if ($(this).val() === 'gmail') {
                $('#smtp_host').val('smtp.gmail.com');
                $('#smtp_port').val('587');
                $('#smtp_encryption').val('tls');
                $('#smtp_from_email').val($('#contact_receive_email').val());
            } else {
                // Reset to defaults for professional email
                if ($('#smtp_host').val() === 'smtp.gmail.com') {
                    $('#smtp_host').val('');
                }
            }
        });
        
        // Sync receive email with from email if Gmail
        $('#contact_receive_email').on('blur', function() {
            if ($('#contact_email_type').val() === 'gmail') {
                $('#smtp_from_email').val($(this).val());
                $('#smtp_username').val($(this).val());
            }
        });
        
        // Reset contact settings
        $('#reset-contact-settings').on('click', function(e) {
            e.preventDefault();
            if (confirm('هل أنت متأكد من استعادة جميع إعدادات تواصل معنا إلى القيم الافتراضية؟ سيتم حذف جميع الإعدادات الحالية.')) {
                $('#reset-contact-form').submit();
            }
        });
        
        // Media uploader for footer logo
        $('#upload_footer_logo').on('click', function(e) {
            e.preventDefault();
            var button = $(this);
            var input = $('#footer_logo');
            
            var frame = wp.media({
                title: 'اختر شعار الفوتر',
                button: {
                    text: 'استخدام الصورة'
                },
                multiple: false
            });
            
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                input.val(attachment.url);
                if (input.closest('td').find('img').length) {
                    input.closest('td').find('img').attr('src', attachment.url);
                } else {
                    input.closest('td').append('<p><img src="' + attachment.url + '" style="max-width: 200px; height: auto; margin-top: 10px;"></p>');
                }
            });
            
            frame.open();
        });
    });
    </script>
    
    <style>
    .tab-content {
        margin-top: 20px;
    }
    .form-table th {
        width: 200px;
    }
    </style>
    <?php
}
