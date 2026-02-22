/**
 * Wishlist functionality for product cards
 */
(function($) {
	'use strict';

	// Initialize wishlist buttons when DOM is ready
	function initWishlistButtons() {
		$('.wishlist-btn[data-product-id]').off('click.wishlist').on('click.wishlist', function(e) {
			e.preventDefault();
			e.stopPropagation();

			var $btn = $(this);
			var $icon = $btn.find('i');
			var productId = $btn.data('product-id');

			if (!productId) {
				return;
			}

			// Get AJAX URL and nonce
			var ajaxUrl = '';
			var nonce = '';
			
			// Try to get from khutaaWishlist first (our custom handler)
			if (typeof khutaaWishlist !== 'undefined') {
				ajaxUrl = khutaaWishlist.ajaxurl || ajaxurl;
				nonce = khutaaWishlist.nonce || '';
			} else if (typeof ajaxurl !== 'undefined') {
				ajaxUrl = ajaxurl;
			}

			var isInWishlist = $btn.hasClass('active') || $icon.hasClass('fa-solid');
			
			// Always use our custom AJAX handlers which will call YITH if available
			if (ajaxUrl) {
				var action = isInWishlist ? 'remove_from_wishlist' : 'add_to_wishlist';
				var ajaxAction = 'khutaa_' + action;

				$.ajax({
					type: 'POST',
					url: ajaxUrl,
					data: {
						action: ajaxAction,
						product_id: productId,
						nonce: nonce
					},
					beforeSend: function() {
						$btn.prop('disabled', true);
					},
					success: function(response) {
						// Check various response formats
						var success = false;
						
						if (response) {
							// WordPress AJAX success format: { success: true, data: {...} }
							if (response.success === true || response.result === 'success') {
								success = true;
							} else if (response.data && (response.data.success || response.data.result === 'success')) {
								success = true;
							}
						}
						
						if (success) {
							// Toggle button state based on action
							if (action === 'add_to_wishlist') {
								$btn.addClass('active');
								$icon.removeClass('fa-regular').addClass('fa-solid');
							} else {
								$btn.removeClass('active');
								$icon.removeClass('fa-solid').addClass('fa-regular');
							}
							
							// Trigger YITH events if available
							if (typeof yith_wcwl_lists !== 'undefined' || typeof yith_wcwl_frontend !== 'undefined') {
								$(document).trigger('yith_wcwl_reload_after_ajax');
								$(document).trigger('yith_wcwl_reload_fragments');
							}
						} else {
							console.log('Wishlist AJAX response (not success):', response);
							if (response && response.data && response.data.message) {
								console.log('Message:', response.data.message);
							}
						}
					},
					error: function(xhr, status, error) {
						console.log('Wishlist AJAX error:', error, xhr);
					},
					complete: function() {
						$btn.prop('disabled', false);
					}
				});
			} else {
				// Final fallback: Simple toggle if no AJAX available
				if (isInWishlist) {
					$btn.removeClass('active');
					$icon.removeClass('fa-solid').addClass('fa-regular');
				} else {
					$btn.addClass('active');
					$icon.removeClass('fa-regular').addClass('fa-solid');
				}
			}
		});
	}

	// Initialize on document ready
	$(document).ready(function() {
		initWishlistButtons();
	});

	// Re-initialize after AJAX loads (for dynamic content)
	$(document).on('added_to_cart removed_from_cart yith_wcwl_reload_after_ajax', function() {
		setTimeout(initWishlistButtons, 100);
	});

	// Re-initialize when products are loaded dynamically
	var observer = new MutationObserver(function(mutations) {
		var hasNewProducts = false;
		mutations.forEach(function(mutation) {
			if (mutation.addedNodes.length) {
				hasNewProducts = true;
			}
		});
		if (hasNewProducts) {
			setTimeout(initWishlistButtons, 100);
		}
	});

	// Start observing when DOM is ready
	$(document).ready(function() {
		var productsContainer = document.querySelector('.products, [data-y="products"], ul.products');
		if (productsContainer) {
			observer.observe(productsContainer, {
				childList: true,
				subtree: true
			});
		}
	});

})(jQuery);
