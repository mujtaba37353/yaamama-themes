<?php
/**
 * The Template for displaying single products.
 *
 * Override for WooCommerce template.
 *
 * @package Nafhat
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

get_header('shop');

while (have_posts()) :
    the_post();

    global $product;
    
    // Make sure we have a valid product object
    if (!is_a($product, 'WC_Product')) {
        $product = wc_get_product(get_the_ID());
    }
    
    if (!$product) {
        continue;
    }

// Get product data
$product_id = $product->get_id();
$product_title = $product->get_name();
$product_description = $product->get_description();
$product_short_description = $product->get_short_description();
$product_price = $product->get_price();
$product_regular_price = $product->get_regular_price();
$product_sale_price = $product->get_sale_price();
$product_sku = $product->get_sku();
$stock_status = $product->get_stock_status();

// Get product gallery images
$attachment_ids = $product->get_gallery_image_ids();
$main_image_id = $product->get_image_id();
$main_image_url = $main_image_id ? wp_get_attachment_url($main_image_id) : wc_placeholder_img_src();

// Get product categories
$categories = wp_get_post_terms($product_id, 'product_cat');
$category_name = !empty($categories) ? $categories[0]->name : '';
$category_link = !empty($categories) ? get_term_link($categories[0]) : '';

// Get product brand if exists
$brands = wp_get_post_terms($product_id, 'product_brand');
$brand_name = !empty($brands) ? $brands[0]->name : $product_title;
?>

<main>
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <div class="y-u-max-w-1200 y-u-w-full pd-page">
            <!-- Breadcrumb -->
            <p class="pd-breadcrumb">
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"><?php esc_html_e('المتجر', 'nafhat'); ?></a>
                <?php if ($category_name) : ?>
                &lt; <a href="<?php echo esc_url($category_link); ?>"><?php echo esc_html($category_name); ?></a>
                <?php endif; ?>
                &lt; <?php echo esc_html($product_title); ?>
            </p>

            <!-- Product Hero Section -->
            <div class="pd-hero">
                <!-- Product Gallery -->
                <div class="pd-gallery">
                    <div class="pd-thumb-stack">
                        <?php if ($main_image_id) : ?>
                        <button class="thumb active" data-image="<?php echo esc_url($main_image_url); ?>">
                            <img src="<?php echo esc_url($main_image_url); ?>" alt="<?php esc_attr_e('صورة مصغرة', 'nafhat'); ?>" />
                        </button>
                        <?php endif; ?>
                        <?php if (!empty($attachment_ids)) : ?>
                            <?php foreach ($attachment_ids as $attachment_id) : 
                                $gallery_image_url = wp_get_attachment_url($attachment_id);
                            ?>
                            <button class="thumb" data-image="<?php echo esc_url($gallery_image_url); ?>">
                                <img src="<?php echo esc_url($gallery_image_url); ?>" alt="<?php esc_attr_e('صورة مصغرة', 'nafhat'); ?>" />
                            </button>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="pd-main-img">
                        <img id="pd-main-image" src="<?php echo esc_url($main_image_url); ?>" alt="<?php echo esc_attr($product_title); ?>" />
                    </div>
                </div>

                <!-- Product Info -->
                <div class="pd-info">
                    <p class="pd-brand"><?php echo esc_html($brand_name); ?></p>
                    <h1 class="pd-name"><?php echo esc_html($product_title); ?></h1>
                    <p class="pd-sku">
                        <?php if ($stock_status === 'instock') : ?>
                        <span class="in-stock"><?php esc_html_e('متوفر في المخزون', 'nafhat'); ?></span>
                        <?php else : ?>
                        <span class="out-of-stock"><?php esc_html_e('غير متوفر', 'nafhat'); ?></span>
                        <?php endif; ?>
                        <?php if ($product_sku) : ?>
                        <?php esc_html_e('رمز المنتج:', 'nafhat'); ?> <strong><?php echo esc_html($product_sku); ?></strong>
                        <?php endif; ?>
                    </p>
                    
                    <!-- Price -->
                    <div class="pd-price-block">
                        <span class="pd-price-label"><?php esc_html_e('السعر', 'nafhat'); ?></span>
                        <div class="pd-price-value">
                            <?php if ($product->is_on_sale() && $product_sale_price) : ?>
                            <span class="pd-price-old"><?php echo esc_html($product_regular_price); ?></span>
                            <span class="pd-price-number"><?php echo esc_html($product_sale_price); ?></span>
                            <?php else : ?>
                            <span class="pd-price-number"><?php echo esc_html($product_price); ?></span>
                            <?php endif; ?>
                            <span class="pd-price-currency"><?php esc_html_e('ر.س', 'nafhat'); ?></span>
                        </div>
                    </div>

                    <!-- Add to Cart Form -->
                    <?php do_action('woocommerce_before_add_to_cart_form'); ?>
                    <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
                        <?php do_action('woocommerce_before_add_to_cart_button'); ?>
                        <div class="pd-controls">
                            <?php 
                            $max_qty = $product->get_max_purchase_quantity();
                            // If max is -1 (unlimited), don't set max attribute
                            $max_attr = ($max_qty > 0) ? 'max="' . esc_attr($max_qty) . '"' : '';
                            ?>
                            <div class="pd-qty-control" aria-label="<?php esc_attr_e('تحديد الكمية', 'nafhat'); ?>">
                                <button type="button" class="qty-btn qty-minus" aria-label="<?php esc_attr_e('نقصان', 'nafhat'); ?>">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <input type="number" id="quantity" class="qty-value" name="quantity" value="1" min="1" <?php echo $max_attr; ?> />
                                <button type="button" class="qty-btn qty-plus" aria-label="<?php esc_attr_e('زيادة', 'nafhat'); ?>">
                                    <i class="fas fa-chevron-up"></i>
                                </button>
                            </div>
                            <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product_id); ?>" class="pd-add-btn single_add_to_cart_button button alt">
                                <?php esc_html_e('إضافة إلى السلة', 'nafhat'); ?>
                            </button>
                            <?php 
                            $in_wishlist = is_user_logged_in() && function_exists('nafhat_is_in_wishlist') && nafhat_is_in_wishlist(get_current_user_id(), $product_id);
                            ?>
                            <button type="button" class="pd-fav-btn <?php echo $in_wishlist ? 'in-wishlist' : ''; ?>" aria-label="<?php esc_attr_e('إضافة للمفضلة', 'nafhat'); ?>" data-product_id="<?php echo esc_attr($product_id); ?>">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                        <?php do_action('woocommerce_after_add_to_cart_button'); ?>
                    </form>
                    <?php do_action('woocommerce_after_add_to_cart_form'); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="pd-reviews container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <div class="y-u-max-w-1200 y-u-w-full">
            <!-- Review Form with Interactive Stars -->
            <?php if (get_option('woocommerce_enable_reviews') === 'yes' && $product->get_reviews_allowed()) : ?>
            <div class="reviews-form">
                <div class="reviews-summary">
                    <p class="reviews-label"><?php esc_html_e('تقييمك', 'nafhat'); ?></p>
                    <div class="pd-stars pd-stars-input" id="star-rating-input">
                        <i class="fas fa-star" data-rating="5"></i>
                        <i class="fas fa-star" data-rating="4"></i>
                        <i class="fas fa-star" data-rating="3"></i>
                        <i class="fas fa-star" data-rating="2"></i>
                        <i class="fas fa-star" data-rating="1"></i>
                    </div>
                    <p class="reviews-title"><?php esc_html_e('مراجعات', 'nafhat'); ?> (<?php echo esc_html($product->get_review_count()); ?>)</p>
                </div>
                
                <?php if (is_user_logged_in()) : ?>
                <form action="<?php echo esc_url(get_option('siteurl')); ?>/wp-comments-post.php" method="post" id="commentform">
                    <input type="hidden" name="rating" id="rating-value" value="" required />
                    <textarea name="comment" placeholder="<?php esc_attr_e('اكتب مراجعتك هنا...', 'nafhat'); ?>" rows="4" required></textarea>
                    <input type="hidden" name="comment_post_ID" value="<?php echo esc_attr($product_id); ?>" />
                    <input type="hidden" name="comment_parent" value="0" />
                    <button type="submit" class="pd-submit-review"><?php esc_html_e('إرسال', 'nafhat'); ?></button>
                </form>
                <?php else : ?>
                <p class="login-to-review">
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"><?php esc_html_e('سجل دخولك لتتمكن من إضافة مراجعة', 'nafhat'); ?></a>
                </p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <!-- Existing Reviews -->
            <?php
            $reviews = get_comments(array(
                'post_id' => $product_id,
                'status' => 'approve',
                'type' => 'review',
                'number' => 5,
            ));
            
            if (!empty($reviews)) :
            ?>
            <div class="reviews-list">
                <?php foreach ($reviews as $review) : 
                    $review_rating = get_comment_meta($review->comment_ID, 'rating', true);
                ?>
                <div class="review-item">
                    <div class="review-header">
                        <span class="review-author"><?php echo esc_html($review->comment_author); ?></span>
                        <div class="review-stars">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <i class="fas fa-star <?php echo $i <= $review_rating ? 'filled' : ''; ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <p class="review-content"><?php echo esc_html($review->comment_content); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Product Accordion -->
    <section class="pd-accordion container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <div class="y-u-max-w-1200 y-u-w-full">
            <?php if ($product_description) : ?>
            <details class="pd-accordion-item" open>
                <summary><?php esc_html_e('وصف المنتج', 'nafhat'); ?></summary>
                <div class="accordion-content"><?php echo wp_kses_post($product_description); ?></div>
            </details>
            <?php endif; ?>
            
            <?php if ($product_short_description) : ?>
            <details class="pd-accordion-item">
                <summary><?php esc_html_e('مميزات المنتج', 'nafhat'); ?></summary>
                <div class="accordion-content"><?php echo wp_kses_post($product_short_description); ?></div>
            </details>
            <?php endif; ?>
            
            <?php 
            // Get product attributes for additional info
            $attributes = $product->get_attributes();
            if (!empty($attributes)) :
            ?>
            <details class="pd-accordion-item">
                <summary><?php esc_html_e('معلومات عن المنتج', 'nafhat'); ?></summary>
                <div class="accordion-content">
                    <ul class="product-attributes">
                        <?php foreach ($attributes as $attribute) : 
                            $attribute_name = wc_attribute_label($attribute->get_name());
                            $attribute_value = $attribute->is_taxonomy() 
                                ? implode(', ', wc_get_product_terms($product_id, $attribute->get_name(), array('fields' => 'names')))
                                : implode(', ', $attribute->get_options());
                        ?>
                        <li><strong><?php echo esc_html($attribute_name); ?>:</strong> <?php echo esc_html($attribute_value); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </details>
            <?php endif; ?>
        </div>
    </section>

    <!-- Related Products -->
    <?php
    $related_products = wc_get_related_products($product_id, 4);
    if (!empty($related_products)) :
    ?>
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <div class="y-u-max-w-1200 y-u-mb-32 y-u-flex y-u-justify-between y-u-items-center">
            <h2 class="y-u-text-3xl y-u-text-bold y-u-mb-16 y-u-color-muted"><?php esc_html_e('منتجات ذات صلة:', 'nafhat'); ?></h2>
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="y-u-text-sm y-u-text-bold y-u-mb-16 show-all"><?php esc_html_e('عرض الكل', 'nafhat'); ?></a>
        </div>
        <ul class="products-grid">
            <?php foreach ($related_products as $related_id) : 
                $related = wc_get_product($related_id);
                if (!$related) continue;
                
                $related_link = get_permalink($related_id);
                $related_image = wp_get_attachment_url($related->get_image_id());
                if (!$related_image) {
                    $related_image = wc_placeholder_img_src();
                }
                $related_title = $related->get_name();
                $related_description = $related->get_short_description() ?: wp_trim_words($related->get_description(), 15);
                $related_price = $related->get_price();
            ?>
            <li class="product-card">
                <a href="<?php echo esc_url($related_link); ?>" class="product-link">
                    <div class="product-img">
                        <img src="<?php echo esc_url($related_image); ?>" alt="<?php echo esc_attr($related_title); ?>" />
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/heart.svg'); ?>" alt="<?php esc_attr_e('المفضلة', 'nafhat'); ?>" class="wishlist-icon" />
                        <?php if ($related->is_on_sale()) : ?>
                        <div class="discount"><?php esc_html_e('خصم', 'nafhat'); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="product-content">
                        <p class="product-title"><?php echo esc_html($related_title); ?></p>
                        <p class="product-description"><?php echo esc_html($related_description); ?></p>
                    </div>
                </a>
                <div class="product-actions">
                    <a href="<?php echo esc_url($related->add_to_cart_url()); ?>" class="product-add-to-cart">
                        <?php echo esc_html($related_price); ?> 
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/ريال.svg'); ?>" alt="<?php esc_attr_e('ريال', 'nafhat'); ?>" />
                    </a>
                    <button class="product-add-to-wishlist" data-product_id="<?php echo esc_attr($related_id); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/add.svg'); ?>" alt="<?php esc_attr_e('إضافة', 'nafhat'); ?>" />
                    </button>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif; ?>
</main>

<?php
endwhile; // end of the loop.

get_footer('shop');
