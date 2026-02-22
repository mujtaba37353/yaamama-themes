window.darkThemeAssetUrl = window.darkThemeAssetUrl || function (path) {
    const base = window.DarkTheme && window.DarkTheme.baseUrl ? window.DarkTheme.baseUrl.replace(/\/$/, "") : "";
    return base ? `${base}/${path}` : path;
};

fetch(window.darkThemeAssetUrl("components/offers/y-c-offers.html"))
    .then((response) => response.text())
    .then((data) => {
        const temp = document.createElement("div");
        const normalized = data.replace(/\.\.\/\.\.\/assets\//g, window.darkThemeAssetUrl("assets/"));
        temp.innerHTML = normalized;

        const productsContainer = document.querySelector('[data-y="offers"]');
        if (productsContainer && !productsContainer.children.length) {
            productsContainer.innerHTML = temp.innerHTML;
        }
    })
    .catch((error) => console.error(error));
