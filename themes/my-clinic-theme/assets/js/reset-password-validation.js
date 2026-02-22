// Password validation for reset password form
document.addEventListener('DOMContentLoaded', function() {
  const newPasswordInput = document.getElementById('new_password');
  const confirmPasswordInput = document.getElementById('confirm_password');
  const passwordError = document.getElementById('reset-password-error');
  const resetForm = document.querySelector('form[method="POST"]');
  
  if (!newPasswordInput || !confirmPasswordInput || !passwordError || !resetForm) {
    return;
  }
  
  // Validate password match
  function validatePasswordMatch() {
    const password = newPasswordInput.value;
    const passwordConfirm = confirmPasswordInput.value;
    
    if (passwordConfirm.length === 0) {
      passwordError.style.display = 'none';
      confirmPasswordInput.setCustomValidity('');
      return true;
    }
    
    if (password !== passwordConfirm) {
      passwordError.style.display = 'block';
      confirmPasswordInput.setCustomValidity('كلمات المرور غير متطابقة');
      return false;
    } else {
      passwordError.style.display = 'none';
      confirmPasswordInput.setCustomValidity('');
      return true;
    }
  }
  
  // Real-time validation on password confirm input
  confirmPasswordInput.addEventListener('input', function() {
    validatePasswordMatch();
  });
  
  confirmPasswordInput.addEventListener('blur', function() {
    validatePasswordMatch();
  });
  
  // Validate on password input change
  newPasswordInput.addEventListener('input', function() {
    if (confirmPasswordInput.value.length > 0) {
      validatePasswordMatch();
    }
  });
  
  // Validate on form submit
  resetForm.addEventListener('submit', function(e) {
    if (!validatePasswordMatch()) {
      e.preventDefault();
      confirmPasswordInput.focus();
      confirmPasswordInput.reportValidity();
      return false;
    }
    
    // Check if passwords are filled
    if (!newPasswordInput.value || !confirmPasswordInput.value) {
      e.preventDefault();
      if (!newPasswordInput.value) {
        newPasswordInput.focus();
      } else {
        confirmPasswordInput.focus();
      }
      return false;
    }
  });
});
