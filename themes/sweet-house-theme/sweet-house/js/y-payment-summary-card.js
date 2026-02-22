fetch("../../components/payment/y-c-payment-summary-card.html")
  .then((response) => response.text())
  .then((data) => {
    document.querySelector('[data-y="payment-summary"]').innerHTML = data;
  });
