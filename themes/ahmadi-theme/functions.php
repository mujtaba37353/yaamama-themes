<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

require_once get_template_directory() . '/inc/ydash-bridge.php';

function ahmadi_theme_asset(string $path): string
{
    $path = ltrim($path, '/');
    return get_template_directory_uri() . '/ahmadi-store/' . $path;
}

function ahmadi_theme_find_attachment_url_by_filename(string $filename): string
{
    $filename = trim($filename);
    if ($filename === '') {
        return '';
    }

    static $cache = [];
    if (array_key_exists($filename, $cache)) {
        return $cache[$filename];
    }

    $basename = basename($filename);
    $sanitized = sanitize_file_name($basename);

    $attachment_ids = get_posts([
        'post_type' => 'attachment',
        'posts_per_page' => 1,
        'post_status' => 'inherit',
        'fields' => 'ids',
        'meta_query' => [
            'relation' => 'OR',
            [
                'key' => '_ahmadi_demo_source',
                'value' => $basename,
                'compare' => '=',
            ],
            [
                'key' => '_wp_attached_file',
                'value' => $sanitized,
                'compare' => 'LIKE',
            ],
            [
                'key' => '_wp_attached_file',
                'value' => $basename,
                'compare' => 'LIKE',
            ],
        ],
    ]);

    $attachment_id = $attachment_ids ? (int) $attachment_ids[0] : 0;
    $url = $attachment_id ? (string) wp_get_attachment_url($attachment_id) : '';
    $cache[$filename] = $url;

    return $url;
}

function ahmadi_theme_design_image_url(string $filename): string
{
    $filename = ltrim($filename, '/');
    $theme_path = get_template_directory() . '/ahmadi-store/assets/' . $filename;
    if (file_exists($theme_path)) {
        return ahmadi_theme_normalize_media_url(ahmadi_theme_asset('assets/' . $filename));
    }

    $attachment_url = ahmadi_theme_find_attachment_url_by_filename($filename);
    if ($attachment_url !== '') {
        return ahmadi_theme_normalize_media_url($attachment_url);
    }

    return ahmadi_theme_normalize_media_url(ahmadi_theme_asset('assets/' . $filename));
}

function ahmadi_theme_get_term_image_url(int $term_id): string
{
    $external = get_term_meta($term_id, 'ahmadi_category_image_url', true);
    if (is_string($external) && $external !== '') {
        return ahmadi_theme_normalize_media_url($external);
    }

    $value = get_term_meta($term_id, 'thumbnail_id', true);
    if (is_numeric($value)) {
        $attachment_url = wp_get_attachment_url((int) $value);
        if (is_string($attachment_url) && $attachment_url !== '') {
            return ahmadi_theme_normalize_media_url($attachment_url);
        }
    }

    if (is_string($value) && $value !== '' && !ctype_digit($value)) {
        return ahmadi_theme_normalize_media_url($value);
    }

    return '';
}

function ahmadi_theme_get_external_product_image_url(int $product_id): string
{
    $thumbnail_meta = get_post_meta($product_id, '_thumbnail_id', true);
    if (is_string($thumbnail_meta) && $thumbnail_meta !== '' && !ctype_digit($thumbnail_meta)) {
        return ahmadi_theme_normalize_media_url($thumbnail_meta);
    }

    $external = get_post_meta($product_id, 'ahmadi_product_image_url', true);
    if (is_string($external) && $external !== '') {
        return ahmadi_theme_normalize_media_url($external);
    }

    $external = get_post_meta($product_id, '_external_image_url', true);
    if (is_string($external) && $external !== '') {
        return ahmadi_theme_normalize_media_url($external);
    }

    return '';
}

function ahmadi_theme_get_product_image_html(WC_Product $product, string $size = 'woocommerce_thumbnail'): string
{
    $external_url = ahmadi_theme_get_external_product_image_url($product->get_id());
    if ($external_url !== '') {
        $alt = $product->get_name();
        return sprintf(
            '<img src="%1$s" alt="%2$s" loading="lazy">',
            esc_url($external_url),
            esc_attr($alt)
        );
    }

    $image_id = $product->get_image_id();
    if ($image_id) {
        return wp_get_attachment_image($image_id, $size, false, ['loading' => 'lazy']);
    }

    return wc_placeholder_img($size);
}

function ahmadi_theme_normalize_media_url(string $value): string
{
    $value = trim($value);
    if ($value === '') {
        return '';
    }

    if (ctype_digit($value)) {
        $attachment_url = wp_get_attachment_url((int) $value);
        if (is_string($attachment_url) && $attachment_url !== '') {
            $value = $attachment_url;
        }
    }

    return str_replace(' ', '%20', $value);
}

function ahmadi_theme_page_url(string $slug): string
{
    $slug = trim($slug, '/');
    $aliases = [
        'shop-archive' => 'shop',
    ];
    if (isset($aliases[$slug])) {
        $slug = $aliases[$slug];
    }
    if ($slug === '') {
        return home_url('/');
    }
    return home_url('/' . $slug . '/');
}

function ahmadi_theme_replace_asset_paths(string $html): string
{
    $asset_base = ahmadi_theme_asset('assets/');
    $html = str_replace('"/assets/', '"' . $asset_base, $html);
    $html = str_replace("'/assets/", "'" . $asset_base, $html);
    $html = str_replace('url(/assets/', 'url(' . $asset_base, $html);
    return $html;
}

function ahmadi_theme_replace_page_links(string $html): string
{
    $routes = [
        'home' => '',
        'shop-archive' => 'shop',
        'shop' => 'shop',
        'product-single' => 'product-single',
        'cart' => 'cart',
        'account' => 'account',
        'favorite' => 'favorite',
        'contact-us' => 'contact-us',
        'about-us' => 'about-us',
        'login' => 'login',
        'signup' => 'signup',
        'forget-password' => 'forget-password',
        'password-confirm' => 'password-confirm',
        'payment' => 'payment',
        'privacy' => 'privacy',
        'replacement' => 'replacement',
    ];

    foreach ($routes as $key => $slug) {
        $url = ahmadi_theme_page_url($slug);
        $html = str_replace('/templates/' . $key . '/layout.html', $url, $html);
        $html = str_replace('../' . $key . '/layout.html', $url, $html);
        $html = str_replace('./' . $key . '/layout.html', $url, $html);
    }

    return $html;
}

function ahmadi_theme_render_template(string $template_path): void
{
    $full_path = get_template_directory() . '/ahmadi-store/' . ltrim($template_path, '/');
    if (!file_exists($full_path)) {
        return;
    }

    $html = file_get_contents($full_path);
    if ($html === false) {
        return;
    }

    if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $matches)) {
        $html = $matches[1];
    }

    $html = preg_replace('/<div class="y-c-sale-banner">.*?<\/div>/is', '', $html);
    $html = preg_replace('/<div id="header-placeholder"[^>]*><\/div>/is', '', $html);
    $html = preg_replace('/<div id="footer-placeholder"[^>]*><\/div>/is', '', $html);
    $html = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $html);

    $html = ahmadi_theme_replace_asset_paths($html);
    $html = ahmadi_theme_replace_page_links($html);

    echo $html;
}

function ahmadi_theme_get_latest_post(string $post_type): ?WP_Post
{
    $posts = get_posts([
        'post_type' => $post_type,
        'posts_per_page' => 1,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    ]);

    return $posts ? $posts[0] : null;
}

function ahmadi_theme_get_site_logo_url(): string
{
    $logo_post = ahmadi_theme_get_latest_post('ahmadi_site_logo');
    $logo_url = $logo_post ? get_post_meta($logo_post->ID, 'ahmadi_site_logo_url', true) : '';
    if (!is_string($logo_url) || $logo_url === '') {
        return ahmadi_theme_asset('assets/Frame 5.png');
    }
    return $logo_url;
}

function ahmadi_theme_register_site_content_menu(): void
{
    add_menu_page(
        'محتوى الموقع',
        'محتوى الموقع',
        'edit_posts',
        'ahmadi-site-content',
        '',
        'dashicons-welcome-write-blog',
        58
    );
}

add_action('admin_menu', 'ahmadi_theme_register_site_content_menu');

