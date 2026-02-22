'use strict';

/**
 * Loads an HTML component from a URL into a placeholder element.
 * @param {string} url The URL of the HTML component to load.
 * @param {string} placeholderId The ID of the element to load the component into.
 * @returns {Promise<void>} A promise that resolves when the component is loaded.
 */
function loadComponent(url, placeholderId) {
    return fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(data => {
            const placeholder = document.getElementById(placeholderId);
            if (placeholder) {
                placeholder.innerHTML = data;
            }
        })
        .catch(error => {
            console.error(`Could not load component from ${url}:`, error);
        });
}

/**
 * Initializes the application after the DOM is fully loaded.
 */
function initWhenReady() {
    const componentBaseUrl = window.ahmadiTheme && window.ahmadiTheme.componentBaseUrl
        ? window.ahmadiTheme.componentBaseUrl
        : '';
    const tasks = [];
    if (document.getElementById('header-placeholder')) {
        tasks.push(loadComponent(`${componentBaseUrl}/components/y-header.html`, 'header-placeholder'));
    }
    if (document.getElementById('footer-placeholder')) {
        tasks.push(loadComponent(`${componentBaseUrl}/components/y-footer.html`, 'footer-placeholder'));
    }

    const finalize = () => {
        initializeSharedComponents();
        if (typeof initializeApp === 'function') {
            initializeApp();
        }
    };

    if (tasks.length) {
        Promise.all(tasks).then(finalize);
        return;
    }

    finalize();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initWhenReady);
} else {
    initWhenReady();
}


/**
 * Initializes the shared UI components.
 */
function initializeSharedComponents() {
    setupCategoriesDropdown();
    setupMobileCategoriesDropdown();
    setupMobileCategoriesDropdownMenu();
    setupMobileMenu();
    setupMobileSearch();
    setupActiveNavLinks();
    setupCartFunctionality();
    setupWooCartQuantityControls();
    setupSearchFunctionality();
    setupProfileNavigation();
    setupAddToCartNotice();
    setupFavoriteIcons();
    setupFavoritesPage();
}


/**
 * Generic function to set up dropdown menu functionality.
 * @param {string} buttonId - The ID of the button that toggles the dropdown.
 * @param {string} dropdownId - The ID of the dropdown content element.
 */
function setupDropdown(buttonId, dropdownId) {
    const button = document.getElementById(buttonId);
    const dropdown = document.getElementById(dropdownId);

    if (!button || !dropdown) {
        console.warn(`Dropdown setup failed: could not find elements with IDs ${buttonId} and/or ${dropdownId}`);
        return;
    }

    button.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdown.classList.toggle('y-c-show');
        const icon = this.querySelector('.fa-chevron-down');
        if (icon) {
            icon.style.transform = dropdown.classList.contains('y-c-show') ? 'rotate(180deg)' : 'rotate(0)';
        }
    });

    document.addEventListener('click', function (e) {
        if (!button.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('y-c-show');
            const icon = button.querySelector('.fa-chevron-down');
            if (icon) {
                icon.style.transform = 'rotate(0)';
            }
        }
    });
}

/**
 * Sets up the categories dropdown menu for desktop view.
 */
function setupCategoriesDropdown() {
    setupDropdown('categoriesBtn', 'categoriesDropdown');
}

/**
 * Sets up the categories dropdown menu for mobile view.
 */
function setupMobileCategoriesDropdown() {
    setupDropdown('categories-mobile-Btn', 'categoriesDropdown');
}

/**
 * Sets up the mobile categories dropdown menu.
 */
function setupMobileCategoriesDropdownMenu() {
    setupDropdown('categories-mobile-Btn', 'categoriesMobileDropdown');
}


/**
 * Sets up the mobile menu functionality.
 */
