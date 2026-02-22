(function () {
  function init() {
    document.querySelectorAll('.qty-btn[data-qty-down]').forEach(function (btn) {
      if (btn._qtyBound) return;
      btn._qtyBound = true;
      btn.addEventListener('click', function () {
        var input = btn.parentElement.querySelector('.qty-input, input[type="number"]');
        if (!input) return;
        var v = parseInt(input.value, 10) || 0;
        var min = parseInt(input.getAttribute('min'), 10) || 0;
        if (v > min) input.value = v - 1;
      });
    });
    document.querySelectorAll('.qty-btn[data-qty-up]').forEach(function (btn) {
      if (btn._qtyBound) return;
      btn._qtyBound = true;
      btn.addEventListener('click', function () {
        var input = btn.parentElement.querySelector('.qty-input, input[type="number"]');
        if (!input) return;
        var v = parseInt(input.value, 10) || 0;
        var max = parseInt(input.getAttribute('max'), 10);
        if (isNaN(max) || max <= 0 || v < max) input.value = v + 1;
      });
    });
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
