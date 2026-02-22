fetch("../../components/cart/y-c-cart-table.html")
    .then((response) => response.text())
    .then((data) => {
        document.querySelector('[data-y="cart-table"]').innerHTML = data;
    });
