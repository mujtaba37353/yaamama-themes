document.addEventListener('DOMContentLoaded', function() {
  const deactivateBtn = document.getElementById('btn-deactivate-account');
  const popup = document.getElementById('deactivate-popup');
  const cancelBtn = document.getElementById('btn-cancel-deactivate');
  const confirmBtn = document.getElementById('btn-confirm-deactivate');

  if (deactivateBtn && popup) {
    deactivateBtn.addEventListener('click', function() {
      popup.style.display = 'flex';
    });
  }

  if (cancelBtn && popup) {
    cancelBtn.addEventListener('click', function() {
      popup.style.display = 'none';
    });
  }
  
  if (confirmBtn) {
    confirmBtn.addEventListener('click', function() {
      popup.style.display = 'none';
    });
  }

  if (popup) {
    popup.addEventListener('click', function(e) {
      if (e.target === popup) {
        popup.style.display = 'none';
      }
    });
  }

  // Remove show password buttons from account edit form
  function removePasswordButtons() {
    const accountForm = document.querySelector('#edit-account-content');
    if (accountForm) {
      const passwordButtons = accountForm.querySelectorAll('.show-password-input');
      passwordButtons.forEach(function(button) {
        button.remove();
      });
      
      // Remove padding that was added for the button
      const passwordInputs = accountForm.querySelectorAll('.password-input input[type="password"]');
      passwordInputs.forEach(function(input) {
        input.style.paddingRight = '0.8rem';
      });
    }
  }

  // Remove buttons immediately
  removePasswordButtons();

  // Also remove after a short delay in case WooCommerce adds them later
  setTimeout(removePasswordButtons, 100);
  setTimeout(removePasswordButtons, 500);
});

