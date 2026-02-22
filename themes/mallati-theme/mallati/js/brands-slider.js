/**
 * Categories / indicators slider - wire up prev/next buttons for category sliders.
 * Used on home, category, sub-category pages.
 */
(function () {
  const init = () => {
    const containers = document.querySelectorAll('.categories-section, .indecators');
    containers.forEach((container) => {
      const prevBtn = container.querySelector('.indecator:last-of-type');
      const nextBtn = container.querySelector('.indecator:first-of-type');
      const slider = container.closest('section')?.querySelector('.categories-slider');
      if (!slider || !prevBtn || !nextBtn) return;

      const scroll = (dir) => {
        const amount = slider.offsetWidth * 0.6;
        slider.scrollBy({ left: dir === 'prev' ? amount : -amount, behavior: 'smooth' });
      };
      prevBtn.addEventListener('click', () => scroll('prev'));
      nextBtn.addEventListener('click', () => scroll('next'));
    });
  };
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
