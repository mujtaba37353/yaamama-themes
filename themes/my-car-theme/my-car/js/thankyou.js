/**
 * Thank You Page JavaScript
 * 
 * @package MyCarTheme
 */

(function($) {
    'use strict';

    // Initialize when DOM is ready
    $(document).ready(function() {
        initBookingDatesDisplay();
        initConfetti();
    });

    /**
     * Initialize Booking Dates Display
     * Shows the booking dates from localStorage
     */
    function initBookingDatesDisplay() {
        const container = document.getElementById('thankyou-booking-dates');
        if (!container) return;

        // Get booking data from localStorage
        const pickupDate = localStorage.getItem('pickup-date');
        const pickupTime = localStorage.getItem('pickup-time');
        const dropoffDate = localStorage.getItem('dropoff-date');
        const dropoffTime = localStorage.getItem('dropoff-time');

        // Build HTML
        let html = '';

        // Pickup Date Card
        if (pickupDate || pickupTime) {
            html += `
                <div class="y-c-booking-date-card">
                    <div class="y-c-booking-date-card-icon">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div class="y-c-booking-date-card-content">
                        <span class="y-c-booking-date-card-label">تاريخ ووقت الاستلام</span>
                        <span class="y-c-booking-date-card-value">${pickupDate || 'غير محدد'} - ${pickupTime || 'غير محدد'}</span>
                    </div>
                </div>
            `;
        }

        // Dropoff Date Card
        if (dropoffDate || dropoffTime) {
            html += `
                <div class="y-c-booking-date-card">
                    <div class="y-c-booking-date-card-icon">
                        <i class="fa-solid fa-calendar-xmark"></i>
                    </div>
                    <div class="y-c-booking-date-card-content">
                        <span class="y-c-booking-date-card-label">تاريخ ووقت التسليم</span>
                        <span class="y-c-booking-date-card-value">${dropoffDate || 'غير محدد'} - ${dropoffTime || 'غير محدد'}</span>
                    </div>
                </div>
            `;
        }

        // If no dates available
        if (!html) {
            html = `
                <div class="y-c-no-booking-dates" style="text-align: center; padding: 20px; color: var(--y-color-third-text);">
                    <i class="fa-solid fa-info-circle" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                    <span>لم يتم تحديد تواريخ الحجز</span>
                </div>
            `;
        }

        container.innerHTML = html;

        // Clear booking dates from localStorage after displaying
        // This ensures fresh dates for next booking
        clearBookingDates();
    }

    /**
     * Clear booking dates from localStorage
     */
    function clearBookingDates() {
        // Delay clearing to ensure data is displayed first
        setTimeout(function() {
            localStorage.removeItem('pickup-date');
            localStorage.removeItem('pickup-time');
            localStorage.removeItem('dropoff-date');
            localStorage.removeItem('dropoff-time');
        }, 2000);
    }

    /**
     * Initialize Confetti Animation (optional celebration effect)
     */
    function initConfetti() {
        const successIcon = document.querySelector('.y-c-icon-success');
        if (!successIcon) return;

        // Create simple confetti effect
        createConfetti();
    }

    /**
     * Create simple confetti particles
     */
    function createConfetti() {
        const colors = ['#6b21a8', '#22c55e', '#f59e0b', '#3b82f6', '#ec4899'];
        const confettiCount = 50;
        const container = document.querySelector('.y-c-thankyou-header');
        
        if (!container) return;

        for (let i = 0; i < confettiCount; i++) {
            setTimeout(function() {
                const confetti = document.createElement('div');
                confetti.style.cssText = `
                    position: absolute;
                    width: 10px;
                    height: 10px;
                    background-color: ${colors[Math.floor(Math.random() * colors.length)]};
                    left: ${Math.random() * 100}%;
                    top: 0;
                    opacity: 1;
                    border-radius: ${Math.random() > 0.5 ? '50%' : '0'};
                    transform: rotate(${Math.random() * 360}deg);
                    animation: confettiFall ${2 + Math.random() * 2}s ease-out forwards;
                    pointer-events: none;
                    z-index: 10;
                `;
                
                container.style.position = 'relative';
                container.style.overflow = 'hidden';
                container.appendChild(confetti);

                // Remove confetti after animation
                setTimeout(function() {
                    confetti.remove();
                }, 4000);
            }, i * 30);
        }

        // Add confetti animation if not exists
        if (!document.getElementById('confetti-styles')) {
            const style = document.createElement('style');
            style.id = 'confetti-styles';
            style.textContent = `
                @keyframes confettiFall {
                    0% {
                        transform: translateY(0) rotate(0deg);
                        opacity: 1;
                    }
                    100% {
                        transform: translateY(400px) rotate(720deg);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }

})(jQuery);
