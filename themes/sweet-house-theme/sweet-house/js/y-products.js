fetch("../../components/products/y-c-products.html")
  .then((response) => response.text())
  .then((data) => {
    const temp = document.createElement("div");
    temp.innerHTML = data;

    const productsContainer = document.querySelector('[data-y="products"]');

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
  })
  .catch((error) => console.error(error));
