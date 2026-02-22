<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="y-u-flex y-u-justify-center y-u-items-center y-u-fixed header y-u-top-left">
    <div class="container y-u-flex y-u-justify-between y-u-items-center y-u-w-full y-u-max-w-1200">
        <div class="logo y-u-flex y-u-justify-end y-u-items-center">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.png'); ?>" alt="<?php bloginfo('name'); ?>" />
                </a>
            <?php endif; ?>
            
            <?php
            // Get page URLs
            $shop_url = home_url('/shop');
            $contact_page = get_page_by_path('contact-us');
            $contact_url = $contact_page ? get_permalink($contact_page->ID) : home_url('/contact-us');
            $about_page = get_page_by_path('about-us');
            $about_url = $about_page ? get_permalink($about_page->ID) : home_url('/about-us');
            
            // Check if menu is assigned to primary location
            if (has_nav_menu('primary')) {
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'desktop-menu y-u-flex y-u-justify-end y-u-items-center',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'walker'         => new Nafhat_Walker_Nav_Menu(),
                ));
            } else {
                // Default menu fallback
                ?>
                <ul class="desktop-menu y-u-flex y-u-justify-end y-u-items-center">
                    <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('الرئيسية', 'nafhat'); ?></a></li>
                    <li><a href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('المتجر', 'nafhat'); ?></a></li>
                    <li><a href="<?php echo esc_url($contact_url); ?>"><?php esc_html_e('تواصل معنا', 'nafhat'); ?></a></li>
                    <li><a href="<?php echo esc_url($about_url); ?>"><?php esc_html_e('من نحن', 'nafhat'); ?></a></li>
                </ul>
                <?php
            }
            ?>
        </div>

        <button class="mobile-menu-btn y-u-flex y-u-justify-center y-u-items-center" aria-label="<?php esc_attr_e('تبديل القائمة المحمولة', 'nafhat'); ?>" type="button">
            <span class="hamburger-line hamburger-line-1"></span>
            <span class="hamburger-line hamburger-line-2"></span>
            <i class="fas fa-chevron-left mobile-menu-chevron"></i>
        </button>

        <div class="user-nav y-u-flex y-u-justify-end y-u-items-center">
            <?php if (function_exists('is_woocommerce')) : ?>
                <!-- Search Bar Container -->
                <div class="header-search-container">
                    <button type="button" class="header-search-toggle" aria-label="<?php esc_attr_e('بحث', 'nafhat'); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/search.svg'); ?>" alt="<?php esc_attr_e('بحث', 'nafhat'); ?>" />
                    </button>
                    <form role="search" method="get" class="header-search-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="header-search-input-wrapper">
                            <i class="fas fa-search header-search-icon"></i>
                            <input type="search" name="s" class="header-search-input" placeholder="<?php esc_attr_e('ابحث...', 'nafhat'); ?>" value="<?php echo get_search_query(); ?>" />
                        </div>
                    </form>
                </div>
                
                <?php if (is_user_logged_in()) : ?>
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/profile.svg'); ?>" alt="<?php esc_attr_e('حسابي', 'nafhat'); ?>" />
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/profile.svg'); ?>" alt="<?php esc_attr_e('تسجيل الدخول', 'nafhat'); ?>" />
                    </a>
                <?php endif; ?>
                
                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount') . 'wishlist'); ?>">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/heart.svg'); ?>" alt="<?php esc_attr_e('المفضلة', 'nafhat'); ?>" />
                </a>
                
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-icon">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/cart.svg'); ?>" alt="<?php esc_attr_e('السلة', 'nafhat'); ?>" />
                    <?php 
                    $cart_count = function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
                    ?>
                    <span class="cart-count" style="<?php echo $cart_count == 0 ? 'display: none;' : ''; ?>"><?php echo $cart_count; ?></span>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/search.svg'); ?>" alt="<?php esc_attr_e('بحث', 'nafhat'); ?>" />
                </a>
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/profile.svg'); ?>" alt="<?php esc_attr_e('حسابي', 'nafhat'); ?>" />
                </a>
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/heart.svg'); ?>" alt="<?php esc_attr_e('المفضلة', 'nafhat'); ?>" />
                </a>
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/cart.svg'); ?>" alt="<?php esc_attr_e('السلة', 'nafhat'); ?>" />
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Search Overlay Backdrop -->
    <div class="search-overlay-backdrop"></div>
    
    <div class="mobile-menu-overlay">
        <nav class="mobile-menu">
            <?php
            // Get page URLs (reuse from above if available, otherwise get them again)
            if (!isset($shop_url)) {
                $shop_url = home_url('/shop');
                $contact_page = get_page_by_path('contact-us');
                $contact_url = $contact_page ? get_permalink($contact_page->ID) : home_url('/contact-us');
                $about_page = get_page_by_path('about-us');
                $about_url = $about_page ? get_permalink($about_page->ID) : home_url('/about-us');
            }
            
            // Check if menu is assigned to primary location
            if (has_nav_menu('primary')) {
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'y-u-flex y-u-flex-col y-u-gap-16',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'walker'         => new Nafhat_Walker_Nav_Menu(),
                ));
            } else {
                // Default menu fallback
                ?>
                <ul class="y-u-flex y-u-flex-col y-u-gap-16">
                    <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('الرئيسية', 'nafhat'); ?></a></li>
                    <li><a href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('المتجر', 'nafhat'); ?></a></li>
                    <li><a href="<?php echo esc_url($contact_url); ?>"><?php esc_html_e('تواصل معنا', 'nafhat'); ?></a></li>
                    <li><a href="<?php echo esc_url($about_url); ?>"><?php esc_html_e('من نحن', 'nafhat'); ?></a></li>
                </ul>
                <?php
            }
            ?>
            
            <ul class="mobile-user-links y-u-flex y-u-justify-between y-u-items-center">
                <?php if (function_exists('is_woocommerce')) : ?>
                    <li><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/search.svg'); ?>" alt="<?php esc_attr_e('بحث', 'nafhat'); ?>" />
                    </a></li>
                    <li><a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/profile.svg'); ?>" alt="<?php esc_attr_e('حسابي', 'nafhat'); ?>" />
                    </a></li>
                    <li><a href="<?php echo esc_url(wc_get_page_permalink('myaccount') . 'wishlist'); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/heart.svg'); ?>" alt="<?php esc_attr_e('المفضلة', 'nafhat'); ?>" />
                    </a></li>
                    <li><a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-icon" style="position: relative;">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/cart.svg'); ?>" alt="<?php esc_attr_e('السلة', 'nafhat'); ?>" />
                        <?php if (function_exists('WC') && WC()->cart && WC()->cart->get_cart_contents_count() > 0) : ?>
                            <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                        <?php endif; ?>
                    </a></li>
                <?php else : ?>
                    <li><a href="<?php echo esc_url(home_url('/')); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/search.svg'); ?>" alt="<?php esc_attr_e('بحث', 'nafhat'); ?>" />
                    </a></li>
                    <li><a href="<?php echo esc_url(home_url('/')); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/profile.svg'); ?>" alt="<?php esc_attr_e('حسابي', 'nafhat'); ?>" />
                    </a></li>
                    <li><a href="<?php echo esc_url(home_url('/')); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/heart.svg'); ?>" alt="<?php esc_attr_e('المفضلة', 'nafhat'); ?>" />
                    </a></li>
                    <li><a href="<?php echo esc_url(home_url('/')); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/cart.svg'); ?>" alt="<?php esc_attr_e('السلة', 'nafhat'); ?>" />
                    </a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<?php
/**
 * Custom Walker for Navigation Menu
 */
class Nafhat_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu\">\n";
    }

    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names .'>';

        $attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';

        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes .'>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}
?>
