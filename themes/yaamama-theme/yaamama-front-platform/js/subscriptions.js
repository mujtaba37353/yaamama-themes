document.addEventListener('DOMContentLoaded', () => {
  const planCards = Array.from(document.querySelectorAll('[data-plan-card]'));
  if (!planCards.length) {
    return;
  }

  const formatter = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });

  const applyPeriod = (card, period) => {
    const priceEl = card.querySelector('[data-plan-price]');
    const priceIdInput = card.querySelector('[data-plan-price-id]');
    const trialEl = card.querySelector('[data-plan-trial]');
    if (!priceEl || !priceIdInput) {
      return;
    }

    const price = card.dataset[period === 'year' ? 'priceYear' : 'priceMonth'];
    const priceId = card.dataset[period === 'year' ? 'priceIdYear' : 'priceIdMonth'];
    const trialLabel = card.dataset[period === 'year' ? 'trialYear' : 'trialMonth'] || '';

    if (!price || !priceId) {
      return;
    }

    const numeric = Number(price);
    priceEl.textContent = Number.isFinite(numeric)
      ? formatter.format(numeric)
      : price;

    priceIdInput.value = priceId;

    if (trialEl) {
      if (trialLabel) {
        trialEl.textContent = trialLabel;
        trialEl.style.display = '';
      } else {
        trialEl.textContent = '';
        trialEl.style.display = 'none';
      }
    }
  };

  planCards.forEach((card) => {
    const defaultPeriod = card.dataset.defaultPeriod || 'month';
    applyPeriod(card, defaultPeriod);
  });

  const billingToggles = document.querySelectorAll('[data-billing-toggle]');
  if (!billingToggles.length) {
    return;
  }

  billingToggles.forEach((btn) => {
    btn.addEventListener('click', () => {
      const period = btn.getAttribute('data-period') || 'month';
      planCards.forEach((card) => applyPeriod(card, period));

      billingToggles.forEach((toggle) => toggle.classList.remove('active'));
      btn.classList.add('active');
    });
  });
});
