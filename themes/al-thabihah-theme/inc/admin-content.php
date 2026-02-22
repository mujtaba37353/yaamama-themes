<?php

if (!defined('ABSPATH')) {
    exit;
}

function al_thabihah_design_path($relative) {
    return trailingslashit(get_template_directory()) . 'al-thabihah/' . ltrim($relative, '/');
}

function al_thabihah_read_design_file($relative) {
    $path = al_thabihah_design_path($relative);
    if (!file_exists($path)) {
        return '';
    }
    $contents = file_get_contents($path);
    return $contents ? $contents : '';
}

function al_thabihah_dom_xpath($html) {
    if (!$html) {
        return null;
    }
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
    libxml_clear_errors();
    return new DOMXPath($dom);
}

function al_thabihah_node_inner_html($node) {
    $html = '';
    foreach ($node->childNodes as $child) {
        $html .= $node->ownerDocument->saveHTML($child);
    }
    return trim($html);
}

function al_thabihah_extract_data_y($html, $data_y, $index = 0) {
    $xpath = al_thabihah_dom_xpath($html);
    if (!$xpath) {
        return '';
    }
    $nodes = $xpath->query("//*[@data-y='{$data_y}']");
    $node = $nodes->item($index);
    if ($nodes->length === 0 || null === $node) {
        return '';
    }
    return al_thabihah_node_inner_html($node);
}

function al_thabihah_extract_data_y_attr($html, $data_y, $attr, $index = 0) {
    $xpath = al_thabihah_dom_xpath($html);
    if (!$xpath) {
        return '';
    }
    $nodes = $xpath->query("//*[@data-y='{$data_y}']");
    $node = $nodes->item($index);
    if ($nodes->length === 0 || null === $node) {
        return '';
    }
    return $node->getAttribute($attr);
}

function al_thabihah_default_home_settings() {
    $html = al_thabihah_read_design_file('templates/home/layout.html');
    $featured_title = al_thabihah_extract_data_y($html, 'featured-title', 0);
    $offers_title = al_thabihah_extract_data_y($html, 'featured-title', 1);
    $offers_label = al_thabihah_extract_data_y($html, 'category-title-offers');
    $naemi_label = al_thabihah_extract_data_y($html, 'category-title-naemi');
    $tays_label = al_thabihah_extract_data_y($html, 'category-title-tays');
    $ejel_label = al_thabihah_extract_data_y($html, 'category-title-ejel');
    $cuts_label = al_thabihah_extract_data_y($html, 'category-title-cuts');

    return array(
        'hero_title' => al_thabihah_extract_data_y($html, 'hero-title'),
        'hero_subtitle' => al_thabihah_extract_data_y($html, 'hero-subtitle'),
        'category_subtitle' => al_thabihah_extract_data_y($html, 'category-subtitle'),
        'category_title' => al_thabihah_extract_data_y($html, 'category-title'),
        'category_offers_label' => $offers_label ?: 'العروض',
        'category_naemi_label' => $naemi_label ?: 'نعيمي',
        'category_tays_label' => $tays_label ?: 'تيس كشميري',
        'category_ejel_label' => $ejel_label ?: 'عجل',
        'category_cuts_label' => $cuts_label ?: 'قطعيات لحم',
        'featured_title' => $featured_title,
        'offers_title' => $offers_title,
        'promo_title' => al_thabihah_extract_data_y($html, 'promo-banner-title-1'),
        'promo_subtitle' => al_thabihah_extract_data_y($html, 'promo-banner-title-2'),
        'promo_button' => al_thabihah_extract_data_y($html, 'promo-banner-btn'),
        'testimonials_title' => al_thabihah_extract_data_y($html, 'testimonials-title'),
        'hero_image_id' => 0,
        'category_offers_image_id' => 0,
        'category_naemi_image_id' => 0,
        'category_tays_image_id' => 0,
        'category_ejel_image_id' => 0,
        'category_cuts_image_id' => 0,
        'promo_image_id' => 0,
    );
}

function al_thabihah_default_about_settings() {
    $html = al_thabihah_read_design_file('templates/about-us/layout.html');
    return array(
        'content' => al_thabihah_extract_data_y($html, 'about-description'),
        'image_id' => 0,
    );
}

function al_thabihah_default_contact_settings() {
    $html = al_thabihah_read_design_file('templates/contact-us/layout.html');
    return array(
        'content' => '',
        'email' => 'Al-thabihah@gmail.com',
        'phone' => '+966 12 345 6789',
        'whatsapp' => '',
        'image_id' => 0,
    );
}

function al_thabihah_default_footer_settings() {
    $html = al_thabihah_read_design_file('components/y-footer.html');
    return array(
        'header_logo_id' => 0,
        'footer_logo_id' => 0,
        'description' => al_thabihah_extract_data_y($html, 'footer-description-column'),
        'address' => 'الرياض، المملكة العربية السعودية',
        'email' => 'Al-thabihah@gmail.com',
        'phone' => '+966 12 345 6789',
        'whatsapp' => '',
        'floating_phone' => '',
        'floating_whatsapp' => '',
        'floating_enabled' => 0,
    );
}

function al_thabihah_default_colors() {
    return array(
        'header_color' => '#67001a',
        'footer_color' => '#67001a',
        'add_to_cart_color' => '#9a1c20',
        'checkout_color' => '#9a1c20',
        'payment_color' => '#9a1c20',
        'page_background' => '#f7f7f7',
    );
}

function al_thabihah_default_policy_content() {
    $policy_path = trailingslashit(get_template_directory()) . 'template-parts/policy-content.php';
    return file_exists($policy_path) ? file_get_contents($policy_path) : '';
}

function al_thabihah_get_option($key, $default = array()) {
    $value = get_option($key, null);
    if ($value === null || $value === false) {
        return $default;
    }
    return is_array($value) ? $value : $default;
}

function al_thabihah_admin_menu() {
    add_menu_page(
        'المحتوى',
        'المحتوى',
        'manage_options',
        'al-thabihah-content',
        'al_thabihah_admin_pages_index',
        'dashicons-edit',
        25
    );

    add_submenu_page('al-thabihah-content', 'الصفحات', 'الصفحات', 'manage_options', 'al-thabihah-pages', 'al_thabihah_admin_pages_index');
    add_submenu_page('al-thabihah-content', 'منتجات ديمو', 'منتجات ديمو', 'manage_options', 'al-thabihah-demo-products', 'al_thabihah_admin_demo_products');
    add_submenu_page('al-thabihah-content', 'الصفحة الرئيسية', 'الصفحة الرئيسية', 'manage_options', 'al-thabihah-home', 'al_thabihah_admin_home');
    add_submenu_page('al-thabihah-content', 'من نحن', 'من نحن', 'manage_options', 'al-thabihah-about', 'al_thabihah_admin_about');
    add_submenu_page('al-thabihah-content', 'سياسة الشحن', 'سياسة الشحن', 'manage_options', 'al-thabihah-delivery', 'al_thabihah_admin_delivery');
    add_submenu_page('al-thabihah-content', 'سياسة الاسترجاع', 'سياسة الاسترجاع', 'manage_options', 'al-thabihah-replacement', 'al_thabihah_admin_replacement');
    add_submenu_page('al-thabihah-content', 'سياسة الخصوصية', 'سياسة الخصوصية', 'manage_options', 'al-thabihah-privacy', 'al_thabihah_admin_privacy');
    add_submenu_page('al-thabihah-content', 'تواصل معنا', 'تواصل معنا', 'manage_options', 'al-thabihah-contact', 'al_thabihah_admin_contact');
    add_submenu_page('al-thabihah-content', 'الفوتر', 'الفوتر', 'manage_options', 'al-thabihah-footer', 'al_thabihah_admin_footer');
    add_submenu_page('al-thabihah-content', 'إعدادات الموقع', 'إعدادات الموقع', 'manage_options', 'al-thabihah-site-settings', 'al_thabihah_admin_site_settings');
}
add_action('admin_menu', 'al_thabihah_admin_menu');

