<?php
/**
 * My Account page
 *
 * @package TechnoSouqTheme
 */

defined('ABSPATH') || exit;

// Enqueue account styles
$theme_version = wp_get_theme()->get('Version');
$techno_souq_path = get_template_directory_uri() . '/techno-souq';
wp_enqueue_style('techno-souq-account', $techno_souq_path . '/templates/account/y-account.css', array(
    'techno-souq-header',
    'techno-souq-footer',
    'techno-souq-forms',
    'techno-souq-buttons',
    'techno-souq-cards'
), $theme_version);

// Enqueue account scripts
wp_enqueue_script('techno-souq-account', $techno_souq_path . '/js/account.js', array('jquery'), $theme_version, true);

// Get current endpoint
$current_endpoint = WC()->query->get_current_endpoint();
if (empty($current_endpoint)) {
    $current_endpoint = 'dashboard';
}

// Get account page URL
$account_url = wc_get_page_permalink('myaccount');
?>

<div data-y="account-main">
    <div class="y-l-container" data-y="account-container">
        <h1 data-y="account-page-title"><?php esc_html_e('حسابي', 'techno-souq-theme'); ?></h1>
        <p class="y-c-breadcrumb" data-y="account-breadcrumb">
            <a href="<?php echo esc_url(home_url('/')); ?>" data-y="breadcrumb-home"><?php esc_html_e('الرئيسية', 'techno-souq-theme'); ?></a>
            <span data-y="breadcrumb-separator"> > </span>
            <?php esc_html_e('حسابي', 'techno-souq-theme'); ?>
        </p>

        <h3 class="y-c-header-title" data-y="account-header-title" style="display: none;"><?php esc_html_e('حسابي', 'techno-souq-theme'); ?></h3>
        
        <?php
        // Hide menu and navigation for non-logged in users on register/login pages
        $is_register_page = !is_user_logged_in() && isset($_GET['action']) && $_GET['action'] === 'register';
        $is_login_page = !is_user_logged_in() && (!isset($_GET['action']) || $_GET['action'] !== 'register');
        ?>
        
        <?php if (is_user_logged_in() || (!$is_register_page && !$is_login_page)) : ?>
        <div class="y-c-Profile-container" data-y="account-profile-container">
            <div class="y-c-Profile-menu" data-y="profile-menu">
                <a href="<?php echo esc_url($account_url); ?>" 
                   id="account-details-link" 
                   data-title="تفاصيل الحساب" 
                   data-y="account-details-link"
                   class="<?php echo ($current_endpoint === 'dashboard' || empty($current_endpoint)) ? 'y-c-active' : ''; ?>">
                    <p><?php esc_html_e('تفاصيل الحساب', 'techno-souq-theme'); ?></p>
                </a>
                <a href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>" 
                   id="orders-link" 
                   data-title="الطلبات" 
                   data-y="orders-link"
                   class="<?php echo ($current_endpoint === 'orders') ? 'y-c-active' : ''; ?>">
                    <p><?php esc_html_e('الطلبات', 'techno-souq-theme'); ?></p>
                </a>
                <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address')); ?>" 
                   id="address-link" 
                   data-title="العنوان" 
                   data-y="address-link"
                   class="<?php echo ($current_endpoint === 'edit-address') ? 'y-c-active' : ''; ?>">
                    <p><?php esc_html_e('العنوان', 'techno-souq-theme'); ?></p>
                </a>
                <a href="<?php echo esc_url(wc_logout_url()); ?>" 
                   data-y="logout-link">
                    <p data-y="logout-text"><?php esc_html_e('تسجيل خروج', 'techno-souq-theme'); ?></p>
                </a>
            </div>
            
            <div class="y-c-Profile-info" id="profile-content-placeholder" data-y="profile-content-placeholder">
                <?php
                /**
                 * My Account content.
                 *
                 * @since 2.6.0
                 */
                do_action('woocommerce_account_content');
                ?>
            </div>
        </div>
        <?php else : ?>
        <!-- Show login/register forms without menu for non-logged in users -->
        <div class="y-c-Profile-info" id="profile-content-placeholder" data-y="profile-content-placeholder">
            <?php
            // Check if user is not logged in and action is register
            if ($is_register_page && 'yes' === get_option('woocommerce_enable_myaccount_registration')) {
                // Load registration form only
                // The shortcode's form-login.php loading is prevented by the filter in functions.php
                wc_get_template('myaccount/form-register.php');
            } else {
                // Load login form only (no register form)
                wc_get_template('myaccount/form-login.php');
            }
            ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Mobile Bottom Navigation -->
<nav class="y-c-mobile-nav" data-y="mobile-nav">
    <a href="<?php echo esc_url($account_url); ?>" 
       id="mobile-account-details-link" 
       data-title="تفاصيل الحساب" 
       data-y="mobile-account-details-link"
       class="y-c-mobile-nav-item <?php echo ($current_endpoint === 'dashboard' || empty($current_endpoint)) ? 'y-c-active' : ''; ?>">
        <i class="fas fa-user"></i>
        <span><?php esc_html_e('الحساب', 'techno-souq-theme'); ?></span>
    </a>
    <a href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>" 
       id="mobile-orders-link" 
       data-title="الطلبات" 
       data-y="mobile-orders-link"
       class="y-c-mobile-nav-item <?php echo ($current_endpoint === 'orders') ? 'y-c-active' : ''; ?>">
        <i class="fas fa-shopping-bag"></i>
        <span><?php esc_html_e('الطلبات', 'techno-souq-theme'); ?></span>
    </a>
    <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address')); ?>" 
       id="mobile-address-link" 
       data-title="العنوان" 
       data-y="mobile-address-link"
       class="y-c-mobile-nav-item <?php echo ($current_endpoint === 'edit-address') ? 'y-c-active' : ''; ?>">
        <i class="fas fa-map-marker-alt"></i>
        <span><?php esc_html_e('العنوان', 'techno-souq-theme'); ?></span>
    </a>
    <a href="<?php echo esc_url(wc_logout_url()); ?>" 
       class="y-c-mobile-nav-item" 
       data-y="mobile-logout-link">
        <i class="fas fa-sign-out-alt" data-y="mobile-logout-icon"></i>
        <span data-y="mobile-logout-text"><?php esc_html_e('خروج', 'techno-souq-theme'); ?></span>
    </a>
</nav>
</nav>