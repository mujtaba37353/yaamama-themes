fetch("../../components/for home/y-c-for-home.html")
  .then((response) => response.text())
  .then((data) => {
    const temp = document.createElement("div");
    temp.innerHTML = data;

    const productsContainer = document.querySelector('[data-y="for-home"]');
    productsContainer.innerHTML = temp.innerHTML;
  })
  .catch((error) => console.error(error));
