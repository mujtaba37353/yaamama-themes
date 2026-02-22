document.addEventListener('DOMContentLoaded', function () {
    const accordionItems = document.querySelectorAll('.y-c-accordion-item');

    accordionItems.forEach(item => {
        const toggle = item.querySelector('.y-c-accordion-toggle');
        const panel = item.querySelector('.y-c-accordion-panel');
        
        if (!toggle || !panel) return;

        toggle.addEventListener('click', function () {
            const isActive = item.classList.contains('active');
            
            // Close all other accordion items (optional - for single open behavior)
            // Uncomment the following lines if you want only one accordion open at a time
            /*
            accordionItems.forEach(otherItem => {
                if (otherItem !== item && otherItem.classList.contains('active')) {
                    otherItem.classList.remove('active');
                    otherItem.querySelector('.y-c-accordion-toggle').setAttribute('aria-expanded', 'false');
                }
            });
            */

            // Toggle current item
            if (isActive) {
                item.classList.remove('active');
                toggle.setAttribute('aria-expanded', 'false');
            } else {
                item.classList.add('active');
                toggle.setAttribute('aria-expanded', 'true');
            }
        });
    });
});
