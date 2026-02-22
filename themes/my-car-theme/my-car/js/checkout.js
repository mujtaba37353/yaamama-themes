/**
 * Checkout Page JavaScript
 * 
 * @package MyCarTheme
 */

(function($) {
    'use strict';

    // Initialize when DOM is ready
    $(document).ready(function() {
        initBookingSummary();
        initPaymentMethods();
    });

    /**
     * Initialize Booking Summary
     * Displays the booking dates and times from localStorage
     */
    function initBookingSummary() {
        const summaryContainer = document.getElementById('checkout-booking-summary');
        if (!summaryContainer) return;

        // Get booking data from localStorage
        const pickupDate = localStorage.getItem('pickup-date');
        const pickupTime = localStorage.getItem('pickup-time');
        const dropoffDate = localStorage.getItem('dropoff-date');
        const dropoffTime = localStorage.getItem('dropoff-time');

        // Build summary HTML
        let html = '<div class="y-c-booking-summary-items">';

        // Get cart items from the page
        const cartItems = document.querySelectorAll('.y-c-cart-item');
        
        if (cartItems.length > 0) {
            cartItems.forEach(function(item) {
                const image = item.querySelector('.y-c-cart-item-image img');
                const name = item.querySelector('.y-c-cart-item-name');
                
                html += `
                    <div class="y-c-booking-item">
                        <div class="y-c-booking-item-image">
                            ${image ? image.outerHTML : '<div class="y-c-no-image"><i class="fa-solid fa-car"></i></div>'}
                        </div>
                        <div class="y-c-booking-item-info">
                            <div class="y-c-booking-item-name">${name ? name.textContent : 'سيارة'}</div>
                            <div class="y-c-booking-dates">
                                ${pickupDate || pickupTime ? `
                                    <div class="y-c-booking-date-row">
                                        <i class="fa-solid fa-calendar-check"></i>
                                        <span class="y-c-booking-date-label">الاستلام:</span>
                                        <span class="y-c-booking-date-value">${pickupDate || ''} ${pickupTime || ''}</span>
                                    </div>
                                ` : ''}
                                ${dropoffDate || dropoffTime ? `
                                    <div class="y-c-booking-date-row">
                                        <i class="fa-solid fa-calendar-xmark"></i>
                                        <span class="y-c-booking-date-label">التسليم:</span>
                                        <span class="y-c-booking-date-value">${dropoffDate || ''} ${dropoffTime || ''}</span>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
        } else {
            // If no cart items found, show dates only
            html += `
                <div class="y-c-booking-item">
                    <div class="y-c-booking-item-info" style="width: 100%;">
                        <div class="y-c-booking-dates">
                            ${pickupDate || pickupTime ? `
                                <div class="y-c-booking-date-row">
                                    <i class="fa-solid fa-calendar-check"></i>
                                    <span class="y-c-booking-date-label">تاريخ ووقت الاستلام:</span>
                                    <span class="y-c-booking-date-value">${pickupDate || 'غير محدد'} - ${pickupTime || 'غير محدد'}</span>
                                </div>
                            ` : ''}
                            ${dropoffDate || dropoffTime ? `
                                <div class="y-c-booking-date-row">
                                    <i class="fa-solid fa-calendar-xmark"></i>
                                    <span class="y-c-booking-date-label">تاريخ ووقت التسليم:</span>
                                    <span class="y-c-booking-date-value">${dropoffDate || 'غير محدد'} - ${dropoffTime || 'غير محدد'}</span>
                                </div>
                            ` : ''}
                            ${!pickupDate && !pickupTime && !dropoffDate && !dropoffTime ? `
                                <div class="y-c-no-booking-dates">
                                    <i class="fa-solid fa-info-circle"></i>
                                    <span>لم يتم تحديد تواريخ الحجز</span>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        }

        html += '</div>';
        summaryContainer.innerHTML = html;

        // Calculate rental days if both dates are available
        if (pickupDate && dropoffDate) {
            const days = calculateRentalDays(pickupDate, dropoffDate);
            if (days > 0) {
                const daysHtml = `
                    <div class="y-c-rental-duration">
                        <i class="fa-solid fa-clock"></i>
                        <span>مدة الإيجار: <strong>${days} ${days === 1 ? 'يوم' : 'أيام'}</strong></span>
                    </div>
                `;
                summaryContainer.insertAdjacentHTML('beforeend', daysHtml);
            }
        }
    }

    /**
     * Calculate rental days between two dates
     */
    function calculateRentalDays(pickupDate, dropoffDate) {
        // Parse Arabic date format (e.g., "25 يناير 2026")
        const arabicMonths = {
            'يناير': 0, 'فبراير': 1, 'مارس': 2, 'أبريل': 3,
            'مايو': 4, 'يونيو': 5, 'يوليو': 6, 'أغسطس': 7,
            'سبتمبر': 8, 'أكتوبر': 9, 'نوفمبر': 10, 'ديسمبر': 11
        };

        function parseArabicDate(dateStr) {
            const parts = dateStr.split(' ');
            if (parts.length >= 3) {
                const day = parseInt(parts[0]);
                const month = arabicMonths[parts[1]];
                const year = parseInt(parts[2]);
                if (!isNaN(day) && month !== undefined && !isNaN(year)) {
                    return new Date(year, month, day);
                }
            }
            return null;
        }

        const pickup = parseArabicDate(pickupDate);
        const dropoff = parseArabicDate(dropoffDate);

        if (pickup && dropoff) {
            const diffTime = Math.abs(dropoff - pickup);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return diffDays;
        }

        return 0;
    }

    /**
     * Initialize Payment Methods
     * Handle payment method selection styling
     */
    function initPaymentMethods() {
        const paymentMethods = document.querySelectorAll('.y-c-payment-method input[type="radio"]');
        
        paymentMethods.forEach(function(radio) {
            // Set initial state
            updatePaymentMethodState(radio);
            
            // Listen for changes
            radio.addEventListener('change', function() {
                // Reset all methods
                paymentMethods.forEach(function(r) {
                    updatePaymentMethodState(r);
                });
            });
        });

        // Also handle WooCommerce's payment method updates via AJAX
        $(document.body).on('updated_checkout', function() {
            const updatedMethods = document.querySelectorAll('.y-c-payment-method input[type="radio"]');
            updatedMethods.forEach(function(radio) {
                updatePaymentMethodState(radio);
                radio.addEventListener('change', function() {
                    updatedMethods.forEach(function(r) {
                        updatePaymentMethodState(r);
                    });
                });
            });
        });
    }

    /**
     * Update payment method visual state
     */
    function updatePaymentMethodState(radio) {
        const label = radio.closest('.y-c-payment-method-label') || radio.parentElement;
        const checkIcon = label.querySelector('.y-c-payment-check');
        
        if (radio.checked) {
            label.style.borderColor = 'var(--y-color-primary)';
            label.style.backgroundColor = 'rgba(107, 33, 168, 0.05)';
            if (checkIcon) checkIcon.style.opacity = '1';
        } else {
            label.style.borderColor = '';
            label.style.backgroundColor = '';
            if (checkIcon) checkIcon.style.opacity = '0';
        }
    }

})(jQuery);
