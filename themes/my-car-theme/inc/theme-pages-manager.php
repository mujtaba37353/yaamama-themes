<?php
/**
 * Theme Pages Manager
 * Manages dynamic content for static pages and auto-creates theme pages
 *
 * @package MyCarTheme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Pages Manager Class
 */
class My_Car_Theme_Pages_Manager {
    
    /**
     * Option name for storing page content
     */
    const OPTION_NAME = 'my_car_theme_pages_content';
    
    /**
     * Theme pages configuration
     */
    private $theme_pages = array();
    
    /**
     * Default content for pages
     */
    private $default_content = array();
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_theme_pages();
        $this->init_default_content();
        
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_my_car_create_pages', array($this, 'ajax_create_pages'));
        add_action('wp_ajax_my_car_reset_content', array($this, 'ajax_reset_content'));
    }
    
    /**
     * Initialize theme pages configuration
     */
    private function init_theme_pages() {
        $this->theme_pages = array(
            'home' => array(
                'title' => 'الرئيسية',
                'slug' => 'home',
                'template' => 'front-page.php',
                'is_front_page' => true,
            ),
            'shop' => array(
                'title' => 'أسطولنا',
                'slug' => 'shop',
                'template' => '',
                'is_shop' => true,
            ),
            'cart' => array(
                'title' => 'سلة المشتريات',
                'slug' => 'cart',
                'template' => '',
                'is_wc_page' => 'cart',
            ),
            'checkout' => array(
                'title' => 'إتمام الطلب',
                'slug' => 'checkout',
                'template' => '',
                'is_wc_page' => 'checkout',
            ),
            'my-account' => array(
                'title' => 'حسابي',
                'slug' => 'my-account',
                'template' => '',
                'is_wc_page' => 'myaccount',
            ),
            'contact-us' => array(
                'title' => 'تواصل معنا',
                'slug' => 'contact-us',
                'template' => 'page-contact-us.php',
            ),
            'about-us' => array(
                'title' => 'من نحن',
                'slug' => 'about-us',
                'template' => 'page-about-us.php',
                'has_dynamic_content' => true,
            ),
            'privacy-policy' => array(
                'title' => 'سياسة الخصوصية',
                'slug' => 'privacy-policy',
                'template' => 'page-privacy-policy.php',
                'has_dynamic_content' => true,
            ),
            'cancellation-policy' => array(
                'title' => 'سياسة الإلغاء',
                'slug' => 'cancellation-policy',
                'template' => 'page-cancellation-policy.php',
                'has_dynamic_content' => true,
            ),
            'faq' => array(
                'title' => 'الأسئلة الشائعة',
                'slug' => 'faq',
                'template' => 'page-faq.php',
                'has_dynamic_content' => true,
            ),
            'offers' => array(
                'title' => 'العروض',
                'slug' => 'offers',
                'template' => 'page-offers.php',
            ),
        );
    }
    
    /**
     * Initialize default content for dynamic pages
     */
    private function init_default_content() {
        $this->default_content = array(
            'about-us' => array(
                'hero_title' => 'من نحن',
                'hero_subtitle' => 'تعرف على شركتنا ورؤيتنا',
                'company_title' => 'عن شركتنا',
                'company_description' => 'نحن شركة رائدة في مجال تأجير السيارات في المملكة العربية السعودية، نقدم خدماتنا منذ أكثر من 10 سنوات. نسعى دائماً لتقديم أفضل تجربة لعملائنا من خلال توفير أسطول متنوع من السيارات الحديثة والفاخرة بأسعار تنافسية.

نؤمن بأن رحلتك تستحق الأفضل، لذلك نحرص على توفير سيارات بحالة ممتازة مع خدمة عملاء متميزة على مدار الساعة.',
                'vision_title' => 'رؤيتنا',
                'vision_text' => 'أن نكون الخيار الأول لتأجير السيارات في المملكة العربية السعودية، ونقدم خدمات تفوق توقعات عملائنا.',
                'mission_title' => 'رسالتنا',
                'mission_text' => 'توفير حلول تنقل مريحة وآمنة بأسعار عادلة، مع الحفاظ على أعلى معايير الجودة والخدمة.',
                'values' => array(
                    array('icon' => 'fa-shield-halved', 'title' => 'الأمان', 'description' => 'سيارات مؤمنة بالكامل'),
                    array('icon' => 'fa-handshake', 'title' => 'الثقة', 'description' => 'شفافية في التعامل'),
                    array('icon' => 'fa-award', 'title' => 'الجودة', 'description' => 'سيارات حديثة ومتميزة'),
                    array('icon' => 'fa-headset', 'title' => 'الدعم', 'description' => 'خدمة عملاء 24/7'),
                ),
                'stats' => array(
                    array('number' => '+500', 'label' => 'سيارة'),
                    array('number' => '+10,000', 'label' => 'عميل سعيد'),
                    array('number' => '+10', 'label' => 'سنوات خبرة'),
                    array('number' => '+5', 'label' => 'فروع'),
                ),
            ),
            'privacy-policy' => array(
                'hero_title' => 'سياسة الخصوصية',
                'hero_subtitle' => 'نحمي بياناتك ونحترم خصوصيتك',
                'intro' => 'نحن في MY CAR نلتزم بحماية خصوصية عملائنا. توضح هذه السياسة كيفية جمع واستخدام وحماية معلوماتك الشخصية عند استخدام خدماتنا.',
                'last_update' => 'يناير 2026',
                'sections' => array(
                    array(
                        'icon' => 'fa-database',
                        'title' => 'المعلومات التي نجمعها',
                        'content' => '<ul>
<li><strong>المعلومات الشخصية:</strong> الاسم، البريد الإلكتروني، رقم الهاتف، العنوان، ورقم الهوية عند الحجز.</li>
<li><strong>معلومات رخصة القيادة:</strong> رقم الرخصة، تاريخ الإصدار، وتاريخ الانتهاء.</li>
<li><strong>معلومات الدفع:</strong> تفاصيل البطاقة الائتمانية (مشفرة ومحمية).</li>
<li><strong>معلومات الاستخدام:</strong> سجل الحجوزات وتفضيلات السيارات.</li>
</ul>',
                    ),
                    array(
                        'icon' => 'fa-gears',
                        'title' => 'كيف نستخدم معلوماتك',
                        'content' => '<ul>
<li>معالجة حجوزات السيارات وإتمام المعاملات.</li>
<li>التواصل معك بخصوص حجوزاتك وخدماتنا.</li>
<li>إرسال العروض والتحديثات (بموافقتك).</li>
<li>تحسين خدماتنا وتجربة المستخدم.</li>
<li>الامتثال للمتطلبات القانونية والتنظيمية.</li>
</ul>',
                    ),
                    array(
                        'icon' => 'fa-lock',
                        'title' => 'حماية معلوماتك',
                        'content' => 'نستخدم تقنيات التشفير المتقدمة (SSL) لحماية بياناتك. جميع المعلومات الحساسة مخزنة في خوادم آمنة ومحمية بجدران نارية متعددة. لا نشارك معلوماتك مع أطراف ثالثة إلا عند الضرورة القانونية أو بموافقتك.',
                    ),
                    array(
                        'icon' => 'fa-cookie-bite',
                        'title' => 'ملفات تعريف الارتباط (Cookies)',
                        'content' => 'نستخدم ملفات تعريف الارتباط لتحسين تجربتك على موقعنا. يمكنك إدارة تفضيلات ملفات تعريف الارتباط من خلال إعدادات متصفحك.',
                    ),
                    array(
                        'icon' => 'fa-user-shield',
                        'title' => 'حقوقك',
                        'content' => '<ul>
<li>الوصول إلى بياناتك الشخصية المخزنة لدينا.</li>
<li>طلب تصحيح أو تحديث معلوماتك.</li>
<li>طلب حذف بياناتك (مع مراعاة المتطلبات القانونية).</li>
<li>إلغاء الاشتراك في الرسائل التسويقية.</li>
</ul>',
                    ),
                ),
            ),
            'cancellation-policy' => array(
                'hero_title' => 'سياسة الإلغاء',
                'hero_subtitle' => 'شروط وأحكام إلغاء الحجز',
                'intro' => 'نحن في MY CAR نفهم أن الظروف قد تتغير. لذلك وضعنا سياسة إلغاء واضحة وعادلة لعملائنا. يرجى قراءة الشروط التالية بعناية.',
                'last_update' => 'يناير 2026',
                'tiers' => array(
                    array('time' => 'قبل 48 ساعة أو أكثر', 'percentage' => '100%', 'label' => 'استرداد كامل', 'type' => 'full'),
                    array('time' => 'قبل 24-48 ساعة', 'percentage' => '50%', 'label' => 'استرداد جزئي', 'type' => 'partial'),
                    array('time' => 'أقل من 24 ساعة', 'percentage' => '0%', 'label' => 'لا يوجد استرداد', 'type' => 'none'),
                ),
                'sections' => array(
                    array(
                        'icon' => 'fa-ban',
                        'title' => 'شروط الإلغاء',
                        'content' => '<ul>
<li>يجب تقديم طلب الإلغاء عبر الموقع الإلكتروني أو الاتصال بخدمة العملاء.</li>
<li>يتم احتساب وقت الإلغاء من وقت تقديم الطلب، وليس من وقت تأكيده.</li>
<li>الحجوزات الخاصة بالعروض والمناسبات قد تخضع لشروط إلغاء مختلفة.</li>
<li>في حالة عدم الحضور (No-Show)، لن يتم استرداد أي مبلغ.</li>
</ul>',
                    ),
                    array(
                        'icon' => 'fa-edit',
                        'title' => 'تعديل الحجز',
                        'content' => '<ul>
<li>يمكن تعديل تاريخ أو وقت الحجز مجاناً قبل 24 ساعة من موعد الاستلام.</li>
<li>التعديلات خلال 24 ساعة قد تخضع لرسوم إضافية.</li>
<li>تغيير نوع السيارة يخضع للتوافر وقد يتطلب فرق سعر.</li>
<li>يمكن تمديد فترة الإيجار بالاتصال بخدمة العملاء (يخضع للتوافر).</li>
</ul>',
                    ),
                    array(
                        'icon' => 'fa-money-bill-wave',
                        'title' => 'طريقة الاسترداد',
                        'content' => '<ul>
<li>يتم استرداد المبالغ بنفس طريقة الدفع الأصلية.</li>
<li>استرداد البطاقات الائتمانية يستغرق 5-14 يوم عمل.</li>
<li>التحويلات البنكية تستغرق 3-7 أيام عمل.</li>
<li>ستتلقى إشعاراً بالبريد الإلكتروني عند معالجة الاسترداد.</li>
</ul>',
                    ),
                    array(
                        'icon' => 'fa-cloud-sun-rain',
                        'title' => 'حالات استثنائية',
                        'content' => 'في حالات الظروف القاهرة (الكوارث الطبيعية، الأوبئة، إلخ)، قد نقدم شروط إلغاء مرنة. يرجى التواصل معنا لمناقشة حالتك.',
                    ),
                ),
            ),
            'faq' => array(
                'hero_title' => 'الأسئلة الشائعة',
                'hero_subtitle' => 'إجابات على أكثر الأسئلة شيوعاً',
                'main_title' => 'كيف يمكننا مساعدتك؟',
                'questions' => array(
                    array(
                        'question' => 'ما هي المستندات المطلوبة لاستئجار سيارة؟',
                        'answer' => 'تحتاج إلى رخصة قيادة سارية المفعول، هوية وطنية أو جواز سفر ساري، وبطاقة ائتمانية باسمك.',
                    ),
                    array(
                        'question' => 'هل يمكنني إلغاء الحجز؟',
                        'answer' => 'نعم، يمكنك إلغاء الحجز. الإلغاء قبل 48 ساعة مجاني، وقبل 24 ساعة يتم خصم 50%، وأقل من 24 ساعة لا يوجد استرداد.',
                    ),
                    array(
                        'question' => 'هل التأمين مشمول في السعر؟',
                        'answer' => 'نعم، جميع سياراتنا مؤمنة تأميناً شاملاً. يمكنك الترقية لتأمين بدون تحمل أي مسؤولية مقابل رسوم إضافية.',
                    ),
                    array(
                        'question' => 'ما هي سياسة الوقود؟',
                        'answer' => 'نسلم السيارة بخزان ممتلئ ونتوقع إعادتها بنفس المستوى. في حالة عدم التعبئة، سيتم احتساب رسوم تعبئة.',
                    ),
                    array(
                        'question' => 'هل يمكنني استلام وتسليم السيارة من مكان مختلف؟',
                        'answer' => 'نعم، نوفر خدمة الاستلام والتسليم من أي مكان داخل المدينة مقابل رسوم إضافية بسيطة.',
                    ),
                ),
                'cta_title' => 'لم تجد إجابة لسؤالك؟',
                'cta_text' => 'لا تتردد في التواصل معنا وسنكون سعداء بمساعدتك',
                'cta_button' => 'تواصل معنا',
            ),
        );
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            'إدارة صفحات القالب',
            'إدارة الصفحات',
            'manage_options',
            'my-car-pages-manager',
            array($this, 'render_admin_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('my_car_pages_options', self::OPTION_NAME);
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'appearance_page_my-car-pages-manager') {
            return;
        }
        
        wp_enqueue_style('my-car-admin-pages', get_template_directory_uri() . '/my-car/admin/css/pages-manager.css', array(), '1.0.0');
        wp_enqueue_script('my-car-admin-pages', get_template_directory_uri() . '/my-car/admin/js/pages-manager.js', array('jquery'), '1.0.0', true);
        
        wp_localize_script('my-car-admin-pages', 'myCarPagesManager', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('my_car_pages_nonce'),
            'strings' => array(
                'confirmCreate' => 'هل أنت متأكد من إنشاء الصفحات المفقودة؟',
                'confirmReset' => 'هل أنت متأكد من إعادة المحتوى إلى الافتراضي؟ سيتم فقدان جميع التعديلات.',
                'creating' => 'جاري الإنشاء...',
                'resetting' => 'جاري الإعادة...',
                'success' => 'تم بنجاح!',
                'error' => 'حدث خطأ. يرجى المحاولة مرة أخرى.',
            ),
        ));
    }
    
    /**
     * Render admin page
     */
    public function render_admin_page() {
        $pages_status = $this->get_pages_status();
        $saved_content = get_option(self::OPTION_NAME, array());
        $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'pages';
        ?>
        <div class="wrap my-car-pages-manager">
            <h1>
                <span class="dashicons dashicons-admin-page"></span>
                إدارة صفحات القالب
            </h1>
            
            <nav class="nav-tab-wrapper">
                <a href="?page=my-car-pages-manager&tab=pages" class="nav-tab <?php echo $active_tab === 'pages' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-admin-page"></span>
                    إدارة الصفحات
                </a>
                <a href="?page=my-car-pages-manager&tab=about" class="nav-tab <?php echo $active_tab === 'about' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-groups"></span>
                    من نحن
                </a>
                <a href="?page=my-car-pages-manager&tab=privacy" class="nav-tab <?php echo $active_tab === 'privacy' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-shield"></span>
                    سياسة الخصوصية
                </a>
                <a href="?page=my-car-pages-manager&tab=cancellation" class="nav-tab <?php echo $active_tab === 'cancellation' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-dismiss"></span>
                    سياسة الإلغاء
                </a>
                <a href="?page=my-car-pages-manager&tab=faq" class="nav-tab <?php echo $active_tab === 'faq' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-editor-help"></span>
                    الأسئلة الشائعة
                </a>
            </nav>
            
            <div class="tab-content">
                <?php
                switch ($active_tab) {
                    case 'about':
                        $this->render_about_tab($saved_content);
                        break;
                    case 'privacy':
                        $this->render_privacy_tab($saved_content);
                        break;
                    case 'cancellation':
                        $this->render_cancellation_tab($saved_content);
                        break;
                    case 'faq':
                        $this->render_faq_tab($saved_content);
                        break;
                    default:
                        $this->render_pages_tab($pages_status);
                        break;
                }
                ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render pages management tab
     */
    private function render_pages_tab($pages_status) {
        $missing_count = count(array_filter($pages_status, function($p) { return !$p['exists']; }));
        ?>
        <div class="pages-tab">
            <div class="pages-header">
                <div class="pages-stats">
                    <div class="stat-box">
                        <span class="stat-number"><?php echo count($pages_status); ?></span>
                        <span class="stat-label">إجمالي الصفحات</span>
                    </div>
                    <div class="stat-box stat-success">
                        <span class="stat-number"><?php echo count($pages_status) - $missing_count; ?></span>
                        <span class="stat-label">صفحات موجودة</span>
                    </div>
                    <div class="stat-box stat-warning">
                        <span class="stat-number"><?php echo $missing_count; ?></span>
                        <span class="stat-label">صفحات مفقودة</span>
                    </div>
                </div>
                
                <?php if ($missing_count > 0): ?>
                <button type="button" id="create-missing-pages" class="button button-primary button-large">
                    <span class="dashicons dashicons-plus-alt"></span>
                    إنشاء الصفحات المفقودة (<?php echo $missing_count; ?>)
                </button>
                <?php else: ?>
                <div class="all-pages-exist">
                    <span class="dashicons dashicons-yes-alt"></span>
                    جميع الصفحات موجودة
                </div>
                <?php endif; ?>
            </div>
            
            <table class="wp-list-table widefat fixed striped pages-table">
                <thead>
                    <tr>
                        <th class="column-status">الحالة</th>
                        <th class="column-title">الصفحة</th>
                        <th class="column-slug">الرابط الثابت</th>
                        <th class="column-template">القالب</th>
                        <th class="column-actions">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pages_status as $slug => $page): ?>
                    <tr class="<?php echo $page['exists'] ? 'page-exists' : 'page-missing'; ?>">
                        <td class="column-status">
                            <?php if ($page['exists']): ?>
                                <span class="status-badge status-exists">
                                    <span class="dashicons dashicons-yes"></span>
                                    موجودة
                                </span>
                            <?php else: ?>
                                <span class="status-badge status-missing">
                                    <span class="dashicons dashicons-no"></span>
                                    غير موجودة
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="column-title">
                            <strong><?php echo esc_html($page['title']); ?></strong>
                        </td>
                        <td class="column-slug">
                            <code>/<?php echo esc_html($slug); ?>/</code>
                        </td>
                        <td class="column-template">
                            <?php if (!empty($page['template'])): ?>
                                <code><?php echo esc_html($page['template']); ?></code>
                            <?php else: ?>
                                <span class="no-template">افتراضي</span>
                            <?php endif; ?>
                        </td>
                        <td class="column-actions">
                            <?php if ($page['exists']): ?>
                                <a href="<?php echo esc_url($page['url']); ?>" target="_blank" class="button button-small">
                                    <span class="dashicons dashicons-external"></span>
                                    عرض
                                </a>
                                <a href="<?php echo esc_url($page['edit_url']); ?>" class="button button-small">
                                    <span class="dashicons dashicons-edit"></span>
                                    تحرير
                                </a>
                            <?php else: ?>
                                <button type="button" class="button button-small create-single-page" data-slug="<?php echo esc_attr($slug); ?>">
                                    <span class="dashicons dashicons-plus"></span>
                                    إنشاء
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    
    /**
     * Render About Us tab
     */
    private function render_about_tab($saved_content) {
        $content = isset($saved_content['about-us']) ? $saved_content['about-us'] : array();
        $defaults = $this->default_content['about-us'];
        ?>
        <form method="post" action="options.php" class="content-form">
            <?php settings_fields('my_car_pages_options'); ?>
            
            <div class="form-header">
                <h2>محتوى صفحة من نحن</h2>
                <div class="form-actions">
                    <button type="button" class="button reset-content" data-page="about-us">
                        <span class="dashicons dashicons-image-rotate"></span>
                        إعادة للافتراضي
                    </button>
                    <?php submit_button('حفظ التغييرات', 'primary', 'submit', false); ?>
                </div>
            </div>
            
            <div class="form-section">
                <h3><span class="dashicons dashicons-format-image"></span> قسم الهيرو</h3>
                <table class="form-table">
                    <tr>
                        <th><label>عنوان الهيرو</label></th>
                        <td>
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[about-us][hero_title]" 
                                   value="<?php echo esc_attr($content['hero_title'] ?? $defaults['hero_title']); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th><label>العنوان الفرعي</label></th>
                        <td>
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[about-us][hero_subtitle]" 
                                   value="<?php echo esc_attr($content['hero_subtitle'] ?? $defaults['hero_subtitle']); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="form-section">
                <h3><span class="dashicons dashicons-building"></span> عن الشركة</h3>
                <table class="form-table">
                    <tr>
                        <th><label>عنوان القسم</label></th>
                        <td>
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[about-us][company_title]" 
                                   value="<?php echo esc_attr($content['company_title'] ?? $defaults['company_title']); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th><label>الوصف</label></th>
                        <td>
                            <textarea name="<?php echo self::OPTION_NAME; ?>[about-us][company_description]" 
                                      rows="5" class="large-text"><?php echo esc_textarea($content['company_description'] ?? $defaults['company_description']); ?></textarea>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="form-section">
                <h3><span class="dashicons dashicons-visibility"></span> الرؤية والرسالة</h3>
                <table class="form-table">
                    <tr>
                        <th><label>عنوان الرؤية</label></th>
                        <td>
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[about-us][vision_title]" 
                                   value="<?php echo esc_attr($content['vision_title'] ?? $defaults['vision_title']); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th><label>نص الرؤية</label></th>
                        <td>
                            <textarea name="<?php echo self::OPTION_NAME; ?>[about-us][vision_text]" 
                                      rows="3" class="large-text"><?php echo esc_textarea($content['vision_text'] ?? $defaults['vision_text']); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th><label>عنوان الرسالة</label></th>
                        <td>
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[about-us][mission_title]" 
                                   value="<?php echo esc_attr($content['mission_title'] ?? $defaults['mission_title']); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th><label>نص الرسالة</label></th>
                        <td>
                            <textarea name="<?php echo self::OPTION_NAME; ?>[about-us][mission_text]" 
                                      rows="3" class="large-text"><?php echo esc_textarea($content['mission_text'] ?? $defaults['mission_text']); ?></textarea>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="form-section">
                <h3><span class="dashicons dashicons-star-filled"></span> القيم</h3>
                <div class="repeater-field" id="values-repeater">
                    <?php 
                    $values = isset($content['values']) ? $content['values'] : $defaults['values'];
                    foreach ($values as $i => $value): 
                    ?>
                    <div class="repeater-item">
                        <div class="repeater-item-header">
                            <span class="item-title">قيمة <?php echo $i + 1; ?></span>
                            <button type="button" class="remove-item button-link-delete">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </div>
                        <div class="repeater-item-content">
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[about-us][values][<?php echo $i; ?>][icon]" 
                                   value="<?php echo esc_attr($value['icon']); ?>" placeholder="أيقونة (مثال: fa-shield-halved)">
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[about-us][values][<?php echo $i; ?>][title]" 
                                   value="<?php echo esc_attr($value['title']); ?>" placeholder="العنوان">
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[about-us][values][<?php echo $i; ?>][description]" 
                                   value="<?php echo esc_attr($value['description']); ?>" placeholder="الوصف">
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-repeater-item" data-target="values-repeater" data-page="about-us" data-field="values">
                    <span class="dashicons dashicons-plus"></span>
                    إضافة قيمة
                </button>
            </div>
            
            <div class="form-section">
                <h3><span class="dashicons dashicons-chart-bar"></span> الإحصائيات</h3>
                <div class="repeater-field" id="stats-repeater">
                    <?php 
                    $stats = isset($content['stats']) ? $content['stats'] : $defaults['stats'];
                    foreach ($stats as $i => $stat): 
                    ?>
                    <div class="repeater-item">
                        <div class="repeater-item-content inline">
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[about-us][stats][<?php echo $i; ?>][number]" 
                                   value="<?php echo esc_attr($stat['number']); ?>" placeholder="الرقم" class="small-text">
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[about-us][stats][<?php echo $i; ?>][label]" 
                                   value="<?php echo esc_attr($stat['label']); ?>" placeholder="التسمية">
                            <button type="button" class="remove-item button-link-delete">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-repeater-item" data-target="stats-repeater" data-page="about-us" data-field="stats">
                    <span class="dashicons dashicons-plus"></span>
                    إضافة إحصائية
                </button>
            </div>
            
        </form>
        <?php
    }
    
    /**
     * Render Privacy Policy tab
     */
    private function render_privacy_tab($saved_content) {
        $content = isset($saved_content['privacy-policy']) ? $saved_content['privacy-policy'] : array();
        $defaults = $this->default_content['privacy-policy'];
        ?>
        <form method="post" action="options.php" class="content-form">
            <?php settings_fields('my_car_pages_options'); ?>
            
            <div class="form-header">
                <h2>محتوى صفحة سياسة الخصوصية</h2>
                <div class="form-actions">
                    <button type="button" class="button reset-content" data-page="privacy-policy">
                        <span class="dashicons dashicons-image-rotate"></span>
                        إعادة للافتراضي
                    </button>
                    <?php submit_button('حفظ التغييرات', 'primary', 'submit', false); ?>
                </div>
            </div>
            
            <div class="form-section">
                <h3><span class="dashicons dashicons-format-image"></span> الهيرو والمقدمة</h3>
                <table class="form-table">
                    <tr>
                        <th><label>عنوان الهيرو</label></th>
                        <td>
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[privacy-policy][hero_title]" 
                                   value="<?php echo esc_attr($content['hero_title'] ?? $defaults['hero_title']); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th><label>العنوان الفرعي</label></th>
                        <td>
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[privacy-policy][hero_subtitle]" 
                                   value="<?php echo esc_attr($content['hero_subtitle'] ?? $defaults['hero_subtitle']); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th><label>المقدمة</label></th>
                        <td>
                            <textarea name="<?php echo self::OPTION_NAME; ?>[privacy-policy][intro]" 
                                      rows="3" class="large-text"><?php echo esc_textarea($content['intro'] ?? $defaults['intro']); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th><label>تاريخ آخر تحديث</label></th>
                        <td>
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[privacy-policy][last_update]" 
                                   value="<?php echo esc_attr($content['last_update'] ?? $defaults['last_update']); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="form-section">
                <h3><span class="dashicons dashicons-list-view"></span> أقسام السياسة</h3>
                <div class="repeater-field" id="privacy-sections-repeater">
                    <?php 
                    $sections = isset($content['sections']) ? $content['sections'] : $defaults['sections'];
                    foreach ($sections as $i => $section): 
                    ?>
                    <div class="repeater-item section-item">
                        <div class="repeater-item-header">
                            <span class="item-title"><?php echo esc_html($section['title']); ?></span>
                            <button type="button" class="remove-item button-link-delete">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </div>
                        <div class="repeater-item-content">
                            <div class="field-row">
                                <input type="text" name="<?php echo self::OPTION_NAME; ?>[privacy-policy][sections][<?php echo $i; ?>][icon]" 
                                       value="<?php echo esc_attr($section['icon']); ?>" placeholder="أيقونة">
                                <input type="text" name="<?php echo self::OPTION_NAME; ?>[privacy-policy][sections][<?php echo $i; ?>][title]" 
                                       value="<?php echo esc_attr($section['title']); ?>" placeholder="العنوان" class="wide">
                            </div>
                            <?php 
                            wp_editor(
                                $section['content'], 
                                'privacy_section_' . $i,
                                array(
                                    'textarea_name' => self::OPTION_NAME . '[privacy-policy][sections][' . $i . '][content]',
                                    'textarea_rows' => 5,
                                    'media_buttons' => false,
                                    'teeny' => true,
                                )
                            );
                            ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-repeater-item" data-target="privacy-sections-repeater" data-page="privacy-policy" data-field="sections">
                    <span class="dashicons dashicons-plus"></span>
                    إضافة قسم
                </button>
            </div>
            
        </form>
        <?php
    }
    
    /**
     * Render Cancellation Policy tab
     */
    private function render_cancellation_tab($saved_content) {
        $content = isset($saved_content['cancellation-policy']) ? $saved_content['cancellation-policy'] : array();
        $defaults = $this->default_content['cancellation-policy'];
        ?>
        <form method="post" action="options.php" class="content-form">
            <?php settings_fields('my_car_pages_options'); ?>
            
            <div class="form-header">
                <h2>محتوى صفحة سياسة الإلغاء</h2>
                <div class="form-actions">
                    <button type="button" class="button reset-content" data-page="cancellation-policy">
                        <span class="dashicons dashicons-image-rotate"></span>
                        إعادة للافتراضي
                    </button>
                    <?php submit_button('حفظ التغييرات', 'primary', 'submit', false); ?>
                </div>
            </div>
            
            <div class="form-section">
                <h3><span class="dashicons dashicons-format-image"></span> الهيرو والمقدمة</h3>
                <table class="form-table">
                    <tr>
                        <th><label>عنوان الهيرو</label></th>
                        <td>
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[cancellation-policy][hero_title]" 
                                   value="<?php echo esc_attr($content['hero_title'] ?? $defaults['hero_title']); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th><label>العنوان الفرعي</label></th>
                        <td>
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[cancellation-policy][hero_subtitle]" 
                                   value="<?php echo esc_attr($content['hero_subtitle'] ?? $defaults['hero_subtitle']); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th><label>المقدمة</label></th>
                        <td>
                            <textarea name="<?php echo self::OPTION_NAME; ?>[cancellation-policy][intro]" 
                                      rows="3" class="large-text"><?php echo esc_textarea($content['intro'] ?? $defaults['intro']); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th><label>تاريخ آخر تحديث</label></th>
                        <td>
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[cancellation-policy][last_update]" 
                                   value="<?php echo esc_attr($content['last_update'] ?? $defaults['last_update']); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="form-section">
                <h3><span class="dashicons dashicons-clock"></span> فترات الإلغاء</h3>
                <div class="repeater-field" id="tiers-repeater">
                    <?php 
                    $tiers = isset($content['tiers']) ? $content['tiers'] : $defaults['tiers'];
                    foreach ($tiers as $i => $tier): 
                    ?>
                    <div class="repeater-item tier-item">
                        <div class="repeater-item-content inline">
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[cancellation-policy][tiers][<?php echo $i; ?>][time]" 
                                   value="<?php echo esc_attr($tier['time']); ?>" placeholder="الوقت">
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[cancellation-policy][tiers][<?php echo $i; ?>][percentage]" 
                                   value="<?php echo esc_attr($tier['percentage']); ?>" placeholder="النسبة" class="small-text">
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[cancellation-policy][tiers][<?php echo $i; ?>][label]" 
                                   value="<?php echo esc_attr($tier['label']); ?>" placeholder="التسمية">
                            <select name="<?php echo self::OPTION_NAME; ?>[cancellation-policy][tiers][<?php echo $i; ?>][type]">
                                <option value="full" <?php selected($tier['type'], 'full'); ?>>أخضر</option>
                                <option value="partial" <?php selected($tier['type'], 'partial'); ?>>أصفر</option>
                                <option value="none" <?php selected($tier['type'], 'none'); ?>>أحمر</option>
                            </select>
                            <button type="button" class="remove-item button-link-delete">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-repeater-item" data-target="tiers-repeater" data-page="cancellation-policy" data-field="tiers">
                    <span class="dashicons dashicons-plus"></span>
                    إضافة فترة
                </button>
            </div>
            
            <div class="form-section">
                <h3><span class="dashicons dashicons-list-view"></span> أقسام السياسة</h3>
                <div class="repeater-field" id="cancellation-sections-repeater">
                    <?php 
                    $sections = isset($content['sections']) ? $content['sections'] : $defaults['sections'];
                    foreach ($sections as $i => $section): 
                    ?>
                    <div class="repeater-item section-item">
                        <div class="repeater-item-header">
                            <span class="item-title"><?php echo esc_html($section['title']); ?></span>
                            <button type="button" class="remove-item button-link-delete">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </div>
                        <div class="repeater-item-content">
                            <div class="field-row">
                                <input type="text" name="<?php echo self::OPTION_NAME; ?>[cancellation-policy][sections][<?php echo $i; ?>][icon]" 
                                       value="<?php echo esc_attr($section['icon']); ?>" placeholder="أيقونة">
                                <input type="text" name="<?php echo self::OPTION_NAME; ?>[cancellation-policy][sections][<?php echo $i; ?>][title]" 
                                       value="<?php echo esc_attr($section['title']); ?>" placeholder="العنوان" class="wide">
                            </div>
                            <?php 
                            wp_editor(
                                $section['content'], 
                                'cancel_section_' . $i,
                                array(
                                    'textarea_name' => self::OPTION_NAME . '[cancellation-policy][sections][' . $i . '][content]',
                                    'textarea_rows' => 5,
                                    'media_buttons' => false,
                                    'teeny' => true,
                                )
                            );
                            ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-repeater-item" data-target="cancellation-sections-repeater" data-page="cancellation-policy" data-field="sections">
                    <span class="dashicons dashicons-plus"></span>
                    إضافة قسم
                </button>
            </div>
            
        </form>
        <?php
    }
    
    /**
     * Render FAQ tab
     */
    private function render_faq_tab($saved_content) {
        $content = isset($saved_content['faq']) ? $saved_content['faq'] : array();
        $defaults = $this->default_content['faq'];
        ?>
        <form method="post" action="options.php" class="content-form">
            <?php settings_fields('my_car_pages_options'); ?>
            
            <div class="form-header">
                <h2>محتوى صفحة الأسئلة الشائعة</h2>
                <div class="form-actions">
                    <button type="button" class="button reset-content" data-page="faq">
                        <span class="dashicons dashicons-image-rotate"></span>
                        إعادة للافتراضي
                    </button>
                    <?php submit_button('حفظ التغييرات', 'primary', 'submit', false); ?>
                </div>
            </div>
            
            <div class="form-section">
                <h3><span class="dashicons dashicons-format-image"></span> الهيرو</h3>
                <table class="form-table">
                    <tr>
                        <th><label>عنوان الهيرو</label></th>
                        <td>
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[faq][hero_title]" 
                                   value="<?php echo esc_attr($content['hero_title'] ?? $defaults['hero_title']); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th><label>العنوان الفرعي</label></th>
                        <td>
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[faq][hero_subtitle]" 
                                   value="<?php echo esc_attr($content['hero_subtitle'] ?? $defaults['hero_subtitle']); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th><label>العنوان الرئيسي</label></th>
                        <td>
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[faq][main_title]" 
                                   value="<?php echo esc_attr($content['main_title'] ?? $defaults['main_title']); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="form-section">
                <h3><span class="dashicons dashicons-editor-help"></span> الأسئلة والأجوبة</h3>
                <div class="repeater-field" id="questions-repeater">
                    <?php 
                    $questions = isset($content['questions']) ? $content['questions'] : $defaults['questions'];
                    foreach ($questions as $i => $qa): 
                    ?>
                    <div class="repeater-item qa-item">
                        <div class="repeater-item-header">
                            <span class="item-title">سؤال <?php echo $i + 1; ?></span>
                            <button type="button" class="remove-item button-link-delete">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </div>
                        <div class="repeater-item-content">
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[faq][questions][<?php echo $i; ?>][question]" 
                                   value="<?php echo esc_attr($qa['question']); ?>" placeholder="السؤال" class="widefat">
                            <textarea name="<?php echo self::OPTION_NAME; ?>[faq][questions][<?php echo $i; ?>][answer]" 
                                      rows="3" placeholder="الإجابة" class="widefat"><?php echo esc_textarea($qa['answer']); ?></textarea>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-repeater-item" data-target="questions-repeater" data-page="faq" data-field="questions">
                    <span class="dashicons dashicons-plus"></span>
                    إضافة سؤال
                </button>
            </div>
            
            <div class="form-section">
                <h3><span class="dashicons dashicons-megaphone"></span> قسم التواصل (CTA)</h3>
                <table class="form-table">
                    <tr>
                        <th><label>عنوان CTA</label></th>
                        <td>
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[faq][cta_title]" 
                                   value="<?php echo esc_attr($content['cta_title'] ?? $defaults['cta_title']); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th><label>نص CTA</label></th>
                        <td>
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[faq][cta_text]" 
                                   value="<?php echo esc_attr($content['cta_text'] ?? $defaults['cta_text']); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th><label>نص الزر</label></th>
                        <td>
                            <input type="text" name="<?php echo self::OPTION_NAME; ?>[faq][cta_button]" 
                                   value="<?php echo esc_attr($content['cta_button'] ?? $defaults['cta_button']); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                </table>
            </div>
            
        </form>
        <?php
    }
    
    /**
     * Get pages status
     */
    public function get_pages_status() {
        $status = array();
        
        foreach ($this->theme_pages as $slug => $config) {
            $page = get_page_by_path($slug);
            
            $status[$slug] = array(
                'title' => $config['title'],
                'template' => $config['template'] ?? '',
                'exists' => !is_null($page),
                'page_id' => $page ? $page->ID : null,
                'url' => $page ? get_permalink($page->ID) : '',
                'edit_url' => $page ? get_edit_post_link($page->ID) : '',
            );
        }
        
        return $status;
    }
    
    /**
     * AJAX: Create missing pages
     */
    public function ajax_create_pages() {
        check_ajax_referer('my_car_pages_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $slug = isset($_POST['slug']) ? sanitize_text_field($_POST['slug']) : '';
        $created = array();
        $errors = array();
        
        $pages_to_create = $slug ? array($slug => $this->theme_pages[$slug]) : $this->theme_pages;
        
        foreach ($pages_to_create as $page_slug => $config) {
            $existing = get_page_by_path($page_slug);
            
            if ($existing) {
                continue;
            }
            
            $page_data = array(
                'post_title' => $config['title'],
                'post_name' => $page_slug,
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_content' => '',
            );
            
            $page_id = wp_insert_post($page_data);
            
            if (is_wp_error($page_id)) {
                $errors[] = $page_slug;
                continue;
            }
            
            // Set page template if specified
            if (!empty($config['template'])) {
                update_post_meta($page_id, '_wp_page_template', $config['template']);
            }
            
            // Set as WooCommerce page if needed
            if (!empty($config['is_wc_page'])) {
                update_option('woocommerce_' . $config['is_wc_page'] . '_page_id', $page_id);
            }
            
            // Set as front page
            if (!empty($config['is_front_page'])) {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $page_id);
            }
            
            // Set as shop page
            if (!empty($config['is_shop'])) {
                update_option('woocommerce_shop_page_id', $page_id);
            }
            
            $created[] = $page_slug;
        }
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        wp_send_json_success(array(
            'created' => $created,
            'errors' => $errors,
            'message' => sprintf('تم إنشاء %d صفحة بنجاح', count($created)),
        ));
    }
    
    /**
     * AJAX: Reset content to default
     */
    public function ajax_reset_content() {
        check_ajax_referer('my_car_pages_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $page = isset($_POST['page']) ? sanitize_text_field($_POST['page']) : '';
        
        if (!isset($this->default_content[$page])) {
            wp_send_json_error('Invalid page');
        }
        
        $saved_content = get_option(self::OPTION_NAME, array());
        unset($saved_content[$page]);
        update_option(self::OPTION_NAME, $saved_content);
        
        wp_send_json_success(array(
            'message' => 'تم إعادة المحتوى إلى الافتراضي',
        ));
    }
    
    /**
     * Get page content (static method for templates)
     */
    public static function get_content($page_slug, $field = null, $default = '') {
        $saved_content = get_option(self::OPTION_NAME, array());
        
        $instance = new self();
        $defaults = $instance->default_content[$page_slug] ?? array();
        $content = $saved_content[$page_slug] ?? array();
        
        if ($field === null) {
            return array_merge($defaults, $content);
        }
        
        return $content[$field] ?? $defaults[$field] ?? $default;
    }
}

// Initialize
new My_Car_Theme_Pages_Manager();

/**
 * Helper function to get page content in templates
 */
function my_car_get_page_content($page_slug, $field = null, $default = '') {
    return My_Car_Theme_Pages_Manager::get_content($page_slug, $field, $default);
}
