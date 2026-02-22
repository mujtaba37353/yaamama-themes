// ============================================
// Validation Configuration
// ============================================
const ValidationConfig = {
  errorMessages: {
    ar: {
      name: "الاسم بالكامل مطلوب",
      message: "نص الرسالة مطلوب",
      messageMin: "نص الرسالة يجب أن يكون {length} أحرف على الأقل",
      email: "البريد الإلكتروني غير صحيح",
      emailRequired: "البريد الإلكتروني مطلوب",
      emailInvalid: "يرجى استخدام الحروف الإنجليزية فقط في البريد الإلكتروني",
      password: "كلمة المرور مطلوبة",
      passwordMin: "كلمة المرور يجب أن تكون {length} أحرف على الأقل",
      passwordWeak: "كلمة المرور ضعيفة - استخدم أحرف كبيرة وصغيرة وأرقام",
      passwordMatch: "كلمة المرور غير متطابقة",
      phone: "رقم الجوال مطلوب",
      phoneLength: "رقم الجوال يجب أن يكون {length} أرقام",
      phoneFormat: "رقم الجوال يجب أن يبدأ بـ {prefix}",
      phoneInvalid: "رقم الجوال غير صحيح",
      fullName: "الاسم الكامل مطلوب",
      nameFormat: "الاسم يجب أن يحتوي على حرفين على الأقل",
      address: "العنوان مطلوب",
      addressMin: "العنوان يجب أن يكون {length} أحرف على الأقل",
      paymentMethod: "يجب اختيار طريقة الدفع",
      cardNumber: "رقم البطاقة مطلوب",
      cardNumberInvalid: "رقم البطاقة غير صحيح",
      cardNumberLength: "رقم البطاقة يجب أن يكون بين 13-19 رقم",
      cardholderName: "اسم حامل البطاقة مطلوب",
      /*change*/
      cardholderNameInvalid: "اسم حامل البطاقة يجب أن يكون أحرف إنجليزية فقط",
      /*end of change*/
      expiryMonth: "الشهر مطلوب",
      expiryYear: "السنة مطلوبة",
      expiryInvalid: "تاريخ انتهاء البطاقة غير صحيح",
      expiryPast: "البطاقة منتهية الصلاحية",
      cvv: "CVV مطلوب",
      cvvInvalid: "CVV يجب أن يكون من 3 إلى 5 أرقام",
      firstName: "الاسم الأول مطلوب",
      lastName: "الاسم الأخير مطلوب",
      street: "الشارع مطلوب",
      district: "الحي مطلوب",
      city: "المدينة مطلوبة",
      region: "المنطقة مطلوبة",
      postalCode: "الرمز البريدي مطلوب",
      postalCodeInvalid: "الرمز البريدي يجب أن يكون 5 أرقام",
      buildingNo: "رقم المبنى مطلوب",
      buildingNoInvalid: "رقم المبنى يجب أن يكون أرقام فقط",
      unitNo: "رقم الوحدة مطلوب",
      unitNoInvalid: "رقم الوحدة يجب أن يكون أرقام فقط",
    }
  },
  phoneConfig: {
    length: 10,
    prefix: "05",
    countryCode: "+966"
  },
  passwordConfig: {
    minLength: 8,
    requireStrong: false
  },
    messageConfig: {
      minLength: 10
    },
    addressConfig: {
      minLength: 10
    },
};

// ============================================
// Utility Functions
// ============================================

function validateCreditCard(cardNumber) {
  const cleanNumber = cardNumber.replace(/\s+/g, '');
  
  if (!/^\d{13,19}$/.test(cleanNumber)) {
    return false;
  }
  
  let sum = 0;
  let isEven = false;
  
  for (let i = cleanNumber.length - 1; i >= 0; i--) {
    let digit = parseInt(cleanNumber[i]);
    
    if (isEven) {
      digit *= 2;
      if (digit > 9) {
        digit -= 9;
      }
    }
    
    sum += digit;
    isEven = !isEven;
  }
  
  return sum % 10 === 0;
}

