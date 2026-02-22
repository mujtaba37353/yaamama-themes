<?php
/**
 * The header template file
 *
 * @package TechnoSouqTheme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="y-l-sticky-header" data-y="header-container">
    <div class="y-l-header" data-y="header-main">
        <div class="y-l-header-right" data-y="header-left-section">
            <a href="#" class="y-c-header-menu-bars" data-y="header-menu" id="menu-toggle">
                <i class="fa-solid fa-bars" data-y="menu-bars-icon"></i>
                القائمة
            </a>
            <div class="y-l-header-input-container" data-y="header-search-container">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="search" name="s" class="y-c-header-input" placeholder="ابحث عن منتج ..." 
                        value="<?php echo get_search_query(); ?>" data-y="header-search-input">
                    <i class="fas fa-search y-c-search-icon" data-y="header-search-icon"></i>
                </form>
            </div>
        </div>

        <div class="y-l-header-center" data-y="header-center-section">
            <a href="<?php echo esc_url(home_url('/')); ?>" data-y="header-logo-link">
                <?php
                $logo_url = techno_souq_asset_url('logo.png');
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    echo '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr(get_bloginfo('name')) . '" data-y="header-logo">';
                }
                ?>
            </a>
        </div>

        <div class="y-l-header-links" data-y="header-right-section">
            <?php if (is_user_logged_in()) : ?>
                <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" data-y="header-account-link">
                    <i class="fa-regular fa-user" data-y="account-icon"></i>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" data-y="header-account-link">
                    <i class="fa-regular fa-user" data-y="account-icon"></i>
                </a>
            <?php endif; ?>
            
            <?php
            // Get wishlist page URL
            $wishlist_page = get_page_by_path('wishlist');
            $wishlist_url = $wishlist_page ? get_permalink($wishlist_page) : home_url('/wishlist');
            ?>
            <a href="<?php echo esc_url($wishlist_url); ?>" data-y="header-favorite-link">
                <i class="fa-regular fa-heart" data-y="favorite-icon"></i>
            </a>
            
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" data-y="header-cart-link">
                <i class="fa-solid fa-cart-shopping" data-y="cart-icon"></i>
                <?php if (WC()->cart && !WC()->cart->is_empty()) : ?>
                    <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                <?php endif; ?>
            </a>
            
            <div class="y-c-header-price" data-y="header-price">
                <?php if (WC()->cart) : ?>
                    <span class="y-c-price-value"><?php echo WC()->cart->get_cart_total(); ?></span>
                <?php else : ?>
                    <span class="y-c-price-value">0</span>
                <?php endif; ?>
                <span class="y-c-price-currency">
                    <img src="<?php echo esc_url(techno_souq_asset_url('coin.png')); ?>" alt="Currency">
                </span>
            </div>
        </div>
    </div>

    <!-- Mobile menu backdrop -->
    <div class="y-l-mega-menu-backdrop" id="mega-menu-backdrop" data-y="menu-backdrop"></div>

    <div class="y-l-mega-menu" id="mega-menu" data-y="mega-menu">
        <div class="y-l-mega-menu-container" data-y="mega-menu-container">
            <!-- Mobile menu close button -->
            <button class="y-c-mobile-menu-close" id="mobile-menu-close" aria-label="إغلاق القائمة" data-y="menu-close-btn">
                <i class="fa-solid fa-times" data-y="close-icon"></i>
            </button>

            <div data-y="main-pages-section">
                <div class="y-c-mega-menu-list y-c-mega-menu-pages" data-y="main-pages-list">
                    <a href="<?php echo esc_url(home_url('/')); ?>" data-y="home-link">
                        <i class="fa-solid fa-home" data-y="home-icon"></i>
                        الرئيسية
                    </a>
                    <?php
                    // Get shop page URL - always use product archive link for shop page
                    // This ensures it goes to the shop archive, not the front page
                    if (function_exists('get_post_type_archive_link')) {
                        $shop_url = get_post_type_archive_link('product');
                    }
                    
                    // Fallback: try WooCommerce shop page if archive link doesn't work
                    if (empty($shop_url) && function_exists('wc_get_page_permalink')) {
                        $shop_url = wc_get_page_permalink('shop');
                    }
                    
                    // Final fallback
                    if (empty($shop_url)) {
                        $shop_url = home_url('/shop');
                    }
                    ?>
                    <a href="<?php echo esc_url($shop_url); ?>" data-y="shop-link">
                        <i class="fa-solid fa-shopping-bag" data-y="shop-icon"></i>
                        تسوق
                    </a>
                    <?php
                    // Get About Us page or create link to home
                    $about_page = get_page_by_path('about-us');
                    $about_url = $about_page ? get_permalink($about_page) : home_url('/about-us');
                    ?>
                    <a href="<?php echo esc_url($about_url); ?>" data-y="about-link">
                        <i class="fa-solid fa-users" data-y="about-icon"></i>
                        من نحن
                    </a>
                    <?php
                    // Get Contact Us page or create link to home
                    $contact_page = get_page_by_path('contact-us');
                    $contact_url = $contact_page ? get_permalink($contact_page) : home_url('/contact-us');
                    ?>
                    <a href="<?php echo esc_url($contact_url); ?>" data-y="contact-link">
                        <i class="fa-solid fa-phone" data-y="contact-icon"></i>
                        تواصل معنا
                    </a>
                </div>
            </div>

            <?php
            // Display product categories in mega menu - only main categories (parent = 0)
            $product_categories = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'parent' => 0, // Only get main categories
                'orderby' => 'name',
                'order' => 'ASC',
                'number' => 10, // Increase limit to show all main categories
            ));

            if (!empty($product_categories) && !is_wp_error($product_categories)) :
                foreach ($product_categories as $category) :
                    // Skip uncategorized category
                    if ($category->slug === 'uncategorized') {
                        continue;
                    }
                    ?>
                    <div class="y-l-mega-menu-column" data-y="category-<?php echo esc_attr($category->term_id); ?>-column">
                        <h3 class="y-c-mega-menu-title" data-y="category-<?php echo esc_attr($category->term_id); ?>-title">
                            <?php echo esc_html($category->name); ?>
                            <i class="fa-solid fa-chevron-down y-c-mobile-menu-arrow" data-y="category-<?php echo esc_attr($category->term_id); ?>-arrow"></i>
                        </h3>
                        <?php
                        // Get subcategories for this main category
                        $subcategories = get_terms(array(
                            'taxonomy' => 'product_cat',
                            'hide_empty' => true,
                            'parent' => $category->term_id,
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'number' => 10, // Show all subcategories
                        ));
                        if (!empty($subcategories) && !is_wp_error($subcategories)) :
                            ?>
                            <ul class="y-c-mega-menu-list" data-y="category-<?php echo esc_attr($category->term_id); ?>-list">
                                <?php foreach ($subcategories as $subcat) : ?>
                                    <li>
                                        <a href="<?php echo esc_url(get_term_link($subcat)); ?>" data-y="subcategory-<?php echo esc_attr($subcat->term_id); ?>-link">
                                            <?php echo esc_html($subcat->name); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <!-- If no subcategories, show link to main category -->
                            <ul class="y-c-mega-menu-list" data-y="category-<?php echo esc_attr($category->term_id); ?>-list">
                                <li>
                                    <a href="<?php echo esc_url(get_term_link($category)); ?>" data-y="category-<?php echo esc_attr($category->term_id); ?>-link">
                                        عرض جميع المنتجات
                                    </a>
                                </li>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <?php
                endforeach;
            endif;
            ?>
            
            <div class="y-c-mega-menu-list y-c-mega-menu-pages" data-y="mobile-only-links">
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" data-y="mobile-cart-link">
                    <i class="fa-solid fa-bag-shopping" data-y="mobile-cart-icon"></i>
                    سلة المشتريات
                </a>
            </div>
        </div>
    </div>
</header>
