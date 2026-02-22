fetch("../../components/payment/y-c-payment-form.html")
  .then((response) => response.text())
  .then((data) => {
    const paymentContainer = document.querySelector('[data-y="payment"]');
    if (paymentContainer) {
      paymentContainer.innerHTML = data;
      initializePaymentForm();
    }
  })
  .catch((error) => console.error(error));

function initializePaymentForm() {
  const paymentRadios = document.querySelectorAll(
    'input[name="payment-method"]'
  );
  const creditDetails = document.getElementById("credit-card-details");
  const stcDetails = document.getElementById("stc-pay-details");

  function toggleDetails() {
    if (creditDetails) creditDetails.style.display = "none";
    if (stcDetails) stcDetails.style.display = "none";

    const selected = document.querySelector(
      'input[name="payment-method"]:checked'
    );
    if (selected) {
      if (selected.value === "credit-card" && creditDetails) {
        creditDetails.style.display = "block";
      } else if (selected.value === "stc-pay" && stcDetails) {
        stcDetails.style.display = "block";
      }
    }
  }

  toggleDetails();

  paymentRadios.forEach((radio) => {
    radio.addEventListener("change", toggleDetails);
  });

  const submitBtn = document.getElementById("submit-payment-btn");
  const popup = document.getElementById("payment-success-popup");

  if (submitBtn && popup) {
    submitBtn.addEventListener("click", (e) => {
      e.preventDefault();
      popup.style.display = "flex";
    });
  }
}
