(() => {
  const init = () => {
    const slider = document.querySelector(".hero-slider");
    const slides = document.querySelectorAll(".hero-slide");
    const indicators = document.querySelectorAll(".hero-indicator");
    const prevBtn = document.querySelector(".hero-slider-prev");
    const nextBtn = document.querySelector(".hero-slider-next");

    if (!slider || !slides.length) return;

    let currentSlide = 0;
    let isTransitioning = false;
    let autoSlideInterval;

    const config = {
      autoSlide: true,
      autoSlideDelay: 5000,
      transitionDuration: 500,
      pauseOnHover: true,
    };

    const updateSlide = (index) => {
      if (isTransitioning) return;

      isTransitioning = true;

      slides.forEach((slide) => slide.classList.remove("active"));
      indicators.forEach((indicator) => indicator.classList.remove("active"));

      slides[index].classList.add("active");
      indicators[index].classList.add("active");

      setTimeout(() => {
        isTransitioning = false;
      }, config.transitionDuration);
    };

    const goToSlide = (index) => {
      if (index < 0 || index >= slides.length || isTransitioning) return;

      currentSlide = index;
      updateSlide(currentSlide);
    };

    const nextSlide = () => {
      const next = (currentSlide + 1) % slides.length;
      goToSlide(next);
    };

    const prevSlide = () => {
      const prev = currentSlide === 0 ? slides.length - 1 : currentSlide - 1;
      goToSlide(prev);
    };

    const startAutoSlide = () => {
      if (!config.autoSlide) return;

      autoSlideInterval = setInterval(() => {
        nextSlide();
      }, config.autoSlideDelay);
    };

    const stopAutoSlide = () => {
      if (autoSlideInterval) {
        clearInterval(autoSlideInterval);
        autoSlideInterval = null;
      }
    };

    if (nextBtn) {
      nextBtn.addEventListener("click", () => {
        stopAutoSlide();
        nextSlide();
        startAutoSlide();
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener("click", () => {
        stopAutoSlide();
        prevSlide();
        startAutoSlide();
      });
    }

    // Indicator click events
    indicators.forEach((indicator, index) => {
      indicator.addEventListener("click", () => {
        stopAutoSlide();
        goToSlide(index);
        startAutoSlide();
      });
    });

    // Keyboard navigation
    document.addEventListener("keydown", (e) => {
      if (e.key === "ArrowLeft") {
        stopAutoSlide();
        nextSlide();
        startAutoSlide();
      } else if (e.key === "ArrowRight") {
        stopAutoSlide();
        prevSlide();
        startAutoSlide();
      }
    });

    // Touch/swipe support
    let startX = 0;
    let startY = 0;
    let endX = 0;
    let endY = 0;

    const handleTouchStart = (e) => {
      startX = e.touches[0].clientX;
      startY = e.touches[0].clientY;
    };

    const handleTouchEnd = (e) => {
      endX = e.changedTouches[0].clientX;
      endY = e.changedTouches[0].clientY;

      const diffX = startX - endX;
      const diffY = startY - endY;

      // Only trigger if horizontal swipe is more significant than vertical
      if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
        stopAutoSlide();

        if (diffX > 0) {
          // Swipe left - next slide
          nextSlide();
        } else {
          // Swipe right - previous slide
          prevSlide();
        }

        startAutoSlide();
      }
    };

    slider.addEventListener("touchstart", handleTouchStart, { passive: true });
    slider.addEventListener("touchend", handleTouchEnd, { passive: true });

    // Pause on hover
    if (config.pauseOnHover) {
      slider.addEventListener("mouseenter", stopAutoSlide);
      slider.addEventListener("mouseleave", startAutoSlide);
    }

    // Initialize
    updateSlide(currentSlide);
    startAutoSlide();

    // Pause when page is not visible
    document.addEventListener("visibilitychange", () => {
      if (document.hidden) {
        stopAutoSlide();
      } else {
        startAutoSlide();
      }
    });
  };

  // Initialize when DOM is ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init, { once: true });
  } else {
    init();
  }
})();
