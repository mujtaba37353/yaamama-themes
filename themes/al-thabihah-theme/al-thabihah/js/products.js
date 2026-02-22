// Al Thabihah/js/products.js

// New product list based on the "Al Thabihah" theme
const products = [
    { id: 1, name: "ذبيحة نعيمي 8: 10 كجم", price: 750.00, image: "/assets/product.jpg", category: "naemi" },
    { id: 2, name: "تيس كشميري 7: 9 كجم", price: 650.00, image: "/assets/product2.png", category: "tays" },
    { id: 3, name: "عجل بلدي (ربع)", price: 1200.00, image: "/assets/product2.png", category: "ejel" },
    { id: 4, name: "قطعيات لحم بقر (1 كجم)", price: 45.00, image: "/assets/product.jpg", category: "cuts" },
    { id: 5, name: " نعيمي (10-12 كجم)", price: 700.00, image: "/assets/product2.png", category: "offers", offer: true, oldPrice: 750.00 },
    { id: 6, name: "ذبيحة نعيمي 10: 12 كجم", price: 850.00, image: "/assets/product.jpg", category: "naemi" },
    { id: 7, name: "تيس بلدي 6: 8 كجم", price: 600.00, image: "/assets/product.jpg", category: "tays" },
    { id: 8, name: "قطع لحم عجل (1 كجم)", price: 55.00, image: "/assets/product2.png", category: "cuts" },
    { id: 9, name: "ذبيحة نعيمي 12: 14 كجم", price: 950.00, image: "/assets/product.jpg", category: "naemi" },
    { id: 10, name: "تيس كشميري 9: 11 كجم", price: 720.00, image: "/assets/product.jpg", category: "tays" },
    { id: 11, name: "عجل بلدي (نصف)", price: 2300.00, image: "/assets/product2.png", category: "ejel" },
    { id: 12, name: "قطع لحم غنم (1 كجم)", price: 50.00, image: "/assets/product.jpg", category: "cuts" },
    { id: 13, name: " تيس بلدي", price: 550.00, image: "/assets/product.jpg", category: "offers", offer: true, oldPrice: 600.00 },
    { id: 14, name: "ذبيحة نعيمي 14: 16 كجم", price: 1050.00, image: "/assets/product2.png", category: "naemi" },
    { id: 15, name: "تيس كشميري 11: 13 كجم", price: 780.00, image: "/assets/product.jpg", category: "tays" },
    { id: 16, name: "عجل بلدي (كامل)", price: 4500.00, image: "/assets/product.jpg", category: "ejel" },

    // Additional Naemi products
    { id: 17, name: "ذبيحة نعيمي 16: 18 كجم", price: 1200.00, image: "/assets/product2.png", category: "naemi" },
    { id: 18, name: "ذبيحة نعيمي 20: 22 كجم", price: 1450.00, image: "/assets/product.jpg", category: "naemi" },
    { id: 19, name: " نعيمي ممتاز (18-20 كجم)", price: 1350.00, image: "/assets/product2.png", category: "offers", offer: true, oldPrice: 1500.00 },
    { id: 20, name: "ذبيحة نعيمي صغير 6: 8 كجم", price: 650.00, image: "/assets/product.jpg", category: "naemi" },

    // Additional Tays products
    { id: 21, name: "تيس كشميري 13: 15 كجم", price: 850.00, image: "/assets/product.jpg", category: "tays" },
    { id: 22, name: "تيس بلدي 8: 10 كجم", price: 700.00, image: "/assets/product.jpg", category: "tays" },
    { id: 23, name: " تيس كشميري كبير", price: 800.00, image: "/assets/product2.png", category: "offers", offer: true, oldPrice: 900.00 },
    { id: 24, name: "تيس حري 10: 12 كجم", price: 750.00, image: "/assets/product.jpg", category: "tays" },

    // Additional Ejel products
    { id: 25, name: "عجل صغير (8 كجم)", price: 800.00, image: "/assets/product2.png", category: "ejel" },
    { id: 26, name: " عجل بلدي (ربع ونصف)", price: 3200.00, image: "/assets/product2.png", category: "offers", offer: true, oldPrice: 3500.00 },
    { id: 27, name: "عجل فريزي (ربع)", price: 1400.00, image: "/assets/product2.png", category: "ejel" },
    { id: 28, name: "عجل بلدي مميز (نصف)", price: 2500.00, image: "/assets/product2.png", category: "ejel" },

    // Additional Cuts products
    { id: 29, name: "قطع لحم نعيمي (1 كجم)", price: 60.00, image: "/assets/product.jpg", category: "cuts" },
    { id: 30, name: "قطع لحم تيس (1 كجم)", price: 55.00, image: "/assets/product.jpg", category: "cuts" },
    { id: 31, name: " مشكل لحوم (2 كجم)", price: 100.00, image: "/assets/product.jpg", category: "offers", offer: true, oldPrice: 120.00 },
    { id: 32, name: "قطع لحم عجل ممتاز (1 كجم)", price: 65.00, image: "/assets/product.jpg", category: "cuts" },
    { id: 33, name: "لحم فرم مخلوط (1 كجم)", price: 40.00, image: "/assets/product.jpg", category: "cuts" },

    // Seasonal and special offers
    { id: 34, name: " باقة العائلة الكبيرة", price: 2800.00, image: "/assets/product2.png", category: "offers", offer: true, oldPrice: 3200.00 },
    { id: 35, name: " ذبيحة + قطعيات", price: 850.00, image: "/assets/product2.png", category: "offers", offer: true, oldPrice: 950.00 },
    { id: 36, name: "تيس مدفون جاهز (8 كجم)", price: 1200.00, image: "/assets/product.jpg", category: "naemi" },
    { id: 37, name: " لحوم مشوية جاهزة", price: 180.00, image: "/assets/product2.png", category: "offers", offer: true, oldPrice: 220.00 },
    { id: 38, name: "ذبيحة نعيمي محشية (12 كجم)", price: 1100.00, image: "/assets/product.jpg", category: "naemi" },
    { id: 39, name: "عجل هولندي (ربع)", price: 1600.00, image: "/assets/product.jpg", category: "ejel" },
    { id: 40, name: " مجموعة الشواء المميزة", price: 320.00, image: "/assets/product2.png", category: "offers", offer: true, oldPrice: 380.00 }
];


