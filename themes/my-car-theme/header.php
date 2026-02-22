<?php
/**
 * The header template
 *
 * @package MyCarTheme
 */
?>
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

<header>
    <div class="y-l-header-container">
        <div class="y-l-header-top">
            <div class="y-l-right-side">
                <div class="y-c-header-logo">
                    <?php
                    if (has_custom_logo()) {
                        the_custom_logo();
                    } else {
                        ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>">
                            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/my-car/assets/logo.png" alt="<?php bloginfo('name'); ?>">
                        </a>
                        <?php
                    }
                    ?>
                </div>

                <nav class="y-l-header-nav">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'items_wrap'     => '%3$s',
                        'fallback_cb'    => false,
                        'walker'         => new My_Car_Nav_Walker(),
                    ));
                    ?>
                    
                    <?php
                    // Fallback menu items if no menu is set
                    if (!has_nav_menu('primary')) {
                        ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="y-c-nav-link">
                            <div><i class="fa-solid fa-house"></i></div>
                            <span>الرئيسية</span>
                        </a>
                        <?php
                        $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');
                        if (!$shop_url || $shop_url == home_url('/')) {
                            $shop_url = home_url('/shop/');
                        }
                        ?>
                        <a href="<?php echo esc_url($shop_url); ?>" class="y-c-nav-link">
                            <div><i class="fa-solid fa-car"></i></div>
                            <span>أسطولنا</span>
                        </a>
                        <?php
                        // Get offers page URL
                        $offers_page = get_page_by_path('offers');
                        if (!$offers_page) {
                            $offers_page = get_pages(array('meta_key' => '_wp_page_template', 'meta_value' => 'page-offers.php'));
                            $offers_page = !empty($offers_page) ? $offers_page[0] : null;
                        }
                        $offers_url = $offers_page ? get_permalink($offers_page->ID) : home_url('/offers');
                        ?>
                        <a href="<?php echo esc_url($offers_url); ?>" class="y-c-nav-link">
                            <div><i class="fa-solid fa-percent"></i></div>
                            <span>العروض</span>
                        </a>
                        <?php
                        // Get FAQ page URL
                        $faq_page = get_page_by_path('faq');
                        if (!$faq_page) {
                            $faq_page = get_pages(array('meta_key' => '_wp_page_template', 'meta_value' => 'page-faq.php'));
                            $faq_page = !empty($faq_page) ? $faq_page[0] : null;
                        }
                        $faq_url = $faq_page ? get_permalink($faq_page->ID) : home_url('/faq');
                        ?>
                        <a href="<?php echo esc_url($faq_url); ?>" class="y-c-nav-link">
                            <div><i class="fa-solid fa-circle-question"></i></div>
                            <span>الأسئلة الشائعة</span>
                        </a>
                        <?php
                    }
                    ?>
                </nav>
            </div>

            <div class="y-l-header-actions">
                <div class="y-c-header-search-expandable">
                    <?php get_search_form(); ?>
                </div>

                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="y-c-header-action" aria-label="Account">
                    <i class="fa-regular fa-user"></i>
                </a>

                <button class="y-c-mobile-menu-button" id="mobile-menu-button" data-y="mobile-menu-button"
                    aria-label="Toggle Mobile Menu" aria-expanded="false" aria-controls="header-mobile">
                    <i class="fas fa-bars" id="mobile-menu-icon"></i>
                </button>
            </div>
        </div>

        <div class="y-l-header-mobile" id="header-mobile" data-y="header-mobile" role="menu" aria-hidden="true">
            <div class="y-l-header-mobile-content">
                <div class="y-l-header-mobile-links" data-y="header-mobile-links" role="menubar">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'items_wrap'     => '%3$s',
                        'fallback_cb'    => 'my_car_mobile_menu_fallback',
                        'walker'         => new My_Car_Mobile_Nav_Walker(),
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</header>

<?php
/**
 * Custom Navigation Walker for Desktop Menu
 */
class My_Car_Nav_Walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $icon = '';
        // Map menu items to icons
        if (strpos(strtolower($item->title), 'أسطول') !== false || strpos(strtolower($item->title), 'store') !== false || strpos(strtolower($item->title), 'اسطول') !== false) {
            $icon = '<i class="fa-solid fa-car"></i>';
        } elseif (strpos(strtolower($item->title), 'عروض') !== false || strpos(strtolower($item->title), 'offer') !== false) {
            $icon = '<i class="fa-solid fa-percent"></i>';
        } elseif (strpos(strtolower($item->title), 'سؤال') !== false || strpos(strtolower($item->title), 'faq') !== false || strpos(strtolower($item->title), 'اسئلة') !== false || strpos(strtolower($item->title), 'أسئلة') !== false) {
            $icon = '<i class="fa-solid fa-circle-question"></i>';
        } elseif (strpos(strtolower($item->title), 'رئيس') !== false || strpos(strtolower($item->title), 'home') !== false) {
            $icon = '<i class="fa-solid fa-house"></i>';
        }

        // Force "أسطولنا" to go to shop page
        $url = $item->url;
        if ((strpos(strtolower($item->title), 'أسطول') !== false || strpos(strtolower($item->title), 'اسطول') !== false) && function_exists('wc_get_page_permalink')) {
            $url = wc_get_page_permalink('shop');
        } elseif (strpos(strtolower($item->title), 'عروض') !== false) {
            // Force "العروض" to go to offers page
            $offers_page = get_page_by_path('offers');
            if (!$offers_page) {
                $offers_page = get_pages(array('meta_key' => '_wp_page_template', 'meta_value' => 'page-offers.php'));
                $offers_page = !empty($offers_page) ? $offers_page[0] : null;
            }
            if ($offers_page) {
                $url = get_permalink($offers_page->ID);
            }
        } elseif (strpos(strtolower($item->title), 'سؤال') !== false || strpos(strtolower($item->title), 'faq') !== false || strpos(strtolower($item->title), 'اسئلة') !== false || strpos(strtolower($item->title), 'أسئلة') !== false) {
            // Force "الأسئلة الشائعة" to go to FAQ page
            $faq_page = get_page_by_path('faq');
            if (!$faq_page) {
                $faq_page = get_pages(array('meta_key' => '_wp_page_template', 'meta_value' => 'page-faq.php'));
                $faq_page = !empty($faq_page) ? $faq_page[0] : null;
            }
            if ($faq_page) {
                $url = get_permalink($faq_page->ID);
            }
        }
        
        $output .= $indent . '<a href="' . esc_url($url) . '" class="y-c-nav-link">';
        if ($icon) {
            $output .= '<div>' . $icon . '</div>';
        }
        $output .= '<span>' . apply_filters('the_title', $item->title, $item->ID) . '</span>';
        $output .= '</a>';
    }
}

