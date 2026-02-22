fetch("../../components/about us/y-c-about-us.html")
  .then((response) => response.text())
  .then((data) => {
    const container = document.querySelector('[data-y="about-us"]');
    if (!container) return;
    container.innerHTML = data;
    if (window.mykitchenResolveAssets) {
      window.mykitchenResolveAssets(container);
    }
  });