function ahmadi_theme_register_pages_tools_menu(): void
{
    add_submenu_page(
        'ahmadi-site-content',
        'إضافة الصفحات جميعا',
        'إضافة الصفحات جميعا',
        'manage_options',
        'ahmadi-add-pages',
        'ahmadi_theme_render_add_pages_screen'
    );
}

add_action('admin_menu', 'ahmadi_theme_register_pages_tools_menu');

function ahmadi_theme_render_add_pages_screen(): void
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $action_url = admin_url('admin-post.php');
    $notice = '';
    if (isset($_GET['ahmadi_pages_updated'])) {
        $notice = '<div class="notice notice-success is-dismissible"><p>تمت إضافة الصفحات أو تحديثها بنجاح.</p></div>';
    }

    echo '<div class="wrap">';
    echo '<h1>إضافة الصفحات جميعا</h1>';
    echo $notice;
    echo '<form method="post" action="' . esc_url($action_url) . '">';
    wp_nonce_field('ahmadi_theme_add_pages');
    echo '<input type="hidden" name="action" value="ahmadi_theme_add_pages">';
    submit_button('إضافة الصفحات جميعا');
    echo '</form>';
    echo '</div>';
}

function ahmadi_theme_handle_add_pages(): void
{
    if (!current_user_can('manage_options')) {
        wp_die('لا تملك الصلاحيات اللازمة لتنفيذ هذا الإجراء.');
    }

    check_admin_referer('ahmadi_theme_add_pages');
    ahmadi_theme_ensure_pages();

    $redirect = wp_get_referer();
    if (!is_string($redirect) || $redirect === '') {
        $redirect = admin_url('admin.php?page=ahmadi-add-pages');
    }

    wp_safe_redirect(add_query_arg('ahmadi_pages_updated', '1', $redirect));
    exit;
}

add_action('admin_post_ahmadi_theme_add_pages', 'ahmadi_theme_handle_add_pages');

function ahmadi_theme_register_site_content_types(): void
{
    $common_args = [
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'ahmadi-site-content',
        'supports' => ['title'],
        'capability_type' => 'post',
        'hierarchical' => false,
    ];

    register_post_type('ahmadi_site_notice', array_merge($common_args, [
        'labels' => [
            'name' => 'شريط الخصم',
            'singular_name' => 'شريط الخصم',
            'add_new_item' => 'إضافة شريط خصم',
            'edit_item' => 'تعديل شريط الخصم',
        ],
    ]));

    register_post_type('ahmadi_site_logo', array_merge($common_args, [
        'labels' => [
            'name' => 'شعار الموقع',
            'singular_name' => 'شعار الموقع',
            'add_new_item' => 'إضافة شعار الموقع',
            'edit_item' => 'تعديل شعار الموقع',
        ],
    ]));

    register_post_type('ahmadi_home_hero', array_merge($common_args, [
        'labels' => [
            'name' => 'الرئيسية - البنر الأول',
            'singular_name' => 'بنر رئيسي',
            'add_new_item' => 'إضافة بنر رئيسي',
            'edit_item' => 'تعديل البنر الرئيسي',
        ],
    ]));

    register_post_type('ahmadi_home_brand', array_merge($common_args, [
        'labels' => [
            'name' => 'الرئيسية - البراندات',
            'singular_name' => 'براند',
            'add_new_item' => 'إضافة براند',
            'edit_item' => 'تعديل البراند',
        ],
        'supports' => ['title', 'page-attributes'],
    ]));

    register_post_type('ahmadi_home_promo', array_merge($common_args, [
        'labels' => [
            'name' => 'الرئيسية - البنرات البرومو',
            'singular_name' => 'بنر برومو',
            'add_new_item' => 'إضافة بنر برومو',
            'edit_item' => 'تعديل بنر برومو',
        ],
        'supports' => ['title', 'page-attributes'],
    ]));

    register_post_type('ahmadi_home_middle', array_merge($common_args, [
        'labels' => [
            'name' => 'الرئيسية - البنر الأوسط',
            'singular_name' => 'بنر قبل قسم حولنا',
            'add_new_item' => 'إضافة بنر',
            'edit_item' => 'تعديل البنر',
        ],
    ]));

    register_post_type('ahmadi_contact_content', array_merge($common_args, [
        'labels' => [
            'name' => 'تواصل معنا',
            'singular_name' => 'تواصل معنا',
            'add_new_item' => 'إضافة محتوى تواصل معنا',
            'edit_item' => 'تعديل محتوى تواصل معنا',
        ],
    ]));

    register_post_type('ahmadi_about_content', array_merge($common_args, [
        'labels' => [
            'name' => 'من نحن',
            'singular_name' => 'من نحن',
            'add_new_item' => 'إضافة محتوى من نحن',
            'edit_item' => 'تعديل محتوى من نحن',
        ],
    ]));

    register_post_type('ahmadi_footer_about', array_merge($common_args, [
        'labels' => [
            'name' => 'الفوتر - حولنا',
            'singular_name' => 'حولنا (فوتر)',
            'add_new_item' => 'إضافة محتوى الفوتر',
            'edit_item' => 'تعديل محتوى الفوتر',
        ],
    ]));
}

add_action('init', 'ahmadi_theme_register_site_content_types');

function ahmadi_theme_render_text_input(string $name, string $label, string $value = '', string $type = 'text', string $placeholder = ''): void
{
    printf(
        '<p><label for="%1$s"><strong>%2$s</strong></label><br><input class="widefat" type="%3$s" id="%1$s" name="%1$s" value="%4$s" placeholder="%5$s"></p>',
        esc_attr($name),
        esc_html($label),
        esc_attr($type),
        esc_attr($value),
        esc_attr($placeholder)
    );
}

function ahmadi_theme_render_textarea(string $name, string $label, string $value = '', string $placeholder = ''): void
{
    printf(
        '<p><label for="%1$s"><strong>%2$s</strong></label><br><textarea class="widefat" rows="4" id="%1$s" name="%1$s" placeholder="%3$s">%4$s</textarea></p>',
        esc_attr($name),
        esc_html($label),
        esc_attr($placeholder),
        esc_textarea($value)
    );
}

function ahmadi_theme_render_media_input(string $name, string $label, string $value = ''): void
{
    printf(
        '<p><label for="%1$s"><strong>%2$s</strong></label><br><input class="widefat ahmadi-media-field" type="text" id="%1$s" name="%1$s" value="%3$s" placeholder="https://"><button class="button ahmadi-media-upload" data-target="%1$s" type="button">اختيار صورة</button></p>',
        esc_attr($name),
        esc_html($label),
        esc_attr($value)
    );
}

function ahmadi_theme_add_meta_boxes(): void
{
    add_meta_box('ahmadi-site-notice', 'بيانات شريط الخصم', 'ahmadi_theme_notice_meta_box', 'ahmadi_site_notice', 'normal', 'default');
    add_meta_box('ahmadi-site-logo', 'بيانات شعار الموقع', 'ahmadi_theme_site_logo_meta_box', 'ahmadi_site_logo', 'normal', 'default');
    add_meta_box('ahmadi-home-hero', 'بيانات البنر الرئيسي', 'ahmadi_theme_home_hero_meta_box', 'ahmadi_home_hero', 'normal', 'default');
    add_meta_box('ahmadi-home-brand', 'بيانات البراند', 'ahmadi_theme_home_brand_meta_box', 'ahmadi_home_brand', 'normal', 'default');
    add_meta_box('ahmadi-home-promo', 'بيانات البنر البرومو', 'ahmadi_theme_home_promo_meta_box', 'ahmadi_home_promo', 'normal', 'default');
    add_meta_box('ahmadi-home-middle', 'بيانات البنر قبل قسم حولنا', 'ahmadi_theme_home_middle_meta_box', 'ahmadi_home_middle', 'normal', 'default');
    add_meta_box('ahmadi-contact-content', 'بيانات صفحة تواصل معنا', 'ahmadi_theme_contact_meta_box', 'ahmadi_contact_content', 'normal', 'default');
    add_meta_box('ahmadi-about-content', 'بيانات صفحة من نحن', 'ahmadi_theme_about_meta_box', 'ahmadi_about_content', 'normal', 'default');
    add_meta_box('ahmadi-footer-about', 'بيانات حولنا (الفوتر)', 'ahmadi_theme_footer_meta_box', 'ahmadi_footer_about', 'normal', 'default');
    add_meta_box('ahmadi-product-external-image', 'صورة المنتج الخارجية', 'ahmadi_theme_product_external_image_meta_box', 'product', 'side', 'default');
}

