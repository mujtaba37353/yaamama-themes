<?php
/**
 * The template for displaying product in list view
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;

global $product;

if (!$product) {
    return;
}
?>

<li <?php wc_product_class('y-c-product-card', $product); ?>>
    <?php
    // Get product category
    $categories = wp_get_post_terms($product->get_id(), 'product_cat');
    $category_name = !empty($categories) && !is_wp_error($categories) ? $categories[0]->name : '';
    
    // Product image
    $image_id = $product->get_image_id();
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : wc_placeholder_img_src('large');
    
    // Product price
    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();
    $price = $sale_price ? $sale_price : $regular_price;
    
    // Product features (using meta or attributes)
    $features = array();
    if ($product->has_attributes()) {
        $attributes = $product->get_attributes();
        foreach ($attributes as $attr) {
            if ($attr->get_visible()) {
                $feature_name = wc_attribute_label($attr->get_name());
                if ($feature_name) {
                    $features[] = $feature_name;
                }
            }
        }
    }
    ?>
    
    <div class="y-c-card-layout">
        <!-- Product Image -->
        <div class="y-c-card-image">
            <a href="<?php echo esc_url($product->get_permalink()); ?>">
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
            </a>
        </div>

        <!-- Product Details -->
        <div class="y-c-card-details">
            <!-- Product Info -->
            <div class="y-c-card-info">
                <?php if ($category_name) : ?>
                    <div class="y-c-card-category">
                        <i class="fa-solid fa-car"></i>
                        <span><?php echo esc_html($category_name); ?></span>
                    </div>
                <?php endif; ?>

                <h3 class="y-c-card-name">
                    <a href="<?php echo esc_url($product->get_permalink()); ?>"><?php echo esc_html($product->get_name()); ?></a>
                </h3>

                <?php
                // Display short description or excerpt
                $short_description = $product->get_short_description();
                if ($short_description) {
                    echo '<p class="y-c-card-similar">' . wp_kses_post($short_description) . '</p>';
                }
                ?>

                <!-- Product Features -->
                <?php if (!empty($features)) : ?>
                    <div class="y-c-card-features">
                        <?php
                        $display_features = array_slice($features, 0, 4); // Show max 4 features
                        foreach ($display_features as $feature) {
                            echo '<div class="y-c-card-feature-item">';
                            echo '<i class="fa-solid fa-check"></i>';
                            echo '<span>' . esc_html($feature) . '</span>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Pricing -->
            <div class="y-c-card-pricing">
                <span class="y-c-card-price-label">السعر اليومي</span>
                
                <?php if ($sale_price && $sale_price < $regular_price) : ?>
                    <div class="y-c-card-price-old">
                        <?php echo esc_html($regular_price); ?>
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/my-car/assets/coin.png" alt="ريال" class="y-c-coin-icon">
                    </div>
                <?php endif; ?>
                
                <div class="y-c-card-price-amount">
                    <?php echo esc_html($price); ?>
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/my-car/assets/coin.png" alt="ريال" class="y-c-coin-icon">
                </div>

                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="y-c-basic-btn y-c-fleet-book-btn">
                    احجز الان
                </a>
            </div>
        </div>
    </div>
</li>
