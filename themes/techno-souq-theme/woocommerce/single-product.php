<?php
/**
 * The Template for displaying all single products
 *
 * @package TechnoSouqTheme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();

// Remove default WooCommerce wrappers
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

// Enqueue single product styles and scripts
$theme_version = wp_get_theme()->get('Version');
$techno_souq_path = get_template_directory_uri() . '/techno-souq';
wp_enqueue_style('techno-souq-product-single', $techno_souq_path . '/templates/product-single/y-product-single.css', array(
    'techno-souq-header',
    'techno-souq-footer',
    'techno-souq-buttons',
    'techno-souq-cards'
), $theme_version);
wp_enqueue_script('techno-souq-single-product', $techno_souq_path . '/js/single-product.js', array('techno-souq-shared-components'), $theme_version, true);
wp_enqueue_script('techno-souq-product-slider', $techno_souq_path . '/js/product-slider.js', array(), $theme_version, true);

while (have_posts()) {
    the_post();
    global $product;
    ?>

    <main data-y="product-main">
        <section class="y-l-container" data-y="product-container">
            <!-- Breadcrumb -->
            <p class="y-c-breadcrumb" data-y="product-breadcrumb">
                <?php
                if (function_exists('woocommerce_breadcrumb')) {
                    $breadcrumb = woocommerce_breadcrumb(array(
                        'delimiter' => ' > ',
                        'wrap_before' => '',
                        'wrap_after' => '',
                        'home' => 'الرئيسية'
                    ), false);
                    echo $breadcrumb;
                } else {
                    echo 'الرئيسية > ' . get_the_title();
                }
                ?>
            </p>

            <div class="y-l-product-detail" data-y="product-detail-section">
                <!-- Left Column (Images) -->
                <div class="y-l-product-images" data-product-id="<?php echo esc_attr($product->get_id()); ?>" data-y="product-images-section">
                    <div class="y-c-single-slider-container">
                        <?php
                        // Calculate discount percentage
                        $regular_price = $product->get_regular_price();
                        $sale_price = $product->get_sale_price();
                        if ($sale_price && $regular_price) {
                            $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                            ?>
                            <span class="y-c-single-discount" data-y="product-discount-badge">خصم <?php echo esc_html($discount); ?>%</span>
                        <?php } ?>
                        
                        <a href="#" class="y-c-single-fav-btn" data-favorite-toggle data-product-id="<?php echo esc_attr($product->get_id()); ?>" data-y="product-favorite-btn">
                            <i class="fas fa-heart" data-y="product-favorite-icon"></i>
                        </a>

                        <button class="y-c-single-slider-btn y-c-single-slider-next" data-y="slider-next-btn">
                            <i class="fas fa-chevron-left"></i>
                        </button>

                        <button class="y-c-single-slider-btn y-c-single-slider-prev" data-y="slider-prev-btn">
                            <i class="fas fa-chevron-right"></i>
                        </button>

                        <div class="y-c-single-image-wrapper" data-y="product-image-container">
                            <?php
                            $image_id = $product->get_image_id();
                            $image_url = wp_get_attachment_image_src($image_id, 'full');
                            if ($image_url) {
                                ?>
                                <img src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="y-c-single-main-image" data-y="product-image" id="main-product-image">
                            <?php } else {
                                echo wc_placeholder_img('full');
                            }
                            
                            // Gallery images
                            $gallery_ids = $product->get_gallery_image_ids();
                            if (!empty($gallery_ids)) {
                                foreach ($gallery_ids as $gallery_id) {
                                    $gallery_url = wp_get_attachment_image_src($gallery_id, 'full');
                                    if ($gallery_url) {
                                        ?>
                                        <img src="<?php echo esc_url($gallery_url[0]); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="y-c-single-main-image" style="display: none;">
                                    <?php }
                                }
                            }
                            ?>
                        </div>

                        <div class="y-c-single-dots">
                            <?php
                            $total_images = 1 + count($gallery_ids);
                            for ($i = 0; $i < $total_images; $i++) {
                                $active = $i === 0 ? 'active' : '';
                                ?>
                                <span class="y-c-single-dot <?php echo esc_attr($active); ?>" data-index="<?php echo esc_attr($i); ?>"></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Info) -->
                <div class="y-l-product-info" data-y="product-info-section">
                    <?php
                    // Get product category
                    $categories = wp_get_post_terms($product->get_id(), 'product_cat');
                    if (!empty($categories)) {
                        $category = $categories[0];
                        ?>
                        <h2 class="y-c-category-title" data-y="product-category-title"><?php echo esc_html($category->name); ?></h2>
                    <?php } ?>

                    <h1 class="y-c-product-title" data-y="product-title"><?php the_title(); ?></h1>

                    <?php if ($product->is_featured()) : ?>
                        <div class="y-c-product-meta">
                            <span class="y-c-meta-label">حصريًا لدى العيسائي للإلكترونيات</span>
                        </div>
                    <?php endif; ?>

                    <div class="y-c-price-section">
                        <h3 class="y-c-section-label">السعر</h3>
                        <div class="y-c-product-price-large" data-y="product-price-container">
                            <?php echo $product->get_price_html(); ?>
                        </div>
                    </div>

                    <div class="y-l-product-purchase" data-y="product-purchase-section">
                        <?php
                        // Quantity controls
                        if ($product->is_purchasable() && $product->is_in_stock()) {
                            ?>
                            <div class="y-c-product-quantity-wrapper">
                                <div class="y-c-section-label" data-y="quantity-label">الكمية</div>
                                <div class="y-c-quantity-control" data-y="quantity-controls">
                                    <button class="y-c-quantity-btn" id="decrease-quantity" data-y="quantity-decrease-btn">-</button>
                                    <input type="number" class="y-c-quantity-input" value="1" min="1" max="<?php echo esc_attr($product->get_max_purchase_quantity()); ?>" data-y="quantity-input">
                                    <button class="y-c-quantity-btn" id="increase-quantity" data-y="quantity-increase-btn">+</button>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="y-c-product-buttons">
                            <?php
                            woocommerce_template_single_add_to_cart();
                            ?>
                            <?php if ($product->is_in_stock() && $product->is_purchasable()) : ?>
                                <a href="<?php echo esc_url(wc_get_checkout_url() . '?add-to-cart=' . $product->get_id() . '&quantity=1'); ?>" class="y-c-btn y-c-btn-primary y-c-btn-full" data-y="buy-now-btn" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                                    <i class="fas fa-wallet" data-y="wallet-icon"></i>
                                    اشتري الآن
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="y-l-reviews-section" data-y="reviews-section">
                <div class="y-u-flex y-u-justify-between y-u-align-center">
                    <div class="y-c-rating-wrapper">
                        <h3 class="y-c-section-label">تقييمك</h3>
                        <div class="y-c-rating-stars" id="user-rating" data-y="rating-stars">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <i class="far fa-star" data-rating="<?php echo esc_attr($i); ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <div class="y-c-reviews-container">
                    <h3 class="y-c-section-label">مراجعات</h3>
                    <?php
                    if (comments_open() || get_comments_number()) {
                        comments_template();
                    }
                    ?>
                </div>
            </div>

            <!-- Product Description and Information Section -->
            <div class="y-l-product-details" data-y="product-details-section">
                <?php
                // Get product description
                $product_description = get_the_content();
                $product_description = apply_filters('the_content', $product_description);
                $product_description_clean = trim(strip_tags($product_description));
                
                // Get additional information
                // Check if product has attributes, dimensions, or weight
                $has_additional_info = $product->has_attributes() || $product->has_dimensions() || $product->has_weight();
                
                $additional_info = '';
                $additional_info_clean = '';
                
                if ($has_additional_info) {
                    ob_start();
                    woocommerce_product_additional_information_tab();
                    $additional_info = ob_get_clean();
                    $additional_info_clean = trim(strip_tags($additional_info));
                    
                    // Remove the "Additional information" heading and any whitespace
                    $additional_info_clean = preg_replace('/Additional information/i', '', $additional_info_clean);
                    $additional_info_clean = preg_replace('/معلومات إضافية/i', '', $additional_info_clean);
                    $additional_info_clean = trim($additional_info_clean);
                    
                    // If only heading exists without actual content, mark as empty
                    if (empty($additional_info_clean) || strlen($additional_info_clean) < 10) {
                        $additional_info_clean = '';
                        $additional_info = '';
                    }
                }
                ?>
                
                <!-- Description Section (always visible if content exists) -->
                <?php if (!empty($product_description_clean)) : ?>
                    <div class="y-c-product-detail-section" data-y="product-description-section">
                        <h3 class="y-c-section-label">وصف المنتج</h3>
                        <div class="y-c-product-detail-content">
                            <?php echo $product_description; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Additional Information Section (only if there's actual content) -->
                <?php if ($has_additional_info && !empty($additional_info_clean)) : ?>
                    <div class="y-c-product-detail-section" data-y="product-info-section">
                        <h3 class="y-c-section-label">معلومات عن المنتج</h3>
                        <div class="y-c-product-detail-content">
                            <?php echo $additional_info; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Features Section (if attributes exist) -->
                <?php if ($product->has_attributes()) : ?>
                    <div class="y-c-product-detail-section" data-y="product-features-section">
                        <h3 class="y-c-section-label">مميزات المنتج</h3>
                        <div class="y-c-product-detail-content">
                            <?php wc_display_product_attributes($product); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Related Products -->
            <?php
            $related_products = wc_get_related_products($product->get_id(), 8);
            if (!empty($related_products)) :
                ?>
                <div class="y-l-similar-products-header" data-y="related-products-header">
                    <h2 class="y-c-section-title y-u-text-right">منتجات ذات صلة:</h2>
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="y-c-categories-btn" data-y="related-products-view-all">شاهد الكل</a>
                </div>

                <ul id="product-container-related" class="y-l-product-grid y-c-slider-container-js" data-limit="8" data-y="related-products-grid">
                    <?php
                    foreach ($related_products as $related_id) {
                        $related_product = wc_get_product($related_id);
                        if ($related_product && $related_product->is_visible()) {
                            $post_object = get_post($related_id);
                            setup_postdata($GLOBALS['post'] = $post_object);
                            wc_get_template_part('content', 'product');
                        }
                    }
                    wp_reset_postdata();
                    ?>
                </ul>
            <?php endif; ?>

        </section>
    </main>

    <style>
        /* Hide default WooCommerce quantity input */
        .y-c-quantity-input-hidden,
        .cart .quantity input.qty {
            display: none !important;
        }
    </style>
    <script>
    (function() {
        // Quantity controls
        const decreaseBtn = document.getElementById('decrease-quantity');
        const increaseBtn = document.getElementById('increase-quantity');
        const quantityInput = document.querySelector('.y-c-quantity-input');
        const buyNowBtn = document.querySelector('[data-y="buy-now-btn"]');
        
        if (decreaseBtn && increaseBtn && quantityInput) {
            const updateWooCommerceQuantity = function(value) {
                // Update WooCommerce quantity field if exists
                const wcQty = document.querySelector('input[name="quantity"]');
                if (wcQty) wcQty.value = value;
                
                // Update buy now link with quantity
                if (buyNowBtn) {
                    const productId = buyNowBtn.getAttribute('data-product-id');
                    if (productId) {
                        const checkoutUrl = buyNowBtn.href.split('?')[0];
                        buyNowBtn.href = checkoutUrl + '?add-to-cart=' + productId + '&quantity=' + value;
                    }
                }
            };
            
            decreaseBtn.addEventListener('click', function() {
                let value = parseInt(quantityInput.value);
                if (value > parseInt(quantityInput.min)) {
                    quantityInput.value = value - 1;
                    updateWooCommerceQuantity(quantityInput.value);
                }
            });
            
            increaseBtn.addEventListener('click', function() {
                let value = parseInt(quantityInput.value);
                if (value < parseInt(quantityInput.max)) {
                    quantityInput.value = value + 1;
                    updateWooCommerceQuantity(quantityInput.value);
                }
            });
            
            quantityInput.addEventListener('change', function() {
                let value = parseInt(this.value);
                const min = parseInt(this.min);
                const max = parseInt(this.max);
                
                if (value < min) value = min;
                if (value > max) value = max;
                
                this.value = value;
                updateWooCommerceQuantity(value);
            });
        }
        
        // Rating stars functionality - Connect custom stars to rating select field
        const ratingStars = document.querySelectorAll('#user-rating [data-rating]');
        const ratingSelect = document.getElementById('rating');
        
        if (ratingStars.length > 0 && ratingSelect) {
            // Function to update star display
            function updateStars(selectedRating) {
                ratingStars.forEach((star, index) => {
                    const starRating = parseInt(star.getAttribute('data-rating'));
                    if (starRating <= selectedRating) {
                        star.classList.remove('far');
                        star.classList.add('fas');
                    } else {
                        star.classList.remove('fas');
                        star.classList.add('far');
                    }
                });
            }
            
            // Click on star
            ratingStars.forEach((star) => {
                star.addEventListener('click', function(e) {
                    e.preventDefault();
                    const selectedRating = parseInt(this.getAttribute('data-rating'));
                    
                    // Update select field
                    ratingSelect.value = selectedRating;
                    
                    // Update star display
                    updateStars(selectedRating);
                    
                    // Trigger change event to ensure form validation recognizes the change
                    ratingSelect.dispatchEvent(new Event('change', { bubbles: true }));
                });
            });
            
            // Hover effect
            ratingStars.forEach((star) => {
                star.addEventListener('mouseenter', function() {
                    const hoverRating = parseInt(this.getAttribute('data-rating'));
                    ratingStars.forEach((s, index) => {
                        const starRating = parseInt(s.getAttribute('data-rating'));
                        if (starRating <= hoverRating) {
                            s.style.opacity = '1';
                        } else {
                            s.style.opacity = '0.5';
                        }
                    });
                });
            });
            
            // Reset on mouse leave
            const ratingContainer = document.getElementById('user-rating');
            if (ratingContainer) {
                ratingContainer.addEventListener('mouseleave', function() {
                    const currentRating = ratingSelect.value ? parseInt(ratingSelect.value) : 0;
                    ratingStars.forEach((star) => {
                        star.style.opacity = '1';
                    });
                    if (currentRating > 0) {
                        updateStars(currentRating);
                    } else {
                        ratingStars.forEach((star) => {
                            star.classList.remove('fas');
                            star.classList.add('far');
                        });
                    }
                });
            }
            
            // Sync with select field if changed manually
            ratingSelect.addEventListener('change', function() {
                const selectedRating = parseInt(this.value);
                if (selectedRating > 0) {
                    updateStars(selectedRating);
                } else {
                    ratingStars.forEach((star) => {
                        star.classList.remove('fas');
                        star.classList.add('far');
                    });
                }
            });
            
            // Initialize stars if rating is already selected
            if (ratingSelect.value) {
                updateStars(parseInt(ratingSelect.value));
            }
        }
    })();
    </script>

    <?php
}

get_footer();
