document.addEventListener('DOMContentLoaded', () => {
    const sliderContainer = document.querySelector('.y-l-slider-container');
    if (!sliderContainer) {
        return;
    }

    const sliderWrapper = sliderContainer.querySelector('.y-l-slider-wrapper');
    const slides = sliderContainer.querySelectorAll('.y-c-slide');
    const prevButton = sliderContainer.querySelector('.y-c-slider-prev'); // Left arrow for RTL
    const nextButton = sliderContainer.querySelector('.y-c-slider-next'); // Right arrow for RTL
    const dotsContainer = sliderContainer.querySelector('.y-c-slider-dots');

    if (!sliderWrapper || !slides.length || !prevButton || !nextButton || !dotsContainer) {
        return;
    }

    let currentIndex = 0;
    const totalSlides = slides.length;
    let autoPlayInterval;

    // Create dots
    for (let i = 0; i < totalSlides; i++) {
        const dot = document.createElement('div');
        dot.classList.add('y-c-slider-dot');
        dot.addEventListener('click', () => {
            goToSlide(i);
            resetAutoPlay();
        });
        dotsContainer.appendChild(dot);
    }
    const dots = dotsContainer.querySelectorAll('.y-c-slider-dot');

    function goToSlide(index) {
        // This handles wrapping around correctly for both positive and negative numbers
        currentIndex = (index + totalSlides) % totalSlides;

        // Use different transform values for mobile vs desktop
        const isMobile = window.innerWidth <= 768;
        const transformValue = isMobile ? 106 : 103;
        sliderWrapper.style.transform = `translateX(${currentIndex * transformValue}%)`;
        updateDots();
    }

    function updateDots() {
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }

    function startAutoPlay() {
        autoPlayInterval = setInterval(() => {
            // Check if the page is visible to avoid running in the background
            if (document.visibilityState === 'visible') {
                // In RTL, the "next" slide is visually to the left.
                goToSlide(currentIndex + 1);
            }
        }, 4000);
    }

    function resetAutoPlay() {
        clearInterval(autoPlayInterval);
        startAutoPlay();
    }

    nextButton.addEventListener('click', () => {
        goToSlide(currentIndex - 1);
        resetAutoPlay();
    });

    prevButton.addEventListener('click', () => {
        goToSlide(currentIndex + 1);
        resetAutoPlay();
    });
    const scroller = document.querySelector(".y-c-brands-scroller");
    if (scroller) {
        // Check if the user prefers reduced motion before starting animation
        if (!window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
            addAnimation(scroller);
        }
    }


    function addAnimation(scroller) {
        const scrollerInner = scroller.querySelector(".y-c-scroller-inner");
        if (!scrollerInner) return;

        // Clone items for a seamless loop - duplicate the entire set
        const items = Array.from(scrollerInner.children);
        items.forEach(item => {
            const clone = item.cloneNode(true);
            clone.setAttribute('aria-hidden', 'true'); // Mark clones as decorative
            scrollerInner.appendChild(clone);
        });

        // Add CSS animation class
        scrollerInner.classList.add('y-c-scroller-animated');

        // JavaScript-based smooth scrolling for better control
        let scrollPosition = 0;
        let animationId;
        let isPaused = false;

        // Calculate dimensions after cloning
        const itemWidth = items[0].offsetWidth;
        const gap = parseInt(getComputedStyle(scrollerInner).gap) || 0;
        const totalItemWidth = itemWidth + gap;
        const totalWidth = totalItemWidth * items.length;

        function scroll() {
            if (!isPaused) {
                scrollPosition -= 0.5; // Scroll speed (pixels per frame)

                // Reset position when we've scrolled past one full set of items
                if (Math.abs(scrollPosition) >= totalWidth) {
                    scrollPosition = 0;
                }

                scrollerInner.style.transform = `translateX(${scrollPosition}px)`;
            }
            animationId = requestAnimationFrame(scroll);
        }

        // Start the animation
        animationId = requestAnimationFrame(scroll);

        // Pause/resume animation on hover
        scroller.addEventListener('mouseenter', () => {
            isPaused = true;
            scrollerInner.style.animationPlayState = 'paused';
        });

        scroller.addEventListener('mouseleave', () => {
            isPaused = false;
            scrollerInner.style.animationPlayState = 'running';
        });

        // Handle visibility change (pause when tab is not visible)
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                isPaused = true;
            } else {
                isPaused = false;
            }
        });

        // Clean up on page unload
        window.addEventListener('beforeunload', () => {
            if (animationId) {
                cancelAnimationFrame(animationId);
            }
        });
    }
    // Initialize the slider
    goToSlide(0);
    startAutoPlay();
});