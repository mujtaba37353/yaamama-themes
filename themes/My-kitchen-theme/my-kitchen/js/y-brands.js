const container = document.querySelector('[data-y="brands"]');
if (container && container.dataset.realBrands !== "1") {
  fetch("../../components/home/y-c-brands.html")
    .then((response) => response.text())
    .then((data) => {
      if (!container) return;
      container.innerHTML = data;
      if (window.mykitchenResolveAssets) {
        window.mykitchenResolveAssets(container);
      }
    });
}