add_action('add_meta_boxes', 'ahmadi_theme_add_meta_boxes');

function ahmadi_theme_notice_meta_box(WP_Post $post): void
{
    wp_nonce_field('ahmadi_site_notice_meta', 'ahmadi_site_notice_nonce');
    $value = get_post_meta($post->ID, 'ahmadi_notice_text', true);
    ahmadi_theme_render_text_input('ahmadi_notice_text', 'نص الشريط', $value, 'text', 'خصم 20% على رسوم التوصيل في كامل الموقع');
}

function ahmadi_theme_site_logo_meta_box(WP_Post $post): void
{
    wp_nonce_field('ahmadi_site_logo_meta', 'ahmadi_site_logo_nonce');
    $value = get_post_meta($post->ID, 'ahmadi_site_logo_url', true);
    ahmadi_theme_render_media_input('ahmadi_site_logo_url', 'صورة الشعار', $value);
}

function ahmadi_theme_home_hero_meta_box(WP_Post $post): void
{
    wp_nonce_field('ahmadi_home_hero_meta', 'ahmadi_home_hero_nonce');
    ahmadi_theme_render_text_input('ahmadi_hero_badge', 'شعار الخصم', get_post_meta($post->ID, 'ahmadi_hero_badge', true));
    ahmadi_theme_render_textarea('ahmadi_hero_title', 'العنوان الرئيسي', get_post_meta($post->ID, 'ahmadi_hero_title', true));
    ahmadi_theme_render_textarea('ahmadi_hero_subtitle', 'النص الوصفي', get_post_meta($post->ID, 'ahmadi_hero_subtitle', true));
    ahmadi_theme_render_text_input('ahmadi_hero_cta_text', 'نص الزر', get_post_meta($post->ID, 'ahmadi_hero_cta_text', true));
    ahmadi_theme_render_text_input('ahmadi_hero_cta_url', 'رابط الزر', get_post_meta($post->ID, 'ahmadi_hero_cta_url', true), 'url');
    ahmadi_theme_render_media_input('ahmadi_hero_image', 'صورة البنر', get_post_meta($post->ID, 'ahmadi_hero_image', true));
    ahmadi_theme_render_text_input('ahmadi_hero_bg_color', 'لون الخلفية', get_post_meta($post->ID, 'ahmadi_hero_bg_color', true), 'color');
}

function ahmadi_theme_home_brand_meta_box(WP_Post $post): void
{
    wp_nonce_field('ahmadi_home_brand_meta', 'ahmadi_home_brand_nonce');
    ahmadi_theme_render_media_input('ahmadi_brand_image', 'صورة البراند', get_post_meta($post->ID, 'ahmadi_brand_image', true));
    ahmadi_theme_render_text_input('ahmadi_brand_alt', 'النص البديل', get_post_meta($post->ID, 'ahmadi_brand_alt', true));
}

function ahmadi_theme_home_promo_meta_box(WP_Post $post): void
{
    wp_nonce_field('ahmadi_home_promo_meta', 'ahmadi_home_promo_nonce');
    ahmadi_theme_render_text_input('ahmadi_promo_title', 'العنوان', get_post_meta($post->ID, 'ahmadi_promo_title', true));
    ahmadi_theme_render_textarea('ahmadi_promo_subtitle', 'النص الوصفي', get_post_meta($post->ID, 'ahmadi_promo_subtitle', true));
    ahmadi_theme_render_text_input('ahmadi_promo_cta_text', 'نص الرابط', get_post_meta($post->ID, 'ahmadi_promo_cta_text', true));
    ahmadi_theme_render_text_input('ahmadi_promo_cta_url', 'رابط الزر', get_post_meta($post->ID, 'ahmadi_promo_cta_url', true), 'url');
    ahmadi_theme_render_media_input('ahmadi_promo_image', 'صورة البرومو', get_post_meta($post->ID, 'ahmadi_promo_image', true));
    ahmadi_theme_render_text_input('ahmadi_promo_gradient_start', 'لون متدرج (بداية)', get_post_meta($post->ID, 'ahmadi_promo_gradient_start', true), 'color');
    ahmadi_theme_render_text_input('ahmadi_promo_gradient_end', 'لون متدرج (نهاية)', get_post_meta($post->ID, 'ahmadi_promo_gradient_end', true), 'color');
}

function ahmadi_theme_home_middle_meta_box(WP_Post $post): void
{
    wp_nonce_field('ahmadi_home_middle_meta', 'ahmadi_home_middle_nonce');
    ahmadi_theme_render_text_input('ahmadi_middle_badge', 'شعار الخصم', get_post_meta($post->ID, 'ahmadi_middle_badge', true));
    ahmadi_theme_render_textarea('ahmadi_middle_title', 'العنوان', get_post_meta($post->ID, 'ahmadi_middle_title', true));
    ahmadi_theme_render_textarea('ahmadi_middle_subtitle', 'النص الوصفي', get_post_meta($post->ID, 'ahmadi_middle_subtitle', true));
    ahmadi_theme_render_text_input('ahmadi_middle_cta_text', 'نص الزر', get_post_meta($post->ID, 'ahmadi_middle_cta_text', true));
    ahmadi_theme_render_text_input('ahmadi_middle_cta_url', 'رابط الزر', get_post_meta($post->ID, 'ahmadi_middle_cta_url', true), 'url');
    ahmadi_theme_render_media_input('ahmadi_middle_left_image', 'صورة يسار', get_post_meta($post->ID, 'ahmadi_middle_left_image', true));
    ahmadi_theme_render_media_input('ahmadi_middle_center_image', 'صورة وسط', get_post_meta($post->ID, 'ahmadi_middle_center_image', true));
    ahmadi_theme_render_media_input('ahmadi_middle_right_image', 'صورة يمين', get_post_meta($post->ID, 'ahmadi_middle_right_image', true));
}

function ahmadi_theme_product_external_image_meta_box(WP_Post $post): void
{
    wp_nonce_field('ahmadi_product_external_image', 'ahmadi_product_external_image_nonce');
    ahmadi_theme_render_text_input(
        'ahmadi_product_image_url',
        'رابط صورة المنتج (خارجي)',
        get_post_meta($post->ID, 'ahmadi_product_image_url', true),
        'url',
        'https://'
    );
}

function ahmadi_theme_contact_meta_box(WP_Post $post): void
{
    wp_nonce_field('ahmadi_contact_meta', 'ahmadi_contact_nonce');
    ahmadi_theme_render_text_input('ahmadi_contact_hero_title', 'عنوان الهيدر', get_post_meta($post->ID, 'ahmadi_contact_hero_title', true));
    ahmadi_theme_render_text_input('ahmadi_contact_intro_title', 'عنوان المقدمة', get_post_meta($post->ID, 'ahmadi_contact_intro_title', true));
    ahmadi_theme_render_textarea('ahmadi_contact_intro_text', 'نص المقدمة', get_post_meta($post->ID, 'ahmadi_contact_intro_text', true));
    ahmadi_theme_render_text_input('ahmadi_contact_whatsapp_title', 'عنوان الواتساب', get_post_meta($post->ID, 'ahmadi_contact_whatsapp_title', true));
    ahmadi_theme_render_text_input('ahmadi_contact_whatsapp_link', 'رابط الواتساب', get_post_meta($post->ID, 'ahmadi_contact_whatsapp_link', true), 'url');
    ahmadi_theme_render_text_input('ahmadi_contact_map_name', 'اسم الموقع', get_post_meta($post->ID, 'ahmadi_contact_map_name', true));
    ahmadi_theme_render_text_input('ahmadi_contact_map_address', 'عنوان الموقع', get_post_meta($post->ID, 'ahmadi_contact_map_address', true));
    ahmadi_theme_render_text_input('ahmadi_contact_map_link', 'رابط الخريطة', get_post_meta($post->ID, 'ahmadi_contact_map_link', true), 'url');
    ahmadi_theme_render_textarea('ahmadi_contact_address_text', 'نص العنوان', get_post_meta($post->ID, 'ahmadi_contact_address_text', true));
    ahmadi_theme_render_text_input('ahmadi_contact_phone_text', 'رقم الهاتف', get_post_meta($post->ID, 'ahmadi_contact_phone_text', true));
    ahmadi_theme_render_text_input('ahmadi_contact_email_one', 'البريد الإلكتروني الأول', get_post_meta($post->ID, 'ahmadi_contact_email_one', true));
    ahmadi_theme_render_text_input('ahmadi_contact_email_two', 'البريد الإلكتروني الثاني', get_post_meta($post->ID, 'ahmadi_contact_email_two', true));
    ahmadi_theme_render_text_input('ahmadi_contact_hours_line_one', 'سطر الدوام الأول', get_post_meta($post->ID, 'ahmadi_contact_hours_line_one', true));
    ahmadi_theme_render_text_input('ahmadi_contact_hours_line_two', 'سطر الدوام الثاني', get_post_meta($post->ID, 'ahmadi_contact_hours_line_two', true));
}

