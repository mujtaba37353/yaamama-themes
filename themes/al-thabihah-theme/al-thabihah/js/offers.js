// Offers page - shows only products with active offers

document.addEventListener('DOMContentLoaded', function () {
    // Ensure productUtils is loaded
    if (!window.productUtils) {
        console.error("productUtils not available. Make sure products.js is loaded first.");
        return;
    }

    // Get only products that have offers
    const offerProducts = window.productUtils.products.filter(product => product.offer === true && product.oldPrice);

    // Update page title to show number of offers
    const pageTitle = document.querySelector('[data-y="page-title"]');
    if (pageTitle) {
        pageTitle.textContent = `العروض (${offerProducts.length})`;
    }

    // Initialize show more functionality with offer products
    window.productUtils.initializeShowMore(offerProducts, '#products-container', '#show-more-btn');
});
