const productsContainer = document.querySelector('[data-y="products"]');
if (
  !productsContainer ||
  productsContainer.dataset.realProducts === "1" ||
  productsContainer.querySelector(".product-card")
) {
  // Real products already rendered by PHP.
} else {
  fetch("../../components/products/y-c-products.html")
    .then((response) => response.text())
    .then((data) => {
      const temp = document.createElement("div");
      temp.innerHTML = data;
      if (window.mykitchenResolveAssets) {
        window.mykitchenResolveAssets(temp);
      }

      if (
        document.title.includes("منتجات اخرى") ||
        document.title.includes("المنتج")
      ) {
        const productCards = temp.querySelectorAll(".product-card");
        const limited = Array.from(productCards).slice(0, 10);

        const productsWrapper = document.createElement("div");
        productsWrapper.className = "products";

        limited.forEach((card) => productsWrapper.appendChild(card));

        productsContainer.innerHTML = "";
        productsContainer.appendChild(productsWrapper);
      } else {
        productsContainer.innerHTML = temp.innerHTML;
      }
      if (window.mykitchenResolveAssets) {
        window.mykitchenResolveAssets(productsContainer);
      }
    })
    .catch((error) => console.error(error));
}
