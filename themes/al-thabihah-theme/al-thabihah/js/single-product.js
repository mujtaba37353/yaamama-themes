// Al Thabihah/js/single-product.js

document.addEventListener('DOMContentLoaded', function () {
    // Initialize static page functionality
    initGallery();
    initQuantitySelector();
    initAddToCart();
    initRelatedProductsSlider();
});

// --- Image Gallery Logic ---
function initGallery() {
    window.changeImage = function (src) {
        document.getElementById('main-product-image').src = src;

        // Update active state
        const thumbnails = document.querySelectorAll('.y-c-thumbnail');
        thumbnails.forEach(thumb => {
            if (thumb.src === src) thumb.classList.add('active');
            else thumb.classList.remove('active');
        });
    }
}

// --- Quantity Logic ---
function initQuantitySelector() {
    const input = document.getElementById('qty-input');
    const btnMinus = document.getElementById('qty-minus');
    const btnPlus = document.getElementById('qty-plus');

    btnMinus.addEventListener('click', () => {
        let val = parseInt(input.value);
        if (val > 1) input.value = val - 1;
    });

    btnPlus.addEventListener('click', () => {
        let val = parseInt(input.value);
        input.value = val + 1;
    });
}

// --- Add to Cart Logic ---
function initAddToCart() {
    const form = document.getElementById('add-to-cart-form');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const quantity = parseInt(document.getElementById('qty-input').value);

        // Gather Options
        const formData = new FormData(form);
        const options = {};
        formData.forEach((value, key) => {
            options[key] = value;
        });

        // Show success notification
        if (window.productUtils && window.productUtils.showNotification) {
            window.productUtils.showNotification('تم إضافة المنتج للسلة بنجاح!', 'success');
        }

        // Optional: Log selected options for debugging
        console.log('Added to cart with options:', options, 'Quantity:', quantity);
    });
}

