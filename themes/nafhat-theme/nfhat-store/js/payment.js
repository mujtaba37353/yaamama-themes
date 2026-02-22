document.addEventListener("DOMContentLoaded", function () {
  const paymentRadios = document.querySelectorAll(
    'input[name="paymentMethod"]'
  );
  const creditCardInputs = document.getElementById("credit-card-inputs");

  function togglePaymentInputs() {
    const selected = document.querySelector(
      'input[name="paymentMethod"]:checked'
    );

    if (selected && selected.value === "online") {
      if (creditCardInputs) {
        creditCardInputs.style.display = "grid";
        const inputs = creditCardInputs.querySelectorAll("input");
        inputs.forEach((input) => (input.disabled = false));
      }
    } else {
      if (creditCardInputs) {
        creditCardInputs.style.display = "none";
        const inputs = creditCardInputs.querySelectorAll("input");
        inputs.forEach((input) => (input.disabled = true));
      }
    }
  }

  togglePaymentInputs();

  paymentRadios.forEach((radio) => {
    radio.addEventListener("change", togglePaymentInputs);
  });

  const noteCheckbox = document.getElementById("addNote");
  const noteTextarea = document.getElementById("order-note");

  if (noteCheckbox && noteTextarea) {
    noteTextarea.style.display = noteCheckbox.checked ? "block" : "none";

    noteCheckbox.addEventListener("change", function () {
      if (this.checked) {
        noteTextarea.style.display = "block";
        noteTextarea.focus();
      } else {
        noteTextarea.style.display = "none";
      }
    });
  }

  const successModal = document.getElementById("payment-success-modal");
  const submitButton = document.querySelector(".submit-order-button");
  const successCard = successModal
    ? successModal.querySelector(".success-card")
    : null;

  const openSuccessModal = () => {
    if (!successModal || !successCard) return;
    successModal.classList.add("is-open");
    successModal.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
    successCard.focus();
  };

  const closeSuccessModal = () => {
    if (!successModal) return;
    successModal.classList.remove("is-open");
    successModal.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  };

  if (submitButton && successModal) {
    submitButton.addEventListener("click", function (event) {
      event.preventDefault();
      openSuccessModal();
    });
  }

  if (successModal) {
    successModal.addEventListener("click", function (event) {
      if (event.target.hasAttribute("data-success-dismiss")) {
        closeSuccessModal();
      }
    });

    document.addEventListener("keydown", function (event) {
      if (
        event.key === "Escape" &&
        successModal.classList.contains("is-open")
      ) {
        closeSuccessModal();
      }
    });
  }
});
