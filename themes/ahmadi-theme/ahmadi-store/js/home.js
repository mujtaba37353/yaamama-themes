'use strict';

/**
 * Main application initialization function for the home page.
 */
function initializeApp() {
    // Page Content Setup
    setupScrollAnimations();
    setupBrandSlider();
    setupCategoriesScrollButtons();
}


function setupScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const animatedElements = document.querySelectorAll('.y-c-category-card, .y-c-product-card, .y-c-promo-banner');
    animatedElements.forEach((el, index) => {
        el.classList.add('fade-in');
        el.style.transitionDelay = `${index * 0.05}s`;
        observer.observe(el);
    });
}

/**
 * Sets up the brand logo slider.
 */
function setupBrandSlider() {
    const container = document.getElementById("brands-container");
    if (container) {
        setInterval(() => {
            const lastImg = container.lastElementChild;
            if (lastImg) {
                container.prepend(lastImg);
            }
        }, 2000);
    }
}

function setupCategoriesMarquee() {
    // Intentionally disabled: categories should be user-scrollable only.
}

function setupCategoriesScrollButtons() {
    const carousel = document.querySelector('.y-c-categories-carousel');
    if (!carousel) {
        return;
    }

    const grid = carousel.querySelector('.y-c-categories-grid');
    const prevBtn = carousel.querySelector('.y-c-categories-arrow-prev');
    const nextBtn = carousel.querySelector('.y-c-categories-arrow-next');

    if (!grid || !prevBtn || !nextBtn) {
        return;
    }

    const getStep = () => {
        const card = grid.querySelector('.y-c-category-card');
        const cardWidth = card ? card.getBoundingClientRect().width : 260;
        return cardWidth * 2;
    };

    const isRtl = getComputedStyle(grid).direction === 'rtl';
    const scrollByStep = (step) => {
        const direction = isRtl ? -1 : 1;
        grid.scrollBy({ left: step * direction, behavior: 'smooth' });
    };

    prevBtn.addEventListener('click', () => {
        scrollByStep(-getStep());
    });

    nextBtn.addEventListener('click', () => {
        scrollByStep(getStep());
    });
}