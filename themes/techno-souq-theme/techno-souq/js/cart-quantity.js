/**
 * Cart Quantity Controls
 * Handles + and - buttons for cart quantity updates
 */
(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        const cartForm = document.querySelector('.woocommerce-cart-form');
        if (!cartForm) return;
        
        // Handle quantity buttons
        const quantityButtons = document.querySelectorAll('.y-c-quantity-btn');
        
        quantityButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const wrapper = this.closest('.y-c-quantity-control');
                if (!wrapper) return;
                
                const input = wrapper.querySelector('input[type="number"].y-c-quantity-input');
                if (!input) return;
                
                let currentValue = parseInt(input.value) || 0;
                const min = parseInt(input.min) || 0;
                const max = parseInt(input.max) || Infinity;
                
                // Check if this is increase or decrease button by ID
                const isIncrease = this.id && this.id.includes('increase');
                const isDecrease = this.id && this.id.includes('decrease');
                
                if (isIncrease && currentValue < max) {
                    input.value = currentValue + 1;
                } else if (isDecrease && currentValue > min) {
                    input.value = currentValue - 1;
                } else {
                    return; // No change needed
                }
                
                // Trigger change event
                input.dispatchEvent(new Event('change', { bubbles: true }));
                
                // Update cart by submitting form
                const updateButton = cartForm.querySelector('button[name="update_cart"]');
                if (updateButton) {
                    updateButton.removeAttribute('disabled');
                    updateButton.click();
                } else {
                    // Create hidden update button if doesn't exist
                    const hiddenButton = document.createElement('button');
                    hiddenButton.type = 'submit';
                    hiddenButton.name = 'update_cart';
                    hiddenButton.value = 'Update cart';
                    hiddenButton.style.cssText = 'display: none !important; position: absolute !important; visibility: hidden !important;';
                    cartForm.appendChild(hiddenButton);
                    hiddenButton.click();
                }
            });
        });
    });
})();
