fetch("../../components/layout/y-c-navbar.html", { cache: "no-store" })
  .then((response) => response.text())
  .then((data) => {
    const container = document.querySelector('[data-y="nav"]');
    if (!container) return;
    container.innerHTML = data;
    if (window.mykitchenResolveAssets) {
      window.mykitchenResolveAssets(container);
    }
    if (window.MYK_NAV_CATEGORIES && Array.isArray(window.MYK_NAV_CATEGORIES)) {
      const menus = container.querySelectorAll(".mobile-menu, .desktop-menu");
      menus.forEach((menu) => {
        menu
          .querySelectorAll('a[href*="/product-category/"]')
          .forEach((link) => link.closest("li")?.remove());
        const anchor = menu.querySelector('[data-y="nav-categories-anchor"]');
        const fragment = document.createDocumentFragment();
        window.MYK_NAV_CATEGORIES.forEach((category) => {
          if (!category || !category.url || !category.name) return;
          const li = document.createElement("li");
          const link = document.createElement("a");
          link.href = category.url;
          if (category.icon) {
            const icon = document.createElement("i");
            icon.className = category.icon;
            link.appendChild(icon);
            link.appendChild(document.createTextNode(" "));
          }
          link.appendChild(document.createTextNode(category.name));
          li.appendChild(link);
          fragment.appendChild(li);
        });
        if (anchor && anchor.parentNode) {
          anchor.parentNode.insertBefore(fragment, anchor.nextSibling);
          anchor.remove();
        } else {
          menu.appendChild(fragment);
        }
      });
    }

        if (typeof window.MYK_CART_COUNT !== "undefined") {
          const count = parseInt(window.MYK_CART_COUNT, 10);
          container.querySelectorAll('[data-y="cart-count"]').forEach((node) => {
            const value = Number.isFinite(count) ? count : 0;
            if (value > 0) {
              node.textContent = String(value);
              node.classList.remove("is-empty");
            } else {
              node.textContent = "";
              node.classList.add("is-empty");
            }
          });
        }

        if (window.jQuery && typeof window.jQuery(document.body).trigger === "function") {
          window.jQuery(document.body).trigger("wc_fragment_refresh");
        }

    if (typeof window.wireMobileMenu === "function") {
      window.wireMobileMenu(document);
    }

    const searchForm = container.querySelector(".search-input");
    const searchInput = searchForm?.querySelector("input");
    if (searchForm && searchInput) {
      const goToShopSearch = () => {
        const query = searchInput.value.trim();
        if (!query) return;
        const url = new URL("/my-kitchen/shop/", window.location.origin);
        url.searchParams.set("s", query);
        url.searchParams.set("post_type", "product");
        window.location.href = url.toString();
      };

      if (searchForm.tagName === "FORM") {
        searchForm.addEventListener("submit", (event) => {
          event.preventDefault();
          goToShopSearch();
        });
      }

      searchInput.addEventListener("keydown", (event) => {
        if (event.key === "Enter") {
          event.preventDefault();
          goToShopSearch();
        }
      });
    }

    initializeNavbarToggle();

    setNavbarActiveFromLocation();
  });

function initializeNavbarToggle() {
  const hamburgerMenu = document.getElementById("hamburger-menu");
  const navbarLinks = document.getElementById("navbar-links");
  const searchIcon = document.getElementById("search-icon");
  const searchContainer = document.getElementById("search-container");

  if (hamburgerMenu && navbarLinks) {
    hamburgerMenu.addEventListener("click", function () {
      hamburgerMenu.classList.toggle("active");
      navbarLinks.classList.toggle("active");

      const isOpen = navbarLinks.classList.contains("active");
      document.body.classList.toggle("nav-open", isOpen);
      hamburgerMenu.setAttribute("aria-expanded", isOpen ? "true" : "false");
      if (isOpen) {
        const firstLink = navbarLinks.querySelector("a");
        if (firstLink) firstLink.focus();
      }
    });

    hamburgerMenu.addEventListener("keydown", function (e) {
      if (
        e.key === "Enter" ||
        e.key === " " ||
        e.keyCode === 13 ||
        e.keyCode === 32
      ) {
        e.preventDefault();
        hamburgerMenu.click();
      }
    });

    const navLinks = navbarLinks.querySelectorAll("a");
    navLinks.forEach((link) => {
      link.addEventListener("click", function (event) {
        event.preventDefault();

        const pageKey = derivePageKey(
          link.dataset.page || link.getAttribute("href") || ""
        );
        if (pageKey) document.body.setAttribute("data-current-page", pageKey);

        navLinks.forEach((l) => l.removeAttribute("data-active"));
        link.setAttribute("data-active", "");

        hamburgerMenu.classList.remove("active");
        navbarLinks.classList.remove("active");
        document.body.classList.remove("nav-open");
        if (hamburgerMenu) hamburgerMenu.setAttribute("aria-expanded", "false");

        const href = link.getAttribute("href");
        const target = link.getAttribute("target");
        if (href && href.trim() !== "" && href !== "#") {
          setTimeout(() => {
            if (target === "_blank") window.open(href, "_blank");
            else window.location.href = href;
          }, 50);
        }
      });

      link.addEventListener("keydown", function (event) {
        if (event.key === "Enter" || event.keyCode === 13) {
          event.preventDefault();
          link.click();
        }
      });
    });

    document.addEventListener("click", function (event) {
      if (
        !hamburgerMenu.contains(event.target) &&
        !navbarLinks.contains(event.target)
      ) {
        hamburgerMenu.classList.remove("active");
        navbarLinks.classList.remove("active");
      }
    });
  }

  if (searchIcon && searchContainer) {
    searchIcon.addEventListener("click", function (event) {
      if (window.innerWidth <= 820) {
        event.preventDefault();
        searchContainer.classList.toggle("mobile-search-active");


        if (searchContainer.classList.contains("mobile-search-active")) {
          const searchInput = searchContainer.querySelector(".search-input");
          setTimeout(() => searchInput.focus(), 100);
        }
      }
    });

    document.addEventListener("click", function (event) {
      if (!searchContainer.contains(event.target)) {
        searchContainer.classList.remove("mobile-search-active");
      }
    });
  }

  window.addEventListener("resize", function () {
    if (window.innerWidth > 820) {
      if (hamburgerMenu) hamburgerMenu.classList.remove("active");
      if (navbarLinks) navbarLinks.classList.remove("active");
      if (searchContainer)
        searchContainer.classList.remove("mobile-search-active");
    }
  });
}

