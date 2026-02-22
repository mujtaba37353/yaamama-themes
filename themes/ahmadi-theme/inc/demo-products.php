<?php
/**
 * Demo products/categories admin tools.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ahmadi_demo_products_capability(): string
{
    return class_exists('WooCommerce') ? 'manage_woocommerce' : 'manage_options';
}

function ahmadi_demo_products_menu(): void
{
    add_submenu_page(
        'edit.php?post_type=product',
        'منتجات ديمو',
        'منتجات ديمو',
        ahmadi_demo_products_capability(),
        'ahmadi-demo-products',
        'ahmadi_demo_products_page'
    );
}

add_action('admin_menu', 'ahmadi_demo_products_menu');

function ahmadi_demo_products_page(): void
{
    if (!current_user_can(ahmadi_demo_products_capability())) {
        wp_die('غير مصرح لك بالوصول إلى هذه الصفحة.');
    }

    $status = isset($_GET['ahmadi_demo_status']) ? sanitize_text_field(wp_unslash($_GET['ahmadi_demo_status'])) : '';
    $message = isset($_GET['ahmadi_demo_message']) ? sanitize_text_field(wp_unslash($_GET['ahmadi_demo_message'])) : '';
    ?>
    <div class="wrap">
        <h1>منتجات ديمو</h1>
        <p>استخدم الأزرار التالية لإنشاء منتجات وفئات ديمو من ملفات التصميم أو حذفها.</p>

        <?php if ($status === 'success' && $message !== '') : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo esc_html($message); ?></p>
            </div>
        <?php elseif ($status === 'error' && $message !== '') : ?>
            <div class="notice notice-error is-dismissible">
                <p><?php echo esc_html($message); ?></p>
            </div>
        <?php endif; ?>

        <?php if (!class_exists('WooCommerce')) : ?>
            <div class="notice notice-warning">
                <p>يجب تفعيل WooCommerce أولاً لإنشاء منتجات وفئات ديمو.</p>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('ahmadi_demo_create'); ?>
            <input type="hidden" name="action" value="ahmadi_create_demo_products">
            <p>
                <button type="submit" class="button button-primary">إنشاء منتجات وفئات ديمو</button>
            </p>
        </form>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="margin-top: 10px;">
            <?php wp_nonce_field('ahmadi_demo_delete'); ?>
            <input type="hidden" name="action" value="ahmadi_delete_demo_products">
            <p>
                <button type="submit" class="button">مسح منتجات وفئات الديمو</button>
            </p>
        </form>
    </div>
    <?php
}

add_action('admin_post_ahmadi_create_demo_products', 'ahmadi_demo_create_handler');
add_action('admin_post_ahmadi_delete_demo_products', 'ahmadi_demo_delete_handler');

function ahmadi_demo_create_handler(): void
{
    if (!current_user_can(ahmadi_demo_products_capability())) {
        wp_die('غير مصرح.');
    }
    check_admin_referer('ahmadi_demo_create');

    $result = ahmadi_demo_create_content();
    if (is_wp_error($result)) {
        $message = $result->get_error_message();
        $status = 'error';
    } else {
        $message = 'تم إنشاء منتجات وفئات الديمو بنجاح.';
        $status = 'success';
    }

    $redirect = add_query_arg(
        [
            'page' => 'ahmadi-demo-products',
            'ahmadi_demo_status' => $status,
            'ahmadi_demo_message' => $message,
        ],
        admin_url('edit.php?post_type=product')
    );
    wp_safe_redirect($redirect);
    exit;
}

function ahmadi_demo_delete_handler(): void
{
    if (!current_user_can(ahmadi_demo_products_capability())) {
        wp_die('غير مصرح.');
    }
    check_admin_referer('ahmadi_demo_delete');

    $result = ahmadi_demo_delete_content();
    if (is_wp_error($result)) {
        $message = $result->get_error_message();
        $status = 'error';
    } else {
        $message = 'تم حذف منتجات وفئات الديمو.';
        $status = 'success';
    }

    $redirect = add_query_arg(
        [
            'page' => 'ahmadi-demo-products',
            'ahmadi_demo_status' => $status,
            'ahmadi_demo_message' => $message,
        ],
        admin_url('edit.php?post_type=product')
    );
    wp_safe_redirect($redirect);
    exit;
}

function ahmadi_demo_asset_path(string $filename): string
{
    return trailingslashit(get_template_directory()) . 'ahmadi-store/assets/' . $filename;
}

function ahmadi_demo_import_attachment(string $filename, string $title, array &$state): int
{
    if (isset($state['attachments_by_source'][$filename])) {
        return (int) $state['attachments_by_source'][$filename];
    }

    $path = ahmadi_demo_asset_path($filename);
    if (!file_exists($path)) {
        return 0;
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $file_array = [
        'name' => basename($path),
        'tmp_name' => $path,
    ];

    $attachment_id = media_handle_sideload($file_array, 0, $title);
    if (is_wp_error($attachment_id)) {
        return 0;
    }

    update_post_meta($attachment_id, '_ahmadi_demo_source', $filename);
    $state['attachments'][] = $attachment_id;
    $state['attachments_by_source'][$filename] = $attachment_id;

    return (int) $attachment_id;
}

function ahmadi_demo_create_content()
{
    if (!class_exists('WooCommerce')) {
        return new WP_Error('no_woo', 'WooCommerce غير مفعّل.');
    }

    $state = get_option('ahmadi_demo_content_state');
    if (!is_array($state)) {
        $state = [
            'terms' => [],
            'products' => [],
            'attachments' => [],
            'attachments_by_source' => [],
        ];
    }

    $categories = [
        [
            'name' => 'الأسماك',
            'slug' => 'fish',
            'image' => 'image 46.png',
            'color_start' => '#dfeff5',
            'color_end' => '#c2dbe8',
        ],
        [
            'name' => 'اللحوم',
            'slug' => 'meat',
            'image' => 'image 48.png',
            'color_start' => '#f8e6e6',
            'color_end' => '#f1c7c7',
        ],
        [
            'name' => 'الحبوب',
            'slug' => 'grains',
            'image' => 'image 47.png',
            'color_start' => '#f9f0dc',
            'color_end' => '#edd9b2',
        ],
        [
            'name' => 'القهوة والشاي',
            'slug' => 'coffee-tea',
            'image' => 'image 49.png',
            'color_start' => '#f3e6d9',
            'color_end' => '#e1c9b5',
        ],
        [
            'name' => 'الزيوت',
            'slug' => 'oils',
            'image' => 'image 50.png',
            'color_start' => '#f7f4d6',
            'color_end' => '#ebe1a3',
        ],
        [
            'name' => 'الخضروات',
            'slug' => 'vegetables',
            'image' => 'image 36.png',
            'color_start' => '#e8f5e9',
            'color_end' => '#c8e6c9',
        ],
        [
            'name' => 'الفواكه',
            'slug' => 'fruits',
            'image' => 'image 34.png',
            'color_start' => '#fde2e4',
            'color_end' => '#f9c2c5',
        ],
        [
            'name' => 'منتجات الألبان',
            'slug' => 'dairy',
            'image' => 'image 41.png',
            'color_start' => '#e8f1ff',
            'color_end' => '#c8dcff',
        ],
    ];

    $term_ids = [];
    foreach ($categories as $category) {
        $term = get_term_by('slug', $category['slug'], 'product_cat');
        if (!$term) {
            $created = wp_insert_term($category['name'], 'product_cat', ['slug' => $category['slug']]);
            if (is_wp_error($created)) {
                continue;
            }
            $term_id = (int) $created['term_id'];
            $state['terms'][] = $term_id;
        } else {
            $term_id = (int) $term->term_id;
        }

        $term_ids[$category['slug']] = $term_id;
        update_term_meta($term_id, 'ahmadi_category_color_start', $category['color_start']);
        update_term_meta($term_id, 'ahmadi_category_color_end', $category['color_end']);

        $attachment_id = ahmadi_demo_import_attachment($category['image'], $category['name'], $state);
        if ($attachment_id) {
            update_term_meta($term_id, 'thumbnail_id', $attachment_id);
        }
    }

    $products = [
        [
            'name' => 'لحم غنم طازج',
            'slug' => 'lamb-fresh-demo',
            'image' => 'image 43.png',
            'price' => 89.99,
            'category' => 'meat',
        ],
        [
            'name' => 'أرز بسمتي هندي',
            'slug' => 'basmati-rice-demo',
            'image' => 'image 42.png',
            'price' => 28.75,
            'category' => 'grains',
        ],
        [
            'name' => 'شاي أحمر',
            'slug' => 'red-tea-demo',
            'image' => 'image 49.png',
            'price' => 18.5,
            'category' => 'coffee-tea',
        ],
        [
            'name' => 'عسل سدر',
            'slug' => 'sidr-honey-demo',
            'image' => 'image 45.png',
            'price' => 120,
            'category' => 'oils',
        ],
        [
            'name' => 'سمك هامور',
            'slug' => 'hamour-fish-demo',
            'image' => 'image 46.png',
            'price' => 70.99,
            'category' => 'fish',
        ],
        [
            'name' => 'لحم بقر طازج',
            'slug' => 'beef-fresh-demo',
            'image' => 'image 48.png',
            'price' => 95.25,
            'category' => 'meat',
        ],
        [
            'name' => 'فاصوليا بيضاء',
            'slug' => 'white-beans-demo',
            'image' => 'image 47.png',
            'price' => 15.75,
            'category' => 'grains',
        ],
        [
            'name' => 'قهوة عربية',
            'slug' => 'arabic-coffee-demo',
            'image' => 'image 49.png',
            'price' => 35,
            'category' => 'coffee-tea',
        ],
    ];

    foreach ($products as $product) {
        $existing = get_page_by_path($product['slug'], OBJECT, 'product');
        if ($existing instanceof WP_Post) {
            $post_id = (int) $existing->ID;
        } else {
            $post_id = wp_insert_post([
                'post_title' => $product['name'],
                'post_name' => $product['slug'],
                'post_type' => 'product',
                'post_status' => 'publish',
                'post_content' => 'منتج ديمو من تصميم الموقع.',
                'post_excerpt' => 'وصف مختصر لمنتج ديمو.',
            ]);

            if (!$post_id || is_wp_error($post_id)) {
                continue;
            }

            $state['products'][] = $post_id;
        }
        wp_set_object_terms($post_id, 'simple', 'product_type');
        update_post_meta($post_id, '_regular_price', $product['price']);
        update_post_meta($post_id, '_price', $product['price']);
        update_post_meta($post_id, '_stock_status', 'instock');
        update_post_meta($post_id, '_manage_stock', 'no');
        update_post_meta($post_id, '_sku', 'demo-' . $product['slug']);

        if (isset($term_ids[$product['category']])) {
            wp_set_object_terms($post_id, [$term_ids[$product['category']]], 'product_cat');
        }

        $attachment_id = ahmadi_demo_import_attachment($product['image'], $product['name'], $state);
        if ($attachment_id) {
            set_post_thumbnail($post_id, $attachment_id);
        }
    }

    update_option('ahmadi_demo_content_state', $state, false);
    return true;
}

function ahmadi_demo_delete_content()
{
    if (!class_exists('WooCommerce')) {
        return new WP_Error('no_woo', 'WooCommerce غير مفعّل.');
    }

    $state = get_option('ahmadi_demo_content_state');
    if (!is_array($state)) {
        return true;
    }

    if (!empty($state['products'])) {
        foreach ($state['products'] as $product_id) {
            $product_id = (int) $product_id;
            if ($product_id) {
                wp_delete_post($product_id, true);
            }
        }
    }

    if (!empty($state['terms'])) {
        foreach ($state['terms'] as $term_id) {
            $term_id = (int) $term_id;
            if ($term_id) {
                wp_delete_term($term_id, 'product_cat');
            }
        }
    }

    if (!empty($state['attachments'])) {
        foreach ($state['attachments'] as $attachment_id) {
            $attachment_id = (int) $attachment_id;
            if ($attachment_id) {
                wp_delete_attachment($attachment_id, true);
            }
        }
    }

    delete_option('ahmadi_demo_content_state');
    return true;
}
