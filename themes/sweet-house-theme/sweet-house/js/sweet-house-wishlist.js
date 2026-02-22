(function () {
  var COOKIE_KEY = 'sweet_house_wishlist';
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
    var fromPhp = (window.sweetHouseWishlist && window.sweetHouseWishlist.wishlistIds) ? window.sweetHouseWishlist.wishlistIds : [];
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

  function renderWishlistEmptyState(container) {
    if (!container) return;
    if (container.querySelector('.not-found-content')) return;
    var shopUrl = (window.sweetHouseWishlist && window.sweetHouseWishlist.shopUrl) || (window.location.origin + '/shop/');
    var assetUri = (window.sweetHouseWishlist && window.sweetHouseWishlist.assetUri) || '';
    container.innerHTML =
      '<div class="not-found-content">' +
      '<img src="' + assetUri + 'assets/empty-fav.png" alt="المفضلة فارغة" class="not-found-img" />' +
      '<p class="not-found-text">قائمة المفضلة فارغة، لم تقم بإضافة أي منتجات إلى قائمة المفضلة الخاصة بك بعد.</p>' +
      '<a href="' + shopUrl + '" class="btn-back">تصفح المنتجات <i class="fa-solid fa-store"></i></a>' +
      '</div>';
  }

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
      if (card && !input.checked) card.remove();
      var wrapper = document.querySelector('[data-y="wishlist-products"]');
      if (wrapper) {
        var list = wrapper.tagName === 'UL' ? wrapper : wrapper.querySelector('ul.products');
        if (list && !list.querySelector('.product-card')) {
          renderWishlistEmptyState(wrapper.parentElement || wrapper);
        }
      }
    }
  });

  document.addEventListener('DOMContentLoaded', function () {
    syncCheckboxes(document);
  });
})();
