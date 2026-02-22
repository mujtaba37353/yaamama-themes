// Updated product data to reflect car rentals and special offers
const products = [
    // --- OFFER PRODUCTS ---
    {
        id: 1, name: "نيسان صني", year: 2025,
        price: 1700.00, originalPrice: 2000.00,
        image: "/assets/product.png", category: "سيدان صغيرة",
        passengers: 4, bags: 3, transmission: 'A', doors: 4,
        hasDiscount: true, offerDuration: 7, freeKm: 200
    },
    {
        id: 2, name: "هيونداي اكسنت", year: 2025,
        price: 1300.00, originalPrice: 1500.00,
        image: "/assets/product.png", category: "سيدان صغيرة",
        passengers: 4, bags: 2, transmission: 'A', doors: 4,
        hasDiscount: true, offerDuration: 7, freeKm: 200
    },
    {
        id: 3, name: "إم جي 5", year: 2024,
        price: 1450.00, originalPrice: 1700.00,
        image: "/assets/mg.png", category: "سيدان & كومباكت",
        passengers: 5, bags: 3, transmission: 'A', doors: 4,
        hasDiscount: true, offerDuration: 7, freeKm: 200
    },

    // --- REGULAR PRODUCTS ---
    {
        id: 4, name: "شانجان يوني-في", year: 2025,
        price: 350.00, image: "/assets/small-car.png", category: "السيارات العائلية",
        passengers: 5, bags: 4, transmission: 'A', doors: 4,
        hasDiscount: false
    },
    {
        id: 5, name: "تويوتا يارس", year: 2025,
        price: 280.00, image: "/assets/product.png", category: "سيدان صغيرة",
        passengers: 4, bags: 2, transmission: 'A', doors: 4,
        hasDiscount: false
    },
    {
        id: 6, name: "هيونداي إلنترا", year: 2025,
        price: 310.00, image: "/assets/mg.png", category: "سيدان & كومباكت",
        passengers: 5, bags: 3, transmission: 'A', doors: 4,
        hasDiscount: false
    },
    {
        id: 7, name: "تويوتا كامري", year: 2025,
        price: 400.00, image: "/assets/product.png", category: "سيدان & كومباكت",
        passengers: 5, bags: 4, transmission: 'A', doors: 4,
        hasDiscount: false
    },
    {
        id: 8, name: "جي إيه سي GA4", year: 2024,
        price: 260.00, image: "/assets/small-car.png", category: "سيدان صغيرة",
        passengers: 5, bags: 3, transmission: 'A', doors: 4,
        hasDiscount: false
    },
    {
        id: 9, name: "تويوتا راش", year: 2025,
        price: 450.00, image: "/assets/product.png", category: "السيارات العائلية",
        passengers: 7, bags: 4, transmission: 'A', doors: 4,
        hasDiscount: false
    },
    {
        id: 10, name: "هيونداي كريتا", year: 2025,
        price: 380.00, image: "/assets/mg.png", category: "السيارات العائلية",
        passengers: 5, bags: 4, transmission: 'A', doors: 4,
        hasDiscount: false
    },
    {
        id: 11, name: "شانجان السفن", year: 2025,
        price: 230.00, image: "/assets/small-car.png", category: "سيدان صغيرة",
        passengers: 4, bags: 2, transmission: 'A', doors: 4,
        hasDiscount: false
    },
    {
        id: 12, name: "إم جي RX5", year: 2024,
        price: 420.00, image: "/assets/product.png", category: "السيارات العائلية",
        passengers: 5, bags: 4, transmission: 'A', doors: 4,
        hasDiscount: false
    },
    {
        id: 13, name: "نيسان كيكس", year: 2025,
        price: 330.00, image: "/assets/mg.png", category: "سيدان & كومباكت",
        passengers: 5, bags: 3, transmission: 'A', doors: 4,
        hasDiscount: false
    },
    {
        id: 14, name: "هيونداي سوناتا", year: 2025,
        price: 430.00, image: "/assets/small-car.png", category: "سيدان & كومباكت",
        passengers: 5, bags: 4, transmission: 'A', doors: 4,
        hasDiscount: false
    },
];

