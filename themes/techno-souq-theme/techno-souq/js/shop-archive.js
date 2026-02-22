// Configuration for pagination
const paginationConfig = {
    itemsPerPage: 15,
    currentPage: 1,
    totalPages: 0
};

// Product configuration
const productConfig = {
    displayProducts: []
};

// Sample product data by categories
const productsByCategory = {
    vegetables: [
        {
            id: 101,
            name: 'طماطم طازجة',
            price: '15 ر.س',
            oldPrice: '20 ر.س',
            image: '/assets/10.png',
            category: 'vegetables'
        },
        {
            id: 102,
            name: 'خيار أخضر',
            price: '10 ر.س',
            image: '/assets/20.png',
            category: 'vegetables'
        },
        {
            id: 103,
            name: 'فلفل أخضر',
            price: '12 ر.س',
            oldPrice: '18 ر.س',
            image: '/assets/10.png',
            category: 'vegetables'
        },
        {
            id: 104,
            name: 'بصل أحمر',
            price: '8 ر.س',
            image: '/assets/20.png',
            category: 'vegetables'
        },
        {
            id: 105,
            name: 'باذنجان',
            price: '14 ر.س',
            oldPrice: '19 ر.س',
            image: '/assets/10.png',
            category: 'vegetables'
        }
    ],
    fruits: [
        {
            id: 201,
            name: 'تفاح أحمر',
            price: '25 ر.س',
            oldPrice: '30 ر.س',
            image: '/assets/20.png',
            category: 'fruits'
        },
        {
            id: 202,
            name: 'موز',
            price: '18 ر.س',
            image: '/assets/10.png',
            category: 'fruits'
        },
        {
            id: 203,
            name: 'برتقال',
            price: '20 ر.س',
            oldPrice: '24 ر.س',
            image: '/assets/20.png',
            category: 'fruits'
        },
        {
            id: 204,
            name: 'فراولة',
            price: '35 ر.س',
            image: '/assets/10.png',
            category: 'fruits'
        },
        {
            id: 205,
            name: 'عنب أخضر',
            price: '30 ر.س',
            oldPrice: '38 ر.س',
            image: '/assets/20.png',
            category: 'fruits'
        }
    ],
    dairy: [
        {
            id: 301,
            name: 'حليب طازج',
            price: '15 ر.س',
            oldPrice: '18 ر.س',
            image: '/assets/wash.png',
            category: 'dairy'
        },
        {
            id: 302,
            name: 'جبن أبيض',
            price: '25 ر.س',
            image: '/assets/10.png',
            category: 'dairy'
        },
        {
            id: 303,
            name: 'زبادي',
            price: '12 ر.س',
            oldPrice: '15 ر.س',
            image: '/assets/wash.png',
            category: 'dairy'
        },
        {
            id: 304,
            name: 'قشطة طازجة',
            price: '20 ر.س',
            image: '/assets/10.png',
            category: 'dairy'
        }
    ],
    bakery: [
        {
            id: 401,
            name: 'خبز عربي',
            price: '5 ر.س',
            image: '/assets/20.png',
            category: 'bakery'
        },
        {
            id: 402,
            name: 'كعك بالسمسم',
            price: '18 ر.س',
            oldPrice: '22 ر.س',
            image: '/assets/10.png',
            category: 'bakery'
        },
        {
            id: 403,
            name: 'كرواسان',
            price: '15 ر.س',
            image: '/assets/20.png',
            category: 'bakery'
        },
        {
            id: 404,
            name: 'بسكويت',
            price: '12 ر.س',
            oldPrice: '16 ر.س',
            image: '/assets/10.png',
            category: 'bakery'
        }
    ]
};

// Function to initialize the shop archive page
document.addEventListener('DOMContentLoaded', function () {
    // Check if we're on the shop archive page
    const isShopArchivePage = document.querySelector('.y-l-shop-section') !== null;

    if (isShopArchivePage) {
        // Initialize shop archive specific features
        initializeShopArchive();
        initializeDropdownMenu();
    }

    // Initialize category sections (can be used on multiple pages)
    initializeCategoryProducts();
});

// Initialize the shop archive with pagination
function initializeShopArchive() {
    // Check if WooCommerce has already populated products (PHP-generated)
    const container = document.getElementById('featured-products-container');
    if (container) {
        const existingProducts = container.querySelectorAll('.y-c-card[data-product-id]');
        if (existingProducts.length > 0) {
            // WooCommerce products are already loaded via PHP, skip JavaScript initialization
            console.log('WooCommerce products detected, using PHP-rendered products');
            // Still initialize dropdowns and filters, but don't override products
            return;
        }
    }

    // Make sure we have access to the product utils
    if (!window.productUtils) {
        console.error('Product utilities not found. Make sure products.js is loaded before shop-archive.js');
        return;
    }

    // Use the global products array from products.js
    const allProducts = window.productUtils.products;

    // Add category products to the main list for display
    let allShopProducts = [...allProducts];

    // Add some products from each category to the main shop display
    Object.values(productsByCategory).forEach(categoryProducts => {
        // Add representative products from each category (limiting to avoid duplication)
        categoryProducts.slice(0, 2).forEach(product => {
            // Ensure we don't add duplicates (checking by ID)
            if (!allShopProducts.some(p => p.id === product.id)) {
                allShopProducts.push(product);
            }
        });
    });

    // Set the display products
    productConfig.displayProducts = [...allShopProducts];

    // Calculate total pages
    paginationConfig.totalPages = Math.ceil(productConfig.displayProducts.length / paginationConfig.itemsPerPage);

    // Display products for the current page
    displayProductsForCurrentPage();

    // Create pagination controls
    createPaginationControls();
}

