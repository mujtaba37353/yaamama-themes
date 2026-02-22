fetch("../../components/home/y-c-offers-section.html")
  .then((response) => response.text())
  .then((data) => {
    const container = document.querySelector('[data-y="offers-sec"]');
    if (!container) return;
    container.innerHTML = data;
    if (window.mykitchenResolveAssets) {
      window.mykitchenResolveAssets(container);
    }
  });