// Updated function to generate the new product card HTML
function createProductCard(product) {

    // Main card class is y-c-product-card
    return `
    <li class="y-c-product-card" data-y="product-card-${product.id}">
        <div class="y-c-card-layout">
            <div class="y-c-card-pricing ${product.hasDiscount ? 'y-c-offer-pricing' : ''}">
                ${product.hasDiscount ? `
                <div class="y-c-offer-price-details">
                    <div class="y-c-card-price-label" data-y="product-price-label-${product.id}">
                        السعر ل ${product.offerDuration || 7} يوم
                    </div>
                    <div class="y-c-offer-kms" data-y="product-kms-${product.id}">
                        مجاني كم ${product.freeKm || 0}
                    </div>
                    <div class="y-c-card-price-old" data-y="product-old-price-${product.id}">
                        ${(product.originalPrice || (product.price / 0.85)).toFixed(2)} ريال
                    </div>
                    <div class="y-c-card-price-amount" data-y="product-price-container-${product.id}">
                        ${product.price.toFixed(2)}
                        <span class="y-c-card-price-currency">ريال</span>
                    </div>
                </div>
                ` : `
                <div>
                    <div class="y-c-card-price-label" data-y="product-price-label-${product.id}">
                        السعر ل 1 يوم
                    </div>
                    <div class="y-c-card-price-amount" data-y="product-price-container-${product.id}">
                        ${product.price.toFixed(2)}
                        <span class="y-c-card-price-currency">ريال</span>
                    </div>
                </div>
                `}
               <span> <a href="/templates/store/layout.html" class="y-c-basic-btn" data-y="product-book-btn-${product.id}">احجز الان</a></span>
            </div>

            <div class="y-c-card-info">
                <div>
                    <div class="y-c-card-category" data-y="product-category-${product.id}">
                        <i class="fa-solid fa-car"></i>
                        ${product.category}
                    </div>
                    <h3 class="y-c-card-name" data-y="product-title-${product.id}">${product.name} ${product.year}</h3>
                    <div class="y-c-card-similar" data-y="product-similar-${product.id}">أو مشابهة</div>
                </div>
                <div class="y-c-card-features" data-y="product-features-${product.id}">
                    <span class="y-c-card-feature-item" title="ركاب">
                        <i class="fa-solid fa-users"></i>
                        ${product.passengers}
                    </span>
                    <span class="y-c-card-feature-item" title="حقائب">
                        <i class="fa-solid fa-suitcase"></i>
                        ${product.bags}
                    </span>
                    <span class="y-c-card-feature-item" title="ناقل حركة">
                        <i class="fa-solid fa-gear"></i>
                        ${product.transmission}
                    </span>
                    <span class="y-c-card-feature-item" title="أبواب">
                        <i class="fa-solid fa-door-closed"></i>
                        ${product.doors}
                    </span>
                </div>
            </div>

            <div class="y-c-card-image">
                    <img src="${product.image}" alt="${product.name}" loading="lazy" data-y="product-image-${product.id}">
            </div>
        </div>
    </li>
    `;
}


// --- Rest of the functions (renderProductCards, getProductsByCategory, etc.) remain the same ---
// Function to render product cards into any element with the class 'y-l-products-grid'
function renderProductCards(productsToRender, targetContainerId, limit = null) {
    const container = document.getElementById(targetContainerId);
    if (!container) {
        console.error(`Container with ID ${targetContainerId} not found.`);
        return;
    }
    container.innerHTML = ''; // Clear previous content

    const cardFunction = createProductCard; // Use the single, unified card function

    const itemsToShow = limit ? Math.min(limit, productsToRender.length) : productsToRender.length;
    for (let i = 0; i < itemsToShow; i++) {
        if (productsToRender[i]) {
            container.innerHTML += cardFunction(productsToRender[i]);
        }
    }
}


// Function to get products by category
function getProductsByCategory(category) {
    if (category === 'all' || !category) {
        return products;
    }
    return products.filter(product => product.category === category);
}

// Function to get product by ID
function getProductById(id) {
    // Use == to allow comparison between number and string if needed
    return products.find(product => product.id == id);
}

// --- Cart Functionality ---
// Note: This is still "add to cart" logic, which might be different from "book now".
// For now, we keep the cart logic but the button text is changed.
let cart = JSON.parse(localStorage.getItem('cart')) || [];

