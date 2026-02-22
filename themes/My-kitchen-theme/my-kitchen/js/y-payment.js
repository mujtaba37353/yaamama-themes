const paymentContainer = document.querySelector('[data-y="payment"]');
if (paymentContainer && paymentContainer.dataset.realCheckout !== "1") {
  fetch("../../components/payment/y-c-payment-form.html")
    .then((response) => response.text())
    .then((data) => {
      paymentContainer.innerHTML = data;
      if (window.mykitchenResolveAssets) {
        window.mykitchenResolveAssets(paymentContainer);
      }
      initializePayment();
    });
}

function initializePayment() {
  const paymentRadios = document.querySelectorAll(
    'input[name="payment-method"]'
  );
  const creditCardFields = document.getElementById("credit-card-fields");
  const paymentForm = document.querySelector(".payment-form");
  const paymentSubmitBtn = paymentForm?.querySelector(".btn-primary");
  const paymentSuccessPopup = document.getElementById("payment-success-popup");
  const paymentSuccessClose = document.getElementById(
    "btn-close-payment-success"
  );


  function handlePaymentSelection() {
    const selected = Array.from(paymentRadios).find((r) => r.checked);
    const isCard = selected && selected.value === "card";
    if (creditCardFields) {
      creditCardFields.style.display = isCard ? "flex" : "none";
    }
  }

  if (paymentRadios && paymentRadios.length) {
    paymentRadios.forEach((radio) =>
      radio.addEventListener("change", handlePaymentSelection)
    );
  }

  handlePaymentSelection();


  const cardNumberInput = document.getElementById("card-number");
  if (cardNumberInput) {
    cardNumberInput.addEventListener("input", function (e) {
      let value = e.target.value.replace(/\s/g, "");
      value = value.replace(/(.{4})/g, "$1 ");
      e.target.value = value.trim();
    });

    cardNumberInput.addEventListener("focus", function () {
      this.style.opacity = "1";
      this.style.background = "#f9f9f9";
    });

    cardNumberInput.addEventListener("blur", function () {
      if (!this.value) {
        this.style.opacity = "0";
        this.style.background = "transparent";
      }
    });
  }


  const expiryInput = document.getElementById("expiry-date");
  if (expiryInput) {
    expiryInput.addEventListener("input", function (e) {
      let value = e.target.value.replace(/\D/g, "");
      if (value.length >= 2) {
        value = value.substring(0, 2) + " / " + value.substring(2, 4);
      }
      e.target.value = value;
    });

    expiryInput.addEventListener("focus", function () {
      this.style.opacity = "1";
      this.style.background = "#f9f9f9";
    });

    expiryInput.addEventListener("blur", function () {
      if (!this.value) {
        this.style.opacity = "0";
        this.style.background = "transparent";
      }
    });
  }


  const allInputs = document.querySelectorAll(
    ".hidden-input, .hidden-textarea"
  );
  allInputs.forEach((input) => {
    input.addEventListener("focus", function () {
      this.style.opacity = "1";
      this.style.background = "#f9f9f9";
    });

    input.addEventListener("blur", function () {
      if (!this.value) {
        this.style.opacity = "0";
        this.style.background = "transparent";
      }
    });
  });


  const phoneInput = document.getElementById("phone");
  if (phoneInput) {
    phoneInput.addEventListener("input", function (e) {
      let value = e.target.value.replace(/\D/g, "");
      if (value.startsWith("05")) {
        e.target.value = value.substring(0, 10);
      }
    });
  }

  // payment success popup
  const openPaymentPopup = () => {
    if (paymentSuccessPopup) paymentSuccessPopup.style.display = "flex";
  };
  const closePaymentPopup = () => {
    if (paymentSuccessPopup) paymentSuccessPopup.style.display = "none";
  };

  if (paymentSubmitBtn) {
    paymentSubmitBtn.addEventListener("click", function (e) {
      e.preventDefault();
      openPaymentPopup();
    });
  }

  if (paymentSuccessClose) {
    paymentSuccessClose.addEventListener("click", closePaymentPopup);
  }

  if (paymentSuccessPopup) {
    paymentSuccessPopup.addEventListener("click", function (e) {
      if (e.target === paymentSuccessPopup) {
        closePaymentPopup();
      }
    });
  }
}

