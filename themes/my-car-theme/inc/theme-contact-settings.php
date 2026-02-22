<?php
/**
 * Theme Contact Settings
 * Manages contact form, SMTP settings, and floating WhatsApp
 *
 * @package MyCarTheme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Contact Settings Class
 */
class My_Car_Theme_Contact_Settings {
    
    /**
     * Singleton instance
     */
    private static $instance = null;
    
    /**
     * Option name for storing contact settings
     */
    const OPTION_NAME = 'my_car_theme_contact_settings';
    
    /**
     * Default settings
     */
    private $defaults = array();
    
    /**
     * Get singleton instance
     */
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    public function __construct() {
        // Prevent multiple instances
        if (self::$instance !== null) {
            return;
        }
        
        $this->init_defaults();
        
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_my_car_test_email', array($this, 'ajax_test_email'));
        add_action('wp_ajax_my_car_send_contact_form', array($this, 'ajax_send_contact_form'));
        add_action('wp_ajax_nopriv_my_car_send_contact_form', array($this, 'ajax_send_contact_form'));
        add_action('wp_footer', array($this, 'render_whatsapp_button'));
        add_action('phpmailer_init', array($this, 'configure_smtp'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
    }
    
    /**
     * Initialize default settings
     */
    private function init_defaults() {
        $this->defaults = array(
            // Contact Info
            'company_name' => 'MY CAR',
            'address' => 'الرياض - المملكة العربية السعودية',
            'phone_1' => '059688929',
            'phone_2' => '058493948',
            'email' => 'info@super.ksa.com',
            'working_hours' => 'من 9:30 صباحاً حتى 10:30 مساءً',
            'map_link' => 'https://maps.google.com',
            
            // WhatsApp
            'whatsapp_enabled' => true,
            'whatsapp_number' => '966596889290',
            'whatsapp_message' => 'مرحباً، أريد الاستفسار عن خدماتكم',
            'whatsapp_position' => 'left',
            
            // Email Settings
            'recipient_email' => '',
            'email_subject_prefix' => '[MY CAR] ',
            
            // SMTP Settings
            'smtp_enabled' => false,
            'smtp_type' => 'gmail', // gmail or professional
            'smtp_host' => '',
            'smtp_port' => 587,
            'smtp_encryption' => 'tls', // tls, ssl, none
            'smtp_auth' => true,
            'smtp_username' => '',
            'smtp_password' => '',
            'smtp_from_email' => '',
            'smtp_from_name' => 'MY CAR',
            
            // Gmail specific
            'gmail_client_id' => '',
            'gmail_client_secret' => '',
        );
    }
    
    /**
     * Get setting value
     */
    public static function get_setting($key, $default = '') {
        $settings = get_option(self::OPTION_NAME, array());
        $instance = new self();
        
        if (isset($settings[$key])) {
            return $settings[$key];
        }
        
        return isset($instance->defaults[$key]) ? $instance->defaults[$key] : $default;
    }
    
    /**
     * Get all settings
     */
    public static function get_all_settings() {
        $settings = get_option(self::OPTION_NAME, array());
        $instance = new self();
        return array_merge($instance->defaults, $settings);
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            'إعدادات التواصل',
            'إعدادات التواصل',
            'manage_options',
            'my-car-contact-settings',
            array($this, 'render_admin_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('my_car_contact_options', self::OPTION_NAME, array($this, 'sanitize_settings'));
    }
    
    /**
     * Sanitize settings
     */
    public function sanitize_settings($input) {
        $sanitized = array();
        
        // Contact Info
        $sanitized['company_name'] = sanitize_text_field($input['company_name'] ?? '');
        $sanitized['address'] = sanitize_text_field($input['address'] ?? '');
        $sanitized['phone_1'] = sanitize_text_field($input['phone_1'] ?? '');
        $sanitized['phone_2'] = sanitize_text_field($input['phone_2'] ?? '');
        $sanitized['email'] = sanitize_email($input['email'] ?? '');
        $sanitized['working_hours'] = sanitize_text_field($input['working_hours'] ?? '');
        $sanitized['map_link'] = esc_url_raw($input['map_link'] ?? '');
        
        // WhatsApp
        $sanitized['whatsapp_enabled'] = isset($input['whatsapp_enabled']) ? true : false;
        $sanitized['whatsapp_number'] = sanitize_text_field($input['whatsapp_number'] ?? '');
        $sanitized['whatsapp_message'] = sanitize_textarea_field($input['whatsapp_message'] ?? '');
        $sanitized['whatsapp_position'] = sanitize_text_field($input['whatsapp_position'] ?? 'left');
        
        // Email Settings
        $sanitized['recipient_email'] = sanitize_email($input['recipient_email'] ?? '');
        $sanitized['email_subject_prefix'] = sanitize_text_field($input['email_subject_prefix'] ?? '');
        
        // SMTP Settings
        $sanitized['smtp_enabled'] = isset($input['smtp_enabled']) ? true : false;
        $sanitized['smtp_type'] = sanitize_text_field($input['smtp_type'] ?? 'gmail');
        $sanitized['smtp_host'] = sanitize_text_field($input['smtp_host'] ?? '');
        $sanitized['smtp_port'] = absint($input['smtp_port'] ?? 587);
        $sanitized['smtp_encryption'] = sanitize_text_field($input['smtp_encryption'] ?? 'tls');
        $sanitized['smtp_auth'] = isset($input['smtp_auth']) ? true : false;
        $sanitized['smtp_username'] = sanitize_text_field($input['smtp_username'] ?? '');
        
        // Only update password if provided
        if (!empty($input['smtp_password'])) {
            $sanitized['smtp_password'] = $input['smtp_password'];
        } else {
            $old_settings = get_option(self::OPTION_NAME, array());
            $sanitized['smtp_password'] = $old_settings['smtp_password'] ?? '';
        }
        
        $sanitized['smtp_from_email'] = sanitize_email($input['smtp_from_email'] ?? '');
        $sanitized['smtp_from_name'] = sanitize_text_field($input['smtp_from_name'] ?? '');
        
        return $sanitized;
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'appearance_page_my-car-contact-settings') {
            return;
        }
        
        wp_enqueue_style('my-car-admin-contact', get_template_directory_uri() . '/my-car/admin/css/contact-settings.css', array(), '1.0.0');
        wp_enqueue_script('my-car-admin-contact', get_template_directory_uri() . '/my-car/admin/js/contact-settings.js', array('jquery'), '1.0.0', true);
        
        wp_localize_script('my-car-admin-contact', 'myCarContactSettings', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('my_car_contact_nonce'),
            'strings' => array(
                'testing' => 'جاري إرسال البريد التجريبي...',
                'success' => 'تم إرسال البريد التجريبي بنجاح!',
                'error' => 'فشل إرسال البريد. يرجى التحقق من الإعدادات.',
                'enterEmail' => 'يرجى إدخال بريد إلكتروني للاختبار',
            ),
        ));
    }
    
    /**
     * Enqueue frontend scripts
     */
    public function enqueue_frontend_scripts() {
        // Contact form script
        if (is_page('contact-us') || is_page_template('page-contact-us.php')) {
            wp_enqueue_script('my-car-contact-form', get_template_directory_uri() . '/my-car/js/contact-form.js', array('jquery'), '1.0.0', true);
            wp_localize_script('my-car-contact-form', 'myCarContactForm', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('my_car_contact_form_nonce'),
                'strings' => array(
                    'sending' => 'جاري الإرسال...',
                    'success' => 'تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.',
                    'error' => 'حدث خطأ أثناء الإرسال. يرجى المحاولة مرة أخرى.',
                ),
            ));
        }
        
        // WhatsApp CSS
        if (self::get_setting('whatsapp_enabled')) {
            wp_enqueue_style('my-car-whatsapp', get_template_directory_uri() . '/my-car/components/y-whatsapp.css', array(), '1.0.0');
        }
    }
    
