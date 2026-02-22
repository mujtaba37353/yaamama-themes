document.addEventListener('DOMContentLoaded', () => {
    // Favorite functionality - Now using database via AJAX
    
    // Cache for user favorites (loaded from database)
    let userFavoritesCache = [];
    let favoritesLoaded = false;

    // Check if user is logged in
    function isUserLoggedIn() {
        if (typeof technoSouqAjax !== 'undefined' && technoSouqAjax.isLoggedIn === true) {
            return true;
        }
        if (document.body.classList.contains('logged-in')) {
            return true;
        }
        return false;
    }

    // Show notification to user
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `y-c-notification y-c-notification--${type}`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: ${type === 'error' ? '#f44336' : '#4CAF50'};
            color: white;
            padding: 15px 25px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 10000;
            font-size: 16px;
            max-width: 90%;
            text-align: center;
            animation: slideDown 0.3s ease;
        `;
        notification.textContent = message;
        
        // Add animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateX(-50%) translateY(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateX(-50%) translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
        
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideDown 0.3s ease reverse';
            setTimeout(() => {
                notification.remove();
                style.remove();
            }, 300);
        }, 3000);
    }

    // Get favorites from database via AJAX
    function getFavorites(callback) {
        // Return cached favorites if already loaded
        if (favoritesLoaded && callback) {
            callback(userFavoritesCache);
            return userFavoritesCache;
        }
        
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
                    userFavoritesCache = response.data.favorites.map(id => Number(id));
                    favoritesLoaded = true;
                    if (callback) callback(userFavoritesCache);
                } else {
                    userFavoritesCache = [];
                    favoritesLoaded = true;
                    if (callback) callback([]);
                }
            },
            error: function() {
                userFavoritesCache = [];
                favoritesLoaded = true;
                if (callback) callback([]);
            }
        });
        
        return userFavoritesCache;
    }

    // Check if a product is in favorites (synchronous check from cache)
    function isFavoriteProduct(productId) {
        return userFavoritesCache.includes(Number(productId));
    }

    // Toggle favorite status for a product via AJAX
    function toggleFavorite(productId, callback) {
        // Check if user is logged in
        if (!isUserLoggedIn()) {
            const loginUrl = typeof wc_cart_params !== 'undefined' && wc_cart_params.wc_ajax_url 
                ? wc_cart_params.wc_ajax_url.replace('?wc-ajax=add_to_cart', '/my-account/?action=login')
                : '/my-account/?action=login';
            showNotification('يرجى تسجيل الدخول لإضافة المنتجات إلى المفضلة', 'error');
            // Optionally redirect to login page after a delay
            setTimeout(() => {
                if (confirm('هل تريد الانتقال إلى صفحة تسجيل الدخول؟')) {
                    window.location.href = loginUrl;
                }
            }, 2000);
            if (callback) callback(false);
            return false;
        }
        
        if (typeof jQuery === 'undefined' || typeof technoSouqAjax === 'undefined') {
            showNotification('حدث خطأ. يرجى تحديث الصفحة والمحاولة مرة أخرى.', 'error');
            if (callback) callback(false);
            return false;
        }
        
        const numericId = Number(productId);
        const wasFavorite = isFavoriteProduct(numericId);
        
        // Optimistically update UI
        if (!wasFavorite) {
            userFavoritesCache.push(numericId);
        } else {
            userFavoritesCache = userFavoritesCache.filter(id => id !== numericId);
        }
        
        // Send AJAX request
        jQuery.ajax({
            url: technoSouqAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'techno_souq_toggle_favorite',
                product_id: numericId,
                nonce: technoSouqAjax.nonce
            },
            success: function(response) {
                if (response.success && response.data) {
                    // Update cache
                    if (response.data.is_favorite) {
                        if (!userFavoritesCache.includes(numericId)) {
                            userFavoritesCache.push(numericId);
                        }
                    } else {
                        userFavoritesCache = userFavoritesCache.filter(id => id !== numericId);
                    }
                    
                    // Show success message
                    showNotification(response.data.message, 'success');
                    
                    if (callback) callback(response.data.is_favorite);
                    
                    // Dispatch event to update wishlist page if open
                    document.dispatchEvent(new CustomEvent('favoritesUpdated'));
                } else {
                    // Revert optimistic update
                    if (wasFavorite) {
                        userFavoritesCache.push(numericId);
                    } else {
                        userFavoritesCache = userFavoritesCache.filter(id => id !== numericId);
                    }
                    showNotification(response.data && response.data.message 
                        ? response.data.message 
                        : 'حدث خطأ أثناء تحديث المفضلة', 'error');
                    if (callback) callback(wasFavorite);
                }
            },
            error: function(xhr, status, error) {
                // Revert optimistic update
                if (wasFavorite) {
                    userFavoritesCache.push(numericId);
                } else {
                    userFavoritesCache = userFavoritesCache.filter(id => id !== numericId);
                }
                showNotification('حدث خطأ أثناء تحديث المفضلة. يرجى المحاولة مرة أخرى.', 'error');
                if (callback) callback(wasFavorite);
            }
        });
        
        return !wasFavorite; // Return optimistic result
    }

    // Base product data
    const products = [
        {
            id: 1,
            name: 'غسالة ملابس أوتوماتيك',
            price: '1200 ر.س',
            oldPrice: '1500 ر.س',
            image: '/assets/wash.png',
            category: 'appliances'
        },
        {
            id: 2,
            name: 'شاشة تلفزيون ذكية 55 بوصة',
            price: '2500 ر.س',
            oldPrice: '3000 ر.س',
            image: '/assets/tv.png',
            category: 'electronics'
        },
        {
            id: 3,
            name: 'مكيف هواء سبليت',
            price: '3000 ر.س',
            oldPrice: '3500 ر.س',
            image: '/assets/conditioner.png',
            category: 'appliances'
        },
        {
            id: 4,
            name: 'مكنسة كهربائية لاسلكية',
            price: '800 ر.س',
            oldPrice: '1000 ر.س',
            image: '/assets/vacup_cleaner.png',
            category: 'appliances'
        },
        {
            id: 5,
            name: 'غسالة ملابس تعبئة علوية',
            price: '1500 ر.س',
            oldPrice: '1800 ر.س',
            image: '/assets/wash.png',
            category: 'appliances'
        },
        {
            id: 6,
            name: 'تلفزيون 4K فائق الدقة',
            price: '2800 ر.س',
            oldPrice: '3200 ر.س',
            image: '/assets/tv.png',
            category: 'electronics'
        },
        {
            id: 7,
            name: 'مكيف شباك قوي',
            price: '1800 ر.س',
            image: '/assets/conditioner.png',
            category: 'appliances'
        },
        {
            id: 8,
            name: 'مكنسة روبوت ذكية',
            price: '1200 ر.س',
            image: '/assets/vacup_cleaner.png',
            category: 'appliances'
        },
        {
            id: 9,
            name: 'لابتوب محمول',
            price: '4500 ر.س',
            oldPrice: '5000 ر.س',
            image: '/assets/tv.png',
            category: 'electronics'
        },
        {
            id: 10,
            name: 'هاتف ذكي',
            price: '2000 ر.س',
            oldPrice: '2500 ر.س',
            image: '/assets/tv.png',
            category: 'electronics'
        },
        {
            id: 11,
            name: 'ثلاجة كبيرة',
            price: '3500 ر.س',
            image: '/assets/tv.png',
            category: 'appliances'
        },
        {
            id: 12,
            name: 'فرن ميكروويف',
            price: '600 ر.س',
            oldPrice: '750 ر.س',
            image: '/assets/tv.png',
            category: 'appliances'
        }
    ];

    // Function to create a product card
    function createProductCard(product) {
        const isFavorite = isFavoriteProduct(product.id);

        // Calculate discount percentage if oldPrice exists
        let discountPercentage = '';
        if (product.oldPrice) {
            const newPrice = parseFloat(product.price.replace(/[^\d.]/g, ''));
            const oldPrice = parseFloat(product.oldPrice.replace(/[^\d.]/g, ''));
            if (oldPrice > newPrice) {
                discountPercentage = Math.round((oldPrice - newPrice) / oldPrice * 100);
            }
        }

        return `
        <li class="y-c-card" data-product-id="${product.id}" data-y="product-card">
            <div class="y-c-card__top-actions" data-y="card-top-actions">
                <a href="#" class="y-c-card__favorite ${isFavorite ? 'active' : ''}" data-favorite-toggle data-y="favorite-toggle">
                    <i class="fas fa-heart" data-y="favorite-icon"></i>
                </a>
                ${discountPercentage ? `<div class="y-c-card__discount" data-y="discount-badge"> خصم ${discountPercentage}%</div>` : ''}
            </div>

            <a href="/templates/product-single/layout.html?id=${product.id}" class="y-c-card__link" data-y="product-link">
                <div class="y-c-card__image-container" data-y="image-container">
                    <img src="${product.image}" alt="${product.name}" class="y-c-card__image" data-y="product-image">
                </div>
                <div class="y-c-card__body" data-y="card-body">
                    <h3 class="y-c-card__category" data-y="product-category">${product.category}</h3>

                    <h4 class="y-c-card__title" data-y="product-title">${product.name.length > 23 ? product.name.substring(0, 23) + '...' : product.name}</h4>
                    
                    <div class="y-c-card__footer" data-y="card-footer">
                        <div class="y-u-flex y-u-flex-column y-u-align-start" data-y="price-container"> 
                            <p class="y-c-card__price" data-y="product-price">
                                ${product.price.replace('ر.س', '')}
                                <img src="/assets/coin.png" alt="SAR" class="y-c-price-icon" data-y="price-icon">
                            </p> 
                            <p class="y-c-card__old-price" data-y="old-price">
                                ${product.oldPrice ? product.oldPrice.replace('ر.س', '') : ''}
                                ${product.oldPrice ? '<img src="/assets/coin-through.png" alt="SAR" class="y-c-price-icon-through" data-y="old-price-icon">' : ''}
                            </p>
                        </div>
                    </div>
                </div>
            </a>
            <div class="y-c-card__actions" data-y="card-actions">
                <a href="#" class="y-c-card__btn" data-cart-btn data-y="cart-btn">
                    <i class="fas fa-shopping-cart" data-y="cart-icon"></i>
                </a>
            </div>
        </li>
    `;
    }

    // Enhanced function to generate product sections
    function generateProductSection(containerId, productsData, limit = productsData.length, filterOptions = null) {
        const productContainer = document.getElementById(containerId);

        if (!productContainer) {
            console.warn(`Product container with ID '${containerId}' not found`);
            return;
        }

        // Clear any existing content
        productContainer.innerHTML = '';

        // Create UL element for the product list
        const productList = document.createElement('ul');
        productList.className = 'y-c-product-list';

        // Preserve any grid settings from the container
        const containerStyle = window.getComputedStyle(productContainer);
        if (containerStyle.display === 'grid') {
            // Copy grid properties if the container was using grid
            productList.style.gridTemplateColumns = containerStyle.gridTemplateColumns;
            productList.style.gap = containerStyle.gap;
        }

        productContainer.appendChild(productList);

        // Apply filters if provided
        let filteredProducts = [...productsData];
        if (filterOptions) {
            if (filterOptions.category) {
                filteredProducts = filteredProducts.filter(product =>
                    product.category === filterOptions.category);
            }

            if (filterOptions.minPrice !== undefined) {
                filteredProducts = filteredProducts.filter(product => {
                    const price = typeof product.price === 'number'
                        ? product.price
                        : parseFloat(product.price.replace(/[^\d.]/g, ''));
                    return price >= filterOptions.minPrice;
                });
            }

            if (filterOptions.maxPrice !== undefined) {
                filteredProducts = filteredProducts.filter(product => {
                    const price = typeof product.price === 'number'
                        ? product.price
                        : parseFloat(product.price.replace(/[^\d.]/g, ''));
                    return price <= filterOptions.maxPrice;
                });
            }
        }

        // Get subset of products if limit is provided
        const productsToShow = filteredProducts.slice(0, limit);

        // If no products to show after filtering
        if (productsToShow.length === 0) {
            productList.innerHTML = '<li class="y-c-no-products">لا توجد منتجات متاحة</li>';
            return;
        }

        // Generate cards for each product
        productsToShow.forEach(product => {
            const card = createProductCard(product);
            productList.innerHTML += card;
        });

        return productsToShow.length; // Return count of displayed products
    }

    // Make these functions and data available globally
    window.productUtils = {
        products: products,
        createProductCard: createProductCard,
        generateProductSection: generateProductSection,
        toggleFavorite: toggleFavorite,
        isFavoriteProduct: isFavoriteProduct,
        getFavorites: getFavorites,
        showNotification: showNotification
    };

    // Initialize favorite buttons state on page load
    function initializeFavoriteButtons() {
        // Load favorites from database first
        getFavorites(function(favorites) {
            const favoriteButtons = document.querySelectorAll('[data-favorite-toggle]');
            favoriteButtons.forEach(button => {
                // Skip wishlist page buttons - they're already set correctly
                if (button.getAttribute('data-wishlist-item') === 'true') {
                    return;
                }
                
                const productId = button.getAttribute('data-product-id') || button.closest('.y-c-card')?.dataset.productId;
                if (productId) {
                    const numericId = Number(productId);
                    if (favorites.includes(numericId)) {
                        button.classList.add('active');
                    } else {
                        button.classList.remove('active');
                    }
                }
            });
        });
    }

    // Initialize on page load - wait a bit for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initializeFavoriteButtons, 500);
        });
    } else {
        setTimeout(initializeFavoriteButtons, 500);
    }
    
    // Listen for favorites updated event (from wishlist page or when favorites change)
    document.addEventListener('favoritesUpdated', function() {
        // Reload favorites from database and update buttons
        favoritesLoaded = false; // Force reload
        initializeFavoriteButtons();
    });

    // Set up favorite toggling event delegation
    document.addEventListener('click', (event) => {
        const favoriteToggle = event.target.closest('[data-favorite-toggle]');
        if (favoriteToggle) {
            event.preventDefault();
            event.stopPropagation();

            // Get product ID from button attribute or parent card
            let productId = favoriteToggle.getAttribute('data-product-id');
            if (!productId) {
                const productCard = favoriteToggle.closest('.y-c-card');
                if (productCard) {
                    productId = productCard.dataset.productId;
                }
            }
            
            if (productId) {
                // Optimistically update UI
                const wasActive = favoriteToggle.classList.contains('active');
                favoriteToggle.classList.toggle('active');
                
                // Toggle favorite via AJAX
                toggleFavorite(productId, function(isFavorite) {
                    // Update UI based on actual result
                    if (isFavorite) {
                        favoriteToggle.classList.add('active');
                    } else {
                        favoriteToggle.classList.remove('active');
                    }
                });
            }
        }

        // Handle cart button clicks
        const cartButton = event.target.closest('[data-cart-btn]');
        if (cartButton) {
            event.preventDefault();
            event.stopPropagation();

            const productCard = cartButton.closest('.y-c-card');
            if (productCard) {
                const productId = productCard.dataset.productId;
                // Add to cart functionality would go here
                console.log(`Adding product ${productId} to cart`);
            }
        }
    });

    // Auto-initialize product sections on non-shop pages
    function initializeProductSections() {
        // Check if we're on a page that needs automatic product generation
        // Skip on shop-archive page which has its own initialization logic
        const isShopArchivePage = document.querySelector('.y-l-shop-section') !== null;

        if (!isShopArchivePage) {
            // Find all product containers in the page
            const productContainers = document.querySelectorAll('[id^="product-container-"]');

            // If there are specific containers with IDs, populate them
            if (productContainers.length > 0) {
                productContainers.forEach(container => {
                    // Check if container already has WooCommerce products loaded (has ul.products with real product cards)
                    const existingProducts = container.querySelector('ul.products');
                    if (existingProducts) {
                        const hasRealProducts = existingProducts.querySelectorAll('li.y-c-card[data-product-id]').length > 0;
                        if (hasRealProducts) {
                            // WooCommerce products already loaded, skip JavaScript generation
                            console.log('WooCommerce products already loaded in', container.id, '- skipping JavaScript generation');
                            return;
                        }
                    }
                    
                    // Get the data attributes
                    const limit = container.dataset.limit ? parseInt(container.dataset.limit) : products.length;
                    const category = container.dataset.category || null;

                    // Apply category filter if specified
                    const filterOptions = category ? { category: category } : null;

                    // Generate product cards for this container only if no WooCommerce products exist
                    generateProductSection(container.id, products, limit, filterOptions);
                });
            }

            // Backwards compatibility for the original product-container
            const defaultContainer = document.getElementById('product-container');
            if (defaultContainer) {
                const existingProducts = defaultContainer.querySelector('ul.products');
                if (!existingProducts || existingProducts.querySelectorAll('li.y-c-card[data-product-id]').length === 0) {
                    generateProductSection('product-container', products);
                }
            }
        }
    }

    // Make sure the CSS is loaded
    function loadProductListStyles() {
        // Check if the style is already loaded
        if (!document.querySelector('link[href*="y-cards.css"]')) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            // Corrected the filename from y-product-list.css to y-cards.css
            link.href = '/components/y-cards.css';
            document.head.appendChild(link);
        }
    }

    // Run initialization
    initializeProductSections();
    loadProductListStyles();
});