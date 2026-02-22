fetch("../../components/payment/y-c-payment-form.html")
  .then((response) => response.text())
  .then((data) => {
    document.querySelector('[data-y="payment"]').innerHTML = data;

    initializePayment();
  });

function initializePayment() {
  const paymentRadios = document.querySelectorAll(
    'input[name="payment-method"]'
  );
  const creditCardFields = document.getElementById("credit-card-fields");
  const paymentForm = document.querySelector("#payment-form"); // Form ID is now specific
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

  // Input Formatting & Focus Effects
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

  // --- JustValidate Implementation ---
  const validator = new JustValidate("#payment-form", {
    errorFieldCssClass: "is-invalid",
    errorLabelStyle: {
      color: "#dc3545",
      fontSize: "12px",
      marginTop: "5px",
    },
  });

  validator
    .addField("#first-name", [
      {
        rule: "required",
        errorMessage: "الاسم مطلوب",
      },
    ])
    .addField("#delivery-email", [
      {
        rule: "required",
        errorMessage: "البريد الإلكتروني مطلوب",
      },
      {
        rule: "email",
        errorMessage: "بريد إلكتروني غير صحيح",
      },
    ])
    .addField("#phone", [
      {
        rule: "required",
        errorMessage: "رقم الجوال مطلوب",
      },
      {
        rule: "customRegexp",
        value: /^05\d{8}$/,
        errorMessage: "يجب أن يبدأ بـ 05 ويتكون من 10 أرقام",
      },
    ])
    .addField("#address", [
      {
        rule: "required",
        errorMessage: "العنوان مطلوب",
      },
    ])
    .addField("#password", [
      {
        rule: "required",
        errorMessage: "كلمة المرور مطلوبة",
      },
      {
         rule: 'minLength',
         value: 6,
         errorMessage: 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
      }
    ])
    // Conditional validation for Credit Card
    .addRequiredGroup("#payment-form input[name='payment-method']", "اختر طريقة دفع")
    .onSuccess((event) => {
        // Only if validation passes
        openPaymentPopup();
    });
    
    // Add fields dynamically or handle their display logic if needed for card validation
    // Since card fields are always in DOM but hidden, we might need to add them conditionally
    // However, for simplicity, let's validate them only if payment method is card.
    // JustValidate supports conditional validation but it's easier to add/remove fields on change.
    
    const paymentMethodRadios = document.querySelectorAll('input[name="payment-method"]');
    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', () => {
             updateValidationRules(radio.value);
        });
    });

    // Initial check
    const checkedRadio = document.querySelector('input[name="payment-method"]:checked');
    if(checkedRadio) updateValidationRules(checkedRadio.value);


    function updateValidationRules(paymentMethod) {
        // Remove card fields if they exist to avoid validating hidden fields
        validator.removeField("#card-holder-name");
        validator.removeField("#card-number");
        validator.removeField("#expiry-date");
        validator.removeField("#cvv");

        if (paymentMethod === 'card') {
            validator
                .addField("#card-holder-name", [{ rule: "required", errorMessage: "اسم حامل البطاقة مطلوب" }])
                .addField("#card-number", [{ rule: "required", errorMessage: "رقم البطاقة مطلوب" }])
                .addField("#expiry-date", [{ rule: "required", errorMessage: "تاريخ الانتهاء مطلوب" }])
                .addField("#cvv", [{ rule: "required", errorMessage: "رمز CVV مطلوب" }]);
        }
    }


  // payment success popup logic
  const openPaymentPopup = () => {
    if (paymentSuccessPopup) paymentSuccessPopup.style.display = "flex";
  };
  const closePaymentPopup = () => {
    if (paymentSuccessPopup) paymentSuccessPopup.style.display = "none";
  };

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
