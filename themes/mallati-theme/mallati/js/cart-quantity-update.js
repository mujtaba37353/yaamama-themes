(function () {
  function ensureUpdateCartField(form) {
    var field = form.querySelector('input[name="update_cart"]');
    if (!field) {
      field = document.createElement('input');
      field.type = 'hidden';
      field.name = 'update_cart';
      field.value = '1';
      form.appendChild(field);
    }
    return field;
  }

  function submitCartForm() {
    var form = document.querySelector('.woocommerce-cart-form');
    if (!form) return;
    ensureUpdateCartField(form).value = '1';
    form.submit();
  }
  function init() {
    var form = document.querySelector('.woocommerce-cart-form');
    if (!form) return;
    form.querySelectorAll('.qty-btn').forEach(function (btn) {
      if (btn._cartQtyBound) return;
      btn._cartQtyBound = true;
      btn.addEventListener('click', function () {
        setTimeout(submitCartForm, 120);
      });
    });
    form.querySelectorAll('.qty-input, input[name^="cart["]').forEach(function (input) {
      if (input._cartQtyBound) return;
      input._cartQtyBound = true;
      input.addEventListener('change', submitCartForm);
    });
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