function al_thabihah_admin_assets($hook) {
    if (strpos($hook, 'al-thabihah') === false) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_style('al-thabihah-admin', al_thabihah_asset_uri('assets/css/admin/content-admin.css'), array(), AL_THABIHAH_THEME_VERSION);
    wp_enqueue_script('al-thabihah-admin', al_thabihah_asset_uri('assets/js/admin/content-admin.js'), array('jquery', 'wp-color-picker'), AL_THABIHAH_THEME_VERSION, true);
}
add_action('admin_enqueue_scripts', 'al_thabihah_admin_assets');

function al_thabihah_admin_notice($message, $type = 'success') {
    set_transient('al_thabihah_admin_notice', array('message' => $message, 'type' => $type), 30);
}

function al_thabihah_render_admin_notice() {
    $notice = get_transient('al_thabihah_admin_notice');
    if (!$notice) {
        return;
    }
    delete_transient('al_thabihah_admin_notice');
    $class = $notice['type'] === 'error' ? 'notice notice-error' : 'notice notice-success';
    echo '<div class="' . esc_attr($class) . '"><p>' . esc_html($notice['message']) . '</p></div>';
}
add_action('admin_notices', 'al_thabihah_render_admin_notice');

function al_thabihah_admin_pages_index() {
    if (!current_user_can('manage_options')) {
        wp_die('غير مصرح');
    }
    settings_errors('al_thabihah_admin');
    ?>
    <div class="wrap">
        <h1>الصفحات</h1>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('al_thabihah_pages_sync', 'al_thabihah_pages_nonce'); ?>
            <input type="hidden" name="action" value="al_thabihah_pages_sync">
            <button class="button button-primary">إنشاء/تحديث كل صفحات الموقع</button>
        </form>

        <?php
        $pages = array('offers', 'contact-us', 'about-us', 'privacy-policy', 'replacement-policy', 'delivery-policy', 'login', 'signup', 'register', 'pass-reset', 'forgot-password', 'reset-password', 'account', 'my-account');
        ?>
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>العنوان</th>
                    <th>slug</th>
                    <th>الحالة</th>
                    <th>آخر تحديث</th>
                    <th>عرض</th>
                    <th>تحرير</th>
                    <th>مصمم؟</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($pages as $slug) :
                $page = get_page_by_path($slug);
                if (!$page) {
                    continue;
                }
                $is_design = get_post_meta($page->ID, 'al_thabihah_from_design', true);
                ?>
                <tr>
                    <td><?php echo esc_html($page->post_title); ?></td>
                    <td><?php echo esc_html($page->post_name); ?></td>
                    <td><?php echo esc_html($page->post_status); ?></td>
                    <td><?php echo esc_html(get_the_modified_date('', $page)); ?></td>
                    <td><a href="<?php echo esc_url(get_permalink($page)); ?>" target="_blank">عرض</a></td>
                    <td><a href="<?php echo esc_url(get_edit_post_link($page->ID)); ?>">تحرير</a></td>
                    <td><?php echo $is_design ? 'نعم' : 'لا'; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

function al_thabihah_render_image_field($label, $name, $attachment_id, $default_url) {
    $preview = $attachment_id ? wp_get_attachment_url($attachment_id) : $default_url;
    ?>
    <div class="al-thabihah-image-field">
        <label><?php echo esc_html($label); ?></label>
        <div class="al-thabihah-image-preview">
            <img src="<?php echo esc_url($preview); ?>" alt="">
        </div>
        <input type="hidden" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($attachment_id); ?>" data-image-id>
        <button type="button" class="button al-thabihah-image-upload">رفع/اختيار صورة</button>
        <button type="button" class="button al-thabihah-image-remove" data-default-url="<?php echo esc_url($default_url); ?>">إزالة</button>
    </div>
    <?php
}

function al_thabihah_admin_home() {
    if (!current_user_can('manage_options')) {
        wp_die('غير مصرح');
    }
    settings_errors('al_thabihah_admin');
    $defaults = al_thabihah_default_home_settings();
    $settings = wp_parse_args(al_thabihah_get_option('al_thabihah_home_settings', array()), $defaults);
    ?>
    <div class="wrap">
        <h1>الصفحة الرئيسية</h1>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('al_thabihah_home_save', 'al_thabihah_home_nonce'); ?>
            <input type="hidden" name="action" value="al_thabihah_home_save">

            <h2>السكشن الأول (الهيرو)</h2>
            <table class="form-table">
                <tr>
                    <th>عنوان الهيرو</th>
                    <td><input type="text" name="hero_title" value="<?php echo esc_attr($settings['hero_title']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>وصف الهيرو</th>
                    <td><input type="text" name="hero_subtitle" value="<?php echo esc_attr($settings['hero_subtitle']); ?>" class="regular-text"></td>
                </tr>
            </table>

            <h2>سكشن الأيقونات</h2>
            <table class="form-table">
                <tr>
                    <th>عنوان قسم الأيقونات</th>
                    <td><input type="text" name="category_title" value="<?php echo esc_attr($settings['category_title']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>وصف قسم الأيقونات</th>
                    <td><input type="text" name="category_subtitle" value="<?php echo esc_attr($settings['category_subtitle']); ?>" class="regular-text"></td>
                </tr>
            </table>

            <h2>الصور</h2>
            <?php
            al_thabihah_render_image_field('صورة الهيرو', 'hero_image_id', (int) $settings['hero_image_id'], al_thabihah_asset_uri('al-thabihah/assets/hero.jpg'));
            ?>
            <h2>نصوص أيقونات التصنيفات</h2>
            <p><input type="text" name="category_offers_label" value="<?php echo esc_attr($settings['category_offers_label']); ?>" class="regular-text" placeholder="نص أيقونة العروض"></p>
            <p><input type="text" name="category_naemi_label" value="<?php echo esc_attr($settings['category_naemi_label']); ?>" class="regular-text" placeholder="نص أيقونة نعيمي"></p>
            <p><input type="text" name="category_tays_label" value="<?php echo esc_attr($settings['category_tays_label']); ?>" class="regular-text" placeholder="نص أيقونة تيس"></p>
            <p><input type="text" name="category_ejel_label" value="<?php echo esc_attr($settings['category_ejel_label']); ?>" class="regular-text" placeholder="نص أيقونة عجل"></p>
            <p><input type="text" name="category_cuts_label" value="<?php echo esc_attr($settings['category_cuts_label']); ?>" class="regular-text" placeholder="نص أيقونة قطعيات"></p>

            <h2>عناوين الأقسام</h2>
            <table class="form-table">
                <tr>
                    <th>عنوان الأكثر طلبًا</th>
                    <td><input type="text" name="featured_title" value="<?php echo esc_attr($settings['featured_title']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>عنوان العروض</th>
                    <td><input type="text" name="offers_title" value="<?php echo esc_attr($settings['offers_title']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>عنوان آراء العملاء</th>
                    <td><input type="text" name="testimonials_title" value="<?php echo esc_attr($settings['testimonials_title']); ?>" class="regular-text"></td>
                </tr>
            </table>

            <h2>البنر الوسط</h2>
            <?php al_thabihah_render_image_field('صورة البنر', 'promo_image_id', (int) $settings['promo_image_id'], al_thabihah_asset_uri('al-thabihah/assets/hero.jpg')); ?>
            <table class="form-table">
                <tr>
                    <th>جملة البنر الرئيسية</th>
                    <td>
                        <input type="text" name="promo_title" value="<?php echo esc_attr($settings['promo_title']); ?>" class="regular-text">
                        <p class="description">تظهر كعنوان رئيسي داخل البنر.</p>
                    </td>
                </tr>
                <tr>
                    <th>الجملة الثانوية</th>
                    <td>
                        <input type="text" name="promo_subtitle" value="<?php echo esc_attr($settings['promo_subtitle']); ?>" class="regular-text">
                        <p class="description">تظهر أسفل العنوان الرئيسي داخل البنر.</p>
                    </td>
                </tr>
                <tr>
                    <th>نص زر البنر</th>
                    <td>
                        <input type="text" name="promo_button" value="<?php echo esc_attr($settings['promo_button']); ?>" class="regular-text">
                        <p class="description">النص المعروض على زر البنر.</p>
                    </td>
                </tr>
            </table>

            <p>
                <button class="button button-primary">حفظ التعديلات</button>
            </p>
        </form>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('al_thabihah_home_restore', 'al_thabihah_home_restore_nonce'); ?>
            <input type="hidden" name="action" value="al_thabihah_home_restore">
            <button class="button">استعادة المحتوى الأصلي</button>
        </form>
    </div>
    <?php
}

function al_thabihah_admin_about() {
    if (!current_user_can('manage_options')) {
        wp_die('غير مصرح');
    }
    settings_errors('al_thabihah_admin');
    $page = get_page_by_path('about-us');
    $content = $page ? $page->post_content : '';
    $defaults = al_thabihah_default_about_settings();
    $settings = wp_parse_args(al_thabihah_get_option('al_thabihah_about_settings', array()), $defaults);
    ?>
    <div class="wrap">
        <h1>من نحن</h1>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('al_thabihah_about_save', 'al_thabihah_about_nonce'); ?>
            <input type="hidden" name="action" value="al_thabihah_about_save">
            <?php wp_editor($content, 'about_content', array('textarea_name' => 'about_content')); ?>

            <?php al_thabihah_render_image_field('صورة من نحن', 'about_image_id', (int) $settings['image_id'], al_thabihah_asset_uri('al-thabihah/assets/about-us.png')); ?>

            <p><button class="button button-primary">حفظ التعديلات</button></p>
        </form>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('al_thabihah_about_restore', 'al_thabihah_about_restore_nonce'); ?>
            <input type="hidden" name="action" value="al_thabihah_about_restore">
            <button class="button">استعادة المحتوى الأصلي</button>
        </form>
    </div>
    <?php
}

function al_thabihah_admin_policy_editor($slug, $title, $restore_action) {
    $page = get_page_by_path($slug);
    $content = $page ? $page->post_content : '';
    ?>
    <div class="wrap">
        <h1><?php echo esc_html($title); ?></h1>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field($restore_action . '_save', $restore_action . '_nonce'); ?>
            <input type="hidden" name="action" value="<?php echo esc_attr($restore_action . '_save'); ?>">
            <?php wp_editor($content, $slug . '_content', array('textarea_name' => 'policy_content')); ?>
            <p><button class="button button-primary">حفظ التعديلات</button></p>
        </form>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field($restore_action . '_restore', $restore_action . '_restore_nonce'); ?>
            <input type="hidden" name="action" value="<?php echo esc_attr($restore_action . '_restore'); ?>">
            <button class="button">استعادة المحتوى الأصلي</button>
        </form>
    </div>
    <?php
}

function al_thabihah_admin_privacy() {
    settings_errors('al_thabihah_admin');
    al_thabihah_admin_policy_editor('privacy-policy', 'سياسة الخصوصية', 'al_thabihah_privacy');
}

function al_thabihah_admin_replacement() {
    settings_errors('al_thabihah_admin');
    al_thabihah_admin_policy_editor('replacement-policy', 'سياسة الاسترجاع', 'al_thabihah_replacement');
}

function al_thabihah_admin_delivery() {
    settings_errors('al_thabihah_admin');
    al_thabihah_admin_policy_editor('delivery-policy', 'سياسة الشحن', 'al_thabihah_delivery');
}

function al_thabihah_admin_contact() {
    if (!current_user_can('manage_options')) {
        wp_die('غير مصرح');
    }
    settings_errors('al_thabihah_admin');
    $page = get_page_by_path('contact-us');
    $content = $page ? $page->post_content : '';
    $defaults = al_thabihah_default_contact_settings();
    $settings = wp_parse_args(al_thabihah_get_option('al_thabihah_contact_settings', array()), $defaults);
    $smtp = al_thabihah_get_option('al_thabihah_smtp_settings', array());
    ?>
    <div class="wrap">
        <h1>تواصل معنا</h1>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('al_thabihah_contact_save', 'al_thabihah_contact_nonce'); ?>
            <input type="hidden" name="action" value="al_thabihah_contact_save">

            <h2>معلومات التواصل</h2>
            <table class="form-table">
                <tr>
                    <th>البريد المعروض</th>
                    <td><input type="email" name="contact_email" value="<?php echo esc_attr($settings['email']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>رقم الجوال</th>
                    <td><input type="text" name="contact_phone" value="<?php echo esc_attr($settings['phone']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>رقم الواتساب</th>
                    <td><input type="text" name="contact_whatsapp" value="<?php echo esc_attr($settings['whatsapp']); ?>" class="regular-text"></td>
                </tr>
            </table>

            <?php al_thabihah_render_image_field('صورة تواصل معنا', 'contact_image_id', (int) $settings['image_id'], al_thabihah_asset_uri('al-thabihah/assets/contact-us.png')); ?>

            <p><button class="button button-primary">حفظ التعديلات</button></p>
        </form>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('al_thabihah_contact_restore', 'al_thabihah_contact_restore_nonce'); ?>
            <input type="hidden" name="action" value="al_thabihah_contact_restore">
            <button class="button">استعادة المحتوى الأصلي</button>
        </form>

        <hr>

        <h2>إعدادات البريد (SMTP)</h2>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('al_thabihah_smtp_save', 'al_thabihah_smtp_nonce'); ?>
            <input type="hidden" name="action" value="al_thabihah_smtp_save">
            <table class="form-table">
                <tr>
                    <th>تفعيل SMTP</th>
                    <td><input type="checkbox" name="smtp_enabled" value="1" <?php checked(1, $smtp['enabled'] ?? 0); ?>></td>
                </tr>
                <tr>
                    <th>نوع الإعداد</th>
                    <td>
                        <select name="smtp_type">
                            <option value="gmail" <?php selected('gmail', $smtp['type'] ?? ''); ?>>Gmail App Password</option>
                            <option value="smtp" <?php selected('smtp', $smtp['type'] ?? ''); ?>>SMTP</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Host</th>
                    <td><input type="text" name="smtp_host" value="<?php echo esc_attr($smtp['host'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>Port</th>
                    <td><input type="number" name="smtp_port" value="<?php echo esc_attr($smtp['port'] ?? ''); ?>" class="small-text"></td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td><input type="text" name="smtp_username" value="<?php echo esc_attr($smtp['username'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>Password</th>
                    <td><input type="password" name="smtp_password" value="<?php echo esc_attr($smtp['password'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>Encryption</th>
                    <td>
                        <select name="smtp_encryption">
                            <option value="" <?php selected('', $smtp['encryption'] ?? ''); ?>>بدون</option>
                            <option value="ssl" <?php selected('ssl', $smtp['encryption'] ?? ''); ?>>SSL</option>
                            <option value="tls" <?php selected('tls', $smtp['encryption'] ?? ''); ?>>TLS</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>From Email</th>
                    <td><input type="email" name="smtp_from_email" value="<?php echo esc_attr($smtp['from_email'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>From Name</th>
                    <td><input type="text" name="smtp_from_name" value="<?php echo esc_attr($smtp['from_name'] ?? ''); ?>" class="regular-text"></td>
                </tr>
            </table>
            <p><button class="button button-primary">حفظ إعدادات SMTP</button></p>
        </form>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('al_thabihah_smtp_test', 'al_thabihah_smtp_test_nonce'); ?>
            <input type="hidden" name="action" value="al_thabihah_smtp_test">
            <p><button class="button">إرسال بريد اختبار</button></p>
        </form>
    </div>
    <?php
}

function al_thabihah_admin_footer() {
    if (!current_user_can('manage_options')) {
        wp_die('غير مصرح');
    }
    settings_errors('al_thabihah_admin');
    $defaults = al_thabihah_default_footer_settings();
    $settings = wp_parse_args(al_thabihah_get_option('al_thabihah_footer_settings', array()), $defaults);
    ?>
    <div class="wrap">
        <h1>الفوتر</h1>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('al_thabihah_footer_save', 'al_thabihah_footer_nonce'); ?>
            <input type="hidden" name="action" value="al_thabihah_footer_save">

            <?php al_thabihah_render_image_field('شعار الهيدر', 'header_logo_id', (int) $settings['header_logo_id'], al_thabihah_asset_uri('al-thabihah/assets/logo.png')); ?>
            <?php al_thabihah_render_image_field('شعار الفوتر', 'footer_logo_id', (int) $settings['footer_logo_id'], al_thabihah_asset_uri('al-thabihah/assets/logo.png')); ?>

            <?php wp_editor($settings['description'], 'footer_description', array('textarea_name' => 'footer_description')); ?>

            <table class="form-table">
                <tr>
                    <th>العنوان</th>
                    <td><input type="text" name="footer_address" value="<?php echo esc_attr($settings['address']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>البريد</th>
                    <td><input type="email" name="footer_email" value="<?php echo esc_attr($settings['email']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>الهاتف</th>
                    <td><input type="text" name="footer_phone" value="<?php echo esc_attr($settings['phone']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>واتساب</th>
                    <td><input type="text" name="footer_whatsapp" value="<?php echo esc_attr($settings['whatsapp']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>تفعيل الأزرار العائمة</th>
                    <td><input type="checkbox" name="floating_enabled" value="1" <?php checked(1, $settings['floating_enabled']); ?>></td>
                </tr>
                <tr>
                    <th>هاتف عائم</th>
                    <td><input type="text" name="floating_phone" value="<?php echo esc_attr($settings['floating_phone']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>واتساب عائم</th>
                    <td><input type="text" name="floating_whatsapp" value="<?php echo esc_attr($settings['floating_whatsapp']); ?>" class="regular-text"></td>
                </tr>
            </table>

            <p><button class="button button-primary">حفظ التعديلات</button></p>
        </form>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('al_thabihah_footer_restore', 'al_thabihah_footer_restore_nonce'); ?>
            <input type="hidden" name="action" value="al_thabihah_footer_restore">
            <button class="button">استعادة المحتوى الأصلي</button>
        </form>
    </div>
    <?php
}

function al_thabihah_admin_site_settings() {
    if (!current_user_can('manage_options')) {
        wp_die('غير مصرح');
    }
    settings_errors('al_thabihah_admin');
    $defaults = al_thabihah_default_colors();
    $colors = wp_parse_args(al_thabihah_get_option('al_thabihah_site_colors', array()), $defaults);
    ?>
    <div class="wrap">
        <h1>إعدادات الموقع</h1>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('al_thabihah_colors_save', 'al_thabihah_colors_nonce'); ?>
            <input type="hidden" name="action" value="al_thabihah_colors_save">
            <?php
            $labels = array(
                'header_color' => 'لون الهيدر',
                'footer_color' => 'لون الفوتر',
                'add_to_cart_color' => 'لون زر إضافة للسلة',
                'checkout_color' => 'لون زر اتمام الشراء',
                'payment_color' => 'لون زر اتمام الدفع',
                'page_background' => 'لون خلفية الصفحات',
            );
            ?>
            <table class="form-table">
                <?php foreach ($colors as $key => $value) : ?>
                    <tr>
                        <th><?php echo esc_html($labels[$key] ?? $key); ?></th>
                        <td><input type="text" class="al-thabihah-color-picker" name="colors[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($value); ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <p><button class="button button-primary">حفظ الألوان</button></p>
        </form>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('al_thabihah_colors_restore', 'al_thabihah_colors_restore_nonce'); ?>
            <input type="hidden" name="action" value="al_thabihah_colors_restore">
            <button class="button">استعادة الألوان الأصلية</button>
        </form>
    </div>
    <?php
}

function al_thabihah_admin_demo_products() {
    if (!current_user_can('manage_options')) {
        wp_die('غير مصرح');
    }
    settings_errors('al_thabihah_admin');
    $categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => false, 'meta_key' => 'al_thabihah_is_demo', 'meta_value' => '1'));
    $products = get_posts(array('post_type' => 'product', 'numberposts' => -1, 'meta_key' => 'al_thabihah_is_demo', 'meta_value' => '1'));
    ?>
    <div class="wrap">
        <h1>منتجات ديمو</h1>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('al_thabihah_demo_actions', 'al_thabihah_demo_nonce'); ?>
            <input type="hidden" name="action" value="al_thabihah_demo_actions">
            <button class="button button-primary" name="demo_action" value="create_all">إنشاء التصنيفات والمنتجات</button>
            <button class="button" name="demo_action" value="delete_products">حذف منتجات الديمو</button>
        </form>

        <h2>التصنيفات</h2>
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>slug</th>
                    <th>العدد</th>
                    <th>تاريخ الإنشاء</th>
                    <th>تحرير</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $term) : ?>
                    <tr>
                        <td><?php echo esc_html($term->name); ?></td>
                        <td><?php echo esc_html($term->slug); ?></td>
                        <td><?php echo esc_html($term->count); ?></td>
                        <td><?php echo esc_html(get_term_meta($term->term_id, 'al_thabihah_demo_created', true)); ?></td>
                        <td><a href="<?php echo esc_url(get_edit_term_link($term)); ?>">تحرير</a></td>
                        <td>
                            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                                <?php wp_nonce_field('al_thabihah_demo_actions', 'al_thabihah_demo_nonce'); ?>
                                <input type="hidden" name="action" value="al_thabihah_demo_actions">
                                <input type="hidden" name="term_id" value="<?php echo esc_attr($term->term_id); ?>">
                                <button class="button" name="demo_action" value="delete_category" onclick="return confirm('هل أنت متأكد من حذف التصنيف؟');">حذف</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>المنتجات</h2>
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>الاسم</th>
                    <th>SKU</th>
                    <th>السعر</th>
                    <th>التصنيف</th>
                    <th>الحالة</th>
                    <th>تحرير</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product_post) :
                    $product = wc_get_product($product_post->ID);
                    ?>
                    <tr>
                        <td><?php echo $product ? $product->get_image('thumbnail') : ''; ?></td>
                        <td><?php echo esc_html($product_post->post_title); ?></td>
                        <td><?php echo esc_html($product ? $product->get_sku() : ''); ?></td>
                        <td><?php echo esc_html($product ? $product->get_price() : ''); ?></td>
                        <td><?php echo esc_html(join(', ', wp_get_post_terms($product_post->ID, 'product_cat', array('fields' => 'names')))); ?></td>
                        <td><?php echo esc_html($product_post->post_status); ?></td>
                        <td><a href="<?php echo esc_url(get_edit_post_link($product_post->ID)); ?>">تحرير</a></td>
                        <td>
                            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                                <?php wp_nonce_field('al_thabihah_demo_actions', 'al_thabihah_demo_nonce'); ?>
                                <input type="hidden" name="action" value="al_thabihah_demo_actions">
                                <input type="hidden" name="product_id" value="<?php echo esc_attr($product_post->ID); ?>">
                                <button class="button" name="demo_action" value="delete_product">حذف</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

