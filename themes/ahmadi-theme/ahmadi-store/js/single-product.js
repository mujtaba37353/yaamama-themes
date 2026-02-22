'use strict';

document.addEventListener('DOMContentLoaded', () => {
    initializeProductDetailsPage();
    generateProducts();
});

function initializeProductDetailsPage() {
    try {
        let productData = JSON.parse(localStorage.getItem('selectedProduct'));

        // If no product data in localStorage, try to use a default product
        if (!productData) {
            // Try to get the first product from the products array if available
            if (typeof products !== 'undefined' && products.length > 0) {
                productData = products[0];
                console.info('Using first product from products array as fallback');
            } else {
                // If still no product data, show error
                handleMissingProduct();
                return;
            }
        }

        populateProductDetails(productData);
        setupQuantityControls();
        setupStockDisplay(productData.stock);
        setupReviewSection();
        setupAddToCart(productData);
        setupFavoriteButton(productData);
    } catch (error) {
        console.error('Error initializing product page:', error);
        handleMissingProduct();
    }
}

function handleMissingProduct() {
    const productContainer = document.querySelector('.y-c-Element-row, [data-y="product-container"]');
    if (productContainer) {
        productContainer.innerHTML = `
            <div class="y-c-Element-col" style="text-align: center; width: 100%;">
                <h1>خطأ</h1>
                <p>لم يتم العثور على المنتج. الرجاء العودة إلى <a href="../shop-archive/layout.html">صفحة المنتجات</a> والمحاولة مرة أخرى.</p>
            </div>
        `;
    }

    // Hide reviews section
    const reviewsSection = document.querySelector('.y-c-reviews, [data-y="reviews-section"]');
    if (reviewsSection) {
        reviewsSection.style.setProperty('display', 'none', 'important');
    }

    // Hide related products if they exist
    const relatedProducts = document.querySelector('.y-c-products-section, [data-y="related-products-section"]');
    if (relatedProducts) {
        relatedProducts.style.display = 'none';
    }
}

function populateProductDetails(product) {
    const categoryMap = {
        grains: 'حبوب',
        meat: 'لحوم',
        fish: 'أسماك',
        oils: 'زيوت وعسل',
        coffee: 'قهوة وشاي',
        dairy: 'منتجات الألبان'
    };

    // Query all possible selectors including data attributes
    const productImage = document.querySelector('.y-c-Element-row img, [data-y="product-image"]');
    const productTitle = document.querySelector('.y-c-Element-col h1, [data-y="product-title"]');
    const productPrice = document.querySelector('.y-c-Element-col .price, .y-c-price, [data-y="product-price"]');
    const productIdElement = document.querySelector('.y-c-details p:nth-of-type(1), [data-y="product-id"]');
    const productCategoryElement = document.querySelector('.y-c-details p:nth-of-type(2), [data-y="product-category"]');
    const reviewTitle = document.querySelector('.y-c-be-first, [data-y="first-review-text"]');

    // Update elements if they exist
    if (productImage) productImage.src = product.image || '/assets/image-placeholder.png';
    if (productImage) productImage.alt = product.name || '';
    if (productTitle) productTitle.textContent = product.name || '';
    if (productPrice) productPrice.textContent = product.price || '';
    if (productIdElement) productIdElement.textContent = `رمز المنتج: ${product.id || 'N/A'}`;
    if (productCategoryElement) {
        const categoryText = product.category ? (categoryMap[product.category] || product.category) : 'غير مصنف';
        productCategoryElement.textContent = `التصنيف: ${categoryText}`;
    }
    if (reviewTitle) reviewTitle.textContent = `كن أول من يقيم "${product.name || ''}"`;
}

function setupStockDisplay(stockNumber) {
    const stockEl = document.getElementById('stock') || document.querySelector('[data-y="product-stock-display"]');
    const addToCartButton = document.querySelector('.y-c-add-cart, [data-y="add-to-cart-btn"]');
    if (!stockEl) return;

    // Handle undefined or null stock values
    stockNumber = typeof stockNumber === 'number' ? stockNumber : 0;

    stockEl.textContent = `${stockNumber} متوفر في المخزون`;
    stockEl.className = ''; // Reset classes

    if (stockNumber > 5) {
        stockEl.classList.add('y-c-stock');
    } else if (stockNumber > 0 && stockNumber <= 5) {
        stockEl.classList.add('y-c-stock-low', 'stock-about-to-end');
    } else {
        stockEl.classList.add('y-c-out-of-stock');
        if (addToCartButton) {
            addToCartButton.textContent = 'نفذت الكمية';
            addToCartButton.disabled = true;
        }
    }
}

