fetch("../../components/products/y-c-related-products.html")
  .then((response) => response.text())
  .then((data) => {
    const relatedProducts = document.querySelector('[data-y="related-products"]');
    if (relatedProducts) {
      relatedProducts.innerHTML = data;
    }
  });

