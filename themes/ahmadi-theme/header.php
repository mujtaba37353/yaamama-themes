<?php
$notice_post = ahmadi_theme_get_latest_post('ahmadi_site_notice');
$notice_text = $notice_post ? get_post_meta($notice_post->ID, 'ahmadi_notice_text', true) : '';
if ($notice_text === '') {
    $notice_text = 'خصم %20 على رسوم التوصيل';
}

$nav_categories = [];
if (function_exists('get_terms')) {
    $terms = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC',
    ]);
    if (!is_wp_error($terms)) {
        foreach ($terms as $term) {
            if (!($term instanceof WP_Term)) {
                continue;
            }
            if (strtolower((string) $term->slug) === 'uncategorized') {
                continue;
            }
            $link = get_term_link($term);
            if (is_wp_error($link)) {
                continue;
            }
            $nav_categories[] = [
                'name' => $term->name,
                'url' => $link,
            ];
        }
    }
}
$nav_fallback_url = ahmadi_theme_page_url('shop');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?php echo esc_url(ahmadi_theme_asset('assets/Frame 5.png')); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <div class="y-c-sale-banner">
        <p><?php echo esc_html($notice_text); ?></p>
    </div>
    <header class="y-c-header">
        <div class="y-c-header-container">
            <div class="y-c-header-right">
                <div class="y-c-cart-icon">
                    <a href="<?php echo esc_url(ahmadi_theme_page_url('cart')); ?>">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="y-c-cart-count">
                            <?php
                            $cart_count = 0;
                            if (class_exists('WooCommerce') && function_exists('WC') && WC()->cart) {
                                $cart_count = WC()->cart->get_cart_contents_count();
                            }
                            echo esc_html($cart_count);
                            ?>
                        </span>
                        <span class="y-c-cart-text">
                            <?php
                            $cart_text = 'ر.س 0.00';
                            if (class_exists('WooCommerce') && function_exists('WC') && WC()->cart) {
                                $cart_text = WC()->cart->get_cart_total();
                            }
                            echo wp_kses_post($cart_text);
                            ?>
                        </span>
                    </a>

                </div>
                <div class="y-c-user-icons">
                    <a href="<?php echo esc_url(ahmadi_theme_page_url('favorite')); ?>">
                        <i class="fa-solid fa-heart"></i>
                    </a>
                    <a href="<?php echo esc_url(ahmadi_theme_page_url('account')); ?>">
                        <i class="fa-solid fa-user"></i>
                    </a>
                </div>

            </div>
            <div class="y-c-phone-info">
                <i class="fa-solid fa-phone"></i>
                <a href="tel:+966534411732" class="y-c-contact-link">
                    <div>اتصل بنا</div>
                    <div>966534411732+</div>
                </a>
            </div>
            <div class="y-c-header-center">
                <div class="y-c-search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="search" class="y-c-search-input">
                </div>
            </div>

            <div class="y-c-header-left">
                <a href="<?php echo esc_url(ahmadi_theme_page_url('')); ?>">
                    <div class="y-c-logo">
                        <img src="<?php echo esc_url(ahmadi_theme_get_site_logo_url()); ?>" alt="Logo">
                    </div>
                </a>
            </div>
        </div>

        <nav class="y-c-navigation">
            <div class="y-c-nav-container">
                <div class="y-c-categories-dropdown">
                    <button class="y-c-categories-btn" id="categoriesBtn">
                        <i class="fas fa-bars"></i>
                        الفئات
                    </button>
                    <div class="y-c-dropdown-content" id="categoriesDropdown">
                        <?php if ($nav_categories) : ?>
                            <?php foreach ($nav_categories as $category) : ?>
                                <a href="<?php echo esc_url($category['url']); ?>"><?php echo esc_html($category['name']); ?></a>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <a href="<?php echo esc_url($nav_fallback_url); ?>">تسوق</a>
                        <?php endif; ?>
                    </div>
                </div>
                <ul class="y-c-nav-links">
                    <li><a href="<?php echo esc_url(ahmadi_theme_page_url('')); ?>">الرئيسية</a></li>
                    <li><a href="<?php echo esc_url(ahmadi_theme_page_url('shop')); ?>">تسوق</a></li>
                    <li><a href="<?php echo esc_url(ahmadi_theme_page_url('about-us')); ?>">من نحن</a></li>
                    <li><a href="<?php echo esc_url(ahmadi_theme_page_url('contact-us')); ?>">تواصل معنا</a></li>
                </ul>
                <div></div>
            </div>
        </nav>
    </header>

    <div class="y-c-mobile-header">

        <div class="y-c-mobile-logo">
            <a href="<?php echo esc_url(ahmadi_theme_page_url('')); ?>">
                <img src="<?php echo esc_url(ahmadi_theme_get_site_logo_url()); ?>" alt="Logo">
            </a>
        </div>
        <div class="y-c-mobile-icons">
            <a href="<?php echo esc_url(ahmadi_theme_page_url('favorite')); ?>">
                <i class="fa-solid fa-heart"></i>
            </a>
            <a href="<?php echo esc_url(ahmadi_theme_page_url('account')); ?>">
                <i class="fa-solid fa-user"></i>
            </a>
            <i class="fas fa-search mobile-search-icon" id="mobileSearchIcon"></i>
            <a href="<?php echo esc_url(ahmadi_theme_page_url('cart')); ?>">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="y-c-cart-count">
                    <?php
                    $cart_count = 0;
                    if (class_exists('WooCommerce') && function_exists('WC') && WC()->cart) {
                        $cart_count = WC()->cart->get_cart_contents_count();
                    }
                    echo esc_html($cart_count);
                    ?>
                </span>
            </a>
        </div>

    </div>
    <div class="y-c-mobile-header-actions">
        <button class="y-c-mobile-header-menu-btn" id="mobileMenuBtn">
            القائمة
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="y-c-mobile-nav-dropdown" id="mobileNavDropdown">
            <ul class="y-c-mobile-nav-links">
                <li><a href="<?php echo esc_url(ahmadi_theme_page_url('')); ?>">الرئيسية</a></li>
                <li><a href="<?php echo esc_url(ahmadi_theme_page_url('shop')); ?>">تسوق</a></li>
                <li><a href="<?php echo esc_url(ahmadi_theme_page_url('about-us')); ?>">من نحن</a></li>
                <li><a href="<?php echo esc_url(ahmadi_theme_page_url('contact-us')); ?>">تواصل معنا</a></li>
            </ul>
        </div>
        <div class="y-c-categories-dropdown">
            <button class="y-c-categories-btn mobile-categories-btn" id="categories-mobile-Btn">
                <i class="fas fa-bars"></i>
                الفئات
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="y-c-dropdown-content" id="categoriesMobileDropdown">
                <?php if ($nav_categories) : ?>
                    <?php foreach ($nav_categories as $category) : ?>
                        <a href="<?php echo esc_url($category['url']); ?>"><?php echo esc_html($category['name']); ?></a>
                    <?php endforeach; ?>
                <?php else : ?>
                    <a href="<?php echo esc_url($nav_fallback_url); ?>">تسوق</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="y-c-mobile-search-expanded" id="mobileSearchExpanded">
        <div class="y-c-search-container">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="search" class="y-c-search-input">
        </div>
    </div>