/**
 * Custom Navigation Walker for Mobile Menu
 */
class My_Car_Mobile_Nav_Walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $icon = '';
        if (strpos(strtolower($item->title), 'أسطول') !== false || strpos(strtolower($item->title), 'store') !== false || strpos(strtolower($item->title), 'اسطول') !== false) {
            $icon = '<i class="fa-solid fa-car"></i>';
        } elseif (strpos(strtolower($item->title), 'عروض') !== false || strpos(strtolower($item->title), 'offer') !== false) {
            $icon = '<i class="fa-solid fa-percent"></i>';
        } elseif (strpos(strtolower($item->title), 'سؤال') !== false || strpos(strtolower($item->title), 'faq') !== false || strpos(strtolower($item->title), 'اسئلة') !== false || strpos(strtolower($item->title), 'أسئلة') !== false) {
            $icon = '<i class="fa-solid fa-circle-question"></i>';
        } elseif (strpos(strtolower($item->title), 'رئيس') !== false || strpos(strtolower($item->title), 'home') !== false) {
            $icon = '<i class="fa-solid fa-house"></i>';
        }

        // Force "أسطولنا" to go to shop page
        $url = $item->url;
        if ((strpos(strtolower($item->title), 'أسطول') !== false || strpos(strtolower($item->title), 'اسطول') !== false) && function_exists('wc_get_page_permalink')) {
            $url = wc_get_page_permalink('shop');
        } elseif (strpos(strtolower($item->title), 'عروض') !== false) {
            // Force "العروض" to go to offers page
            $offers_page = get_page_by_path('offers');
            if (!$offers_page) {
                $offers_page = get_pages(array('meta_key' => '_wp_page_template', 'meta_value' => 'page-offers.php'));
                $offers_page = !empty($offers_page) ? $offers_page[0] : null;
            }
            if ($offers_page) {
                $url = get_permalink($offers_page->ID);
            }
        } elseif (strpos(strtolower($item->title), 'سؤال') !== false || strpos(strtolower($item->title), 'faq') !== false || strpos(strtolower($item->title), 'اسئلة') !== false || strpos(strtolower($item->title), 'أسئلة') !== false) {
            // Force "الأسئلة الشائعة" to go to FAQ page
            $faq_page = get_page_by_path('faq');
            if (!$faq_page) {
                $faq_page = get_pages(array('meta_key' => '_wp_page_template', 'meta_value' => 'page-faq.php'));
                $faq_page = !empty($faq_page) ? $faq_page[0] : null;
            }
            if ($faq_page) {
                $url = get_permalink($faq_page->ID);
            }
        }
        
        $output .= '<a href="' . esc_url($url) . '" data-y="header-mobile-link" role="menuitem">';
        if ($icon) {
            $output .= $icon . ' ';
        }
        $output .= apply_filters('the_title', $item->title, $item->ID);
        $output .= '</a>';
    }
}

/**
 * Mobile Menu Fallback
 */
function my_car_mobile_menu_fallback() {
    ?>
    <a href="<?php echo esc_url(home_url('/')); ?>" data-y="header-mobile-link-home" role="menuitem">
        <i class="fa-solid fa-house"></i>
        الرئيسية
    </a>
    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" data-y="header-mobile-link-products" role="menuitem">
        <i class="fa-solid fa-car"></i>
        أسطولنا
    </a>
    <?php
    // Get offers page URL
    $offers_page = get_page_by_path('offers');
    if (!$offers_page) {
        $offers_page = get_pages(array('meta_key' => '_wp_page_template', 'meta_value' => 'page-offers.php'));
        $offers_page = !empty($offers_page) ? $offers_page[0] : null;
    }
    $offers_url = $offers_page ? get_permalink($offers_page->ID) : home_url('/offers');
    ?>
    <a href="<?php echo esc_url($offers_url); ?>" data-y="header-mobile-link-offers" role="menuitem">
        <i class="fa-solid fa-percent"></i>
        العروض
    </a>
    <?php
    // Get FAQ page URL
    $faq_page = get_page_by_path('faq');
    if (!$faq_page) {
        $faq_page = get_pages(array('meta_key' => '_wp_page_template', 'meta_value' => 'page-faq.php'));
        $faq_page = !empty($faq_page) ? $faq_page[0] : null;
    }
    $faq_url = $faq_page ? get_permalink($faq_page->ID) : home_url('/faq');
    ?>
    <a href="<?php echo esc_url($faq_url); ?>" data-y="header-mobile-link-faq" role="menuitem">
        <i class="fa-solid fa-circle-question"></i>
        الأسئلة الشائعة
    </a>
    <?php
}
