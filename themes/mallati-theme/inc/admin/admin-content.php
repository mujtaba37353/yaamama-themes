<?php
/**
 * Mallati Admin Content - لوحة تحكم المحتوى
 * No ACF, no external plugins. All via theme_mod/options.
 */
if (!defined('ABSPATH')) exit;

define('MALLATI_ADMIN_SLUG', 'mallati-content');
define('MALLATI_DESIGN_PATH', get_template_directory() . '/mallati/');

class Mallati_Admin_Content {

    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_menu'], 20);
        add_action('admin_init', [__CLASS__, 'handle_actions']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin']);
        add_action('after_switch_theme', [__CLASS__, 'create_pages_on_activation']);
    }

    /** إنشاء الصفحات المطلوبة (بما فيها قائمة المفضلة) عند تفعيل الثيم */
    public static function create_pages_on_activation() {
        if (!current_user_can('manage_options')) return;
        self::create_pages();
    }

    public static function add_menu() {
        add_menu_page(
            __('المحتوى', 'mallati-theme'),
            __('المحتوى', 'mallati-theme'),
            'manage_options',
            MALLATI_ADMIN_SLUG,
            [__CLASS__, 'render_pages'],
            'dashicons-edit-page',
            30
        );
        add_submenu_page(MALLATI_ADMIN_SLUG, __('الصفحات', 'mallati-theme'), __('1) الصفحات', 'mallati-theme'), 'manage_options', MALLATI_ADMIN_SLUG, [__CLASS__, 'render_pages']);
        add_submenu_page(MALLATI_ADMIN_SLUG, __('منتجات ديمو', 'mallati-theme'), __('2) منتجات ديمو', 'mallati-theme'), 'manage_options', 'mallati-demo-products', [__CLASS__, 'render_demo_products']);
        add_submenu_page(MALLATI_ADMIN_SLUG, __('الصفحة الرئيسية', 'mallati-theme'), __('3) الصفحة الرئيسية', 'mallati-theme'), 'manage_options', 'mallati-home', [__CLASS__, 'render_home']);
        add_submenu_page(MALLATI_ADMIN_SLUG, __('من نحن', 'mallati-theme'), __('4) من نحن', 'mallati-theme'), 'manage_options', 'mallati-about', [__CLASS__, 'render_about']);
        add_submenu_page(MALLATI_ADMIN_SLUG, __('سياسة الشحن', 'mallati-theme'), __('5) سياسة الشحن', 'mallati-theme'), 'manage_options', 'mallati-shipping', [__CLASS__, 'render_shipping']);
        add_submenu_page(MALLATI_ADMIN_SLUG, __('سياسة الاسترجاع', 'mallati-theme'), __('6) سياسة الاسترجاع', 'mallati-theme'), 'manage_options', 'mallati-return', [__CLASS__, 'render_return']);
        add_submenu_page(MALLATI_ADMIN_SLUG, __('سياسة الخصوصية', 'mallati-theme'), __('7) سياسة الخصوصية', 'mallati-theme'), 'manage_options', 'mallati-privacy', [__CLASS__, 'render_privacy']);
        add_submenu_page(MALLATI_ADMIN_SLUG, __('تواصل معنا', 'mallati-theme'), __('8) تواصل معنا', 'mallati-theme'), 'manage_options', 'mallati-contact', [__CLASS__, 'render_contact']);
        add_submenu_page(MALLATI_ADMIN_SLUG, __('الفوتر', 'mallati-theme'), __('9) الفوتر', 'mallati-theme'), 'manage_options', 'mallati-footer', [__CLASS__, 'render_footer']);
        add_submenu_page(MALLATI_ADMIN_SLUG, __('إعدادات الموقع', 'mallati-theme'), __('10) إعدادات الموقع', 'mallati-theme'), 'manage_options', 'mallati-site-settings', [__CLASS__, 'render_site_settings']);
    }

    public static function enqueue_admin($hook) {
        if (strpos($hook, 'mallati') === false) return;
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_add_inline_style('wp-admin', '
            .mallati-admin-wrap { max-width: 900px; }
            .mallati-admin-wrap h1 { margin-bottom: 16px; }
            .mallati-form-table th { width: 180px; }
            .mallati-image-preview { max-width: 150px; height: auto; display: block; margin: 8px 0; border: 1px solid #ddd; }
            .mallati-actions { margin: 16px 0; }
            .mallati-actions button, .mallati-actions .button { margin-left: 8px; }
        ');
    }

    public static function handle_actions() {
        if (!current_user_can('manage_options')) return;
        $action = isset($_REQUEST['mallati_action']) ? sanitize_text_field(wp_unslash($_REQUEST['mallati_action'])) : '';
        if (!$action) return;
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'mallati_content')) return;

        switch ($action) {
            case 'create_demo_all':
                self::create_demo_all();
                break;
            case 'delete_demo_products':
                self::delete_demo_products();
                break;
            case 'delete_demo_product':
                if (!empty($_REQUEST['product_id'])) self::delete_demo_product(absint($_REQUEST['product_id']));
                break;
            case 'delete_demo_category':
                if (!empty($_REQUEST['term_id'])) self::delete_demo_category(absint($_REQUEST['term_id']));
                break;
            case 'create_pages':
                self::create_pages();
                break;
            case 'restore_home':
                self::restore_home();
                break;
            case 'restore_about':
                self::restore_about();
                break;
            case 'restore_shipping':
                self::restore_shipping();
                break;
            case 'restore_return':
                self::restore_return();
                break;
            case 'restore_privacy':
                self::restore_privacy();
                break;
            case 'restore_footer':
                self::restore_footer();
                break;
            case 'restore_colors':
                self::restore_colors();
                break;
            case 'save_home':
                self::save_home();
                break;
            case 'save_about':
                self::save_about();
                break;
            case 'save_shipping':
                self::save_shipping();
                break;
            case 'save_return':
                self::save_return();
                break;
            case 'save_privacy':
                self::save_privacy();
                break;
            case 'save_footer':
                self::save_footer();
                break;
            case 'save_site_settings':
                self::save_site_settings();
                break;
            case 'save_contact':
                self::save_contact();
                break;
            case 'restore_contact':
                self::restore_contact();
                break;
            case 'test_email':
                self::test_email();
                break;
        }
    }

    private static function redirect_with_notice($url, $type, $msg) {
        wp_safe_redirect(add_query_arg(['mallati_notice' => $type, 'mallati_msg' => urlencode($msg)], $url));
        exit;
    }

    public static function render_notices() {
        if (!isset($_GET['mallati_notice'])) return;
        $type = sanitize_text_field(wp_unslash($_GET['mallati_notice']));
        $msg = isset($_GET['mallati_msg']) ? urldecode(sanitize_text_field(wp_unslash($_GET['mallati_msg']))) : '';
        $class = $type === 'success' ? 'notice-success' : 'notice-error';
        if ($msg) echo '<div class="notice ' . esc_attr($class) . ' is-dismissible"><p>' . esc_html($msg) . '</p></div>';
    }

    // --- Demo Products ---
    private static function get_demo_categories_data() {
        return [
            ['name' => 'المكياج', 'slug' => 'makeup'],
            ['name' => 'وصل حديثاً', 'slug' => 'new-arrivals'],
            ['name' => 'العطور', 'slug' => 'perfumes'],
            ['name' => 'الأكثر مبيعاً', 'slug' => 'best-sellers'],
            ['name' => 'منتجات العناية', 'slug' => 'skincare'],
        ];
    }

    private static function get_demo_product_templates() {
        return [
            ['name' => 'بنطلون رجالي كاجوال بجيوب سوستة وشريط فضي', 'price' => 200.50, 'sale_price' => 160.40],
            ['name' => 'عطر نسائي فاخر', 'price' => 350, 'sale_price' => 280],
            ['name' => 'مجموعة مكياج أساسية', 'price' => 180, 'sale_price' => 0],
            ['name' => 'منتج عناية بالبشرة', 'price' => 120, 'sale_price' => 96],
            ['name' => 'كريم مرطب للوجه', 'price' => 95, 'sale_price' => 76],
        ];
    }

    private static function get_demo_product_images() {
        return ['cat1.png', 'cat2.png', 'cat3.png', 'cat4.png', 'cat5.png'];
    }

