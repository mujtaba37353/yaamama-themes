const cartSummary = document.querySelector('[data-y="cart-summary"]');
if (cartSummary && cartSummary.dataset.realCart !== "1") {
  fetch("../../components/cards/y-c-cart-summary-card.html")
    .then((response) => response.text())
    .then((data) => {
      cartSummary.innerHTML = data;
      if (window.mykitchenResolveAssets) {
        window.mykitchenResolveAssets(cartSummary);
      }
    });
}
