document.addEventListener('DOMContentLoaded', () => {
    // --- Accordion Functionality ---
    const accordionHeaders = document.querySelectorAll('.y-c-accordion-header');

    accordionHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const item = header.parentElement;
            const icon = header.querySelector('.y-c-accordion-icon');
            const content = item.querySelector('.y-c-accordion-content');

            // Toggle active class on the item
            const isActive = item.classList.contains('active');

            if (isActive) {
                item.classList.remove('active');
                icon.classList.remove('fa-minus');
                icon.classList.add('fa-plus');
            } else {
                item.classList.add('active');
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-minus');
            }
        });
    });

    // Initialize Accordion Icons
    document.querySelectorAll('.y-c-accordion-item').forEach(item => {
        const icon = item.querySelector('.y-c-accordion-icon');
        if (item.classList.contains('active')) {
            icon.classList.add('fa-minus');
            icon.classList.remove('fa-plus');
        } else {
            icon.classList.add('fa-plus');
            icon.classList.remove('fa-minus');
        }
    });

    // --- Quantity Controls ---
    const decreaseBtn = document.getElementById('decrease-quantity');
    const increaseBtn = document.getElementById('increase-quantity');
    const quantityInput = document.querySelector('.y-c-quantity-input');

    if (decreaseBtn && increaseBtn && quantityInput) {
        decreaseBtn.addEventListener('click', () => {
            let value = parseInt(quantityInput.value);
            if (value > 1) {
                quantityInput.value = value - 1;
            }
        });

        increaseBtn.addEventListener('click', () => {
            let value = parseInt(quantityInput.value);
            const max = parseInt(quantityInput.getAttribute('max')) || 10;
            if (value < max) {
                quantityInput.value = value + 1;
            }
        });

        quantityInput.addEventListener('change', () => {
            let value = parseInt(quantityInput.value);
            const max = parseInt(quantityInput.getAttribute('max')) || 10;
            if (isNaN(value) || value < 1) {
                quantityInput.value = 1;
            } else if (value > max) {
                quantityInput.value = max;
            }
        });
    }

    // --- Rating Stars Interaction ---
    const ratingStarsContainer = document.getElementById('user-rating');
    if (ratingStarsContainer) {
        const stars = ratingStarsContainer.querySelectorAll('i');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = parseInt(star.getAttribute('data-rating'));

                // Update UI
                stars.forEach(s => {
                    const sRating = parseInt(s.getAttribute('data-rating'));
                    if (sRating <= rating) {
                        s.classList.remove('far');
                        s.classList.add('fas');
                    } else {
                        s.classList.remove('fas');
                        s.classList.add('far');
                    }
                });
            });

            // Optional: Hover effect
            star.addEventListener('mouseover', () => {
                const rating = parseInt(star.getAttribute('data-rating'));
                stars.forEach(s => {
                    const sRating = parseInt(s.getAttribute('data-rating'));
                    if (sRating <= rating) {
                        s.style.color = 'var(--y-color-primary-hover)';
                    }
                });
            });

            star.addEventListener('mouseout', () => {
                stars.forEach(s => {
                    s.style.color = ''; // Reset inline style
                });
            });
        });
    }

    // --- Favorite Toggle Functionality ---
    // Use the same functions from products.js if available, otherwise use AJAX directly
    
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
    
    // Show notification (reuse from products.js if available)
    function showNotification(message, type = 'info') {
        if (window.productUtils && typeof window.productUtils.showNotification === 'function') {
            window.productUtils.showNotification(message, type);
            return;
        }
        // Fallback notification
        alert(message);
    }
    
    // Toggle favorite via AJAX (reuse from products.js if available)
    function toggleFavorite(productId, callback) {
        // Use products.js function if available
        if (window.productUtils && typeof window.productUtils.toggleFavorite === 'function') {
            return window.productUtils.toggleFavorite(productId, callback);
        }
        
        // Fallback: direct AJAX call
        if (!isUserLoggedIn()) {
            const loginUrl = '/my-account/?action=login';
            showNotification('يرجى تسجيل الدخول لإضافة المنتجات إلى المفضلة', 'error');
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
        
        jQuery.ajax({
            url: technoSouqAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'techno_souq_toggle_favorite',
                product_id: Number(productId),
                nonce: technoSouqAjax.nonce
            },
            success: function(response) {
                if (response.success && response.data) {
                    showNotification(response.data.message, 'success');
                    if (callback) callback(response.data.is_favorite);
                    document.dispatchEvent(new CustomEvent('favoritesUpdated'));
                } else {
                    showNotification(response.data && response.data.message 
                        ? response.data.message 
                        : 'حدث خطأ أثناء تحديث المفضلة', 'error');
                    if (callback) callback(false);
                }
            },
            error: function() {
                showNotification('حدث خطأ أثناء تحديث المفضلة. يرجى المحاولة مرة أخرى.', 'error');
                if (callback) callback(false);
            }
        });
    }
    
    // Check if product is favorite (use products.js if available)
    function isFavoriteProduct(productId) {
        if (window.productUtils && typeof window.productUtils.isFavoriteProduct === 'function') {
            return window.productUtils.isFavoriteProduct(productId);
        }
        return false; // Will be updated after loading favorites
    }

    // Initialize favorite button state
    const favBtn = document.querySelector('.y-c-single-fav-btn');
    if (favBtn) {
        const productId = favBtn.getAttribute('data-product-id');
        
        // Set initial state - wait for products.js to load favorites
        function checkInitialState() {
            if (productId && isFavoriteProduct(productId)) {
                favBtn.classList.add('active');
            } else {
                favBtn.classList.remove('active');
            }
        }
        
        // Check immediately and also after a delay (to allow products.js to load)
        checkInitialState();
        setTimeout(checkInitialState, 1000);
        
        // Also check when favorites are updated
        document.addEventListener('favoritesUpdated', checkInitialState);
        
        // Handle click
        favBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            if (productId) {
                // Optimistically update UI
                const wasActive = favBtn.classList.contains('active');
                favBtn.classList.toggle('active');
                
                // Toggle favorite via AJAX
                toggleFavorite(productId, function(isFavorite) {
                    // Update UI based on actual result
                    if (isFavorite) {
                        favBtn.classList.add('active');
                    } else {
                        favBtn.classList.remove('active');
                    }
                });
            }
        });
    }

    // --- Image Slider / Dot Navigation Logic ---
    const dots = document.querySelectorAll('.y-c-single-dot');
    const mainImage = document.getElementById('main-product-image');
    const prevBtn = document.querySelector('.y-c-single-slider-prev');
    const nextBtn = document.querySelector('.y-c-single-slider-next');

    // Sample images for demonstration of navigation
    const productImages = [
        '/assets/wash.png',         // Image for dot 0
        '/assets/phone-slider.png', // Image for dot 1 (example)
        '/assets/hero-image.png'    // Image for dot 2 (example)
    ];

    let currentIndex = 0;

    function updateImage(index) {
        if (mainImage && productImages[index]) {
            // Update Active Dot State
            dots.forEach(d => d.classList.remove('active'));
            dots[index].classList.add('active');

            // Navigate: Change Image
            mainImage.style.opacity = '0';
            setTimeout(() => {
                mainImage.src = productImages[index];
                mainImage.style.opacity = '1';
            }, 200);
        }
    }

    // Dot click
    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            currentIndex = parseInt(dot.getAttribute('data-index'));
            updateImage(currentIndex);
        });
    });

    // Prev button
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + productImages.length) % productImages.length;
            updateImage(currentIndex);
        });
    }

    // Next button
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % productImages.length;
            updateImage(currentIndex);
        });
    }
});