<?php
/**
 * Demo Products Importer
 * 
 * @package MyCarTheme
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/demo-products.php';

/**
 * Import demo products and categories
 */
function my_car_import_demo_products() {
    // Check if user has permission
    if (!current_user_can('manage_options')) {
        wp_die(__('ليس لديك صلاحية لتنفيذ هذه العملية.', 'my-car-theme'));
    }

    // Check nonce
    if (!isset($_POST['my_car_import_nonce']) || !wp_verify_nonce($_POST['my_car_import_nonce'], 'my_car_import_products')) {
        wp_die(__('فشل التحقق من الأمان.', 'my-car-theme'));
    }

    $demo_data = my_car_get_demo_products();
    $imported_categories = array();
    $imported_products = 0;
    $errors = array();

    // Import Categories
    foreach ($demo_data['categories'] as $cat_data) {
        // Check if category already exists
        $term = get_term_by('slug', $cat_data['slug'], 'product_cat');
        
        if (!$term) {
            // Create category
            $term_result = wp_insert_term(
                $cat_data['name'],
                'product_cat',
                array(
                    'slug' => $cat_data['slug'],
                    'description' => isset($cat_data['description']) ? $cat_data['description'] : '',
                )
            );

            if (!is_wp_error($term_result)) {
                $term_id = $term_result['term_id'];
                $imported_categories[$cat_data['slug']] = $term_id;

                // Set category image
                if (isset($cat_data['image']) && !empty($cat_data['image'])) {
                    my_car_set_category_image($term_id, $cat_data['image']);
                }
            } else {
                $errors[] = sprintf(__('فشل إنشاء الفئة: %s', 'my-car-theme'), $cat_data['name']);
            }
        } else {
            $imported_categories[$cat_data['slug']] = $term->term_id;
        }
    }

    // Import Products
    foreach ($demo_data['products'] as $product_data) {
        // Check if product already exists
        $existing_product = get_page_by_path($product_data['slug'], OBJECT, 'product');
        
        if (!$existing_product) {
            // Create product
            $product = new WC_Product_Simple();
            $product->set_name($product_data['name']);
            $product->set_slug($product_data['slug']);
            $product->set_description($product_data['description']);
            $product->set_short_description($product_data['short_description']);
            $product->set_regular_price($product_data['regular_price']);
            $product->set_price($product_data['price']);
            $product->set_status('publish');
            $product->set_catalog_visibility('visible');
            $product->set_stock_status('instock');
            $product->set_manage_stock(false);

            // Set category
            if (isset($imported_categories[$product_data['category']])) {
                $product->set_category_ids(array($imported_categories[$product_data['category']]));
            }

            // Set attributes
            if (isset($product_data['attributes']) && is_array($product_data['attributes'])) {
                $attributes = array();
                foreach ($product_data['attributes'] as $attr_name => $attr_value) {
                    $attribute = new WC_Product_Attribute();
                    $attribute->set_name($attr_name);
                    $attribute->set_options(array($attr_value));
                    $attribute->set_visible(true);
                    $attribute->set_variation(false);
                    $attributes[] = $attribute;
                }
                $product->set_attributes($attributes);
            }

            $product_id = $product->save();

            if ($product_id) {
                $imported_products++;

                // Set product image
                if (isset($product_data['image']) && !empty($product_data['image'])) {
                    my_car_set_product_image($product_id, $product_data['image']);
                }
            } else {
                $errors[] = sprintf(__('فشل إنشاء المنتج: %s', 'my-car-theme'), $product_data['name']);
            }
        }
    }

    // Set success message
    $message = sprintf(
        __('تم استيراد %d منتج و %d فئة بنجاح.', 'my-car-theme'),
        $imported_products,
        count($imported_categories)
    );

    if (!empty($errors)) {
        $message .= ' ' . __('بعض الأخطاء حدثت:', 'my-car-theme') . ' ' . implode(', ', $errors);
    }

    // Redirect with message
    wp_redirect(add_query_arg(array(
        'page' => 'my-car-demo-importer',
        'imported' => '1',
        'products' => $imported_products,
        'categories' => count($imported_categories),
    ), admin_url('themes.php')));
    exit;
}
add_action('admin_post_my_car_import_products', 'my_car_import_demo_products');

/**
 * Set category image from URL or local path
 */
