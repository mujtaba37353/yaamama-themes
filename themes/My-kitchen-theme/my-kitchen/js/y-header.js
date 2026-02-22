const headerContainer = document.querySelector('[data-y="header"]');
if (headerContainer) {
  fetch("../../components/home/y-c-header.html")
    .then((response) => response.text())
    .then((data) => {
      headerContainer.innerHTML = data;
      if (window.mykitchenResolveAssets) {
        window.mykitchenResolveAssets(headerContainer);
      }
    });
}
