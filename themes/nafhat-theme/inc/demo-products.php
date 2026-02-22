<?php
/**
 * Demo Products System
 *
 * @package Nafhat
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Product Brand Taxonomy for WooCommerce
 */
function nafhat_register_product_brand_taxonomy() {
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    $labels = array(
        'name'                       => __('العلامات التجارية', 'nafhat'),
        'singular_name'              => __('علامة تجارية', 'nafhat'),
        'menu_name'                  => __('العلامات التجارية', 'nafhat'),
        'all_items'                  => __('جميع العلامات التجارية', 'nafhat'),
        'edit_item'                  => __('تعديل علامة تجارية', 'nafhat'),
        'view_item'                  => __('عرض علامة تجارية', 'nafhat'),
        'update_item'                => __('تحديث علامة تجارية', 'nafhat'),
        'add_new_item'               => __('إضافة علامة تجارية جديدة', 'nafhat'),
        'new_item_name'              => __('اسم علامة تجارية جديدة', 'nafhat'),
        'search_items'               => __('بحث عن علامات تجارية', 'nafhat'),
        'not_found'                  => __('لم يتم العثور على علامات تجارية', 'nafhat'),
    );
    
    $args = array(
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'rewrite'           => array('slug' => 'brand'),
    );
    
    register_taxonomy('product_brand', array('product'), $args);
}
add_action('init', 'nafhat_register_product_brand_taxonomy');

/**
 * Add Brand Image Field to Add Form
 */
function nafhat_brand_add_image_field() {
    ?>
    <div class="form-field">
        <label for="brand_image"><?php esc_html_e('صورة العلامة التجارية', 'nafhat'); ?></label>
        <input type="hidden" name="brand_image" id="brand_image" value="">
        <div id="brand_image_preview" style="margin-bottom: 10px;"></div>
        <button type="button" class="button nafhat-upload-brand-image"><?php esc_html_e('اختر صورة', 'nafhat'); ?></button>
        <button type="button" class="button nafhat-remove-brand-image" style="display:none;"><?php esc_html_e('إزالة', 'nafhat'); ?></button>
    </div>
    <?php
}
add_action('product_brand_add_form_fields', 'nafhat_brand_add_image_field');

/**
 * Add Brand Image Field to Edit Form
 */
function nafhat_brand_edit_image_field($term) {
    $image_id = get_term_meta($term->term_id, 'brand_image', true);
    ?>
    <tr class="form-field">
        <th scope="row"><label for="brand_image"><?php esc_html_e('صورة العلامة التجارية', 'nafhat'); ?></label></th>
        <td>
            <input type="hidden" name="brand_image" id="brand_image" value="<?php echo esc_attr($image_id); ?>">
            <div id="brand_image_preview" style="margin-bottom: 10px;">
                <?php if ($image_id) : ?>
                    <img src="<?php echo esc_url(wp_get_attachment_url($image_id)); ?>" style="max-width: 150px; height: auto;">
                <?php endif; ?>
            </div>
            <button type="button" class="button nafhat-upload-brand-image"><?php esc_html_e('اختر صورة', 'nafhat'); ?></button>
            <button type="button" class="button nafhat-remove-brand-image" <?php echo $image_id ? '' : 'style="display:none;"'; ?>><?php esc_html_e('إزالة', 'nafhat'); ?></button>
        </td>
    </tr>
    <?php
}
add_action('product_brand_edit_form_fields', 'nafhat_brand_edit_image_field');

/**
 * Save Brand Image
 */
function nafhat_save_brand_image($term_id) {
    if (isset($_POST['brand_image'])) {
        update_term_meta($term_id, 'brand_image', absint($_POST['brand_image']));
    }
}
add_action('created_product_brand', 'nafhat_save_brand_image');
add_action('edited_product_brand', 'nafhat_save_brand_image');

/**
 * Enqueue Media for Brand Image Upload
 */
function nafhat_brand_admin_scripts($hook) {
    if ($hook === 'edit-tags.php' || $hook === 'term.php') {
        if (isset($_GET['taxonomy']) && $_GET['taxonomy'] === 'product_brand') {
            wp_enqueue_media();
            wp_add_inline_script('jquery', '
                jQuery(document).ready(function($) {
                    $(".nafhat-upload-brand-image").on("click", function(e) {
                        e.preventDefault();
                        var frame = wp.media({
                            title: "اختر صورة",
                            button: { text: "استخدم هذه الصورة" },
                            multiple: false
                        });
                        frame.on("select", function() {
                            var attachment = frame.state().get("selection").first().toJSON();
                            $("#brand_image").val(attachment.id);
                            $("#brand_image_preview").html("<img src=\"" + attachment.url + "\" style=\"max-width: 150px; height: auto;\">");
                            $(".nafhat-remove-brand-image").show();
                        });
                        frame.open();
                    });
                    $(".nafhat-remove-brand-image").on("click", function(e) {
                        e.preventDefault();
                        $("#brand_image").val("");
                        $("#brand_image_preview").html("");
                        $(this).hide();
                    });
                });
            ');
        }
    }
}
add_action('admin_enqueue_scripts', 'nafhat_brand_admin_scripts');

/**
 * Register Demo Product Custom Post Type (Legacy - kept for cleanup)
 */
function nafhat_register_demo_product_post_type() {
    $labels = array(
        'name'                  => __('منتجات ديمو', 'nafhat'),
        'singular_name'         => __('منتج ديمو', 'nafhat'),
        'menu_name'             => __('منتجات ديمو', 'nafhat'),
        'add_new'               => __('إضافة جديد', 'nafhat'),
        'add_new_item'          => __('إضافة منتج ديمو جديد', 'nafhat'),
        'edit_item'             => __('تعديل منتج ديمو', 'nafhat'),
        'new_item'              => __('منتج ديمو جديد', 'nafhat'),
        'view_item'             => __('عرض منتج ديمو', 'nafhat'),
        'search_items'          => __('بحث عن منتجات ديمو', 'nafhat'),
        'not_found'             => __('لم يتم العثور على منتجات ديمو', 'nafhat'),
        'not_found_in_trash'    => __('لم يتم العثور على منتجات ديمو في المهملات', 'nafhat'),
    );
    
    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => false, // We'll use custom admin page
        'show_in_menu'       => false,
        'query_var'          => true,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'thumbnail'),
    );
    
    register_post_type('demo_product', $args);
}
add_action('init', 'nafhat_register_demo_product_post_type');

