window.darkThemeAssetUrl = window.darkThemeAssetUrl || function (path) {
    const base = window.DarkTheme && window.DarkTheme.baseUrl ? window.DarkTheme.baseUrl.replace(/\/$/, "") : "";
    return base ? `${base}/${path}` : path;
};

fetch(window.darkThemeAssetUrl("components/payment/y-c-payment-summary-card.html"))
    .then((response) => response.text())
    .then((data) => {
        const normalized = data.replace(/\.\.\/\.\.\/assets\//g, window.darkThemeAssetUrl("assets/"));
        const host = document.querySelector('[data-y="payment-summary"]');
        if (host && !host.children.length) {
            host.innerHTML = normalized;
        }
    });