    /**
     * Render admin page
     */
    public function render_admin_page() {
        $settings = self::get_all_settings();
        $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'contact';
        ?>
        <div class="wrap my-car-contact-settings">
            <h1>
                <span class="dashicons dashicons-email-alt"></span>
                إعدادات التواصل
            </h1>
            
            <nav class="nav-tab-wrapper">
                <a href="?page=my-car-contact-settings&tab=contact" class="nav-tab <?php echo $active_tab === 'contact' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-location"></span>
                    معلومات التواصل
                </a>
                <a href="?page=my-car-contact-settings&tab=whatsapp" class="nav-tab <?php echo $active_tab === 'whatsapp' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-whatsapp"></span>
                    واتساب
                </a>
                <a href="?page=my-car-contact-settings&tab=smtp" class="nav-tab <?php echo $active_tab === 'smtp' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-email"></span>
                    إعدادات البريد (SMTP)
                </a>
            </nav>
            
            <div class="tab-content">
                <form method="post" action="options.php">
                    <?php settings_fields('my_car_contact_options'); ?>
                    
                    <?php if ($active_tab === 'contact'): ?>
                        <?php $this->render_contact_tab($settings); ?>
                    <?php elseif ($active_tab === 'whatsapp'): ?>
                        <?php $this->render_whatsapp_tab($settings); ?>
                    <?php elseif ($active_tab === 'smtp'): ?>
                        <?php $this->render_smtp_tab($settings); ?>
                    <?php endif; ?>
                    
                    <?php submit_button('حفظ الإعدادات'); ?>
                </form>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render contact info tab
     */
    private function render_contact_tab($settings) {
        ?>
        <div class="settings-section">
            <h2><span class="dashicons dashicons-building"></span> معلومات الشركة</h2>
            <table class="form-table">
                <tr>
                    <th><label for="company_name">اسم الشركة</label></th>
                    <td>
                        <input type="text" id="company_name" name="<?php echo self::OPTION_NAME; ?>[company_name]" 
                               value="<?php echo esc_attr($settings['company_name']); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th><label for="address">العنوان</label></th>
                    <td>
                        <input type="text" id="address" name="<?php echo self::OPTION_NAME; ?>[address]" 
                               value="<?php echo esc_attr($settings['address']); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th><label for="phone_1">رقم الهاتف الأول</label></th>
                    <td>
                        <input type="text" id="phone_1" name="<?php echo self::OPTION_NAME; ?>[phone_1]" 
                               value="<?php echo esc_attr($settings['phone_1']); ?>" class="regular-text" dir="ltr">
                    </td>
                </tr>
                <tr>
                    <th><label for="phone_2">رقم الهاتف الثاني</label></th>
                    <td>
                        <input type="text" id="phone_2" name="<?php echo self::OPTION_NAME; ?>[phone_2]" 
                               value="<?php echo esc_attr($settings['phone_2']); ?>" class="regular-text" dir="ltr">
                    </td>
                </tr>
                <tr>
                    <th><label for="email">البريد الإلكتروني</label></th>
                    <td>
                        <input type="email" id="email" name="<?php echo self::OPTION_NAME; ?>[email]" 
                               value="<?php echo esc_attr($settings['email']); ?>" class="regular-text" dir="ltr">
                    </td>
                </tr>
                <tr>
                    <th><label for="working_hours">ساعات العمل</label></th>
                    <td>
                        <input type="text" id="working_hours" name="<?php echo self::OPTION_NAME; ?>[working_hours]" 
                               value="<?php echo esc_attr($settings['working_hours']); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th><label for="map_link">رابط الخريطة</label></th>
                    <td>
                        <input type="url" id="map_link" name="<?php echo self::OPTION_NAME; ?>[map_link]" 
                               value="<?php echo esc_attr($settings['map_link']); ?>" class="regular-text" dir="ltr">
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="settings-section">
            <h2><span class="dashicons dashicons-email-alt"></span> إعدادات نموذج الاتصال</h2>
            <table class="form-table">
                <tr>
                    <th><label for="recipient_email">البريد المستقبل للرسائل</label></th>
                    <td>
                        <input type="email" id="recipient_email" name="<?php echo self::OPTION_NAME; ?>[recipient_email]" 
                               value="<?php echo esc_attr($settings['recipient_email']); ?>" class="regular-text" dir="ltr">
                        <p class="description">البريد الذي سيستقبل رسائل نموذج التواصل</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="email_subject_prefix">بادئة عنوان الرسالة</label></th>
                    <td>
                        <input type="text" id="email_subject_prefix" name="<?php echo self::OPTION_NAME; ?>[email_subject_prefix]" 
                               value="<?php echo esc_attr($settings['email_subject_prefix']); ?>" class="regular-text">
                        <p class="description">مثال: [MY CAR] - ستظهر قبل عنوان كل رسالة</p>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    
    /**
     * Render WhatsApp tab
     */
    private function render_whatsapp_tab($settings) {
        ?>
        <div class="settings-section">
            <h2><span class="dashicons dashicons-whatsapp"></span> زر واتساب العائم</h2>
            <table class="form-table">
                <tr>
                    <th><label for="whatsapp_enabled">تفعيل زر الواتساب</label></th>
                    <td>
                        <label class="switch">
                            <input type="checkbox" id="whatsapp_enabled" name="<?php echo self::OPTION_NAME; ?>[whatsapp_enabled]" 
                                   value="1" <?php checked($settings['whatsapp_enabled'], true); ?>>
                            <span class="slider round"></span>
                        </label>
                        <span class="switch-label">عرض زر الواتساب العائم في الموقع</span>
                    </td>
                </tr>
                <tr>
                    <th><label for="whatsapp_number">رقم الواتساب</label></th>
                    <td>
                        <input type="text" id="whatsapp_number" name="<?php echo self::OPTION_NAME; ?>[whatsapp_number]" 
                               value="<?php echo esc_attr($settings['whatsapp_number']); ?>" class="regular-text" dir="ltr"
                               placeholder="966596889290">
                        <p class="description">أدخل الرقم بالصيغة الدولية بدون + (مثال: 966596889290)</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="whatsapp_message">الرسالة الافتراضية</label></th>
                    <td>
                        <textarea id="whatsapp_message" name="<?php echo self::OPTION_NAME; ?>[whatsapp_message]" 
                                  rows="3" class="large-text"><?php echo esc_textarea($settings['whatsapp_message']); ?></textarea>
                        <p class="description">الرسالة التي ستظهر عند فتح محادثة الواتساب</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="whatsapp_position">موقع الزر</label></th>
                    <td>
                        <select id="whatsapp_position" name="<?php echo self::OPTION_NAME; ?>[whatsapp_position]">
                            <option value="left" <?php selected($settings['whatsapp_position'], 'left'); ?>>يسار</option>
                            <option value="right" <?php selected($settings['whatsapp_position'], 'right'); ?>>يمين</option>
                        </select>
                    </td>
                </tr>
            </table>
            
            <div class="whatsapp-preview">
                <h3>معاينة الزر</h3>
                <div class="preview-container">
                    <div class="whatsapp-button-preview <?php echo esc_attr($settings['whatsapp_position']); ?>">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render SMTP tab
     */
    private function render_smtp_tab($settings) {
        ?>
        <div class="settings-section">
            <h2><span class="dashicons dashicons-admin-generic"></span> إعدادات SMTP</h2>
            
            <div class="smtp-notice info">
                <span class="dashicons dashicons-info"></span>
                <p>تفعيل SMTP يضمن وصول رسائل البريد الإلكتروني بشكل أفضل ويمنع وصولها لمجلد السبام.</p>
            </div>
            
            <table class="form-table">
                <tr>
                    <th><label for="smtp_enabled">تفعيل SMTP</label></th>
                    <td>
                        <label class="switch">
                            <input type="checkbox" id="smtp_enabled" name="<?php echo self::OPTION_NAME; ?>[smtp_enabled]" 
                                   value="1" <?php checked($settings['smtp_enabled'], true); ?>>
                            <span class="slider round"></span>
                        </label>
                        <span class="switch-label">استخدام SMTP لإرسال البريد</span>
                    </td>
                </tr>
                <tr class="smtp-field">
                    <th><label for="smtp_type">نوع البريد</label></th>
                    <td>
                        <select id="smtp_type" name="<?php echo self::OPTION_NAME; ?>[smtp_type]" class="smtp-type-select">
                            <option value="gmail" <?php selected($settings['smtp_type'], 'gmail'); ?>>Gmail</option>
                            <option value="professional" <?php selected($settings['smtp_type'], 'professional'); ?>>بريد احترافي (Custom SMTP)</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Gmail Settings -->
        <div class="settings-section smtp-gmail-settings" <?php echo $settings['smtp_type'] !== 'gmail' ? 'style="display:none;"' : ''; ?>>
            <h2><span class="dashicons dashicons-email"></span> إعدادات Gmail</h2>
            
            <div class="smtp-notice warning">
                <span class="dashicons dashicons-warning"></span>
                <p>لاستخدام Gmail، يجب تفعيل "App Passwords" من إعدادات حساب Google الخاص بك. 
                <a href="https://myaccount.google.com/apppasswords" target="_blank">انقر هنا لإنشاء كلمة مرور التطبيق</a></p>
            </div>
            
            <table class="form-table">
                <tr>
                    <th><label for="gmail_username">بريد Gmail</label></th>
                    <td>
                        <input type="email" id="gmail_username" name="<?php echo self::OPTION_NAME; ?>[smtp_username]" 
                               value="<?php echo esc_attr($settings['smtp_username']); ?>" class="regular-text" dir="ltr"
                               placeholder="your-email@gmail.com">
                    </td>
                </tr>
                <tr>
                    <th><label for="gmail_password">كلمة مرور التطبيق</label></th>
                    <td>
                        <input type="password" id="gmail_password" name="<?php echo self::OPTION_NAME; ?>[smtp_password]" 
                               value="" class="regular-text" dir="ltr" placeholder="أدخل كلمة مرور التطبيق">
                        <p class="description">اتركه فارغاً إذا لم تريد تغيير كلمة المرور الحالية</p>
                        <?php if (!empty($settings['smtp_password'])): ?>
                            <span class="password-set"><span class="dashicons dashicons-yes"></span> تم تعيين كلمة المرور</span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Professional SMTP Settings -->
        <div class="settings-section smtp-professional-settings" <?php echo $settings['smtp_type'] !== 'professional' ? 'style="display:none;"' : ''; ?>>
            <h2><span class="dashicons dashicons-admin-network"></span> إعدادات SMTP المخصص</h2>
            
            <table class="form-table">
                <tr>
                    <th><label for="smtp_host">خادم SMTP (Host)</label></th>
                    <td>
                        <input type="text" id="smtp_host" name="<?php echo self::OPTION_NAME; ?>[smtp_host]" 
                               value="<?php echo esc_attr($settings['smtp_host']); ?>" class="regular-text" dir="ltr"
                               placeholder="smtp.example.com">
                    </td>
                </tr>
                <tr>
                    <th><label for="smtp_port">المنفذ (Port)</label></th>
                    <td>
                        <input type="number" id="smtp_port" name="<?php echo self::OPTION_NAME; ?>[smtp_port]" 
                               value="<?php echo esc_attr($settings['smtp_port']); ?>" class="small-text" dir="ltr">
                        <p class="description">المنافذ الشائعة: 587 (TLS), 465 (SSL), 25 (غير آمن)</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="smtp_encryption">التشفير</label></th>
                    <td>
                        <select id="smtp_encryption" name="<?php echo self::OPTION_NAME; ?>[smtp_encryption]">
                            <option value="tls" <?php selected($settings['smtp_encryption'], 'tls'); ?>>TLS</option>
                            <option value="ssl" <?php selected($settings['smtp_encryption'], 'ssl'); ?>>SSL</option>
                            <option value="none" <?php selected($settings['smtp_encryption'], 'none'); ?>>بدون تشفير</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="smtp_auth">المصادقة</label></th>
                    <td>
                        <label>
                            <input type="checkbox" id="smtp_auth" name="<?php echo self::OPTION_NAME; ?>[smtp_auth]" 
                                   value="1" <?php checked($settings['smtp_auth'], true); ?>>
                            تفعيل المصادقة (Authentication)
                        </label>
                    </td>
                </tr>
                <tr>
                    <th><label for="pro_smtp_username">اسم المستخدم</label></th>
                    <td>
                        <input type="text" id="pro_smtp_username" name="<?php echo self::OPTION_NAME; ?>[smtp_username]" 
                               value="<?php echo esc_attr($settings['smtp_username']); ?>" class="regular-text" dir="ltr">
                    </td>
                </tr>
                <tr>
                    <th><label for="pro_smtp_password">كلمة المرور</label></th>
                    <td>
                        <input type="password" id="pro_smtp_password" name="<?php echo self::OPTION_NAME; ?>[smtp_password]" 
                               value="" class="regular-text" dir="ltr" placeholder="أدخل كلمة المرور">
                        <p class="description">اتركه فارغاً إذا لم تريد تغيير كلمة المرور الحالية</p>
                        <?php if (!empty($settings['smtp_password'])): ?>
                            <span class="password-set"><span class="dashicons dashicons-yes"></span> تم تعيين كلمة المرور</span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- From Email Settings -->
        <div class="settings-section smtp-field">
            <h2><span class="dashicons dashicons-email-alt2"></span> إعدادات المرسل</h2>
            <table class="form-table">
                <tr>
                    <th><label for="smtp_from_email">البريد المرسل (From Email)</label></th>
                    <td>
                        <input type="email" id="smtp_from_email" name="<?php echo self::OPTION_NAME; ?>[smtp_from_email]" 
                               value="<?php echo esc_attr($settings['smtp_from_email']); ?>" class="regular-text" dir="ltr">
                        <p class="description">البريد الذي سيظهر كمرسل للرسائل</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="smtp_from_name">اسم المرسل (From Name)</label></th>
                    <td>
                        <input type="text" id="smtp_from_name" name="<?php echo self::OPTION_NAME; ?>[smtp_from_name]" 
                               value="<?php echo esc_attr($settings['smtp_from_name']); ?>" class="regular-text">
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Test Email -->
        <div class="settings-section">
            <h2><span class="dashicons dashicons-email"></span> اختبار البريد</h2>
            <table class="form-table">
                <tr>
                    <th><label for="test_email">بريد الاختبار</label></th>
                    <td>
                        <input type="email" id="test_email" class="regular-text" dir="ltr" 
                               placeholder="test@example.com">
                        <button type="button" id="send-test-email" class="button button-secondary">
                            <span class="dashicons dashicons-email-alt"></span>
                            إرسال بريد اختبار
                        </button>
                        <div id="test-email-result"></div>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    
    /**
     * Configure SMTP
     */
    public function configure_smtp($phpmailer) {
        $settings = self::get_all_settings();
        
        if (!$settings['smtp_enabled']) {
            return;
        }
        
        $phpmailer->isSMTP();
        $phpmailer->SMTPAuth = $settings['smtp_auth'];
        
        if ($settings['smtp_type'] === 'gmail') {
            $phpmailer->Host = 'smtp.gmail.com';
            $phpmailer->Port = 587;
            $phpmailer->SMTPSecure = 'tls';
        } else {
            $phpmailer->Host = $settings['smtp_host'];
            $phpmailer->Port = $settings['smtp_port'];
            
            if ($settings['smtp_encryption'] !== 'none') {
                $phpmailer->SMTPSecure = $settings['smtp_encryption'];
            }
        }
        
        $phpmailer->Username = $settings['smtp_username'];
        $phpmailer->Password = $settings['smtp_password'];
        
        if (!empty($settings['smtp_from_email'])) {
            $phpmailer->setFrom($settings['smtp_from_email'], $settings['smtp_from_name']);
        }
    }
    
    /**
     * AJAX: Test email
     */
    public function ajax_test_email() {
        check_ajax_referer('my_car_contact_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('غير مصرح');
        }
        
        $test_email = sanitize_email($_POST['email'] ?? '');
        
        if (empty($test_email)) {
            wp_send_json_error('يرجى إدخال بريد إلكتروني صالح');
        }
        
        $subject = 'بريد اختبار من MY CAR';
        $message = 'هذا بريد اختبار من موقع MY CAR للتأكد من عمل إعدادات SMTP بشكل صحيح.
        
إذا وصلك هذا البريد، فإن الإعدادات تعمل بشكل صحيح!

تاريخ الإرسال: ' . current_time('Y-m-d H:i:s');
        
        $headers = array('Content-Type: text/plain; charset=UTF-8');
        
        $sent = wp_mail($test_email, $subject, $message, $headers);
        
        if ($sent) {
            wp_send_json_success('تم إرسال البريد الاختباري بنجاح!');
        } else {
            global $phpmailer;
            $error = '';
            if (isset($phpmailer) && isset($phpmailer->ErrorInfo)) {
                $error = $phpmailer->ErrorInfo;
            }
            wp_send_json_error('فشل إرسال البريد. ' . $error);
        }
    }
    
    /**
     * AJAX: Send contact form
     */
    public function ajax_send_contact_form() {
        check_ajax_referer('my_car_contact_form_nonce', 'nonce');
        
        $settings = self::get_all_settings();
        
        $name = sanitize_text_field($_POST['name'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        $subject = sanitize_text_field($_POST['subject'] ?? '');
        $message = sanitize_textarea_field($_POST['message'] ?? '');
        
        // Validation
        if (empty($name) || empty($email) || empty($phone) || empty($subject)) {
            wp_send_json_error('يرجى ملء جميع الحقول المطلوبة');
        }
        
        if (!is_email($email)) {
            wp_send_json_error('البريد الإلكتروني غير صالح');
        }
        
        $recipient = !empty($settings['recipient_email']) ? $settings['recipient_email'] : $settings['email'];
        
        if (empty($recipient)) {
            wp_send_json_error('لم يتم تكوين البريد المستقبل');
        }
        
        $email_subject = $settings['email_subject_prefix'] . $subject;
        
        $email_body = "رسالة جديدة من نموذج التواصل\n\n";
        $email_body .= "الاسم: {$name}\n";
        $email_body .= "البريد الإلكتروني: {$email}\n";
        $email_body .= "رقم الهاتف: {$phone}\n";
        $email_body .= "الموضوع: {$subject}\n\n";
        $email_body .= "الرسالة:\n{$message}\n\n";
        $email_body .= "---\n";
        $email_body .= "تاريخ الإرسال: " . current_time('Y-m-d H:i:s');
        
        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'Reply-To: ' . $name . ' <' . $email . '>',
        );
        
        $sent = wp_mail($recipient, $email_subject, $email_body, $headers);
        
        if ($sent) {
            wp_send_json_success('تم إرسال رسالتك بنجاح!');
        } else {
            wp_send_json_error('حدث خطأ أثناء الإرسال. يرجى المحاولة مرة أخرى.');
        }
    }
    
    /**
     * Render WhatsApp floating button
     */
    public function render_whatsapp_button() {
        $settings = self::get_all_settings();
        
        if (empty($settings['whatsapp_enabled']) || empty($settings['whatsapp_number'])) {
            return;
        }
        
        $whatsapp_url = 'https://wa.me/' . $settings['whatsapp_number'];
        if (!empty($settings['whatsapp_message'])) {
            $whatsapp_url .= '?text=' . urlencode($settings['whatsapp_message']);
        }
        
        $position_class = $settings['whatsapp_position'] === 'right' ? 'y-whatsapp-right' : 'y-whatsapp-left';
        ?>
        <a href="<?php echo esc_url($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer" 
           class="y-c-whatsapp-float <?php echo $position_class; ?>" title="تواصل معنا عبر واتساب">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
        </a>
        <?php
    }
}

// Initialize singleton
My_Car_Theme_Contact_Settings::get_instance();

/**
 * Helper function to get contact setting
 */
function my_car_get_contact_setting($key, $default = '') {
    return My_Car_Theme_Contact_Settings::get_setting($key, $default);
}

/**
 * Helper function to get all contact settings
 */
function my_car_get_contact_settings() {
    return My_Car_Theme_Contact_Settings::get_all_settings();
}
