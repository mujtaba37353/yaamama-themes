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
  // Check for checkbox-based popup (old implementation)
  const popupToggle = document.getElementById("payment-success-popup");
  if (popupToggle) {
    popupToggle.checked = true;
    setTimeout(() => {
      popupToggle.checked = false;
    }, duration);
    return;
  }

  // Check for modal-based popup (new implementation for payment page)
  const successModal = document.getElementById("payment-success-modal");
  if (successModal) {
    const successCard = successModal.querySelector(".success-card");
    successModal.classList.add("is-open");
    successModal.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
    if (successCard) successCard.focus();
    
    // Setup close handlers if not already set (or rely on payment.js to handle closing)
    // payment.js handles closing via click on backdrop or escape key
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
    const preventHandler = (e) => e.preventDefault();
    contactForm.addEventListener('submit', preventHandler);

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
      .onSuccess(() => {
        contactForm.removeEventListener('submit', preventHandler);
        contactForm.submit();
      });
  }

  // ============================================
  // Login Form Validation
  // ============================================
  const loginForm = document.querySelector("#login-form");
  if (loginForm) {
    loginForm.addEventListener('submit', (e) => e.preventDefault());

    const validator = new JustValidate(loginForm, {
      validateBeforeSubmitting: true,
    });
    
    validator
      .addField("#email", [
        { rule: "required", errorMessage: messages.emailRequired },
        { rule: "email", errorMessage: messages.email },
        {
          validator: (value) => !/[\u0600-\u06FF]/.test(value),
          errorMessage: messages.emailInvalid,
        }
      ])
      .addField("#password", [
        { rule: "required", errorMessage: messages.password },
      ])
      .onSuccess((event) => {
        // Submit to backend here
      });
  }

  // ============================================
  // Signup Form Validation
  // ============================================
  const signupForm = document.querySelector("#signup-form");
  if (signupForm) {
    signupForm.addEventListener('submit', (e) => e.preventDefault());

    const validator = new JustValidate(signupForm, {
      validateBeforeSubmitting: true,
    });
    
    validator
      .addField("#email", [
        { rule: "required", errorMessage: messages.emailRequired },
        { rule: "email", errorMessage: messages.email },
        {
          validator: (value) => !/[\u0600-\u06FF]/.test(value),
          errorMessage: messages.emailInvalid,
        }
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
      .addField("#password", [
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
      .addField("#confirm-password", [
        { rule: "required", errorMessage: messages.passwordMatch },
        { 
          validator: (value, fields) => {
            return value === fields['#password'].elem.value;
          },
          errorMessage: messages.passwordMatch,
        },
      ])
      .onSuccess((event) => {
        // Submit to backend here
      });
  }

  // ============================================
  // Forget Password Form Validation
  // ============================================
  const forgetPasswordForm = document.querySelector("#forget-password-form");
  if (forgetPasswordForm) {
    forgetPasswordForm.addEventListener('submit', (e) => e.preventDefault());

    const validator = new JustValidate(forgetPasswordForm, {
      validateBeforeSubmitting: true,
    });
    
    validator
      .addField("#email", [
        { rule: "required", errorMessage: messages.emailRequired },
        { rule: "email", errorMessage: messages.email },
        {
          validator: (value) => !/[\u0600-\u06FF]/.test(value),
          errorMessage: messages.emailInvalid,
        }
      ])
      .onSuccess((event) => {
        // Submit to backend here
      });
  }

  // ============================================
  // Profile Form Validation
  // ============================================
  const profileForm = document.querySelector("#profile-form");
  if (profileForm) {
    profileForm.addEventListener('submit', (e) => e.preventDefault());

    const validator = new JustValidate(profileForm, {
      validateBeforeSubmitting: true,
    });

    validator
      .addField("#name", [
        { rule: "required", errorMessage: messages.fullName },
        { rule: "minLength", value: 2, errorMessage: messages.nameFormat },
      ])
      .addField("#email", [
        { rule: "required", errorMessage: messages.emailRequired },
        { rule: "email", errorMessage: messages.email },
        {
          validator: (value) => !/[\u0600-\u06FF]/.test(value),
          errorMessage: messages.emailInvalid,
        }
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
      ])
      // Password Change Validation
      .addField("#password", [ // Current Password
        {
          validator: (value, fields) => {
            if (fields['#new-password'] && fields['#new-password'].elem.value.length > 0) {
              return value && value.length > 0;
            }
            return true;
          },
          errorMessage: messages.password,
        }
      ])
      .addField("#new-password", [
        {
          validator: (value, fields) => {
             // If current password is provided, new password might not be strictly required unless we enforce flow order
             // But if user wants to change pw, they normally type in new pw.
             return true; 
          }, 
        },
        {
           rule: "minLength",
           value: ValidationConfig.passwordConfig.minLength,
           errorMessage: messages.passwordMin.replace('{length}', ValidationConfig.passwordConfig.minLength),
        }
      ])
      .addField("#confirm-password", [
        {
          validator: (value, fields) => {
            if (fields['#new-password'] && fields['#new-password'].elem.value.length > 0) {
              return value === fields['#new-password'].elem.value;
            }
            return true;
          },
          errorMessage: messages.passwordMatch,
        }
      ])
      .onSuccess((event) => {
        // Submit profile update
        // Show success message or popup
      });
  }

  // ============================================
  // New Address Form Validation (Specific)
  // ============================================
  const newAddressForm = document.querySelector("#address-new-form");
  if (newAddressForm) {
    newAddressForm.addEventListener('submit', (e) => e.preventDefault());

    const validator = new JustValidate(newAddressForm, {
      validateBeforeSubmitting: true,
    });

    validator
      .addField("#fullName", [
        { rule: "required", errorMessage: messages.fullName },
        { rule: "minLength", value: 2, errorMessage: messages.nameFormat },
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
      .addField("#city", [
        { rule: "required", errorMessage: messages.city },
      ])
      .addField("#district", [
        { rule: "required", errorMessage: messages.district },
      ])
      .addField("#street", [
        { rule: "required", errorMessage: messages.street },
      ])
      .addField("#building", [
        { rule: "required", errorMessage: messages.buildingNo },
        {
          validator: (value) => /^\d+$/.test(value.trim()),
          errorMessage: messages.buildingNoInvalid,
        }
      ])
      .addField("#postal", [
        { rule: "required", errorMessage: messages.postalCode },
        {
          validator: (value) => /^\d{5}$/.test(value.trim()),
          errorMessage: messages.postalCodeInvalid,
        }
      ])
      .addField("#apartment", [
        {
          validator: (value) => {
            if (!value) return true; // Optional
            return /^\d+$/.test(value.trim());
          },
          errorMessage: messages.unitNoInvalid,
        }
      ])
      .onSuccess((event) => {
        // Show success popup or redirect
        alert("تم حفظ العنوان بنجاح!");
        // Redirect back to addresses list
        window.location.href = "../account/account.html";
      });
  }

  // ============================================
  // Address Form Validation Helper (Legacy/Other forms)
  // ============================================
  function addAddressValidation(formSelector, prefix) {
    const form = document.querySelector(formSelector);
    if (!form) return;

    form.addEventListener('submit', (e) => e.preventDefault());

    const validator = new JustValidate(form, {
      validateBeforeSubmitting: true,
    });

    validator
      .addField(`#${prefix}-first-name`, [
        { rule: "required", errorMessage: messages.firstName },
        { rule: "minLength", value: 2, errorMessage: messages.nameFormat },
      ])
      .addField(`#${prefix}-last-name`, [
        { rule: "required", errorMessage: messages.lastName },
        { rule: "minLength", value: 2, errorMessage: messages.nameFormat },
      ])
      .addField(`#${prefix}-street`, [
        { rule: "required", errorMessage: messages.street },
      ])
      .addField(`#${prefix}-district`, [
        { rule: "required", errorMessage: messages.district },
      ])
      .addField(`#${prefix}-city`, [
        { rule: "required", errorMessage: messages.city },
      ])
      .addField(`#${prefix}-region`, [
        { rule: "required", errorMessage: messages.region },
      ])
      .addField(`#${prefix}-postal-code`, [
        { rule: "required", errorMessage: messages.postalCode },
        {
          validator: (value) => /^\d{5}$/.test(value.trim()),
          errorMessage: messages.postalCodeInvalid,
        }
      ])
      .addField(`#${prefix}-building-no`, [
        { rule: "required", errorMessage: messages.buildingNo },
        {
          validator: (value) => /^\d+$/.test(value.trim()),
          errorMessage: messages.buildingNoInvalid,
        }
      ])
      .addField(`#${prefix}-unit-no`, [
        { rule: "required", errorMessage: messages.unitNo },
        {
          validator: (value) => /^\d+$/.test(value.trim()),
          errorMessage: messages.unitNoInvalid,
        }
      ])
      .onSuccess((event) => {
        // Show success popup
        const popup = document.getElementById("address-saved-popup");
        if (popup) {
          popup.checked = true;
          setTimeout(() => { popup.checked = false; }, 3000);
        }
      });
  }

  // Edit Address Form
  addAddressValidation("#address-edit-form", "edit");

  // New Address Form (Handled specifically above)
  // addAddressValidation("#address-new-form", "new");

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