function ahmadi_theme_about_meta_box(WP_Post $post): void
{
    wp_nonce_field('ahmadi_about_meta', 'ahmadi_about_nonce');
    ahmadi_theme_render_text_input('ahmadi_about_title', 'عنوان الصفحة', get_post_meta($post->ID, 'ahmadi_about_title', true));
    ahmadi_theme_render_textarea('ahmadi_about_text', 'نص من نحن', get_post_meta($post->ID, 'ahmadi_about_text', true));
    ahmadi_theme_render_text_input('ahmadi_about_trust_title', 'عنوان لماذا يثق بنا', get_post_meta($post->ID, 'ahmadi_about_trust_title', true));
    ahmadi_theme_render_textarea('ahmadi_about_trust_items', 'نقاط الثقة (كل سطر نقطة)', get_post_meta($post->ID, 'ahmadi_about_trust_items', true));
    ahmadi_theme_render_text_input('ahmadi_about_counter_1_number', 'عداد 1 رقم', get_post_meta($post->ID, 'ahmadi_about_counter_1_number', true));
    ahmadi_theme_render_text_input('ahmadi_about_counter_1_label', 'عداد 1 نص', get_post_meta($post->ID, 'ahmadi_about_counter_1_label', true));
    ahmadi_theme_render_text_input('ahmadi_about_counter_2_number', 'عداد 2 رقم', get_post_meta($post->ID, 'ahmadi_about_counter_2_number', true));
    ahmadi_theme_render_text_input('ahmadi_about_counter_2_label', 'عداد 2 نص', get_post_meta($post->ID, 'ahmadi_about_counter_2_label', true));
    ahmadi_theme_render_text_input('ahmadi_about_counter_3_number', 'عداد 3 رقم', get_post_meta($post->ID, 'ahmadi_about_counter_3_number', true));
    ahmadi_theme_render_text_input('ahmadi_about_counter_3_label', 'عداد 3 نص', get_post_meta($post->ID, 'ahmadi_about_counter_3_label', true));
    ahmadi_theme_render_text_input('ahmadi_about_counter_4_number', 'عداد 4 رقم', get_post_meta($post->ID, 'ahmadi_about_counter_4_number', true));
    ahmadi_theme_render_text_input('ahmadi_about_counter_4_label', 'عداد 4 نص', get_post_meta($post->ID, 'ahmadi_about_counter_4_label', true));
}

function ahmadi_theme_footer_meta_box(WP_Post $post): void
{
    wp_nonce_field('ahmadi_footer_meta', 'ahmadi_footer_nonce');
    ahmadi_theme_render_text_input('ahmadi_footer_title', 'عنوان القسم', get_post_meta($post->ID, 'ahmadi_footer_title', true));
    ahmadi_theme_render_textarea('ahmadi_footer_text', 'نص حولنا', get_post_meta($post->ID, 'ahmadi_footer_text', true));
}

function ahmadi_theme_save_site_content_meta(int $post_id, WP_Post $post): void
{
    $meta_map = [
        'ahmadi_site_notice' => [
            'nonce' => 'ahmadi_site_notice_nonce',
            'action' => 'ahmadi_site_notice_meta',
            'fields' => [
                'ahmadi_notice_text' => 'text',
            ],
        ],
        'ahmadi_site_logo' => [
            'nonce' => 'ahmadi_site_logo_nonce',
            'action' => 'ahmadi_site_logo_meta',
            'fields' => [
                'ahmadi_site_logo_url' => 'url',
            ],
        ],
        'ahmadi_home_hero' => [
            'nonce' => 'ahmadi_home_hero_nonce',
            'action' => 'ahmadi_home_hero_meta',
            'fields' => [
                'ahmadi_hero_badge' => 'text',
                'ahmadi_hero_title' => 'textarea',
                'ahmadi_hero_subtitle' => 'textarea',
                'ahmadi_hero_cta_text' => 'text',
                'ahmadi_hero_cta_url' => 'url',
                'ahmadi_hero_image' => 'url',
                'ahmadi_hero_bg_color' => 'text',
            ],
        ],
        'ahmadi_home_brand' => [
            'nonce' => 'ahmadi_home_brand_nonce',
            'action' => 'ahmadi_home_brand_meta',
            'fields' => [
                'ahmadi_brand_image' => 'url',
                'ahmadi_brand_alt' => 'text',
            ],
        ],
        'ahmadi_home_promo' => [
            'nonce' => 'ahmadi_home_promo_nonce',
            'action' => 'ahmadi_home_promo_meta',
            'fields' => [
                'ahmadi_promo_title' => 'text',
                'ahmadi_promo_subtitle' => 'textarea',
                'ahmadi_promo_cta_text' => 'text',
                'ahmadi_promo_cta_url' => 'url',
                'ahmadi_promo_image' => 'url',
                'ahmadi_promo_gradient_start' => 'text',
                'ahmadi_promo_gradient_end' => 'text',
            ],
        ],
        'ahmadi_home_middle' => [
            'nonce' => 'ahmadi_home_middle_nonce',
            'action' => 'ahmadi_home_middle_meta',
            'fields' => [
                'ahmadi_middle_badge' => 'text',
                'ahmadi_middle_title' => 'textarea',
                'ahmadi_middle_subtitle' => 'textarea',
                'ahmadi_middle_cta_text' => 'text',
                'ahmadi_middle_cta_url' => 'url',
                'ahmadi_middle_left_image' => 'url',
                'ahmadi_middle_center_image' => 'url',
                'ahmadi_middle_right_image' => 'url',
            ],
        ],
        'ahmadi_contact_content' => [
            'nonce' => 'ahmadi_contact_nonce',
            'action' => 'ahmadi_contact_meta',
            'fields' => [
                'ahmadi_contact_hero_title' => 'text',
                'ahmadi_contact_intro_title' => 'text',
                'ahmadi_contact_intro_text' => 'textarea',
                'ahmadi_contact_whatsapp_title' => 'text',
                'ahmadi_contact_whatsapp_link' => 'url',
                'ahmadi_contact_map_name' => 'text',
                'ahmadi_contact_map_address' => 'text',
                'ahmadi_contact_map_link' => 'url',
                'ahmadi_contact_address_text' => 'textarea',
                'ahmadi_contact_phone_text' => 'text',
                'ahmadi_contact_email_one' => 'text',
                'ahmadi_contact_email_two' => 'text',
                'ahmadi_contact_hours_line_one' => 'text',
                'ahmadi_contact_hours_line_two' => 'text',
            ],
        ],
        'ahmadi_about_content' => [
            'nonce' => 'ahmadi_about_nonce',
            'action' => 'ahmadi_about_meta',
            'fields' => [
                'ahmadi_about_title' => 'text',
                'ahmadi_about_text' => 'textarea',
                'ahmadi_about_trust_title' => 'text',
                'ahmadi_about_trust_items' => 'textarea',
                'ahmadi_about_counter_1_number' => 'text',
                'ahmadi_about_counter_1_label' => 'text',
                'ahmadi_about_counter_2_number' => 'text',
                'ahmadi_about_counter_2_label' => 'text',
                'ahmadi_about_counter_3_number' => 'text',
                'ahmadi_about_counter_3_label' => 'text',
                'ahmadi_about_counter_4_number' => 'text',
                'ahmadi_about_counter_4_label' => 'text',
            ],
        ],
        'ahmadi_footer_about' => [
            'nonce' => 'ahmadi_footer_nonce',
            'action' => 'ahmadi_footer_meta',
            'fields' => [
                'ahmadi_footer_title' => 'text',
                'ahmadi_footer_text' => 'textarea',
            ],
        ],
    ];

    if (!isset($meta_map[$post->post_type])) {
        return;
    }

    $config = $meta_map[$post->post_type];
    if (!isset($_POST[$config['nonce']]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[$config['nonce']])), $config['action'])) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    foreach ($config['fields'] as $field => $type) {
        $value = $_POST[$field] ?? '';
        $value = is_string($value) ? wp_unslash($value) : '';
        switch ($type) {
            case 'url':
                $value = esc_url_raw($value);
                break;
            case 'textarea':
                $value = sanitize_textarea_field($value);
                break;
            default:
                $value = sanitize_text_field($value);
        }
        if ($value === '') {
            delete_post_meta($post_id, $field);
        } else {
            update_post_meta($post_id, $field, $value);
        }
    }
}

