// Configuration for pagination
const paginationConfig = {
    itemsPerPage: 12,
    currentPage: 1,
    totalPages: 0
};

// Define window.productUtils if it doesn't exist yet
window.productUtils = window.productUtils || {};
window.productUtils.products = products;
window.productUtils.createProductCard = createProductCard;



// Function to initialize the shop archive page
document.addEventListener('DOMContentLoaded', function () {
    // Ensure productUtils is properly defined before initializing
    window.productUtils = window.productUtils || {};
    window.productUtils.products = products;
    window.productUtils.createProductCard = createProductCard;

    // This check ensures that initializeShopArchive is only called ONCE
    // either by this file, or by offers.js if it's the offers page.
    // We check if 'offers.js' is included by looking for a specific element on that page.
    const isOffersPage = document.querySelector('.y-c-hero-tab-btn[data-y="hero-tab-booking"] i.fa-tag');

    if (!isOffersPage) {
        initializeShopArchive();
    }

    initializeMegaMenuDropdowns();
    initializeMobileFilter();
    initializeCustomDropdowns();

    // Initialize the range slider with a delay to ensure DOM is ready
    setTimeout(() => {
        const rangeSlider = new PriceRangeSlider();
        rangeSlider.initialize();
    }, 0);
});

// Initialize the shop archive with pagination
function initializeShopArchive() {
    // Make sure we have access to products from window.productUtils
    if (!window.productUtils || !window.productUtils.products) {
        console.error("productUtils not available. Make sure products.js is loaded before shop-archive.js");
        return;
    }

    // Calculate total pages
    paginationConfig.totalPages = Math.ceil(window.productUtils.products.length / paginationConfig.itemsPerPage);

    // Display products for the current page
    displayProductsForCurrentPage();

    // Create pagination controls
    createPaginationControls();
}



// Display products for the current page
function displayProductsForCurrentPage() {
    const container = document.getElementById('products-container');
    if (!container) return;

    // Make sure we have access to products and utility functions
    if (!window.productUtils || !window.productUtils.products || !window.productUtils.createProductCard) {
        console.error("productUtils not available. Make sure products.js is loaded before shop-archive.js");
        return;
    }

    // Clear existing content
    container.innerHTML = '';

    // Calculate start and end indices for the current page
    const startIndex = (paginationConfig.currentPage - 1) * paginationConfig.itemsPerPage;
    const endIndex = Math.min(startIndex + paginationConfig.itemsPerPage, window.productUtils.products.length);

    // Display products for the current page
    for (let i = startIndex; i < endIndex; i++) {
        const product = window.productUtils.products[i];
        if (product) {
            container.innerHTML += window.productUtils.createProductCard(product);
        }
    }
}

// Create pagination controls
function createPaginationControls() {
    const paginationContainer = document.getElementById('pagination-container');
    if (!paginationContainer) {
        // This element might not exist on all pages (e.g., store.html uses a "Show More" button)
        // Let's check for the "Show More" button as a fallback.
        const showMoreBtn = document.getElementById('show-more-btn');
        if (showMoreBtn) {
            // Logic for "Show More" button
            if (paginationConfig.currentPage >= paginationConfig.totalPages) {
                showMoreBtn.style.display = 'none'; // Hide if no more pages
            } else {
                showMoreBtn.style.display = 'block';
            }
            // Add click listener if not already added
            if (!showMoreBtn.dataset.listenerAdded) {
                showMoreBtn.addEventListener('click', () => {
                    paginationConfig.currentPage++;
                    appendProductsForNextPage(); // Use a different function to append
                });
                showMoreBtn.dataset.listenerAdded = 'true';
            }
        }
        return; // Exit if no pagination container
    }

    // --- Logic for actual pagination (like on offers.html if it had one) ---
    // Clear existing content
    paginationContainer.innerHTML = '';

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

// Append products for "Show More" functionality
function appendProductsForNextPage() {
    const container = document.getElementById('products-container');
    if (!container) return;

    const startIndex = (paginationConfig.currentPage - 1) * paginationConfig.itemsPerPage;
    const endIndex = Math.min(startIndex + paginationConfig.itemsPerPage, window.productUtils.products.length);

    for (let i = startIndex; i < endIndex; i++) {
        const product = window.productUtils.products[i];
        if (product) {
            container.innerHTML += window.productUtils.createProductCard(product);
        }
    }

    // Hide button if this was the last page
    const showMoreBtn = document.getElementById('show-more-btn');
    if (showMoreBtn && paginationConfig.currentPage >= paginationConfig.totalPages) {
        showMoreBtn.style.display = 'none';
    }
}


// Change page function (for pagination controls)
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
    const shopGrid = document.querySelector('.y-l-shop-grid') || document.querySelector('.y-l-products-grid');
    if (shopGrid) {
        shopGrid.scrollIntoView({ behavior: 'smooth' });
    }
}

