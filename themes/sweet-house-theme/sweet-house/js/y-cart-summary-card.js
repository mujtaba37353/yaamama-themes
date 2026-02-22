fetch("../../components/cards/y-c-cart-summary-card.html")
  .then((response) => response.text())
  .then((data) => {
    document.querySelector('[data-y="cart-summary"]').innerHTML = data;
  });