/**
 * Register Demo Product Category Taxonomy
 */
function nafhat_register_demo_product_category() {
    $labels = array(
        'name'              => __('فئات منتجات ديمو', 'nafhat'),
        'singular_name'     => __('فئة منتج ديمو', 'nafhat'),
        'search_items'      => __('بحث عن فئات', 'nafhat'),
        'all_items'         => __('جميع الفئات', 'nafhat'),
        'parent_item'       => __('الفئة الأم', 'nafhat'),
        'parent_item_colon' => __('الفئة الأم:', 'nafhat'),
        'edit_item'          => __('تعديل الفئة', 'nafhat'),
        'update_item'        => __('تحديث الفئة', 'nafhat'),
        'add_new_item'       => __('إضافة فئة جديدة', 'nafhat'),
        'new_item_name'      => __('اسم الفئة الجديدة', 'nafhat'),
        'menu_name'          => __('الفئات', 'nafhat'),
    );
    
    $args = array(
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => false,
        'show_admin_column' => false,
        'query_var'         => true,
        'rewrite'           => false,
    );
    
    register_taxonomy('demo_product_category', array('demo_product'), $args);
}
add_action('init', 'nafhat_register_demo_product_category');

/**
 * Initialize Default Categories (if none exist)
 */
function nafhat_init_default_categories() {
    $existing_categories = get_terms(array(
        'taxonomy'   => 'demo_product_category',
        'hide_empty' => false,
    ));
    
    if (empty($existing_categories) || is_wp_error($existing_categories)) {
        $default_categories = array(
            array('name' => __('المكياج', 'nafhat'), 'image' => 'cat1.png'),
            array('name' => __('وصل حديثا', 'nafhat'), 'image' => 'cat2.png'),
            array('name' => __('العطور', 'nafhat'), 'image' => 'cat3.png'),
            array('name' => __('الاكثر مبيعا', 'nafhat'), 'image' => 'cat4.png'),
            array('name' => __('منتجات العناية', 'nafhat'), 'image' => 'cat5.png'),
        );
        
        foreach ($default_categories as $cat) {
            $term = wp_insert_term($cat['name'], 'demo_product_category');
            if (!is_wp_error($term)) {
                $image_url = get_template_directory_uri() . '/assets/images/' . $cat['image'];
                update_term_meta($term['term_id'], 'category_image', $image_url);
            }
        }
    }
}
add_action('admin_init', 'nafhat_init_default_categories');

/**
 * Add Demo Products Admin Menu
 */
function nafhat_add_demo_products_menu() {
    add_submenu_page(
        'themes.php',
        __('منتجات ديمو', 'nafhat'),
        __('منتجات ديمو', 'nafhat'),
        'manage_options',
        'demo-products',
        'nafhat_demo_products_page'
    );
}
add_action('admin_menu', 'nafhat_add_demo_products_menu');

/**
 * Delete All Demo Products and Categories
 */