function derivePageKey(source) {
  if (!source) return "";
  if (/^[a-z0-9\-]+$/i.test(source)) return source;

  try {
    const url = new URL(source, window.location.href);
    if (url.searchParams.has("filter")) return url.searchParams.get("filter");
    if (url.searchParams.has("category"))
      return url.searchParams.get("category");
    const parts = url.pathname
      .split("/")
      .filter(Boolean)
      .map((p) => decodeURIComponent(p).toLowerCase());
    if (parts.includes("home")) return "home";
    if (parts.includes("shop")) return "store";
    if (parts.includes("offers")) return "offers";
    if (parts.includes("products")) return "products";
    if (
      parts.includes("for home") ||
      parts.includes("for-home") ||
      parts.includes("for_home")
    )
      return "household";
    if (parts.includes("decoration") || parts.includes("decor")) return "decor";
  } catch (e) {
    const low = source.toLowerCase();
    if (low.indexOf("under-99") !== -1 || low.indexOf("under%2d99") !== -1)
      return "under-99";
    if (low.indexOf("decor") !== -1 || low.indexOf("decoration") !== -1)
      return "decor";
    if (low.indexOf("products") !== -1) return "products";
  }
  return "";
}

function setNavbarActiveFromLocation() {
  const navbarLinks = document.querySelectorAll("#navbar-links a");
  if (!navbarLinks || !navbarLinks.length) return;
  const current = new URL(window.location.href);
  let matched = null;

  navbarLinks.forEach((link) => {
    const href = link.getAttribute("href") || "";
    try {
      const url = new URL(href, window.location.href);
      if (
        decodeURIComponent(url.pathname) ===
        decodeURIComponent(current.pathname)
      )
        matched = link;
    } catch (e) {

    }
  });

  if (!matched) {
    navbarLinks.forEach((link) => {
      const href = link.getAttribute("href") || "";
      try {
        const url = new URL(href, window.location.href);
        if (
          url.searchParams.has("category") &&
          current.searchParams.has("category") &&
          url.searchParams.get("category") ===
            current.searchParams.get("category")
        )
          matched = link;
        if (
          url.searchParams.has("filter") &&
          current.searchParams.has("filter") &&
          url.searchParams.get("filter") === current.searchParams.get("filter")
        )
          matched = link;
      } catch (e) {}
    });
  }

  if (!matched) {
    navbarLinks.forEach((link) => {
      const pageKey = link.dataset.page || "";
      if (!pageKey) return;
      const derived = derivePageKey(window.location.href);
      if (derived && derived === pageKey) matched = link;
    });
  }

  if (matched) {
    navbarLinks.forEach((l) => l.removeAttribute("data-active"));
    matched.setAttribute("data-active", "");
    const key = derivePageKey(
      matched.dataset.page || matched.getAttribute("href") || ""
    );
    if (key) document.body.setAttribute("data-current-page", key);
  }
}

function updateAddedToCartLabels() {
  document.querySelectorAll("a.added_to_cart").forEach((link) => {
    link.textContent = "اذهب للسلة";
  });
}

document.addEventListener("DOMContentLoaded", () => {
  updateAddedToCartLabels();
  if (window.jQuery) {
    window.jQuery(document.body).on("added_to_cart", () => {
      updateAddedToCartLabels();
    });
  }
});