function ahmadi_theme_save_product_external_image(int $post_id, WP_Post $post): void
{
    if ($post->post_type !== 'product') {
        return;
    }
    if (!isset($_POST['ahmadi_product_external_image_nonce'])) {
        return;
    }
    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ahmadi_product_external_image_nonce'])), 'ahmadi_product_external_image')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $value = isset($_POST['ahmadi_product_image_url']) ? esc_url_raw(wp_unslash($_POST['ahmadi_product_image_url'])) : '';
    if ($value === '') {
        delete_post_meta($post_id, 'ahmadi_product_image_url');
    } else {
        update_post_meta($post_id, 'ahmadi_product_image_url', $value);
    }
}

add_action('save_post', 'ahmadi_theme_save_product_external_image', 10, 2);

add_action('save_post', 'ahmadi_theme_save_site_content_meta', 10, 2);

function ahmadi_theme_admin_assets(string $hook): void
{
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || empty($screen->post_type)) {
        return;
    }

    $allowed = [
        'ahmadi_site_notice',
        'ahmadi_site_logo',
        'ahmadi_home_hero',
        'ahmadi_home_brand',
        'ahmadi_home_promo',
        'ahmadi_home_middle',
        'ahmadi_contact_content',
        'ahmadi_about_content',
        'ahmadi_footer_about',
    ];
    if (!in_array($screen->post_type, $allowed, true)) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script(
        'ahmadi-admin-media',
        get_template_directory_uri() . '/ahmadi-store/js/admin-media.js',
        ['jquery'],
        '1.0.0',
        true
    );
}

add_action('admin_enqueue_scripts', 'ahmadi_theme_admin_assets');

function ahmadi_theme_get_term_color(int $term_id, string $meta_key, string $fallback = ''): string
{
    $value = get_term_meta($term_id, $meta_key, true);
    return is_string($value) && $value !== '' ? $value : $fallback;
}

function ahmadi_theme_product_cat_color_fields(): void
{
    ?>
    <div class="form-field">
        <label for="ahmadi_category_color_start">لون الخلفية (البداية)</label>
        <input type="color" name="ahmadi_category_color_start" id="ahmadi_category_color_start" value="#f8f1e9">
        <p class="description">اللون الأول لخلفية القسم.</p>
    </div>
    <div class="form-field">
        <label for="ahmadi_category_image_url">رابط صورة القسم (خارجي)</label>
        <input type="url" name="ahmadi_category_image_url" id="ahmadi_category_image_url" placeholder="https://">
        <p class="description">يمكنك وضع رابط صورة من السيرفر السحابي.</p>
    </div>
    <div class="form-field">
        <label for="ahmadi_category_color_end">لون الخلفية (النهاية)</label>
        <input type="color" name="ahmadi_category_color_end" id="ahmadi_category_color_end" value="#f0e6d6">
        <p class="description">اللون الثاني لخلفية القسم.</p>
    </div>
    <?php
}

function ahmadi_theme_product_cat_color_fields_edit(WP_Term $term): void
{
    $start = ahmadi_theme_get_term_color($term->term_id, 'ahmadi_category_color_start', '#f8f1e9');
    $end = ahmadi_theme_get_term_color($term->term_id, 'ahmadi_category_color_end', '#f0e6d6');
    $image_url = get_term_meta($term->term_id, 'ahmadi_category_image_url', true);
    ?>
    <tr class="form-field">
        <th scope="row"><label for="ahmadi_category_color_start">لون الخلفية (البداية)</label></th>
        <td>
            <input type="color" name="ahmadi_category_color_start" id="ahmadi_category_color_start" value="<?php echo esc_attr($start); ?>">
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="ahmadi_category_color_end">لون الخلفية (النهاية)</label></th>
        <td>
            <input type="color" name="ahmadi_category_color_end" id="ahmadi_category_color_end" value="<?php echo esc_attr($end); ?>">
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="ahmadi_category_image_url">رابط صورة القسم (خارجي)</label></th>
        <td>
            <input type="url" name="ahmadi_category_image_url" id="ahmadi_category_image_url" value="<?php echo esc_attr((string) $image_url); ?>" placeholder="https://">
        </td>
    </tr>
    <?php
}

function ahmadi_theme_save_product_cat_colors(int $term_id): void
{
    if (isset($_POST['ahmadi_category_color_start'])) {
        $start = sanitize_text_field(wp_unslash($_POST['ahmadi_category_color_start']));
        if ($start !== '') {
            update_term_meta($term_id, 'ahmadi_category_color_start', $start);
        } else {
            delete_term_meta($term_id, 'ahmadi_category_color_start');
        }
    }

    if (isset($_POST['ahmadi_category_color_end'])) {
        $end = sanitize_text_field(wp_unslash($_POST['ahmadi_category_color_end']));
        if ($end !== '') {
            update_term_meta($term_id, 'ahmadi_category_color_end', $end);
        } else {
            delete_term_meta($term_id, 'ahmadi_category_color_end');
        }
    }

    if (isset($_POST['ahmadi_category_image_url'])) {
        $image_url = esc_url_raw(wp_unslash($_POST['ahmadi_category_image_url']));
        if ($image_url !== '') {
            update_term_meta($term_id, 'ahmadi_category_image_url', $image_url);
        } else {
            delete_term_meta($term_id, 'ahmadi_category_image_url');
        }
    }
}

add_action('product_cat_add_form_fields', 'ahmadi_theme_product_cat_color_fields');
add_action('product_cat_edit_form_fields', 'ahmadi_theme_product_cat_color_fields_edit');
add_action('created_product_cat', 'ahmadi_theme_save_product_cat_colors');
add_action('edited_product_cat', 'ahmadi_theme_save_product_cat_colors');

require_once get_template_directory() . '/inc/demo-products.php';

