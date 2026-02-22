(function () {
	var COOKIE_KEY = 'beauty_care_wishlist';
	var COOKIE_DAYS = 30;

	function readCookie(name) {
		var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
		return match ? decodeURIComponent(match[2]) : '';
	}

	function writeCookie(name, value, days) {
		var expires = new Date();
		expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
		document.cookie = name + '=' + encodeURIComponent(value) + ';expires=' + expires.toUTCString() + ';path=/;SameSite=Lax';
	}

	function getWishlistIds() {
		var raw = readCookie(COOKIE_KEY);
		if (raw) {
			try {
				var parsed = JSON.parse(raw);
				if (Array.isArray(parsed)) {
					return parsed.map(function (id) { return parseInt(id, 10); }).filter(Boolean);
				}
			} catch (e) {}
		}
		var fromPhp = (window.beautyCareWishlist && window.beautyCareWishlist.wishlistIds) ? window.beautyCareWishlist.wishlistIds : [];
		if (Array.isArray(fromPhp) && fromPhp.length > 0) {
			var ids = fromPhp.map(function (id) { return parseInt(id, 10); }).filter(Boolean);
			setWishlistIds(ids);
			return ids;
		}
		return [];
	}

	function setWishlistIds(ids) {
		var cleaned = Array.from(new Set(ids.map(function (id) { return parseInt(id, 10); }))).filter(Boolean).sort(function (a, b) { return a - b; });
		writeCookie(COOKIE_KEY, JSON.stringify(cleaned), COOKIE_DAYS);
	}

	function syncCheckboxes(root) {
		root = root || document;
		var ids = new Set(getWishlistIds());
		var inputs = root.querySelectorAll('.favorite-toggle__checkbox[data-product-id]');
		for (var i = 0; i < inputs.length; i++) {
			var input = inputs[i];
			var pid = parseInt(input.getAttribute('data-product-id'), 10);
			if (!pid) continue;
			input.checked = ids.has(pid);
		}
	}

	function renderWishlistEmptyState(wrapper) {
		if (!wrapper) return;
		var section = wrapper.closest('.bc-wishlist-section') || wrapper.closest('.store-section');
		if (!section || section.querySelector('.empty-state-container')) return;
		var shopUrl = (window.beautyCareWishlist && window.beautyCareWishlist.shopUrl) || (window.location.origin + '/shop/');
		var assetUri = (window.beautyCareWishlist && window.beautyCareWishlist.assetUri) || '';
		var emptyHtml =
			'<div class="empty-state-container" data-y="wishlist-empty">' +
			'<div class="empty-state">' +
			'<img src="' + assetUri + '/empty-cart.png" alt="">' +
			'<h3>لا توجد منتجات في المفضلة</h3>' +
			'<a href="' + shopUrl + '" class="btn main-button">تصفح المنتجات</a>' +
			'</div></div>';
		section.innerHTML = emptyHtml;
	}

	document.addEventListener('click', function (event) {
		if (event.target.closest('.favorite-toggle')) {
			event.stopPropagation();
		}
	}, true);

	document.addEventListener('change', function (event) {
		var input = event.target;
		if (!input || !input.classList.contains('favorite-toggle__checkbox')) return;
		var pid = parseInt(input.getAttribute('data-product-id'), 10);
		if (!pid) return;
		var current = new Set(getWishlistIds());
		if (input.checked) current.add(pid);
		else current.delete(pid);
		setWishlistIds(Array.from(current));
		if (document.body && document.body.getAttribute('data-current-page') === 'wishlist') {
			var card = input.closest('.product-card');
			if (card && !input.checked) {
				card.remove();
			}
			var wrapper = document.querySelector('[data-y="wishlist-products"]');
			if (wrapper) {
				var list = wrapper.tagName === 'UL' ? wrapper : wrapper;
				if (list && !list.querySelector('.product-card')) {
					renderWishlistEmptyState(wrapper);
				}
			}
		}
	});

	document.addEventListener('DOMContentLoaded', function () {
		syncCheckboxes(document);
	});
})();
