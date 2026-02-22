document.addEventListener("DOMContentLoaded", () => {
  const slider = document.querySelector(".opinions-slider");
  const slides = document.querySelectorAll(".opinion-slide");
  const prevBtn = document.querySelector(".opinion-slider-prev");
  const nextBtn = document.querySelector(".opinion-slider-next");

  if (!slider || slides.length === 0) return;

  let currentIndex = 0;
  const slideWidth =
    slides[0].offsetWidth + parseInt(getComputedStyle(slider).gap);

  const updateSliderPosition = () => {
    slider.style.transform = `translateX(${currentIndex * slideWidth}px)`;
  };

  const showNextSlide = () => {
    const slidesPerView = window.innerWidth <= 767 ? 1 : 2;
    if (currentIndex < slides.length - slidesPerView) {
      currentIndex++;
      updateSliderPosition();
    }
  };

  const showPrevSlide = () => {
    if (currentIndex > 0) {
      currentIndex--;
      updateSliderPosition();
    }
  };

  if (nextBtn) nextBtn.addEventListener("click", showNextSlide);
  if (prevBtn) prevBtn.addEventListener("click", showPrevSlide);

  window.addEventListener("resize", () => {
    const newSlideWidth =
      slides[0].offsetWidth + parseInt(getComputedStyle(slider).gap);
    if (slideWidth !== newSlideWidth) {
      slideWidth = newSlideWidth;
      updateSliderPosition();
    }
  });
});
