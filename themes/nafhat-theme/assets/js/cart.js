/**
 * Cart Page JavaScript
 * Handles quantity updates
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Quantity buttons
        $(document).on('click', '.qty-btn', function(e) {
            e.preventDefault();
            
            var $btn = $(this);
            var $qtySelector = $btn.closest('.qty-selector');
            var $input = $qtySelector.find('input[type="number"]');
            var currentVal = parseInt($input.val()) || 1;
            var min = parseInt($input.attr('min')) || 0;
            var max = parseInt($input.attr('max')) || 999;
            
            if (max === -1) max = 999; // WooCommerce uses -1 for unlimited
            
            if ($btn.hasClass('qty-minus')) {
                if (currentVal > min) {
                    $input.val(currentVal - 1).trigger('change');
                }
            } else if ($btn.hasClass('qty-plus')) {
                if (currentVal < max) {
                    $input.val(currentVal + 1).trigger('change');
                }
            }
        });
        
        // Auto-update cart on quantity change (with debounce)
        var updateTimer;
        $(document).on('change', '.cart_item input.qty, .cart-item input[type="number"]', function() {
            clearTimeout(updateTimer);
            var $form = $(this).closest('form');
            
            updateTimer = setTimeout(function() {
                // Show loading state
                $form.find('.cart-list').css('opacity', '0.5');
                
                // Trigger update cart
                $form.find('button[name="update_cart"]').prop('disabled', false).trigger('click');
            }, 800);
        });
        
        // Handle update cart response
        $(document.body).on('updated_wc_div', function() {
            $('.cart-list').css('opacity', '1');
        });
        
    });
    
})(jQuery);
