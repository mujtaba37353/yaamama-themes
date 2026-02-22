document.addEventListener('DOMContentLoaded', () => {
  const tabs = Array.from(document.querySelectorAll('.tabs .tab-btn[data-filter]'));
  const cards = Array.from(document.querySelectorAll('.themes-grid .theme-card[data-categories]'));

  if (!tabs.length || !cards.length) {
    return;
  }

  const applyFilter = (filter) => {
    cards.forEach((card) => {
      const categories = (card.getAttribute('data-categories') || '')
        .split(' ')
        .map((value) => value.trim())
        .filter(Boolean);
      const match = filter === 'all' || categories.includes(filter);
      card.style.display = match ? '' : 'none';
    });
  };

  tabs.forEach((tab) => {
    tab.addEventListener('click', () => {
      tabs.forEach((item) => item.classList.remove('active'));
      tab.classList.add('active');
      applyFilter(tab.getAttribute('data-filter') || 'all');
    });
  });

  applyFilter('all');
});