function setupMobileMenu() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileNavDropdown = document.getElementById('mobileNavDropdown');
    const mobileSearchExpanded = document.getElementById('mobileSearchExpanded');

    if (mobileMenuBtn && mobileNavDropdown) {
        mobileMenuBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            mobileNavDropdown.classList.toggle('y-c-show');
            if (mobileSearchExpanded) {
                mobileSearchExpanded.classList.remove('y-c-show');
            }
        });

        document.addEventListener('click', function (e) {
            if (!mobileMenuBtn.contains(e.target) && !mobileNavDropdown.contains(e.target)) {
                mobileNavDropdown.classList.remove('y-c-show');
            }
        });
    }
}

/**
 * Sets up the mobile search functionality.
 */
function setupMobileSearch() {
    const mobileSearchIcon = document.getElementById('mobileSearchIcon');
    const mobileSearchExpanded = document.getElementById('mobileSearchExpanded');
    const mobileNavDropdown = document.getElementById('mobileNavDropdown');

    if (mobileSearchIcon && mobileSearchExpanded) {
        mobileSearchIcon.addEventListener('click', function (e) {
            e.stopPropagation();
            mobileSearchExpanded.classList.toggle('y-c-show');
            if (mobileNavDropdown) {
                mobileNavDropdown.classList.remove('y-c-show');
            }

            if (mobileSearchExpanded.classList.contains('y-c-show')) {
                setTimeout(() => {
                    const searchInput = mobileSearchExpanded.querySelector('input');
                    if (searchInput) {
                        searchInput.focus();
                    }
                }, 100);
            }
        });

        document.addEventListener('click', function (e) {
            if (!mobileSearchIcon.contains(e.target) && !mobileSearchExpanded.contains(e.target)) {
                mobileSearchExpanded.classList.remove('y-c-show');
            }
        });
    }
}

/**
 * Sets the active class on the current navigation link.
 */
function setupActiveNavLinks() {
    const normalizePath = (path) => {
        if (!path) {
            return '/';
        }
        return path.replace(/\/+$/, '') || '/';
    };

    const currentPath = normalizePath(window.location.pathname);
    const navLinks = document.querySelectorAll('.y-c-nav-links a');

    navLinks.forEach(link => {
        const linkHref = link.getAttribute('href') || '';
        let linkPath = '/';
        try {
            linkPath = normalizePath(new URL(linkHref, window.location.origin).pathname);
        } catch (error) {
            linkPath = normalizePath(linkHref);
        }
        link.classList.toggle('y-c-active', linkPath === currentPath);
    });

    const toggleActiveByHref = (selector, expectedPath) => {
        const targetPath = normalizePath(expectedPath);
        document.querySelectorAll(selector).forEach(link => {
            let linkPath = '/';
            try {
                linkPath = normalizePath(new URL(link.getAttribute('href') || '', window.location.origin).pathname);
            } catch (error) {
                linkPath = normalizePath(link.getAttribute('href') || '');
            }
            link.classList.toggle('y-c-active', linkPath === targetPath);
        });
    };

    toggleActiveByHref('.y-c-cart-icon a', '/cart/');
    toggleActiveByHref('a[href*="favorite"]', '/favorite/');
    toggleActiveByHref('a[href*="account"]', '/account/');
}

/**
 * Sets up cart functionality.
 */
function setupCartFunctionality() {
    if (window.ahmadiTheme && window.ahmadiTheme.isWooCommerce) {
        return;
    }
    document.addEventListener('click', function (e) {
        const addToCartButton = e.target.closest('.y-c-add-to-cart-btn');
        if (addToCartButton) {
            const productName = addToCartButton.dataset.product;
            const productPrice = addToCartButton.dataset.price;

            addToCart(productName, productPrice);

            addToCartButton.style.transform = 'scale(0.95)';
            addToCartButton.innerHTML = '<i class="fas fa-check"></i> تمت الإضافة';

            setTimeout(() => {
                addToCartButton.style.transform = '';
                addToCartButton.innerHTML = '<i class="fas fa-cart-plus"></i> إضافة للسلة';
            }, 1500);
        }
    });

    updateCartDisplay();
}

/**
 * Enables quantity +/- buttons on WooCommerce cart.
 */
