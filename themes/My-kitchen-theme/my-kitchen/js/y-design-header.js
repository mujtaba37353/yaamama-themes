const designHeader = document.querySelector('[data-y="design-header"]');
if (designHeader) {
  fetch("../../components/pages header/y-c-design-header.html")
    .then((response) => response.text())
    .then((data) => {
      designHeader.innerHTML = data;
      if (window.mykitchenResolveAssets) {
        window.mykitchenResolveAssets(designHeader);
      }
    });
}
