document.addEventListener("DOMContentLoaded", function () {
  const paymentRadios = document.querySelectorAll(
    'input[name="payment_method"]'
  );
  const paymentRows = document.querySelectorAll(".payment-method-row");

  function updatePaymentDisplay() {
    paymentRows.forEach((row) => {
      const radio = row.querySelector('input[name="payment_method"]');
      const body = row.querySelector(".method-body");

      if (radio && body) {
        if (radio.checked) {
          body.style.display = "block";

          const inputs = body.querySelectorAll("input");
          inputs.forEach((input) => (input.disabled = false));
        } else {
          body.style.display = "none";

          const inputs = body.querySelectorAll("input");
          inputs.forEach((input) => (input.disabled = true));
        }
      }
    });
  }

  updatePaymentDisplay();

  paymentRadios.forEach((radio) => {
    radio.addEventListener("change", updatePaymentDisplay);
  });

  const noteSection = document.querySelector(".note-section");

  if (noteSection) {
    const noteCheckbox = noteSection.querySelector("input[type='checkbox']");
    const noteBody = noteSection.querySelector(".method-body");
    const noteTextarea = noteBody ? noteBody.querySelector("textarea") : null;

    if (noteCheckbox && noteBody) {
      const toggleNote = () => {
        if (noteCheckbox.checked) {
          noteBody.style.display = "block";
          if (noteTextarea) {
            noteTextarea.disabled = false;
            noteTextarea.focus();
          }
        } else {
          noteBody.style.display = "none";
          if (noteTextarea) noteTextarea.disabled = true;
        }
      };

      toggleNote();

      noteCheckbox.addEventListener("change", toggleNote);
    }
  }

  const successModal = document.getElementById("payment-success-modal");
  const submitButton = document.querySelector(".btn-submit");
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

  // if (submitButton && successModal) {
  //   submitButton.addEventListener("click", function (event) {
  //     event.preventDefault();
  //     openSuccessModal();
  //   });
  // }

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
