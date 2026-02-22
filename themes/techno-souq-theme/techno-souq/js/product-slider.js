document.addEventListener('DOMContentLoaded', () => {
    // Wait briefly for products.js to populate the grids before initializing the sliders
    setTimeout(initAllProductSliders, 500);

    function initAllProductSliders() {
        // Find all containers with the class 'y-c-slider-container-js'
        const containers = document.querySelectorAll('.y-c-slider-container-js');

        if (containers.length === 0) return;

        containers.forEach(container => {
            // Check if this container has already been initialized to prevent double buttons
            if (container.classList.contains('y-c-slider-initialized')) return;

            // Check if the inner product list (ul created by products.js) exists
            // products.js creates a .y-c-product-list inside the container
            const productList = container.querySelector('.y-c-product-list');

            if (!productList) {
                // If products aren't loaded yet for this container, we might need to retry later
                // But since we are in a loop, let's just skip for now and maybe the timeout will catch it if re-called
                return;
            }

            // 1. Transform Layout
            // Add wrapper class to the main container
            container.classList.add('y-c-slider-wrapper-styled');
            container.classList.add('y-c-slider-initialized'); // Mark as done

            // Add track class to the inner list to change it from grid to flex
            productList.classList.add('y-c-slider-track-styled');

            // 2. Create Navigation Buttons
            // In RTL: Right Arrow is usually "Previous/Start", Left Arrow is "Next/End"
            const prevBtn = document.createElement('button');
            prevBtn.className = 'y-c-slider-nav-btn y-c-slider-nav-prev';
            prevBtn.setAttribute('aria-label', 'Previous');
            prevBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';

            const nextBtn = document.createElement('button');
            nextBtn.className = 'y-c-slider-nav-btn y-c-slider-nav-next';
            nextBtn.setAttribute('aria-label', 'Next');
            nextBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';

            // Append buttons to the container (outside the track)
            container.appendChild(prevBtn);
            container.appendChild(nextBtn);

            // 3. Implement Scroll Logic
            const scrollAmount = 300; // Approx width of card + gap

            nextBtn.addEventListener('click', () => {
                // In RTL, scrolling "next" usually means moving to the negative X direction (left)
                productList.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            });

            prevBtn.addEventListener('click', () => {
                // In RTL, scrolling "prev" means moving to positive X direction (right)
                productList.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });
        });

        // Retry strictly for uninitialized containers if some were empty
        const uninitialized = document.querySelectorAll('.y-c-slider-container-js:not(.y-c-slider-initialized)');
        if (uninitialized.length > 0) {
            setTimeout(initAllProductSliders, 500);
        }
    }
});