const cartTable = document.querySelector('[data-y="cart-table"]');
if (cartTable && cartTable.dataset.realCart !== "1") {
  fetch("../../components/cart/y-c-cart-table.html")
    .then((response) => response.text())
    .then((data) => {
      cartTable.innerHTML = data;
      if (window.mykitchenResolveAssets) {
        window.mykitchenResolveAssets(cartTable);
      }
    });
}