function nafhat_delete_all_demo_products() {
    // Delete WooCommerce demo products (marked with _nafhat_demo_product)
    if (class_exists('WooCommerce')) {
        $products = wc_get_products(array(
            'limit' => -1,
            'status' => 'any',
            'meta_key' => '_nafhat_demo_product',
            'meta_value' => 'yes',
        ));
        
        foreach ($products as $product) {
            // Delete product reviews
            $comments = get_comments(array(
                'post_id' => $product->get_id(),
                'type' => 'review',
            ));
            foreach ($comments as $comment) {
                wp_delete_comment($comment->comment_ID, true);
            }
            
            // Delete product
            $product->delete(true);
        }
        
        // Delete WooCommerce categories that were created by demo import
        // (Only delete empty categories to avoid deleting user-created ones)
        $categories = get_terms(array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
        ));
        
        if (!empty($categories) && !is_wp_error($categories)) {
            $demo_category_names = array(
                __('المكياج', 'nafhat'),
                __('وصل حديثا', 'nafhat'),
                __('العطور', 'nafhat'),
                __('الاكثر مبيعا', 'nafhat'),
                __('منتجات العناية', 'nafhat'),
            );
            
            foreach ($categories as $category) {
                // Only delete if it's a demo category and has no products
                if (in_array($category->name, $demo_category_names) && $category->count == 0) {
                    wp_delete_term($category->term_id, 'product_cat');
                }
            }
        }
    }
    
    // Delete old demo_product posts (legacy)
    $demo_products = get_posts(array(
        'post_type'      => 'demo_product',
        'posts_per_page' => -1,
        'post_status'    => 'any',
    ));
    
    foreach ($demo_products as $product) {
        wp_delete_post($product->ID, true);
    }
    
    // Delete old demo categories (legacy)
    $demo_categories = get_terms(array(
        'taxonomy'   => 'demo_product_category',
        'hide_empty' => false,
    ));
    
    if (!empty($demo_categories) && !is_wp_error($demo_categories)) {
        foreach ($demo_categories as $category) {
            wp_delete_term($category->term_id, 'demo_product_category');
        }
    }
    
    // Delete brands from taxonomy
    if (taxonomy_exists('product_brand')) {
        $brands = get_terms(array(
            'taxonomy'   => 'product_brand',
            'hide_empty' => false,
            'meta_key'   => '_nafhat_demo_brand',
            'meta_value' => 'yes',
        ));
        
        if (!empty($brands) && !is_wp_error($brands)) {
            foreach ($brands as $brand) {
                wp_delete_term($brand->term_id, 'product_brand');
            }
        }
    }
    
    // Legacy: Delete old option-based brands
    delete_option('nafhat_demo_brands');
    
    return true;
}

/**
 * Get Product Brands from Taxonomy
 */
function nafhat_get_demo_brands() {
    if (!taxonomy_exists('product_brand')) {
        return array();
    }
    
    $brands = get_terms(array(
        'taxonomy'   => 'product_brand',
        'hide_empty' => false,
    ));
    
    if (empty($brands) || is_wp_error($brands)) {
        return array();
    }
    
    $result = array();
    foreach ($brands as $brand) {
        $image_id = get_term_meta($brand->term_id, 'brand_image', true);
        $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
        
        $result[] = array(
            'id'    => $brand->term_id,
            'name'  => $brand->name,
            'image' => $image_url,
            'slug'  => $brand->slug,
        );
    }
    
    return $result;
}

/**
 * Import Brands from Design Files to WooCommerce Taxonomy
 */
function nafhat_import_brands_from_design() {
    if (!class_exists('WooCommerce')) {
        return false;
    }
    
    // Make sure taxonomy is registered
    if (!taxonomy_exists('product_brand')) {
        nafhat_register_product_brand_taxonomy();
    }
    
    // Use the design files path - images are in nfhat-store/assets/
    $design_assets_path = get_template_directory() . '/nfhat-store/assets/';
    
    // Define brands from design (matching front-page.php)
    $brands_data = array(
        array('name' => 'Hugo Boss', 'image' => 'boss-logo.png'),
        array('name' => 'Calvin Klein', 'image' => 'calvin-logo.png'),
        array('name' => 'Dior', 'image' => 'dior-logo.png'),
        array('name' => 'Valentino', 'image' => 'valentino-logo.png'),
        array('name' => 'Tom Ford', 'image' => 'tom-ford-logo.png'),
        array('name' => 'Chanel', 'image' => 'channel-logo.png'),
        array('name' => 'Paco Rabanne', 'image' => 'paco-logo.png'),
        array('name' => 'Mugler', 'image' => 'mugler-logo.png'),
        array('name' => 'Dolce & Gabbana', 'image' => 'dolice-logo.png'),
        array('name' => 'Marc Jacobs', 'image' => 'marc-logo.png'),
    );
    
    $imported_count = 0;
    
    foreach ($brands_data as $brand_data) {
        // Check if brand already exists
        $existing_term = get_term_by('name', $brand_data['name'], 'product_brand');
        
        // Import brand image to media library
        $image_path = $design_assets_path . $brand_data['image'];
        $image_id = nafhat_import_image_to_media($image_path, $brand_data['name']);
        
        if (!$existing_term) {
            // Create new brand term
            $term = wp_insert_term($brand_data['name'], 'product_brand');
            
            if (!is_wp_error($term)) {
                $term_id = $term['term_id'];
                
                if ($image_id) {
                    update_term_meta($term_id, 'brand_image', $image_id);
                }
                
                // Mark as demo brand for easy deletion
                update_term_meta($term_id, '_nafhat_demo_brand', 'yes');
                
                $imported_count++;
            }
        } else {
            // Always update brand image for existing terms
            if ($image_id) {
                update_term_meta($existing_term->term_id, 'brand_image', $image_id);
                // Mark as demo brand if not already
                update_term_meta($existing_term->term_id, '_nafhat_demo_brand', 'yes');
            }
            $imported_count++;
        }
    }
    
    return $imported_count > 0;
}

/**
 * Demo Products Admin Page
 */
