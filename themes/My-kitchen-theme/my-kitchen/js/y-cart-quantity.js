document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector(".myk-cart-form");
  if (!form) {
    return;
  }

  const submitUpdate = () => {
    let updateInput = form.querySelector('input[name="update_cart"]');
    if (!updateInput) {
      updateInput = document.createElement("input");
      updateInput.type = "hidden";
      updateInput.name = "update_cart";
      updateInput.value = "Update cart";
      form.appendChild(updateInput);
    }
    form.submit();
  };

  form.addEventListener("click", (event) => {
    const minus = event.target.closest("[data-qty-minus]");
    const plus = event.target.closest("[data-qty-plus]");
    if (!minus && !plus) {
      return;
    }

    const container = event.target.closest(".qnt");
    const input = container ? container.querySelector("input.qty") : null;
    if (!input) {
      return;
    }

    const min = input.getAttribute("min") ? parseFloat(input.getAttribute("min")) : 0;
    const max = input.getAttribute("max") ? parseFloat(input.getAttribute("max")) : null;
    const step = input.getAttribute("step") ? parseFloat(input.getAttribute("step")) : 1;
    let value = parseFloat(input.value || "0");
    if (Number.isNaN(value)) {
      value = 0;
    }

    if (minus) {
      value = value - step;
    } else if (plus) {
      value = value + step;
    }

    if (value < min) {
      value = min;
    }
    if (max !== null && value > max) {
      value = max;
    }

    input.value = value;
    input.dispatchEvent(new Event("change", { bubbles: true }));
  });

  form.addEventListener("change", (event) => {
    if (event.target && event.target.matches("input.qty")) {
      submitUpdate();
    }
  });
});
