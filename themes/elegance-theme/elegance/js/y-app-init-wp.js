/**
 * Elegance Theme - WP: header/footer come from PHP; active nav from body class.
 */
(() => {
  const wireMobileMenu = (root = document) => {
    const mobileMenuBtn = root.querySelector(".mobile-menu-btn");
    const mobileMenuOverlay = root.querySelector(".mobile-menu-overlay");
    if (!mobileMenuBtn || !mobileMenuOverlay) return;

    const openMobileMenu = () => {
      mobileMenuBtn.classList.add("active");
      mobileMenuOverlay.classList.add("active");
      document.body.style.overflow = "hidden";
    };

    const closeMobileMenu = () => {
      mobileMenuBtn.classList.remove("active");
      mobileMenuOverlay.classList.remove("active");
      document.body.style.overflow = "";
    };

    const toggleMobileMenu = () => {
      if (mobileMenuOverlay.classList.contains("active")) {
        closeMobileMenu();
      } else {
        openMobileMenu();
      }
    };

    mobileMenuBtn.addEventListener("click", toggleMobileMenu);

    mobileMenuOverlay.addEventListener("click", (event) => {
      if (event.target === mobileMenuOverlay) {
        closeMobileMenu();
      }
    });

    const mobileMenuLinks =
      mobileMenuOverlay.querySelectorAll(".mobile-menu a");
    mobileMenuLinks.forEach((link) => {
      link.addEventListener("click", () => {
        closeMobileMenu();
      });
    });

    document.addEventListener("keydown", (event) => {
      if (
        event.key === "Escape" &&
        mobileMenuOverlay.classList.contains("active")
      ) {
        closeMobileMenu();
      }
    });
  };

  /** WordPress: active nav from body class elegance-nav-{key}. Links use data-nav="{key}". */
  const wireActiveLinksFromBody = (root = document) => {
    const bodyClass = Array.from(document.body.classList).find((c) =>
      c.startsWith("elegance-nav-")
    );
    const navKey = bodyClass ? bodyClass.replace("elegance-nav-", "") : "home";
    const anchors = root.querySelectorAll("header a[data-nav], .mobile-menu a[data-nav]");
    anchors.forEach((a) => {
      a.classList.remove("active");
      if (a.getAttribute("data-nav") === navKey) {
        a.classList.add("active");
      }
    });
  };

  const init = () => {
    wireMobileMenu(document);
    wireActiveLinksFromBody(document);
  };

  const initRangeInput = () => {
    const rangeInput = document.getElementById("price-range");
    const rangeValueDisplay = document.getElementById("range-value");

    if (rangeInput && rangeValueDisplay) {
      const updateRangeGradient = (value) => {
        const percentage = (value / rangeInput.max) * 100;
        const primaryColor =
          getComputedStyle(document.documentElement)
            .getPropertyValue("--y-color-primary")
            .trim() || "#007bff";

        const gradient = `linear-gradient(
            to left,
            ${primaryColor} 0%,
            ${primaryColor} ${percentage}%,
            #ddd ${percentage}%,
            #ddd 100%
          )`;

        rangeInput.style.background = gradient;

        rangeValueDisplay.textContent = value;
      };

      updateRangeGradient(rangeInput.value);

      rangeInput.addEventListener("input", (e) => {
        updateRangeGradient(e.target.value);
      });

      rangeInput.addEventListener("change", (e) => {
        updateRangeGradient(e.target.value);
      });

      rangeInput.addEventListener("mouseenter", () => {
        rangeInput.style.transition = "background 0.3s ease";
      });

      rangeInput.addEventListener("mouseleave", () => {
        rangeInput.style.transition = "background 0.1s ease";
      });
    }
  };

  initRangeInput();

  const initFilterToggles = () => {
    const hideFilterBtn = document.querySelector(".hide-filter-btn");
    const filterList = document.querySelector(".filter-list");
    const updateFilterToggleLabel = () => {
      if (!hideFilterBtn || !filterList) return;
      const labelEl = hideFilterBtn.querySelector("span");
      if (!labelEl) return;
      const inlineDisplay = filterList.style.display;
      labelEl.textContent = inlineDisplay === "none" ? "عرض الفلتر" : "إخفاء الفلتر";
    };
    const setCategoryState = (categoryEl, isOpen) => {
      if (!categoryEl) return;
      categoryEl.classList.toggle("active", isOpen);
      const btn = categoryEl.querySelector(".filter-category-btn[aria-expanded]");
      if (btn) {
        btn.setAttribute("aria-expanded", isOpen ? "true" : "false");
      }
      const sub = categoryEl.querySelector(".filter-subcategories");
      if (sub) {
        sub.setAttribute("aria-hidden", isOpen ? "false" : "true");
      }
    };

    if (hideFilterBtn && filterList) {
      hideFilterBtn.addEventListener("click", () => {
        const isHidden = filterList.style.display === "none";
        if (isHidden) {
          filterList.style.removeProperty("display");
        } else {
          filterList.style.display = "none";
        }
        updateFilterToggleLabel();
      });
      updateFilterToggleLabel();
    }

    document.querySelectorAll(".filter-category").forEach((cat) => {
      setCategoryState(cat, false);
    });

    const categoryBtns = document.querySelectorAll(".filter-category-btn");
    categoryBtns.forEach((btn) => {
      btn.addEventListener("click", (e) => {
        if (btn.tagName !== "BUTTON") return;
        e.preventDefault();
        const parent = btn.closest(".filter-category");
        if (!parent) return;
        const wasOpen = parent.classList.contains("active");
        document.querySelectorAll(".filter-category").forEach((cat) => {
          setCategoryState(cat, false);
        });
        if (!wasOpen) {
          setCategoryState(parent, true);
        }
      });
    });

    const clearAllLink = document.querySelector(".clear-all-link");
    if (clearAllLink) {
      clearAllLink.addEventListener("click", (e) => {
        e.preventDefault();
        document.querySelectorAll(".filter-category").forEach((cat) => {
          setCategoryState(cat, false);
        });
        document.querySelectorAll(".filter-sub-checkbox").forEach((cb) => {
          cb.checked = false;
        });
        const minSlider = document.getElementById("price-range-min");
        const maxSlider = document.getElementById("price-range-max");
        if (minSlider) {
          minSlider.value = 0;
          minSlider.dispatchEvent(new Event("input", { bubbles: true }));
        }
        if (maxSlider) {
          maxSlider.value = 3000;
          maxSlider.dispatchEvent(new Event("input", { bubbles: true }));
        }
      });
    }
  };

  const initDualRangeSlider = () => {
    const minSlider = document.getElementById("price-range-min");
    const maxSlider = document.getElementById("price-range-max");
    const minLabel = document.getElementById("price-min-label");
    const maxLabel = document.getElementById("price-max-label");
    const fillEl = document.getElementById("price-range-fill");
    const minRange = Number(minSlider?.min || 0);
    const maxRange = Number(maxSlider?.max || 3000);

    if (!minSlider || !maxSlider) return;

    // Keep slider math in a fixed LTR axis to avoid RTL inversion.
    minSlider.style.direction = "ltr";
    maxSlider.style.direction = "ltr";

    const getQueryNumber = (key) => {
      const params = new URLSearchParams(window.location.search);
      if (!params.has(key)) return null;
      const value = Number(params.get(key));
      return Number.isFinite(value) ? value : null;
    };

    const clamp = (value) => Math.min(maxRange, Math.max(minRange, value));

    const queryMin = getQueryNumber("min_price");
    const queryMax = getQueryNumber("max_price");
    if (queryMin !== null) minSlider.value = String(clamp(queryMin));
    if (queryMax !== null) maxSlider.value = String(clamp(queryMax));

    const updateLabels = () => {
      if (minLabel) minLabel.textContent = minSlider.value;
      if (maxLabel) maxLabel.textContent = maxSlider.value;
    };

    const updateFill = () => {
      if (!fillEl) return;
      const minVal = parseInt(minSlider.value, 10);
      const maxVal = parseInt(maxSlider.value, 10);
      const range = maxRange - minRange || 1;
      const minPct = ((minVal - minRange) / range) * 100;
      const maxPct = ((maxVal - minRange) / range) * 100;
      fillEl.style.left = minPct + "%";
      fillEl.style.width = (maxPct - minPct) + "%";
    };

    const updateSliders = (changed) => {
      let minValue = parseInt(minSlider.value, 10);
      let maxValue = parseInt(maxSlider.value, 10);

      minValue = clamp(minValue);
      maxValue = clamp(maxValue);

      if (changed === "min" && minValue > maxValue) {
        minValue = maxValue;
      }
      if (changed === "max" && maxValue < minValue) {
        maxValue = minValue;
      }

      minSlider.value = String(minValue);
      maxSlider.value = String(maxValue);

      updateLabels();
      updateFill();
    };

    const applyPriceFilter = () => {
      const minValue = clamp(parseInt(minSlider.value, 10));
      const maxValue = clamp(parseInt(maxSlider.value, 10));
      const url = new URL(window.location.href);

      // Reset pagination when a new filter is applied.
      url.pathname = url.pathname.replace(/\/page\/\d+\/?$/, "/");

      if (minValue <= minRange && maxValue >= maxRange) {
        url.searchParams.delete("min_price");
        url.searchParams.delete("max_price");
      } else {
        url.searchParams.set("min_price", String(minValue));
        url.searchParams.set("max_price", String(maxValue));
      }

      if (url.toString() !== window.location.href) {
        window.location.assign(url.toString());
      }
    };

    minSlider.addEventListener("input", () => updateSliders("min"));
    maxSlider.addEventListener("input", () => updateSliders("max"));
    minSlider.addEventListener("change", applyPriceFilter);
    maxSlider.addEventListener("change", applyPriceFilter);

    updateSliders();
  };

  const initSortDropdown = () => {
    const sortBtn = document.querySelector(".sort-dropdown-btn");
    const sortList = document.querySelector(".sort-dropdown-list");

    if (sortBtn && sortList) {
      sortBtn.classList.remove("active");
      sortList.classList.remove("active");
      sortList.style.display = "none";
      sortList.setAttribute("aria-hidden", "true");

      const openList = () => {
        sortBtn.classList.add("active");
        sortList.classList.add("active");
        sortList.style.removeProperty("display");
        sortList.setAttribute("aria-hidden", "false");
      };
      const closeList = () => {
        sortBtn.classList.remove("active");
        sortList.classList.remove("active");
        sortList.style.display = "none";
        sortList.setAttribute("aria-hidden", "true");
      };

      sortBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        if (sortList.classList.contains("active")) {
          closeList();
        } else {
          openList();
        }
      });

      document.addEventListener("click", (e) => {
        if (!sortBtn.contains(e.target) && !sortList.contains(e.target)) {
          closeList();
        }
      });

      const sortOptions = sortList.querySelectorAll("a");
      sortOptions.forEach((option) => {
        option.addEventListener("click", (e) => {
          sortBtn.querySelector("span").textContent = option.textContent.trim();
          closeList();
          const href = option.getAttribute("href");
          if (href && href !== "#") {
            window.location.href = href;
          }
        });
      });
    }
  };

  const runStoreInit = () => {
    initFilterToggles();
    initDualRangeSlider();
    initSortDropdown();
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", runStoreInit, { once: true });
  } else {
    runStoreInit();
  }

  const initStatusPopups = () => {
    const triggers = document.querySelectorAll("[data-status-popup]");
    if (!triggers.length) return;

    const createOverlay = () => {
      const overlay = document.createElement("div");
      overlay.className = "status-popup-overlay";
      overlay.innerHTML = `
        <div class="status-popup-card" role="alert" aria-live="polite">
          <span class="status-popup-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" focusable="false">
              <path d="M5 13l4 4L19 7"></path>
            </svg>
          </span>
          <p class="status-popup-text"></p>
        </div>
      `;
      document.body.appendChild(overlay);
      return overlay;
    };

    const overlay =
      document.querySelector(".status-popup-overlay") || createOverlay();
    const messageEl = overlay.querySelector(".status-popup-text");
    let hideTimeout;

    const closePopup = () => {
      overlay.classList.remove("active");
      if (hideTimeout) {
        clearTimeout(hideTimeout);
        hideTimeout = null;
      }
    };

    const showPopup = (message, duration) => {
      if (messageEl) messageEl.textContent = message;
      overlay.classList.add("active");
      if (hideTimeout) clearTimeout(hideTimeout);
      hideTimeout = window.setTimeout(closePopup, duration);
    };

    overlay.addEventListener("click", (event) => {
      if (event.target === overlay) closePopup();
    });

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape" && overlay.classList.contains("active")) {
        closePopup();
      }
    });

    triggers.forEach((trigger) => {
      trigger.addEventListener("click", (event) => {
        if (
          trigger.matches("button[type='submit'], input[type='submit']") ||
          (trigger.tagName === "BUTTON" && !trigger.getAttribute("type"))
        ) {
          event.preventDefault();
        }

        const message =
          trigger.dataset.statusPopupMessage?.trim() || "تم الحفظ بنجاح";
        const duration =
          Number(trigger.dataset.statusPopupDuration) || 2600;
        showPopup(message, duration);
      });
    });
  };

  initStatusPopups();

  const FAV_STORAGE_KEY = "elegance_favorites";
  const getFavorites = () => {
    try {
      const raw = localStorage.getItem(FAV_STORAGE_KEY);
      const arr = raw ? JSON.parse(raw) : [];
      return Array.isArray(arr) ? arr.map(Number).filter(Boolean) : [];
    } catch (e) {
      return [];
    }
  };
  const setFavorites = (ids) => {
    localStorage.setItem(FAV_STORAGE_KEY, JSON.stringify(ids));
  };
  const toggleFavorite = (productId) => {
    const id = Number(productId);
    if (!id) return;
    const ids = getFavorites();
    const idx = ids.indexOf(id);
    if (idx === -1) ids.push(id);
    else ids.splice(idx, 1);
    setFavorites(ids);
    return ids.indexOf(id) !== -1;
  };
  const applyFavoriteState = (el, isActive) => {
    el.classList.toggle("active", isActive);
    const icon = el.querySelector("i") || (el.tagName === "I" ? el : null);
    if (icon) {
      icon.classList.toggle("fa-regular", !isActive);
      icon.classList.toggle("fa-solid", isActive);
    }
  };
  const favoriteButtons = document.querySelectorAll(".product-card-favorite");
  const favIds = getFavorites();
  favoriteButtons.forEach((button) => {
    const productId = button.getAttribute("data-product-id");
    if (productId && favIds.indexOf(Number(productId)) !== -1) {
      applyFavoriteState(button, true);
    }
    button.addEventListener("click", (e) => {
      e.preventDefault();
      const id = button.getAttribute("data-product-id");
      if (!id) return;
      const nowActive = toggleFavorite(id);
      applyFavoriteState(button, nowActive);
      if (window.location.pathname.indexOf("favorites") !== -1) {
        const ids = getFavorites();
        const url = ids.length
          ? window.location.pathname + "?ids=" + ids.slice(0, 50).join(",")
          : window.location.pathname;
        window.location.replace(url);
      }
    });
  });
  if (typeof window.eleganceGetFavorites === "undefined") {
    window.eleganceGetFavorites = getFavorites;
  }
  if (window.location.pathname.indexOf("favorites") !== -1) {
    const params = new URLSearchParams(window.location.search);
    const urlIds = params.get("ids");
    if (urlIds) {
      const ids = urlIds.split(",").map(Number).filter(Boolean);
      if (ids.length > 0) setFavorites(ids);
    }
    if (!window.location.search && getFavorites().length > 0) {
      window.location.replace(
        window.location.pathname + "?ids=" + getFavorites().slice(0, 50).join(",")
      );
    }
  }

  const initSingleProductThumbs = () => {
    const detailsSection = document.querySelector(".details-section");
    if (!detailsSection) return;
    const mainImage = detailsSection.querySelector(".elegance-main-product-image");
    const thumbButtons = detailsSection.querySelectorAll(".elegance-thumb-btn[data-main-image]");
    if (!mainImage || thumbButtons.length === 0) return;
    thumbButtons.forEach((btn) => {
      btn.addEventListener("click", () => {
        const nextUrl = btn.getAttribute("data-main-image");
        if (!nextUrl) return;
        mainImage.setAttribute("src", nextUrl);
        thumbButtons.forEach((item) => item.classList.remove("is-active"));
        btn.classList.add("is-active");
      });
    });
  };
  initSingleProductThumbs();

  const initSingleProductQuantityButtons = () => {
    const quantityWrappers = document.querySelectorAll(".details-section .quantity");
    if (!quantityWrappers.length) return;
    quantityWrappers.forEach((wrapper) => {
      const input = wrapper.querySelector("input[name='quantity']");
      if (!input) return;
      wrapper.querySelectorAll(".elegance-qty-btn[data-step]").forEach((btn) => {
        btn.addEventListener("click", () => {
          const step = Number(btn.getAttribute("data-step")) || 0;
          const min = Number(input.min || 1);
          const max = Number(input.max || 9999);
          const current = Number(input.value || min);
          const next = Math.min(max, Math.max(min, current + step));
          input.value = String(next);
          input.dispatchEvent(new Event("change", { bubbles: true }));
        });
      });
    });
  };
  initSingleProductQuantityButtons();

  const passwordToggles = document.querySelectorAll(".password-toggle");
  passwordToggles.forEach((toggle) => {
    toggle.addEventListener("click", () => {
      const passwordInput = toggle.parentElement.querySelector(
        "input[type='password'], input[type='text']"
      );
      const icon = toggle.querySelector("i");
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      } else {
        passwordInput.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      }
    });
  });

  const initProfileOrders = () => {
    const ordersList = document.getElementById("ordersList");
    const orderDetailsPage = document.getElementById("orderDetailsPage");
    const viewButtons = document.querySelectorAll(".view-order-btn");
    const backButton = document.getElementById("backToOrders");

    if (!ordersList || !orderDetailsPage) return;

    viewButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const orderItem = this.closest(".item");

        const orderNumber = orderItem.querySelector(".order-number")
          ? orderItem.querySelector(".order-number").textContent
          : "";
        const orderDate = orderItem.querySelector(".order-date")
          ? orderItem.querySelector(".order-date").textContent
          : "";

        const orderNumberEl = document.getElementById("orderNumber");
        const orderDateEl = document.getElementById("orderDate");

        if (orderNumberEl) orderNumberEl.textContent = orderNumber;
        if (orderDateEl) orderDateEl.textContent = orderDate;

        ordersList.style.display = "none";
        ordersList.classList.remove("active");
        orderDetailsPage.classList.add("active");
      });
    });

    if (backButton) {
      backButton.addEventListener("click", function (e) {
        e.preventDefault();
        orderDetailsPage.classList.remove("active");
        ordersList.style.display = "block";
        ordersList.classList.add("active");
      });
    }
  };

  initProfileOrders();

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init, { once: true });
  } else {
    init();
  }
})();