function al_thabihah_create_or_update_page($slug, $title, $content, $template) {
    $page = get_page_by_path($slug);
    if (!$page) {
        $page_id = wp_insert_post(array(
            'post_title' => $title,
            'post_name' => $slug,
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_content' => $content,
        ));
    } else {
        $page_id = $page->ID;
        wp_update_post(array(
            'ID' => $page_id,
            'post_content' => $content,
        ));
    }
    if ($page_id && !is_wp_error($page_id)) {
        update_post_meta($page_id, '_wp_page_template', $template);
        update_post_meta($page_id, 'al_thabihah_from_design', 1);
    }
}

function al_thabihah_handle_pages_sync() {
    if (!current_user_can('manage_options')) {
        wp_die('غير مصرح');
    }
    check_admin_referer('al_thabihah_pages_sync', 'al_thabihah_pages_nonce');
    al_thabihah_create_or_update_page('about-us', 'من نحن', al_thabihah_extract_data_y(al_thabihah_read_design_file('templates/about-us/layout.html'), 'about-description'), 'page-templates/about-us.php');
    al_thabihah_create_or_update_page('contact-us', 'تواصل معنا', '', 'page-templates/contact-us.php');
    $policy = al_thabihah_default_policy_content();
    al_thabihah_create_or_update_page('privacy-policy', 'سياسة الخصوصية', $policy, 'page-templates/privacy-policy.php');
    al_thabihah_create_or_update_page('replacement-policy', 'سياسة الاسترجاع', $policy, 'page-templates/replacement-policy.php');
    al_thabihah_create_or_update_page('delivery-policy', 'سياسة الشحن', $policy, 'page-templates/delivery-policy.php');
    al_thabihah_create_or_update_page('offers', 'العروض', '', 'page-templates/offers.php');
    al_thabihah_create_or_update_page('login', 'تسجيل الدخول', '', 'page-templates/login.php');
    al_thabihah_create_or_update_page('signup', 'حساب جديد', '', 'page-templates/signup.php');
    al_thabihah_create_or_update_page('register', 'تسجيل', '', 'page-templates/signup.php');
    al_thabihah_create_or_update_page('pass-reset', 'استعادة كلمة المرور', '', 'page-templates/pass-reset.php');
    al_thabihah_create_or_update_page('forgot-password', 'نسيت كلمة المرور', '', 'page-templates/pass-reset.php');
    al_thabihah_create_or_update_page('reset-password', 'إعادة تعيين كلمة المرور', '', 'page-templates/reset-password.php');
    al_thabihah_create_or_update_page('account', 'حسابي', '', 'page-templates/account.php');
    al_thabihah_create_or_update_page('my-account', 'حسابي', '', 'page-templates/account.php');

    if (function_exists('wc_get_page_id')) {
        $my_page = get_page_by_path('my-account');
        if ($my_page) {
            update_option('woocommerce_myaccount_page_id', $my_page->ID);
        } else {
            $acc_page = get_page_by_path('account');
            if ($acc_page) {
                update_option('woocommerce_myaccount_page_id', $acc_page->ID);
            }
        }
    }

    al_thabihah_admin_notice('تم تحديث الصفحات بنجاح', 'success');
    wp_safe_redirect(admin_url('admin.php?page=al-thabihah-pages'));
    exit;
}
add_action('admin_post_al_thabihah_pages_sync', 'al_thabihah_handle_pages_sync');

