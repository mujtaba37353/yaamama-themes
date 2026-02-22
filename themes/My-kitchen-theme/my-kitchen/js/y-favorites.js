(() => {
  const COOKIE_KEY = "myk_wishlist";
  const COOKIE_DAYS = 30;

  function readCookie(name) {
    const match = document.cookie.match(
      new RegExp("(^| )" + name + "=([^;]+)")
    );
    return match ? decodeURIComponent(match[2]) : "";
  }

  function writeCookie(name, value, days) {
    const expires = new Date();
    expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie =
      name +
      "=" +
      encodeURIComponent(value) +
      ";expires=" +
      expires.toUTCString() +
      ";path=/";
  }

  function getWishlistIds() {
    const raw = readCookie(COOKIE_KEY);
    if (!raw) return [];
    try {
      const parsed = JSON.parse(raw);
      if (!Array.isArray(parsed)) return [];
      return parsed.map((id) => parseInt(id, 10)).filter(Boolean);
    } catch (e) {
      return [];
    }
  }

  function setWishlistIds(ids) {
    const cleaned = Array.from(new Set(ids.map((id) => parseInt(id, 10))))
      .filter(Boolean)
      .sort((a, b) => a - b);
    writeCookie(COOKIE_KEY, JSON.stringify(cleaned), COOKIE_DAYS);
  }

  function syncCheckboxes(root = document) {
    const ids = new Set(getWishlistIds());
    root
      .querySelectorAll(".favorite-toggle__checkbox[data-product-id]")
      .forEach((input) => {
        const pid = parseInt(input.getAttribute("data-product-id"), 10);
        if (!pid) return;
        input.checked = ids.has(pid);
      });
  }

  function renderWishlistEmptyState(container) {
    if (!container) return;
    if (container.querySelector(".wishlist-empty")) return;
    const shopUrl = (window.MYK_SITE_URL || window.location.origin + "/") + "shop/";
    container.innerHTML =
      '<div class="wishlist-empty">' +
      '<p>لا يوجد منتجات مفضلة حالياً.</p>' +
      '<a class="btn main-button" href="' +
      shopUrl +
      '">تصفح المنتجات</a>' +
      "</div>";
  }

  document.addEventListener("change", (event) => {
    const input = event.target;
    if (!input || !input.classList.contains("favorite-toggle__checkbox")) {
      return;
    }

    const pid = parseInt(input.getAttribute("data-product-id"), 10);
    if (!pid) return;

    const current = new Set(getWishlistIds());
    if (input.checked) current.add(pid);
    else current.delete(pid);
    setWishlistIds(Array.from(current));

    if (document.body && document.body.getAttribute("data-current-page") === "wishlist") {
      const card = input.closest(".product-card");
      if (card && !input.checked) {
        card.remove();
      }
      const list = document.querySelector("[data-y='wishlist-products']");
      if (list && !list.querySelector(".product-card")) {
        renderWishlistEmptyState(list.parentElement || list);
      }
    }
  });

  window.mykitchenSyncFavorites = syncCheckboxes;
  window.mykitchenGetWishlistIds = getWishlistIds;

  document.addEventListener("DOMContentLoaded", () => {
    syncCheckboxes(document);
  });
})();
