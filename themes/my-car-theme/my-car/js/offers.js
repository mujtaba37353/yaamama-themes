/**
 * This script runs on the Offers page.
 * It filters the global product list to only include 'hasDiscount: true' items,
 * and then re-runs the shop initialization from store.js to display only those offers.
 */

function initializeOfferPage() {
    // Check if products.js and store.js are loaded and their functions are available
    if (window.productUtils && window.productUtils.products && typeof initializeShopArchive === 'function') {

        // 1. Get the full product list provided by products.js
        const allProducts = window.productUtils.products;

        // 2. Filter to get ONLY products that have a discount
        const offerProducts = allProducts.filter(product => {
            if (!product) return false;
            // Use the 'hasDiscount' flag from the products.js file
            return product.hasDiscount === true;
        });

        // 3. OVERRIDE the global product list with our new filtered list
        // This tells the functions in store.js to only use these products.
        window.productUtils.products = offerProducts;

        // 4. Now, call the main shop initializer from store.js
        // It will use the *filtered* list and the main createProductCard function
        // (which already handles offer styling) to render the page.
        initializeShopArchive();

    } else {
        // If scripts (products.js, store.js) haven't finished loading, 
        // wait 50ms and try again.
        setTimeout(initializeOfferPage, 50);
    }
}

// Start the process for the offers page.
// This runs after products.js and store.js have loaded.
initializeOfferPage();