function al_thabihah_save_home() {
    if (!current_user_can('manage_options')) {
        wp_die('غير مصرح');
    }
    check_admin_referer('al_thabihah_home_save', 'al_thabihah_home_nonce');
    $defaults = al_thabihah_default_home_settings();
    $settings = array(
        'hero_title' => sanitize_text_field($_POST['hero_title'] ?? $defaults['hero_title']),
        'hero_subtitle' => sanitize_text_field($_POST['hero_subtitle'] ?? $defaults['hero_subtitle']),
        'category_subtitle' => sanitize_text_field($_POST['category_subtitle'] ?? $defaults['category_subtitle']),
        'category_title' => sanitize_text_field($_POST['category_title'] ?? $defaults['category_title']),
        'category_offers_label' => sanitize_text_field($_POST['category_offers_label'] ?? $defaults['category_offers_label']),
        'category_naemi_label' => sanitize_text_field($_POST['category_naemi_label'] ?? $defaults['category_naemi_label']),
        'category_tays_label' => sanitize_text_field($_POST['category_tays_label'] ?? $defaults['category_tays_label']),
        'category_ejel_label' => sanitize_text_field($_POST['category_ejel_label'] ?? $defaults['category_ejel_label']),
        'category_cuts_label' => sanitize_text_field($_POST['category_cuts_label'] ?? $defaults['category_cuts_label']),
        'featured_title' => sanitize_text_field($_POST['featured_title'] ?? $defaults['featured_title']),
        'offers_title' => sanitize_text_field($_POST['offers_title'] ?? $defaults['offers_title']),
        'promo_title' => sanitize_text_field($_POST['promo_title'] ?? $defaults['promo_title']),
        'promo_subtitle' => sanitize_text_field($_POST['promo_subtitle'] ?? $defaults['promo_subtitle']),
        'promo_button' => sanitize_text_field($_POST['promo_button'] ?? $defaults['promo_button']),
        'testimonials_title' => sanitize_text_field($_POST['testimonials_title'] ?? $defaults['testimonials_title']),
        'hero_image_id' => absint($_POST['hero_image_id'] ?? 0),
        'category_offers_image_id' => absint($_POST['category_offers_image_id'] ?? 0),
        'category_naemi_image_id' => absint($_POST['category_naemi_image_id'] ?? 0),
        'category_tays_image_id' => absint($_POST['category_tays_image_id'] ?? 0),
        'category_ejel_image_id' => absint($_POST['category_ejel_image_id'] ?? 0),
        'category_cuts_image_id' => absint($_POST['category_cuts_image_id'] ?? 0),
        'promo_image_id' => absint($_POST['promo_image_id'] ?? 0),
    );
    update_option('al_thabihah_home_settings', $settings);
    al_thabihah_admin_notice('تم حفظ الصفحة الرئيسية', 'success');
    wp_safe_redirect(admin_url('admin.php?page=al-thabihah-home'));
    exit;
}
add_action('admin_post_al_thabihah_home_save', 'al_thabihah_save_home');

