window.darkThemeAssetUrl = window.darkThemeAssetUrl || function (path) {
    const base = window.DarkTheme && window.DarkTheme.baseUrl ? window.DarkTheme.baseUrl.replace(/\/$/, "") : "";
    return base ? `${base}/${path}` : path;
};

fetch(window.darkThemeAssetUrl("components/cart/y-c-cart-table.html"))
    .then((response) => response.text())
    .then((data) => {
        const normalized = data.replace(/\.\.\/\.\.\/assets\//g, window.darkThemeAssetUrl("assets/"));
        const host = document.querySelector('[data-y="cart-table"]');
        if (host && !host.children.length) {
            host.innerHTML = normalized;
        }
    });

/**
 * أزرار تحديث الكمية في السلة (+ / -) وتحديث السلة عند الضغط
 */
function darkThemeInitCartQuantityButtons() {
    const form = document.querySelector(".woocommerce-cart-form");
    if (!form) return;

    const quantityWrappers = form.querySelectorAll(".product-quantity .quantity:not(.cart-qty-buttons-added)");
    const updateCartBtn = form.querySelector('button[name="update_cart"]');

    quantityWrappers.forEach(function (wrapper) {
        const input = wrapper.querySelector(".qty");
        if (!input) return;

        wrapper.classList.add("cart-qty-buttons-added");

        const min = parseInt(input.getAttribute("min"), 10);
        const max = parseInt(input.getAttribute("max"), 10) || 9999;
        const minVal = isNaN(min) ? 0 : min;
        const maxVal = isNaN(max) ? 9999 : max;

        function getVal() {
            return parseInt(input.value, 10) || minVal;
        }

        function submitCart() {
            if (updateCartBtn && !updateCartBtn.disabled) {
                updateCartBtn.removeAttribute("disabled");
                updateCartBtn.click();
            }
        }

        const btnMinus = document.createElement("button");
        btnMinus.type = "button";
        btnMinus.className = "cart-qty-minus";
        btnMinus.setAttribute("aria-label", "تقليل الكمية");
        btnMinus.textContent = "−";
        btnMinus.addEventListener("click", function () {
            const v = getVal();
            const newVal = Math.max(minVal, v - 1);
            input.value = newVal;
            input.dispatchEvent(new Event("change", { bubbles: true }));
            submitCart();
        });

        const btnPlus = document.createElement("button");
        btnPlus.type = "button";
        btnPlus.className = "cart-qty-plus";
        btnPlus.setAttribute("aria-label", "زيادة الكمية");
        btnPlus.textContent = "+";
        btnPlus.addEventListener("click", function () {
            const v = getVal();
            const newVal = Math.min(maxVal, v + 1);
            input.value = newVal;
            input.dispatchEvent(new Event("change", { bubbles: true }));
            submitCart();
        });

        input.parentNode.insertBefore(btnMinus, input);
        input.parentNode.insertBefore(btnPlus, input.nextSibling);
    });
}

function darkThemeRunCartQuantityButtons() {
    darkThemeInitCartQuantityButtons();
    /* بعد تحديث السلة (AJAX) يعيد WooCommerce استبدال النموذج، فنعيد إضافة الأزرار */
    if (window.jQuery) {
        window.jQuery(document.body).off("updated_cart_totals.cartQtyButtons wc_fragments_refreshed.cartQtyButtons");
        window.jQuery(document.body).on("updated_cart_totals.cartQtyButtons wc_fragments_refreshed.cartQtyButtons", function () {
            darkThemeInitCartQuantityButtons();
        });
    }
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", darkThemeRunCartQuantityButtons);
} else {
    darkThemeRunCartQuantityButtons();
}
