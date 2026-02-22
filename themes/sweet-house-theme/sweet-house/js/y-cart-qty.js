/**
 * Cart quantity +/- buttons — sync with input and auto-update cart via AJAX.
 * Does not depend on wc-cart; implements own AJAX update.
 * Intercepts form submit (Enter key, etc.) to prevent full page reload.
 */
(function () {
  "use strict";

  var debounceTimer;

  function ajaxUpdateCart() {
    var $ = window.jQuery;
    if (typeof $ === "undefined") return;

    var $form = $(".woocommerce-cart-form");
    if ($form.length === 0) return;

    var $totals = $(".cart_totals");
    var formData = $form.serialize();

    $form.addClass("processing");
    if ($totals.length) $totals.addClass("processing");

    $.ajax({
      type: "POST",
      url: $form.attr("action"),
      data: formData,
      dataType: "html",
      cache: false,
      success: function (response) {
        var $html = $(response);
        var $newForm = $(".woocommerce-cart-form", $html);
        var $newTotals = $(".cart_totals", $html);

        if ($newForm.length === 0) {
          window.location.reload();
          return;
        }

        $form.replaceWith($newForm.first());
        if ($newTotals.length && $totals.length) {
          $(".cart_totals").replaceWith($newTotals.first());
        }

        if (typeof $(document.body).trigger === "function") {
          $(document.body).trigger("updated_cart_totals");
        }
        init();
      },
      error: function () {
        window.location.reload();
      },
      complete: function () {
        $(".woocommerce-cart-form, .cart_totals").removeClass("processing");
      },
    });
  }

  function scheduleCartUpdate() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(ajaxUpdateCart, 500);
  }

  function init() {
    document.querySelectorAll(".qnt").forEach(function (wrap) {
      var input = wrap.querySelector("input.qty");
      var minusBtn = wrap.querySelector(".qnt-minus");
      var plusBtn = wrap.querySelector(".qnt-plus");
      if (!input || !minusBtn || !plusBtn) return;

      var min = parseInt(input.getAttribute("min") || "1", 10);
      var max = input.getAttribute("max");
      max = max ? parseInt(max, 10) : null;

      function updateValue(val, triggerUpdate) {
        val = parseInt(val, 10) || min;
        if (val < min) val = min;
        if (max !== null && val > max) val = max;
        input.value = val;
        minusBtn.disabled = val <= min;
        plusBtn.disabled = max !== null && val >= max;
        if (triggerUpdate !== false) scheduleCartUpdate();
      }

      minusBtn.addEventListener("click", function () {
        var v = parseInt(input.value, 10) || min;
        updateValue(v - 1);
      });

      plusBtn.addEventListener("click", function () {
        var v = parseInt(input.value, 10) || min;
        updateValue(v + 1);
      });

      input.addEventListener("change", function () {
        updateValue(input.value);
      });

      updateValue(input.value, false);
    });
  }

  function onFormSubmit(e) {
    var form = e.target && e.target.tagName && e.target.tagName.toLowerCase() === "form" ? e.target : (e.target && e.target.form ? e.target.form : null);
    if (!form || !form.classList || !form.classList.contains("woocommerce-cart-form")) return;
    e.preventDefault();
    e.stopPropagation();
    clearTimeout(debounceTimer);
    ajaxUpdateCart();
    return false;
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", function () {
      init();
      document.addEventListener("submit", onFormSubmit, true);
    });
  } else {
    init();
    document.addEventListener("submit", onFormSubmit, true);
  }

  document.body.addEventListener("updated_cart_totals", init);
})();
