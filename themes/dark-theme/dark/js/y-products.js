window.darkThemeAssetUrl = window.darkThemeAssetUrl || function (path) {
    const base = window.DarkTheme && window.DarkTheme.baseUrl ? window.DarkTheme.baseUrl.replace(/\/$/, "") : "";
    return base ? `${base}/${path}` : path;
};

fetch(window.darkThemeAssetUrl("components/products/y-c-products.html"))
    .then((response) => response.text())
    .then((data) => {
        const temp = document.createElement("div");
        const normalized = data.replace(/\.\.\/\.\.\/assets\//g, window.darkThemeAssetUrl("assets/"));
        temp.innerHTML = normalized;

        const productsContainer = document.querySelector('[data-y="products"]');
        if (!productsContainer) {
            return;
        }
        // لا تستبدل المحتوى إذا كان الثيم قد عرض المنتجات من PHP (صفحة المتجر)
        if (productsContainer.children.length > 0) {
            return;
        }

        if (document.title.includes("منتجات اخرى") || document.title.includes("المنتج")) {
            const productCards = temp.querySelectorAll(".product-card");
            const limited = Array.from(productCards).slice(0, 10);

            const productsWrapper = document.createElement("div");
            productsWrapper.className = "products";

            limited.forEach((card) => productsWrapper.appendChild(card));

            if (!productsContainer.children.length) {
                productsContainer.innerHTML = "";
                productsContainer.appendChild(productsWrapper);
            }
        } else {
            if (!productsContainer.children.length) {
                productsContainer.innerHTML = temp.innerHTML;
            }
        }
    })
    .catch((error) => console.error(error));