function setupQuantityControls() {
    const plusBtn = document.querySelector('.y-c-quantity .y-c-plus-btn, .plus-btn, [data-y="quantity-plus"]');
    const minusBtn = document.querySelector('.y-c-quantity .y-c-minus-btn, .minus-btn, [data-y="quantity-minus"]');
    const input = document.querySelector('.y-c-quantity input, [data-y="quantity-input"]');

    if (!plusBtn || !minusBtn || !input) return;

    plusBtn.addEventListener('click', () => {
        input.value = parseInt(input.value, 10) + 1;
    });

    minusBtn.addEventListener('click', () => {
        const currentValue = parseInt(input.value, 10);
        if (currentValue > 1) {
            input.value = currentValue - 1;
        }
    });
}

function setupReviewSection() {
    const starsContainer = document.querySelector('.y-c-rating .y-c-stars, .y-c-rating [data-y="star-rating-container"]');
    const reviewTextarea = document.querySelector('.y-c-reviews textarea, [data-y="review-textarea"]');
    const submitReviewButton = document.querySelector('.y-c-submit-review, [data-y="submit-review-btn"]');

    if (!starsContainer || !reviewTextarea || !submitReviewButton) return;

    const stars = starsContainer.querySelectorAll('.fa-star');
    let currentRating = 0;

    const setRating = (rating) => {
        stars.forEach((star, index) => {
            star.classList.toggle('y-c-active-stars', index < rating);
            star.classList.toggle('fa-solid', index < rating);
            star.classList.toggle('fa-regular', index >= rating);
        });
    };

    const updateSubmitButtonVisibility = () => {
        const hasContent = currentRating > 0 || reviewTextarea.value.trim() !== '';
        submitReviewButton.classList.toggle('y-c-submit-review-show', hasContent);
    };

    stars.forEach((star, index) => {
        star.addEventListener('mouseover', () => setRating(index + 1));
        star.addEventListener('click', () => {
            currentRating = index + 1;
            updateSubmitButtonVisibility();
        });
    });

    starsContainer.addEventListener('mouseleave', () => setRating(currentRating));
    reviewTextarea.addEventListener('input', updateSubmitButtonVisibility);
}

function setupAddToCart(product) {
    const addToCartButton = document.querySelector('.y-c-add-cart, [data-y="add-to-cart-btn"]');
    if (!addToCartButton || addToCartButton.disabled) return;

    addToCartButton.addEventListener('click', () => {
        const quantityInput = document.querySelector('.y-c-quantity input, [data-y="quantity-input"]');
        const quantity = parseInt(quantityInput.value, 10) || 1;

        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const existingItem = cart.find(item => item.id === product.id || item.name === product.name);

        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cart.push({
                id: product.id || `SKU-${Date.now()}`,
                name: product.name,
                price: product.price,
                quantity: quantity,
                image: product.image || '/assets/image-placeholder.png'
            });
        }

        localStorage.setItem('cart', JSON.stringify(cart));

        if (typeof updateCartDisplay === 'function') updateCartDisplay();

        addToCartButton.textContent = 'تمت الإضافة!';
        addToCartButton.disabled = true;
        setTimeout(() => {
            addToCartButton.textContent = 'اضافة إلى السلة';
            addToCartButton.disabled = false;
        }, 2000);
    });
}

function setupFavoriteButton(product) {
    const favButton = document.querySelector('.y-c-fav, .fav, [data-y="favorite-btn"]');
    if (!favButton) return;

    favButton.addEventListener('click', () => {
        const icon = favButton.querySelector('i');
        const favText = favButton.querySelector('.y-c-fav-text, .fav-text, [data-y="fav-text"]');
        const isFavorite = favButton.classList.toggle('y-c-active');

        icon.classList.toggle('fa-regular', !isFavorite);
        icon.classList.toggle('fa-solid', isFavorite);
        if (favText) {
            favText.textContent = isFavorite ? ' تمت الإضافة إلى المفضلة' : ' اضافة إلى المفضلة';
        }

        // Save favorite products to localStorage
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        if (isFavorite) {
            if (!favorites.some(item => item.id === product.id || item.name === product.name)) {
                favorites.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    image: product.image
                });
            }
        } else {
            favorites = favorites.filter(item => item.id !== product.id && item.name !== product.name);
        }
        localStorage.setItem('favorites', JSON.stringify(favorites));
    });
}