function nafhat_demo_products_page() {
    // Handle form submissions
    if (isset($_POST['nafhat_demo_product_action'])) {
        check_admin_referer('nafhat_demo_product_nonce');
        
        $action = sanitize_text_field($_POST['nafhat_demo_product_action']);
        
        if ($action === 'import_from_design') {
            // Import categories and products from design files
            $imported = nafhat_import_from_design_files();
            if ($imported) {
                echo '<div class="notice notice-success"><p>' . __('تم استيراد المنتجات والفئات من ملفات التصميم بنجاح', 'nafhat') . '</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>' . __('حدث خطأ أثناء الاستيراد', 'nafhat') . '</p></div>';
            }
        } elseif ($action === 'import_brands') {
            // Import brands from design files
            $imported = nafhat_import_brands_from_design();
            if ($imported) {
                echo '<div class="notice notice-success"><p>' . __('تم استيراد العلامات التجارية من ملفات التصميم بنجاح', 'nafhat') . '</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>' . __('حدث خطأ أثناء استيراد العلامات التجارية', 'nafhat') . '</p></div>';
            }
        } elseif ($action === 'delete_all') {
            // Delete all products and categories
            $deleted = nafhat_delete_all_demo_products();
            if ($deleted) {
                echo '<div class="notice notice-success"><p>' . __('تم حذف جميع المنتجات والفئات بنجاح', 'nafhat') . '</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>' . __('حدث خطأ أثناء الحذف', 'nafhat') . '</p></div>';
            }
        }
    }
    
    // Get counts for display
    $products_count = 0;
    $categories_count = 0;
    
    // Count WooCommerce demo products
    if (class_exists('WooCommerce')) {
        $demo_products = wc_get_products(array(
            'limit' => -1,
            'status' => 'any',
            'meta_key' => '_nafhat_demo_product',
            'meta_value' => 'yes',
            'return' => 'ids',
        ));
        $products_count = count($demo_products);
        
        // Count WooCommerce categories (excluding uncategorized)
        $uncategorized = get_term_by('slug', 'uncategorized', 'product_cat');
        $exclude_ids = $uncategorized ? array($uncategorized->term_id) : array();
        
        $categories = get_terms(array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'exclude'    => $exclude_ids,
        ));
        if (!empty($categories) && !is_wp_error($categories)) {
            $categories_count = count($categories);
        }
    }
    
    $brands = nafhat_get_demo_brands();
    $brands_count = count($brands);
    
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('منتجات ديمو', 'nafhat'); ?></h1>
        
        <div style="display: flex; gap: 20px; margin-top: 30px; flex-wrap: wrap;">
            <!-- Add Products and Categories Button -->
            <div class="card" style="flex: 1; min-width: 300px; background: #f0f9ff; border-left: 4px solid #2271b1;">
                <h2 style="margin-top: 0;"><?php esc_html_e('إضافة جميع المنتجات والفئات', 'nafhat'); ?></h2>
                <p><?php esc_html_e('اضغط على الزر أدناه لإضافة جميع المنتجات والفئات مع صورها من ملفات التصميم.', 'nafhat'); ?></p>
                <form method="post" action="" style="margin-top: 15px;">
                    <?php wp_nonce_field('nafhat_demo_product_nonce'); ?>
                    <input type="hidden" name="nafhat_demo_product_action" value="import_from_design">
                    <p class="submit" style="margin: 0;">
                        <input type="submit" class="button button-primary button-large" value="<?php esc_attr_e('إضافة جميع المنتجات والفئات', 'nafhat'); ?>" onclick="return confirm('<?php esc_attr_e('سيتم إضافة جميع المنتجات والفئات من ملفات التصميم. هل تريد المتابعة؟', 'nafhat'); ?>');" />
                    </p>
                </form>
            </div>
            
            <!-- Add Brands Button -->
            <div class="card" style="flex: 1; min-width: 300px; background: #f0fff4; border-left: 4px solid #00a32a;">
                <h2 style="margin-top: 0;"><?php esc_html_e('إضافة العلامات التجارية', 'nafhat'); ?></h2>
                <p><?php esc_html_e('اضغط على الزر أدناه لإضافة العلامات التجارية من ملفات التصميم.', 'nafhat'); ?></p>
                <form method="post" action="" style="margin-top: 15px;">
                    <?php wp_nonce_field('nafhat_demo_product_nonce'); ?>
                    <input type="hidden" name="nafhat_demo_product_action" value="import_brands">
                    <p class="submit" style="margin: 0;">
                        <input type="submit" class="button button-primary button-large" style="background: #00a32a; border-color: #00a32a;" value="<?php esc_attr_e('إضافة العلامات التجارية', 'nafhat'); ?>" onclick="return confirm('<?php esc_attr_e('سيتم إضافة العلامات التجارية من ملفات التصميم. هل تريد المتابعة؟', 'nafhat'); ?>');" />
                    </p>
                </form>
            </div>
            
            <!-- Delete All Button -->
            <div class="card" style="flex: 1; min-width: 300px; background: #fff5f5; border-left: 4px solid #dc3232;">
                <h2 style="margin-top: 0;"><?php esc_html_e('حذف جميع المنتجات والفئات', 'nafhat'); ?></h2>
                <p><?php esc_html_e('اضغط على الزر أدناه لحذف جميع المنتجات والفئات والعلامات التجارية الموجودة.', 'nafhat'); ?></p>
                <form method="post" action="" style="margin-top: 15px;">
                    <?php wp_nonce_field('nafhat_demo_product_nonce'); ?>
                    <input type="hidden" name="nafhat_demo_product_action" value="delete_all">
                    <p class="submit" style="margin: 0;">
                        <input type="submit" class="button button-link-delete button-large" style="color: #dc3232; border-color: #dc3232;" value="<?php esc_attr_e('حذف جميع المنتجات والفئات', 'nafhat'); ?>" onclick="return confirm('<?php esc_attr_e('هل أنت متأكد من حذف جميع المنتجات والفئات والعلامات التجارية؟ سيتم حذف كل شيء ولا يمكن التراجع عن هذا الإجراء.', 'nafhat'); ?>');" />
                    </p>
                </form>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="card" style="margin-top: 20px;">
            <h2><?php esc_html_e('الإحصائيات', 'nafhat'); ?></h2>
            <p>
                <strong><?php esc_html_e('عدد المنتجات:', 'nafhat'); ?></strong> <?php echo esc_html($products_count); ?><br />
                <strong><?php esc_html_e('عدد الفئات:', 'nafhat'); ?></strong> <?php echo esc_html($categories_count); ?><br />
                <strong><?php esc_html_e('عدد العلامات التجارية:', 'nafhat'); ?></strong> <?php echo esc_html($brands_count); ?>
            </p>
        </div>
    </div>
    <?php
}

