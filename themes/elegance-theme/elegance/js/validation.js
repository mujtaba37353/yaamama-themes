// ============================================
// Validation Configuration
// ============================================
const ValidationConfig = {
    errorMessages: {
      ar: {
        required: "هذا الحقل مطلوب",
        email: "البريد الإلكتروني غير صحيح",
        emailRequired: "البريد الإلكتروني مطلوب",
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
        expiryMonth: "الشهر مطلوب",
        expiryYear: "السنة مطلوبة",
        expiryInvalid: "تاريخ انتهاء البطاقة غير صحيح",
        expiryPast: "البطاقة منتهية الصلاحية",
        cvv: "CVV مطلوب",
        cvvInvalid: "CVV يجب أن يكون 3 أو 4 أرقام",
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
    addressConfig: {
      minLength: 10
    }
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
    const expectedLength = isAmex ? 4 : 3;
    
    return /^\d+$/.test(cleanCVV) && (cleanCVV.length === 3 || cleanCVV.length === 4);
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
    const overlay = document.querySelector(".status-popup-overlay");
    if (overlay) {
      overlay.classList.add("active");
      setTimeout(() => {
        overlay.classList.remove("active");
      }, duration);
    }
  }
  
  // ============================================
  // Form Validators
  // ============================================
  
  document.addEventListener("DOMContentLoaded", () => {
    const messages = ValidationConfig.errorMessages.ar;
    
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
        ])
        .onSuccess((event) => {
          // Submit to backend here
        });
    }
  
    // ============================================
    // Payment Form Validation
    // ============================================
    const paymentForm = document.querySelector("#payment-form");
    if (paymentForm) {
      const cardFieldsContainer = document.querySelector(".card");
  
      // Prevent the form from ever doing a native submit (page reload)
      paymentForm.addEventListener('submit', (e) => {
        e.preventDefault();
      });
      
      // Toggle card fields visibility based on payment method
      const paymentRadios = document.querySelectorAll('#payment-method-group input[type="radio"]');
      paymentRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
          if (cardFieldsContainer) {
            const isCard = e.target.id === 'visa';
            cardFieldsContainer.style.display = isCard ? 'block' : 'none';
          }
        });
      });
  
      const validator = new JustValidate(paymentForm, {
        validateBeforeSubmitting: true,
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
          }
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
  