function ahmadi_theme_enqueue_assets(): void
{
    $theme_uri = get_template_directory_uri();
    $theme_dir = get_template_directory();

    $styles = [
        'ahmadi-reset' => '/ahmadi-store/base/reset.css',
        'ahmadi-tokens' => '/ahmadi-store/base/tokens.css',
        'ahmadi-typography' => '/ahmadi-store/base/typography.css',
        'ahmadi-utilities' => '/ahmadi-store/base/utilities.css',
        'ahmadi-fontawesome' => '/ahmadi-store/assets/fontawesome/css/all.min.css',
        'ahmadi-header' => '/ahmadi-store/components/y-header.css',
        'ahmadi-buttons' => '/ahmadi-store/components/y-buttons.css',
        'ahmadi-cards' => '/ahmadi-store/components/y-cards.css',
        'ahmadi-forms' => '/ahmadi-store/components/y-forms.css',
        'ahmadi-footer' => '/ahmadi-store/components/y-footer.css',
    ];

    foreach ($styles as $handle => $relative) {
        $path = $theme_dir . $relative;
        wp_enqueue_style($handle, $theme_uri . $relative, [], file_exists($path) ? filemtime($path) : null);
    }

    $page_styles = [
        'front-page' => '/ahmadi-store/templates/home/y-home.css',
        'page-shop-archive.php' => '/ahmadi-store/templates/shop-archive/y-shop-archive.css',
        'page-product-single.php' => '/ahmadi-store/templates/product-single/y-product-single.css',
        'page-cart.php' => '/ahmadi-store/templates/cart/y-cart.css',
        'page-account.php' => '/ahmadi-store/templates/account/y-account.css',
        'page-contact-us.php' => '/ahmadi-store/templates/contact-us/y-contact-us.css',
        'page-about-us.php' => '/ahmadi-store/templates/about-us/y-about-us.css',
        'page-favorite.php' => '/ahmadi-store/templates/favorite/y-favorite.css',
        'page-login.php' => '/ahmadi-store/templates/login/y-login.css',
        'page-signup.php' => '/ahmadi-store/templates/signup/y-signup.css',
        'page-forget-password.php' => '/ahmadi-store/templates/forget-password/y-forget-password.css',
        'page-password-confirm.php' => '/ahmadi-store/templates/password-confirm/y-password-confirm.css',
        'page-payment.php' => '/ahmadi-store/templates/payment/y-payment.css',
        'page-privacy.php' => '/ahmadi-store/templates/privacy/y-privacy.css',
        'page-replacement.php' => '/ahmadi-store/templates/replacement/y-replacement.css',
        '404' => '/ahmadi-store/templates/404/y-404.css',
    ];

    $use_shop_archive_assets = (function_exists('is_shop') && is_shop())
        || (function_exists('is_product_taxonomy') && is_product_taxonomy());
    $use_shop_archive_scripts = is_page_template('page-shop-archive.php');

    if (is_front_page()) {
        $relative = $page_styles['front-page'];
        $path = $theme_dir . $relative;
        wp_enqueue_style('ahmadi-home', $theme_uri . $relative, [], file_exists($path) ? filemtime($path) : null);
    }

    if (is_404()) {
        $relative = $page_styles['404'];
        $path = $theme_dir . $relative;
        wp_enqueue_style('ahmadi-404', $theme_uri . $relative, [], file_exists($path) ? filemtime($path) : null);
    }

    foreach ($page_styles as $template => $relative) {
        if ($template === 'front-page' || $template === '404') {
            continue;
        }
        if (is_page_template($template)) {
            $path = $theme_dir . $relative;
            $handle = 'ahmadi-' . str_replace(['page-', '.php'], '', $template);
            wp_enqueue_style($handle, $theme_uri . $relative, [], file_exists($path) ? filemtime($path) : null);
        }
    }

    if ($use_shop_archive_assets) {
        $relative = $page_styles['page-shop-archive.php'];
        $path = $theme_dir . $relative;
        wp_enqueue_style('ahmadi-shop-archive', $theme_uri . $relative, [], file_exists($path) ? filemtime($path) : null);
    }

    $shared_handle = 'ahmadi-shared-components';
    $shared_path = $theme_dir . '/ahmadi-store/js/shared-components.js';
    wp_enqueue_script(
        $shared_handle,
        $theme_uri . '/ahmadi-store/js/shared-components.js',
        [],
        file_exists($shared_path) ? filemtime($shared_path) : null,
        true
    );
    wp_localize_script($shared_handle, 'ahmadiTheme', [
        'componentBaseUrl' => $theme_uri . '/ahmadi-store',
        'productUrl' => ahmadi_theme_page_url('product-single'),
        'cartUrl' => function_exists('wc_get_cart_url') ? wc_get_cart_url() : ahmadi_theme_page_url('cart'),
        'isWooCommerce' => class_exists('WooCommerce'),
    ]);

    $scripts = [
        'front-page' => [
            '/ahmadi-store/js/home.js',
        ],
        'page-shop-archive.php' => [
            '/ahmadi-store/js/products.js',
            '/ahmadi-store/js/shop-archive.js',
        ],
        'page-product-single.php' => [
            '/ahmadi-store/js/single-product.js',
            '/ahmadi-store/js/products.js',
        ],
        'page-payment.php' => [
            '/ahmadi-store/js/payment.js',
        ],
        'page-password-confirm.php' => [
            '/ahmadi-store/js/password-confirm.js',
        ],
    ];

    $enqueue_script = static function (string $relative, string $suffix) use ($theme_uri, $theme_dir, $shared_handle): void {
        $path = $theme_dir . $relative;
        $handle = 'ahmadi-' . $suffix;
        wp_enqueue_script(
            $handle,
            $theme_uri . $relative,
            [$shared_handle],
            file_exists($path) ? filemtime($path) : null,
            true
        );
    };

    if (is_front_page()) {
        foreach ($scripts['front-page'] as $relative) {
            $suffix = basename($relative, '.js');
            $enqueue_script($relative, $suffix);
        }
    }

    foreach ($scripts as $template => $relatives) {
        if ($template === 'front-page') {
            continue;
        }
        if (is_page_template($template)) {
            foreach ($relatives as $relative) {
                $suffix = basename($relative, '.js');
                $enqueue_script($relative, $suffix);
            }
        }
    }

    if ($use_shop_archive_scripts && isset($scripts['page-shop-archive.php'])) {
        foreach ($scripts['page-shop-archive.php'] as $relative) {
            $suffix = basename($relative, '.js');
            $enqueue_script($relative, $suffix);
        }
    }
}

add_action('wp_enqueue_scripts', 'ahmadi_theme_enqueue_assets');

add_filter('woocommerce_catalog_orderby', 'ahmadi_theme_translate_catalog_orderby');
add_filter('woocommerce_default_catalog_orderby_options', 'ahmadi_theme_translate_catalog_orderby');

function ahmadi_theme_translate_catalog_orderby(array $options): array
{
    $translations = [
        'menu_order' => 'الترتيب الافتراضي',
        'popularity' => 'الأكثر مبيعا',
        'rating' => 'الأعلى تقييما',
        'date' => 'الأحدث',
        'price' => 'السعر: من الأقل إلى الأعلى',
        'price-desc' => 'السعر: من الأعلى إلى الأقل',
    ];

    foreach ($translations as $key => $label) {
        if (isset($options[$key])) {
            $options[$key] = $label;
        }
    }

    return $options;
}

function ahmadi_theme_translate_forgot_password_notices(string $translated, string $text, string $domain): string
{
    if (is_admin()) {
        return $translated;
    }

    if (!is_page_template('page-forget-password.php') && !is_page('forget-password')) {
        return $translated;
    }

    $translations = [
        'Password reset email has been sent.' => 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.',
        'If this account exists, a password reset link will be sent to the email address on file.' => 'إذا كان الحساب موجودا، سيتم إرسال رابط إعادة تعيين كلمة المرور إلى البريد الإلكتروني المسجل.',
        'Invalid username or email.' => 'اسم المستخدم أو البريد الإلكتروني غير صحيح.',
        'Invalid username.' => 'اسم المستخدم غير صحيح.',
        'Invalid email address.' => 'البريد الإلكتروني غير صحيح.',
        'Enter a username or email address.' => 'يرجى إدخال اسم المستخدم أو البريد الإلكتروني.',
        'There is no account with that username or email address.' => 'لا يوجد حساب بهذا الاسم أو البريد الإلكتروني.',
        'Unable to send the email. The site may not be correctly configured to send emails.' => 'تعذر إرسال البريد الإلكتروني. يرجى المحاولة لاحقا.',
    ];

    return $translations[$text] ?? $translated;
}

add_filter('gettext', 'ahmadi_theme_translate_forgot_password_notices', 20, 3);

function ahmadi_theme_redirect_lost_password_location(string $location, int $status): string
{
    if (empty($_POST['wc_reset_password']) || empty($_POST['user_login'])) {
        return $location;
    }

    $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '';
    if ($request_uri === '' || strpos($request_uri, 'forget-password') === false) {
        return $location;
    }

    $target = wc_get_account_endpoint_url('lost-password');
    if (strpos($location, $target) === false || strpos($location, 'reset-link-sent') === false) {
        return $location;
    }

    wc_add_notice(__('Password reset email has been sent.', 'woocommerce'), 'success');

    return add_query_arg('reset-link-sent', 'true', ahmadi_theme_page_url('forget-password'));
}

