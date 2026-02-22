document.addEventListener('DOMContentLoaded', () => {
  const accountTabs = document.querySelectorAll('input[name="account-page"]');
  const tabLabels = document.querySelectorAll('label[for^="page-"]');

  const syncFromHash = () => {
    const hash = window.location.hash;
    if (!hash) return;
    const target = document.querySelector(hash);
    if (target && target.type === 'radio') {
      target.checked = true;
    }
  };

  if (accountTabs.length) {
    syncFromHash();

    accountTabs.forEach((tab) => {
      tab.addEventListener('change', () => {
        if (!tab.checked) return;
        if (tab.id) {
          history.replaceState(null, '', `#${tab.id}`);
        }
      });
    });
  }

  tabLabels.forEach((label) => {
    label.addEventListener('click', () => {
      const id = label.getAttribute('for');
      if (!id) return;
      const input = document.getElementById(id);
      if (input) {
        input.checked = true;
      }
      history.replaceState(null, '', `#${id}`);
    });
  });

  window.addEventListener('hashchange', syncFromHash);

  const dropdowns = document.querySelectorAll('.custom-dropdown');
  dropdowns.forEach((dropdown) => {
    dropdown.addEventListener('dropdown-select', (event) => {
      const { data } = event.detail || {};
      if (!data || !data.value) {
        return;
      }
      const form = dropdown.closest('form');
      if (!form) {
        return;
      }
      const input = form.querySelector('[data-gender-input]');
      if (input) {
        input.value = data.value;
      }
    });
  });

  const filterButtons = document.querySelectorAll('[data-invoice-filter]');
  const invoiceRows = document.querySelectorAll('[data-invoice-status]');
  if (filterButtons.length && invoiceRows.length) {
    const filterMap = {
      all: null,
      paid: 'invoice-status-badge--paid',
      unpaid: 'invoice-status-badge--unpaid',
      late: 'invoice-status-badge--late',
    };

    filterButtons.forEach((button) => {
      button.addEventListener('click', (event) => {
        event.preventDefault();
        const key = button.getAttribute('data-invoice-filter');
        const target = filterMap[key] || null;

        filterButtons.forEach((btn) => btn.classList.remove('active'));
        button.classList.add('active');

        invoiceRows.forEach((row) => {
          const status = row.getAttribute('data-invoice-status');
          if (!target || status === target) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      });
    });
  }
});
