/**
 * Cart quantity +/- buttons - Beauty Care design
 * تحديث السلة فوراً عند تغيير الكمية
 */
(function () {
  const form = document.querySelector('.woocommerce-cart-form');
  const section = document.querySelector('.cart-section');
  if (!form || !section) return;

  function submitCart() {
    const hidden = document.createElement('input');
    hidden.type = 'hidden';
    hidden.name = 'update_cart';
    hidden.value = 'تحديث السلة';
    form.appendChild(hidden);
    form.submit();
  }

  section.querySelectorAll('.quantity').forEach(function (wrap) {
    const input = wrap.querySelector('input[type="number"]');
    const minus = wrap.querySelector('.qty-minus');
    const plus = wrap.querySelector('.qty-plus');
    if (!input || !minus || !plus) return;

    const min = parseInt(input.getAttribute('min'), 10) || 1;
    const maxAttr = input.getAttribute('max');
    const max = (maxAttr !== null && maxAttr !== '' && parseInt(maxAttr, 10) > 0)
      ? parseInt(maxAttr, 10)
      : 999999;

    minus.addEventListener('click', function () {
      const v = parseInt(input.value, 10) || min;
      if (v > min) {
        input.value = v - 1;
        submitCart();
      }
    });

    plus.addEventListener('click', function () {
      const v = parseInt(input.value, 10) || 0;
      if (v < max) {
        input.value = v + 1;
        submitCart();
      }
    });

    input.addEventListener('change', function () {
      const v = parseInt(input.value, 10);
      if (isNaN(v) || v < min) input.value = min;
      else if (v > max) input.value = max;
      submitCart();
    });
  });
})();