add_filter('wp_redirect', 'ahmadi_theme_redirect_lost_password_location', 10, 2);

function ahmadi_theme_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    remove_action('woocommerce_archive_description', 'woocommerce_product_archive_description', 10);
    remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);
}

add_action('after_setup_theme', 'ahmadi_theme_setup');

function ahmadi_theme_ensure_pages(): void
{
    $pages = [
        ['slug' => 'shop', 'title' => 'تسوق', 'template' => ''],
        ['slug' => 'shop-archive', 'title' => 'تسوق (تصميم)', 'template' => 'page-shop-archive.php'],
        ['slug' => 'product-single', 'title' => 'تفاصيل المنتج', 'template' => 'page-product-single.php'],
        ['slug' => 'cart', 'title' => 'السلة', 'template' => 'page-cart.php'],
        ['slug' => 'account', 'title' => 'حسابي', 'template' => 'page-account.php'],
        ['slug' => 'favorite', 'title' => 'المفضلة', 'template' => 'page-favorite.php'],
        ['slug' => 'contact-us', 'title' => 'تواصل معنا', 'template' => 'page-contact-us.php'],
        ['slug' => 'about-us', 'title' => 'من نحن', 'template' => 'page-about-us.php'],
        ['slug' => 'login', 'title' => 'تسجيل الدخول', 'template' => 'page-login.php'],
        ['slug' => 'signup', 'title' => 'إنشاء حساب', 'template' => 'page-signup.php'],
        ['slug' => 'forget-password', 'title' => 'نسيت كلمة المرور', 'template' => 'page-forget-password.php'],
        ['slug' => 'password-confirm', 'title' => 'تأكيد كلمة المرور', 'template' => 'page-password-confirm.php'],
        ['slug' => 'payment', 'title' => 'الدفع', 'template' => 'page-payment.php'],
        ['slug' => 'privacy', 'title' => 'سياسة الخصوصية', 'template' => 'page-privacy.php'],
        ['slug' => 'replacement', 'title' => 'سياسة الاستبدال', 'template' => 'page-replacement.php'],
    ];

    $created_any = false;
    foreach ($pages as $page) {
        $existing = get_page_by_path($page['slug']);
        if ($existing) {
            if (!empty($page['template'])) {
                update_post_meta($existing->ID, '_wp_page_template', $page['template']);
            } else {
                delete_post_meta($existing->ID, '_wp_page_template');
            }
            if ($page['slug'] === 'shop' && $existing->post_content !== '') {
                wp_update_post([
                    'ID' => $existing->ID,
                    'post_content' => '',
                ]);
            }
            continue;
        }

        $page_id = wp_insert_post([
            'post_title' => $page['title'],
            'post_name' => $page['slug'],
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_content' => '',
        ]);

        if (!is_wp_error($page_id) && $page_id) {
            if (!empty($page['template'])) {
                update_post_meta($page_id, '_wp_page_template', $page['template']);
            }
            $created_any = true;
        }
    }

    if ($created_any || !get_option('ahmadi_theme_pages_created')) {
        update_option('ahmadi_theme_pages_created', 1);
    }

    if (class_exists('WooCommerce')) {
        $shop_page = get_page_by_path('shop');
        $cart_page = get_page_by_path('cart');
        $checkout_page = get_page_by_path('payment');
        $account_page = get_page_by_path('account');
        $privacy_page = get_page_by_path('privacy');

        if ($shop_page) {
            update_option('woocommerce_shop_page_id', $shop_page->ID);
        }
        if ($cart_page) {
            update_option('woocommerce_cart_page_id', $cart_page->ID);
        }
        if ($checkout_page) {
            update_option('woocommerce_checkout_page_id', $checkout_page->ID);
        }
        if ($account_page) {
            update_option('woocommerce_myaccount_page_id', $account_page->ID);
        }
        if ($privacy_page) {
            update_option('wp_page_for_privacy_policy', $privacy_page->ID);
        }
    }
}

add_action('init', 'ahmadi_theme_ensure_pages');

add_filter('woocommerce_product_add_to_cart_text', function (): string {
    return 'إضافة الى السلة';
});

add_filter('woocommerce_loop_add_to_cart_args', function (array $args): array {
    $args['class'] = trim(($args['class'] ?? '') . ' y-c-shop-now-btn');
    return $args;
});

add_filter('woocommerce_add_to_cart_message_html', function (string $message, array $products): string {
    $names = [];
    foreach (array_keys($products) as $product_id) {
        $product = wc_get_product($product_id);
        if ($product) {
            $names[] = $product->get_name();
        }
    }
    $product_text = $names
        ? 'تمت إضافة "' . implode('", "', array_map('esc_html', $names)) . '" إلى السلة.'
        : 'تمت إضافة المنتج إلى السلة.';
    return '<div class="woocommerce-message" role="alert">' . $product_text . '</div>';
}, 10, 2);

add_filter('woocommerce_add_to_cart_fragments', function (array $fragments): array {
    $count = 0;
    $total = 'ر.س 0.00';
    if (class_exists('WooCommerce') && function_exists('WC') && WC()->cart) {
        $count = WC()->cart->get_cart_contents_count();
        $total = WC()->cart->get_cart_total();
    }
    $fragments['.y-c-cart-count'] = '<span class="y-c-cart-count">' . esc_html($count) . '</span>';
    $fragments['.y-c-cart-text'] = '<span class="y-c-cart-text">' . wp_kses_post($total) . '</span>';
    return $fragments;
});

add_filter('woocommerce_account_menu_items', function (array $items): array {
    unset($items['dashboard'], $items['downloads']);

    $labels = [
        'orders' => 'طلباتي',
        'edit-address' => 'العناوين',
        'edit-account' => 'بيانات الحساب',
        'customer-logout' => 'تسجيل الخروج',
    ];

    foreach ($items as $key => $label) {
        if (isset($labels[$key])) {
            $items[$key] = $labels[$key];
        }
    }

    return $items;
}, 20);

add_filter('woocommerce_account_default_endpoint', function (string $endpoint): string {
    return 'edit-account';
});

add_filter('woocommerce_get_query_vars', function (array $vars): array {
    unset($vars['downloads'], $vars['dashboard']);
    return $vars;
});

function ahmadi_account_edit_address(): void
{
    $load_address = get_query_var('edit-address');
    if (!$load_address) {
        wc_get_template('myaccount/my-address.php');
        return;
    }
    if ($load_address === 'billing') {
        $load_address = 'shipping';
    }
    wc_get_template('myaccount/form-edit-address.php', [
        'load_address' => $load_address,
    ]);
}

remove_action('woocommerce_account_edit-address_endpoint', 'woocommerce_account_edit_address');
add_action('woocommerce_account_edit-address_endpoint', 'ahmadi_account_edit_address');

add_filter('woocommerce_shipping_fields', function (array $fields): array {
    $fields['shipping_phone'] = [
        'label' => 'الهاتف',
        'required' => true,
        'class' => ['y-c-form-field'],
        'input_class' => ['y-c-form-input'],
        'type' => 'tel',
        'priority' => 90,
    ];
    $fields['shipping_email'] = [
        'label' => 'البريد الإلكتروني',
        'required' => true,
        'class' => ['y-c-form-field'],
        'input_class' => ['y-c-form-input'],
        'type' => 'email',
        'priority' => 100,
    ];

    return $fields;
});

add_action('template_redirect', function (): void {
    if (!is_user_logged_in() || !function_exists('is_account_page') || !is_account_page()) {
        return;
    }
    if (function_exists('is_wc_endpoint_url') && is_wc_endpoint_url('edit-address')) {
        $address_type = get_query_var('edit-address');
        if ($address_type === 'billing') {
            wp_safe_redirect(wc_get_account_endpoint_url('edit-address/shipping'));
            exit;
        }
    }
    if (function_exists('WC') && WC()->query && WC()->query->get_current_endpoint() === '') {
        wp_safe_redirect(wc_get_account_endpoint_url('edit-account'));
        exit;
    }
});

