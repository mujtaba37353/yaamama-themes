// Al Thabihah/js/store.js

// Configuration for store "Show More" functionality
const storeConfig = {
    itemsPerLoad: 8,       // Number of items to load each time
    itemsCurrentlyShown: 8, // Start by showing this many
    allProducts: [],         // Will be populated with filtered products
    currentCategory: 'all',  // 'all' or a specific category
    categoryTitles: {      // Titles for the product section header
        'all': 'جميع المنتجات',
        'cuts': 'لحوم بالكيلو',
        'minced': 'مفروم',
        'naemi': 'نعيمي',
        'tays': 'تيس كشميري',
        'ejel': 'عجل',
        'bbq': 'مجهز للشواء',
        'offers': 'العروض'
    }
};

document.addEventListener('DOMContentLoaded', function () {
    // Ensure productUtils is loaded
    if (!window.productUtils) {
        console.error("productUtils not available. Make sure products.js is loaded first.");
        return;
    }

    // 1. Get category from URL
    storeConfig.currentCategory = getCategoryFromURL() || 'all';

    // 2. Filter products based on category
    if (storeConfig.currentCategory === 'all') {
        storeConfig.allProducts = window.productUtils.products;
    } else {
        storeConfig.allProducts = window.productUtils.getProductsByCategory(storeConfig.currentCategory);
    }

    // 3. Highlight the active category in the new nav bar
    highlightActiveCategory();

    // 4. Update the products section title
    updateProductsTitle();

    // 5. Load the initial batch of products
    storeConfig.itemsCurrentlyShown = storeConfig.itemsPerLoad;
    displayStoreProducts();

    // 6. Setup "Show More" button
    setupShowMoreButton();
    setupMobileCategorySidebar();
});

/**
 * Gets the 'category' parameter from the URL.
 * @returns {string | null} The category name or null.
 */
function getCategoryFromURL() {
    const params = new URLSearchParams(window.location.search);
    return params.get('category');
}
function setupMobileCategorySidebar() {
    const triggerBtn = document.getElementById('category-menu-trigger');
    const closeBtn = document.getElementById('category-menu-close');
    const sidebar = document.getElementById('category-grid');
    const overlay = document.getElementById('category-overlay');

    if (!triggerBtn || !sidebar || !overlay) return;

    function openSidebar() {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeSidebar() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = ''; // Restore scrolling
    }

    // Event Listeners
    triggerBtn.addEventListener('click', openSidebar);

    if (closeBtn) {
        closeBtn.addEventListener('click', closeSidebar);
    }

    overlay.addEventListener('click', closeSidebar);
}
/**
 * Highlights the active category card in the category bar.
 */
function highlightActiveCategory() {
    const categoryCards = document.querySelectorAll('.y-c-store-category-card');
    categoryCards.forEach(card => {
        card.classList.remove('active');
        if (card.dataset.category === storeConfig.currentCategory) {
            card.classList.add('active');
        }
    });
}

/**
 * Updates the main product grid title (e.g., "جميع المنتجات" or "نعيمي")
 */
function updateProductsTitle() {
    const titleElement = document.getElementById('products-section-title');
    if (titleElement) {
        titleElement.textContent = storeConfig.categoryTitles[storeConfig.currentCategory] || 'جميع المنتجات';
    }
}

/**
 * Displays products based on the current itemsCurrentlyShown count
 */
function displayStoreProducts() {
    const container = document.getElementById('products-container');
    if (!container) return;

    const filteredProducts = storeConfig.allProducts;
    // Get the slice of products to show
    const productsToShow = filteredProducts.slice(0, storeConfig.itemsCurrentlyShown);

    // Check if there are any products in this category
    if (filteredProducts.length === 0) {
        container.innerHTML = `
            <div style="grid-column: 1/-1; text-align: center; padding: 4rem 2rem; color: var(--y-color-primary-text);">
                <i class="fas fa-box-open" style="font-size: 4rem; color: var(--y-color-error); margin-bottom: 1rem;"></i>
                <h3 style="margin-bottom: 0.5rem; font-size: 1.5rem;">لا توجد منتجات في هذا القسم</h3>
                <p style="color: var(--y-color-third-text);">جرب تصفح قسم آخر.</p>
            </div>
        `;
        // Hide the show more button
        const showMoreContainer = document.getElementById('show-more-container');
        if (showMoreContainer) {
            showMoreContainer.style.display = 'none';
        }
        return;
    }

    // Render the products
    let productsHTML = '';
    productsToShow.forEach(product => {
        if (product && window.productUtils.createProductCard) {
            productsHTML += window.productUtils.createProductCard(product);
        }
    });
    container.innerHTML = productsHTML;

    // Update "Show More" button visibility
    updateShowMoreButton();
}

/**
 * Sets up the event listener for the "Show More" button
 */
function setupShowMoreButton() {
    const showMoreBtn = document.getElementById('show-more-btn');
    if (showMoreBtn) {
        showMoreBtn.addEventListener('click', function () {
            // Increase the number of items to show
            storeConfig.itemsCurrentlyShown += storeConfig.itemsPerLoad;

            // Re-render the products
            displayStoreProducts();
        });
    }
}

/**
 * Hides the "Show More" button if all items are already displayed
 */
function updateShowMoreButton() {
    const showMoreContainer = document.getElementById('show-more-container');
    if (showMoreContainer) {
        if (storeConfig.itemsCurrentlyShown >= storeConfig.allProducts.length) {
            showMoreContainer.style.display = 'none'; // Hide button
        } else {
            showMoreContainer.style.display = 'flex'; // Show button
        }
    }
}