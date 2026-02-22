fetch("../../components/less than 99/y-c-less-than.html")
  .then((response) => response.text())
  .then((data) => {
    const temp = document.createElement("div");
    temp.innerHTML = data;

    const productsContainer = document.querySelector('[data-y="less-than"]');
    if (!productsContainer) return;
    productsContainer.innerHTML = temp.innerHTML;
    if (window.mykitchenResolveAssets) {
      window.mykitchenResolveAssets(productsContainer);
    }
  })
  .catch((error) => console.error(error));