function setupWooCartQuantityControls() {
    const cartForm = document.querySelector('.woocommerce-cart-form');
    if (!cartForm) {
        return;
    }

    let submitTimer;
    const enableUpdateButton = () => {
        const updateButton = cartForm.querySelector('button[name="update_cart"]');
        if (updateButton) {
            updateButton.removeAttribute('disabled');
        }
        return updateButton;
    };

    const scheduleCartSubmit = () => {
        clearTimeout(submitTimer);
        submitTimer = setTimeout(() => {
            const updateButton = enableUpdateButton();
            if (updateButton) {
                updateButton.click();
                return;
            }
            cartForm.submit();
        }, 300);
    };

    document.addEventListener('click', function (e) {
        const control = e.target.closest('.y-c-qty-btn');
        if (!control) {
            return;
        }

        const wrapper = control.closest('.y-c-Cart-quantity-btn');
        if (!wrapper) {
            return;
        }

        const input = wrapper.querySelector('input.qty, input[name*="[qty]"]');
        if (!input) {
            return;
        }

        const step = parseFloat(input.getAttribute('step')) || 1;
        const min = input.getAttribute('min') === '' ? null : parseFloat(input.getAttribute('min'));
        const max = input.getAttribute('max') === '' ? null : parseFloat(input.getAttribute('max'));
        const current = parseFloat(input.value) || 0;
        let next = current;

        if (control.classList.contains('y-c-qty-plus')) {
            next = current + step;
        } else if (control.classList.contains('y-c-qty-minus')) {
            next = current - step;
        }

        if (min !== null && !Number.isNaN(min)) {
            next = Math.max(next, min);
        }
        if (max !== null && !Number.isNaN(max)) {
            next = Math.min(next, max);
        }

        input.value = next;
        input.dispatchEvent(new Event('change', { bubbles: true }));

        scheduleCartSubmit();
    });

    cartForm.addEventListener('change', function (e) {
        if (e.target && e.target.matches('input.qty, input[name*="[qty]"]')) {
            scheduleCartSubmit();
        }
    });
}

/**
 * Adds an item to the cart.
 * @param {string} productName The name of the product.
 * @param {string} productPrice The price of the product.
 */
function addToCart(productName, productPrice) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const existingItem = cart.find(item => item.name === productName);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({ name: productName, price: productPrice, quantity: 1 });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
}

/**
 * Updates the cart display with the total number of items.
 */
function updateCartDisplay() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartText = document.querySelector('.y-c-cart-text');

    if (cartText) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartText.textContent = totalItems > 0 ? ` (${totalItems})` : 'ر.س 0.00';
    }
}



/**
 * Sets up search functionality for all search inputs.
 */
function setupSearchFunctionality() {
    const searchInputs = document.querySelectorAll('.y-c-search-input');
    searchInputs.forEach(searchInput => {
        let searchTimeout;
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            searchTimeout = setTimeout(() => {
                if (query.length > 2) {
                    performSearch(query);
                }
            }, 300);
        });

        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = this.value.trim();
                if (query) {
                    performSearch(query);
                }
            }
        });
    });
}

/**
 * Performs a search.
 * @param {string} query The search query.
 */
function performSearch(query) {
    console.log(`البحث عن: ${query}`);
}

/**
 * Sets up profile page navigation.
 */
