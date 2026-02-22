(function () {
  if (typeof mallatiData === 'undefined') return;

  const wireWishlist = (root) => {
    root.querySelectorAll('.product-add-to-wishlist').forEach((label) => {
      const productId = label.getAttribute('data-product-id');
      const checkbox = label.querySelector('.wishlist-checkbox');
      const icon = label.querySelector('i.fa-heart');
      if (!productId || !checkbox || !icon) return;

      if (label.__wishlistBound) return;
      label.__wishlistBound = true;

      const doToggle = () => {
        if (!mallatiData.ajaxUrl || !mallatiData.nonce) return;
        const formData = new FormData();
        formData.append('action', 'mallati_toggle_favourite');
        formData.append('nonce', mallatiData.nonce);
        formData.append('product_id', productId);

        fetch(mallatiData.ajaxUrl, {
          method: 'POST',
          body: formData,
          credentials: 'same-origin',
        })
          .then((r) => r.json())
          .then((data) => {
            if (data.success) {
              const added = data.data && data.data.added;
              checkbox.checked = !!added;
              icon.classList.remove('far', 'fas');
              icon.classList.add(added ? 'fas' : 'far');
            } else if (data.data && data.data.login && mallatiData.myAccountUrl) {
              window.location.href = mallatiData.myAccountUrl;
            }
          })
          .catch(() => {});
      };

      label.addEventListener('click', (e) => {
        e.preventDefault();
        doToggle();
      });
    });
  };

  const init = () => {
    wireWishlist(document);
    document.body.addEventListener('updated_wc_div', () => wireWishlist(document));
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
