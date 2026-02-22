<?php
/**
 * Template Name: صفحة المفضلة
 * Template for Wishlist page
 *
 * @package TechnoSouqTheme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();

// Enqueue wishlist styles
$theme_version = wp_get_theme()->get('Version');
$techno_souq_path = get_template_directory_uri() . '/techno-souq';
wp_enqueue_style('techno-souq-wishlist', $techno_souq_path . '/templates/favorite/y-favorite.css', array(
    'techno-souq-header',
    'techno-souq-footer',
    'techno-souq-cards',
    'techno-souq-buttons'
), $theme_version);

// Enqueue products script for favorite functionality
wp_enqueue_script('techno-souq-products', $techno_souq_path . '/js/products.js', array('techno-souq-shared-components'), $theme_version, true);
// Note: wishlist.js is NOT enqueued here to avoid conflict with inline script below

// Check if user is logged in
$is_logged_in = is_user_logged_in();

// Localize script for AJAX - use a dummy handle since we're using inline script
wp_enqueue_script('techno-souq-wishlist-inline', '', array('jquery'), $theme_version, true);
wp_localize_script('techno-souq-wishlist-inline', 'technoSouqAjax', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('techno_souq_nonce'),
    'isLoggedIn' => $is_logged_in ? true : false,
));
?>

<main data-y="wishlist-main">
    <section class="y-l-container" data-y="wishlist-container">
        <div class="y-l-shop-section" data-y="wishlist-section">
            <!-- Breadcrumb -->
            <p class="y-c-subtitle" data-y="wishlist-breadcrumb">
                <a href="<?php echo esc_url(home_url('/')); ?>" data-y="breadcrumb-home"><?php esc_html_e('الرئيسية', 'techno-souq-theme'); ?></a>
                <span data-y="breadcrumb-separator"> > </span>
                <?php esc_html_e('المفضلة', 'techno-souq-theme'); ?>
            </p>

            <!-- Page Title -->
            <div class="y-l-header-filter-container" data-y="wishlist-header">
                <h2 class="y-c-section-title" data-y="wishlist-title"><?php esc_html_e('المفضلة', 'techno-souq-theme'); ?></h2>
            </div>

            <!-- Products grid -->
            <div class="y-l-product-grid" id="wishlist-products-container" data-y="wishlist-products-grid">
                <?php
                // Check if user is logged in
                $is_logged_in = is_user_logged_in();
                $account_url = wc_get_page_permalink('myaccount');
                $login_url = add_query_arg('action', 'login', $account_url);
                $register_url = add_query_arg('action', 'register', $account_url);
                
                // Get product IDs from localStorage via JavaScript and pass to PHP
                ?>
                
                <!-- Hidden input to store product IDs from localStorage -->
                <input type="hidden" id="wishlist-product-ids" value="">
                
                <!-- Login Required Message (for non-logged in users) -->
                <div class="y-c-empty-favorites" id="login-required-message" data-y="login-required-message" style="display: none;">
                    <i class="far fa-user y-c-empty-icon" data-y="login-icon"></i>
                    <h3 data-y="login-title"><?php esc_html_e('يرجى تسجيل الدخول', 'techno-souq-theme'); ?></h3>
                    <p data-y="login-description"><?php esc_html_e('يجب عليك تسجيل الدخول أو إنشاء حساب للوصول إلى قائمة المفضلة', 'techno-souq-theme'); ?></p>
                    <div class="y-c-auth-buttons" data-y="auth-buttons" style="display: flex; gap: var(--y-spacing-md); margin-top: var(--y-spacing-lg);">
                        <a href="<?php echo esc_url($login_url); ?>" class="y-c-btn y-c-btn-primary y-c-empty-btn" data-y="login-btn">
                            <?php esc_html_e('تسجيل الدخول', 'techno-souq-theme'); ?>
                        </a>
                        <a href="<?php echo esc_url($register_url); ?>" class="y-c-btn y-c-btn-secondary y-c-empty-btn" data-y="register-btn">
                            <?php esc_html_e('إنشاء حساب', 'techno-souq-theme'); ?>
                        </a>
                    </div>
                </div>
                
                <!-- Empty Favorites Message (for logged in users with no favorites) -->
                <div class="y-c-empty-favorites" id="empty-wishlist-message" data-y="empty-wishlist-message" style="display: none;">
                    <i class="far fa-heart y-c-empty-icon" data-y="empty-icon"></i>
                    <h3 data-y="empty-title"><?php esc_html_e('لا توجد منتجات في المفضلة', 'techno-souq-theme'); ?></h3>
                    <p data-y="empty-description"><?php esc_html_e('يمكنك إضافة المنتجات إلى المفضلة بالضغط على أيقونة القلب في أي منتج', 'techno-souq-theme'); ?></p>
                    <?php
                    // Get shop page URL - use same method as header and footer
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
                    <a href="<?php echo esc_url($shop_url); ?>" class="y-c-btn y-c-btn-primary y-c-empty-btn" data-y="empty-btn">
                        <?php esc_html_e('انظر منتجاتنا', 'techno-souq-theme'); ?>
                    </a>
                </div>
                
                <!-- Products List - Will be populated by JavaScript/PHP -->
                <ul class="products columns-4" id="wishlist-products-list" data-y="wishlist-products-list">
                    <?php
                    // Products will be loaded via JavaScript and displayed here
                    // JavaScript will read localStorage and fetch products via AJAX
                    ?>
                </ul>
            </div>
            
            <script>
            (function() {
                'use strict';
                
                // Get favorites from database via AJAX
                const wishlistList = document.getElementById('wishlist-products-list');
                const emptyMessage = document.getElementById('empty-wishlist-message');
                const loginRequiredMessage = document.getElementById('login-required-message');
                
                // Check if user is logged in
                function isUserLoggedIn() {
                    if (document.body.classList.contains('logged-in')) {
                        return true;
                    }
                    if (typeof technoSouqAjax !== 'undefined' && technoSouqAjax.isLoggedIn === true) {
                        return true;
                    }
                    if (document.getElementById('wpadminbar')) {
                        return true;
                    }
                    return false;
                }
                
                // Get favorites from database via AJAX
                function getFavorites(callback) {
                    if (!isUserLoggedIn()) {
                        if (callback) callback([]);
                        return [];
                    }
                    
                    if (typeof jQuery === 'undefined' || typeof technoSouqAjax === 'undefined') {
                        if (callback) callback([]);
                        return [];
                    }
                    
                    jQuery.ajax({
                        url: technoSouqAjax.ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'techno_souq_get_user_favorites',
                            nonce: technoSouqAjax.nonce
                        },
                        success: function(response) {
                            if (response.success && response.data && Array.isArray(response.data.favorites)) {
                                const favorites = response.data.favorites.map(id => Number(id)).filter(id => id > 0);
                                if (callback) callback(favorites);
                            } else {
                                if (callback) callback([]);
                            }
                        },
                        error: function() {
                            if (callback) callback([]);
                        }
                    });
                }
                
                // Load and display products immediately
                function loadWishlistProducts() {
                    // Hide all messages first
                    if (loginRequiredMessage) {
                        loginRequiredMessage.style.display = 'none';
                    }
                    if (emptyMessage) {
                        emptyMessage.style.display = 'none';
                    }
                    if (wishlistList) {
                        wishlistList.style.display = 'none';
                    }
                    
                    // Check if user is logged in
                    const userLoggedIn = isUserLoggedIn();
                    
                    console.log('Wishlist: User logged in:', userLoggedIn);
                    
                    // If user is NOT logged in, show login message
                    if (!userLoggedIn) {
                        if (loginRequiredMessage) {
                            loginRequiredMessage.style.display = 'flex';
                        }
                        return;
                    }
                    
                    // Get favorites from database
                    getFavorites(function(favorites) {
                        console.log('Wishlist: Favorites from database:', favorites);
                        console.log('Wishlist: Favorites count:', favorites.length);
                        
                        // If user is logged in but has no favorites
                        if (!favorites || favorites.length === 0) {
                            if (emptyMessage) {
                                emptyMessage.style.display = 'flex';
                            }
                            return;
                        }
                        
                        // User has favorites - load products
                        loadProductsFromDatabase(favorites);
                    });
                }
                
                // Load products from database
                function loadProductsFromDatabase(favorites) {
                    // Hide messages
                    if (emptyMessage) {
                        emptyMessage.style.display = 'none';
                    }
                    if (loginRequiredMessage) {
                        loginRequiredMessage.style.display = 'none';
                    }
                    if (wishlistList) {
                        wishlistList.style.display = 'none';
                    }
                    
                    // User is logged in and has favorites - load products
                    if (typeof jQuery !== 'undefined' && typeof technoSouqAjax !== 'undefined') {
                        jQuery.ajax({
                            url: technoSouqAjax.ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'techno_souq_get_wishlist_products',
                                nonce: technoSouqAjax.nonce
                            },
                            success: function(response) {
                                console.log('Wishlist: AJAX success response:', response);
                                console.log('Wishlist: Response success:', response.success);
                                console.log('Wishlist: Response data:', response.data);
                                
                                if (response.success && response.data) {
                                    // Check if we have products HTML
                                    const productsHtml = response.data.products || '';
                                    const productsCount = response.data.count || 0;
                                    
                                    console.log('Wishlist: Products HTML length:', productsHtml.length);
                                    console.log('Wishlist: Products count:', productsCount);
                                    console.log('Wishlist: Debug info:', response.data.debug);
                                    
                                    if (productsHtml && productsHtml.trim().length > 0) {
                                        // We have products to display
                                        if (wishlistList) {
                                            wishlistList.innerHTML = productsHtml;
                                            wishlistList.style.display = 'grid';
                                            console.log('Wishlist: Products displayed successfully');
                                            
                                            // All products in wishlist are favorites - add active class immediately
                                            // Use requestAnimationFrame to ensure DOM is ready, then add active class
                                            requestAnimationFrame(function() {
                                                const favoriteButtons = wishlistList.querySelectorAll('[data-favorite-toggle]');
                                                favoriteButtons.forEach(function(button) {
                                                    button.classList.add('active');
                                                });
                                                
                                                // Prevent initializeFavoriteButtons from removing active class
                                                // by marking these buttons as wishlist buttons
                                                favoriteButtons.forEach(function(button) {
                                                    button.setAttribute('data-wishlist-item', 'true');
                                                });
                                            });
                                            
                                            // Don't call initializeFavoriteButtons() here - it causes flickering
                                            // The event delegation from products.js will handle clicks
                                        }
                                        
                                        // Hide messages
                                        if (emptyMessage) {
                                            emptyMessage.style.display = 'none';
                                        }
                                        if (loginRequiredMessage) {
                                            loginRequiredMessage.style.display = 'none';
                                        }
                                    } else {
                                        // No products HTML returned
                                        console.error('Wishlist: Products HTML is empty');
                                        if (emptyMessage) {
                                            emptyMessage.style.display = 'flex';
                                        }
                                        if (wishlistList) {
                                            wishlistList.style.display = 'none';
                                        }
                                    }
                                } else {
                                    // Response indicates failure
                                    console.error('Wishlist: Response indicates failure:', response);
                                    if (emptyMessage) {
                                        emptyMessage.style.display = 'flex';
                                    }
                                    if (wishlistList) {
                                        wishlistList.style.display = 'none';
                                    }
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Wishlist: AJAX error:', status, error);
                                if (emptyMessage) {
                                    emptyMessage.style.display = 'flex';
                                }
                            }
                        });
                    } else {
                        console.error('Wishlist: jQuery or technoSouqAjax not available');
                        if (emptyMessage) {
                            emptyMessage.style.display = 'flex';
                        }
                    }
                }
                
                // Wait for jQuery and technoSouqAjax to be available
                function waitForDependencies(callback) {
                    if (typeof jQuery !== 'undefined' && typeof technoSouqAjax !== 'undefined') {
                        callback();
                    } else {
                        setTimeout(function() {
                            waitForDependencies(callback);
                        }, 100);
                    }
                }
                
                // Load products when DOM and dependencies are ready
                function initWishlist() {
                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', function() {
                            waitForDependencies(loadWishlistProducts);
                        });
                    } else {
                        waitForDependencies(loadWishlistProducts);
                    }
                }
                
                initWishlist();
                
                // Listen for favorites updates (but don't reload if we're on wishlist page)
                // The event delegation from products.js will handle toggling
                document.addEventListener('favoritesUpdated', function() {
                    // Only reload if a product was removed (not added)
                    // For now, just update the active state of buttons
                    const favoriteButtons = wishlistList ? wishlistList.querySelectorAll('[data-favorite-toggle]') : [];
                    if (favoriteButtons.length > 0 && typeof window.productUtils !== 'undefined' && window.productUtils.getFavorites) {
                        window.productUtils.getFavorites(function(favorites) {
                            favoriteButtons.forEach(function(button) {
                                const productId = button.getAttribute('data-product-id');
                                if (productId) {
                                    const numericId = Number(productId);
                                    if (favorites.includes(numericId)) {
                                        button.classList.add('active');
                                    } else {
                                        button.classList.remove('active');
                                        // If product was removed, reload page to update list
                                        setTimeout(function() {
                                            window.location.reload();
                                        }, 500);
                                    }
                                }
                            });
                        });
                    }
                });
            })();
            </script>
        </div>
    </section>
</main>

<?php
get_footer();
