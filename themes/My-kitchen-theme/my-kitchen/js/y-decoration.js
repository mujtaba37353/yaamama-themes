fetch("../../components/decorations/y-c-decorations.html")
  .then((response) => response.text())
  .then((data) => {
    const temp = document.createElement("div");
    temp.innerHTML = data;

    const productsContainer = document.querySelector('[data-y="decorations"]');
    productsContainer.innerHTML = temp.innerHTML;
  })
  .catch((error) => console.error(error));
