<?php
/**
 * Front Page Template
 *
 * @package Nafhat
 * @since 1.0.0
 */

get_header();

// Get homepage settings
$homepage_settings = nafhat_get_homepage_settings();
$hero_slides = isset($homepage_settings['hero_slides']) ? $homepage_settings['hero_slides'] : array();
$secondary_banners = isset($homepage_settings['secondary_banners']) ? $homepage_settings['secondary_banners'] : array();
$third_banner = isset($homepage_settings['third_banner']) ? $homepage_settings['third_banner'] : array();
?>

<main id="main" class="site-main">
    <!-- Hero Slider Section -->
    <?php if (!empty($hero_slides)) : ?>
    <section class="y-u-w-full y-u-flex y-u-justify-center y-u-flex-col hero-section">
        <div class="hero-slider-container">
            <div class="hero-slider">
                <?php foreach ($hero_slides as $index => $slide) : 
                    if (empty($slide['image'])) continue;
                ?>
                <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                    <?php if (!empty($slide['link'])) : ?>
                    <a href="<?php echo esc_url($slide['link']); ?>">
                    <?php endif; ?>
                        <img src="<?php echo esc_url($slide['image']); ?>" alt="<?php echo esc_attr($slide['alt'] ?: sprintf(__('صورة رئيسية %d', 'nafhat'), $index + 1)); ?>" class="y-u-w-full" />
                    <?php if (!empty($slide['link'])) : ?>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Navigation Controls -->
            <div class="hero-slider-controls">
                <button class="hero-slider-btn hero-slider-prev" aria-label="<?php esc_attr_e('الشريحة السابقة', 'nafhat'); ?>" type="button">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <button class="hero-slider-btn hero-slider-next" aria-label="<?php esc_attr_e('الشريحة التالية', 'nafhat'); ?>" type="button">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>

            <!-- Slider Indicators -->
            <div class="hero-slider-indicators">
                <?php foreach ($hero_slides as $index => $slide) : 
                    if (empty($slide['image'])) continue;
                ?>
                <button class="hero-indicator <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>" aria-label="<?php printf(esc_attr__('الانتقال إلى الشريحة %d', 'nafhat'), $index + 1); ?>" type="button"></button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Brands Section -->
    <?php
    // Get demo brands
    $demo_brands = nafhat_get_demo_brands();
    
    // Only show section if there are brands
    if (!empty($demo_brands)) :
    ?>
    <section class="container brands-section y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <div class="y-u-max-w-1200 y-u-mb-32">
            <h2 class="y-u-text-3xl y-u-text-bold y-u-mb-16 y-u-color-muted">
                <?php esc_html_e('العلامات التجارية:', 'nafhat'); ?>
            </h2>
        </div>
        <div class="brands-slider">
            <div class="brands-slide-top">
                <?php
                // Display first 6 brands in top row
                $top_brands = array_slice($demo_brands, 0, 6);
                foreach ($top_brands as $brand) {
                    ?>
                    <img src="<?php echo esc_url($brand['image']); ?>" alt="<?php echo esc_attr($brand['name']); ?>" />
                    <?php
                }
                // If we have less than 6 brands, repeat to fill the row
                if (count($top_brands) < 6 && count($demo_brands) > 0) {
                    $remaining = 6 - count($top_brands);
                    for ($i = 0; $i < $remaining; $i++) {
                        $brand = $demo_brands[$i % count($demo_brands)];
                        ?>
                        <img src="<?php echo esc_url($brand['image']); ?>" alt="<?php echo esc_attr($brand['name']); ?>" />
                        <?php
                    }
                }
                ?>
            </div>
            <div class="brands-slide-bottom">
                <?php
                // Display next 6 brands in bottom row (or repeat if less than 6)
                $bottom_brands = array_slice($demo_brands, 6, 6);
                if (empty($bottom_brands)) {
                    // If no more brands, repeat from beginning
                    $bottom_brands = array_slice($demo_brands, 0, 6);
                }
                foreach ($bottom_brands as $brand) {
                    ?>
                    <img src="<?php echo esc_url($brand['image']); ?>" alt="<?php echo esc_attr($brand['name']); ?>" />
                    <?php
                }
                // Fill remaining slots if needed
                if (count($bottom_brands) < 6 && count($demo_brands) > 0) {
                    $remaining = 6 - count($bottom_brands);
                    for ($i = 0; $i < $remaining; $i++) {
                        $brand = $demo_brands[$i % count($demo_brands)];
                        ?>
                        <img src="<?php echo esc_url($brand['image']); ?>" alt="<?php echo esc_attr($brand['name']); ?>" />
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Categories Section -->
    <?php
    // Get WooCommerce product categories
    $product_categories = array();
    if (class_exists('WooCommerce')) {
        // Get uncategorized category ID to exclude it
        $uncategorized_term = get_term_by('slug', 'uncategorized', 'product_cat');
        $exclude_ids = array();
        if ($uncategorized_term) {
            $exclude_ids[] = $uncategorized_term->term_id;
        }
        
        $product_categories = get_terms(array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'number'     => 5, // Limit to 5 categories
            'exclude'    => $exclude_ids, // Exclude uncategorized
        ));
        
        // Filter out uncategorized if it still exists
        if (!empty($product_categories) && !is_wp_error($product_categories)) {
            $product_categories = array_filter($product_categories, function($cat) {
                return $cat->slug !== 'uncategorized';
            });
        }
    }
    
    // Only show section if there are categories
    if (!empty($product_categories) && !is_wp_error($product_categories)) :
    ?>
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <div class="y-u-max-w-1200 y-u-mb-32">
            <h2 class="y-u-text-3xl y-u-text-bold y-u-mb-16 y-u-color-muted"><?php esc_html_e('الفئات:', 'nafhat'); ?></h2>
        </div>
        <div class="categories-slider y-u-flex y-u-flex-row y-u-gap-16 y-u-justify-center y-u-items-center">
            <?php
            foreach ($product_categories as $cat) {
                // Get category image
                $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
                if ($thumbnail_id) {
                    $image_url = wp_get_attachment_image_url($thumbnail_id, 'full');
                } else {
                    // Fallback to default category images
                    $default_images = array(
                        'المكياج' => 'cat1.png',
                        'وصل حديثا' => 'cat2.png',
                        'العطور' => 'cat3.png',
                        'الاكثر مبيعا' => 'cat4.png',
                        'منتجات العناية' => 'cat5.png',
                    );
                    $image_url = get_template_directory_uri() . '/assets/images/' . ($default_images[$cat->name] ?? 'cat1.png');
                }
                
                $cat_link = get_term_link($cat);
                if (is_wp_error($cat_link)) {
                    $cat_link = wc_get_page_permalink('shop');
                }
                ?>
                <a href="<?php echo esc_url($cat_link); ?>" class="y-u-flex y-u-flex-col y-u-justify-center y-u-items-center">
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($cat->name); ?>" />
                    <p><?php echo esc_html($cat->name); ?></p>
                </a>
                <?php
            }
            ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Best Sellers Section -->
    <?php
    // Get WooCommerce best selling products
    $best_seller_products = array();
    if (class_exists('WooCommerce')) {
        $best_seller_products = wc_get_products(array(
            'limit'   => 4,
            'orderby' => 'popularity',
            'order'   => 'DESC',
            'status'  => 'publish',
        ));
    }
    
    // Only show section if there are products
    if (!empty($best_seller_products)) :
    ?>
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <div class="y-u-max-w-1200 y-u-mb-32 y-u-flex y-u-justify-between y-u-items-center">
            <h2 class="y-u-text-3xl y-u-text-bold y-u-mb-16 y-u-color-muted"><?php esc_html_e('الأكثر مبيعا:', 'nafhat'); ?></h2>
            <a href="<?php echo esc_url(class_exists('WooCommerce') ? wc_get_page_permalink('shop') : '#'); ?>" class="y-u-text-sm y-u-text-bold y-u-mb-16 show-all"><?php esc_html_e('عرض الكل', 'nafhat'); ?></a>
        </div>
        <ul class="products-grid">
            <?php
            foreach ($best_seller_products as $product) {
                $product_id = $product->get_id();
                $product_link = get_permalink($product_id);
                $product_price = $product->get_price();
                $product_image = wp_get_attachment_image_url($product->get_image_id(), 'full');
                
                if (!$product_image) {
                    $product_image = wc_placeholder_img_src('full');
                }
                
                $in_wishlist = is_user_logged_in() && function_exists('nafhat_is_in_wishlist') && nafhat_is_in_wishlist(get_current_user_id(), $product_id);
                ?>
                <li class="product-card" data-product_id="<?php echo esc_attr($product_id); ?>">
                    <a href="<?php echo esc_url($product_link); ?>" class="product-link">
                        <div class="product-img">
                            <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" />
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/heart.svg'); ?>" alt="<?php esc_attr_e('إضافة للمفضلة', 'nafhat'); ?>" class="wishlist-icon <?php echo $in_wishlist ? 'in-wishlist' : ''; ?>" data-product_id="<?php echo esc_attr($product_id); ?>" />
                        </div>
                        <div class="product-content">
                            <p class="product-title"><?php echo esc_html($product->get_name()); ?></p>
                            <p class="product-description"><?php echo esc_html(wp_trim_words($product->get_short_description() ? $product->get_short_description() : $product->get_description(), 20)); ?></p>
                        </div>
                    </a>
                    <div class="product-actions">
                        <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="product-add-to-cart" data-product_id="<?php echo esc_attr($product_id); ?>">
                            <?php echo esc_html($product_price); ?> 
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/ريال.svg'); ?>" alt="<?php esc_attr_e('ريال', 'nafhat'); ?>" />
                        </a>
                        <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="product-add-to-cart-btn ajax_add_to_cart add_to_cart_button" data-product_id="<?php echo esc_attr($product_id); ?>" data-quantity="1">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/add.svg'); ?>" alt="<?php esc_attr_e('إضافة للسلة', 'nafhat'); ?>" />
                        </a>
                    </div>
                </li>
                <?php
            }
            ?>
        </ul>
    </section>
    <?php endif; ?>

    <!-- Banner Section (Secondary Banners) -->
    <?php if (!empty($secondary_banners)) : ?>
    <section class="banner-section container y-u-w-full">
        <?php foreach ($secondary_banners as $index => $banner) : 
            if (empty($banner['image'])) continue;
        ?>
            <?php if (!empty($banner['link'])) : ?>
            <a href="<?php echo esc_url($banner['link']); ?>">
            <?php endif; ?>
                <img src="<?php echo esc_url($banner['image']); ?>" alt="<?php echo esc_attr($banner['alt'] ?: sprintf(__('بانر إعلاني %d', 'nafhat'), $index + 1)); ?>" class="y-u-w-full" />
            <?php if (!empty($banner['link'])) : ?>
            </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>

    <!-- New Arrivals Section -->
    <?php
    // Get WooCommerce newest products
    $new_arrival_products = array();
    if (class_exists('WooCommerce')) {
        $new_arrival_products = wc_get_products(array(
            'limit'   => 4,
            'orderby' => 'date',
            'order'   => 'DESC',
            'status'  => 'publish',
        ));
    }
    
    // Only show section if there are products
    if (!empty($new_arrival_products)) :
    ?>
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <div class="y-u-max-w-1200 y-u-mb-32 y-u-flex y-u-justify-between y-u-items-center">
            <h2 class="y-u-text-3xl y-u-text-bold y-u-mb-16 y-u-color-muted"><?php esc_html_e('وصل حديثا:', 'nafhat'); ?></h2>
            <a href="<?php echo esc_url(class_exists('WooCommerce') ? wc_get_page_permalink('shop') : '#'); ?>" class="y-u-text-sm y-u-text-bold y-u-mb-16 show-all"><?php esc_html_e('عرض الكل', 'nafhat'); ?></a>
        </div>
        <ul class="products-grid">
            <?php
            foreach ($new_arrival_products as $product) {
                $product_id = $product->get_id();
                $product_link = get_permalink($product_id);
                $product_price = $product->get_price();
                $product_image = wp_get_attachment_image_url($product->get_image_id(), 'full');
                
                if (!$product_image) {
                    $product_image = wc_placeholder_img_src('full');
                }
                
                $in_wishlist = is_user_logged_in() && function_exists('nafhat_is_in_wishlist') && nafhat_is_in_wishlist(get_current_user_id(), $product_id);
                ?>
                <li class="product-card" data-product_id="<?php echo esc_attr($product_id); ?>">
                    <a href="<?php echo esc_url($product_link); ?>" class="product-link">
                        <div class="product-img">
                            <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" />
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/heart.svg'); ?>" alt="<?php esc_attr_e('إضافة للمفضلة', 'nafhat'); ?>" class="wishlist-icon <?php echo $in_wishlist ? 'in-wishlist' : ''; ?>" data-product_id="<?php echo esc_attr($product_id); ?>" />
                        </div>
                        <div class="product-content">
                            <p class="product-title"><?php echo esc_html($product->get_name()); ?></p>
                            <p class="product-description"><?php echo esc_html(wp_trim_words($product->get_short_description() ? $product->get_short_description() : $product->get_description(), 20)); ?></p>
                        </div>
                    </a>
                    <div class="product-actions">
                        <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="product-add-to-cart" data-product_id="<?php echo esc_attr($product_id); ?>">
                            <?php echo esc_html($product_price); ?> 
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/ريال.svg'); ?>" alt="<?php esc_attr_e('ريال', 'nafhat'); ?>" />
                        </a>
                        <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="product-add-to-cart-btn ajax_add_to_cart add_to_cart_button" data-product_id="<?php echo esc_attr($product_id); ?>" data-quantity="1">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/add.svg'); ?>" alt="<?php esc_attr_e('إضافة للسلة', 'nafhat'); ?>" />
                        </a>
                    </div>
                </li>
                <?php
            }
            ?>
        </ul>
    </section>
    <?php endif; ?>

    <!-- Third Banner Section -->
    <?php if (!empty($third_banner['image'])) : ?>
    <section class="y-u-w-full y-u-flex y-u-justify-center y-u-flex-col hero-section">
        <?php if (!empty($third_banner['link'])) : ?>
        <a href="<?php echo esc_url($third_banner['link']); ?>">
        <?php endif; ?>
            <img src="<?php echo esc_url($third_banner['image']); ?>" alt="<?php echo esc_attr($third_banner['alt'] ?: __('بانر ثالث', 'nafhat')); ?>" class="y-u-w-full" />
        <?php if (!empty($third_banner['link'])) : ?>
        </a>
        <?php endif; ?>
    </section>
    <?php endif; ?>

    <!-- Customer Reviews Section -->
    <?php
    // Get WooCommerce product reviews first
    $reviews = array();
    if (class_exists('WooCommerce')) {
        $args = array(
            'status' => 'approve',
            'number' => 0, // Get all reviews (0 = no limit)
            'post_type' => 'product',
        );
        $wc_reviews = get_comments($args);
        
        if (!empty($wc_reviews)) {
            foreach ($wc_reviews as $review) {
                $rating = get_comment_meta($review->comment_ID, 'rating', true);
                if (!$rating) {
                    $rating = 5; // Default to 5 if no rating
                }
                $reviews[] = array(
                    'customer_name' => $review->comment_author,
                    'rating' => intval($rating),
                    'review' => $review->comment_content,
                );
            }
        }
    }
    
    // If no WooCommerce reviews, get demo product reviews
    if (empty($reviews)) {
        $demo_reviews = nafhat_get_all_demo_reviews(0); // Get all demo reviews
        if (!empty($demo_reviews)) {
            $reviews = $demo_reviews;
        }
    }
    
    // Only show section if there are reviews
    if (!empty($reviews)) :
    ?>
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <div class="y-u-max-w-1200 y-u-mb-32">
            <h2 class="y-u-text-3xl y-u-text-bold y-u-mb-16 y-u-color-muted"><?php esc_html_e('تقييمات الزبائن:', 'nafhat'); ?></h2>
        </div>
        <div class="opinions-slider-container">
            <div class="opinions-slider">
                <?php
                // Display reviews
                foreach ($reviews as $review) {
                    ?>
                    <div class="message-card opinion-slide">
                        <div class="message-header">
                            <p><?php echo esc_html($review['customer_name']); ?></p>
                            <div class="starts">
                                <?php
                                $rating = isset($review['rating']) ? intval($review['rating']) : 5;
                                for ($i = 0; $i < $rating; $i++) {
                                    ?>
                                    <i class="fas fa-star"></i>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="message-content">
                            <div class="product-description">
                                <?php echo esc_html($review['review']); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="indecators">
            <button class="indecator opinion-slider-prev">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/arrow-right.svg'); ?>" alt="" />
            </button>
            <button class="indecator opinion-slider-next">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/arrow-left.svg'); ?>" alt="" />
            </button>
        </div>
    </section>
    <?php endif; ?>
</main>

<?php
get_footer();