    private static function create_demo_all() {
        if (!class_exists('WooCommerce')) {
            self::redirect_with_notice(admin_url('admin.php?page=mallati-demo-products'), 'error', __('WooCommerce غير مفعّل.', 'mallati-theme'));
            return;
        }
        $cats = self::get_demo_categories_data();
        $templates = self::get_demo_product_templates();
        $images = self::get_demo_product_images();
        $created_cats = 0;
        $created_prods = 0;

        foreach ($cats as $cat) {
            $exists = get_term_by('slug', $cat['slug'], 'product_cat');
            if (!$exists) {
                $r = wp_insert_term($cat['name'], 'product_cat', ['slug' => $cat['slug']]);
                if (!is_wp_error($r)) {
                    add_term_meta($r['term_id'], 'mallati_is_demo', 1, true);
                    $created_cats++;
                }
            }
        }

        foreach ($cats as $cat) {
            $term = get_term_by('slug', $cat['slug'], 'product_cat');
            if (!$term) continue;
            for ($i = 0; $i < 5; $i++) {
                $t = $templates[$i % count($templates)];
                $img = $images[$i % count($images)];
                $name = $t['name'] . ' - ' . $cat['name'] . ' ' . ($i + 1);
                $existing = get_page_by_title($name, OBJECT, 'product');
                if ($existing && get_post_meta($existing->ID, 'mallati_is_demo', true)) continue;
                $post = [
                    'post_title'   => $name,
                    'post_status'  => 'publish',
                    'post_type'    => 'product',
                ];
                $id = wp_insert_post($post);
            if ($id) {
                if (class_exists('WC_Product_Simple')) {
                    wp_set_object_terms($id, 'simple', 'product_type');
                }
                update_post_meta($id, 'mallati_is_demo', 1);
                update_post_meta($id, '_price', $t['sale_price'] ? (string) $t['sale_price'] : (string) $t['price']);
                update_post_meta($id, '_regular_price', (string) $t['price']);
                if ($t['sale_price']) update_post_meta($id, '_sale_price', (string) $t['sale_price']);
                update_post_meta($id, '_sku', 'DEMO-' . $id);
                wp_set_object_terms($id, [$term->term_id], 'product_cat');
                $img_path = get_template_directory() . '/mallati/assets/' . $img;
                if (file_exists($img_path)) {
                    $attach_id = self::upload_image_from_path($img_path, $id);
                    if ($attach_id) set_post_thumbnail($id, $attach_id);
                }
                $created_prods++;
            }
        }
        }
        $msg = sprintf(__('تم إنشاء %d تصنيف و %d منتج ديمو.', 'mallati-theme'), $created_cats, $created_prods);
        self::redirect_with_notice(admin_url('admin.php?page=mallati-demo-products'), 'success', $msg);
    }

    private static function upload_image_from_path($path, $post_id = 0) {
        $filename = basename($path);
        $upload = wp_upload_bits($filename, null, file_get_contents($path));
        if ($upload['error']) return 0;
        $attach = [
            'post_mime_type' => wp_check_filetype($filename, null)['type'],
            'post_title'     => sanitize_file_name(pathinfo($filename, PATHINFO_FILENAME)),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ];
        $id = wp_insert_attachment($attach, $upload['file'], $post_id);
        if (is_wp_error($id)) return 0;
        require_once ABSPATH . 'wp-admin/includes/image.php';
        wp_generate_attachment_metadata($id, $upload['file']);
        return $id;
    }

    private static function delete_demo_products() {
        if (!class_exists('WooCommerce')) return;
        $products = get_posts(['post_type' => 'product', 'meta_key' => 'mallati_is_demo', 'meta_value' => '1', 'posts_per_page' => -1, 'post_status' => 'any']);
        foreach ($products as $p) wp_delete_post($p->ID, true);
        $terms = get_terms(['taxonomy' => 'product_cat', 'meta_key' => 'mallati_is_demo', 'meta_value' => '1', 'hide_empty' => false]);
        foreach ($terms as $t) wp_delete_term($t->term_id, 'product_cat');
        self::redirect_with_notice(admin_url('admin.php?page=mallati-demo-products'), 'success', __('تم حذف جميع منتجات وتصنيفات الديمو.', 'mallati-theme'));
    }

    private static function delete_demo_product($id) {
        if (!current_user_can('manage_options')) return;
        $demo = get_post_meta($id, 'mallati_is_demo', true);
        if ($demo) wp_delete_post($id, true);
        self::redirect_with_notice(admin_url('admin.php?page=mallati-demo-products'), 'success', __('تم حذف المنتج.', 'mallati-theme'));
    }

