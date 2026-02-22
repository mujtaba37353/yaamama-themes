const singleProductContainer = document.querySelector('[data-y="single-product"]');
if (singleProductContainer) {
  if (!singleProductContainer.children.length) {
    const base = window.MYK_ASSETS_URL || "";
    const fallbackPath = "../../components/single product/y-c-single-product.html";
    const source = base
      ? `${base}/components/single product/y-c-single-product.html`
      : fallbackPath;

    fetch(source)
      .then((response) => response.text())
      .then((data) => {
        singleProductContainer.innerHTML = data;
        if (window.mykitchenResolveAssets) {
          window.mykitchenResolveAssets(singleProductContainer);
        }
      });
  }
}

document.addEventListener("click", (event) => {
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

  const min = input.getAttribute("min") ? parseFloat(input.getAttribute("min")) : 1;
  const max = input.getAttribute("max") ? parseFloat(input.getAttribute("max")) : null;
  const step = input.getAttribute("step") ? parseFloat(input.getAttribute("step")) : 1;
  let value = parseFloat(input.value || "1");
  if (Number.isNaN(value)) {
    value = 1;
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
