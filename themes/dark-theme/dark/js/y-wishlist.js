window.darkThemeAssetUrl = window.darkThemeAssetUrl || function (path) {
    const base = window.DarkTheme && window.DarkTheme.baseUrl ? window.DarkTheme.baseUrl.replace(/\/$/, "") : "";
    return base ? `${base}/${path}` : path;
};

document.addEventListener('DOMContentLoaded', () => {
    fetch(window.darkThemeAssetUrl("components/products/y-c-products.html"))
        .then((response) => response.text())
        .then((data) => {
            const normalized = data.replace(/\.\.\/\.\.\/assets\//g, window.darkThemeAssetUrl("assets/"));
            const productsContainer = document.querySelector('[data-y="wishlist-products"]');
            if (productsContainer && !productsContainer.children.length) {
                productsContainer.innerHTML = normalized;
                const heartInputs = productsContainer.querySelectorAll('.product-card-fav-input');
                heartInputs.forEach(input => {
                    input.checked = true;
                });
            }
        })
        .catch((error) => console.error(error));
    fetch(window.darkThemeAssetUrl("components/products/y-c-sub-filter-bar.html"))
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