function setupProfileNavigation() {
    const profileContentPlaceholder = document.getElementById('profile-content-placeholder');
    const headerTitle = document.querySelector('.y-c-header-title');

    // Check if we are on the profile page
    if (!profileContentPlaceholder || !headerTitle) {
        return;
    }

    const baseUrl = window.ahmadiTheme && window.ahmadiTheme.componentBaseUrl
        ? window.ahmadiTheme.componentBaseUrl
        : '';
    const menuLinks = {
        'orders-link': `${baseUrl}/templates/account/components/orders.html`,
        'downloads-link': `${baseUrl}/templates/account/components/downloads.html`,
        'address-link': `${baseUrl}/templates/account/components/address.html`,
        'account-details-link': `${baseUrl}/templates/account/components/account-details.html`
    };

    // Function to load content
    const loadProfileContent = (url, title) => {
        fetch(url)
            .then(response => response.text())
            .then(data => {
                profileContentPlaceholder.innerHTML = data;
                headerTitle.textContent = title;
            })
            .catch(error => {
                console.error('Error loading profile content:', error);
                profileContentPlaceholder.innerHTML = '<p>Error loading content.</p>';
            });
    };

    // Load default content (orders)
    const defaultLink = document.getElementById('orders-link');
    if (defaultLink) {
        const defaultTitle = defaultLink.dataset.title;
        loadProfileContent(menuLinks['orders-link'], defaultTitle);
    }


    // Add click listeners to menu links
    for (const linkId in menuLinks) {
        const link = document.getElementById(linkId);
        if (link) {
            const title = link.dataset.title;
            link.addEventListener('click', (e) => {
                e.preventDefault();
                loadProfileContent(menuLinks[linkId], title);
            });
        }
    }
}

function setupAddToCartNotice() {
    const bindHandler = () => {
        if (typeof jQuery === 'undefined') {
            return false;
        }

        jQuery(document.body).on('added_to_cart', function (event, fragments, cartHash, $button) {
            const wrapper = document.querySelector('.woocommerce-notices-wrapper');
            if (!wrapper) {
                return;
            }

            let productName = '';
            if ($button && $button.length) {
                const card = $button.closest('.y-c-product-card');
                if (card) {
                    const title = card.querySelector('h4');
                    if (title) {
                        productName = title.textContent.trim();
                    }
                }
            }

            const messageText = productName
                ? `تمت إضافة "${productName}" إلى السلة.`
                : 'تمت إضافة المنتج إلى السلة.';

            wrapper.innerHTML = `<div class="woocommerce-message" role="alert">${messageText}</div>`;
        });

        return true;
    };

    if (bindHandler()) {
        // Continue to click-based notice to cover non-jQuery flows.
    }

    let attempts = 0;
    const timer = setInterval(() => {
        attempts += 1;
        if (bindHandler() || attempts > 20) {
            clearInterval(timer);
        }
    }, 250);

    document.body.addEventListener('click', (event) => {
        const button = event.target.closest('.add_to_cart_button');
        if (!button) {
            return;
        }

        const wrapper = document.querySelector('.woocommerce-notices-wrapper');
        if (!wrapper) {
            return;
        }

        const card = button.closest('.y-c-product-card');
        let productName = '';
        if (card) {
            const title = card.querySelector('h4');
            if (title) {
                productName = title.textContent.trim();
            }
        }

        const messageText = productName
            ? `تمت إضافة "${productName}" إلى السلة.`
            : 'تمت إضافة المنتج إلى السلة.';

        wrapper.innerHTML = `<div class="woocommerce-message" role="alert">${messageText}</div>`;
    });
}

function getStoredFavorites() {
    try {
        const data = JSON.parse(localStorage.getItem('favorites'));
        return Array.isArray(data) ? data : [];
    } catch (error) {
        return [];
    }
}

function saveFavorites(favorites) {
    localStorage.setItem('favorites', JSON.stringify(favorites));
}

function getFavoriteKey(item) {
    if (!item) {
        return '';
    }
    if (item.id) {
        return String(item.id);
    }
    if (item.url) {
        return String(item.url);
    }
    return item.name ? String(item.name) : '';
}

function extractProductFromCard(card) {
    if (!card) {
        return null;
    }
    const titleEl = card.querySelector('h4');
    const priceEl = card.querySelector('.y-c-product-price');
    const linkEl = card.querySelector('.y-c-product-image');
    const imageEl = linkEl ? linkEl.querySelector('img') : card.querySelector('img');
    const addButton = card.querySelector('.add_to_cart_button, .y-c-shop-now-btn');
    const productId = addButton
        ? (addButton.dataset.product_id || addButton.getAttribute('data-product_id') || addButton.dataset.productId)
        : null;

    return {
        id: productId || null,
        name: titleEl ? titleEl.textContent.trim() : '',
        price: priceEl ? priceEl.textContent.trim() : '',
        image: imageEl ? imageEl.getAttribute('src') : '',
        url: linkEl ? linkEl.getAttribute('href') : ''
    };
}

