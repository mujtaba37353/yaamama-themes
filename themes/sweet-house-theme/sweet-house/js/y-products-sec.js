fetch("../../components/home/y-c-products-sec.html")
  .then((response) => response.text())
  .then((data) => {
    document.querySelector('[data-y="products-sec"]').innerHTML = data;
  });
