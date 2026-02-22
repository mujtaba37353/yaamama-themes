document.addEventListener('DOMContentLoaded', function () {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    
    paymentMethods.forEach(radio => {
        radio.addEventListener('change', function () {
            // Remove active class from all options
            document.querySelectorAll('.y-c-radio-option').forEach(option => {
                option.classList.remove('y-c-active');
            });
            
            // Hide all payment details
            document.querySelectorAll('.y-c-payment-card-details, .y-c-stc-pay-details').forEach(details => {
                details.style.display = 'none';
            });
            
            // Find the parent label and add active class
            const parentLabel = this.closest('.y-c-radio-option');
            if (parentLabel) {
                parentLabel.classList.add('y-c-active');
                
                // Show payment details for this option
                const details = parentLabel.querySelector('.y-c-payment-card-details, .y-c-stc-pay-details');
                if (details) {
                    details.style.display = 'flex';
                }
            }
            
            // Trigger WooCommerce payment method change
            if (typeof jQuery !== 'undefined' && jQuery.fn.trigger) {
                jQuery('body').trigger('update_checkout');
            }
        });
    });

    // Set initial state based on the checked radio button
    const initialChecked = document.querySelector('input[name="payment_method"]:checked');
    if (initialChecked) {
        const parentLabel = initialChecked.closest('.y-c-radio-option');
        if (parentLabel) {
            parentLabel.classList.add('y-c-active');
            const details = parentLabel.querySelector('.y-c-payment-card-details, .y-c-stc-pay-details');
            if (details) {
                details.style.display = 'flex';
            }
        }
    }

    // Function to show the success popup
    function showSuccessPopup() {
        // Create overlay
        const overlay = document.createElement('div');
        overlay.className = 'y-c-popup-overlay';
        overlay.setAttribute('data-y', 'popup-overlay');

        // Create popup content
        overlay.innerHTML = `
        <div class="y-c-popup-content" data-y="popup-content">
            <i class="fas fa-check-circle" data-y="popup-icon"></i>
            <h2 data-y="popup-title">تم استلام طلبك بنجاح</h2>
            <a href="/templates/home/layout.html" class="y-c-btn y-c-btn-primary y-c-checkout-btn" data-y="return-home-btn">
                العودة إلى الرئيسية
            </a>
        </div>
    `;

        // Append to body
        document.body.appendChild(overlay);

        // Clear the cart from local storage after a successful order
        localStorage.removeItem('cart');
    }

    // Hide Select2 and manage country input
    function initCountryFields() {
        // Hide Select2 containers
        const select2Containers = document.querySelectorAll('.select2-container');
        select2Containers.forEach(container => {
            container.style.display = 'none';
        });
        
        // Hide original select fields
        const countrySelects = document.querySelectorAll('select[name="billing_country"], select[name="shipping_country"]');
        countrySelects.forEach(select => {
            select.style.display = 'none';
        });
        
        // Set default value to Saudi Arabia if empty
        const billingCountryHidden = document.getElementById('billing_country');
        const billingCountryDisplay = document.getElementById('billing_country_display');
        const shippingCountryHidden = document.getElementById('shipping_country');
        const shippingCountryDisplay = document.getElementById('shipping_country_display');
        
        if (billingCountryHidden && billingCountryDisplay) {
            if (!billingCountryHidden.value || billingCountryHidden.value === '') {
                billingCountryHidden.value = 'SA';
                billingCountryDisplay.value = 'السعودية';
            }
            
            // Update hidden field when display field changes
            billingCountryDisplay.addEventListener('blur', function() {
                // Keep SA as default if user doesn't change it
                if (this.value.trim() === '' || this.value.trim() === 'السعودية') {
                    billingCountryHidden.value = 'SA';
                    this.value = 'السعودية';
                }
            });
        }
        
        if (shippingCountryHidden && shippingCountryDisplay) {
            if (!shippingCountryHidden.value || shippingCountryHidden.value === '') {
                shippingCountryHidden.value = 'SA';
                shippingCountryDisplay.value = 'السعودية';
            }
            
            // Update hidden field when display field changes
            shippingCountryDisplay.addEventListener('blur', function() {
                // Keep SA as default if user doesn't change it
                if (this.value.trim() === '' || this.value.trim() === 'السعودية') {
                    shippingCountryHidden.value = 'SA';
                    this.value = 'السعودية';
                }
            });
        }
    }
    
    // Initialize country fields
    initCountryFields();
    
    // Re-initialize after AJAX updates
    if (typeof jQuery !== 'undefined') {
        jQuery('body').on('updated_checkout', function() {
            initCountryFields();
        });
    }
    
    // Note: Form submission is handled by WooCommerce
    // The success popup will be shown after successful order placement via WooCommerce redirect
});