/**
 * ----------------------------------------------------------------
 * Product Card Generator
 * ----------------------------------------------------------------
 * Updated to match the new card design (image_564445.png)
 * - Removed wishlist button
 * - Added icon to "Add to Cart" button
 * - Simplified price display
 */
function createProductCard(product) {
    // Truncate product name to max 20 characters and add ellipsis if needed
    const truncatedName = product.name.length > 20
        ? product.name.substring(0, 20) + '...'
        : product.name;

    // Check if product is already in favorites
    const isProductFavorite = isFavorite(product.id);
    const heartIcon = isProductFavorite ? 'fas fa-heart' : 'far fa-heart';
    const favoriteClass = isProductFavorite ? 'y-c-favorite-btn active' : 'y-c-favorite-btn';

    // Build price HTML with offer support
    let priceHTML = '';
    if (product.offer && product.oldPrice) {
        // This is the style for offer products (if any)
        priceHTML = `
            <div class="y-c-product-price" data-y="product-price-container-${product.id}">

                <span class="y-c-old-price" data-y="product-old-price-${product.id}">${product.oldPrice.toFixed(0)}</span>
                <img src="/assets/coin-sale.png"  class="y-c-coin-icon">

                <span class="y-c-price-amount" data-y="product-price-amount-${product.id}">${product.price.toFixed(0)}</span>
                <img src="/assets/coin-red.png" class="y-c-coin-icon">

                <span class="y-c-discount-text" data-y="product-discount-text-${product.id}">خصم 17%</span>

            </div>
        `;
    } else {
        // Standard price display
        priceHTML = `
            <div class="y-c-product-price" data-y="product-price-container-${product.id}">
                <span class="y-c-price-amount" data-y="product-price-amount-${product.id}">${product.price.toFixed(0)}</span>
                <img src="/assets/coin.png" class="y-c-coin-icon">
            </div>
        `;
    }

    // New card HTML structure
    return `
    <li class="y-c-product-card" data-y="product-card-${product.id}">
            <button class="${favoriteClass}" data-product-id="${product.id}" data-y="product-favorite-${product.id}">
                    <i class="${heartIcon}" data-y="favorite-icon-${product.id}"></i>
            </button>
            
        <a href="/templates/single-product/layout.html?id=${product.id}" class="y-c-card-link" data-y="product-link-${product.id}">
            <div class="y-c-product-image-container" data-y="product-image-container-${product.id}">
              
                <img src="${product.image}" alt="${product.name}" class="y-c-product-image" loading="lazy" data-y="product-image-${product.id}">
                
            </div>
        </a>

        <div class="y-c-product-info" data-y="product-info-${product.id}">
            <h3 class="y-c-product-title" title="${product.name}" data-y="product-title-${product.id}">${truncatedName}</h3>
            
            ${priceHTML}

            <button class="y-c-outline-btn y-c-add-to-cart" data-product-id="${product.id}" data-y="product-add-to-cart-${product.id}">
               
                اضف للسلة
                 <i class="fas fa-shopping-cart" data-y="cart-icon-${product.id}"></i>
            </button>
        </div>
    </li>
    `;
}


