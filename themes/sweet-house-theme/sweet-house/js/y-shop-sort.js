/**
 * Shop sort dropdown — toggle & redirect on selection.
 * WooCommerce uses ?orderby=rating|price|price-desc
 */
(function () {
  "use strict";

  function initShopSort() {
    const dropdown = document.getElementById("shop-sort-dropdown");
    if (!dropdown) return;

    const trigger = dropdown.querySelector(".dropdown-trigger");
    const options = dropdown.querySelectorAll(".dropdown-options li");
    const currentSortEl = dropdown.querySelector(".current-sort");

    if (!trigger || !options.length) return;

    // Toggle dropdown
    trigger.addEventListener("click", function (e) {
      e.stopPropagation();
      dropdown.classList.toggle("open");
    });

    // Close on outside click
    document.addEventListener("click", function () {
      dropdown.classList.remove("open");
    });

    // On option click: redirect with orderby
    options.forEach(function (opt) {
      opt.addEventListener("click", function (e) {
        e.preventDefault();
        const sortValue = opt.getAttribute("data-sort");
        const sortText = opt.textContent.trim();

        if (!sortValue) return;

        const url = new URL(window.location.href);
        url.searchParams.set("orderby", sortValue);
        url.searchParams.delete("paged"); // Reset pagination when changing sort

        window.location.href = url.toString();
      });
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initShopSort);
  } else {
    initShopSort();
  }
})();
