const paymentSummary = document.querySelector('[data-y="payment-summary"]');
if (paymentSummary && paymentSummary.dataset.realSummary !== "1") {
  fetch("../../components/payment/y-c-payment-summary-card.html")
    .then((response) => response.text())
    .then((data) => {
      paymentSummary.innerHTML = data;
      if (window.mykitchenResolveAssets) {
        window.mykitchenResolveAssets(paymentSummary);
      }
    });
}
