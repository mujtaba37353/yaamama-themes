/**
 * Wishlist Page Functionality
 * Displays favorite products from localStorage
 *
 * @package TechnoSouqTheme
 */

document.addEventListener('DOMContentLoaded', function() {
    const FAVORITES_STORAGE_KEY = 'technosouq_favorites';
    const wishlistContainer = document.getElementById('wishlist-products-container');
    const wishlistList = document.getElementById('wishlist-products-list');
    const emptyMessage = document.getElementById('empty-wishlist-message');
    const loginRequiredMessage = document.getElementById('login-required-message');
    
    // Check if user is logged in (multiple methods for reliability)
    function isUserLoggedIn() {
        // Method 1: Check body class (WordPress adds 'logged-in' class)
        if (document.body.classList.contains('logged-in')) {
            return true;
        }
        
        // Method 2: Check technoSouqAjax object
        if (typeof technoSouqAjax !== 'undefined' && technoSouqAjax.isLoggedIn === true) {
            return true;
        }
        
        // Method 3: Check for WordPress admin bar (if visible)
        if (document.getElementById('wpadminbar')) {
            return true;
        }
        
        return false;
    }

    // Get favorites from localStorage
    function getFavorites() {
        try {
            const favoritesJson = localStorage.getItem(FAVORITES_STORAGE_KEY);
            if (!favoritesJson) {
                return [];
            }
            const favorites = JSON.parse(favoritesJson);
            // Ensure it's an array and filter out invalid values
            return Array.isArray(favorites) ? favorites.filter(id => Number(id) > 0) : [];
        } catch (e) {
            console.error('Wishlist: Error reading favorites from localStorage:', e);
            return [];
        }
    }

    // Check if products.js is loaded and has the functions we need
    function getProductData(productId) {
        // Use WooCommerce REST API or AJAX to get product data
        // For now, we'll use a simple approach with WooCommerce's built-in functions
        return null; // Will be handled by PHP
    }

    // Render wishlist products
    function renderWishlistProducts() {
        // Hide all messages and list first
        if (loginRequiredMessage) {
            loginRequiredMessage.style.display = 'none';
        }
        if (emptyMessage) {
            emptyMessage.style.display = 'none';
        }
        if (wishlistList) {
            wishlistList.style.display = 'none';
        }
        
        // Step 1: Check if user is logged in
        const userLoggedIn = isUserLoggedIn();
        const favorites = getFavorites();
        
        console.log('=== Wishlist Debug ===');
        console.log('Is logged in:', userLoggedIn);
        console.log('Body classes:', document.body.className);
        console.log('technoSouqAjax:', typeof technoSouqAjax !== 'undefined' ? technoSouqAjax : 'undefined');
        console.log('Favorites from localStorage:', favorites);
        console.log('Favorites count:', favorites.length);
        console.log('Raw localStorage value:', localStorage.getItem(FAVORITES_STORAGE_KEY));
        console.log('====================');
        
        // Step 2: If user is NOT logged in, show login/register message
        if (!userLoggedIn) {
            console.log('Wishlist: User NOT logged in - showing login message');
            if (loginRequiredMessage) {
                loginRequiredMessage.style.display = 'flex';
            }
            if (emptyMessage) {
                emptyMessage.style.display = 'none';
            }
            if (wishlistList) {
                wishlistList.style.display = 'none';
            }
            return;
        }
        
        // Step 3: User IS logged in, check if they have favorites
        // Convert all favorites to numbers for comparison
        const numericFavorites = favorites.map(id => Number(id)).filter(id => id > 0);
        console.log('Wishlist: Numeric favorites:', numericFavorites);
        
        if (!numericFavorites || numericFavorites.length === 0) {
            console.log('Wishlist: User logged in but NO favorites - showing empty message');
            if (emptyMessage) {
                emptyMessage.style.display = 'flex';
            }
            if (loginRequiredMessage) {
                loginRequiredMessage.style.display = 'none';
            }
            if (wishlistList) {
                wishlistList.style.display = 'none';
            }
            return;
        }
        
        // Step 4: User IS logged in AND has favorites - show products
        console.log('Wishlist: User logged in with', numericFavorites.length, 'favorites - loading products');

        // Hide empty and login messages
        if (emptyMessage) {
            emptyMessage.style.display = 'none';
        }
        if (loginRequiredMessage) {
            loginRequiredMessage.style.display = 'none';
        }
        
        // Show products list
        if (wishlistList) {
            wishlistList.style.display = 'grid';
        }

        // Clear existing products
        if (wishlistList) {
            wishlistList.innerHTML = '';
        }

        // Fetch products via AJAX
        if (typeof jQuery === 'undefined') {
            console.error('Wishlist: jQuery is not available');
            if (emptyMessage) {
                emptyMessage.style.display = 'flex';
            }
            if (wishlistList) {
                wishlistList.style.display = 'none';
            }
            return;
        }
        
        if (typeof technoSouqAjax === 'undefined') {
            console.error('Wishlist: technoSouqAjax is not available');
            if (emptyMessage) {
                emptyMessage.style.display = 'flex';
            }
            if (wishlistList) {
                wishlistList.style.display = 'none';
            }
            return;
        }
        
        // Use numeric favorites for AJAX request
        console.log('Wishlist: Sending AJAX request with product IDs:', numericFavorites);
        jQuery.ajax({
            url: technoSouqAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'techno_souq_get_wishlist_products',
                product_ids: numericFavorites,
                nonce: technoSouqAjax.nonce
            },
            success: function(response) {
                console.log('Wishlist: AJAX success response:', response);
                if (response.success && response.data && response.data.products) {
                    const productsHtml = response.data.products;
                    console.log('Wishlist: Products HTML length:', productsHtml.length);
                    console.log('Wishlist: Products HTML preview:', productsHtml.substring(0, 200));
                    
                    if (wishlistList && productsHtml.trim().length > 0) {
                        wishlistList.innerHTML = productsHtml;
                        console.log('Wishlist: Products inserted into DOM successfully');
                        
                        // Wait a bit for DOM to update, then initialize favorite buttons
                        setTimeout(function() {
                            // Re-initialize favorite buttons after products are loaded
                            if (typeof initializeFavoriteButtons === 'function') {
                                initializeFavoriteButtons();
                            } else if (window.productUtils && typeof window.productUtils.initializeFavoriteButtons === 'function') {
                                window.productUtils.initializeFavoriteButtons();
                            } else {
                                // Fallback: manually set active class on favorite buttons
                                const favoriteButtons = wishlistList.querySelectorAll('[data-favorite-toggle]');
                                const currentFavorites = getFavorites();
                                favoriteButtons.forEach(button => {
                                    const productId = button.getAttribute('data-product-id') || button.closest('.y-c-card')?.dataset.productId;
                                    if (productId && currentFavorites.includes(Number(productId))) {
                                        button.classList.add('active');
                                    }
                                });
                            }
                        }, 100);
                        
                        // Trigger event to update favorite buttons
                        document.dispatchEvent(new CustomEvent('favoritesUpdated'));
                    } else {
                        console.error('Wishlist: Products HTML is empty or wishlistList not found');
                        if (emptyMessage) {
                            emptyMessage.style.display = 'flex';
                        }
                        if (wishlistList) {
                            wishlistList.style.display = 'none';
                        }
                    }
                } else {
                    console.error('Wishlist: AJAX response indicates failure:', response);
                    // Fallback: show empty message
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
                console.error('Wishlist: XHR response:', xhr.responseText);
                // Show empty message on error
                if (emptyMessage) {
                    emptyMessage.style.display = 'flex';
                }
                if (wishlistList) {
                    wishlistList.style.display = 'none';
                }
            }
        });
    }

    // Listen for storage changes (when favorites are updated on other pages)
    window.addEventListener('storage', function(e) {
        if (e.key === FAVORITES_STORAGE_KEY) {
            renderWishlistProducts();
        }
    });

    // Custom event for when favorites are updated
    document.addEventListener('favoritesUpdated', function() {
        renderWishlistProducts();
    });

    // Initial render
    renderWishlistProducts();
});
