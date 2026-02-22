<?php
/**
 * Template Name: صفحة العروض
 * The template for displaying offers page (products with sale price)
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;

get_header();
?>

<main data-y="store-main">

    <div class="y-u-container" data-y="store-content-container">
        <!-- Offers Page Title -->
        <section class="y-l-store-filters" data-y="store-filters">
            <h2 class="y-c-page-title">عروضنا</h2>
        </section>

        <!-- Products Section -->
        <div class="y-l-products-section">
            <?php
            // Get all products
            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'DESC',
            );

            $products_query = new WP_Query($args);
            $has_sale_products = false;
            $sale_products_count = 0;

            if ($products_query->have_posts()) {
                // First, count products on sale
                while ($products_query->have_posts()) {
                    $products_query->the_post();
                    global $product;
                    if (!$product) {
                        $product = wc_get_product(get_the_ID());
                    }
                    
                    if ($product && $product->is_on_sale()) {
                        $has_sale_products = true;
                        $sale_products_count++;
                    }
                }
                
                // Reset query
                wp_reset_postdata();
                
                // Re-query to display products
                $products_query = new WP_Query($args);

                if ($has_sale_products) {
                    ?>
                    <ul class="y-l-products-list" id="products-container">
                        <?php
                        while ($products_query->have_posts()) {
                            $products_query->the_post();
                            global $product;
                            if (!$product) {
                                $product = wc_get_product(get_the_ID());
                            }
                            
                            // Only show products that are actually on sale
                            if ($product && $product->is_on_sale()) {
                                wc_get_template_part('content', 'product-list');
                            }
                        }
                        ?>
                    </ul>

                    <div class="y-l-show-more-container">
                        <?php
                        // Pagination if needed
                        ?>
                    </div>
                    <?php
                    wp_reset_postdata();
                } else {
                    // No products on sale
                    ?>
                    <div class="y-l-no-offers" data-y="no-offers">
                        <div class="y-c-no-offers-box">
                            <p class="y-c-no-offers-message">لا يوجد عروض حالياً</p>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="y-c-basic-btn">
                                العودة إلى الرئيسية
                            </a>
                        </div>
                    </div>
                    <?php
                    wp_reset_postdata();
                }
            } else {
                // No products at all
                ?>
                <div class="y-l-no-offers" data-y="no-offers">
                    <div class="y-c-no-offers-box">
                        <p class="y-c-no-offers-message">لا يوجد عروض حالياً</p>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="y-c-basic-btn">
                            العودة إلى الرئيسية
                        </a>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</main>

<?php
get_footer();
?>