/**
 * ----------------------------------------------------------------
 * Favorites Management
 * ----------------------------------------------------------------
 */
let favorites = JSON.parse(localStorage.getItem('favorites')) || [];

function isFavorite(productId) {
    return favorites.includes(productId);
}

function addToFavorites(productId) {
    if (!isFavorite(productId)) {
        favorites.push(productId);
        localStorage.setItem('favorites', JSON.stringify(favorites));
        return true;
    }
    return false;
}

function removeFromFavorites(productId) {
    const index = favorites.indexOf(productId);
    if (index > -1) {
        favorites.splice(index, 1);
        localStorage.setItem('favorites', JSON.stringify(favorites));
        return true;
    }
    return false;
}

function toggleFavorite(productId) {
    if (isFavorite(productId)) {
        removeFromFavorites(productId);
        return false;
    } else {
        addToFavorites(productId);
        return true;
    }
}

/**
 * ----------------------------------------------------------------
 * Cart & Product Utility Functions
 * (Retained from original file)
 * ----------------------------------------------------------------
 */

// Function to get products by category
function getProductsByCategory(category) {
    if (category === 'all' || !category) {
        return products;
    }
    return products.filter(product => product.category === category);
}

// Function to get product by ID
function getProductById(id) {
    return products.find(product => product.id == id);
}

// Simple cart functionality
let cart = JSON.parse(localStorage.getItem('cart')) || [];

