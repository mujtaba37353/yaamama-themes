<?php
/**
 * The Template for displaying product archives, including the main shop page.
 *
 * Override for WooCommerce template.
 *
 * @package Nafhat
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

get_header('shop');

// Get product categories
$product_categories = get_terms(array(
    'taxonomy'   => 'product_cat',
    'hide_empty' => false,
    'exclude'    => array(get_option('default_product_cat')), // Exclude uncategorized
));
?>

<main>
    <!-- Categories Section -->
    <?php if (!empty($product_categories) && !is_wp_error($product_categories)) : ?>
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <div class="y-u-max-w-1200 y-u-mb-32">
            <h2 class="y-u-text-3xl y-u-text-bold y-u-mb-16 y-u-color-muted"><?php esc_html_e('تسوق حسب الفئة:', 'nafhat'); ?></h2>
        </div>
        <div class="categories-slider y-u-flex y-u-flex-row y-u-gap-16 y-u-justify-center y-u-items-center">
            <?php foreach ($product_categories as $cat) : 
                $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
                $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : wc_placeholder_img_src();
                $cat_link = get_term_link($cat);
            ?>
            <a href="<?php echo esc_url($cat_link); ?>" class="y-u-flex y-u-flex-col y-u-justify-center y-u-items-center">
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($cat->name); ?>" />
                <p><?php echo esc_html($cat->name); ?></p>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Products by Category Sections -->
    <?php
    // Get all product categories with products
    $categories_with_products = get_terms(array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'exclude'    => array(get_option('default_product_cat')),
    ));
    
    if (!empty($categories_with_products) && !is_wp_error($categories_with_products)) :
        foreach ($categories_with_products as $category) :
            // Get products for this category
            $products = wc_get_products(array(
                'limit'    => 4,
                'category' => array($category->slug),
                'status'   => 'publish',
            ));
            
            if (!empty($products)) :
    ?>
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <div class="y-u-max-w-1200 y-u-mb-32 y-u-flex y-u-justify-between y-u-items-center">
            <h2 class="y-u-text-3xl y-u-text-bold y-u-mb-16 y-u-color-muted"><?php echo esc_html($category->name); ?>:</h2>
            <a href="<?php echo esc_url(get_term_link($category)); ?>" class="y-u-text-sm y-u-text-bold y-u-mb-16 show-all"><?php esc_html_e('عرض الكل', 'nafhat'); ?></a>
        </div>
        <ul class="products-grid">
            <?php foreach ($products as $product) : 
                $product_id = $product->get_id();
                $product_link = get_permalink($product_id);
                $product_image = wp_get_attachment_url($product->get_image_id());
                if (!$product_image) {
                    $product_image = wc_placeholder_img_src();
                }
                $product_title = $product->get_name();
                $product_description = $product->get_short_description() ?: wp_trim_words($product->get_description(), 15);
                $product_price = $product->get_price();
            ?>
            <?php 
                $in_wishlist = is_user_logged_in() && function_exists('nafhat_is_in_wishlist') && nafhat_is_in_wishlist(get_current_user_id(), $product_id);
            ?>
            <li class="product-card" data-product_id="<?php echo esc_attr($product_id); ?>">
                <a href="<?php echo esc_url($product_link); ?>" class="product-link">
                    <div class="product-img">
                        <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>" />
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/heart.svg'); ?>" alt="<?php esc_attr_e('المفضلة', 'nafhat'); ?>" class="wishlist-icon <?php echo $in_wishlist ? 'in-wishlist' : ''; ?>" data-product_id="<?php echo esc_attr($product_id); ?>" />
                        <?php if ($product->is_on_sale()) : ?>
                        <div class="discount"><?php esc_html_e('خصم', 'nafhat'); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="product-content">
                        <p class="product-title"><?php echo esc_html($product_title); ?></p>
                        <p class="product-description"><?php echo esc_html($product_description); ?></p>
                    </div>
                </a>
                <div class="product-actions">
                    <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="product-add-to-cart">
                        <?php echo esc_html($product_price); ?> 
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/ريال.svg'); ?>" alt="<?php esc_attr_e('ريال', 'nafhat'); ?>" />
                    </a>
                    <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="product-add-to-cart-btn ajax_add_to_cart add_to_cart_button" data-product_id="<?php echo esc_attr($product_id); ?>" data-quantity="1">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/add.svg'); ?>" alt="<?php esc_attr_e('إضافة للسلة', 'nafhat'); ?>" />
                    </a>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php 
            endif;
        endforeach;
    endif;
    ?>

    <?php
    // If no categories with products, show all products
    if (empty($categories_with_products) || is_wp_error($categories_with_products)) :
        // Get all products
        $all_products = wc_get_products(array(
            'limit'  => -1,
            'status' => 'publish',
        ));
        
        if (!empty($all_products)) :
    ?>
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <div class="y-u-max-w-1200 y-u-mb-32">
            <h2 class="y-u-text-3xl y-u-text-bold y-u-mb-16 y-u-color-muted"><?php esc_html_e('جميع المنتجات:', 'nafhat'); ?></h2>
        </div>
        <ul class="products-grid">
            <?php foreach ($all_products as $product) : 
                $product_id = $product->get_id();
                $product_link = get_permalink($product_id);
                $product_image = wp_get_attachment_url($product->get_image_id());
                if (!$product_image) {
                    $product_image = wc_placeholder_img_src();
                }
                $product_title = $product->get_name();
                $product_description = $product->get_short_description() ?: wp_trim_words($product->get_description(), 15);
                $product_price = $product->get_price();
            ?>
            <?php 
                $in_wishlist = is_user_logged_in() && function_exists('nafhat_is_in_wishlist') && nafhat_is_in_wishlist(get_current_user_id(), $product_id);
            ?>
            <li class="product-card" data-product_id="<?php echo esc_attr($product_id); ?>">
                <a href="<?php echo esc_url($product_link); ?>" class="product-link">
                    <div class="product-img">
                        <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>" />
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/heart.svg'); ?>" alt="<?php esc_attr_e('المفضلة', 'nafhat'); ?>" class="wishlist-icon <?php echo $in_wishlist ? 'in-wishlist' : ''; ?>" data-product_id="<?php echo esc_attr($product_id); ?>" />
                        <?php if ($product->is_on_sale()) : ?>
                        <div class="discount"><?php esc_html_e('خصم', 'nafhat'); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="product-content">
                        <p class="product-title"><?php echo esc_html($product_title); ?></p>
                        <p class="product-description"><?php echo esc_html($product_description); ?></p>
                    </div>
                </a>
                <div class="product-actions">
                    <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="product-add-to-cart">
                        <?php echo esc_html($product_price); ?> 
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/ريال.svg'); ?>" alt="<?php esc_attr_e('ريال', 'nafhat'); ?>" />
                    </a>
                    <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="product-add-to-cart-btn ajax_add_to_cart add_to_cart_button" data-product_id="<?php echo esc_attr($product_id); ?>" data-quantity="1">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/add.svg'); ?>" alt="<?php esc_attr_e('إضافة للسلة', 'nafhat'); ?>" />
                    </a>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php 
        endif;
    endif;
    ?>
</main>

<?php
get_footer('shop');
