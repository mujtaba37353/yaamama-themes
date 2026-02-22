<?php
if (!defined('ABSPATH')) {
    exit;
}
$cart_count = function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$footer_settings = wp_parse_args(al_thabihah_get_option('al_thabihah_footer_settings', array()), al_thabihah_default_footer_settings());
$header_logo_url = $footer_settings['header_logo_id'] ? wp_get_attachment_url($footer_settings['header_logo_id']) : al_thabihah_asset_uri('al-thabihah/assets/logo.png');
?>
<!doctype html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$sticky_header = is_page_template('page-templates/privacy-policy.php') || is_page_template('page-templates/replacement-policy.php') || is_page_template('page-templates/delivery-policy.php');
?>
<header class="y-l-header<?php echo $sticky_header ? ' y-c-sticky-header' : ''; ?>">
    <div class="y-l-header-container">

        <button id="mobile-menu-button" class="y-c-mobile-menu-btn" aria-label="Menu" aria-controls="header-mobile"
            aria-expanded="false">
            <i class="fas fa-bars" id="mobile-menu-icon"></i>
        </button>

        <a href="<?php echo esc_url(home_url('/')); ?>" class="y-c-header-logo" data-y="header-logo-link">
            <img src="<?php echo esc_url($header_logo_url); ?>" alt="Al Thabihah Logo" data-y="header-logo-img">
        </a>

        <nav class="y-l-header-nav" data-y="header-desktop-nav">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="y-c-nav-link" data-y="nav-link-home">الرئيسية</a>
            <a href="<?php echo esc_url(al_thabihah_get_page_link('offers')); ?>" class="y-c-nav-link" data-y="nav-link-offers">العروض</a>
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="y-c-nav-link" data-y="nav-link-store">جميع المنتجات</a>
            <a href="<?php echo esc_url(al_thabihah_get_page_link('about-us')); ?>" class="y-c-nav-link" data-y="nav-link-about">من نحن</a>
        </nav>

        <div class="y-l-header-actions" data-y="header-desktop-actions">

            <div class="y-c-header-search" data-y="desktop-search-container">
                <input type="search" id="expandable-search-input-desktop" class="y-c-search-input"
                    placeholder="...ابحث">
                <button class="y-c-header-icon-btn" id="expandable-search-icon-desktop" aria-label="Search">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="y-c-header-icon-btn" aria-label="Cart" data-y="header-icon-cart">
                <i class="fas fa-bag-shopping"></i>
                <span class="y-c-cart-badge<?php echo $cart_count > 0 ? ' y-c-cart-badge--show' : ''; ?>" data-y="cart-badge"><?php echo esc_html($cart_count); ?></span>
            </a>

            <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="y-c-header-icon-btn" aria-label="Account" data-y="header-icon-user">
                <i class="fas fa-user"></i>
            </a>

        </div>

    </div>

    <div id="header-mobile" class="y-l-mobile-menu" role="dialog" aria-modal="true" aria-hidden="true">

        <div class="y-l-mobile-menu-header">
            <button id="mobile-menu-close" class="y-c-mobile-menu-close" aria-label="Close Menu">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="y-c-mobile-search" data-y="mobile-search-container">
            <input type="search" id="mobile-search-input" class="y-c-search-input" placeholder="...ابحث">
            <button class="y-c-search-btn" aria-label="Search">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <nav class="y-l-header-mobile-links" data-y="header-mobile-nav">

            <a href="<?php echo esc_url(home_url('/')); ?>" data-y="header-mobile-link-home">الرئيسية</a>
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" data-y="header-mobile-link-store">المتجر</a>
            <a href="<?php echo esc_url(al_thabihah_get_page_link('about-us')); ?>" data-y="header-mobile-link-about">من نحن</a>
            <a href="<?php echo esc_url(al_thabihah_get_page_link('contact-us')); ?>" data-y="header-mobile-link-contact">تواصل معنا</a>
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" data-y="header-mobile-link-cart">السلة</a>
            <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" data-y="header-mobile-link-login">حسابي</a>

        </nav>
    </div>
</header>

<?php if (function_exists('woocommerce_output_all_notices')) : ?>
    <div class="y-u-container y-c-woo-notices">
        <?php woocommerce_output_all_notices(); ?>
    </div>
<?php endif; ?>
