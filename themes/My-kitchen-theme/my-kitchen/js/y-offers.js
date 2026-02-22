fetch("../../components/offers/y-c-offers.html")
  .then((response) => response.text())
  .then((data) => {
    const temp = document.createElement("div");
    temp.innerHTML = data;

    const productsContainer = document.querySelector('[data-y="offers"]');
    productsContainer.innerHTML = temp.innerHTML;
  })
  .catch((error) => console.error(error));
