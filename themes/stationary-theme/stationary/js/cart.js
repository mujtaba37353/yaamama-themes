(() => {
  "use strict";

  const form = document.querySelector(".woocommerce-cart-form");
  if (!form) return;

  let updateTimer = null;

  const triggerCartUpdate = () => {
    clearTimeout(updateTimer);
    updateTimer = setTimeout(() => {
      const btn = form.querySelector('[name="update_cart"]');
      if (btn) {
        btn.disabled = false;
        btn.removeAttribute("aria-disabled");
        btn.click();
      }
    }, 600);
  };

  form.addEventListener("click", (e) => {
    const btn = e.target.closest(".qty-btn");
    if (!btn) return;

    e.preventDefault();
    const wrap = btn.closest(".quantity");
    const input = wrap && wrap.querySelector(".qty-input");
    if (!input) return;

    let val = parseInt(input.value, 10) || 0;
    const min = parseInt(input.min, 10) || 0;
    const max = input.max ? parseInt(input.max, 10) : Infinity;

    if (btn.classList.contains("qty-minus")) {
      val = Math.max(min, val - 1);
    } else if (btn.classList.contains("qty-plus")) {
      val = max === -1 ? val + 1 : Math.min(max, val + 1);
    }

    input.value = val;
    triggerCartUpdate();
  });

  form.addEventListener("change", (e) => {
    if (e.target.classList.contains("qty-input")) {
      triggerCartUpdate();
    }
  });
})();
