<?php
/**
 * The front page template file
 *
 * @package TechnoSouqTheme
 */

get_header();
?>

<main data-y="home-main">
    <!-- Hero Section -->
    <?php 
    $defaults = techno_souq_get_default_demo_content();
    $hero_image = techno_souq_get_demo_content('hero_image', $defaults['hero_image']);
    ?>
    <div class="y-c-home-hero" data-y="home-hero">
        <img src="<?php echo esc_url($hero_image); ?>" alt="Hero Image" data-y="hero-image">
    </div>
    <br>
    
    <!-- Devices Slider Section -->
    <section class="y-l-slider-container y-l-container" aria-label="Newest Devices Slider" data-y="devices-slider-section">
        <div class="y-l-slider-wrapper" data-y="slider-wrapper">
            <?php for ($i = 1; $i <= 3; $i++) : 
                $slider_image = techno_souq_get_demo_content("slider_{$i}_image", $defaults["slider_{$i}_image"]);
                $slider_title = techno_souq_get_demo_content("slider_{$i}_title", $defaults["slider_{$i}_title"]);
                $slider_text = techno_souq_get_demo_content("slider_{$i}_text", $defaults["slider_{$i}_text"]);
            ?>
            <!-- Slide <?php echo $i; ?> -->
            <div class="y-c-slide" data-y="slide-<?php echo $i; ?>">
                <div class="y-c-slide-text" data-y="slide-<?php echo $i; ?>-text">
                    <h1 data-y="slide-<?php echo $i; ?>-title"><?php echo esc_html($slider_title); ?></h1>
                    <p data-y="slide-<?php echo $i; ?>-subtitle"><?php echo esc_html($slider_text); ?></p>
                </div>
                <div class="y-c-slide-image" data-y="slide-<?php echo $i; ?>-image-container">
                    <img src="<?php echo esc_url($slider_image); ?>" alt="<?php echo esc_attr($slider_title); ?>" data-y="slide-<?php echo $i; ?>-image">
                </div>
            </div>
            <?php endfor; ?>
        </div>

        <!-- Navigation Buttons -->
        <button class="y-c-slider-btn y-c-slider-prev" aria-label="Previous Slide" data-y="slider-prev-btn">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="y-c-slider-btn y-c-slider-next" aria-label="Next Slide" data-y="slider-next-btn">
            <i class="fas fa-chevron-right"></i>
        </button>

        <!-- Slider Dots -->
        <div class="y-c-slider-dots" data-y="slider-dots"></div>
    </section>
    <br>
    
    <!-- Brands Slider Section -->
    <section class="y-l-brands-slider y-l-container" data-y="brands-section">
        <h2 class="y-c-brands-title" data-y="brands-title">تصفح من خلال العلامات التجارية</h2>
        <div class="y-c-brands-scroller" data-animated="true" data-y="brands-scroller">
            <ul class="y-c-scroller-inner" data-y="brands-list">
                <?php
                // Get all product brands from taxonomy
                $brands = get_terms(array(
                    'taxonomy' => 'product_brand',
                    'hide_empty' => true,
                    'orderby' => 'name',
                    'order' => 'ASC',
                ));
                
                if (!empty($brands) && !is_wp_error($brands)) {
                    $brand_index = 1;
                    foreach ($brands as $brand) {
                        // Get brand logo from term meta or use default placeholder
                        $brand_logo = get_term_meta($brand->term_id, 'brand_logo', true);
                        if (empty($brand_logo)) {
                            // Use default placeholder if no logo is set
                            $brand_logo = techno_souq_asset_url('10.png');
                        } else {
                            // If it's an attachment ID, get the URL
                            if (is_numeric($brand_logo)) {
                                $logo_url = wp_get_attachment_image_url($brand_logo, 'full');
                                $brand_logo = $logo_url ? $logo_url : techno_souq_asset_url('10.png');
                            }
                        }
                        
                        $brand_link = get_term_link($brand);
                        if (is_wp_error($brand_link)) {
                            $brand_link = '#';
                        }
                        ?>
                        <li data-y="brand-item-<?php echo esc_attr($brand_index); ?>">
                            <a href="<?php echo esc_url($brand_link); ?>" title="<?php echo esc_attr($brand->name); ?>">
                                <img src="<?php echo esc_url($brand_logo); ?>" alt="<?php echo esc_attr($brand->name); ?>" data-y="brand-logo-<?php echo esc_attr($brand_index); ?>">
                            </a>
                        </li>
                        <?php
                        $brand_index++;
                    }
                }
                ?>
            </ul>
        </div>
    </section>
    <br>
    
    <!-- Categories Grid Section -->
    <section class="y-l-categories y-l-container" data-y="categories-section">
        <h2 class="y-c-categories-title" data-y="categories-title">تصفح من خلال الفئات</h2>
        <div class="y-c-categories-grid" data-y="categories-grid">
            <?php
            // Get parent product categories (excluding "Uncategorized")
            $categories = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'parent' => 0,
                'exclude' => array(get_option('default_product_cat')), // Exclude default category
                'number' => 5, // Limit to 5 categories
            ));
            
            // Also filter out by slug in case exclude doesn't work
            if (!empty($categories) && !is_wp_error($categories)) {
                $categories = array_filter($categories, function($cat) {
                    return strtolower($cat->slug) !== 'uncategorized' && 
                           strtolower($cat->name) !== 'uncategorized';
                });
            }
            
            if (!empty($categories) && !is_wp_error($categories)) :
                $index = 0;
                foreach (array_slice($categories, 0, 5) as $category) :
                    $category_link = get_term_link($category);
                    $category_image = '';
                    
                    // Try to get category image from WooCommerce
                    $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                    if ($thumbnail_id) {
                        $category_image = wp_get_attachment_image_url($thumbnail_id, 'medium');
                    }
                    
                    // Final fallback to WooCommerce placeholder
                    if (empty($category_image)) {
                        $category_image = wc_placeholder_img_src('medium');
                    }
                    ?>
                    <a href="<?php echo esc_url($category_link); ?>" class="y-c-category-card" data-y="category-card-<?php echo $index + 1; ?>">
                        <img src="<?php echo esc_url($category_image); ?>" alt="<?php echo esc_attr($category->name); ?>" data-y="category-image-<?php echo $index + 1; ?>">
                        <h3 data-y="category-title-<?php echo $index + 1; ?>"><?php echo esc_html($category->name); ?></h3>
                    </a>
                    <?php
                    $index++;
                endforeach;
            endif;
            ?>
        </div>
    </section>
    <br>
    
    <!-- Home Promo 1 Section -->
    <div class="y-l-home-promo y-l-container" data-y="home-promo-1">
        <div class="y-c-slide-text" data-y="promo-1-text">
            <h1 data-y="promo-1-title">أحدث الأجهزة</h1>
            <p data-y="promo-1-subtitle">الجيل الجديد من الأجهزة بين يديك الآن</p>
        </div>
        <div class="y-c-slide-image" data-y="promo-1-image-container">
            <img src="<?php echo esc_url(techno_souq_asset_url('phone-slider.png')); ?>" alt="Latest Devices" data-y="promo-1-image">
        </div>
    </div>
    <br>
    
    <!-- Bestsellers Section -->
    <section class="y-l-bestsellers y-l-container" data-y="bestsellers-section">
        <h2 class="y-c-section-title" data-y="bestsellers-title">الاكثر مبيعا</h2>
        <div class="y-l-product-grid" data-y="bestsellers-grid">
            <ul class="products columns-4">
                <?php
                // Get bestsellers products directly
                $bestsellers_args = array(
                    'limit' => 4,
                    'status' => 'publish',
                    'orderby' => 'total_sales',
                    'order' => 'DESC',
                );
                $bestsellers = wc_get_products($bestsellers_args);
                
                // Filter products with actual sales
                $bestsellers_with_sales = array();
                foreach ($bestsellers as $product) {
                    if ($product && $product->get_total_sales() > 0) {
                        $bestsellers_with_sales[] = $product;
                        if (count($bestsellers_with_sales) >= 4) {
                            break;
                        }
                    }
                }
                
                // If no products with sales, get 4 regular products
                if (empty($bestsellers_with_sales)) {
                    $bestsellers_with_sales = wc_get_products(array(
                        'limit' => 4,
                        'status' => 'publish',
                        'orderby' => 'date',
                        'order' => 'DESC',
                    ));
                }
                
                // Display exactly 4 products
                $count = 0;
                foreach ($bestsellers_with_sales as $product) {
                    if ($count >= 4) break;
                    $GLOBALS['product'] = $product;
                    wc_get_template_part('content', 'product');
                    $count++;
                }
                wp_reset_postdata();
                ?>
            </ul>
        </div>
    </section>
    <br>
    
    <!-- Home Promo 2 Section -->
    <div class="y-l-home-promo y-l-container" data-y="home-promo-2">
        <div class="y-c-slide-text" data-y="promo-2-text">
            <h1 data-y="promo-2-title">أحدث الأجهزة</h1>
            <p data-y="promo-2-subtitle">الجيل الجديد من الأجهزة بين يديك الآن</p>
        </div>
        <div class="y-c-slide-image" data-y="promo-2-image-container">
            <img src="<?php echo esc_url(techno_souq_asset_url('phone-slider.png')); ?>" alt="Latest Devices" data-y="promo-2-image">
        </div>
    </div>
    <br>
    
    <!-- Newest Products Section -->
    <section class="y-l-bestsellers y-l-container" data-y="newest-products-section">
        <h2 class="y-c-section-title" data-y="newest-products-title">أحدث المنتجات</h2>
        <div class="y-l-product-grid" data-y="newest-products-grid">
            <ul class="products columns-4">
                <?php
                // Get newest products directly
                $newest_args = array(
                    'limit' => 4,
                    'status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC',
                );
                $newest_products = wc_get_products($newest_args);
                
                // Display exactly 4 products
                $count = 0;
                foreach ($newest_products as $product) {
                    if ($count >= 4) break;
                    $GLOBALS['product'] = $product;
                    wc_get_template_part('content', 'product');
                    $count++;
                }
                wp_reset_postdata();
                ?>
            </ul>
        </div>
    </section>
    <br>
    
    <!-- CTA Banner Section -->
    <?php 
    $cta_product_id = techno_souq_get_demo_content('cta_product_id', $defaults['cta_product_id']);
    $cta_title = techno_souq_get_demo_content('cta_title', $defaults['cta_title']);
    $cta_description = techno_souq_get_demo_content('cta_description', $defaults['cta_description']);
    $cta_button_text = techno_souq_get_demo_content('cta_button_text', $defaults['cta_button_text']);
    
    // Get product URL if product is selected
    $cta_link = wc_get_page_permalink('shop');
    if ($cta_product_id > 0) {
        $product = wc_get_product($cta_product_id);
        if ($product) {
            $cta_link = get_permalink($cta_product_id);
            // Override title and description with product data if not customized
            if (empty(techno_souq_get_demo_content('cta_title', ''))) {
                $cta_title = $product->get_name();
            }
            if (empty(techno_souq_get_demo_content('cta_description', ''))) {
                $cta_description = $product->get_short_description() ?: $product->get_description();
            }
        }
    }
    ?>
    <section class="y-l-cta-banner" data-y="cta-banner-section">
        <div class="y-l-cta-content y-l-container" data-y="cta-content">
            <h2 data-y="cta-title"><?php echo esc_html($cta_title); ?></h2>
            <br>
            <p data-y="cta-description"><?php echo esc_html($cta_description); ?></p>
            <br>
            <a href="<?php echo esc_url($cta_link); ?>" class="y-c-btn-banner" data-y="cta-main-button"><?php echo esc_html($cta_button_text); ?></a>
        </div>

        <!-- Features Slider -->
        <div class="y-c-features-slider y-l-container" data-y="cta-features-container">
            <div class="y-c-features-track">
                <!-- Original Set of Cards -->
                <?php for ($i = 1; $i <= 4; $i++) : 
                    $feature_text = techno_souq_get_demo_content("feature_{$i}_text", $defaults["feature_{$i}_text"]);
                ?>
                <div class="y-c-home-feature-card" data-y="cta-feature-<?php echo $i; ?>">
                    <i class="fa-solid fa-check"></i>
                    <br>
                    <?php echo esc_html($feature_text); ?>
                </div>
                <?php endfor; ?>

                <!-- Duplicated Set for Seamless Loop -->
                <?php for ($i = 1; $i <= 4; $i++) : 
                    $feature_text = techno_souq_get_demo_content("feature_{$i}_text", $defaults["feature_{$i}_text"]);
                ?>
                <div class="y-c-home-feature-card" aria-hidden="true">
                    <i class="fa-solid fa-check"></i>
                    <br>
                    <?php echo esc_html($feature_text); ?>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