function al_thabihah_restore_home() {
    if (!current_user_can('manage_options')) {
        wp_die('غير مصرح');
    }
    check_admin_referer('al_thabihah_home_restore', 'al_thabihah_home_restore_nonce');
    update_option('al_thabihah_home_settings', al_thabihah_default_home_settings());
    al_thabihah_admin_notice('تم استعادة المحتوى الأصلي', 'success');
    wp_safe_redirect(admin_url('admin.php?page=al-thabihah-home'));
    exit;
}
add_action('admin_post_al_thabihah_home_restore', 'al_thabihah_restore_home');

function al_thabihah_save_about() {
    if (!current_user_can('manage_options')) {
        wp_die('غير مصرح');
    }
    check_admin_referer('al_thabihah_about_save', 'al_thabihah_about_nonce');
    $page = get_page_by_path('about-us');
    if ($page) {
        wp_update_post(array('ID' => $page->ID, 'post_content' => wp_kses_post($_POST['about_content'] ?? '')));
    }
    update_option('al_thabihah_about_settings', array(
        'image_id' => absint($_POST['about_image_id'] ?? 0),
    ));
    al_thabihah_admin_notice('تم حفظ صفحة من نحن', 'success');
    wp_safe_redirect(admin_url('admin.php?page=al-thabihah-about'));
    exit;
}
add_action('admin_post_al_thabihah_about_save', 'al_thabihah_save_about');

