document.addEventListener('DOMContentLoaded', function () {
    // Ensure productUtils is loaded
    if (!window.productUtils) {
        console.error("productUtils not available. Make sure products.js is loaded first.");
        return;
    }

    const featuredContainer = document.getElementById('featured-products-container');
    const offersContainer = document.getElementById('offers-products-container');

    if (featuredContainer) {
        // Get the first 8 products as "featured"
        const featuredProducts = window.productUtils.products.slice(0, 8);

        let productsHTML = '';
        featuredProducts.forEach(product => {
            // Use the createProductCard function from products.js
            productsHTML += window.productUtils.createProductCard(product);
        });

        featuredContainer.innerHTML = productsHTML;
    }

    if (offersContainer) {
        // Get products that have offers
        const offerProducts = window.productUtils.products.filter(product => product.offer && product.oldPrice);

        // If we have less than 8 offer products, duplicate them to reach 8
        let displayProducts = [...offerProducts];
        while (displayProducts.length < 8 && offerProducts.length > 0) {
            displayProducts.push(...offerProducts.slice(0, 8 - displayProducts.length));
        }

        // Take only the first 8 products
        displayProducts = displayProducts.slice(0, 8);

        let offersHTML = '';
        displayProducts.forEach(product => {
            // Use the createProductCard function from products.js
            offersHTML += window.productUtils.createProductCard(product);
        });

        offersContainer.innerHTML = offersHTML;
    }

});