window.darkThemeAssetUrl = window.darkThemeAssetUrl || function (path) {
    const base = window.DarkTheme && window.DarkTheme.baseUrl ? window.DarkTheme.baseUrl.replace(/\/$/, "") : "";
    return base ? `${base}/${path}` : path;
};

fetch(window.darkThemeAssetUrl("components/home/y-c-reviews.html"))
    .then((response) => response.text())
    .then((data) => {
        const host = document.querySelector('[data-y="reviews"]');
        if (host && !host.children.length) {
            const normalized = data.replace(/\.\.\/\.\.\/assets\//g, window.darkThemeAssetUrl("assets/"));
            host.innerHTML = normalized;
            setTimeout(() => {
                initializeReviewsScroll();
            }, 100);
        }
    });

function scrollReviews(direction) {
    const reviewsContainer = document.querySelector('.reviews');
    if (!reviewsContainer) {
        console.log('Reviews container not found');
        return;
    }

    const scrollAmount = 350; 

    console.log(`Scrolling ${direction}, current scrollLeft: ${reviewsContainer.scrollLeft}`);

    if (direction === 'left') {
        reviewsContainer.scrollBy({
            left: -scrollAmount,
            behavior: 'smooth'
        });
    } else if (direction === 'right') {
        reviewsContainer.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
    }

    setTimeout(updateArrows, 300);
}

function updateArrows() {
    const reviewsContainer = document.querySelector('.reviews');
    const leftArrow = document.querySelector('.scroll-arrow.left');
    const rightArrow = document.querySelector('.scroll-arrow.right');

    if (!reviewsContainer || !leftArrow || !rightArrow) return;

    const scrollLeft = reviewsContainer.scrollLeft;
    const scrollWidth = reviewsContainer.scrollWidth;
    const clientWidth = reviewsContainer.clientWidth;

    const isAtStart = scrollLeft <= 5; 
    const isAtEnd = scrollLeft >= (scrollWidth - clientWidth - 5);

    if (isAtStart) {
        leftArrow.style.opacity = '0.4';
        leftArrow.style.cursor = 'default';
    } else {
        leftArrow.style.opacity = '1';
        leftArrow.style.cursor = 'pointer';
    }

    if (isAtEnd) {
        rightArrow.style.opacity = '0.4';
        rightArrow.style.cursor = 'default';
    } else {
        rightArrow.style.opacity = '1';
        rightArrow.style.cursor = 'pointer';
    }
}

function initializeReviewsScroll() {
    updateArrows();
    const reviewsContainer = document.querySelector('.reviews');
    if (reviewsContainer) {
        reviewsContainer.addEventListener('scroll', updateArrows);

        window.scrollReviews = scrollReviews;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        initializeReviewsScroll();
    }, 200);
});