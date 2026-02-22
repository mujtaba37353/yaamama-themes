// Phone number validation for booking form
document.addEventListener('DOMContentLoaded', function() {
  const phoneInput = document.getElementById('patient_phone');
  const phoneError = document.getElementById('phone-error');
  const bookingForm = document.getElementById('booking-form');
  
  if (!phoneInput || !phoneError || !bookingForm) {
    return;
  }
  
  // Validate phone format: starts with 05 followed by 8 digits
  function validatePhone(phone) {
    // Remove any spaces or dashes
    const cleaned = phone.replace(/[\s-]/g, '');
    // Check if it matches pattern: 05 followed by exactly 8 digits
    const phonePattern = /^05[0-9]{8}$/;
    return phonePattern.test(cleaned);
  }
  
  // Real-time validation on input
  phoneInput.addEventListener('input', function() {
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
    
    // Hide error if valid
    if (phoneValue.length === 0 || validatePhone(phoneValue)) {
      phoneError.style.display = 'none';
      this.setCustomValidity('');
    } else {
      // Show error if invalid
      if (phoneValue.length > 0) {
        phoneError.style.display = 'block';
        this.setCustomValidity('رقم الجوال يجب أن يبدأ بـ 05 ويتبعه 8 أرقام');
      }
    }
  });
  
  // Validate on blur
  phoneInput.addEventListener('blur', function() {
    const phoneValue = this.value.replace(/[\s-]/g, '');
    if (phoneValue.length > 0 && !validatePhone(phoneValue)) {
      phoneError.style.display = 'block';
      this.setCustomValidity('رقم الجوال يجب أن يبدأ بـ 05 ويتبعه 8 أرقام');
    } else {
      phoneError.style.display = 'none';
      this.setCustomValidity('');
    }
  });
  
  // Validate on form submit
  bookingForm.addEventListener('submit', function(e) {
    const phoneValue = phoneInput.value.replace(/[\s-]/g, '');
    
    if (!validatePhone(phoneValue)) {
      e.preventDefault();
      phoneError.style.display = 'block';
      phoneInput.setCustomValidity('رقم الجوال يجب أن يبدأ بـ 05 ويتبعه 8 أرقام');
      phoneInput.reportValidity();
      phoneInput.focus();
      return false;
    }
    
    phoneError.style.display = 'none';
    phoneInput.setCustomValidity('');
  });
});