/**
 * Enable UI for Demo Products (for editing)
 */
function nafhat_enable_demo_product_ui() {
    global $wp_post_types;
    if (isset($wp_post_types['demo_product'])) {
        $wp_post_types['demo_product']->show_ui = true;
        $wp_post_types['demo_product']->show_in_menu = false; // Still hide from main menu
    }
}
add_action('admin_init', 'nafhat_enable_demo_product_ui');

/**
 * Add Meta Boxes for Demo Products
 */
function nafhat_add_demo_product_meta_boxes() {
    add_meta_box(
        'demo_product_details',
        __('تفاصيل المنتج', 'nafhat'),
        'nafhat_demo_product_meta_box_callback',
        'demo_product',
        'normal',
        'high'
    );
    
    add_meta_box(
        'demo_product_reviews',
        __('التقييمات', 'nafhat'),
        'nafhat_demo_product_reviews_meta_box_callback',
        'demo_product',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nafhat_add_demo_product_meta_boxes');

/**
 * Demo Product Meta Box Callback
 */
function nafhat_demo_product_meta_box_callback($post) {
    wp_nonce_field('nafhat_demo_product_meta', 'nafhat_demo_product_meta_nonce');
    
    $price = get_post_meta($post->ID, '_demo_product_price', true);
    $image = get_post_meta($post->ID, '_demo_product_image', true);
    $image_id = get_post_meta($post->ID, '_demo_product_image_id', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="demo_product_price"><?php esc_html_e('السعر', 'nafhat'); ?></label></th>
            <td>
                <input type="number" id="demo_product_price" name="demo_product_price" value="<?php echo esc_attr($price); ?>" class="regular-text" step="0.01" />
            </td>
        </tr>
        <tr>
            <th><label for="demo_product_image"><?php esc_html_e('صورة المنتج', 'nafhat'); ?></label></th>
            <td>
                <input type="url" id="demo_product_image" name="demo_product_image" value="<?php echo esc_url($image); ?>" class="regular-text" />
                <button type="button" class="button" onclick="nafhatMediaUploader('demo_product_image', 'demo_product_image_id')"><?php esc_html_e('اختر صورة', 'nafhat'); ?></button>
                <input type="hidden" id="demo_product_image_id" name="demo_product_image_id" value="<?php echo esc_attr($image_id); ?>" />
                <?php if ($image) : ?>
                    <p><img src="<?php echo esc_url($image); ?>" style="max-width: 200px; margin-top: 10px;" /></p>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Demo Product Reviews Meta Box Callback
 */
function nafhat_demo_product_reviews_meta_box_callback($post) {
    $reviews = get_post_meta($post->ID, '_demo_product_reviews', true);
    if (!is_array($reviews)) {
        $reviews = array();
    }
    
    // Ensure at least 3 reviews
    while (count($reviews) < 3) {
        $reviews[] = array('customer_name' => '', 'rating' => 5, 'review' => '');
    }
    
    ?>
    <p><em><?php esc_html_e('ملاحظة: يجب إضافة 3 تقييمات على الأقل لكل منتج', 'nafhat'); ?></em></p>
    <div id="demo-product-reviews-container">
        <?php foreach ($reviews as $index => $review) : ?>
            <div class="demo-review-item" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px;">
                <p>
                    <label><strong><?php esc_html_e('اسم العميل:', 'nafhat'); ?></strong>
                    <input type="text" name="demo_reviews[<?php echo esc_attr($index); ?>][customer_name]" value="<?php echo esc_attr($review['customer_name'] ?? ''); ?>" class="regular-text" required /></label>
                </p>
                <p>
                    <label><strong><?php esc_html_e('التقييم:', 'nafhat'); ?></strong>
                    <select name="demo_reviews[<?php echo esc_attr($index); ?>][rating]">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <option value="<?php echo esc_attr($i); ?>" <?php selected($review['rating'] ?? 5, $i); ?>><?php echo esc_html($i); ?> <?php esc_html_e('نجوم', 'nafhat'); ?></option>
                        <?php endfor; ?>
                    </select></label>
                </p>
                <p>
                    <label><strong><?php esc_html_e('التعليق:', 'nafhat'); ?></strong><br />
                    <textarea name="demo_reviews[<?php echo esc_attr($index); ?>][review]" rows="4" class="large-text" required><?php echo esc_textarea($review['review'] ?? ''); ?></textarea></label>
                </p>
                <?php if (count($reviews) > 3) : ?>
                    <button type="button" class="button remove-review"><?php esc_html_e('حذف التقييم', 'nafhat'); ?></button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button" id="add-review-btn"><?php esc_html_e('إضافة تقييم', 'nafhat'); ?></button>
    
    <script>
    jQuery(document).ready(function($) {
        var reviewIndex = <?php echo count($reviews); ?>;
        
        $('#add-review-btn').on('click', function() {
            var reviewHtml = '<div class="demo-review-item" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px;">' +
                '<p><label><strong><?php esc_html_e('اسم العميل:', 'nafhat'); ?></strong> ' +
                '<input type="text" name="demo_reviews[' + reviewIndex + '][customer_name]" class="regular-text" /></label></p>' +
                '<p><label><strong><?php esc_html_e('التقييم:', 'nafhat'); ?></strong> ' +
                '<select name="demo_reviews[' + reviewIndex + '][rating]">' +
                '<?php for ($i = 1; $i <= 5; $i++) : ?><option value="<?php echo esc_attr($i); ?>"><?php echo esc_html($i); ?> <?php esc_html_e('نجوم', 'nafhat'); ?></option><?php endfor; ?>' +
                '</select></label></p>' +
                '<p><label><strong><?php esc_html_e('التعليق:', 'nafhat'); ?></strong><br />' +
                '<textarea name="demo_reviews[' + reviewIndex + '][review]" rows="4" class="large-text"></textarea></label></p>' +
                '<button type="button" class="button remove-review"><?php esc_html_e('حذف التقييم', 'nafhat'); ?></button>' +
                '</div>';
            $('#demo-product-reviews-container').append(reviewHtml);
            reviewIndex++;
        });
        
        $(document).on('click', '.remove-review', function() {
            $(this).closest('.demo-review-item').remove();
        });
    });
    </script>
    <?php
}

/**
 * Save Demo Product Meta
 */
function nafhat_save_demo_product_meta($post_id) {
    // Check nonce
    if (!isset($_POST['nafhat_demo_product_meta_nonce']) || !wp_verify_nonce($_POST['nafhat_demo_product_meta_nonce'], 'nafhat_demo_product_meta')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save price
    if (isset($_POST['demo_product_price'])) {
        update_post_meta($post_id, '_demo_product_price', sanitize_text_field($_POST['demo_product_price']));
    }
    
    // Save image
    if (isset($_POST['demo_product_image'])) {
        update_post_meta($post_id, '_demo_product_image', esc_url_raw($_POST['demo_product_image']));
    }
    
    if (isset($_POST['demo_product_image_id'])) {
        update_post_meta($post_id, '_demo_product_image_id', intval($_POST['demo_product_image_id']));
        if (intval($_POST['demo_product_image_id']) > 0) {
            set_post_thumbnail($post_id, intval($_POST['demo_product_image_id']));
        }
    }
    
    // Save reviews (ensure at least 3 reviews)
    if (isset($_POST['demo_reviews']) && is_array($_POST['demo_reviews'])) {
        $reviews = array();
        foreach ($_POST['demo_reviews'] as $review) {
            if (!empty($review['customer_name']) && !empty($review['review'])) {
                $reviews[] = array(
                    'customer_name' => sanitize_text_field($review['customer_name']),
                    'rating'        => intval($review['rating']),
                    'review'        => sanitize_textarea_field($review['review']),
                );
            }
        }
        
        // Ensure at least 3 reviews
        if (count($reviews) < 3) {
            // Add default reviews if less than 3
            $default_review = __('مستوحى من عطر بولغري تايقر الرائحه ممتازه ونقيه ثبات متوسط فوحان متوسط ،فيه بدائل لتايقر ممتازه مثل عطر تايقر من عايد فوحان وثبات ونقاوة رائحه ممتازه وهو افضل بديل', 'nafhat');
            while (count($reviews) < 3) {
                $reviews[] = array(
                    'customer_name' => __('عمر العميل', 'nafhat'),
                    'rating'        => 5,
                    'review'        => $default_review,
                );
            }
        }
        
        update_post_meta($post_id, '_demo_product_reviews', $reviews);
    } else {
        // If no reviews, add 3 default ones
        $default_review = __('مستوحى من عطر بولغري تايقر الرائحه ممتازه ونقيه ثبات متوسط فوحان متوسط ،فيه بدائل لتايقر ممتازه مثل عطر تايقر من عايد فوحان وثبات ونقاوة رائحه ممتازه وهو افضل بديل', 'nafhat');
        $reviews = array(
            array('customer_name' => __('عمر العميل', 'nafhat'), 'rating' => 5, 'review' => $default_review),
            array('customer_name' => __('عمر العميل', 'nafhat'), 'rating' => 5, 'review' => $default_review),
            array('customer_name' => __('عمر العميل', 'nafhat'), 'rating' => 5, 'review' => $default_review),
        );
        update_post_meta($post_id, '_demo_product_reviews', $reviews);
    }
}
add_action('save_post_demo_product', 'nafhat_save_demo_product_meta');

/**
 * Add Category Image Field to Taxonomy
 */
function nafhat_add_category_image_field($term) {
    $image = get_term_meta($term->term_id, 'category_image', true);
    $image_id = get_term_meta($term->term_id, 'category_image_id', true);
    ?>
    <tr class="form-field">
        <th scope="row">
            <label for="category_image"><?php esc_html_e('صورة الفئة', 'nafhat'); ?></label>
        </th>
        <td>
            <input type="url" id="category_image" name="category_image" value="<?php echo esc_url($image); ?>" class="regular-text" />
            <button type="button" class="button" onclick="nafhatMediaUploader('category_image', 'category_image_id')"><?php esc_html_e('اختر صورة', 'nafhat'); ?></button>
            <input type="hidden" id="category_image_id" name="category_image_id" value="<?php echo esc_attr($image_id); ?>" />
            <?php if ($image) : ?>
                <p><img src="<?php echo esc_url($image); ?>" style="max-width: 200px; margin-top: 10px;" /></p>
            <?php endif; ?>
        </td>
    </tr>
    <?php
}
add_action('demo_product_category_add_form_fields', 'nafhat_add_category_image_field');
add_action('demo_product_category_edit_form_fields', 'nafhat_add_category_image_field');

/**
 * Save Category Image
 */
function nafhat_save_category_image($term_id) {
    if (isset($_POST['category_image'])) {
        update_term_meta($term_id, 'category_image', esc_url_raw($_POST['category_image']));
    }
    if (isset($_POST['category_image_id'])) {
        update_term_meta($term_id, 'category_image_id', intval($_POST['category_image_id']));
    }
}
add_action('created_demo_product_category', 'nafhat_save_category_image');
add_action('edited_demo_product_category', 'nafhat_save_category_image');

/**
 * Enqueue Media Uploader Script in Admin
 */
function nafhat_enqueue_admin_scripts($hook) {
    if ($hook === 'appearance_page_demo-products' || $hook === 'post.php' || $hook === 'post-new.php') {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'nafhat_enqueue_admin_scripts');

/**
 * Get Demo Products
 */
function nafhat_get_demo_products($args = array()) {
    $defaults = array(
        'posts_per_page' => 4,
        'post_type'      => 'demo_product',
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    
    $args = wp_parse_args($args, $defaults);
    return get_posts($args);
}

/**
 * Get Demo Product Categories
 */
function nafhat_get_demo_categories() {
    return get_terms(array(
        'taxonomy'   => 'demo_product_category',
        'hide_empty' => false,
    ));
}

/**
 * Get Demo Product Reviews
 */
function nafhat_get_demo_product_reviews($product_id) {
    $reviews = get_post_meta($product_id, '_demo_product_reviews', true);
    return is_array($reviews) ? $reviews : array();
}

/**
 * Get All Demo Product Reviews (for reviews section)
 */
function nafhat_get_all_demo_reviews($limit = 3) {
    $products = get_posts(array(
        'post_type'      => 'demo_product',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ));
    
    $all_reviews = array();
    foreach ($products as $product) {
        $reviews = nafhat_get_demo_product_reviews($product->ID);
        foreach ($reviews as $review) {
            $all_reviews[] = $review;
        }
    }
    
    // Shuffle and limit (0 means no limit)
    shuffle($all_reviews);
    if ($limit > 0) {
        return array_slice($all_reviews, 0, $limit);
    }
    return $all_reviews;
}

/**
 * Import image from theme to media library
 */
function nafhat_import_image_to_media($image_path, $title = '') {
    // Check if file exists
    if (!file_exists($image_path)) {
        return 0;
    }
    
    // Get file info
    $file_info = pathinfo($image_path);
    $filename = $file_info['basename'];
    
    // Check if image already exists in media library
    $existing = get_posts(array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'meta_query' => array(
            array(
                'key' => '_nafhat_original_file',
                'value' => $filename,
            ),
        ),
        'posts_per_page' => 1,
    ));
    
    if (!empty($existing)) {
        return $existing[0]->ID;
    }
    
    // Read file
    $file_content = file_get_contents($image_path);
    
    // Upload to WordPress
    $upload = wp_upload_bits($filename, null, $file_content);
    
    if (!empty($upload['error'])) {
        return 0;
    }
    
    // Get file type
    $file_type = wp_check_filetype($filename, null);
    
    // Create attachment
    $attachment = array(
        'post_mime_type' => $file_type['type'],
        'post_title'     => $title ? $title : sanitize_file_name($file_info['filename']),
        'post_content'   => '',
        'post_status'    => 'inherit',
    );
    
    $attach_id = wp_insert_attachment($attachment, $upload['file']);
    
    if ($attach_id) {
        // Generate attachment metadata
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
        wp_update_attachment_metadata($attach_id, $attach_data);
        
        // Mark as imported from theme
        update_post_meta($attach_id, '_nafhat_original_file', $filename);
    }
    
    return $attach_id;
}

/**
 * Import Categories and Products from Design Files to WooCommerce
 */
function nafhat_import_from_design_files() {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return false;
    }
    
    // Use the design files path - images are in nfhat-store/assets/
    $design_assets_path = get_template_directory() . '/nfhat-store/assets/';
    $design_assets_uri = get_template_directory_uri() . '/nfhat-store/assets/';
    
    // Define categories from design (matching index.html)
    $categories_data = array(
        array('name' => __('المكياج', 'nafhat'), 'image' => 'cat1.png'),
        array('name' => __('وصل حديثا', 'nafhat'), 'image' => 'cat2.png'),
        array('name' => __('العطور', 'nafhat'), 'image' => 'cat3.png'),
        array('name' => __('الاكثر مبيعا', 'nafhat'), 'image' => 'cat4.png'),
        array('name' => __('منتجات العناية', 'nafhat'), 'image' => 'cat5.png'),
    );
    
    // Import categories to WooCommerce
    $category_ids = array();
    foreach ($categories_data as $cat_data) {
        // Check if category already exists in WooCommerce
        $existing_term = get_term_by('name', $cat_data['name'], 'product_cat');
        
        if (!$existing_term) {
            $term = wp_insert_term($cat_data['name'], 'product_cat');
            
            if (!is_wp_error($term)) {
                $term_id = $term['term_id'];
                
                // Import category image to media library
                $image_path = $design_assets_path . $cat_data['image'];
                $image_id = nafhat_import_image_to_media($image_path, $cat_data['name']);
                
                if ($image_id) {
                    update_term_meta($term_id, 'thumbnail_id', $image_id);
                }
                
                $category_ids[$cat_data['name']] = $term_id;
            }
        } else {
            $category_ids[$cat_data['name']] = $existing_term->term_id;
            
            // Update image if not set
            $existing_thumbnail = get_term_meta($existing_term->term_id, 'thumbnail_id', true);
            if (!$existing_thumbnail) {
                $image_path = $design_assets_path . $cat_data['image'];
                $image_id = nafhat_import_image_to_media($image_path, $cat_data['name']);
                if ($image_id) {
                    update_term_meta($existing_term->term_id, 'thumbnail_id', $image_id);
                }
            }
        }
    }
    
    // Define products from design
    $products_data = array(
        array(
            'title' => __('غوتشي 1', 'nafhat'),
            'description' => __('عطر فلورا جورجس جاردينيا إنتنس من غوتشي أو دو برفيوم للنساء 100 مل', 'nafhat'),
            'price' => '1000',
            'image' => 'pro1.png',
            'category' => __('العطور', 'nafhat'),
        ),
        array(
            'title' => __('غوتشي 2', 'nafhat'),
            'description' => __('عطر فلورا جورجس جاردينيا إنتنس من غوتشي أو دو برفيوم للنساء 100 مل', 'nafhat'),
            'price' => '1000',
            'image' => 'pro2.png',
            'category' => __('العطور', 'nafhat'),
        ),
        array(
            'title' => __('غوتشي 3', 'nafhat'),
            'description' => __('عطر فلورا جورجس جاردينيا إنتنس من غوتشي أو دو برفيوم للنساء 100 مل', 'nafhat'),
            'price' => '1000',
            'image' => 'pro3.png',
            'category' => __('العطور', 'nafhat'),
        ),
        array(
            'title' => __('غوتشي 4', 'nafhat'),
            'description' => __('عطر فلورا جورجس جاردينيا إنتنس من غوتشي أو دو برفيوم للنساء 100 مل', 'nafhat'),
            'price' => '1000',
            'image' => 'pro4.png',
            'category' => __('العطور', 'nafhat'),
        ),
    );
    
    // Default review text from design
    $default_review_text = __('مستوحى من عطر بولغري تايقر الرائحه ممتازه ونقيه ثبات متوسط فوحان متوسط ،فيه بدائل لتايقر ممتازه مثل عطر تايقر من عايد فوحان وثبات ونقاوة رائحه ممتازه وهو افضل بديل', 'nafhat');
    
    // Import products to WooCommerce
    $imported_count = 0;
    
    foreach ($products_data as $product_data) {
        // Check if product already exists
        $existing_products = wc_get_products(array(
            'name' => $product_data['title'],
            'limit' => 1,
        ));
        
        if (empty($existing_products)) {
            // Create WooCommerce product
            $product = new WC_Product_Simple();
            $product->set_name($product_data['title']);
            $product->set_description($product_data['description']);
            $product->set_short_description($product_data['description']);
            $product->set_regular_price($product_data['price']);
            $product->set_price($product_data['price']);
            $product->set_status('publish');
            $product->set_stock_status('instock');
            $product->set_manage_stock(false);
            
            // Set category
            if (isset($category_ids[$product_data['category']])) {
                $product->set_category_ids(array($category_ids[$product_data['category']]));
            }
            
            // Import product image to media library
            $image_path = $design_assets_path . $product_data['image'];
            $image_id = nafhat_import_image_to_media($image_path, $product_data['title']);
            
            if ($image_id) {
                $product->set_image_id($image_id);
            }
            
            // Save product
            $product_id = $product->save();
            
            if ($product_id) {
                // Add reviews as comments
                for ($i = 0; $i < 3; $i++) {
                    $comment_data = array(
                        'comment_post_ID' => $product_id,
                        'comment_author' => __('عمر العميل', 'nafhat'),
                        'comment_author_email' => 'customer' . ($i + 1) . '@example.com',
                        'comment_content' => $default_review_text,
                        'comment_type' => 'review',
                        'comment_approved' => 1,
                    );
                    
                    $comment_id = wp_insert_comment($comment_data);
                    
                    if ($comment_id) {
                        update_comment_meta($comment_id, 'rating', 5);
                    }
                }
                
                // Mark as demo product
                update_post_meta($product_id, '_nafhat_demo_product', 'yes');
                
                $imported_count++;
            }
        }
    }
    
    return $imported_count > 0 || !empty($category_ids);
}
