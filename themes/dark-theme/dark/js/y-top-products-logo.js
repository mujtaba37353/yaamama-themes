const darkThemeAssetUrl = (path) => {
    const base = window.DarkTheme && window.DarkTheme.baseUrl ? window.DarkTheme.baseUrl.replace(/\/$/, "") : "";
    return base ? `${base}/${path}` : path;
};

fetch(darkThemeAssetUrl("components/products/y-c-top-products-logo.html"))
    .then((response) => response.text())
    .then((data) => {
        const host = document.querySelector('[data-y="top-products-logo"]');
        if (host && !host.children.length) {
            const normalized = data.replace(/\.\.\/\.\.\/assets\//g, darkThemeAssetUrl("assets/"));
            host.innerHTML = normalized;
        }
    });
