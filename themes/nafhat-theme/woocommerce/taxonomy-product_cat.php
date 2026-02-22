<?php
/**
 * The Template for displaying product category archives.
 *
 * Override for WooCommerce template.
 *
 * @package Nafhat
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

get_header('shop');

// Get current category
$current_category = get_queried_object();
$category_thumbnail_id = get_term_meta($current_category->term_id, 'thumbnail_id', true);
$category_image = $category_thumbnail_id ? wp_get_attachment_url($category_thumbnail_id) : get_template_directory_uri() . '/assets/images/makeup.jpg';

// Get subcategories if any
$subcategories = get_terms(array(
    'taxonomy'   => 'product_cat',
    'hide_empty' => false,
    'parent'     => $current_category->term_id,
));

// Get products in this category
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$products_per_page = 16;

$products = wc_get_products(array(
    'limit'    => $products_per_page,
    'page'     => $paged,
    'category' => array($current_category->slug),
    'status'   => 'publish',
    'paginate' => true,
));

$total_products = $products->total;
$max_pages = $products->max_num_pages;
$products = $products->products;
?>

<main>
    <!-- Category Header -->
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col y-u-h-full">
        <h1 class="y-u-text-3xl y-u-text-bold y-u-mb-16 y-u-color-muted y-u-m-b-4">
            <?php echo esc_html($current_category->name); ?>
        </h1>
        <img src="<?php echo esc_url($category_image); ?>" alt="<?php echo esc_attr($current_category->name); ?>" class="y-u-w-full category-header-image" />
    </section>

    <!-- Products Section -->
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <!-- Filters -->
        <div class="filter y-u-flex y-u-flex-row y-u-justify-between y-u-items-center">
            <div class="left y-u-flex y-u-flex-row y-u-gap-16 y-u-items-center y-u-p-b-16">
                <a href="<?php echo esc_url(get_term_link($current_category)); ?>" class="filter-btn active"><?php esc_html_e('الكل', 'nafhat'); ?></a>
                <?php if (!empty($subcategories) && !is_wp_error($subcategories)) : ?>
                    <?php foreach ($subcategories as $subcat) : ?>
                    <a href="<?php echo esc_url(get_term_link($subcat)); ?>" class="filter-btn"><?php echo esc_html($subcat->name); ?></a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="filtration-button">
                <details class="filter-dropdown">
                    <summary class="filter-summary y-u-flex y-u-flex-row y-u-gap-24 y-u-items-center">
                        <?php esc_html_e('تصنيف', 'nafhat'); ?>
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/arrow-down.svg'); ?>" alt="arrow-down" />
                    </summary>
                    <div class="box filter-menu">
                        <details class="filter-dropdown">
                            <summary class="filter-summary y-u-flex y-u-flex-row y-u-gap-24 y-u-items-center">
                                <?php esc_html_e('ترتيب حسب', 'nafhat'); ?>
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/arrow-down.svg'); ?>" alt="arrow-down" />
                            </summary>
                            <div class="box filter-menu">
                                <a href="<?php echo esc_url(add_query_arg('orderby', 'popularity', get_term_link($current_category))); ?>"><?php esc_html_e('الأكثر شيوعاً', 'nafhat'); ?></a>
                                <a href="<?php echo esc_url(add_query_arg('orderby', 'date', get_term_link($current_category))); ?>"><?php esc_html_e('الأحدث', 'nafhat'); ?></a>
                                <a href="<?php echo esc_url(add_query_arg('orderby', 'price', get_term_link($current_category))); ?>"><?php esc_html_e('السعر: من الأقل', 'nafhat'); ?></a>
                                <a href="<?php echo esc_url(add_query_arg('orderby', 'price-desc', get_term_link($current_category))); ?>"><?php esc_html_e('السعر: من الأعلى', 'nafhat'); ?></a>
                            </div>
                        </details>
                    </div>
                </details>
            </div>
        </div>

        <!-- Products Grid -->
        <?php if (!empty($products)) : ?>
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
                $regular_price = $product->get_regular_price();
                $sale_price = $product->get_sale_price();
                $in_wishlist = is_user_logged_in() && function_exists('nafhat_is_in_wishlist') && nafhat_is_in_wishlist(get_current_user_id(), $product_id);
            ?>
            <li class="product-card" data-product_id="<?php echo esc_attr($product_id); ?>">
                <a href="<?php echo esc_url($product_link); ?>" class="product-link">
                    <div class="product-img">
                        <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>" />
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/heart.svg'); ?>" alt="<?php esc_attr_e('المفضلة', 'nafhat'); ?>" class="wishlist-icon <?php echo $in_wishlist ? 'in-wishlist' : ''; ?>" data-product_id="<?php echo esc_attr($product_id); ?>" />
                        <?php if ($product->is_on_sale() && $regular_price && $sale_price) : 
                            $discount_percent = round(((floatval($regular_price) - floatval($sale_price)) / floatval($regular_price)) * 100);
                        ?>
                        <div class="discount"><?php printf(esc_html__('خصم %s%%', 'nafhat'), $discount_percent); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="product-content">
                        <p class="product-title"><?php echo esc_html($product_title); ?></p>
                        <p class="product-description"><?php echo esc_html($product_description); ?></p>
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
            <?php endforeach; ?>
        </ul>

        <!-- Pagination -->
        <?php if ($max_pages > 1) : ?>
        <nav class="woocommerce-pagination nafhat-pagination">
            <?php
            echo paginate_links(array(
                'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                'format'    => '?paged=%#%',
                'current'   => max(1, $paged),
                'total'     => $max_pages,
                'prev_text' => '<i class="fas fa-chevron-right"></i>',
                'next_text' => '<i class="fas fa-chevron-left"></i>',
            ));
            ?>
        </nav>
        <?php endif; ?>

        <?php else : ?>
        <div class="no-products-found">
            <p><?php esc_html_e('لا توجد منتجات في هذه الفئة.', 'nafhat'); ?></p>
        </div>
        <?php endif; ?>
    </section>
</main>

<?php
get_footer('shop');
