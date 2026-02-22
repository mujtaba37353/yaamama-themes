<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;

global $product;

// Ensure product object is set
if (!$product) {
    $product = wc_get_product(get_the_ID());
}

// If still no product, return early
if (!$product || !is_a($product, 'WC_Product')) {
    return;
}

// Get product category
$categories = wp_get_post_terms($product->get_id(), 'product_cat');
$category_name = !empty($categories) && !is_wp_error($categories) ? $categories[0]->name : '';

// Product image
$image_id = $product->get_image_id();
$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : wc_placeholder_img_src('full');

// Product price
$regular_price = $product->get_regular_price();
$sale_price = $product->get_sale_price();
$price = $sale_price ? $sale_price : $regular_price;

// Get product attributes for features
$passengers = $product->get_attribute('passengers') ?: $product->get_attribute('pa_passengers');
$doors = $product->get_attribute('doors') ?: $product->get_attribute('pa_doors');
$fuel = $product->get_attribute('fuel') ?: $product->get_attribute('pa_fuel');
$transmission = $product->get_attribute('transmission') ?: $product->get_attribute('pa_transmission');

// Get product description
$description = $product->get_description();
$short_description = $product->get_short_description();
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('y-l-single-product', $product); ?>>
    <div class="y-l-single-product-wrapper">
        
        <!-- Product Image Section -->
        <div class="y-l-single-product-image">
                <div class="y-c-single-product-image-wrapper">
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" class="y-c-single-product-image">
                </div>
            </div>

            <!-- Product Details Section -->
            <div class="y-l-single-product-details">
                
                <!-- Product Info -->
                <div class="y-l-single-product-info">
                    <?php if ($category_name) : ?>
                        <div class="y-c-card-category">
                            <i class="fa-solid fa-car"></i>
                            <span><?php echo esc_html($category_name); ?></span>
                        </div>
                    <?php endif; ?>

                    <h1 class="y-c-single-product-title">
                        <?php echo esc_html($product->get_name()); ?>
                    </h1>

                    <?php if ($short_description) : ?>
                        <p class="y-c-card-similar">
                            <?php echo wp_kses_post($short_description); ?>
                        </p>
                    <?php endif; ?>

                    <!-- Product Features -->
                    <div class="y-c-card-features y-c-single-product-features">
                        <?php if ($passengers) : ?>
                            <span class="y-c-card-feature-item" title="ركاب">
                                <i class="fa-solid fa-users"></i>
                                <span><?php echo esc_html($passengers); ?></span>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($doors) : ?>
                            <span class="y-c-card-feature-item" title="أبواب">
                                <i class="fa-solid fa-door-closed"></i>
                                <span><?php echo esc_html($doors); ?></span>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($transmission) : ?>
                            <span class="y-c-card-feature-item" title="ناقل حركة">
                                <i class="fa-solid fa-gear"></i>
                                <span><?php echo esc_html($transmission); ?></span>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($fuel) : ?>
                            <span class="y-c-card-feature-item" title="وقود">
                                <i class="fa-solid fa-gas-pump"></i>
                                <span><?php echo esc_html($fuel); ?></span>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Product Description -->
                    <?php if ($description) : ?>
                        <div class="y-c-single-product-description">
                            <h3 class="y-c-description-title">وصف المنتج</h3>
                            <div class="y-c-description-content">
                                <?php echo wp_kses_post($description); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Booking Form Section -->
                <div id="y-l-page-hero" data-y="home-hero">
                    <div class="y-c-hero-form-container" data-y="hero-form-container">
                        <div class="y-c-hero-tabs" data-y="hero-tabs">
                            <button type="button" class="y-c-hero-tab-btn" data-y="hero-tab-booking">
                                <i class="fa-solid fa-calendar-days"></i>
                                <span>احجز مستقبلاً</span>
                            </button>
                            <button type="button" class="y-c-hero-tab-btn active" data-y="hero-tab-search">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <span>احجز الان</span>
                            </button>
                        </div>
                        
                        <form class="y-c-hero-form-content" data-y="hero-form-content" method="get" action="<?php echo function_exists('wc_get_page_permalink') ? esc_url(wc_get_page_permalink('shop')) : esc_url(home_url('/shop')); ?>">
                            
                            <div class="y-l-form-row y-l-form-row-bottom">

                                <div class="y-l-form-field y-l-form-field-datetime">
                                    <label>تاريخ ووقت الخروج</label>
                                    <div class="y-l-datetime-inputs">
                                        <div class="y-c-date-picker" data-picker="single-pickup-date">
                                            <div class="y-c-picker-trigger">
                                                <i class="fa-solid fa-calendar-days"></i>
                                                <span class="y-c-picker-value placeholder">اختر التاريخ</span>
                                            </div>
                                            <input type="hidden" name="pickup-date" id="single-product-pickup-date">
                                        </div>
                                        <div class="y-c-time-picker" data-picker="single-pickup-time">
                                            <div class="y-c-picker-trigger">
                                                <span class="y-c-picker-value">الوقت</span>
                                            </div>
                                            <input type="hidden" name="pickup-time" id="single-product-pickup-time">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="y-l-form-field y-l-form-field-datetime">
                                    <label>تاريخ ووقت التسليم</label>
                                    <div class="y-l-datetime-inputs">
                                        <div class="y-c-date-picker" data-picker="single-dropoff-date">
                                            <div class="y-c-picker-trigger">
                                                <i class="fa-solid fa-calendar-days"></i>
                                                <span class="y-c-picker-value placeholder">اختر التاريخ</span>
                                            </div>
                                            <input type="hidden" name="dropoff-date" id="single-product-dropoff-date">
                                        </div>
                                        <div class="y-c-time-picker" data-picker="single-dropoff-time">
                                            <div class="y-c-picker-trigger">
                                                <span class="y-c-picker-value">الوقت</span>
                                            </div>
                                            <input type="hidden" name="dropoff-time" id="single-product-dropoff-time">
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                        </form>
                    </div>
                </div>

                <!-- Product Pricing -->
                <div class="y-l-single-product-pricing">
                    <div class="y-c-single-product-price-box">
                        <span class="y-c-card-price-label">السعر اليومي</span>
                        
                        <?php if ($sale_price && $sale_price < $regular_price) : ?>
                            <div class="y-c-card-price-old">
                                <?php echo esc_html($regular_price); ?>
                                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/my-car/assets/coin.png" alt="ريال" class="y-c-coin-icon">
                            </div>
                        <?php endif; ?>
                        
                        <div class="y-c-card-price-amount y-c-single-product-price">
                            <?php echo esc_html($price); ?>
                            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/my-car/assets/coin.png" alt="ريال" class="y-c-coin-icon">
                        </div>

                        <?php
                        // Get checkout URL with add to cart
                        $checkout_url = function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : home_url('/checkout/');
                        $add_to_cart_url = add_query_arg('add-to-cart', $product->get_id(), $checkout_url);
                        ?>
                        <a href="<?php echo esc_url($add_to_cart_url); ?>" class="y-c-basic-btn y-c-single-product-book-btn" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                            احجز الان
                        </a>
                    </div>
                </div>
            </div>
        </div>
</div>
