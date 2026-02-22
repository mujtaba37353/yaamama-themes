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

  const invoiceContent = document.getElementById('invoice-content');
  if (invoiceContent) {
    const addressEmptyState = invoiceContent.querySelector('.address-empty-state');
    const addressFilledState = invoiceContent.querySelector('.address-filled-state');
    const addEditButtons = invoiceContent.querySelectorAll('.address-display-card .btn-edit');
    const addressCloseBtn = invoiceContent.querySelector('.address-close');
    const saveBtn = invoiceContent.querySelector('.address-empty-state .btn-save');
    const showFilledState = () => {
      if (addressFilledState) addressFilledState.style.display = 'block';
      if (addressEmptyState) addressEmptyState.style.display = 'none';
    };

    const showEmptyState = () => {
      if (addressFilledState) addressFilledState.style.display = 'none';
      if (addressEmptyState) addressEmptyState.style.display = 'block';
    };

    showFilledState();

    addEditButtons.forEach(function(btn) {
      btn.addEventListener('click', function() {
        showEmptyState();
      });
    });

    if (addressCloseBtn) {
      addressCloseBtn.addEventListener('click', function() {
        showFilledState();
      });
    }

    if (saveBtn) {
      saveBtn.addEventListener('click', function(e) {
        showFilledState();
      });
    }
  }
});

