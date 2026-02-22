document.addEventListener('DOMContentLoaded', function () {
    const title = document.querySelector('[data-y="page-header-title"]');
    const products = document.querySelectorAll('.y-c-product-card');
    if (title && products.length) {
        title.textContent = `العروض (${products.length})`;
    }
});