function my_car_set_category_image($term_id, $image_url) {
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Check if it's a local file path
    $image_path = str_replace(get_template_directory_uri(), get_template_directory(), $image_url);
    
    if (file_exists($image_path)) {
        // Use local file - copy to temp location
        $tmp = wp_tempnam(basename($image_path));
        if (!copy($image_path, $tmp)) {
            return false;
        }
        
        $file_array = array(
            'name' => basename($image_path),
            'tmp_name' => $tmp
        );
    } else {
        // Download from URL
        $tmp = download_url($image_url);
        
        if (is_wp_error($tmp)) {
            return false;
        }

        $file_array = array(
            'name' => basename($image_url),
            'tmp_name' => $tmp
        );
    }

    // Upload image
    $attachment_id = media_handle_sideload($file_array, 0);

    if (is_wp_error($attachment_id)) {
        @unlink($file_array['tmp_name']);
        return false;
    }

    // Set as category thumbnail
    update_term_meta($term_id, 'thumbnail_id', $attachment_id);

    return $attachment_id;
}

/**
 * Set product image from URL or local path
 */
function my_car_set_product_image($product_id, $image_url) {
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Check if it's a local file path
    $image_path = str_replace(get_template_directory_uri(), get_template_directory(), $image_url);
    
    if (file_exists($image_path)) {
        // Use local file - copy to temp location
        $tmp = wp_tempnam(basename($image_path));
        if (!copy($image_path, $tmp)) {
            return false;
        }
        
        $file_array = array(
            'name' => basename($image_path),
            'tmp_name' => $tmp
        );
    } else {
        // Download from URL
        $tmp = download_url($image_url);
        
        if (is_wp_error($tmp)) {
            return false;
        }

        $file_array = array(
            'name' => basename($image_url),
            'tmp_name' => $tmp
        );
    }

    // Upload image
    $attachment_id = media_handle_sideload($file_array, $product_id);

    if (is_wp_error($attachment_id)) {
        @unlink($file_array['tmp_name']);
        return false;
    }

    // Set as product image
    set_post_thumbnail($product_id, $attachment_id);

    return $attachment_id;
}

/**
 * Add admin menu page
 */
function my_car_add_demo_importer_page() {
    add_theme_page(
        __('منتجات ديمو', 'my-car-theme'),
        __('منتجات ديمو', 'my-car-theme'),
        'manage_options',
        'my-car-demo-importer',
        'my_car_demo_importer_page'
    );
}
add_action('admin_menu', 'my_car_add_demo_importer_page');

/**
 * Demo importer page content
 */
function my_car_demo_importer_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('ليس لديك صلاحية للوصول إلى هذه الصفحة.', 'my-car-theme'));
    }

    $demo_data = my_car_get_demo_products();
    $products_count = count($demo_data['products']);
    $categories_count = count($demo_data['categories']);

    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <div class="notice notice-error">
                <p><?php _e('يجب تفعيل WooCommerce أولاً لاستيراد المنتجات.', 'my-car-theme'); ?></p>
            </div>
        </div>
        <?php
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <?php if (isset($_GET['imported']) && $_GET['imported'] == '1') : ?>
            <div class="notice notice-success is-dismissible">
                <p>
                    <?php
                    printf(
                        __('تم استيراد %d منتج و %d فئة بنجاح!', 'my-car-theme'),
                        isset($_GET['products']) ? intval($_GET['products']) : 0,
                        isset($_GET['categories']) ? intval($_GET['categories']) : 0
                    );
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="card">
            <h2><?php _e('استيراد المنتجات والفئات', 'my-car-theme'); ?></h2>
            <p>
                <?php
                printf(
                    __('سيتم استيراد %d منتج و %d فئة من التصميم إلى WooCommerce.', 'my-car-theme'),
                    $products_count,
                    $categories_count
                );
                ?>
            </p>

            <h3><?php _e('الفئات:', 'my-car-theme'); ?></h3>
            <ul>
                <?php foreach ($demo_data['categories'] as $cat) : ?>
                    <li><?php echo esc_html($cat['name']); ?></li>
                <?php endforeach; ?>
            </ul>

            <h3><?php _e('المنتجات:', 'my-car-theme'); ?></h3>
            <ul>
                <?php foreach ($demo_data['products'] as $product) : ?>
                    <li><?php echo esc_html($product['name']); ?> - <?php echo esc_html($product['price']); ?> ريال</li>
                <?php endforeach; ?>
            </ul>

            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('my_car_import_products', 'my_car_import_nonce'); ?>
                <input type="hidden" name="action" value="my_car_import_products">
                <p class="submit">
                    <button type="submit" class="button button-primary button-large">
                        <?php _e('استيراد المنتجات والفئات', 'my-car-theme'); ?>
                    </button>
                </p>
            </form>
        </div>
    </div>
    <?php
}