function validateExpiryDate(month, year) {
  if (!month || !year) return false;
  
  const currentDate = new Date();
  const currentYear = currentDate.getFullYear();
  const currentMonth = currentDate.getMonth() + 1;
  
  const expiryYear = parseInt(year);
  const expiryMonth = parseInt(month);
  
  let fullYear = expiryYear;
  if (expiryYear < 100) {
    fullYear = 2000 + expiryYear;
  }
  
  if (fullYear < currentYear) return false;
  if (fullYear === currentYear && expiryMonth < currentMonth) return false;
  
  if (expiryMonth < 1 || expiryMonth > 12) return false;
  
  return true;
}

    function validateCVV(cvv, cardNumber = "") {
      const cleanCVV = cvv.replace(/\s+/g, '');
      
      const isAmex = cardNumber.startsWith('34') || cardNumber.startsWith('37');
      // Update logic: Allow 3 to 5 digits as requested
      // Originally: expectedLength = isAmex ? 4 : 3;
      // Now checking range 3-5
      
      return /^\d+$/.test(cleanCVV) && (cleanCVV.length >= 3 && cleanCVV.length <= 5);
    }

function isStrongPassword(password) {
  const hasUpperCase = /[A-Z]/.test(password);
  const hasLowerCase = /[a-z]/.test(password);
  const hasNumbers = /\d/.test(password);
  const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
  
  return hasUpperCase && hasLowerCase && hasNumbers;
}

function validateSaudiPhone(phone) {
  const cleaned = phone.replace(/\s+/g, '');
  return /^05\d{8}$/.test(cleaned);
}

function sanitizeInput(value) {
  return value.trim().replace(/\s+/g, ' ');
}

function showSuccessPopup(duration = 3000) {
  const popupToggle = document.getElementById("payment-success-popup");
  if (popupToggle) {
    popupToggle.checked = true;
    setTimeout(() => {
      popupToggle.checked = false;
    }, duration);
  }
}

// ============================================
// Form Validators
// ============================================

