<?php
/**
 * Demo Products Management
 *
 * @package TechnoSouqTheme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add Demo Products menu page
 */
function techno_souq_add_demo_products_menu() {
    add_theme_page(
        __('منتجات ديمو', 'techno-souq-theme'),
        __('منتجات ديمو', 'techno-souq-theme'),
        'edit_theme_options',
        'techno-souq-demo-products',
        'techno_souq_demo_products_page'
    );
}
add_action('admin_menu', 'techno_souq_add_demo_products_menu');

/**
 * Demo Products Admin Page
 */
function techno_souq_demo_products_page() {
    // Handle form submission
    if (isset($_POST['techno_souq_import_demo_products']) && check_admin_referer('techno_souq_demo_products_nonce', 'techno_souq_demo_products_nonce')) {
        techno_souq_import_demo_products();
        echo '<div class="notice notice-success"><p>' . __('تم استيراد المنتجات بنجاح', 'techno-souq-theme') . '</p></div>';
    }
    
    // Handle reset
    if (isset($_POST['techno_souq_reset_demo_products']) && check_admin_referer('techno_souq_reset_demo_products_nonce', 'techno_souq_reset_demo_products_nonce')) {
        techno_souq_reset_demo_products();
        echo '<div class="notice notice-success"><p>' . __('تم حذف جميع المنتجات الديمو', 'techno-souq-theme') . '</p></div>';
    }
    
    // Get demo products flag
    $has_demo_products = get_option('techno_souq_has_demo_products', false);
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('منتجات ديمو', 'techno-souq-theme'); ?></h1>
        
        <div class="card">
            <h2><?php echo esc_html__('استيراد المنتجات الديمو', 'techno-souq-theme'); ?></h2>
            <p><?php echo esc_html__('سيتم استيراد جميع المنتجات والفئات من ملفات التصميم.', 'techno-souq-theme'); ?></p>
            
            <form method="post" action="">
                <?php wp_nonce_field('techno_souq_demo_products_nonce', 'techno_souq_demo_products_nonce'); ?>
                <?php submit_button(__('استيراد المنتجات الديمو', 'techno-souq-theme'), 'primary', 'techno_souq_import_demo_products'); ?>
            </form>
        </div>
        
        <?php if ($has_demo_products) : ?>
        <div class="card">
            <h2><?php echo esc_html__('إعادة تعيين', 'techno-souq-theme'); ?></h2>
            <p><?php echo esc_html__('سيتم حذف جميع المنتجات والفئات الديمو.', 'techno-souq-theme'); ?></p>
            
            <form method="post" action="" onsubmit="return confirm('<?php echo esc_js(__('هل أنت متأكد من حذف جميع المنتجات الديمو؟', 'techno-souq-theme')); ?>');">
                <?php wp_nonce_field('techno_souq_reset_demo_products_nonce', 'techno_souq_reset_demo_products_nonce'); ?>
                <?php submit_button(__('حذف جميع المنتجات الديمو', 'techno-souq-theme'), 'secondary', 'techno_souq_reset_demo_products'); ?>
            </form>
        </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Import demo products
 */
function techno_souq_import_demo_products() {
    // Demo products data with subcategories and brands
    $demo_products = array(
        // Electronics - Smartphones
        array(
            'name' => 'هاتف ذكي',
            'price' => 2000,
            'regular_price' => 2500,
            'category' => 'electronics',
            'subcategory' => 'smartphones',
            'brand' => 'samsung',
            'image' => 'tv.png',
        ),
        // Electronics - Tablets
        array(
            'name' => 'الأجهزة اللوحية',
            'price' => 1800,
            'regular_price' => 2200,
            'category' => 'electronics',
            'subcategory' => 'tablets',
            'brand' => 'vestel',
            'image' => 'wash.png',
        ),
        // Electronics - Laptops
        array(
            'name' => 'لابتوب محمول',
            'price' => 4500,
            'regular_price' => 5000,
            'category' => 'electronics',
            'subcategory' => 'laptops',
            'brand' => 'hp',
            'image' => 'tv.png',
        ),
        // Electronics - Accessories
        array(
            'name' => 'الإكسسوارات',
            'price' => 150,
            'regular_price' => 200,
            'category' => 'electronics',
            'subcategory' => 'accessories',
            'brand' => 'samsung',
            'image' => '20.png',
        ),
        array(
            'name' => 'شاشة تلفزيون ذكية 55 بوصة',
            'price' => 2500,
            'regular_price' => 3000,
            'category' => 'electronics',
            'subcategory' => 'accessories',
            'brand' => 'lg',
            'image' => 'tv.png',
        ),
        array(
            'name' => 'تلفزيون 4K فائق الدقة',
            'price' => 2800,
            'regular_price' => 3200,
            'category' => 'electronics',
            'subcategory' => 'accessories',
            'brand' => 'sony',
            'image' => 'tv.png',
        ),
        // Appliances - Washing Machines
        array(
            'name' => 'غسالة ملابس أوتوماتيك',
            'price' => 1200,
            'regular_price' => 1500,
            'category' => 'appliances',
            'subcategory' => 'washing-machines',
            'brand' => 'vestel',
            'image' => 'wash.png',
        ),
        array(
            'name' => 'غسالة ملابس تعبئة علوية',
            'price' => 1500,
            'regular_price' => 1800,
            'category' => 'appliances',
            'subcategory' => 'washing-machines',
            'brand' => 'lg',
            'image' => 'wash.png',
        ),
        // Appliances - Air Conditioners
        array(
            'name' => 'مكيف هواء سبليت',
            'price' => 3000,
            'regular_price' => 3500,
            'category' => 'appliances',
            'subcategory' => 'air-conditioners',
            'brand' => 'samsung',
            'image' => 'conditioner.png',
        ),
        array(
            'name' => 'مكيف شباك قوي',
            'price' => 1800,
            'regular_price' => 0,
            'category' => 'appliances',
            'subcategory' => 'air-conditioners',
            'brand' => 'vestel',
            'image' => 'conditioner.png',
        ),
        // Appliances - Vacuum Cleaners
        array(
            'name' => 'مكنسة كهربائية لاسلكية',
            'price' => 800,
            'regular_price' => 1000,
            'category' => 'appliances',
            'subcategory' => 'vacuum-cleaners',
            'brand' => 'dyson',
            'image' => 'vacup_cleaner.png',
        ),
        array(
            'name' => 'مكنسة روبوت ذكية',
            'price' => 1200,
            'regular_price' => 0,
            'category' => 'appliances',
            'subcategory' => 'vacuum-cleaners',
            'brand' => 'samsung',
            'image' => 'vacup_cleaner.png',
        ),
        // Appliances - Refrigerators
        array(
            'name' => 'ثلاجة كبيرة',
            'price' => 3500,
            'regular_price' => 0,
            'category' => 'appliances',
            'subcategory' => 'refrigerators',
            'brand' => 'lg',
            'image' => 'tv.png',
        ),
        // Appliances - Microwaves
        array(
            'name' => 'فرن ميكروويف',
            'price' => 600,
            'regular_price' => 750,
            'category' => 'appliances',
            'subcategory' => 'microwaves',
            'brand' => 'samsung',
            'image' => 'tv.png',
        ),
        // Vegetables - Tomatoes
        array(
            'name' => 'طماطم طازجة',
            'price' => 15,
            'regular_price' => 20,
            'category' => 'vegetables',
            'subcategory' => 'tomatoes',
            'brand' => 'fresh',
            'image' => '10.png',
        ),
        // Vegetables - Cucumbers
        array(
            'name' => 'خيار أخضر',
            'price' => 10,
            'regular_price' => 0,
            'category' => 'vegetables',
            'subcategory' => 'cucumbers',
            'brand' => 'fresh',
            'image' => '20.png',
        ),
        // Vegetables - Peppers
        array(
            'name' => 'فلفل',
            'price' => 12,
            'regular_price' => 15,
            'category' => 'vegetables',
            'subcategory' => 'peppers',
            'brand' => 'fresh',
            'image' => '10.png',
        ),
        // Vegetables - Onions
        array(
            'name' => 'بصل',
            'price' => 8,
            'regular_price' => 10,
            'category' => 'vegetables',
            'subcategory' => 'onions',
            'brand' => 'fresh',
            'image' => '10.png',
        ),
        // Vegetables - Potatoes
        array(
            'name' => 'بطاطس',
            'price' => 9,
            'regular_price' => 12,
            'category' => 'vegetables',
            'subcategory' => 'potatoes',
            'brand' => 'fresh',
            'image' => '10.png',
        ),
        // Vegetables - Carrots
        array(
            'name' => 'جزر',
            'price' => 11,
            'regular_price' => 14,
            'category' => 'vegetables',
            'subcategory' => 'carrots',
            'brand' => 'fresh',
            'image' => '20.png',
        ),
        // Fruits - Apples
        array(
            'name' => 'تفاح أحمر',
            'price' => 25,
            'regular_price' => 30,
            'category' => 'fruits',
            'subcategory' => 'apples',
            'brand' => 'fresh',
            'image' => '20.png',
        ),
        // Fruits - Oranges
        array(
            'name' => 'برتقال',
            'price' => 20,
            'regular_price' => 25,
            'category' => 'fruits',
            'subcategory' => 'oranges',
            'brand' => 'fresh',
            'image' => '20.png',
        ),
        // Fruits - Bananas
        array(
            'name' => 'موز',
            'price' => 18,
            'regular_price' => 22,
            'category' => 'fruits',
            'subcategory' => 'bananas',
            'brand' => 'fresh',
            'image' => '20.png',
        ),
        // Fruits - Grapes
        array(
            'name' => 'عنب',
            'price' => 30,
            'regular_price' => 35,
            'category' => 'fruits',
            'subcategory' => 'grapes',
            'brand' => 'fresh',
            'image' => '20.png',
        ),
        // Fruits - Strawberries
        array(
            'name' => 'فراولة',
            'price' => 35,
            'regular_price' => 40,
            'category' => 'fruits',
            'subcategory' => 'strawberries',
            'brand' => 'fresh',
            'image' => '20.png',
        ),
        // Fruits - Mangoes
        array(
            'name' => 'مانجو',
            'price' => 40,
            'regular_price' => 45,
            'category' => 'fruits',
            'subcategory' => 'mangoes',
            'brand' => 'fresh',
            'image' => '20.png',
        ),
    );
    
    // Create main categories
    $categories = array(
        'electronics' => 'إلكترونيات',
        'appliances' => 'أجهزة منزلية',
        'vegetables' => 'خضروات',
        'fruits' => 'فواكه',
    );
    
    // Create subcategories for each main category
    $subcategories = array(
        'electronics' => array(
            'smartphones' => 'الهواتف الذكية',
            'tablets' => 'الأجهزة اللوحية',
            'laptops' => 'الكمبيوترات المحمولة',
            'accessories' => 'الإكسسوارات',
        ),
        'appliances' => array(
            'washing-machines' => 'غسالات',
            'air-conditioners' => 'مكيفات',
            'vacuum-cleaners' => 'مكنسة كهربائية',
            'refrigerators' => 'ثلاجات',
            'microwaves' => 'أفران ميكروويف',
        ),
        'vegetables' => array(
            'tomatoes' => 'طماطم',
            'cucumbers' => 'خيار',
            'peppers' => 'فلفل',
            'onions' => 'بصل',
            'potatoes' => 'بطاطس',
            'carrots' => 'جزر',
        ),
        'fruits' => array(
            'apples' => 'تفاح',
            'oranges' => 'برتقال',
            'bananas' => 'موز',
            'grapes' => 'عنب',
            'strawberries' => 'فراولة',
            'mangoes' => 'مانجو',
        ),
    );
    
    // Category images mapping
    $category_images = array(
        'electronics' => 'tv.png',
        'appliances' => 'wash.png',
        'vegetables' => '10.png',
        'fruits' => '20.png',
    );
    
    // Create main categories
    $category_ids = array();
    foreach ($categories as $slug => $name) {
        $term = wp_insert_term($name, 'product_cat', array('slug' => $slug));
        if (!is_wp_error($term)) {
            $category_ids[$slug] = $term['term_id'];
        } else {
            // Term already exists
            $term = get_term_by('slug', $slug, 'product_cat');
            if ($term) {
                $category_ids[$slug] = $term->term_id;
            }
        }
        
        // Set category image
        if (isset($category_ids[$slug]) && isset($category_images[$slug])) {
            $image_url = techno_souq_asset_url($category_images[$slug]);
            $image_id = techno_souq_upload_image_from_url($image_url, $name);
            if ($image_id) {
                update_term_meta($category_ids[$slug], 'thumbnail_id', $image_id);
            }
        }
    }
    
    // Subcategory images mapping
    $subcategory_images = array(
        'electronics' => array(
            'smartphones' => 'tv.png',
            'tablets' => 'wash.png',
            'laptops' => 'tv.png',
            'accessories' => '20.png',
        ),
        'appliances' => array(
            'washing-machines' => 'wash.png',
            'air-conditioners' => 'conditioner.png',
            'vacuum-cleaners' => 'vacup_cleaner.png',
            'refrigerators' => 'tv.png',
            'microwaves' => 'tv.png',
        ),
        'vegetables' => array(
            'tomatoes' => '10.png',
            'cucumbers' => '20.png',
            'peppers' => '10.png',
            'onions' => '10.png',
            'potatoes' => '10.png',
            'carrots' => '20.png',
        ),
        'fruits' => array(
            'apples' => '20.png',
            'oranges' => '20.png',
            'bananas' => '20.png',
            'grapes' => '20.png',
            'strawberries' => '20.png',
            'mangoes' => '20.png',
        ),
    );
    
    // Create subcategories
    $subcategory_ids = array();
    foreach ($subcategories as $parent_slug => $subs) {
        if (!isset($category_ids[$parent_slug])) {
            continue;
        }
        $parent_id = $category_ids[$parent_slug];
        
        foreach ($subs as $sub_slug => $sub_name) {
            $full_slug = $parent_slug . '-' . $sub_slug;
            $term = wp_insert_term($sub_name, 'product_cat', array(
                'slug' => $full_slug,
                'parent' => $parent_id
            ));
            
            if (!is_wp_error($term)) {
                $subcategory_ids[$full_slug] = $term['term_id'];
            } else {
                // Term already exists
                $term = get_term_by('slug', $full_slug, 'product_cat');
                if ($term) {
                    $subcategory_ids[$full_slug] = $term->term_id;
                }
            }
            
            // Set subcategory image
            if (isset($subcategory_ids[$full_slug]) && 
                isset($subcategory_images[$parent_slug]) && 
                isset($subcategory_images[$parent_slug][$sub_slug])) {
                $image_url = techno_souq_asset_url($subcategory_images[$parent_slug][$sub_slug]);
                $image_id = techno_souq_upload_image_from_url($image_url, $sub_name);
                if ($image_id) {
                    update_term_meta($subcategory_ids[$full_slug], 'thumbnail_id', $image_id);
                }
            }
        }
    }
    
    // Create brands taxonomy terms
    $brands = array(
        'samsung' => 'سامسونج',
        'lg' => 'إل جي',
        'sony' => 'سوني',
        'vestel' => 'فيستل',
        'hp' => 'إتش بي',
        'dyson' => 'دايسون',
        'fresh' => 'طازج',
    );
    
    $brand_ids = array();
    foreach ($brands as $slug => $name) {
        $term = wp_insert_term($name, 'product_brand', array('slug' => $slug));
        if (!is_wp_error($term)) {
            $brand_ids[$slug] = $term['term_id'];
        } else {
            // Term already exists
            $term = get_term_by('slug', $slug, 'product_brand');
            if ($term) {
                $brand_ids[$slug] = $term->term_id;
            }
        }
    }
    
    // Import products
    foreach ($demo_products as $product_data) {
        $product = new WC_Product_Simple();
        $product->set_name($product_data['name']);
        $product->set_regular_price($product_data['regular_price']);
        $product->set_price($product_data['price']);
        $product->set_status('publish');
        $product->set_catalog_visibility('visible');
        
        // Set categories - both main category and subcategory
        $product_categories = array();
        
        // Add main category
        if (isset($category_ids[$product_data['category']])) {
            $product_categories[] = $category_ids[$product_data['category']];
        }
        
        // Add subcategory
        if (isset($product_data['subcategory'])) {
            $subcategory_key = $product_data['category'] . '-' . $product_data['subcategory'];
            if (isset($subcategory_ids[$subcategory_key])) {
                $product_categories[] = $subcategory_ids[$subcategory_key];
            }
        }
        
        if (!empty($product_categories)) {
            $product->set_category_ids($product_categories);
        }
        
        $product_id = $product->save();
        
        // Set brand after product is saved
        if (isset($product_data['brand']) && isset($brand_ids[$product_data['brand']])) {
            wp_set_object_terms($product_id, $brand_ids[$product_data['brand']], 'product_brand');
        }
        
        // Set product image
        $image_url = techno_souq_asset_url($product_data['image']);
        $image_id = techno_souq_upload_image_from_url($image_url, $product_data['name']);
        if ($image_id) {
            $product->set_image_id($image_id);
            $product->save();
        }
    }
    
    update_option('techno_souq_has_demo_products', true);
}

/**
 * Upload image from URL
 */
function techno_souq_upload_image_from_url($url, $title = '') {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    
    $tmp = download_url($url);
    if (is_wp_error($tmp)) {
        return false;
    }
    
    $file_array = array(
        'name' => basename($url),
        'tmp_name' => $tmp
    );
    
    $id = media_handle_sideload($file_array, 0, $title);
    
    if (is_wp_error($id)) {
        @unlink($file_array['tmp_name']);
        return false;
    }
    
    return $id;
}

/**
 * Reset demo products
 */
function techno_souq_reset_demo_products() {
    // Get all products with demo flag or delete all products
    $products = wc_get_products(array('limit' => -1, 'status' => 'any'));
    
    foreach ($products as $product) {
        wp_delete_post($product->get_id(), true);
    }
    
    // Delete categories
    $categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
    ));
    
    foreach ($categories as $category) {
        if ($category->slug !== 'uncategorized') {
            wp_delete_term($category->term_id, 'product_cat');
        }
    }
    
    // Delete brands
    $brands = get_terms(array(
        'taxonomy' => 'product_brand',
        'hide_empty' => false,
    ));
    
    if (!empty($brands) && !is_wp_error($brands)) {
        foreach ($brands as $brand) {
            wp_delete_term($brand->term_id, 'product_brand');
        }
    }
    
    delete_option('techno_souq_has_demo_products');
}
