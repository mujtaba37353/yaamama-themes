/**
 * Doctor Reviews JavaScript
 */
(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        const reviewForm = document.getElementById('add-review-form');
        const reviewMessage = document.getElementById('review-message');
        
        if (!reviewForm) return;
        
        // Initialize star rating inputs
        initStarRatings();
        
        // Handle form submission
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(reviewForm);
            formData.append('action', 'add_doctor_review');
            // Get nonce from hidden field
            const nonceField = reviewForm.querySelector('input[name="review_nonce"]');
            if (nonceField) {
                formData.append('nonce', nonceField.value);
            }
            
            // Show loading state
            const submitBtn = reviewForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'جاري الإرسال...';
            reviewMessage.style.display = 'none';
            
            fetch(ajaxurl || '/wp-admin/admin-ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    reviewMessage.style.display = 'block';
                    reviewMessage.style.color = 'var(--y-color-success, #28a745)';
                    reviewMessage.textContent = data.data.message || 'تم إضافة التقييم بنجاح!';
                    reviewForm.reset();
                    // Reset star ratings to default
                    initStarRatings();
                    // Don't reload - review needs admin approval first
                    // setTimeout(function() {
                    //     location.reload();
                    // }, 2000);
                } else {
                    reviewMessage.style.display = 'block';
                    reviewMessage.style.color = 'var(--y-color-error, #dc3545)';
                    reviewMessage.textContent = data.data.message || 'حدث خطأ أثناء إضافة التقييم';
                }
            })
            .catch(error => {
                reviewMessage.style.display = 'block';
                reviewMessage.style.color = 'var(--y-color-error, #dc3545)';
                reviewMessage.textContent = 'حدث خطأ أثناء إرسال التقييم';
                console.error('Error:', error);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
        
        function initStarRatings() {
            const starInputs = document.querySelectorAll('.star-rating-input');
            starInputs.forEach(function(container) {
                const inputs = container.querySelectorAll('input[type="radio"]');
                const labels = container.querySelectorAll('.star-label');
                
                inputs.forEach(function(input, index) {
                    input.addEventListener('change', function() {
                        updateStarDisplay(container, parseInt(this.value));
                    });
                });
                
                labels.forEach(function(label, index) {
                    label.addEventListener('mouseenter', function() {
                        const value = 5 - index;
                        highlightStars(container, value);
                    });
                });
                
                container.addEventListener('mouseleave', function() {
                    const checked = container.querySelector('input[type="radio"]:checked');
                    if (checked) {
                        updateStarDisplay(container, parseInt(checked.value));
                    }
                });
                
                // Initialize display
                const checked = container.querySelector('input[type="radio"]:checked');
                if (checked) {
                    updateStarDisplay(container, parseInt(checked.value));
                }
            });
        }
        
        function updateStarDisplay(container, value) {
            const labels = container.querySelectorAll('.star-label i');
            labels.forEach(function(star, index) {
                const starValue = 5 - index;
                if (starValue <= value) {
                    star.className = 'fa-solid fa-star';
                    star.style.color = 'var(--y-color-warning, #ffc107)';
                } else {
                    star.className = 'fa-regular fa-star';
                    star.style.color = 'var(--y-color-grey, #999)';
                }
            });
        }
        
        function highlightStars(container, value) {
            const labels = container.querySelectorAll('.star-label i');
            labels.forEach(function(star, index) {
                const starValue = 5 - index;
                if (starValue <= value) {
                    star.className = 'fa-solid fa-star';
                    star.style.color = 'var(--y-color-warning, #ffc107)';
                } else {
                    star.className = 'fa-regular fa-star';
                    star.style.color = 'var(--y-color-grey, #999)';
                }
            });
        }
    });
})();
