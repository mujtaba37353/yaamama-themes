fetch("../../components/electronics/y-c-electronics.html")
  .then((response) => response.text())
  .then((data) => {
    const temp = document.createElement("div");
    temp.innerHTML = data;

    const productsContainer = document.querySelector('[data-y="electronics"]');
    productsContainer.innerHTML = temp.innerHTML;
  })
  .catch((error) => console.error(error));
