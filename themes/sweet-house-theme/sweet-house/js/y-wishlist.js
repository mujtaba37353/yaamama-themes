document.addEventListener("DOMContentLoaded", () => {
  fetch("../../components/products/y-c-products.html")
    .then((response) => response.text())
    .then((data) => {
      const productsContainer = document.querySelector(
        '[data-y="wishlist-products"]'
      );
      if (productsContainer) {
        productsContainer.innerHTML = data;
        const heartInputs = productsContainer.querySelectorAll(
          ".favorite-toggle__checkbox"
        );
        heartInputs.forEach((input) => {
          input.checked = true;
        });
      }
    })
    .catch((error) => console.error(error));
});
