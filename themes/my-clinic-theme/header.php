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

<header class="y-u-flex y-u-justify-center y-u-items-center y-u-fixed header y-u-top-left">
    <div class="container y-u-flex y-u-justify-between y-u-items-center y-u-w-full y-u-max-w-1200">
        <div class="logo y-u-flex y-u-justify-end y-u-items-center">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="y-u-text-3xl y-u-font-bold y-u-color-primary">
                <?php
                $logo_url = get_template_directory_uri() . '/assets/images/navbar-icon.png';
                if (file_exists(get_template_directory() . '/assets/images/navbar-icon.png')) {
                    echo '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr(get_bloginfo('name')) . '">';
                } else {
                    echo '<span>' . esc_html(get_bloginfo('name')) . '</span>';
                }
                ?>
            </a>
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container' => false,
                'menu_class' => 'desktop-menu y-u-flex y-u-justify-end y-u-items-center',
                'fallback_cb' => 'my_clinic_fallback_menu',
            ));
            ?>
        </div>
        <button class="mobile-menu-btn y-u-flex-col y-u-justify-between" aria-label="Toggle mobile menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="user-nav y-u-flex y-u-justify-end y-u-items-center">
            <a href="<?php echo esc_url(my_clinic_get_myaccount_url()); ?>">
                حسابي
            </a>
        </div>
    </div>

    <div class="mobile-menu-overlay">
        <nav class="mobile-menu">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container' => false,
                'menu_class' => 'y-u-flex y-u-flex-col y-u-gap-16',
                'fallback_cb' => 'my_clinic_fallback_menu',
            ));
            ?>
            <ul class="y-u-flex y-u-flex-col y-u-gap-16">
                <li><a href="<?php echo esc_url(my_clinic_get_myaccount_url()); ?>">حسابي</a></li>
            </ul>
        </nav>
    </div>
</header>
