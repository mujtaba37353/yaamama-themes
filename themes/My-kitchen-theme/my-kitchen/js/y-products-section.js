fetch("../../components/home/y-c-products-section.html")
  .then((response) => response.text())
  .then((data) => {
    const container = document.querySelector('[data-y="products-sec"]');
    if (!container) return;
    container.innerHTML = data;
    if (window.mykitchenResolveAssets) {
      window.mykitchenResolveAssets(container);
    }
  });