function updateCartBadge() {
    const badge = document.querySelector('.y-c-cart-badge');
    if (badge) {
        const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        badge.textContent = totalItems;
        badge.style.display = totalItems > 0 ? 'flex' : 'none';
    }
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `y-c-notification y-c-notification--${type}`;
    notification.setAttribute('data-y', `notification-${type}`);
    notification.innerHTML = `
        <i class="fa-solid fa-check-circle" data-y="notification-icon"></i>
        <span data-y="notification-message">${message}</span>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Show and hide animation
    setTimeout(() => notification.classList.add('show'), 100);
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentElement) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Initialize cart badge and add to cart buttons on page load
document.addEventListener('DOMContentLoaded', function () {
    updateCartBadge();
    initializeAddToCartButtons();
    initializeFavoriteButtons();
});

// Function to initialize add to cart button functionality
function initializeAddToCartButtons() {
    // Use event delegation to handle dynamically added buttons
    document.addEventListener('click', function (event) {
        const button = event.target.closest('.y-c-add-to-cart');
        if (button) {
            event.preventDefault();

            const productId = parseInt(button.dataset.productId);
            if (productId) {
                // Add loading state
                button.disabled = true;
                button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

                // Simulate slight delay for better UX
                setTimeout(() => {
                    addToCart(productId);

                    // Reset button state
                    button.disabled = false;

                    // Temporary success state
                    button.innerHTML = '<i class="fa-solid fa-check"></i> تم';
                    button.classList.add('success');

                    setTimeout(() => {
                        button.innerHTML = '<i class="fas fa-shopping-cart"></i> اضف للسلة';
                        button.classList.remove('success');
                    }, 1500);

                }, 300);
            }
        }
    });
}

// Function to initialize favorite button functionality
function initializeFavoriteButtons() {
    // Use event delegation to handle dynamically added buttons
    document.addEventListener('click', function (event) {
        const button = event.target.closest('.y-c-favorite-btn');
        if (button) {
            event.preventDefault();
            event.stopPropagation(); // Prevent link navigation

            const productId = parseInt(button.dataset.productId);
            if (productId) {
                const icon = button.querySelector('i');
                const product = getProductById(productId);

                // Toggle favorite status
                const isNowFavorite = toggleFavorite(productId);

                // Update icon
                if (isNowFavorite) {
                    icon.className = 'fas fa-heart';
                    button.classList.add('active');
                } else {
                    icon.className = 'far fa-heart';
                    button.classList.remove('active');
                }

                // Add animation
                button.classList.add('animate-bounce');
                setTimeout(() => button.classList.remove('animate-bounce'), 600);

                // Trigger custom event
                document.dispatchEvent(new CustomEvent('favoritesUpdated', {
                    detail: { productId, isFavorite: isNowFavorite, favorites }
                }));
            }
        }
    });
}

// Enhanced addToCart function with quantity support
function addToCart(productId, quantity = 1) {
    const product = getProductById(productId);
    if (product) {
        const existingItem = cart.find(item => item.id === productId);
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            // Add a *copy* of the product to avoid mutating the original array
            cart.push({ ...product, quantity: quantity });
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartBadge();

        // Show success message
        const message = quantity > 1
            ? `تم إضافة ${quantity} قطع من ${product.name} للسلة بنجاح!`
            : `تم إضافة ${product.name} للسلة بنجاح!`;
        showNotification(message, 'success');

        // Trigger custom event for other components
        document.dispatchEvent(new CustomEvent('cartUpdated', {
            detail: { productId, quantity, cart }
        }));

        return true;
    }
    return false;
}

// Function to remove from cart
function removeFromCart(productId) {
    const itemIndex = cart.findIndex(item => item.id === productId);
    if (itemIndex !== -1) {
        const removedItem = cart[itemIndex];
        cart.splice(itemIndex, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartBadge();

        showNotification(`تم حذف ${removedItem.name} من السلة`, 'info');

        // Trigger custom event
        document.dispatchEvent(new CustomEvent('cartUpdated', {
            detail: { productId, removed: true, cart }
        }));

        return true;
    }
    return false;
}

// Function to update item quantity in cart
function updateCartQuantity(productId, newQuantity) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        if (newQuantity <= 0) {
            return removeFromCart(productId);
        }

        item.quantity = newQuantity;
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartBadge();

        // Trigger custom event
        document.dispatchEvent(new CustomEvent('cartUpdated', {
            detail: { productId, quantity: newQuantity, cart }
        }));

        return true;
    }
    return false;
}

// Function to clear entire cart
function clearCart() {
    cart = [];
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartBadge();
    showNotification('تم إفراغ السلة', 'info');

    // Trigger custom event
    document.dispatchEvent(new CustomEvent('cartCleared', { detail: { cart } }));
}

// Function to get cart total
function getCartTotal() {
    return cart.reduce((total, item) => total + (item.price * item.quantity), 0);
}

// Function to get cart items count
function getCartItemsCount() {
    return cart.reduce((total, item) => total + item.quantity, 0);
}

// Enhanced updateCartBadge with animation
function updateCartBadge() {
    const badge = document.querySelector('.y-c-cart-badge');
    if (badge) {
        const totalItems = getCartItemsCount();
        const currentCount = parseInt(badge.textContent) || 0;

        badge.textContent = totalItems;
        badge.style.display = totalItems > 0 ? 'flex' : 'none';

        // Add animation for new items
        if (totalItems > currentCount) {
            badge.classList.add('animate-bounce');
            setTimeout(() => badge.classList.remove('animate-bounce'), 600);
        }
    }
}

// Show More functionality for offers and other pages
let showMoreConfig = {
    itemsPerLoad: 8,
    currentlyShown: 0,
    totalItems: 0,
    filteredProducts: [],
    containerSelector: '#products-container',
    buttonSelector: '#show-more-btn'
};

// Initialize show more functionality
function initializeShowMore(products, containerSelector = '#products-container', buttonSelector = '#show-more-btn') {
    showMoreConfig.filteredProducts = products;
    showMoreConfig.totalItems = products.length;
    showMoreConfig.currentlyShown = 0;
    showMoreConfig.containerSelector = containerSelector;
    showMoreConfig.buttonSelector = buttonSelector;

    // Load initial products
    loadMoreProducts();

    // Update button visibility
    updateShowMoreButton();
}

// Load more products function
function loadMoreProducts() {
    const container = document.querySelector(showMoreConfig.containerSelector);
    if (!container) return;

    const products = showMoreConfig.filteredProducts;

    // Check if there are any products on first load
    if (products.length === 0 && showMoreConfig.currentlyShown === 0) {
        container.innerHTML = `
            <div style="grid-column: 1/-1; text-align: center; padding: 4rem 2rem;">
                <i class="fas fa-tag" style="font-size: 4rem; color: var(--y-color-error); margin-bottom: 1rem;"></i>
                <h3 style="margin-bottom: 0.5rem; font-size: 1.5rem;">لا توجد منتجات حالياً</h3>
                <p style="color: var(--y-color-third-text);">تابعنا للحصول على أحدث المنتجات</p>
            </div>
        `;
        return;
    }

    // Calculate start and end indices for the next batch
    const startIndex = showMoreConfig.currentlyShown;
    const endIndex = Math.min(startIndex + showMoreConfig.itemsPerLoad, showMoreConfig.totalItems);

    // Display products for this batch
    for (let i = startIndex; i < endIndex; i++) {
        const product = products[i];
        if (product) {
            container.innerHTML += createProductCard(product);
        }
    }

    // Update currently shown count
    showMoreConfig.currentlyShown = endIndex;

    // Update show more button visibility
    updateShowMoreButton();

    // Re-initialize favorite buttons if available
    if (window.favoriteUtils && window.favoriteUtils.initializeFavoriteButtons) {
        window.favoriteUtils.initializeFavoriteButtons();
    }
}

// Update show more button visibility
function updateShowMoreButton() {
    const showMoreBtn = document.querySelector(showMoreConfig.buttonSelector);
    if (!showMoreBtn) return;

    // Hide button if all products are shown
    if (showMoreConfig.currentlyShown >= showMoreConfig.totalItems) {
        showMoreBtn.style.display = 'none';
    } else {
        showMoreBtn.style.display = 'flex';
    }
}

// Create enhanced product utilities object for use by other scripts
window.productUtils = {
    // Data
    products: products,
    get cart() { return getCart(); }, // Use a getter to ensure it's up-to-date
    get favorites() { return getFavorites(); }, // Use a getter to ensure it's up-to-date

    // Product functions
    createProductCard: createProductCard,
    getProductsByCategory: getProductsByCategory,
    getProductById: getProductById,

    // Cart functions
    addToCart: addToCart,
    removeFromCart: removeFromCart,
    updateCartQuantity: updateCartQuantity,
    clearCart: clearCart,
    getCartTotal: getCartTotal,
    getCartItemsCount: getCartItemsCount,
    updateCartBadge: updateCartBadge,

    // Favorite functions
    addToFavorites: addToFavorites,
    removeFromFavorites: removeFromFavorites,
    toggleFavorite: toggleFavorite,
    isFavorite: isFavorite,
    initializeFavoriteButtons: initializeFavoriteButtons,

    // Show More functions
    initializeShowMore: initializeShowMore,
    loadMoreProducts: loadMoreProducts,
    updateShowMoreButton: updateShowMoreButton,

    // Utility functions
    showNotification: showNotification,
    initializeAddToCartButtons: initializeAddToCartButtons
};

// Make loadMoreProducts function globally accessible
window.loadMoreProducts = loadMoreProducts;

// Helper function to get cart from localStorage
function getCart() {
    const cart = localStorage.getItem('cart');
    return cart ? JSON.parse(cart) : [];
}

// Helper function to get favorites from localStorage
function getFavorites() {
    const favs = localStorage.getItem('favorites');
    return favs ? JSON.parse(favs) : [];
}

// Update the global cart and favorites variables on load
cart = getCart();
favorites = getFavorites();