// Make changePage function globally accessible
window.changePage = changePage;

// Initialize range slider functionality
function initializePriceRangeSlider() {
    const minSlider = document.getElementById('min-price-slider');
    const maxSlider = document.getElementById('max-price-slider');
    const minValue = document.getElementById('min-value');
    const maxValue = document.getElementById('max-value');
    const track = document.querySelector('.y-c-slider-track');

    if (!minSlider || !maxSlider || !minValue || !maxValue || !track) {
        // If using the new slider, these elements won't exist.
        // The new slider is initialized in its own class (range-slider.js)
        return;
    }

    function updateSlider() {
        const min = parseInt(minSlider.value);
        const max = parseInt(maxSlider.value);

        if (min > max) {
            // Prevent crossover
            if (this === minSlider) {
                minSlider.value = max;
            } else {
                maxSlider.value = min;
            }
            return;
        }

        // Calculate percentages for the colored track
        const minPercent = ((min - minSlider.min) / (minSlider.max - minSlider.min)) * 100;
        const maxPercent = ((max - minSlider.min) / (minSlider.max - minSlider.min)) * 100;

        // Update the track's colored portion
        track.style.setProperty('--start', minPercent + '%');
        track.style.setProperty('--end', maxPercent + '%');

        // Update displayed values
        minValue.textContent = min;
        maxValue.textContent = max;
    }

    // Initialize the sliders
    updateSlider();

    // Add event listeners
    minSlider.addEventListener('input', updateSlider);
    maxSlider.addEventListener('input', updateSlider);
}

// Initialize Mega Menu Dropdowns for Filter Sidebar
function initializeMegaMenuDropdowns() {
    const megaMenuTitles = document.querySelectorAll('.y-c-mega-menu-title');

    megaMenuTitles.forEach(title => {
        const list = title.nextElementSibling;

        if (list && list.classList.contains('y-c-mega-menu-list')) {
            // Check if we're in mobile mode
            function isMobileMode() {
                return window.innerWidth <= 768;
            }

            // Initialize based on screen size
            function initializeList() {
                if (isMobileMode()) {
                    // Mobile: use 'show' class, start closed
                    list.classList.remove('show', 'collapsed');
                } else {
                    // Desktop: use 'collapsed' class, start closed
                    list.classList.add('collapsed');
                    list.classList.remove('show');
                }
            }

            // Initialize on load
            initializeList();

            // Handle click events
            title.addEventListener('click', function (e) {
                e.preventDefault();

                // Toggle active state for arrow rotation
                title.classList.toggle('active');

                // Toggle list visibility based on screen size
                if (isMobileMode()) {
                    // Mobile mode: toggle 'show' class
                    list.classList.toggle('show');
                } else {
                    // Desktop mode: toggle 'collapsed' class
                    list.classList.toggle('collapsed');
                }
            });

            // Handle window resize to reinitialize
            window.addEventListener('resize', function () {
                // Reset active states on resize
                title.classList.remove('active');
                initializeList();
            });
        }
    });

    // Shop menu dropdowns (Category and Sort)
    const shopMenuButtons = document.querySelectorAll('.y-c-shop-menu-button');

    shopMenuButtons.forEach(button => {
        const dropdown = button.nextElementSibling;

        // Handle click on shop menu buttons
        button.addEventListener('click', function (e) {
            e.preventDefault();

            // Toggle active class on button for arrow rotation
            this.classList.toggle('active');

            // Toggle active class on dropdown to show/hide
            if (dropdown) {
                dropdown.classList.toggle('active');
            }
        });
    });

    // Handle clicks outside to close the dropdown
    document.addEventListener('click', function (e) {
        shopMenuButtons.forEach(button => {
            const dropdown = button.nextElementSibling;

            // If click is outside the menu
            if (!button.contains(e.target) && !dropdown.contains(e.target)) {
                button.classList.remove('active');
                dropdown.classList.remove('active');
            }
        });
    });
}

