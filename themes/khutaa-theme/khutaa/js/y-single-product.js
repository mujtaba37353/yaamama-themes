fetch("../../components/single product/y-c-single-product.html")
    .then((response) => response.text())
    .then((data) => {
        document.querySelector('[data-y="single-product"]').innerHTML = data;
    });
