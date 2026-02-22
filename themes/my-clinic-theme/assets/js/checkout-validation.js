// Checkout form validation
document.addEventListener('DOMContentLoaded', function() {
  const createAccountCheckbox = document.getElementById('createaccount');
  const accountFields = document.getElementById('account-fields');
  const passwordInput = document.getElementById('account_password');
  const passwordConfirmInput = document.getElementById('account_password_confirm');
  const passwordError = document.getElementById('password-error');
  const checkoutForm = document.getElementById('checkout-form');
  
  // Register form validation (for checkout registration)
  const registerForm = document.getElementById('register-form');
  const registerPhoneInput = document.getElementById('register_phone');
  const registerPasswordInput = document.getElementById('register_password');
  const registerPasswordConfirmInput = document.getElementById('register_password_confirm');
  const registerPasswordError = document.getElementById('register-password-error');
  
  // Toggle account fields visibility
  if (createAccountCheckbox && accountFields) {
    createAccountCheckbox.addEventListener('change', function() {
      if (this.checked) {
        accountFields.style.display = 'block';
        if (passwordInput) passwordInput.setAttribute('required', 'required');
        if (passwordConfirmInput) passwordConfirmInput.setAttribute('required', 'required');
      } else {
        accountFields.style.display = 'none';
        if (passwordInput) {
          passwordInput.removeAttribute('required');
          passwordInput.value = '';
        }
        if (passwordConfirmInput) {
          passwordConfirmInput.removeAttribute('required');
          passwordConfirmInput.value = '';
        }
        if (passwordError) passwordError.style.display = 'none';
      }
    });
  }
  
  // Validate password match
  function validatePasswordMatch() {
    if (!passwordInput || !passwordConfirmInput || !passwordError) {
      return true;
    }
    
    const password = passwordInput.value;
    const passwordConfirm = passwordConfirmInput.value;
    
    if (passwordConfirm.length === 0) {
      passwordError.style.display = 'none';
      passwordConfirmInput.setCustomValidity('');
      return true;
    }
    
    if (password !== passwordConfirm) {
      passwordError.style.display = 'block';
      passwordConfirmInput.setCustomValidity('كلمات المرور غير متطابقة');
      return false;
    } else {
      passwordError.style.display = 'none';
      passwordConfirmInput.setCustomValidity('');
      return true;
    }
  }
  
  // Real-time validation on password confirm input
  if (passwordConfirmInput) {
    passwordConfirmInput.addEventListener('input', function() {
      validatePasswordMatch();
    });
    
    passwordConfirmInput.addEventListener('blur', function() {
      validatePasswordMatch();
    });
  }
  
  // Validate on password input change
  if (passwordInput) {
    passwordInput.addEventListener('input', function() {
      if (passwordConfirmInput && passwordConfirmInput.value.length > 0) {
        validatePasswordMatch();
      }
    });
  }
  
  // Validate on form submit
  if (checkoutForm) {
    checkoutForm.addEventListener('submit', function(e) {
      if (createAccountCheckbox && createAccountCheckbox.checked) {
        if (!validatePasswordMatch()) {
          e.preventDefault();
          if (passwordConfirmInput) {
            passwordConfirmInput.focus();
            passwordConfirmInput.reportValidity();
          }
          return false;
        }
        
        // Check if passwords are filled
        if (!passwordInput.value || !passwordConfirmInput.value) {
          e.preventDefault();
          if (passwordError) {
            passwordError.textContent = 'يجب إدخال كلمة المرور وتأكيدها';
            passwordError.style.display = 'block';
          }
          if (passwordInput && !passwordInput.value) {
            passwordInput.focus();
          } else if (passwordConfirmInput && !passwordConfirmInput.value) {
            passwordConfirmInput.focus();
          }
          return false;
        }
      }
    });
  }
  
  // Validate phone number for register form
  if (registerPhoneInput) {
    function validatePhone(phone) {
      const cleaned = phone.replace(/[\s-]/g, '');
      const phonePattern = /^05[0-9]{8}$/;
      return phonePattern.test(cleaned);
    }
    
    registerPhoneInput.addEventListener('input', function() {
      const phoneValue = this.value.replace(/[\s-]/g, '');
      
      // Only allow numbers
      if (phoneValue && !/^[0-9]+$/.test(phoneValue)) {
        this.value = phoneValue.replace(/[^0-9]/g, '');
        return;
      }
      
      // Limit to 10 digits
      if (phoneValue.length > 10) {
        this.value = phoneValue.substring(0, 10);
        return;
      }
      
      // Validate format
      if (phoneValue.length === 0) {
        this.setCustomValidity('');
      } else if (validatePhone(phoneValue)) {
        this.setCustomValidity('');
      } else {
        this.setCustomValidity('رقم الجوال يجب أن يبدأ بـ 05 ويتبعه 8 أرقام');
      }
    });
    
    registerPhoneInput.addEventListener('blur', function() {
      const phoneValue = this.value.replace(/[\s-]/g, '');
      if (phoneValue.length > 0 && !validatePhone(phoneValue)) {
        this.reportValidity();
      }
    });
  }
  
  // Validate password match for register form
  function validateRegisterPasswordMatch() {
    if (!registerPasswordInput || !registerPasswordConfirmInput || !registerPasswordError) {
      return true;
    }
    
    const password = registerPasswordInput.value;
    const passwordConfirm = registerPasswordConfirmInput.value;
    
    if (passwordConfirm.length === 0) {
      registerPasswordError.style.display = 'none';
      registerPasswordConfirmInput.setCustomValidity('');
      return true;
    }
    
    if (password !== passwordConfirm) {
      registerPasswordError.style.display = 'block';
      registerPasswordConfirmInput.setCustomValidity('كلمات المرور غير متطابقة');
      return false;
    } else {
      registerPasswordError.style.display = 'none';
      registerPasswordConfirmInput.setCustomValidity('');
      return true;
    }
  }
  
  // Real-time validation on register password confirm input
  if (registerPasswordConfirmInput) {
    registerPasswordConfirmInput.addEventListener('input', function() {
      validateRegisterPasswordMatch();
    });
    
    registerPasswordConfirmInput.addEventListener('blur', function() {
      validateRegisterPasswordMatch();
    });
  }
  
  // Validate on register password input change
  if (registerPasswordInput) {
    registerPasswordInput.addEventListener('input', function() {
      if (registerPasswordConfirmInput && registerPasswordConfirmInput.value.length > 0) {
        validateRegisterPasswordMatch();
      }
    });
  }
  
  // Validate register form on submit
  if (registerForm) {
    registerForm.addEventListener('submit', function(e) {
      // Validate phone
      if (registerPhoneInput) {
        const phoneValue = registerPhoneInput.value.replace(/[\s-]/g, '');
        if (!/^05[0-9]{8}$/.test(phoneValue)) {
          e.preventDefault();
          registerPhoneInput.focus();
          registerPhoneInput.reportValidity();
          return false;
        }
      }
      
      // Validate password match
      if (!validateRegisterPasswordMatch()) {
        e.preventDefault();
        if (registerPasswordConfirmInput) {
          registerPasswordConfirmInput.focus();
          registerPasswordConfirmInput.reportValidity();
        }
        return false;
      }
      
      // Check if gender is selected
      const genderInputs = registerForm.querySelectorAll('input[name="register_gender"]');
      let genderSelected = false;
      genderInputs.forEach(function(input) {
        if (input.checked) {
          genderSelected = true;
        }
      });
      
      if (!genderSelected) {
        e.preventDefault();
        alert('يرجى اختيار النوع (ذكر أو أنثى)');
        return false;
      }
    });
  }
});
