fetch("../../components/home/y-c-last-chance-section.html")
  .then((response) => response.text())
  .then((data) => {
    const container = document.querySelector('[data-y="last-chance-sec"]');
    if (!container) return;
    container.innerHTML = data;
    if (window.mykitchenResolveAssets) {
      window.mykitchenResolveAssets(container);
    }
  });