function al_thabihah_restore_about() {
    check_admin_referer('al_thabihah_about_restore', 'al_thabihah_about_restore_nonce');
    $defaults = al_thabihah_default_about_settings();
    $page = get_page_by_path('about-us');
    if ($page) {
        wp_update_post(array('ID' => $page->ID, 'post_content' => wp_kses_post($defaults['content'])));
    }
    update_option('al_thabihah_about_settings', array('image_id' => 0));
    al_thabihah_admin_notice('تم استعادة محتوى من نحن', 'success');
    wp_safe_redirect(admin_url('admin.php?page=al-thabihah-about'));
    exit;
}
add_action('admin_post_al_thabihah_about_restore', 'al_thabihah_restore_about');

function al_thabihah_save_policy($slug, $action) {
    $page = get_page_by_path($slug);
    if ($page) {
        wp_update_post(array('ID' => $page->ID, 'post_content' => wp_kses_post($_POST['policy_content'] ?? '')));
        update_post_meta($page->ID, 'al_thabihah_from_design', 1);
    }
    al_thabihah_admin_notice('تم حفظ المحتوى', 'success');
    wp_safe_redirect(admin_url('admin.php?page=' . str_replace('al_thabihah_', 'al-thabihah-', $action)));
    exit;
}

function al_thabihah_restore_policy($slug, $redirect_page) {
    $page = get_page_by_path($slug);
    $content = al_thabihah_default_policy_content();
    if ($page) {
        wp_update_post(array('ID' => $page->ID, 'post_content' => wp_kses_post($content)));
    }
    al_thabihah_admin_notice('تم استعادة المحتوى الأصلي', 'success');
    wp_safe_redirect(admin_url('admin.php?page=' . $redirect_page));
    exit;
}

function al_thabihah_privacy_save() { check_admin_referer('al_thabihah_privacy_save', 'al_thabihah_privacy_nonce'); al_thabihah_save_policy('privacy-policy', 'al_thabihah_privacy'); }
function al_thabihah_privacy_restore() { check_admin_referer('al_thabihah_privacy_restore', 'al_thabihah_privacy_restore_nonce'); al_thabihah_restore_policy('privacy-policy', 'al-thabihah-privacy'); }
function al_thabihah_replacement_save() { check_admin_referer('al_thabihah_replacement_save', 'al_thabihah_replacement_nonce'); al_thabihah_save_policy('replacement-policy', 'al_thabihah_replacement'); }
function al_thabihah_replacement_restore() { check_admin_referer('al_thabihah_replacement_restore', 'al_thabihah_replacement_restore_nonce'); al_thabihah_restore_policy('replacement-policy', 'al-thabihah-replacement'); }
function al_thabihah_delivery_save() { check_admin_referer('al_thabihah_delivery_save', 'al_thabihah_delivery_nonce'); al_thabihah_save_policy('delivery-policy', 'al_thabihah_delivery'); }
function al_thabihah_delivery_restore() { check_admin_referer('al_thabihah_delivery_restore', 'al_thabihah_delivery_restore_nonce'); al_thabihah_restore_policy('delivery-policy', 'al-thabihah-delivery'); }

add_action('admin_post_al_thabihah_privacy_save', 'al_thabihah_privacy_save');
add_action('admin_post_al_thabihah_privacy_restore', 'al_thabihah_privacy_restore');
add_action('admin_post_al_thabihah_replacement_save', 'al_thabihah_replacement_save');
add_action('admin_post_al_thabihah_replacement_restore', 'al_thabihah_replacement_restore');
add_action('admin_post_al_thabihah_delivery_save', 'al_thabihah_delivery_save');
add_action('admin_post_al_thabihah_delivery_restore', 'al_thabihah_delivery_restore');

function al_thabihah_save_contact() {
    check_admin_referer('al_thabihah_contact_save', 'al_thabihah_contact_nonce');
    $page = get_page_by_path('contact-us');
    if ($page) {
        wp_update_post(array('ID' => $page->ID, 'post_content' => ''));
    }
    update_option('al_thabihah_contact_settings', array(
        'email' => sanitize_email($_POST['contact_email'] ?? ''),
        'phone' => sanitize_text_field($_POST['contact_phone'] ?? ''),
        'whatsapp' => sanitize_text_field($_POST['contact_whatsapp'] ?? ''),
        'image_id' => absint($_POST['contact_image_id'] ?? 0),
    ));
    al_thabihah_admin_notice('تم حفظ تواصل معنا', 'success');
    wp_safe_redirect(admin_url('admin.php?page=al-thabihah-contact'));
    exit;
}
add_action('admin_post_al_thabihah_contact_save', 'al_thabihah_save_contact');

function al_thabihah_restore_contact() {
    check_admin_referer('al_thabihah_contact_restore', 'al_thabihah_contact_restore_nonce');
    $defaults = al_thabihah_default_contact_settings();
    $page = get_page_by_path('contact-us');
    if ($page) {
        wp_update_post(array('ID' => $page->ID, 'post_content' => ''));
    }
    update_option('al_thabihah_contact_settings', $defaults);
    al_thabihah_admin_notice('تم استعادة محتوى تواصل معنا', 'success');
    wp_safe_redirect(admin_url('admin.php?page=al-thabihah-contact'));
    exit;
}
add_action('admin_post_al_thabihah_contact_restore', 'al_thabihah_restore_contact');

