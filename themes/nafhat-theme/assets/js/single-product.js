/**
 * Single Product Page JavaScript
 * Handles gallery thumbnails and quantity controls
 */
document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // Gallery Thumbnail Click Handler
    const initGallery = () => {
        const thumbs = document.querySelectorAll('.pd-thumb-stack .thumb');
        const mainImage = document.getElementById('pd-main-image');

        if (!thumbs.length || !mainImage) return;

        thumbs.forEach(thumb => {
            thumb.addEventListener('click', function() {
                // Remove active class from all thumbs
                thumbs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked thumb
                this.classList.add('active');
                
                // Update main image
                const newImageUrl = this.getAttribute('data-image');
                if (newImageUrl) {
                    mainImage.style.opacity = '0';
                    setTimeout(() => {
                        mainImage.src = newImageUrl;
                        mainImage.style.opacity = '1';
                    }, 150);
                }
            });
        });
    };

    // Quantity Control Handler
    const initQuantityControl = () => {
        const qtyControl = document.querySelector('.pd-qty-control');
        if (!qtyControl) return;

        const minusBtn = qtyControl.querySelector('.qty-minus');
        const plusBtn = qtyControl.querySelector('.qty-plus');
        const qtyInput = qtyControl.querySelector('.qty-value, input[name="quantity"]');

        if (!minusBtn || !plusBtn || !qtyInput) return;

        const min = parseInt(qtyInput.getAttribute('min')) || 1;
        const maxAttr = qtyInput.getAttribute('max');
        const max = maxAttr ? parseInt(maxAttr) : 9999; // If no max, allow up to 9999

        minusBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let currentVal = parseInt(qtyInput.value) || 1;
            if (currentVal > min) {
                qtyInput.value = currentVal - 1;
                qtyInput.dispatchEvent(new Event('change'));
            }
        });

        plusBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let currentVal = parseInt(qtyInput.value) || 1;
            if (max <= 0 || currentVal < max) {
                qtyInput.value = currentVal + 1;
                qtyInput.dispatchEvent(new Event('change'));
            }
        });

        // Validate input on change
        qtyInput.addEventListener('change', function() {
            let val = parseInt(this.value) || min;
            if (val < min) val = min;
            if (max > 0 && val > max) val = max;
            this.value = val;
        });
    };

    // Star Rating Interactive (for review form)
    const initStarRating = () => {
        const starsContainer = document.getElementById('star-rating-input');
        const ratingInput = document.getElementById('rating-value');
        
        if (!starsContainer) return;

        const stars = starsContainer.querySelectorAll('.fa-star');
        let selectedRating = 0;
        
        // Initialize all stars as grey
        stars.forEach(star => {
            star.style.cursor = 'pointer';
            star.style.color = '#ddd';
        });
        
        stars.forEach((star) => {
            const rating = parseInt(star.getAttribute('data-rating'));
            
            star.addEventListener('click', () => {
                selectedRating = rating;
                
                // Update hidden input value
                if (ratingInput) {
                    ratingInput.value = rating;
                }
                
                // Update visual stars - fill from right to left based on rating
                stars.forEach((s) => {
                    const starRating = parseInt(s.getAttribute('data-rating'));
                    if (starRating <= rating) {
                        s.classList.add('filled');
                        s.style.color = '#f5a623';
                    } else {
                        s.classList.remove('filled');
                        s.style.color = '#ddd';
                    }
                });
            });

            star.addEventListener('mouseenter', () => {
                // Highlight stars on hover
                stars.forEach((s) => {
                    const starRating = parseInt(s.getAttribute('data-rating'));
                    if (starRating <= rating) {
                        s.style.color = '#f5a623';
                    }
                });
            });

            star.addEventListener('mouseleave', () => {
                // Restore to selected state
                stars.forEach((s) => {
                    const starRating = parseInt(s.getAttribute('data-rating'));
                    if (starRating <= selectedRating) {
                        s.style.color = '#f5a623';
                    } else {
                        s.style.color = '#ddd';
                    }
                });
            });
        });
    };

    // Initialize all
    initGallery();
    initQuantityControl();
    initStarRating();
});
