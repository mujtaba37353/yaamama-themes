/**
 * Wishlist JavaScript
 * Handles add/remove from wishlist functionality
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Toggle wishlist on heart icon click
        $(document).on('click', '.pd-fav-btn, .wishlist-icon', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $btn = $(this);
            var productId = $btn.data('product_id') || $btn.closest('[data-product_id]').data('product_id');
            
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
            
            // Check if user is logged in
            if (!nafhatWishlist.isLoggedIn) {
                if (confirm(nafhatWishlist.strings.loginRequired + '\n\nهل تريد تسجيل الدخول؟')) {
                    window.location.href = nafhatWishlist.loginUrl;
                }
                return;
            }
            
            // Add loading state
            $btn.addClass('loading');
            
            $.ajax({
                url: nafhatWishlist.ajaxurl,
                type: 'POST',
                data: {
                    action: 'nafhat_toggle_wishlist',
                    product_id: productId,
                    nonce: nafhatWishlist.nonce
                },
                success: function(response) {
                    $btn.removeClass('loading');
                    
                    if (response.success) {
                        if (response.data.action === 'added') {
                            $btn.addClass('in-wishlist');
                            showNotification(nafhatWishlist.strings.added, 'success');
                        } else {
                            $btn.removeClass('in-wishlist');
                            showNotification(nafhatWishlist.strings.removed, 'info');
                        }
                        
                        // Update wishlist count in header if exists
                        updateWishlistCount(response.data.count);
                    } else {
                        if (response.data.login_required) {
                            if (confirm(response.data.message + '\n\nهل تريد تسجيل الدخول؟')) {
                                window.location.href = response.data.login_url;
                            }
                        } else {
                            showNotification(response.data.message || nafhatWishlist.strings.error, 'error');
                        }
                    }
                },
                error: function() {
                    $btn.removeClass('loading');
                    showNotification(nafhatWishlist.strings.error, 'error');
                }
            });
        });
        
        // Remove from wishlist page
        $(document).on('click', '.wishlist-remove-btn', function(e) {
            e.preventDefault();
            
            var $btn = $(this);
            var $item = $btn.closest('.wishlist-item');
            var productId = $btn.data('product_id');
            
            if (!productId) return;
            
            $btn.addClass('loading');
            
            $.ajax({
                url: nafhatWishlist.ajaxurl,
                type: 'POST',
                data: {
                    action: 'nafhat_remove_from_wishlist',
                    product_id: productId,
                    nonce: nafhatWishlist.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $item.fadeOut(300, function() {
                            $(this).remove();
                            
                            // Check if wishlist is empty
                            if ($('.wishlist-item').length === 0) {
                                location.reload();
                            }
                        });
                        
                        updateWishlistCount(response.data.count);
                        showNotification(response.data.message, 'info');
                    } else {
                        $btn.removeClass('loading');
                        showNotification(response.data.message || nafhatWishlist.strings.error, 'error');
                    }
                },
                error: function() {
                    $btn.removeClass('loading');
                    showNotification(nafhatWishlist.strings.error, 'error');
                }
            });
        });
        
        // Update wishlist count in header
        function updateWishlistCount(count) {
            $('.wishlist-count').text(count);
            if (count > 0) {
                $('.wishlist-count').show();
            } else {
                $('.wishlist-count').hide();
            }
        }
        
        // Show notification
        function showNotification(message, type) {
            // Remove existing notifications
            $('.nafhat-notification').remove();
            
            var bgColor = '#28a745'; // success
            if (type === 'error') bgColor = '#dc3545';
            if (type === 'info') bgColor = '#17a2b8';
            
            var $notification = $('<div class="nafhat-notification" style="' +
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
