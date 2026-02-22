/**
 * Add to Cart JavaScript
 * Handles AJAX add to cart functionality for product cards
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Handle add to cart button click
        $(document).on('click', '.product-add-to-cart-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $btn = $(this);
            var productId = $btn.data('product_id');
            var quantity = $btn.data('quantity') || 1;
            
            if (!productId) {
                // Try to get from parent product card
                var $card = $btn.closest('.product-card');
                if ($card.length) {
                    productId = $card.data('product_id');
                }
            }
            
            if (!productId) {
                console.error('Product ID not found');
                return;
            }
            
            // Add loading state
            $btn.addClass('loading');
            
            // AJAX add to cart
            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'woocommerce_ajax_add_to_cart',
                    product_id: productId,
                    quantity: quantity
                },
                success: function(response) {
                    $btn.removeClass('loading');
                    
                    if (response.error && response.product_url) {
                        // Variable product - redirect to product page
                        window.location = response.product_url;
                        return;
                    }
                    
                    // Success
                    $btn.addClass('added');
                    
                    // Update cart fragments
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $btn]);
                    
                    // Update cart count in header
                    updateCartCount(response.cart_count);
                    
                    // Show notification
                    showNotification('تمت إضافة المنتج للسلة', 'success');
                    
                    // Remove added class after animation
                    setTimeout(function() {
                        $btn.removeClass('added');
                    }, 500);
                },
                error: function() {
                    $btn.removeClass('loading');
                    showNotification('حدث خطأ، يرجى المحاولة مرة أخرى', 'error');
                }
            });
        });
        
        // Update cart count in header
        function updateCartCount(count) {
            var $cartCounts = $('.cart-count');
            if (count && count > 0) {
                $cartCounts.text(count).show();
            } else {
                $cartCounts.hide();
            }
        }
        
        // Listen for WooCommerce cart updates
        $(document.body).on('added_to_cart removed_from_cart updated_cart_totals', function(e, fragments) {
            if (fragments && fragments['.cart-count']) {
                $('.cart-count').replaceWith(fragments['.cart-count']);
            }
            // Refresh cart count via AJAX
            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'nafhat_get_cart_count'
                },
                success: function(response) {
                    if (response.success) {
                        updateCartCount(response.data.count);
                    }
                }
            });
        });
        
        // Show notification function
        function showNotification(message, type) {
            // Remove existing notifications
            $('.nafhat-cart-notification').remove();
            
            var bgColor = '#28a745'; // success
            if (type === 'error') bgColor = '#dc3545';
            if (type === 'info') bgColor = '#17a2b8';
            
            var $notification = $('<div class="nafhat-cart-notification" style="' +
                'position: fixed;' +
                'bottom: 20px;' +
                'right: 20px;' +
                'background: ' + bgColor + ';' +
                'color: white;' +
                'padding: 15px 25px;' +
                'border-radius: 8px;' +
                'box-shadow: 0 4px 15px rgba(0,0,0,0.2);' +
                'z-index: 9999;' +
                'font-size: 14px;' +
                'animation: slideIn 0.3s ease;' +
                '">' + message + '</div>');
            
            $('body').append($notification);
            
            setTimeout(function() {
                $notification.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }
        
    });
    
})(jQuery);