function addToCart(productId, quantity = 1) {
    const product = getProductById(productId);
    if (product) {
        const existingItemIndex = cart.findIndex(item => item.id === productId);
        if (existingItemIndex > -1) {
            cart[existingItemIndex].quantity += quantity;
        } else {
            // Store only necessary info in cart
            cart.push({
                id: product.id,
                name: product.name,
                price: product.price,
                image: product.image,
                quantity: quantity
            });
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartBadge();

        // Show success message
        const message = quantity > 1
            ? `تم إضافة ${quantity} قطع من ${product.name} للسلة بنجاح!`
            : `تم إضافة ${product.name} للسلة بنجاح!`;
        console.log(message);


        // Trigger custom event for other components
        document.dispatchEvent(new CustomEvent('cartUpdated', {
            detail: { productId, quantity, cart }
        }));

        return true;
    }
    console.error(`Product with ID ${productId} not found.`);
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

        console.log(`تم حذف ${removedItem.name} من السلة`);


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
    const itemIndex = cart.findIndex(item => item.id === productId);
    if (itemIndex > -1) {
        if (newQuantity <= 0) {
            return removeFromCart(productId);
        }

        cart[itemIndex].quantity = newQuantity;
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
    console.log('تم إفراغ السلة');

    // Trigger custom event
    document.dispatchEvent(new CustomEvent('cartCleared', { detail: { cart } }));
}

// Function to get cart total
function getCartTotal() {
    return cart.reduce((total, item) => {
        // Ensure price is a number before calculation
        const price = Number(item.price) || 0;
        return total + (price * item.quantity);
    }, 0);
}


// Function to get cart items count
function getCartItemsCount() {
    return cart.reduce((total, item) => total + item.quantity, 0);
}

// Enhanced updateCartBadge with animation
function updateCartBadge() {
    // Use timeout to ensure header is loaded
    setTimeout(() => {
        const badge = document.getElementById('y-c-cart-badge'); // Use ID selector
        if (badge) {
            const totalItems = getCartItemsCount();
            const currentCount = parseInt(badge.textContent) || 0;

            badge.textContent = totalItems;
            badge.style.display = totalItems > 0 ? 'flex' : 'none';

            // Add animation for new items
            if (totalItems > currentCount) {
                badge.classList.add('animate-bounce');
                // Use standard CSS animation or remove if not defined
                // setTimeout(() => badge.classList.remove('animate-bounce'), 600);
            }
        } else {
            // console.warn('Cart badge element not found yet.');
        }
    }, 150); // Adjust delay if needed
}

// Initialize Add to Cart Buttons
function initializeAddToCartButtons() {
    document.addEventListener('click', function (event) {
        // Updated selector to find the new button class
        const button = event.target.closest('.y-c-basic-btn[data-y^="product-book-btn-"]');

        if (button && !button.disabled) { // Check if button is not already processing
            event.preventDefault();

            // Extract product ID from data-y attribute
            const dataY = button.getAttribute('data-y');
            const productId = parseInt(dataY.split('-').pop());

            if (productId) {
                // The button in the new design doesn't have an icon, so we just change text
                button.disabled = true;
                const originalContent = button.innerHTML; // Store original content
                button.innerHTML = '... جاري الحجز'; // Loading state

                setTimeout(() => {
                    // We'll use the 'addToCart' function for now, though this might be "BookNow"
                    const success = addToCart(productId);

                    if (success) {
                        button.innerHTML = 'تم الحجز';
                        button.classList.add('success'); // Add success class for styling

                        // Revert after a delay
                        setTimeout(() => {
                            button.innerHTML = originalContent;
                            button.classList.remove('success');
                            button.disabled = false;
                        }, 1500);
                    } else {
                        // Handle error: revert button and log
                        button.innerHTML = originalContent; // Revert on error
                        button.disabled = false;
                        console.error('حدث خطأ أثناء الحجز');
                    }
                }, 300); // Simulate network delay
            } else {
                console.error('Product ID not found on button:', button);
            }
        }
    });
}


// Expose necessary functions globally via productUtils
window.productUtils = {
    products,
    cart,
    createProductCard,
    renderProductCards,
    getProductsByCategory,
    getProductById,
    addToCart,
    removeFromCart,
    updateCartQuantity,
    clearCart,
    getCartTotal,
    getCartItemsCount,
    updateCartBadge,
    initializeAddToCartButtons
};

// Initial setup on DOMContentLoaded
document.addEventListener('DOMContentLoaded', function () {
    // Initial badge update might need slight delay if header loads async
    window.productUtils.updateCartBadge();
    // Initialize delegated add-to-cart listener so buttons (including dynamically
    // rendered ones) will respond to clicks.
    window.productUtils.initializeAddToCartButtons();
});