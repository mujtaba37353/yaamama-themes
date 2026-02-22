document.addEventListener('DOMContentLoaded', () => {
    fetch("../../components/products/y-c-products.html")
        .then((response) => response.text())
        .then((data) => {
            const productsContainer = document.querySelector('[data-y="wishlist-products"]');
            if (productsContainer) {
                productsContainer.innerHTML = data;
                const hearts = productsContainer.querySelectorAll('.wishlist-btn i');
                hearts.forEach(icon => {
                    icon.closest('.wishlist-btn').classList.add('active');
                });
            }
        })
        .catch((error) => console.error(error));
    fetch("../../components/products/y-c-sub-filter-bar.html")
      .then(response => response.text())
      .then(data => {
         const filterSection = document.querySelector("section[data-y='filter']");
         if(filterSection) {
             filterSection.outerHTML = data;
             if (typeof initializeNewFilterBar === 'function') {
                 initializeNewFilterBar();
             }
         }
      });
});

