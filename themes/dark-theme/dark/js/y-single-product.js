window.darkThemeAssetUrl = window.darkThemeAssetUrl || function (path) {
    const base = window.DarkTheme && window.DarkTheme.baseUrl ? window.DarkTheme.baseUrl.replace(/\/$/, "") : "";
    return base ? `${base}/${path}` : path;
};

fetch(window.darkThemeAssetUrl("components/single product/y-c-single-product.html"))
    .then((response) => response.text())
    .then((data) => {
        const host = document.querySelector('[data-y="single-product"]');
        if (host && !host.children.length) {
            host.innerHTML = data;
        }
    });

(function () {
    document.addEventListener("click", function (event) {
        var minusBtn = event.target.closest("[data-qty-minus]");
        var plusBtn = event.target.closest("[data-qty-plus]");
        if (!minusBtn && !plusBtn) return;

        var wrapper = event.target.closest(".single-product .actions .qnt");
        if (!wrapper) return;
        var input = wrapper.querySelector("input[type='number']");
        if (!input) return;

        var current = parseInt(input.value, 10) || 1;
        var max = input.getAttribute("max") ? parseInt(input.getAttribute("max"), 10) : null;
        if (minusBtn) {
            input.value = Math.max(1, current - 1);
        }
        if (plusBtn) {
            var next = current + 1;
            input.value = max !== null && !isNaN(max) ? Math.min(max, next) : next;
        }
    });

    document.addEventListener("click", function (event) {
        var btn = event.target.closest("[data-buy-now]");
        if (!btn) return;
        var form = btn.closest("form.actions");
        if (!form) return;
        var hidden = form.querySelector("#single-product-redirect-checkout");
        if (hidden) {
            hidden.value = "1";
        }
        form.submit();
    });
})();
