document.addEventListener('DOMContentLoaded', function () {
    const mainImage = document.getElementById('main-product-image');
    const thumbnails = document.querySelectorAll('.y-c-thumbnail');

    if (mainImage && thumbnails.length) {
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', () => {
                mainImage.src = thumb.src;
                thumbnails.forEach(t => t.classList.remove('active'));
                thumb.classList.add('active');
            });
        });
    }

    const input = document.getElementById('qty-input');
    const btnMinus = document.getElementById('qty-minus');
    const btnPlus = document.getElementById('qty-plus');

    if (input && btnMinus && btnPlus) {
        btnMinus.addEventListener('click', () => {
            const val = parseInt(input.value || '1', 10);
            if (val > 1) input.value = val - 1;
        });

        btnPlus.addEventListener('click', () => {
            const val = parseInt(input.value || '1', 10);
            input.value = val + 1;
        });
    }

    const track = document.getElementById('related-products-track');
    const prevBtn = document.querySelector('.y-c-slider-prev');
    const nextBtn = document.querySelector('.y-c-slider-next');

    if (track && prevBtn && nextBtn) {
        const scrollAmount = () => {
            const card = track.querySelector('.y-c-product-card');
            return card ? card.offsetWidth + 16 : 300;
        };
        prevBtn.addEventListener('click', () => {
            track.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
        });
        nextBtn.addEventListener('click', () => {
            track.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
        });
    }
});