document.addEventListener("DOMContentLoaded", () => {
  const messages = ValidationConfig.errorMessages.ar;
  
  // ============================================
  // Contact Form Validation
  // ============================================
  const contactForm = document.querySelector("#contact-form");
  if (contactForm) { 
    contactForm.addEventListener('submit', (e) => e.preventDefault());

    const validator = new JustValidate(contactForm, {
      validateBeforeSubmitting: true,
    });

    validator
      .addField("#name", [
        { rule: "required", errorMessage: messages.fullName },
        { rule: "minLength", value: 2, errorMessage: messages.nameFormat },
      ])
      .addField("#number", [
        { rule: "required", errorMessage: messages.phone },
        { 
          rule: "minLength", 
          value: ValidationConfig.phoneConfig.length, 
          errorMessage: messages.phoneLength.replace('{length}', ValidationConfig.phoneConfig.length)
        },
        { 
          rule: "maxLength", 
          value: ValidationConfig.phoneConfig.length, 
          errorMessage: messages.phoneLength.replace('{length}', ValidationConfig.phoneConfig.length)
        },
        {
          validator: (value) => validateSaudiPhone(value),
          errorMessage: messages.phoneFormat.replace('{prefix}', ValidationConfig.phoneConfig.prefix),
        }
      ])
      .addField("#email", [
        { rule: "required", errorMessage: messages.emailRequired },
        { rule: "email", errorMessage: messages.email },
        {
          validator: (value) => !/[\u0600-\u06FF]/.test(value),
          errorMessage: messages.emailInvalid,
        }
      ])
      .addField("#message", [
        { rule: "required", errorMessage: messages.message },
        { 
          rule: "minLength", 
          value: ValidationConfig.messageConfig?.minLength || 10, 
          errorMessage: messages.messageMin.replace('{length}', ValidationConfig.messageConfig?.minLength || 10)
        },
      ])
      .onSuccess((event) => {
        // Show success message
        alert("تم إرسال رسالتك بنجاح!");
        contactForm.reset();
      });
  }

  // ============================================
  // Login Form Validation
  // ============================================
  const loginForm = document.querySelector("#login-form");
  if (loginForm) {
    const validator = new JustValidate(loginForm, {
      validateBeforeSubmitting: true,
    });
    
    // WooCommerce form uses #username (accepts email or username)
    validator
      .addField("#username", [
        { rule: "required", errorMessage: messages.emailRequired },
      ])
      .addField("#password", [
        { rule: "required", errorMessage: messages.password },
      ])
      .onSuccess(() => {
        loginForm.submit();
      });
  }

  // ============================================
  // Signup Form Validation
  // ============================================
  const signupForm = document.querySelector("#signup-form");
  if (signupForm) {
    const validator = new JustValidate(signupForm, {
      validateBeforeSubmitting: true,
    });
    
    // WooCommerce uses #reg_email, #reg_phone, #reg_password, #reg_password2
    validator
      .addField("#reg_email", [
        { rule: "required", errorMessage: messages.emailRequired },
        { rule: "email", errorMessage: messages.email },
        {
          validator: (value) => !/[\u0600-\u06FF]/.test(value),
          errorMessage: messages.emailInvalid,
        }
      ])
      .addField("#reg_phone", [
        { rule: "required", errorMessage: messages.phone },
        { 
          rule: "minLength", 
          value: ValidationConfig.phoneConfig.length, 
          errorMessage: messages.phoneLength.replace('{length}', ValidationConfig.phoneConfig.length)
        },
        { 
          rule: "maxLength", 
          value: ValidationConfig.phoneConfig.length, 
          errorMessage: messages.phoneLength.replace('{length}', ValidationConfig.phoneConfig.length)
        },
        {
          validator: (value) => validateSaudiPhone(value),
          errorMessage: messages.phoneFormat.replace('{prefix}', ValidationConfig.phoneConfig.prefix),
        }
      ])
      .addField("#reg_password", [
        { rule: "required", errorMessage: messages.password },
        { 
          rule: "minLength", 
          value: ValidationConfig.passwordConfig.minLength, 
          errorMessage: messages.passwordMin.replace('{length}', ValidationConfig.passwordConfig.minLength)
        },
        {
          validator: (value) => {
            if (!ValidationConfig.passwordConfig.requireStrong) return true;
            return isStrongPassword(value);
          },
          errorMessage: messages.passwordWeak,
        }
      ])
      .addField("#reg_password2", [
        { rule: "required", errorMessage: messages.passwordMatch },
        { 
          validator: (value, fields) => {
            return value === fields['#reg_password'].elem.value;
          },
          errorMessage: messages.passwordMatch,
        },
      ])
      .onSuccess(() => {
        signupForm.submit();
      });
  }

  // ============================================
  // Forget Password Form Validation
  // ============================================
  const forgetPasswordForm = document.querySelector("#forget-password-form");
  if (forgetPasswordForm) {
    const validator = new JustValidate(forgetPasswordForm, {
      validateBeforeSubmitting: true,
    });
    
    // WooCommerce uses #user_login for email/username
    validator
      .addField("#user_login", [
        { rule: "required", errorMessage: messages.emailRequired },
      ])
      .onSuccess(() => {
        forgetPasswordForm.submit();
      });
  }

  // ============================================
  // Profile Form Validation (WooCommerce Edit Account)
  // Uses: account_first_name, account_last_name, account_email, password_current, password_1, password_2
  // ============================================
  const profileForm = document.querySelector("#profile-form");
  if (profileForm) {
    const validator = new JustValidate(profileForm, {
      validateBeforeSubmitting: true,
    });

    validator
      .addField("#account_first_name", [
        { rule: "required", errorMessage: messages.fullName },
        { rule: "minLength", value: 2, errorMessage: messages.nameFormat },
      ])
      .addField("#account_email", [
        { rule: "required", errorMessage: messages.emailRequired },
        { rule: "email", errorMessage: messages.email },
        {
          validator: (value) => !/[\u0600-\u06FF]/.test(value),
          errorMessage: messages.emailInvalid,
        }
      ]);

    if (document.getElementById("password_current")) {
      validator.addField("#password_current", [
        {
          validator: (value, fields) => {
            const p1 = document.getElementById("password_1");
            if (p1 && p1.value.length > 0) {
              return value && value.length > 0;
            }
            return true;
          },
          errorMessage: messages.password,
        }
      ]);
    }
    if (document.getElementById("password_1")) {
      validator.addField("#password_1", [
        {
          validator: (value, fields) => true,
        },
        {
          rule: "minLength",
          value: ValidationConfig.passwordConfig.minLength,
          errorMessage: messages.passwordMin.replace('{length}', ValidationConfig.passwordConfig.minLength),
        }
      ]);
    }
    if (document.getElementById("password_2")) {
      validator.addField("#password_2", [
        {
          validator: (value, fields) => {
            const p1 = document.getElementById("password_1");
            if (p1 && p1.value.length > 0) {
              return value === p1.value;
            }
            return true;
          },
          errorMessage: messages.passwordMatch,
        }
      ]);
    }

    validator.onSuccess(() => {
      profileForm.submit();
    });
  }

  // ============================================
  // Address Form Validation Helper
  // Supports both custom (edit-*) and WooCommerce (billing_*) field IDs
  // ============================================
  function addAddressValidation(formSelector, prefix) {
    const form = document.querySelector(formSelector);
    if (!form) return;

    const fieldDefs = [
      { id: `${prefix}-first-name`, rules: [{ rule: "required", errorMessage: messages.firstName }, { rule: "minLength", value: 2, errorMessage: messages.nameFormat }] },
      { id: `${prefix}-last-name`, rules: [{ rule: "required", errorMessage: messages.lastName }, { rule: "minLength", value: 2, errorMessage: messages.nameFormat }] },
      { id: `${prefix}-street`, rules: [{ rule: "required", errorMessage: messages.street }] },
      { id: `${prefix}-city`, rules: [{ rule: "required", errorMessage: messages.city }] },
      { id: `${prefix}-postal-code`, rules: [{ rule: "required", errorMessage: messages.postalCode }, { validator: (value) => /^\d{5}$/.test(String(value).trim()), errorMessage: messages.postalCodeInvalid }] },
    ];

    const woocommerceFields = [
      { id: "billing_first_name", rules: [{ rule: "required", errorMessage: messages.firstName }, { rule: "minLength", value: 2, errorMessage: messages.nameFormat }] },
      { id: "billing_last_name", rules: [{ rule: "required", errorMessage: messages.lastName }, { rule: "minLength", value: 2, errorMessage: messages.nameFormat }] },
      { id: "billing_address_1", rules: [{ rule: "required", errorMessage: messages.street }] },
      { id: "billing_city", rules: [{ rule: "required", errorMessage: messages.city }] },
      { id: "billing_postcode", rules: [{ rule: "required", errorMessage: messages.postalCode }, { validator: (value) => /^\d{5}$/.test(String(value || '').trim()), errorMessage: messages.postalCodeInvalid }] },
    ];

    const useCustomFields = !!document.getElementById(`${prefix}-first-name`);
    const fieldsToAdd = useCustomFields ? fieldDefs : woocommerceFields;
    const existingFields = fieldsToAdd.filter(({ id }) => document.getElementById(id));
    if (existingFields.length === 0) return;

    const validator = new JustValidate(form, {
      validateBeforeSubmitting: true,
    });
    existingFields.forEach(({ id, rules }) => validator.addField(`#${id}`, rules));

    validator.onSuccess(() => {
      const popup = document.getElementById("address-saved-popup");
      if (popup) {
        popup.checked = true;
        setTimeout(() => { popup.checked = false; }, 3000);
      }
      form.submit();
    });
  }

  addAddressValidation("#address-edit-form", "edit");
  addAddressValidation("#address-new-form", "new");

  // ============================================
  // Payment Form Validation
  // ============================================
  const paymentForm = document.querySelector("#payment-form");
  if (paymentForm) {
    const cardFieldsContainer = document.querySelector(".card");

    /** Format Card Number (Spacing every 4 digits)*/
    const cardNumberInput = document.getElementById('card-number');
    if (cardNumberInput) {
      cardNumberInput.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 0) {
           value = value.match(/.{1,4}/g)?.join(' ') || value;
        }
        e.target.value = value;
      });
    }

    /** Allow only numbers in expiry month, expiry year, and CVV */
    ['expiry-month', 'expiry-year', 'cvv'].forEach((id) => {
      const input = document.getElementById(id);
      if (input) {
        input.addEventListener('input', (e) => {
          e.target.value = e.target.value.replace(/\D/g, '');
        });
      }
    });

    /** Allow only numbers in phone field */
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
      phoneInput.addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/\D/g, '');
      });
    }

    /** Allow only English letters and spaces in cardholder name */
    const cardholderInput = document.getElementById('cardholder-name');
    if (cardholderInput) {
      cardholderInput.addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/[^a-zA-Z\s]/g, '');
      });
    }

    // Toggle bank transfer details visibility based on payment method
    // Note: card fields visibility is handled purely by CSS (form:has(#visa:checked) .card)
    const paymentRadios = document.querySelectorAll('#payment-method-group input[type="radio"]');
    const bankTransferDetails = document.getElementById('bank-transfer-details');

    paymentRadios.forEach(radio => {
      radio.addEventListener('change', (e) => {
        const isBank = e.target.id === 'bank-transfer';
        if (bankTransferDetails) {
          bankTransferDetails.style.display = isBank ? 'flex' : 'none';
        }
      });
    });

    const validator = new JustValidate(paymentForm, {
      validateBeforeSubmitting: true,
      focusInvalidField: true,
      lockForm: true,
    });

    // Check if card payment is selected
    const isCardPaymentSelected = () => {
      const visa = document.getElementById('visa');
      return visa && visa.checked;
    };

    // --- Personal Information Fields ---
    validator
      .addField("#full-name", [
        { rule: "required", errorMessage: messages.fullName },
        {
          validator: (value) => sanitizeInput(value).length >= 2,
          errorMessage: messages.nameFormat,
        }
      ])
      .addField("#email", [
        { rule: "required", errorMessage: messages.emailRequired },
        { rule: "email", errorMessage: messages.email },
        /*change*/
        {
          validator: (value) => /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value),
          errorMessage: messages.emailInvalid,
        }
        /*end of change*/
      ])
      .addField("#phone", [
        { rule: "required", errorMessage: messages.phone },
        { 
          rule: "minLength", 
          value: ValidationConfig.phoneConfig.length, 
          errorMessage: messages.phoneLength.replace('{length}', ValidationConfig.phoneConfig.length)
        },
        { 
          rule: "maxLength", 
          value: ValidationConfig.phoneConfig.length, 
          errorMessage: messages.phoneLength.replace('{length}', ValidationConfig.phoneConfig.length)
        },
        {
          validator: (value) => validateSaudiPhone(value),
          errorMessage: messages.phoneFormat.replace('{prefix}', ValidationConfig.phoneConfig.prefix),
        }
      ])
      .addField("#address", [
        { rule: "required", errorMessage: messages.address },
        {
          rule: "minLength",
          value: ValidationConfig.addressConfig.minLength,
          errorMessage: messages.addressMin.replace('{length}', ValidationConfig.addressConfig.minLength),
        }
      ])

      // --- Password Field ---
      .addField("#password", [
        { rule: "required", errorMessage: messages.password },
        {
          rule: "minLength",
          value: ValidationConfig.passwordConfig.minLength,
          errorMessage: messages.passwordMin.replace('{length}', ValidationConfig.passwordConfig.minLength),
        }
      ])

      // --- Payment Method Group ---
      .addRequiredGroup('#payment-method-group', messages.paymentMethod)

      // --- Card Fields (only validated when visa/card is selected) ---
      .addField("#card-number", [
        {
          validator: (value) => {
            if (!isCardPaymentSelected()) return true;
            return value && value.replace(/\s+/g, '').length > 0;
          },
          errorMessage: messages.cardNumber,
        },
        {
          validator: (value) => {
            if (!isCardPaymentSelected()) return true;
            return /^\d{13,19}$/.test(value.replace(/\s+/g, ''));
          },
          errorMessage: messages.cardNumberLength,
        },
        {
          validator: (value) => {
            if (!isCardPaymentSelected()) return true;
            return validateCreditCard(value);
          },
          errorMessage: messages.cardNumberInvalid,
        }
      ])
      .addField("#cardholder-name", [
        {
          validator: (value) => {
            if (!isCardPaymentSelected()) return true;
            return value && value.trim().length > 0;
          },
          errorMessage: messages.cardholderName,
        },
        /*change*/
        {
          validator: (value) => {
            if (!isCardPaymentSelected()) return true;
            return /^[a-zA-Z\s]+$/.test(value);
          },
          errorMessage: messages.cardholderNameInvalid,
        }
        /*end of change*/
      ])
      .addField("#expiry-month", [
        {
          validator: (value) => {
            if (!isCardPaymentSelected()) return true;
            return value && value.length > 0;
          },
          errorMessage: messages.expiryMonth,
        },
        {
          validator: (value) => {
            if (!isCardPaymentSelected()) return true;
            const yearField = document.getElementById("expiry-year");
            if (!yearField || !yearField.value) return true;
            return validateExpiryDate(value, yearField.value);
          },
          errorMessage: messages.expiryPast,
        }
      ])
      .addField("#expiry-year", [
        {
          validator: (value) => {
            if (!isCardPaymentSelected()) return true;
            return value && value.length > 0;
          },
          errorMessage: messages.expiryYear,
        }
      ])
      .addField("#cvv", [
        {
          validator: (value) => {
            if (!isCardPaymentSelected()) return true;
            return value && value.length > 0;
          },
          errorMessage: messages.cvv,
        },
        {
          validator: (value) => {
            if (!isCardPaymentSelected()) return true;
            return validateCVV(value, document.getElementById("card-number")?.value || "");
          },
          errorMessage: messages.cvvInvalid,
        }
      ])

      .onSuccess((event) => {
        console.log("Payment form validated successfully");
        
        const formData = {
          fullName: sanitizeInput(document.getElementById("full-name").value),
          email: document.getElementById("email").value.trim(),
          phone: document.getElementById("phone").value.trim(),
          address: sanitizeInput(document.getElementById("address").value),
          paymentMethod: document.querySelector('#payment-method-group input[type="radio"]:checked')?.value,
        };

        if (isCardPaymentSelected()) {
          formData.cardDetails = {
            cardNumber: document.getElementById("card-number").value.replace(/\s+/g, ''),
            cardholderName: sanitizeInput(document.getElementById("cardholder-name").value),
            expiryMonth: document.getElementById("expiry-month").value,
            expiryYear: document.getElementById("expiry-year").value,
            cvv: document.getElementById("cvv").value,
          };
        }

        // Submit to backend here
        
        showSuccessPopup(3000);
      });
  }
});