function al_thabihah_save_footer() {
    check_admin_referer('al_thabihah_footer_save', 'al_thabihah_footer_nonce');
    update_option('al_thabihah_footer_settings', array(
        'header_logo_id' => absint($_POST['header_logo_id'] ?? 0),
        'footer_logo_id' => absint($_POST['footer_logo_id'] ?? 0),
        'description' => wp_kses_post($_POST['footer_description'] ?? ''),
        'address' => sanitize_text_field($_POST['footer_address'] ?? ''),
        'email' => sanitize_email($_POST['footer_email'] ?? ''),
        'phone' => sanitize_text_field($_POST['footer_phone'] ?? ''),
        'whatsapp' => sanitize_text_field($_POST['footer_whatsapp'] ?? ''),
        'floating_enabled' => isset($_POST['floating_enabled']) ? 1 : 0,
        'floating_phone' => sanitize_text_field($_POST['floating_phone'] ?? ''),
        'floating_whatsapp' => sanitize_text_field($_POST['floating_whatsapp'] ?? ''),
    ));
    al_thabihah_admin_notice('تم حفظ الفوتر', 'success');
    wp_safe_redirect(admin_url('admin.php?page=al-thabihah-footer'));
    exit;
}
add_action('admin_post_al_thabihah_footer_save', 'al_thabihah_save_footer');

function al_thabihah_restore_footer() {
    check_admin_referer('al_thabihah_footer_restore', 'al_thabihah_footer_restore_nonce');
    update_option('al_thabihah_footer_settings', al_thabihah_default_footer_settings());
    al_thabihah_admin_notice('تم استعادة محتوى الفوتر', 'success');
    wp_safe_redirect(admin_url('admin.php?page=al-thabihah-footer'));
    exit;
}
add_action('admin_post_al_thabihah_footer_restore', 'al_thabihah_restore_footer');

function al_thabihah_save_colors() {
    check_admin_referer('al_thabihah_colors_save', 'al_thabihah_colors_nonce');
    $defaults = al_thabihah_default_colors();
    $colors = $_POST['colors'] ?? array();
    $sanitized = array();
    foreach ($defaults as $key => $value) {
        $sanitized[$key] = sanitize_hex_color($colors[$key] ?? $value) ?: $value;
    }
    update_option('al_thabihah_site_colors', $sanitized);
    al_thabihah_admin_notice('تم حفظ الألوان', 'success');
    wp_safe_redirect(admin_url('admin.php?page=al-thabihah-site-settings'));
    exit;
}
add_action('admin_post_al_thabihah_colors_save', 'al_thabihah_save_colors');

function al_thabihah_restore_colors() {
    check_admin_referer('al_thabihah_colors_restore', 'al_thabihah_colors_restore_nonce');
    update_option('al_thabihah_site_colors', al_thabihah_default_colors());
    al_thabihah_admin_notice('تم استعادة الألوان الأصلية', 'success');
    wp_safe_redirect(admin_url('admin.php?page=al-thabihah-site-settings'));
    exit;
}
add_action('admin_post_al_thabihah_colors_restore', 'al_thabihah_restore_colors');

function al_thabihah_save_smtp() {
    check_admin_referer('al_thabihah_smtp_save', 'al_thabihah_smtp_nonce');
    update_option('al_thabihah_smtp_settings', array(
        'enabled' => isset($_POST['smtp_enabled']) ? 1 : 0,
        'type' => sanitize_text_field($_POST['smtp_type'] ?? 'smtp'),
        'host' => sanitize_text_field($_POST['smtp_host'] ?? ''),
        'port' => absint($_POST['smtp_port'] ?? 0),
        'username' => sanitize_text_field($_POST['smtp_username'] ?? ''),
        'password' => sanitize_text_field($_POST['smtp_password'] ?? ''),
        'encryption' => sanitize_text_field($_POST['smtp_encryption'] ?? ''),
        'from_email' => sanitize_email($_POST['smtp_from_email'] ?? ''),
        'from_name' => sanitize_text_field($_POST['smtp_from_name'] ?? ''),
    ));
    al_thabihah_admin_notice('تم حفظ إعدادات SMTP', 'success');
    wp_safe_redirect(admin_url('admin.php?page=al-thabihah-contact'));
    exit;
}
add_action('admin_post_al_thabihah_smtp_save', 'al_thabihah_save_smtp');

function al_thabihah_test_smtp() {
    check_admin_referer('al_thabihah_smtp_test', 'al_thabihah_smtp_test_nonce');
    $settings = al_thabihah_get_option('al_thabihah_smtp_settings', array());
    $to = $settings['from_email'] ?? get_option('admin_email');
    $sent = wp_mail($to, 'اختبار SMTP', 'رسالة اختبار من قالب الذبيحة');
    al_thabihah_admin_notice($sent ? 'تم إرسال بريد الاختبار بنجاح' : 'فشل إرسال بريد الاختبار', $sent ? 'success' : 'error');
    wp_safe_redirect(admin_url('admin.php?page=al-thabihah-contact'));
    exit;
}
add_action('admin_post_al_thabihah_smtp_test', 'al_thabihah_test_smtp');

function al_thabihah_parse_demo_products() {
    $js = al_thabihah_read_design_file('js/products.js');
    if (!$js) {
        return array();
    }
    preg_match_all('/\{([^}]+)\}/', $js, $matches);
    $products = array();
    foreach ($matches[1] as $block) {
        if (!preg_match('/id:\s*(\d+)/', $block, $id_match)) {
            continue;
        }
        $id = (int) $id_match[1];
        preg_match('/name:\s*"([^"]+)"/', $block, $name_match);
        preg_match('/price:\s*([0-9.]+)/', $block, $price_match);
        preg_match('/category:\s*"([^"]+)"/', $block, $cat_match);
        preg_match('/image:\s*"([^"]+)"/', $block, $img_match);
        preg_match('/oldPrice:\s*([0-9.]+)/', $block, $old_match);
        $products[] = array(
            'source_id' => $id,
            'name' => $name_match[1] ?? 'منتج',
            'price' => isset($price_match[1]) ? (float) $price_match[1] : 0,
            'category' => $cat_match[1] ?? 'offers',
            'image' => $img_match[1] ?? '/assets/product.jpg',
            'old_price' => isset($old_match[1]) ? (float) $old_match[1] : 0,
        );
    }
    return $products;
}

function al_thabihah_demo_image_id($relative) {
    $path = al_thabihah_design_path(ltrim($relative, '/'));
    if (!file_exists($path)) {
        return 0;
    }
    $attachment = get_page_by_path(basename($path), OBJECT, 'attachment');
    if ($attachment) {
        return $attachment->ID;
    }
    $filetype = wp_check_filetype(basename($path), null);
    $upload_dir = wp_upload_dir();
    $destination = $upload_dir['path'] . '/' . basename($path);
    if (!file_exists($destination)) {
        copy($path, $destination);
    }
    $attachment_id = wp_insert_attachment(array(
        'guid' => $upload_dir['url'] . '/' . basename($path),
        'post_mime_type' => $filetype['type'],
        'post_title' => sanitize_file_name(basename($path)),
        'post_status' => 'inherit',
    ), $destination);
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $attach_data = wp_generate_attachment_metadata($attachment_id, $destination);
    wp_update_attachment_metadata($attachment_id, $attach_data);
    return $attachment_id;
}

function al_thabihah_demo_categories_seed() {
    return array(
        array('name' => 'لحوم بالكيلو', 'slug' => 'cuts'),
        array('name' => 'مفروم', 'slug' => 'minced'),
        array('name' => 'نعيمي', 'slug' => 'naemi'),
        array('name' => 'تيس كشميري', 'slug' => 'tays'),
        array('name' => 'عجل', 'slug' => 'ejel'),
        array('name' => 'مجهز للشواء', 'slug' => 'bbq'),
        array('name' => 'العروض', 'slug' => 'offers'),
    );
}