// --- Related Products Slider Logic ---
function initRelatedProductsSlider() {
    const track = document.getElementById('related-products-track');
    const prevBtn = document.querySelector('.y-c-slider-prev');
    const nextBtn = document.querySelector('.y-c-slider-next');

    if (!track || !prevBtn || !nextBtn) return;

    // Slider state
    let currentIndex = 0;
    let itemsToShow = getItemsToShow();
    let totalItems = 0;

    function getItemsToShow() {
        if (window.innerWidth <= 480) return 2;
        if (window.innerWidth <= 768) return 3;
        return 4;
    }

    // Load related products
    loadRelatedProducts();

    function loadRelatedProducts() {
        // Check if products are available
        if (typeof products === 'undefined') {
            console.warn('Products not loaded, using fallback products');
            createFallbackProducts();
            return;
        }

        // Convert products to the expected format
        const formattedProducts = products.map(product => ({
            id: product.id,
            name: product.name,
            price: product.price,
            originalPrice: product.oldPrice || null,
            discount: product.oldPrice ? Math.round((1 - product.price / product.oldPrice) * 100) : 0,
            image: product.image,
            isOffer: product.offer || false
        }));

        // Get 8 random products for the slider
        const shuffledProducts = [...formattedProducts].sort(() => 0.5 - Math.random());
        const relatedProducts = shuffledProducts.slice(0, 8);

        displayProducts(relatedProducts);
    }

    function createFallbackProducts() {
        const fallbackProducts = [
            { id: 1, name: "ذبيحة نعيمي 8-10 كجم", price: 750, image: "/assets/product.jpg", isOffer: false },
            { id: 2, name: "تيس كشميري 7-9 كجم", price: 650, image: "/assets/product.jpg", isOffer: false },
            { id: 3, name: "عجل بلدي (ربع)", price: 1200, image: "/assets/product.jpg", isOffer: false },
            { id: 4, name: "قطعيات لحم بقر (1 كجم)", price: 45, image: "/assets/product.jpg", isOffer: false },
            { id: 5, name: "نعيمي مميز", price: 700, originalPrice: 750, discount: 17, image: "/assets/product.jpg", isOffer: true },
            { id: 6, name: "تيس بلدي مخفض", price: 550, originalPrice: 600, discount: 8, image: "/assets/product.jpg", isOffer: true },
            { id: 7, name: "عجل صغير (8 كجم)", price: 800, image: "/assets/product.jpg", isOffer: false },
            { id: 8, name: "لحوم مشكلة", price: 100, originalPrice: 120, discount: 17, image: "/assets/product.jpg", isOffer: true }
        ];
        displayProducts(fallbackProducts);
    }

    function displayProducts(relatedProducts) {
        // Clear existing content
        track.innerHTML = '';

        // Create product cards
        relatedProducts.forEach(product => {
            const card = createProductCard(product);
            track.appendChild(card);
        });

        totalItems = relatedProducts.length;
        updateSliderButtons();

        // Add event delegation for add to cart buttons
        track.addEventListener('click', function (e) {
            if (e.target.closest('.y-c-add-to-cart')) {
                e.preventDefault();
                const button = e.target.closest('.y-c-add-to-cart');
                const productId = button.getAttribute('data-product-id');
                addToCartFromSlider(e, productId);
            }
        });
    }

    function createProductCard(product) {
        const card = document.createElement('li');
        card.className = 'y-c-product-card';
        card.setAttribute('data-y', `product-card-${product.id}`);

        // Truncate product name to max 20 characters and add ellipsis if needed
        const truncatedName = product.name.length > 20
            ? product.name.substring(0, 20) + '...'
            : product.name;

        // Build price HTML with offer support
        let priceHTML = '';
        if (product.isOffer && product.originalPrice) {
            // This is the style for offer products
            priceHTML = `
                <div class="y-c-product-price" data-y="product-price-container-${product.id}">
                    <span class="y-c-old-price" data-y="product-old-price-${product.id}">${product.originalPrice.toFixed(0)}</span>
                    <img src="/assets/coin-sale.png" class="y-c-coin-icon">
                    
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

        card.innerHTML = `
            <a href="/templates/single-product/layout.html?id=${product.id}" class="y-c-card-link" data-y="product-link-${product.id}">
                <div class="y-c-product-image-container" data-y="product-image-container-${product.id}">
                    <img src="${product.image}" alt="${product.name}" class="y-c-product-image" loading="lazy" data-y="product-image-${product.id}">
                </div>
            </a>

            <div class="y-c-product-info" data-y="product-info-${product.id}">
                <h3 class="y-c-product-title" title="${product.name}" data-y="product-title-${product.id}">${truncatedName}</h3>
                
                ${priceHTML}

                <button class="y-c-outline-btn y-c-add-to-cart" data-product-id="${product.id}" data-y="product-add-to-cart-${product.id}">
                    <i class="fas fa-shopping-cart" data-y="cart-icon-${product.id}"></i>
                    اضف للسلة
                </button>
            </div>
        `;

        return card;
    }

    function updateSliderButtons() {
        const maxIndex = Math.max(0, totalItems - itemsToShow);
        prevBtn.disabled = currentIndex <= 0;
        nextBtn.disabled = currentIndex >= maxIndex;
    }

    function slideToIndex(index) {
        const maxIndex = Math.max(0, totalItems - itemsToShow);
        currentIndex = Math.max(0, Math.min(index, maxIndex));

        // Calculate slide distance based on percentage (positive for RTL)
        const slidePercentage = (currentIndex * 100) / itemsToShow;
        track.style.transform = `translateX(${slidePercentage}%)`;

        updateSliderButtons();
    }

    // Event listeners
    prevBtn.addEventListener('click', () => slideToIndex(currentIndex - 1));
    nextBtn.addEventListener('click', () => slideToIndex(currentIndex + 1));

    // Handle window resize
    window.addEventListener('resize', () => {
        const newItemsToShow = getItemsToShow();
        if (newItemsToShow !== itemsToShow) {
            itemsToShow = newItemsToShow;
            slideToIndex(0); // Reset to beginning
        }
    });
}

// Global function for add to cart from slider
window.addToCartFromSlider = function (event, productId) {
    // Handle both direct calls and event delegation
    const button = event && event.target ? event.target :
        document.querySelector(`[data-product-id="${productId}"]`);

    if (!button) return;

    // Get product ID from data attribute if not provided
    if (!productId) {
        productId = button.getAttribute('data-product-id') ||
            button.closest('[data-product-id]')?.getAttribute('data-product-id');
    }

    // Add success state
    button.classList.add('success');
    button.innerHTML = '<i class="fas fa-check"></i> تمت الإضافة';

    // Show notification if available
    if (window.productUtils && window.productUtils.showNotification) {
        window.productUtils.showNotification('تم إضافة المنتج للسلة بنجاح!', 'success');
    }

    // Reset button after delay
    setTimeout(() => {
        button.classList.remove('success');
        button.innerHTML = '<i class="fas fa-shopping-cart"></i> اضف للسلة';
    }, 2000);

    console.log(`Added product ${productId} to cart from slider`);
}