// Initialize category products
function initializeCategoryProducts() {
    // Load products for each category
    loadCategoryProducts('product-container-vegetables', productsByCategory.vegetables);
    loadCategoryProducts('product-container-fruits', productsByCategory.fruits);
    loadCategoryProducts('product-container-dairy', productsByCategory.dairy);
    loadCategoryProducts('product-container-bakery', productsByCategory.bakery);
}

// Load products for a specific category
function loadCategoryProducts(containerId, products) {
    const container = document.getElementById(containerId);
    if (!container) return;

    // Make sure we have access to the product utils
    if (!window.productUtils) {
        console.error('Product utilities not found. Make sure products.js is loaded before shop-archive.js');
        return;
    }

    // Get the data-limit attribute value or default to 4
    const limit = container.getAttribute('data-limit') ? parseInt(container.getAttribute('data-limit')) : 4;

    // Generate product cards using the utility function from products.js
    window.productUtils.generateProductSection(containerId, products, limit);
}

// Initialize dropdown menu functionality
function initializeDropdownMenu() {
    setupDropdown('sort-dropdown');
    setupDropdown('category-dropdown'); // This was the old one, you can keep if using elsewhere
    setupDropdown('filter-dropdown');   // Initialize the new filter dropdown
}

// Setup dropdown functionality
function setupDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    if (!dropdown) return;

    const menuButton = dropdown.querySelector('.y-c-shop-menu-button');
    const dropdownMenu = dropdown.querySelector('.y-c-shop-menu-dropdown');
    const menuItems = dropdown.querySelectorAll('.y-c-shop-menu-item');

    // Toggle dropdown visibility when clicking the button
    if (menuButton && dropdownMenu) {
        // Remove any existing listeners by cloning
        const newButton = menuButton.cloneNode(true);
        menuButton.parentNode.replaceChild(newButton, menuButton);
        
        newButton.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isActive = dropdownMenu.classList.contains('active');
            
            // Close all dropdowns first
            document.querySelectorAll('.y-c-shop-menu-dropdown').forEach(d => {
                d.classList.remove('active');
            });
            document.querySelectorAll('.y-c-shop-menu-button').forEach(b => {
                b.classList.remove('active');
            });
            
            // Toggle current dropdown
            if (!isActive) {
                dropdownMenu.classList.add('active');
                newButton.classList.add('active');
            }
        });

        // Prevent dropdown from closing when clicking inside content (important for forms)
        dropdownMenu.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }

    // Add click event to simple link menu items (like Sort options)
    // This logic is kept for the Sort menu, but won't affect the form elements in Filter menu
    menuItems.forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();

            // Remove active class from all items
            menuItems.forEach(i => i.classList.remove('active'));

            // Add active class to clicked item
            this.classList.add('active');

            // Update dropdown button text (Only for Sort menu essentially)
            const itemText = this.textContent.trim();
            const buttonText = menuButton.querySelector('.text'); // Note: ensure HTML has class .text if you rely on this
            // Or rely on data attributes to update specific spans if needed

            // Close dropdown
            dropdownMenu.classList.remove('active');
            menuButton.classList.remove('active');
        });
    });
}

// Display products for the current page
function displayProductsForCurrentPage() {
    const container = document.getElementById('featured-products-container');
    if (!container) return;

    // Check if WooCommerce has already populated the container with products
    // If there are WooCommerce product cards (PHP-generated), don't override them
    const existingProducts = container.querySelectorAll('.y-c-card[data-product-id]');
    if (existingProducts.length > 0) {
        // WooCommerce products are already loaded via PHP, don't override
        console.log('WooCommerce products already loaded, skipping JavaScript product generation');
        return;
    }

    // Make sure we have access to the product utils
    if (!window.productUtils) {
        console.error('Product utilities not found. Make sure products.js is loaded before shop-archive.js');
        return;
    }

    // Clear existing content only if no WooCommerce products exist
    container.innerHTML = '';

    // Calculate start and end indices for the current page
    const startIndex = (paginationConfig.currentPage - 1) * paginationConfig.itemsPerPage;
    const endIndex = Math.min(startIndex + paginationConfig.itemsPerPage, productConfig.displayProducts.length);

    // Display products for the current page
    for (let i = startIndex; i < endIndex; i++) {
        const product = productConfig.displayProducts[i];
        if (product) {
            container.innerHTML += window.productUtils.createProductCard(product);
        }
    }
}

