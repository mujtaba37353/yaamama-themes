document.addEventListener("change", (event) => {
  const target = event.target;
  if (!target.classList.contains("product-card-fav-input")) return;

  if (!window.DarkTheme || !window.DarkTheme.ajaxUrl) return;

  const productId = target.getAttribute("data-product-id");
  if (!productId) return;

  const formData = new FormData();
  formData.append("action", "dark_theme_toggle_wishlist");
  formData.append("product_id", productId);
  formData.append("nonce", window.DarkTheme.nonce || "");

  fetch(window.DarkTheme.ajaxUrl, {
    method: "POST",
    body: formData,
    credentials: "same-origin",
  })
    .then((response) => response.json())
    .then((data) => {
      if (!data || !data.success) {
        target.checked = !target.checked;
      }
    })
    .catch(() => {
      target.checked = !target.checked;
    });
});

document.addEventListener("click", (event) => {
  const minusBtn = event.target.closest("[data-qty-minus]");
  const plusBtn = event.target.closest("[data-qty-plus]");
  if (!minusBtn && !plusBtn) return;

  const wrapper = event.target.closest(".wishlist-content .qnt");
  if (!wrapper) return;
  const input = wrapper.querySelector("input[type='number']");
  if (!input) return;

  const current = parseInt(input.value, 10) || 1;
  if (minusBtn) {
    input.value = Math.max(1, current - 1);
  }
  if (plusBtn) {
    input.value = current + 1;
  }
});