    private static function delete_demo_category($term_id) {
        if (!current_user_can('manage_options')) return;
        $demo = get_term_meta($term_id, 'mallati_is_demo', true);
        if ($demo) {
            $count = wp_count_posts('product');
            $products = get_posts(['post_type' => 'product', 'tax_query' => [['taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => $term_id]], 'posts_per_page' => -1]);
            if (!empty($products)) {
                foreach ($products as $p) {
                    if (get_post_meta($p->ID, 'mallati_is_demo', true)) wp_delete_post($p->ID, true);
                }
            }
            wp_delete_term($term_id, 'product_cat');
        }
        self::redirect_with_notice(admin_url('admin.php?page=mallati-demo-products'), 'success', __('تم حذف التصنيف.', 'mallati-theme'));
    }

    public static function render_demo_products() {
        if (!current_user_can('manage_options')) return;
        self::render_notices();
        $products = [];
        $categories = [];
        if (class_exists('WooCommerce')) {
            $products = get_posts(['post_type' => 'product', 'meta_key' => 'mallati_is_demo', 'meta_value' => '1', 'posts_per_page' => -1, 'post_status' => 'any']);
            $categories = get_terms(['taxonomy' => 'product_cat', 'meta_key' => 'mallati_is_demo', 'meta_value' => '1', 'hide_empty' => false]);
        }
        $nonce = wp_nonce_field('mallati_content', '_wpnonce', true, false);
        ?>
        <div class="wrap mallati-admin-wrap">
            <h1><?php esc_html_e('منتجات ديمو', 'mallati-theme'); ?></h1>
            <div class="mallati-actions">
                <form method="post" style="display:inline">
                    <?php echo $nonce; ?>
                    <input type="hidden" name="mallati_action" value="create_demo_all" />
                    <button type="submit" class="button button-primary"><?php esc_html_e('إنشاء تصنيفات ومنتجات ديمو', 'mallati-theme'); ?></button>
                </form>
                <form method="post" style="display:inline" onsubmit="return confirm('<?php esc_attr_e('حذف كل منتجات وتصنيفات الديمو؟', 'mallati-theme'); ?>');">
                    <?php echo $nonce; ?>
                    <input type="hidden" name="mallati_action" value="delete_demo_products" />
                    <button type="submit" class="button"><?php esc_html_e('حذف منتجات الديمو', 'mallati-theme'); ?></button>
                </form>
            </div>

            <h2><?php esc_html_e('التصنيفات المنشأة', 'mallati-theme'); ?></h2>
            <table class="widefat striped">
                <thead><tr><th><?php esc_html_e('الاسم', 'mallati-theme'); ?></th><th>Slug</th><th><?php esc_html_e('العدد', 'mallati-theme'); ?></th><th><?php esc_html_e('إجراءات', 'mallati-theme'); ?></th></tr></thead>
                <tbody>
                <?php foreach ($categories as $c) :
                    $count = $c->count;
                    $edit = admin_url('term.php?taxonomy=product_cat&tag_ID=' . $c->term_id . '&post_type=product');
                    ?>
                    <tr>
                        <td><?php echo esc_html($c->name); ?></td>
                        <td><?php echo esc_html($c->slug); ?></td>
                        <td><?php echo absint($count); ?></td>
                        <td>
                            <a href="<?php echo esc_url($edit); ?>"><?php esc_html_e('تعديل', 'mallati-theme'); ?></a>
                            <form method="post" style="display:inline" onsubmit="return confirm('<?php esc_attr_e('حذف هذا التصنيف؟', 'mallati-theme'); ?>');">
                                <?php echo $nonce; ?>
                                <input type="hidden" name="mallati_action" value="delete_demo_category" />
                                <input type="hidden" name="term_id" value="<?php echo esc_attr($c->term_id); ?>" />
                                <button type="submit" class="button-link-delete"><?php esc_html_e('حذف', 'mallati-theme'); ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach;
                if (empty($categories)) echo '<tr><td colspan="4">' . esc_html__('لا توجد تصنيفات ديمو.', 'mallati-theme') . '</td></tr>'; ?>
                </tbody>
            </table>

            <h2><?php esc_html_e('المنتجات المنشأة', 'mallati-theme'); ?></h2>
            <table class="widefat striped">
                <thead><tr><th><?php esc_html_e('الصورة', 'mallati-theme'); ?></th><th><?php esc_html_e('الاسم', 'mallati-theme'); ?></th><th>SKU</th><th><?php esc_html_e('السعر', 'mallati-theme'); ?></th><th><?php esc_html_e('التصنيف', 'mallati-theme'); ?></th><th><?php esc_html_e('الحالة', 'mallati-theme'); ?></th><th><?php esc_html_e('إجراءات', 'mallati-theme'); ?></th></tr></thead>
                <tbody>
                <?php foreach ($products as $p) :
                    $product = wc_get_product($p->ID);
                    $thumb = get_the_post_thumbnail_url($p->ID, 'thumbnail');
                    $sku = $product ? $product->get_sku() : '';
                    $price = $product ? $product->get_price_html() : '';
                    $terms = wp_get_object_terms($p->ID, 'product_cat');
                    $cat_name = !empty($terms) ? $terms[0]->name : '-';
                    $edit = admin_url('post.php?post=' . $p->ID . '&action=edit');
                    ?>
                    <tr>
                        <td><?php if ($thumb) { ?><img src="<?php echo esc_url($thumb); ?>" width="50" height="50" alt="" /><?php } else echo '-'; ?></td>
                        <td><?php echo esc_html($p->post_title); ?></td>
                        <td><?php echo esc_html($sku); ?></td>
                        <td><?php echo $price; ?></td>
                        <td><?php echo esc_html($cat_name); ?></td>
                        <td><?php echo esc_html($p->post_status); ?></td>
                        <td>
                            <a href="<?php echo esc_url($edit); ?>"><?php esc_html_e('تعديل', 'mallati-theme'); ?></a>
                            <form method="post" style="display:inline" onsubmit="return confirm('<?php esc_attr_e('حذف هذا المنتج؟', 'mallati-theme'); ?>');">
                                <?php echo $nonce; ?>
                                <input type="hidden" name="mallati_action" value="delete_demo_product" />
                                <input type="hidden" name="product_id" value="<?php echo esc_attr($p->ID); ?>" />
                                <button type="submit" class="button-link-delete"><?php esc_html_e('حذف', 'mallati-theme'); ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach;
                if (empty($products)) echo '<tr><td colspan="7">' . esc_html__('لا توجد منتجات ديمو.', 'mallati-theme') . '</td></tr>'; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    // --- Pages ---
    private static function get_page_slugs() {
        return [
            'front'    => ['slug' => 'front-page', 'title' => __('الرئيسية', 'mallati-theme'), 'template' => ''],
            'about'    => ['slug' => 'about-us', 'title' => __('من نحن', 'mallati-theme'), 'template' => 'page-templates/about-us.php'],
            'shipping' => ['slug' => 'shipping-policy', 'title' => __('سياسة الشحن', 'mallati-theme'), 'template' => 'page-templates/policy.php'],
            'return'   => ['slug' => 'return-policy', 'title' => __('سياسة الاسترجاع', 'mallati-theme'), 'template' => 'page-templates/policy.php'],
            'privacy'  => ['slug' => 'privacy-policy', 'title' => __('سياسة الخصوصية', 'mallati-theme'), 'template' => 'page-templates/policy.php'],
            'contact'  => ['slug' => 'contact-us', 'title' => __('تواصل معنا', 'mallati-theme'), 'template' => 'page-templates/contact-us.php'],
            'usage'    => ['slug' => 'user-polices', 'title' => __('سياسة الاستخدام', 'mallati-theme'), 'template' => 'page-templates/policy.php'],
            'favourites'=> ['slug' => 'favourites', 'title' => __('قائمة المفضلة', 'mallati-theme'), 'template' => 'page-templates/favourites.php'],
        ];
    }

    private static function get_default_colors() {
        return [
            'header_bg'       => '#ffffff',
            'footer_bg'       => '#f3f1ef',
            'add_to_cart'     => '#f7931e',
            'checkout_btn'    => '#f7931e',
            'payment_btn'     => '#f7931e',
            'page_bg'         => '#f3f1ef',
        ];
    }

    private static function get_default_content($key) {
        $about = '<p>نحن في مولاتي نؤمن أن الجمال يبدأ من التفاصيل الصغيرة. منذ انطلاقنا ونحن نقدم لعملائنا أجود أنواع العطور، منتجات المكياج، وحلول العناية الشخصية التي تجمع بين الفخامة والجودة.</p>
<p>رؤيتنا هي أن نكون الوجهة الأولى لكل من يبحث عن منتجات أصلية وموثوقة تضيف لمسة خاصة لحياته اليومية.</p>
<p>نحن ملتزمون بتقديم تجربة تسوق سلسة، دعم عملاء مميز، وأسعار تناسب الجميع. اكتشف مجموعتنا اليوم ودعنا نكون جزءًا من روتينك الجمالي.</p>
<h2>رؤيتنا</h2>
<p>أن نكون الوجهة الأولى لكل من يبحث عن منتجات أصلية وموثوقة تضيف لمسة خاصة لحياته اليومية.</p>
<h2>قيمنا</h2>
<p>1- الجودة والأصالة: نحرص على توفير منتجات أصلية ومضمونة.</p>
<p>2- تجربة تسوق مميزة: واجهة سهلة ودعم عملاء سريع.</p>
<p>3- لمسة فخامة بأسعار مناسبة: نمنح عملاءنا أفضل قيمة مقابل السعر.</p>
<p>4- اكتشفي مجموعتنا الآن ودعينا نكون جزءًا من روتينك الجمالي اليومي.</p>';

        $policy = '<h2>سياسة الاسترجاع والاسترداد</h2>
<p>نعمل دائما لنيل رضاكم ونكون عند حسن ظنكم بنا. إذا كنت ترغب في إرجاع منتج ما، فنحن نقبل بسرور استبدال المنتج أو منحك رصيداً أو إرجاع المبلغ. في حال طلب استرجاع أي منتج، يرجى التواصل معنا عبر البريد الإلكتروني أو الهاتف أو الواتساب.</p>
<h2>الدفع عبر الإنترنت</h2>
<p>ستتم معالجة المبالغ المستردة في غضون ٢٤ ساعة وستضاف إلى حساب العميل في غضون 3-5 أيام عمل، اعتمادًا على مصدر البنك.</p>
<h2>الدفع نقداً عند التسليم</h2>
<p>ستُضاف المبالغ المستردة إلى حساب العميل ويمكن استخدامها في الطلب التالي.</p>
<h2>سياسة الشحن</h2>
<p>يتم الشحن خلال اليوم لكل الطلبات داخل المدينة المنورة، أما كل الطلبات داخل السعودية وخارج المدينة المنورة فيستغرق الشحن من يوم إلى ثلاثة أيام عمل.</p>';

        $map = [
            'about'    => $about,
            'shipping' => '<h2>سياسة الشحن</h2><p>يتم الشحن خلال اليوم لكل الطلبات داخل المدينة المنورة، أما كل الطلبات داخل السعودية وخارج المدينة المنورة فيستغرق الشحن من يوم إلى ثلاثة أيام عمل.</p>' . $policy,
            'return'   => $policy,
            'privacy'  => $policy,
            'usage'    => $policy,
            'favourites' => '',
        ];
        return isset($map[$key]) ? $map[$key] : '';
    }

    private static function create_pages() {
        $slugs = self::get_page_slugs();
        foreach ($slugs as $key => $conf) {
            if ($key === 'front') continue;
            $page = get_page_by_path($conf['slug']);
            if (!$page) {
                $id = wp_insert_post([
                    'post_title'   => $conf['title'],
                    'post_name'    => $conf['slug'],
                    'post_status'  => 'publish',
                    'post_type'    => 'page',
                    'post_content' => self::get_default_content($key),
                ]);
                if ($id && $conf['template']) update_post_meta($id, '_wp_page_template', $conf['template']);
            } else {
                wp_update_post([
                    'ID'           => $page->ID,
                    'post_content' => self::get_default_content($key),
                ]);
                if ($conf['template']) update_post_meta($page->ID, '_wp_page_template', $conf['template']);
            }
        }
        self::redirect_with_notice(admin_url('admin.php?page=' . MALLATI_ADMIN_SLUG), 'success', __('تم إنشاء/تحديث الصفحات.', 'mallati-theme'));
    }

    public static function render_pages() {
        if (!current_user_can('manage_options')) return;
        self::render_notices();
        $nonce = wp_nonce_field('mallati_content', '_wpnonce', true, false);
        $slugs = self::get_page_slugs();
        $rows = [];
        foreach ($slugs as $key => $conf) {
            if ($key === 'front') {
                $front_id = (int) get_option('page_on_front');
                $page = $front_id ? get_post($front_id) : null;
            } else {
                $page = get_page_by_path($conf['slug']);
            }
            $from_design = $page ? get_post_meta($page->ID, 'mallati_from_design', true) : '';
            $rows[] = [
                'title'  => $conf['title'],
                'slug'   => $conf['slug'],
                'status' => $page ? $page->post_status : '-',
                'updated'=> $page ? get_the_modified_date('Y-m-d H:i', $page) : '-',
                'view'   => $page ? get_permalink($page) : '#',
                'edit'   => $page ? admin_url('post.php?post=' . $page->ID . '&action=edit') : '#',
                'flag'   => $from_design ? __('من التصميم', 'mallati-theme') : '',
            ];
        }
        ?>
        <div class="wrap mallati-admin-wrap">
            <h1><?php esc_html_e('الصفحات', 'mallati-theme'); ?></h1>
            <div class="mallati-actions">
                <form method="post" style="display:inline">
                    <?php echo $nonce; ?>
                    <input type="hidden" name="mallati_action" value="create_pages" />
                    <button type="submit" class="button button-primary"><?php esc_html_e('إنشاء/تحديث كل صفحات الموقع', 'mallati-theme'); ?></button>
                </form>
            </div>
            <table class="widefat striped">
                <thead><tr><th><?php esc_html_e('العنوان', 'mallati-theme'); ?></th><th>Slug</th><th><?php esc_html_e('الحالة', 'mallati-theme'); ?></th><th><?php esc_html_e('آخر تحديث', 'mallati-theme'); ?></th><th><?php esc_html_e('عرض', 'mallati-theme'); ?></th><th><?php esc_html_e('تعديل', 'mallati-theme'); ?></th><th><?php esc_html_e('علامة', 'mallati-theme'); ?></th></tr></thead>
                <tbody>
                <?php foreach ($rows as $r) : ?>
                    <tr>
                        <td><?php echo esc_html($r['title']); ?></td>
                        <td><?php echo esc_html($r['slug']); ?></td>
                        <td><?php echo esc_html($r['status']); ?></td>
                        <td><?php echo esc_html($r['updated']); ?></td>
                        <td><a href="<?php echo esc_url($r['view']); ?>" target="_blank"><?php esc_html_e('عرض', 'mallati-theme'); ?></a></td>
                        <td><a href="<?php echo esc_url($r['edit']); ?>"><?php esc_html_e('تعديل', 'mallati-theme'); ?></a></td>
                        <td><?php echo esc_html($r['flag']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    public static function render_home() {
        if (!current_user_can('manage_options')) return;
        self::render_notices();
        $nonce = wp_nonce_field('mallati_content', '_wpnonce', true, false);
        $hero_slides = get_option('mallati_hero_slides', []);
        if (empty($hero_slides)) $hero_slides = [0, 0, 0];
        $hero_slides = array_pad((array) $hero_slides, 3, 0);
        $banner_id = get_option('mallati_home_banner', 0);
        $banner_url = $banner_id ? wp_get_attachment_image_url($banner_id, 'medium') : get_template_directory_uri() . '/mallati/assets/hero1.png';
        $title_best = get_option('mallati_home_title_best', __('الاكثر مبيعا:', 'mallati-theme'));
        $title_new = get_option('mallati_home_title_new', __('وصل حديثا:', 'mallati-theme'));
        $default_img = get_template_directory_uri() . '/mallati/assets/hero1.png';
        ?>
        <div class="wrap mallati-admin-wrap">
            <h1><?php esc_html_e('الصفحة الرئيسية', 'mallati-theme'); ?></h1>
            <form method="post">
                <?php echo $nonce; ?>
                <input type="hidden" name="mallati_action" value="save_home" />
                <h2><?php esc_html_e('صور الهيرو (الشريط العلوي)', 'mallati-theme'); ?></h2>
                <table class="form-table mallati-form-table">
                    <?php for ($i = 0; $i < 3; $i++) :
                        $sid = isset($hero_slides[$i]) ? $hero_slides[$i] : 0;
                        $surl = $sid ? wp_get_attachment_image_url($sid, 'medium') : $default_img;
                        ?>
                        <tr>
                            <th><?php echo esc_html(sprintf(__('الشريحة %d', 'mallati-theme'), $i + 1)); ?></th>
                            <td>
                                <img class="mallati-image-preview" src="<?php echo esc_url($surl); ?>" alt="" id="mallati-hero<?php echo $i; ?>-preview" style="max-width:150px;display:block;margin:8px 0;" />
                                <input type="hidden" name="mallati_hero_slides[]" id="mallati-hero<?php echo $i; ?>-id" value="<?php echo absint($sid); ?>" />
                                <button type="button" class="button mallati-upload-btn" data-target="mallati-hero<?php echo $i; ?>"><?php esc_html_e('اختيار صورة', 'mallati-theme'); ?></button>
                                <button type="button" class="button mallati-remove-btn" data-target="mallati-hero<?php echo $i; ?>"><?php esc_html_e('إزالة', 'mallati-theme'); ?></button>
                            </td>
                        </tr>
                    <?php endfor; ?>
                </table>
                <h2><?php esc_html_e('صورة البانر (بين الأقسام)', 'mallati-theme'); ?></h2>
                <table class="form-table mallati-form-table">
                    <tr>
                        <th><?php esc_html_e('الصورة', 'mallati-theme'); ?></th>
                        <td>
                            <img class="mallati-image-preview" src="<?php echo esc_url($banner_url); ?>" alt="" id="mallati-banner-preview" style="max-width:150px;display:block;margin:8px 0;" />
                            <input type="hidden" name="mallati_home_banner" id="mallati-banner-id" value="<?php echo absint($banner_id); ?>" />
                            <button type="button" class="button mallati-upload-btn" data-target="mallati-banner"><?php esc_html_e('اختيار صورة', 'mallati-theme'); ?></button>
                            <button type="button" class="button mallati-remove-btn" data-target="mallati-banner"><?php esc_html_e('إزالة', 'mallati-theme'); ?></button>
                        </td>
                    </tr>
                </table>
                <h2><?php esc_html_e('نصوص الأقسام', 'mallati-theme'); ?></h2>
                <table class="form-table mallati-form-table">
                    <tr><th><label for="mallati_home_title_best"><?php esc_html_e('عنوان الأكثر مبيعاً', 'mallati-theme'); ?></label></th><td><input type="text" id="mallati_home_title_best" name="mallati_home_title_best" value="<?php echo esc_attr($title_best); ?>" class="regular-text" /></td></tr>
                    <tr><th><label for="mallati_home_title_new"><?php esc_html_e('عنوان وصل حديثاً', 'mallati-theme'); ?></label></th><td><input type="text" id="mallati_home_title_new" name="mallati_home_title_new" value="<?php echo esc_attr($title_new); ?>" class="regular-text" /></td></tr>
                </table>
                <p><button type="submit" class="button button-primary"><?php esc_html_e('حفظ', 'mallati-theme'); ?></button></p>
            </form>
            <form method="post" style="margin-top:16px">
                <?php echo $nonce; ?>
                <input type="hidden" name="mallati_action" value="restore_home" />
                <button type="submit" class="button"><?php esc_html_e('استعادة المحتوى الأصلي', 'mallati-theme'); ?></button>
            </form>
        </div>
        <?php
        for ($i = 0; $i < 3; $i++) self::render_media_script('mallati-hero' . $i, 'mallati-hero' . $i . '-id', 'mallati-hero' . $i . '-preview', $default_img);
        self::render_media_script('mallati-banner', 'mallati-banner-id', 'mallati-banner-preview', $default_img);
    }
    public static function render_about() { self::render_generic_page(__('من نحن', 'mallati-theme'), 'mallati-about', 'restore_about', 'save_about'); }
    public static function render_shipping() { self::render_generic_page(__('سياسة الشحن', 'mallati-theme'), 'mallati-shipping', 'restore_shipping', 'save_shipping'); }
    public static function render_return() { self::render_generic_page(__('سياسة الاسترجاع', 'mallati-theme'), 'mallati-return', 'restore_return', 'save_return'); }
    public static function render_privacy() { self::render_generic_page(__('سياسة الخصوصية', 'mallati-theme'), 'mallati-privacy', 'restore_privacy', 'save_privacy'); }
    public static function render_footer() {
        if (!current_user_can('manage_options')) return;
        self::render_notices();
        $nonce = wp_nonce_field('mallati_content', '_wpnonce', true, false);
        $logo_id = get_theme_mod('mallati_footer_logo', 0);
        $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'medium') : (get_template_directory_uri() . '/mallati/assets/logo.png');
        $phone = get_theme_mod('mallati_footer_phone', get_theme_mod('mallati_phone', '+966 50 000 0000'));
        $whatsapp = get_theme_mod('mallati_footer_whatsapp', '');
        $copyright = get_theme_mod('mallati_footer_copyright', sprintf(__('جميع الحقوق محفوظة لـ %s © %d', 'mallati-theme'), get_bloginfo('name'), (int) date('Y')));
        ?>
        <div class="wrap mallati-admin-wrap">
            <h1><?php esc_html_e('الفوتر', 'mallati-theme'); ?></h1>
            <p><?php esc_html_e('شعار واحد يستخدم في الهيدر والفوتر.', 'mallati-theme'); ?></p>
            <form method="post">
                <?php echo $nonce; ?>
                <input type="hidden" name="mallati_action" value="save_footer" />
                <table class="form-table mallati-form-table">
                    <tr>
                        <th><?php esc_html_e('الشعار الحالي', 'mallati-theme'); ?></th>
                        <td>
                            <div class="mallati-image-field">
                                <img class="mallati-image-preview" src="<?php echo esc_url($logo_url); ?>" alt="" id="mallati-logo-preview" style="<?php echo !$logo_id ? 'max-width:120px' : ''; ?>" />
                                <input type="hidden" name="mallati_logo_id" id="mallati-logo-id" value="<?php echo absint($logo_id); ?>" />
                                <p>
                                    <button type="button" class="button mallati-upload-btn" data-target="mallati-logo"><?php esc_html_e('اختيار صورة', 'mallati-theme'); ?></button>
                                    <button type="button" class="button mallati-remove-btn" data-target="mallati-logo"><?php esc_html_e('إزالة', 'mallati-theme'); ?></button>
                                </p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="mallati_footer_phone"><?php esc_html_e('رقم الاتصال', 'mallati-theme'); ?></label></th>
                        <td><input type="text" id="mallati_footer_phone" name="mallati_footer_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th><label for="mallati_footer_whatsapp"><?php esc_html_e('رقم الواتساب', 'mallati-theme'); ?></label></th>
                        <td><input type="text" id="mallati_footer_whatsapp" name="mallati_footer_whatsapp" value="<?php echo esc_attr($whatsapp); ?>" class="regular-text" placeholder="966500000000" /></td>
                    </tr>
                    <tr>
                        <th><label for="mallati_footer_copyright"><?php esc_html_e('نص حقوق النشر', 'mallati-theme'); ?></label></th>
                        <td><input type="text" id="mallati_footer_copyright" name="mallati_footer_copyright" value="<?php echo esc_attr($copyright); ?>" class="large-text" /></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('الأزرار العائمة', 'mallati-theme'); ?></th>
                        <td><label><input type="checkbox" name="mallati_floating_buttons" value="1" <?php checked(get_theme_mod('mallati_floating_buttons', 0), 1); ?> /> <?php esc_html_e('تفعيل أزرار الاتصال والواتساب العائمة', 'mallati-theme'); ?></label></td>
                    </tr>
                </table>
                <p><button type="submit" class="button button-primary"><?php esc_html_e('حفظ', 'mallati-theme'); ?></button></p>
            </form>
            <form method="post" style="margin-top:16px">
                <?php echo $nonce; ?>
                <input type="hidden" name="mallati_action" value="restore_footer" />
                <button type="submit" class="button"><?php esc_html_e('استعادة المحتوى الأصلي', 'mallati-theme'); ?></button>
            </form>
        </div>
        <?php self::render_media_script('mallati-logo', 'mallati_logo_id', 'mallati-logo-preview', get_template_directory_uri() . '/mallati/assets/logo.png'); ?>
        <?php
    }

    public static function render_contact() {
        if (!current_user_can('manage_options')) return;
        self::render_notices();
        $nonce = wp_nonce_field('mallati_content', '_wpnonce', true, false);
        $addr = get_theme_mod('mallati_address', 'الرياض، المملكة العربية السعودية');
        $email = get_theme_mod('mallati_email', get_option('admin_email'));
        $phone = get_theme_mod('mallati_phone', '+966 50 000 0000');
        $whatsapp = get_theme_mod('mallati_whatsapp', '');
        $map = get_theme_mod('mallati_map_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3624.006470365758!2d46.675296!3d24.713551!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2f042f4f9b7a23%3A0x9af0b5a24b!2sRiyadh!5e0!3m2!1sar!2ssa!4v1688570000000');
        $form_title = get_option('mallati_contact_form_title', __('أرسل رسالة', 'mallati-theme'));
        $form_desc = get_option('mallati_contact_form_desc', __('أرسل رسالة إلينا وسنرد عليك في أسرع وقت ممكن.', 'mallati-theme'));
        $smtp_type = get_option('mallati_smtp_type', 'gmail');
        $smtp_host = get_option('mallati_smtp_host', '');
        $smtp_port = get_option('mallati_smtp_port', '587');
        $smtp_user = get_option('mallati_smtp_user', '');
        ?>
        <div class="wrap mallati-admin-wrap">
            <h1><?php esc_html_e('تواصل معنا', 'mallati-theme'); ?></h1>
            <form method="post">
                <?php echo $nonce; ?>
                <input type="hidden" name="mallati_action" value="save_contact" />
                <h2><?php esc_html_e('معلومات التواصل', 'mallati-theme'); ?></h2>
                <table class="form-table mallati-form-table">
                    <tr><th><label for="mallati_contact_form_title"><?php esc_html_e('عنوان النموذج', 'mallati-theme'); ?></label></th><td><input type="text" id="mallati_contact_form_title" name="mallati_contact_form_title" value="<?php echo esc_attr($form_title); ?>" class="regular-text" /></td></tr>
                    <tr><th><label for="mallati_contact_form_desc"><?php esc_html_e('وصف النموذج', 'mallati-theme'); ?></label></th><td><input type="text" id="mallati_contact_form_desc" name="mallati_contact_form_desc" value="<?php echo esc_attr($form_desc); ?>" class="large-text" /></td></tr>
                    <tr><th><label for="mallati_address"><?php esc_html_e('العنوان', 'mallati-theme'); ?></label></th><td><input type="text" id="mallati_address" name="mallati_address" value="<?php echo esc_attr($addr); ?>" class="large-text" /></td></tr>
                    <tr><th><label for="mallati_email"><?php esc_html_e('البريد الإلكتروني', 'mallati-theme'); ?></label></th><td><input type="email" id="mallati_email" name="mallati_email" value="<?php echo esc_attr($email); ?>" class="regular-text" /></td></tr>
                    <tr><th><label for="mallati_phone"><?php esc_html_e('رقم الهاتف', 'mallati-theme'); ?></label></th><td><input type="text" id="mallati_phone" name="mallati_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" placeholder="+966 50 000 0000" /></td></tr>
                    <tr><th><label for="mallati_whatsapp"><?php esc_html_e('رقم الواتساب', 'mallati-theme'); ?></label></th><td><input type="text" id="mallati_whatsapp" name="mallati_whatsapp" value="<?php echo esc_attr($whatsapp); ?>" class="regular-text" placeholder="966500000000" /></td></tr>
                    <tr><th><label for="mallati_map_embed"><?php esc_html_e('رابط خريطة Google Maps (embed)', 'mallati-theme'); ?></label></th><td><textarea id="mallati_map_embed" name="mallati_map_embed" rows="3" class="large-text" placeholder="https://www.google.com/maps/embed?pb=..."><?php echo esc_textarea($map); ?></textarea><p class="description"><?php esc_html_e('من Google Maps: مشاركة → تضمين خريطة → انسخ رابط iframe src', 'mallati-theme'); ?></p></td></tr>
                </table>
                <h2><?php esc_html_e('إعدادات البريد', 'mallati-theme'); ?></h2>
                <table class="form-table mallati-form-table">
                    <tr><th><label for="mallati_smtp_type"><?php esc_html_e('نوع البريد', 'mallati-theme'); ?></label></th><td><select id="mallati_smtp_type" name="mallati_smtp_type"><option value="gmail" <?php selected($smtp_type, 'gmail'); ?>><?php esc_html_e('Gmail App Password', 'mallati-theme'); ?></option><option value="smtp" <?php selected($smtp_type, 'smtp'); ?>><?php esc_html_e('SMTP (إيميل احترافي)', 'mallati-theme'); ?></option></select></td></tr>
                    <tr class="mallati-gmail-fields" style="<?php echo $smtp_type !== 'gmail' ? 'display:none' : ''; ?>"><th><label for="mallati_gmail_email"><?php esc_html_e('البريد (Gmail)', 'mallati-theme'); ?></label></th><td><input type="email" id="mallati_gmail_email" name="mallati_gmail_email" value="<?php echo esc_attr($smtp_user); ?>" class="regular-text" /></td></tr>
                    <tr class="mallati-gmail-fields" style="<?php echo $smtp_type !== 'gmail' ? 'display:none' : ''; ?>"><th><label for="mallati_gmail_app_pass"><?php esc_html_e('App Password', 'mallati-theme'); ?></label></th><td><input type="password" id="mallati_gmail_app_pass" name="mallati_gmail_app_pass" value="" class="regular-text" placeholder="<?php esc_attr_e('اتركه فارغاً لعدم التغيير', 'mallati-theme'); ?>" autocomplete="new-password" /></td></tr>
                    <tr class="mallati-smtp-fields" style="<?php echo $smtp_type !== 'smtp' ? 'display:none' : ''; ?>"><th><label for="mallati_smtp_host"><?php esc_html_e('SMTP Host', 'mallati-theme'); ?></label></th><td><input type="text" id="mallati_smtp_host" name="mallati_smtp_host" value="<?php echo esc_attr($smtp_host); ?>" class="regular-text" placeholder="smtp.example.com" /></td></tr>
                    <tr class="mallati-smtp-fields" style="<?php echo $smtp_type !== 'smtp' ? 'display:none' : ''; ?>"><th><label for="mallati_smtp_port"><?php esc_html_e('Port', 'mallati-theme'); ?></label></th><td><input type="text" id="mallati_smtp_port" name="mallati_smtp_port" value="<?php echo esc_attr($smtp_port); ?>" class="small-text" /></td></tr>
                    <tr class="mallati-smtp-fields" style="<?php echo $smtp_type !== 'smtp' ? 'display:none' : ''; ?>"><th><label for="mallati_smtp_user"><?php esc_html_e('البريد / Username', 'mallati-theme'); ?></label></th><td><input type="text" id="mallati_smtp_user" name="mallati_smtp_user" value="<?php echo esc_attr($smtp_user); ?>" class="regular-text" /></td></tr>
                    <tr class="mallati-smtp-fields" style="<?php echo $smtp_type !== 'smtp' ? 'display:none' : ''; ?>"><th><label for="mallati_smtp_pass"><?php esc_html_e('كلمة المرور', 'mallati-theme'); ?></label></th><td><input type="password" id="mallati_smtp_pass" name="mallati_smtp_pass" value="" class="regular-text" placeholder="<?php esc_attr_e('اتركه فارغاً لعدم التغيير', 'mallati-theme'); ?>" autocomplete="new-password" /></td></tr>
                </table>
                <p><button type="submit" class="button button-primary"><?php esc_html_e('حفظ', 'mallati-theme'); ?></button></p>
            </form>
            <form method="post" style="display:inline;margin-left:8px">
                <?php echo $nonce; ?>
                <input type="hidden" name="mallati_action" value="test_email" />
                <button type="submit" class="button"><?php esc_html_e('إرسال بريد اختبار', 'mallati-theme'); ?></button>
            </form>
            <form method="post" style="display:inline;margin-left:8px">
                <?php echo $nonce; ?>
                <input type="hidden" name="mallati_action" value="restore_contact" />
                <button type="submit" class="button"><?php esc_html_e('استعادة المحتوى الأصلي', 'mallati-theme'); ?></button>
            </form>
        </div>
        <script>
        jQuery(function($){
            $('#mallati_smtp_type').on('change', function(){
                var v = $(this).val();
                $('.mallati-gmail-fields').toggle(v === 'gmail');
                $('.mallati-smtp-fields').toggle(v === 'smtp');
            });
        });
        </script>
        <?php
    }

    public static function render_site_settings() {
        if (!current_user_can('manage_options')) return;
        self::render_notices();
        $nonce = wp_nonce_field('mallati_content', '_wpnonce', true, false);
        $def = self::get_default_colors();
        $colors = [
            'header_bg'    => get_theme_mod('mallati_color_header_bg', $def['header_bg']),
            'footer_bg'    => get_theme_mod('mallati_color_footer_bg', $def['footer_bg']),
            'add_to_cart'  => get_theme_mod('mallati_color_add_to_cart', $def['add_to_cart']),
            'checkout_btn' => get_theme_mod('mallati_color_checkout_btn', $def['checkout_btn']),
            'payment_btn'  => get_theme_mod('mallati_color_payment_btn', $def['payment_btn']),
            'page_bg'      => get_theme_mod('mallati_color_page_bg', $def['page_bg']),
        ];
        $labels = [
            'header_bg'    => __('لون خلفية الهيدر', 'mallati-theme'),
            'footer_bg'    => __('لون خلفية الفوتر', 'mallati-theme'),
            'add_to_cart'  => __('لون زر أضف للسلة', 'mallati-theme'),
            'checkout_btn' => __('لون زر إتمام الشراء', 'mallati-theme'),
            'payment_btn'  => __('لون زر إتمام الدفع', 'mallati-theme'),
            'page_bg'      => __('لون خلفية الصفحات', 'mallati-theme'),
        ];
        ?>
        <div class="wrap mallati-admin-wrap">
            <h1><?php esc_html_e('إعدادات الموقع', 'mallati-theme'); ?></h1>
            <form method="post">
                <?php echo $nonce; ?>
                <input type="hidden" name="mallati_action" value="save_site_settings" />
                <table class="form-table mallati-form-table">
                    <?php foreach ($colors as $key => $val) : ?>
                    <tr>
                        <th><?php echo esc_html($labels[$key]); ?></th>
                        <td>
                            <input type="text" name="mallati_color_<?php echo esc_attr($key); ?>" id="mallati_color_<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($val); ?>" class="mallati-color-picker" data-default-color="<?php echo esc_attr($def[$key]); ?>" />
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <p><button type="submit" class="button button-primary"><?php esc_html_e('حفظ', 'mallati-theme'); ?></button></p>
            </form>
            <form method="post" style="margin-top:16px">
                <?php echo $nonce; ?>
                <input type="hidden" name="mallati_action" value="restore_colors" />
                <button type="submit" class="button"><?php esc_html_e('استعادة الألوان الأصلية', 'mallati-theme'); ?></button>
            </form>
        </div>
        <script>
        jQuery(function($){
            $('.mallati-color-picker').wpColorPicker();
        });
        </script>
        <?php
    }

    private static function render_media_script($btn_class, $input_id, $preview_id, $default_url) {
        ?>
        <script>
        jQuery(function($){
            var frame;
            $('.mallati-upload-btn[data-target="<?php echo esc_js($btn_class); ?>"]').on('click', function(){
                if (frame) frame.open();
                else {
                    frame = wp.media({ library: { type: 'image' }, multiple: false });
                    frame.on('select', function(){
                        var att = frame.state().get('selection').first().toJSON();
                        $('#<?php echo esc_js($input_id); ?>').val(att.id);
                        $('#<?php echo esc_js($preview_id); ?>').attr('src', att.sizes && att.sizes.medium ? att.sizes.medium.url : att.url).show();
                    });
                    frame.open();
                }
            });
            $('.mallati-remove-btn[data-target="<?php echo esc_js($btn_class); ?>"]').on('click', function(){
                $('#<?php echo esc_js($input_id); ?>').val('');
                $('#<?php echo esc_js($preview_id); ?>').attr('src', '<?php echo esc_js($default_url); ?>');
            });
        });
        </script>
        <?php
    }

    private static function render_generic_page($title, $page, $restore_action, $save_action) {
        if (!current_user_can('manage_options')) return;
        self::render_notices();
        $nonce = wp_nonce_field('mallati_content', '_wpnonce', true, false);
        $key = str_replace('mallati-', '', $page);
        $slug_map = ['about' => 'about-us', 'shipping' => 'shipping-policy', 'return' => 'return-policy', 'privacy' => 'privacy-policy'];
        $slug = isset($slug_map[$key]) ? $slug_map[$key] : '';
        $content = '';
        $img_id = 0;
        if ($slug) {
            $p = get_page_by_path($slug);
            $content = $p ? $p->post_content : self::get_default_content($key);
            $img_id = $p ? (int) get_post_meta($p->ID, 'mallati_page_image', true) : 0;
        } else {
            $content = get_option('mallati_content_' . $key, self::get_default_content($key));
        }
        $img_url = $img_id ? wp_get_attachment_image_url($img_id, 'medium') : '';
        $default_img = get_template_directory_uri() . '/mallati/assets/hero1.png';
        ?>
        <div class="wrap mallati-admin-wrap">
            <h1><?php echo esc_html($title); ?></h1>
            <form method="post">
                <?php echo $nonce; ?>
                <input type="hidden" name="mallati_action" value="<?php echo esc_attr($save_action); ?>" />
                <h2><?php esc_html_e('صورة الصفحة (اختياري)', 'mallati-theme'); ?></h2>
                <table class="form-table mallati-form-table">
                    <tr>
                        <th><?php esc_html_e('الصورة', 'mallati-theme'); ?></th>
                        <td>
                            <img class="mallati-image-preview" src="<?php echo esc_url($img_url ?: $default_img); ?>" alt="" id="mallati-page-img-preview" style="max-width:150px;display:block;margin:8px 0;" />
                            <input type="hidden" name="mallati_page_image" id="mallati-page-img-id" value="<?php echo absint($img_id); ?>" />
                            <button type="button" class="button mallati-upload-btn" data-target="mallati-page-img"><?php esc_html_e('اختيار صورة', 'mallati-theme'); ?></button>
                            <button type="button" class="button mallati-remove-btn" data-target="mallati-page-img"><?php esc_html_e('إزالة', 'mallati-theme'); ?></button>
                        </td>
                    </tr>
                </table>
                <h2><?php esc_html_e('المحتوى', 'mallati-theme'); ?></h2>
                <?php
                wp_editor($content, 'mallati_content', [
                    'textarea_name' => 'mallati_content',
                    'textarea_rows' => 15,
                    'media_buttons' => true,
                    'teeny'         => false,
                    'quicktags'     => true,
                ]);
                ?>
                <p><button type="submit" class="button button-primary"><?php esc_html_e('حفظ', 'mallati-theme'); ?></button></p>
            </form>
            <form method="post" style="margin-top:16px">
                <?php echo $nonce; ?>
                <input type="hidden" name="mallati_action" value="<?php echo esc_attr($restore_action); ?>" />
                <button type="submit" class="button"><?php esc_html_e('استعادة المحتوى الأصلي', 'mallati-theme'); ?></button>
            </form>
        </div>
        <?php self::render_media_script('mallati-page-img', 'mallati-page-img-id', 'mallati-page-img-preview', $default_img);
    }

    private static function restore_home() {
        update_option('mallati_hero_slides', [0, 0, 0]);
        update_option('mallati_home_banner', 0);
        update_option('mallati_home_title_best', __('الاكثر مبيعا:', 'mallati-theme'));
        update_option('mallati_home_title_new', __('وصل حديثا:', 'mallati-theme'));
        self::redirect_with_notice(admin_url('admin.php?page=mallati-home'), 'success', __('تم استعادة المحتوى الأصلي.', 'mallati-theme'));
    }
    private static function restore_about() {
        $page = get_page_by_path('about-us');
        if ($page) {
            wp_update_post(['ID' => $page->ID, 'post_content' => self::get_default_content('about')]);
            update_post_meta($page->ID, 'mallati_from_design', 1);
        }
        self::redirect_with_notice(admin_url('admin.php?page=mallati-about'), 'success', __('تم استعادة المحتوى الأصلي.', 'mallati-theme'));
    }
    private static function restore_shipping() {
        $page = get_page_by_path('shipping-policy');
        if ($page) wp_update_post(['ID' => $page->ID, 'post_content' => self::get_default_content('shipping')]);
        self::redirect_with_notice(admin_url('admin.php?page=mallati-shipping'), 'success', __('تم استعادة المحتوى الأصلي.', 'mallati-theme'));
    }
    private static function restore_return() {
        $page = get_page_by_path('return-policy');
        if ($page) wp_update_post(['ID' => $page->ID, 'post_content' => self::get_default_content('return')]);
        self::redirect_with_notice(admin_url('admin.php?page=mallati-return'), 'success', __('تم استعادة المحتوى الأصلي.', 'mallati-theme'));
    }
    private static function restore_privacy() {
        $page = get_page_by_path('privacy-policy');
        if ($page) wp_update_post(['ID' => $page->ID, 'post_content' => self::get_default_content('privacy')]);
        self::redirect_with_notice(admin_url('admin.php?page=mallati-privacy'), 'success', __('تم استعادة المحتوى الأصلي.', 'mallati-theme'));
    }
    private static function restore_footer() {
        set_theme_mod('mallati_footer_logo', 0);
        set_theme_mod('mallati_logo_id', 0);
        set_theme_mod('mallati_footer_phone', '+966 50 000 0000');
        set_theme_mod('mallati_footer_whatsapp', '');
        set_theme_mod('mallati_footer_copyright', sprintf(__('جميع الحقوق محفوظة لـ %s © %d', 'mallati-theme'), get_bloginfo('name'), (int) date('Y')));
        set_theme_mod('mallati_floating_buttons', 0);
        self::redirect_with_notice(admin_url('admin.php?page=mallati-footer'), 'success', __('تم استعادة المحتوى الأصلي.', 'mallati-theme'));
    }
    private static function restore_colors() {
        foreach (self::get_default_colors() as $key => $val) {
            set_theme_mod('mallati_color_' . $key, $val);
        }
        self::redirect_with_notice(admin_url('admin.php?page=mallati-site-settings'), 'success', __('تم استعادة الألوان الأصلية.', 'mallati-theme'));
    }
    private static function restore_contact() {
        set_theme_mod('mallati_address', 'الرياض، المملكة العربية السعودية');
        set_theme_mod('mallati_email', get_option('admin_email'));
        set_theme_mod('mallati_phone', '+966 50 000 0000');
        set_theme_mod('mallati_whatsapp', '');
        set_theme_mod('mallati_map_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3624.006470365758!2d46.675296!3d24.713551!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2f042f4f9b7a23%3A0x9af0b5a24b!2sRiyadh!5e0!3m2!1sar!2ssa!4v1688570000000');
        update_option('mallati_contact_form_title', __('أرسل رسالة', 'mallati-theme'));
        update_option('mallati_contact_form_desc', __('أرسل رسالة إلينا وسنرد عليك في أسرع وقت ممكن.', 'mallati-theme'));
        update_option('mallati_smtp_type', 'gmail');
        self::redirect_with_notice(admin_url('admin.php?page=mallati-contact'), 'success', __('تم استعادة المحتوى الأصلي.', 'mallati-theme'));
    }

    private static function save_home() {
        if (isset($_POST['mallati_hero_slides']) && is_array($_POST['mallati_hero_slides'])) {
            $slides = array_map('absint', $_POST['mallati_hero_slides']);
            update_option('mallati_hero_slides', array_pad($slides, 3, 0));
        }
        if (isset($_POST['mallati_home_banner'])) update_option('mallati_home_banner', absint($_POST['mallati_home_banner']));
        if (isset($_POST['mallati_home_title_best'])) update_option('mallati_home_title_best', sanitize_text_field(wp_unslash($_POST['mallati_home_title_best'])));
        if (isset($_POST['mallati_home_title_new'])) update_option('mallati_home_title_new', sanitize_text_field(wp_unslash($_POST['mallati_home_title_new'])));
        self::redirect_with_notice(admin_url('admin.php?page=mallati-home'), 'success', __('تم الحفظ.', 'mallati-theme'));
    }
    private static function save_about() {
        $page = get_page_by_path('about-us');
        if ($page) {
            if (isset($_POST['mallati_content'])) wp_update_post(['ID' => $page->ID, 'post_content' => wp_kses_post(wp_unslash($_POST['mallati_content']))]);
            if (isset($_POST['mallati_page_image'])) update_post_meta($page->ID, 'mallati_page_image', absint($_POST['mallati_page_image']));
        }
        self::redirect_with_notice(admin_url('admin.php?page=mallati-about'), 'success', __('تم الحفظ.', 'mallati-theme'));
    }
    private static function save_shipping() {
        $page = get_page_by_path('shipping-policy');
        if ($page) {
            if (isset($_POST['mallati_content'])) wp_update_post(['ID' => $page->ID, 'post_content' => wp_kses_post(wp_unslash($_POST['mallati_content']))]);
            if (isset($_POST['mallati_page_image'])) update_post_meta($page->ID, 'mallati_page_image', absint($_POST['mallati_page_image']));
        }
        self::redirect_with_notice(admin_url('admin.php?page=mallati-shipping'), 'success', __('تم الحفظ.', 'mallati-theme'));
    }
    private static function save_return() {
        $page = get_page_by_path('return-policy');
        if ($page) {
            if (isset($_POST['mallati_content'])) wp_update_post(['ID' => $page->ID, 'post_content' => wp_kses_post(wp_unslash($_POST['mallati_content']))]);
            if (isset($_POST['mallati_page_image'])) update_post_meta($page->ID, 'mallati_page_image', absint($_POST['mallati_page_image']));
        }
        self::redirect_with_notice(admin_url('admin.php?page=mallati-return'), 'success', __('تم الحفظ.', 'mallati-theme'));
    }
    private static function save_privacy() {
        $page = get_page_by_path('privacy-policy');
        if ($page) {
            if (isset($_POST['mallati_content'])) wp_update_post(['ID' => $page->ID, 'post_content' => wp_kses_post(wp_unslash($_POST['mallati_content']))]);
            if (isset($_POST['mallati_page_image'])) update_post_meta($page->ID, 'mallati_page_image', absint($_POST['mallati_page_image']));
        }
        self::redirect_with_notice(admin_url('admin.php?page=mallati-privacy'), 'success', __('تم الحفظ.', 'mallati-theme'));
    }
    private static function save_footer() {
        if (isset($_POST['mallati_logo_id'])) set_theme_mod('mallati_footer_logo', absint($_POST['mallati_logo_id']));
        set_theme_mod('mallati_logo_id', get_theme_mod('mallati_footer_logo', 0));
        if (isset($_POST['mallati_footer_phone'])) set_theme_mod('mallati_footer_phone', sanitize_text_field(wp_unslash($_POST['mallati_footer_phone'])));
        if (isset($_POST['mallati_footer_whatsapp'])) set_theme_mod('mallati_footer_whatsapp', sanitize_text_field(wp_unslash($_POST['mallati_footer_whatsapp'])));
        if (isset($_POST['mallati_footer_copyright'])) set_theme_mod('mallati_footer_copyright', sanitize_text_field(wp_unslash($_POST['mallati_footer_copyright'])));
        set_theme_mod('mallati_floating_buttons', !empty($_POST['mallati_floating_buttons']) ? 1 : 0);
        self::redirect_with_notice(admin_url('admin.php?page=mallati-footer'), 'success', __('تم الحفظ.', 'mallati-theme'));
    }
    private static function save_site_settings() {
        $def = self::get_default_colors();
        foreach (array_keys($def) as $key) {
            if (isset($_POST['mallati_color_' . $key])) {
                $v = sanitize_hex_color(wp_unslash($_POST['mallati_color_' . $key]));
                if ($v) set_theme_mod('mallati_color_' . $key, $v);
            }
        }
        self::redirect_with_notice(admin_url('admin.php?page=mallati-site-settings'), 'success', __('تم الحفظ.', 'mallati-theme'));
    }
    private static function save_contact() {
        if (isset($_POST['mallati_address'])) set_theme_mod('mallati_address', sanitize_text_field(wp_unslash($_POST['mallati_address'])));
        if (isset($_POST['mallati_email'])) set_theme_mod('mallati_email', sanitize_email(wp_unslash($_POST['mallati_email'])));
        if (isset($_POST['mallati_phone'])) set_theme_mod('mallati_phone', sanitize_text_field(wp_unslash($_POST['mallati_phone'])));
        if (isset($_POST['mallati_whatsapp'])) set_theme_mod('mallati_whatsapp', sanitize_text_field(wp_unslash($_POST['mallati_whatsapp'])));
        if (isset($_POST['mallati_map_embed'])) set_theme_mod('mallati_map_embed', esc_url_raw(wp_unslash($_POST['mallati_map_embed'])));
        if (isset($_POST['mallati_contact_form_title'])) update_option('mallati_contact_form_title', sanitize_text_field(wp_unslash($_POST['mallati_contact_form_title'])));
        if (isset($_POST['mallati_contact_form_desc'])) update_option('mallati_contact_form_desc', sanitize_text_field(wp_unslash($_POST['mallati_contact_form_desc'])));
        if (isset($_POST['mallati_smtp_type'])) update_option('mallati_smtp_type', sanitize_text_field(wp_unslash($_POST['mallati_smtp_type'])));
        $type = isset($_POST['mallati_smtp_type']) ? sanitize_text_field(wp_unslash($_POST['mallati_smtp_type'])) : 'gmail';
        if ($type === 'gmail') {
            if (isset($_POST['mallati_gmail_email'])) update_option('mallati_smtp_user', sanitize_email(wp_unslash($_POST['mallati_gmail_email'])));
            if (!empty($_POST['mallati_gmail_app_pass'])) update_option('mallati_smtp_pass', sanitize_text_field(wp_unslash($_POST['mallati_gmail_app_pass'])));
            update_option('mallati_smtp_host', 'smtp.gmail.com');
            update_option('mallati_smtp_port', 587);
        } else {
            if (isset($_POST['mallati_smtp_host'])) update_option('mallati_smtp_host', sanitize_text_field(wp_unslash($_POST['mallati_smtp_host'])));
            if (isset($_POST['mallati_smtp_port'])) update_option('mallati_smtp_port', absint($_POST['mallati_smtp_port']) ?: 587);
            if (isset($_POST['mallati_smtp_user'])) update_option('mallati_smtp_user', sanitize_text_field(wp_unslash($_POST['mallati_smtp_user'])));
            if (!empty($_POST['mallati_smtp_pass'])) update_option('mallati_smtp_pass', sanitize_text_field(wp_unslash($_POST['mallati_smtp_pass'])));
        }
        self::redirect_with_notice(admin_url('admin.php?page=mallati-contact'), 'success', __('تم الحفظ.', 'mallati-theme'));
    }
    private static function test_email() {
        $to = get_option('admin_email');
        $sent = wp_mail($to, __('بريد اختبار - مولاتي', 'mallati-theme'), __('هذا بريد اختبار من لوحة تحكم المحتوى.', 'mallati-theme'), array('Content-Type: text/plain; charset=UTF-8'));
        self::redirect_with_notice(admin_url('admin.php?page=mallati-contact'), $sent ? 'success' : 'error', $sent ? __('تم إرسال بريد الاختبار.', 'mallati-theme') : __('فشل إرسال بريد الاختبار.', 'mallati-theme'));
    }
}

Mallati_Admin_Content::init();