function updateFavoriteIconState(iconWrapper, isFavorite) {
    if (!iconWrapper) {
        return;
    }
    iconWrapper.classList.toggle('y-c-active', isFavorite);
    const icon = iconWrapper.querySelector('i');
    if (icon) {
        icon.classList.toggle('fa-regular', !isFavorite);
        icon.classList.toggle('fa-solid', isFavorite);
    }
}

function setupFavoriteIcons() {
    const icons = document.querySelectorAll('.y-c-product-card .y-c-favorite-icon');
    if (!icons.length) {
        return;
    }

    icons.forEach((iconWrapper) => {
        const card = iconWrapper.closest('.y-c-product-card');
        if (!card) {
            return;
        }

        const product = extractProductFromCard(card);
        if (!product || (!product.id && !product.url && !product.name)) {
            return;
        }

        const favorites = getStoredFavorites();
        const isFavorite = favorites.some(item => getFavoriteKey(item) === getFavoriteKey(product));
        updateFavoriteIconState(iconWrapper, isFavorite);

        iconWrapper.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();

            let nextFavorites = getStoredFavorites();
            const key = getFavoriteKey(product);
            const exists = nextFavorites.some(item => getFavoriteKey(item) === key);

            if (exists) {
                nextFavorites = nextFavorites.filter(item => getFavoriteKey(item) !== key);
            } else {
                nextFavorites.push(product);
            }

            saveFavorites(nextFavorites);
            updateFavoriteIconState(iconWrapper, !exists);
        });
    });
}

function formatFavoritePrice(price) {
    if (!price) {
        return '';
    }
    if (price.includes('ر.س')) {
        return price;
    }
    return `${price} ر.س`;
}

function setupFavoritesPage() {
    const table = document.querySelector('[data-y="favorites-table"]');
    const body = document.querySelector('[data-y="favorites-body"]');
    if (!table || !body) {
        return;
    }

    const emptyMessage = document.querySelector('[data-y="favorites-empty"]');
    const cartUrl = window.ahmadiTheme && window.ahmadiTheme.cartUrl
        ? window.ahmadiTheme.cartUrl
        : `${window.location.origin}/cart/`;

    const render = () => {
        const favorites = getStoredFavorites();
        body.innerHTML = '';

        if (!favorites.length) {
            if (emptyMessage) {
                emptyMessage.style.display = 'block';
            }
            return;
        }

        if (emptyMessage) {
            emptyMessage.style.display = 'none';
        }

        favorites.forEach((item) => {
            const row = document.createElement('tr');
            const productLink = item.url || '#';
            const productImage = item.image || '';
            const productPrice = formatFavoritePrice(item.price);
            row.innerHTML = `
                <td><span class="y-c-delete" role="button" aria-label="حذف المنتج">&times;</span></td>
                <td><img src="${productImage}" alt=""></td>
                <td><a href="${productLink}">${item.name || ''}</a></td>
                <td>${productPrice}</td>
                <td class="y-c-status">متوفر في المخزن</td>
                <td><button class="y-c-add-btn" type="button">إضافة إلى السلة</button></td>
            `;

            const deleteBtn = row.querySelector('.y-c-delete');
            const addBtn = row.querySelector('.y-c-add-btn');

            if (deleteBtn) {
                deleteBtn.addEventListener('click', () => {
                    const key = getFavoriteKey(item);
                    const nextFavorites = getStoredFavorites().filter(fav => getFavoriteKey(fav) !== key);
                    saveFavorites(nextFavorites);
                    render();
                });
            }

            if (addBtn) {
                addBtn.addEventListener('click', () => {
                    if (!item.id) {
                        return;
                    }
                    window.location.href = `${cartUrl}?add-to-cart=${encodeURIComponent(item.id)}`;
                });
            }

            body.appendChild(row);
        });
    };

    render();
}