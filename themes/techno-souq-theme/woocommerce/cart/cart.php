<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * @package TechnoSouqTheme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();

// Remove default WooCommerce wrappers and page title
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_before_cart', 'woocommerce_output_all_notices', 10);

// Enqueue cart styles
$theme_version = wp_get_theme()->get('Version');
$techno_souq_path = get_template_directory_uri() . '/techno-souq';
$cart_css_path = get_template_directory() . '/techno-souq/templates/cart/y-cart.css';
$cart_css_version = file_exists($cart_css_path) ? filemtime($cart_css_path) : $theme_version;
wp_enqueue_style('techno-souq-cart', $techno_souq_path . '/templates/cart/y-cart.css', array(
    'techno-souq-header',
    'techno-souq-footer',
    'techno-souq-cards',
    'techno-souq-buttons'
), $cart_css_version);

// Enqueue cart scripts
wp_enqueue_script('techno-souq-cart', $techno_souq_path . '/js/cart.js', array('techno-souq-shared-components'), $theme_version, true);
wp_enqueue_script('techno-souq-cart-quantity', $techno_souq_path . '/js/cart-quantity.js', array('jquery'), $theme_version, true);

// Only call woocommerce_before_cart if cart is not empty (to prevent default empty cart message)
if (!WC()->cart->is_empty()) {
    do_action('woocommerce_before_cart');
}
?>

<main data-y="cart-main">
    <?php if (WC()->cart->is_empty()) : ?>
        <?php wc_get_template('cart/cart-empty.php'); ?>
    <?php else : ?>
    <section class="y-l-container" data-y="cart-container">
        <h2 class="y-c-cart-page-title" data-y="cart-page-title"><?php esc_html_e('سلة المشتريات', 'techno-souq-theme'); ?></h2>
        
        <p class="y-c-breadcrumb" data-y="cart-breadcrumb">
            <a href="<?php echo esc_url(home_url('/')); ?>" data-y="breadcrumb-home"><?php esc_html_e('الرئيسية', 'techno-souq-theme'); ?></a>
            <span data-y="breadcrumb-separator"> > </span>
            <?php esc_html_e('سلة المشتريات', 'techno-souq-theme'); ?>
        </p>

        <?php
        // Display WooCommerce notices
        wc_print_notices();
        ?>
        
        <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                <?php do_action('woocommerce_before_cart_table'); ?>

                <div class="y-l-cart-container" data-y="cart-content-container">
                    <div class="y-l-cart-items" data-y="cart-items-section">
                        <ul class="y-l-cart-cards" data-y="cart-items-list">
                            <?php
                            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                                if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                                    
                                    // Get product category
                                    $categories = wp_get_post_terms($product_id, 'product_cat');
                                    $category_name = !empty($categories) ? $categories[0]->name : '';
                                    
                                    // Get product price
                                    $price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                                    ?>
                                    <li class="y-c-cart-item" data-y="cart-item">
                                        <div class="cart-item__product">
                                            <?php
                                            $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail'), $cart_item, $cart_item_key);
                                            if (!$product_permalink) {
                                                echo $thumbnail; // PHPCS: XSS ok.
                                            } else {
                                                printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
                                            }
                                            ?>
                                        </div>
                                        
                                        <div class="y-c-cart-item__details">
                                            <?php if ($category_name) : ?>
                                                <h2><?php echo esc_html($category_name); ?></h2>
                                            <?php endif; ?>
                                            
                                            <h3 class="y-c-cart-item__name">
                                                <?php
                                                if (!$product_permalink) {
                                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                                } else {
                                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                                }
                                                
                                                do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);
                                                
                                                // Meta data.
                                                echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.
                                                
                                                // Backorder notification.
                                                if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
                                                }
                                                ?>
                                            </h3>
                                            
                                            <h3><?php esc_html_e('السعر', 'techno-souq-theme'); ?></h3>
                                            <div class="y-c-cart-item__price">
                                                <?php echo $price; // PHPCS: XSS ok. ?>
                                            </div>
                                        </div>
                                        
                                        <div class="y-c-cart-item__actions">
                                            <?php
                                            // Use WooCommerce's standard remove link which handles the removal properly
                                            $remove_url = esc_url(wc_get_cart_remove_url($cart_item_key));
                                            ?>
                                            <a href="<?php echo $remove_url; ?>" class="y-c-cart-item__remove-btn remove" aria-label="<?php esc_attr_e('Remove this item', 'woocommerce'); ?>" data-product_id="<?php echo esc_attr($product_id); ?>" data-product_sku="<?php echo esc_attr($_product->get_sku()); ?>">
                                                <i class="fa-solid fa-x"></i>
                                            </a>
                                            
                                            <div class="y-c-quantity-control">
                                                <button type="button" class="y-c-quantity-btn" id="decrease-quantity-<?php echo esc_attr($cart_item_key); ?>">-</button>
                                                <?php
                                                if ($_product->is_sold_individually()) {
                                                    $min_quantity = 1;
                                                    $max_quantity = 1;
                                                } else {
                                                    $min_quantity = 0;
                                                    $max_quantity = $_product->get_max_purchase_quantity();
                                                }
                                                
                                                $product_quantity = woocommerce_quantity_input(
                                                    array(
                                                        'input_name'   => "cart[{$cart_item_key}][qty]",
                                                        'input_value'  => $cart_item['quantity'],
                                                        'max_value'    => $max_quantity,
                                                        'min_value'    => $min_quantity,
                                                        'product_name' => $_product->get_name(),
                                                        'classes'      => array('y-c-quantity-input'),
                                                    ),
                                                    $_product,
                                                    false
                                                );
                                                
                                                echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
                                                ?>
                                                <button type="button" class="y-c-quantity-btn" id="increase-quantity-<?php echo esc_attr($cart_item_key); ?>">+</button>
                                            </div>
                                        </div>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                        
                        <div class="y-l-cart-summary" data-y="cart-summary-section">
                            <div class="y-c-cart-summary-card" data-y="cart-summary-card">
                                <h3 data-y="cart-summary-title"><?php esc_html_e('ملخص الطلب', 'techno-souq-theme'); ?></h3>
                                
                                <?php do_action('woocommerce_cart_collaterals'); ?>
                                
                                <?php do_action('woocommerce_cart_actions'); ?>
                                
                                <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php do_action('woocommerce_after_cart_table'); ?>
            </form>
            
            <?php do_action('woocommerce_before_cart_collaterals'); ?>
            
            <?php do_action('woocommerce_after_cart'); ?>
        <?php endif; ?>
    </section>
</main>

<?php
get_footer();