function al_thabihah_demo_category_images() {
    return array(
        'offers' => 'assets/offers.png',
        'naemi' => 'assets/sheep.png',
        'tays' => 'assets/tees.png',
        'ejel' => 'assets/cow.png',
        'cuts' => 'assets/meat.png',
        'minced' => 'assets/meat.png',
    );
}

function al_thabihah_ensure_demo_categories() {
    $category_map = array();
    $image_map = al_thabihah_demo_category_images();
    foreach (al_thabihah_demo_categories_seed() as $cat) {
        $term = term_exists($cat['slug'], 'product_cat');
        if (!$term) {
            $term = wp_insert_term($cat['name'], 'product_cat', array('slug' => $cat['slug']));
        }
        if (!is_wp_error($term)) {
            $term_id = is_array($term) ? (int) $term['term_id'] : (int) $term;
            update_term_meta($term_id, 'al_thabihah_is_demo', 1);
            if (!get_term_meta($term_id, 'al_thabihah_demo_created', true)) {
                update_term_meta($term_id, 'al_thabihah_demo_created', current_time('mysql'));
            }
            if (!empty($image_map[$cat['slug']])) {
                $image_id = al_thabihah_demo_image_id($image_map[$cat['slug']]);
                if ($image_id) {
                    update_term_meta($term_id, 'thumbnail_id', $image_id);
                }
            }
            $category_map[$cat['slug']] = $term_id;
        }
    }
    return $category_map;
}

function al_thabihah_create_demo_products($products, $demo_categories) {
    if (empty($products)) {
        return;
    }
    $demo_term_ids = array_values($demo_categories);
    $demo_count = count($demo_term_ids);
    $demo_index = 0;

    foreach ($products as $item) {
        $existing = get_posts(array(
            'post_type' => 'product',
            'meta_key' => 'al_thabihah_demo_source_id',
            'meta_value' => $item['source_id'],
            'numberposts' => 1,
        ));
        if ($existing) {
            $product_id = $existing[0]->ID;
        } else {
            $product_id = wp_insert_post(array(
                'post_title' => $item['name'],
                'post_status' => 'publish',
                'post_type' => 'product',
            ));
        }
        if (!$product_id) {
            continue;
        }
        update_post_meta($product_id, 'al_thabihah_is_demo', 1);
        update_post_meta($product_id, 'al_thabihah_demo_source_id', $item['source_id']);

        $has_explicit_sale = $item['old_price'] > 0;
        $should_force_sale = !$has_explicit_sale && ($item['category'] === 'offers' || ($item['source_id'] % 5 === 0));
        $regular_price = (float) $item['price'];
        $sale_price = 0;

        if ($has_explicit_sale) {
            $regular_price = (float) $item['old_price'];
            $sale_price = (float) $item['price'];
        } elseif ($should_force_sale) {
            $regular_price = round(((float) $item['price']) * 1.15, 2);
            $sale_price = (float) $item['price'];
        }

        update_post_meta($product_id, '_regular_price', $regular_price);
        if ($sale_price > 0) {
            update_post_meta($product_id, '_sale_price', $sale_price);
            update_post_meta($product_id, '_price', $sale_price);
        } else {
            delete_post_meta($product_id, '_sale_price');
            update_post_meta($product_id, '_price', $regular_price);
        }
        if (function_exists('wc_delete_product_transients')) {
            wc_delete_product_transients($product_id);
        }
        if (function_exists('wc_update_product_lookup_tables')) {
            wc_update_product_lookup_tables($product_id);
        }

        if ($demo_count > 0) {
            $term_id = $demo_term_ids[$demo_index % $demo_count];
            $demo_index++;
            wp_set_post_terms($product_id, array((int) $term_id), 'product_cat');
        }

        $image_id = al_thabihah_demo_image_id(ltrim($item['image'], '/'));
        if ($image_id) {
            set_post_thumbnail($product_id, $image_id);
        }
    }
}

function al_thabihah_handle_demo_actions() {
    if (!current_user_can('manage_options')) {
        wp_die('غير مصرح');
    }
    check_admin_referer('al_thabihah_demo_actions', 'al_thabihah_demo_nonce');
    $action = sanitize_text_field($_POST['demo_action'] ?? '');

    if ($action === 'create_categories') {
        al_thabihah_ensure_demo_categories();
        al_thabihah_admin_notice('تم إنشاء التصنيفات', 'success');
    }

    if ($action === 'create_all') {
        $demo_categories = al_thabihah_ensure_demo_categories();
        $products = al_thabihah_parse_demo_products();
        al_thabihah_create_demo_products($products, $demo_categories);
        al_thabihah_admin_notice('تم إنشاء التصنيفات والمنتجات', 'success');
    }

    if ($action === 'create_products') {
        $demo_categories = al_thabihah_ensure_demo_categories();
        $products = al_thabihah_parse_demo_products();
        al_thabihah_create_demo_products($products, $demo_categories);
        al_thabihah_admin_notice('تم إنشاء المنتجات', 'success');
    }

    if ($action === 'delete_products') {
        $products = get_posts(array('post_type' => 'product', 'numberposts' => -1, 'meta_key' => 'al_thabihah_is_demo', 'meta_value' => '1'));
        foreach ($products as $product) {
            wp_delete_post($product->ID, true);
        }
        al_thabihah_admin_notice('تم حذف منتجات الديمو', 'success');
    }

    if ($action === 'delete_product') {
        $product_id = absint($_POST['product_id'] ?? 0);
        if ($product_id) {
            wp_delete_post($product_id, true);
            al_thabihah_admin_notice('تم حذف المنتج', 'success');
        }
    }

    if ($action === 'delete_category') {
        $term_id = absint($_POST['term_id'] ?? 0);
        if ($term_id) {
            $term = get_term($term_id, 'product_cat');
            if ($term && $term->count > 0) {
                al_thabihah_admin_notice('لا يمكن حذف التصنيف لأنه مرتبط بمنتجات', 'error');
            } else {
                wp_delete_term($term_id, 'product_cat');
                al_thabihah_admin_notice('تم حذف التصنيف', 'success');
            }
        }
    }

    wp_safe_redirect(admin_url('admin.php?page=al-thabihah-demo-products'));
    exit;
}
add_action('admin_post_al_thabihah_demo_actions', 'al_thabihah_handle_demo_actions');

function al_thabihah_apply_smtp($phpmailer) {
    $settings = al_thabihah_get_option('al_thabihah_smtp_settings', array());
    if (empty($settings['enabled'])) {
        return;
    }
    $phpmailer->isSMTP();
    $phpmailer->Host = $settings['host'] ?? '';
    $phpmailer->Port = $settings['port'] ?? 587;
    $phpmailer->SMTPAuth = true;
    $phpmailer->Username = $settings['username'] ?? '';
    $phpmailer->Password = $settings['password'] ?? '';
    if (!empty($settings['encryption'])) {
        $phpmailer->SMTPSecure = $settings['encryption'];
    }
    if (!empty($settings['from_email'])) {
        $phpmailer->setFrom($settings['from_email'], $settings['from_name'] ?? '');
    }
}
add_action('phpmailer_init', 'al_thabihah_apply_smtp');
