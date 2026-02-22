/**
 * Store category filter - navigates to category URL on button click
 * "الكل" (data-cat="") → shop base URL
 * Category (data-cat="slug") → product-category/slug/
 * Preserves search param (s) when switching categories.
 */
(function () {
  function getSearchParam() {
    const params = new URLSearchParams(window.location.search);
    const s = params.get('s');
    return (s && s.trim()) ? s : '';
  }

  function initStoreFilter() {
    const container = document.querySelector('.store-section .categories');
    if (!container) return;

    const buttons = container.querySelectorAll('button[data-cat]');
    let shopBase = '';
    if (typeof wc_beauty_care !== 'undefined' && wc_beauty_care.shop_url) {
      shopBase = wc_beauty_care.shop_url.replace(/\/?$/, '');
    } else {
      const m = window.location.pathname.match(/^(.+)\/product-category\/[^/]+\/?$/);
      shopBase = m ? m[1] + '/shop' : window.location.origin + '/shop';
    }

    buttons.forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        const slug = (this.getAttribute('data-cat') || '').trim();
        const searchTerm = getSearchParam();

        buttons.forEach(function (b) {
          b.classList.remove('active');
        });
        this.classList.add('active');

        let url;
        if (slug) {
          url = shopBase.replace(/\/shop\/?$/, '') + '/product-category/' + slug + '/';
        } else {
          url = shopBase + '/';
        }
        if (searchTerm) {
          url += (url.indexOf('?') >= 0 ? '&' : '?') + 's=' + encodeURIComponent(searchTerm);
        }
        window.location.href = url;
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initStoreFilter);
  } else {
    initStoreFilter();
  }
})();