// Create pagination controls
function createPaginationControls() {
    const paginationContainer = document.getElementById('pagination-container');
    if (!paginationContainer) return;
    
    // Check if WooCommerce pagination already exists (PHP-generated)
    const existingPagination = paginationContainer.querySelector('ul');
    if (existingPagination && existingPagination.children.length > 0) {
        // WooCommerce pagination is already rendered, don't override
        console.log('WooCommerce pagination detected, skipping JavaScript pagination');
        return;
    }

    // Clear existing content
    paginationContainer.innerHTML = '';

    // If there's only one page or no products, don't show pagination
    if (paginationConfig.totalPages <= 1) {
        return;
    }

    // Create pagination HTML
    let paginationHTML = '';

    // Previous button
    paginationHTML += `
        <button class="y-c-pagination-button ${paginationConfig.currentPage === 1 ? 'disabled' : ''}" 
                onclick="changePage(${paginationConfig.currentPage - 1})" 
                ${paginationConfig.currentPage === 1 ? 'disabled' : ''}>
            <i class="fas fa-chevron-right"></i>
        </button>
    `;

    // Determine which page numbers to show
    const totalPages = paginationConfig.totalPages;
    const currentPage = paginationConfig.currentPage;

    // Always show first page
    paginationHTML += `
        <button class="y-c-pagination-button ${1 === currentPage ? 'active' : ''}" 
                onclick="changePage(1)">
            1
        </button>
    `;

    // Logic for showing page numbers with ellipsis
    if (totalPages <= 6) {
        // If 6 or fewer pages, show all pages without ellipsis
        for (let i = 2; i < totalPages; i++) {
            paginationHTML += `
                <button class="y-c-pagination-button ${i === currentPage ? 'active' : ''}" 
                        onclick="changePage(${i})">
                    ${i}
                </button>
            `;
        }
    } else {
        // If more than 6 pages, show first 3, ellipsis, and last 2

        // Show page 2 and 3 if we're at the beginning
        if (currentPage <= 3) {
            for (let i = 2; i <= 3; i++) {
                paginationHTML += `
                    <button class="y-c-pagination-button ${i === currentPage ? 'active' : ''}" 
                            onclick="changePage(${i})">
                        ${i}
                    </button>
                `;
            }
            // Add ellipsis
            paginationHTML += `
                <span class="y-c-pagination-ellipsis">...</span>
            `;

            // Show the two pages before the last page
            for (let i = totalPages - 1; i <= totalPages - 1; i++) {
                paginationHTML += `
                    <button class="y-c-pagination-button ${i === currentPage ? 'active' : ''}" 
                            onclick="changePage(${i})">
                        ${i}
                    </button>
                `;
            }
        }
        // Show current page and surrounding pages if in the middle
        else if (currentPage > 3 && currentPage < totalPages - 2) {
            // Add ellipsis after page 1
            paginationHTML += `
                <span class="y-c-pagination-ellipsis">...</span>
            `;

            // Show the current page and one page before and after
            for (let i = currentPage - 1; i <= currentPage + 1; i++) {
                if (i > 1 && i < totalPages) {
                    paginationHTML += `
                        <button class="y-c-pagination-button ${i === currentPage ? 'active' : ''}" 
                                onclick="changePage(${i})">
                            ${i}
                        </button>
                    `;
                }
            }

            // Add ellipsis before last page
            paginationHTML += `
                <span class="y-c-pagination-ellipsis">...</span>
            `;
        }
        // Show the last 3 pages if we're near the end
        else {
            // Add ellipsis after page 1
            paginationHTML += `
                <span class="y-c-pagination-ellipsis">...</span>
            `;

            // Show two pages before the last two pages
            for (let i = totalPages - 3; i < totalPages; i++) {
                if (i > 1) {
                    paginationHTML += `
                        <button class="y-c-pagination-button ${i === currentPage ? 'active' : ''}" 
                                onclick="changePage(${i})">
                            ${i}
                        </button>
                    `;
                }
            }
        }
    }

    // Always show last page if there's more than 1 page
    if (totalPages > 1) {
        paginationHTML += `
            <button class="y-c-pagination-button ${totalPages === currentPage ? 'active' : ''}" 
                    onclick="changePage(${totalPages})">
                ${totalPages}
            </button>
        `;
    }

    // Next button
    paginationHTML += `
        <button class="y-c-pagination-button ${currentPage === totalPages ? 'disabled' : ''}" 
                onclick="changePage(${currentPage + 1})" 
                ${currentPage === totalPages ? 'disabled' : ''}>
            <i class="fas fa-chevron-left"></i>
        </button>
    `;

    // Set the HTML
    paginationContainer.innerHTML = paginationHTML;
}

// Make change page function available globally for pagination buttons
window.changePage = changePage;

// Change page function
function changePage(newPage) {
    // Validate the new page
    if (newPage < 1 || newPage > paginationConfig.totalPages) {
        return;
    }

    // Update current page
    paginationConfig.currentPage = newPage;

    // Display products for the new page
    displayProductsForCurrentPage();

    // Update pagination controls
    createPaginationControls();

    // Scroll to top of products
    const shopGrid = document.querySelector('.y-l-shop-grid');
    if (shopGrid) {
        shopGrid.scrollIntoView({ behavior: 'smooth' });
    }
}