// Initialize custom dropdowns
function initializeCustomDropdowns() {
    const dropdowns = document.querySelectorAll('.y-c-dropdown');

    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.y-c-dropdown-toggle');
        const menu = dropdown.querySelector('.y-c-dropdown-menu');
        const selected = dropdown.querySelector('.y-c-dropdown-selected');
        const options = dropdown.querySelectorAll('.y-c-dropdown-menu button');

        // Toggle dropdown on click
        toggle.addEventListener('click', (e) => {
            e.stopPropagation();

            // Close all other dropdowns
            dropdowns.forEach(otherDropdown => {
                if (otherDropdown !== dropdown) {
                    otherDropdown.classList.remove('active');
                }
            });

            dropdown.classList.toggle('active');
        });

        // Handle option selection
        options.forEach(option => {
            option.addEventListener('click', () => {
                const value = option.getAttribute('data-value');
                const text = option.textContent;

                // Update toggle button
                toggle.setAttribute('data-value', value);
                selected.textContent = text;

                // Close dropdown
                dropdown.classList.remove('active');

                // Trigger change event for compatibility with existing code
                const changeEvent = new CustomEvent('change', {
                    detail: { value, text }
                });
                toggle.dispatchEvent(changeEvent);
            });
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', () => {
        dropdowns.forEach(dropdown => {
            dropdown.classList.remove('active');
        });
    });
}

// Initialize mobile filter functionality
function initializeMobileFilter() {
    const filterToggle = document.getElementById('mobile-filter-toggle');
    const filterColumn = document.querySelector('.y-l-filter-column');

    if (!filterToggle || !filterColumn) {
        return; // Elements not found, probably not on category page
    }

    // Toggle filter visibility
    function toggleFilter() {
        filterToggle.classList.toggle('active');
        filterColumn.classList.toggle('active');
    }

    // Close filter
    function closeFilter() {
        filterToggle.classList.remove('active');
        filterColumn.classList.remove('active');
    }

    // Event listeners
    filterToggle.addEventListener('click', function (e) {
        e.stopPropagation();
        toggleFilter();
    });

    // Close filter when clicking outside
    document.addEventListener('click', function (e) {
        if (!filterColumn.contains(e.target) && !filterToggle.contains(e.target)) {
            closeFilter();
        }
    });

    // Close filter on escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && filterColumn.classList.contains('active')) {
            closeFilter();
        }
    });

    // Handle window resize
    window.addEventListener('resize', function () {
        if (window.innerWidth > 768) {
            closeFilter();
        }
    });

    // Handle filter dropdown clicks (both mobile and desktop)
    const filterButtons = document.querySelectorAll('.y-c-filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            const dropdown = this.parentElement;

            // Toggle the clicked dropdown
            dropdown.classList.toggle('active');

            // Close other dropdowns
            filterButtons.forEach(otherButton => {
                if (otherButton !== button) {
                    otherButton.parentElement.classList.remove('active');
                }
            });
        });
    });

    // Close filter dropdowns when clicking outside
    document.addEventListener('click', function (e) {
        const clickedInsideFilter = e.target.closest('.y-c-filter-dropdown');

        if (!clickedInsideFilter) {
            filterButtons.forEach(button => {
                button.parentElement.classList.remove('active');
            });
        }
    });
}