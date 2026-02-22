<?php
/**
 * The template for displaying product content within loops
 *
 * @package TechnoSouqTheme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}

// Get product data
$product_id = $product->get_id();
$product_title = get_the_title();

// Get product image using WooCommerce methods
// Try multiple methods to ensure we get an image
$product_image_url = '';

// Method 1: Get image ID and retrieve URL
$product_image_id = $product->get_image_id();
if ($product_image_id) {
    // Get the sized image URL
    $image_src = wp_get_attachment_image_src($product_image_id, 'woocommerce_thumbnail');
    if ($image_src && !empty($image_src[0])) {
        $product_image_url = $image_src[0];
    } else {
        // Fallback to full size
        $product_image_url = wp_get_attachment_url($product_image_id);
    }
}

// Method 2: If no image, try WooCommerce placeholder
if (empty($product_image_url)) {
    $placeholder_url = wc_placeholder_img_src('woocommerce_thumbnail');
    if ($placeholder_url) {
        $product_image_url = $placeholder_url;
    }
}

// Method 3: Ultimate fallback - ensure we always have a URL
if (empty($product_image_url)) {
    // Use theme placeholder
    $product_image_url = get_template_directory_uri() . '/techno-souq/assets/10.png';
}

// Ensure absolute URL
if (!empty($product_image_url)) {
    // Convert relative URLs to absolute
    if (strpos($product_image_url, 'http') !== 0 && strpos($product_image_url, '//') !== 0) {
        if (strpos($product_image_url, '/') === 0) {
            $product_image_url = home_url($product_image_url);
        } else {
            $product_image_url = home_url('/' . $product_image_url);
        }
    }
}

// Get pricing information
$regular_price = $product->get_regular_price();
$sale_price = $product->get_sale_price();
$discount_percentage = '';
$has_sale = false;

// Check if product is on sale
if ($product->is_on_sale()) {
    $has_sale = true;
    if ($regular_price && $sale_price) {
        // Calculate discount percentage
        $regular_price_float = (float) $regular_price;
        $sale_price_float = (float) $sale_price;
        if ($regular_price_float > 0) {
            $discount_percentage = round((($regular_price_float - $sale_price_float) / $regular_price_float) * 100);
        }
    }
}

// Get product price HTML (WooCommerce formatted)
$product_price_html = $product->get_price_html();

// Get product category
$categories = wp_get_post_terms($product_id, 'product_cat', array('orderby' => 'parent', 'order' => 'DESC'));
$category_name = !empty($categories) ? $categories[0]->name : '';

// Get stock status
$stock_status = $product->get_stock_status();
$is_in_stock = $product->is_in_stock();
$stock_status_text = '';
if (!$is_in_stock) {
    $stock_status_text = $product->get_availability()['class'];
}
?>

<li class="y-c-card <?php echo !$is_in_stock ? 'out-of-stock' : ''; ?>" data-product-id="<?php echo esc_attr($product_id); ?>" data-y="product-card">
    <div class="y-c-card__top-actions" data-y="card-top-actions">
        <a href="#" class="y-c-card__favorite" data-favorite-toggle data-product-id="<?php echo esc_attr($product_id); ?>" data-y="favorite-toggle">
            <i class="fas fa-heart" data-y="favorite-icon"></i>
        </a>
        <?php if ($has_sale && $discount_percentage > 0) : ?>
            <div class="y-c-card__discount" data-y="discount-badge">خصم <?php echo esc_html($discount_percentage); ?>%</div>
        <?php endif; ?>
    </div>

    <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="y-c-card__link" data-y="product-link">
        <div class="y-c-card__image-container" data-y="image-container">
            <?php if (!$is_in_stock) : ?>
                <div class="y-c-card__out-of-stock-badge">غير متوفر</div>
            <?php endif; ?>
            <?php
            // Final check: ensure we have a valid absolute URL
            if (empty($product_image_url)) {
                $product_image_url = wc_placeholder_img_src('woocommerce_thumbnail');
            }
            
            // Always ensure absolute URL (wp_get_attachment_image_src should return absolute, but double-check)
            if (!empty($product_image_url)) {
                // If URL doesn't start with http/https, make it absolute
                if (strpos($product_image_url, 'http') !== 0 && strpos($product_image_url, '//') !== 0) {
                    // It's a relative URL, make it absolute
                    if (strpos($product_image_url, '/') === 0) {
                        // Absolute path from root
                        $product_image_url = home_url($product_image_url);
                    } else {
                        // Relative path
                        $product_image_url = home_url('/' . $product_image_url);
                    }
                }
            }
            
            // Get placeholder URL for fallback
            $placeholder_url = wc_placeholder_img_src('woocommerce_thumbnail');
            if (!empty($placeholder_url) && strpos($placeholder_url, 'http') !== 0 && strpos($placeholder_url, '//') !== 0) {
                $placeholder_url = home_url($placeholder_url);
            }
            
            // Escape URLs
            $product_image_url = esc_url($product_image_url);
            $placeholder_url = esc_url($placeholder_url);
            ?>
            <img src="<?php echo $product_image_url; ?>" alt="<?php echo esc_attr($product_title); ?>" class="y-c-card__image" data-y="product-image" loading="lazy" onerror="this.onerror=null; if(this.src!=='<?php echo $placeholder_url; ?>'){this.src='<?php echo $placeholder_url; ?>';}">
        </div>
        <div class="y-c-card__body" data-y="card-body">
            <?php if ($category_name) : ?>
                <h3 class="y-c-card__category" data-y="product-category"><?php echo esc_html($category_name); ?></h3>
            <?php endif; ?>
            <h4 class="y-c-card__title" data-y="product-title"><?php echo esc_html($product_title); ?></h4>
            <div class="y-c-card__footer" data-y="card-footer">
                <div class="y-u-flex y-u-flex-column y-u-align-start" data-y="price-container">
                    <p class="y-c-card__price" data-y="product-price">
                        <?php
                        // Display price with currency icon
                        $current_price = $has_sale && $sale_price ? $sale_price : ($product->get_price() ? $product->get_price() : '0');
                        echo wc_price($current_price);
                        ?>
                    </p>
                    <?php if ($has_sale && $regular_price && $sale_price) : ?>
                        <p class="y-c-card__old-price" data-y="old-price">
                            <del><?php echo wc_price($regular_price); ?></del>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </a>
    
    <div class="y-c-card__actions" data-y="card-actions">
        <?php if ($is_in_stock && $product->is_purchasable()) : ?>
            <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" 
               class="y-c-card__btn" 
               data-product_id="<?php echo esc_attr($product_id); ?>" 
               data-y="cart-btn"
               aria-label="<?php echo esc_attr__('أضف إلى السلة', 'techno-souq-theme'); ?>">
                <i class="fas fa-shopping-cart" data-y="cart-icon"></i>
            </a>
        <?php else : ?>
            <span class="y-c-card__btn y-c-card__btn--disabled" data-y="cart-btn-disabled" aria-label="<?php echo esc_attr__('غير متوفر', 'techno-souq-theme'); ?>">
                <i class="fas fa-shopping-cart" data-y="cart-icon"></i>
            </span>
        <?php endif; ?>
    </div>
</li>