add_filter('gettext', function (string $translated, string $text, string $domain): string {
    if ($domain !== 'woocommerce') {
        return $translated;
    }
    $map = [
        'Orders' => 'طلباتي',
        'No order has been made yet.' => 'لم تقم بإنشاء أي طلبات بعد.',
        'Browse products' => 'تسوق المنتجات',
        'Addresses' => 'العناوين',
        'Log out' => 'تسجيل الخروج',
        'The following addresses will be used on the checkout page by default.' => 'سيتم استخدام العناوين التالية بشكل افتراضي في صفحة الدفع.',
        'Billing address' => 'عنوان الفاتورة',
        'Shipping address' => 'عنوان الشحن',
        'Add billing address' => 'إضافة عنوان الفاتورة',
        'Add Billing address' => 'إضافة عنوان الفاتورة',
        'Add shipping address' => 'إضافة عنوان الشحن',
        'Add Shipping address' => 'إضافة عنوان الشحن',
        'You have not set up this type of address yet.' => 'لم تقم بإعداد هذا النوع من العناوين بعد.',
        'Add %s' => 'إضافة %s',
        'Add' => 'إضافة',
        'Invalid username or email.' => 'البريد الإلكتروني غير صحيح أو غير مسجل.',
        'Password reset email has been sent.' => 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.',
        'A reset link will be sent to your email address.' => 'سيتم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.',
    ];
    return $map[$text] ?? $translated;
}, 20, 3);

add_filter('woocommerce_registration_errors', function (WP_Error $errors): WP_Error {
    $mobile = isset($_POST['billing_phone']) ? sanitize_text_field(wp_unslash($_POST['billing_phone'])) : '';
    if ($mobile === '' || !preg_match('/^05\d{8}$/', $mobile)) {
        $errors->add('invalid_mobile', 'رقم الجوال يجب أن يبدأ بـ 05 ويتكون من 10 أرقام.');
    }
    if (isset($_POST['password'], $_POST['confirm_password'])) {
        $password = (string) wp_unslash($_POST['password']);
        $confirm = (string) wp_unslash($_POST['confirm_password']);
        if ($password !== $confirm) {
            $errors->add('password_mismatch', 'كلمتا المرور غير متطابقتين.');
        }
    }
    return $errors;
});

add_action('woocommerce_created_customer', function (int $customer_id): void {
    $mobile = isset($_POST['billing_phone']) ? sanitize_text_field(wp_unslash($_POST['billing_phone'])) : '';
    if ($mobile !== '' && preg_match('/^05\d{8}$/', $mobile)) {
        update_user_meta($customer_id, 'billing_phone', $mobile);
    }
});

add_filter('the_content', function (string $content): string {
    if (is_shop()) {
        return '';
    }
    return $content;
}, 20);

function ahmadi_theme_handle_register(): void
{
    $errors = [];
    if (!isset($_POST['ahmadi_register_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ahmadi_register_nonce'])), 'ahmadi_register')) {
        $errors[] = 'حدث خطأ أثناء التحقق من الطلب. حاول مرة أخرى.';
        wp_safe_redirect(add_query_arg('register_error', rawurlencode(implode('|', $errors)), ahmadi_theme_page_url('signup')));
        exit;
    }

    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $password = isset($_POST['password']) ? (string) wp_unslash($_POST['password']) : '';
    $confirm = isset($_POST['confirm_password']) ? (string) wp_unslash($_POST['confirm_password']) : '';
    $mobile = isset($_POST['billing_phone']) ? sanitize_text_field(wp_unslash($_POST['billing_phone'])) : '';

    $has_error = false;
    if (!$email || !is_email($email)) {
        $errors[] = 'يرجى إدخال بريد إلكتروني صحيح.';
        $has_error = true;
    }
    if ($password === '') {
        $errors[] = 'يرجى إدخال كلمة المرور.';
        $has_error = true;
    } elseif ($password !== $confirm) {
        $errors[] = 'كلمتا المرور غير متطابقتين.';
        $has_error = true;
    }
    if (!preg_match('/^05\d{8}$/', $mobile)) {
        $errors[] = 'رقم الجوال يجب أن يبدأ بـ 05 ويتكون من 10 أرقام.';
        $has_error = true;
    }
    if ($has_error) {
        wp_safe_redirect(add_query_arg('register_error', rawurlencode(implode('|', $errors)), ahmadi_theme_page_url('signup')));
        exit;
    }

    if (email_exists($email)) {
        $errors[] = 'البريد الإلكتروني مستخدم بالفعل. سجل الدخول أو استخدم بريدًا آخر.';
        wp_safe_redirect(add_query_arg('register_error', rawurlencode(implode('|', $errors)), ahmadi_theme_page_url('signup')));
        exit;
    }

    $user_id = wp_create_user($email, $password, $email);
    if (is_wp_error($user_id)) {
        $errors[] = 'تعذر إنشاء الحساب. حاول مرة أخرى.';
        wp_safe_redirect(add_query_arg('register_error', rawurlencode(implode('|', $errors)), ahmadi_theme_page_url('signup')));
        exit;
    }

    update_user_meta($user_id, 'billing_phone', $mobile);
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
    wp_safe_redirect(ahmadi_theme_page_url('account'));
    exit;
}

add_action('admin_post_nopriv_ahmadi_register', 'ahmadi_theme_handle_register');
add_action('admin_post_ahmadi_register', 'ahmadi_theme_handle_register');

add_action('woocommerce_after_edit_account_form', function (): void {
    ?>
    <div class="y-c-login-btn-container">
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" onsubmit="return confirm('هل أنت متأكد من حذف الحساب نهائيًا؟');">
            <input type="hidden" name="action" value="ahmadi_delete_account">
            <?php wp_nonce_field('ahmadi_delete_account', 'ahmadi_delete_account_nonce'); ?>
            <button type="submit" class="y-c-login-btn">حذف الحساب</button>
        </form>
    </div>
    <?php
});

function ahmadi_theme_handle_delete_account(): void
{
    if (!is_user_logged_in()) {
        wp_safe_redirect(ahmadi_theme_page_url('login'));
        exit;
    }
    if (
        !isset($_POST['ahmadi_delete_account_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ahmadi_delete_account_nonce'])), 'ahmadi_delete_account')
    ) {
        if (function_exists('wc_add_notice')) {
            wc_add_notice('تعذر تأكيد طلب حذف الحساب. حاول مرة أخرى.', 'error');
        }
    if (function_exists('wc_get_account_endpoint_url')) {
        wp_safe_redirect(wc_get_account_endpoint_url('edit-account'));
    } else {
        wp_safe_redirect(ahmadi_theme_page_url('account'));
    }
        exit;
    }

    $user_id = get_current_user_id();
    if ($user_id <= 0) {
        if (function_exists('wc_add_notice')) {
            wc_add_notice('تعذر العثور على الحساب.', 'error');
        }
        wp_safe_redirect(ahmadi_theme_page_url('account'));
        exit;
    }

    require_once ABSPATH . 'wp-admin/includes/user.php';
    $deleted = wp_delete_user($user_id);
    if (!$deleted) {
        if (function_exists('wc_add_notice')) {
            wc_add_notice('تعذر حذف الحساب. حاول مرة أخرى.', 'error');
        }
        wp_safe_redirect(ahmadi_theme_page_url('account'));
        exit;
    }

    if (function_exists('wc_add_notice')) {
        wc_add_notice('تم حذف الحساب بنجاح.', 'success');
    }
    wp_logout();
    wp_safe_redirect(ahmadi_theme_page_url('login'));
    exit;
}

add_action('admin_post_ahmadi_delete_account', 'ahmadi_theme_handle_delete_account');

function ahmadi_theme_password_reset_message(string $message, string $key, string $user_login, WP_User $user_data): string
{
    $reset_url = add_query_arg(
        [
            'key' => $key,
            'login' => rawurlencode($user_login),
        ],
        ahmadi_theme_page_url('password-confirm')
    );

    return "لإعادة تعيين كلمة المرور، يرجى الضغط على الرابط التالي:\n\n" . $reset_url . "\n\nإذا لم تطلب إعادة تعيين كلمة المرور، يرجى تجاهل هذه الرسالة.";
}

add_filter('retrieve_password_message', 'ahmadi_theme_password_reset_message', 10, 4);
