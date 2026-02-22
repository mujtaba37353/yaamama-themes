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

    const mobileMenuCloseBtn = root.querySelector(".mobile-menu-close");
    if (mobileMenuCloseBtn) {
      mobileMenuCloseBtn.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        closeMobileMenu();
      });
    }

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

  const wireSearchToggle = (root = document) => {
    const searchIconBtns = root.querySelectorAll(".y-header-search__icon-btn");
    if (!searchIconBtns.length) return;

    searchIconBtns.forEach((btn) => {
      if (btn.dataset.wired === "true") return;
      btn.dataset.wired = "true";
      
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        
        const searchContainer = btn.closest(".y-header-search");
        if (!searchContainer) return;

        const searchWrap = searchContainer.querySelector(".y-header-search__wrap");
        const searchInput = searchContainer.querySelector(".y-header-search__input");
        
        if (!searchWrap || !searchInput) return;

        const isActive = searchWrap.classList.contains("y-header-search--active");
        
        if (isActive) {
          searchWrap.classList.remove("y-header-search--active");
          searchInput.blur();
        } else {
          searchWrap.classList.add("y-header-search--active");
          setTimeout(() => {
            searchInput.focus();
          }, 100);
        }
      });
    });
  };

  let searchCloseHandlersAdded = false;
  const addSearchCloseHandlers = () => {
    if (searchCloseHandlersAdded) return;
    searchCloseHandlersAdded = true;

    document.addEventListener("click", (e) => {
      const searchContainers = document.querySelectorAll(".y-header-search");
      searchContainers.forEach((container) => {
        if (!container.contains(e.target)) {
          const searchWrap = container.querySelector(".y-header-search__wrap");
          if (searchWrap && searchWrap.classList.contains("y-header-search--active")) {
            searchWrap.classList.remove("y-header-search--active");
            const input = searchWrap.querySelector(".y-header-search__input");
            if (input) input.blur();
          }
        }
      });
    });

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        const activeSearchWraps = document.querySelectorAll(".y-header-search__wrap.y-header-search--active");
        activeSearchWraps.forEach((wrap) => {
          wrap.classList.remove("y-header-search--active");
          const input = wrap.querySelector(".y-header-search__input");
          if (input) input.blur();
        });
      }
    });
  };

  const loadFragment = async (selector, href) => {
    const host = document.querySelector(selector);
    if (!host) return null;
    try {
      const res = await fetch(href, { cache: "no-cache" });
      const html = await res.text();
      host.innerHTML = html;
      return host;
    } catch (err) {
      console.error(`Error loading ${selector} from ${href}:`, err);
      return null;
    }
  };

  const wireActiveLinks = (root = document) => {
    const getPathSegment = (pathname) => {
      const cleaned = pathname.replace(/\/$/, "").split("/").filter(Boolean);
      return (cleaned.pop() || "index").toLowerCase();
    };

    const getLinkSegment = (anchor) => {
      const href = anchor.getAttribute("href");
      if (
        !href ||
        href.startsWith("#") ||
        href.startsWith("mailto:") ||
        href.startsWith("tel:")
      )
        return null;
      try {
        const url = new URL(href, window.location.href);
        return getPathSegment(url.pathname);
      } catch (_) {
        return null;
      }
    };

    const anchors = root.querySelectorAll("header a, .mobile-menu a");
    anchors.forEach((a) => a.classList.remove("active"));

    const currentSegment = getPathSegment(window.location.pathname);
    const exactMatches = [];
    anchors.forEach((a) => {
      const seg = getLinkSegment(a);
      if (!seg) return;
      if (seg === currentSegment) exactMatches.push(a);
    });

    const toActivate = exactMatches.length
      ? exactMatches
      : (currentSegment === "index" ? Array.from(anchors).filter((a) => {
          const s = getLinkSegment(a);
          return s === "index" || s === "";
        }) : []);
    toActivate.forEach((a) => a.classList.add("active"));
  };

  const init = async () => {
    const headerHost = await loadFragment(
      "y-navbar",
      "../../components/header.html"
    );
    await loadFragment("y-footer", "../../components/footer.html");

    const root = headerHost || document;
    wireMobileMenu(root);
    wireSearchToggle(root);
    wireSearchToggle(document);
    addSearchCloseHandlers();
    wireActiveLinks(document);
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
    const priceRangeSection = document.querySelector(".price-range-section");

    if (hideFilterBtn && filterList) {
      hideFilterBtn.addEventListener("click", () => {
        const isHidden = filterList.style.display === "none";
        filterList.style.display = isHidden ? "flex" : "none";
        if (priceRangeSection) {
          priceRangeSection.style.display = isHidden ? "flex" : "none";
        }
      });
    }

    const filterItems = document.querySelectorAll(".filter-item");
    filterItems.forEach((item) => {
      item.addEventListener("click", () => {
        const section = item.closest(".filter-section");
        const options = section?.querySelector(".filter-options");
        const icon = item.querySelector("i");

        if (options && icon) {
          options.classList.toggle("active");
          icon.classList.toggle("fa-chevron-up");
          icon.classList.toggle("fa-chevron-down");
        }
      });
    });

    const priceRangeHeader = document.querySelector(".price-range-header");
    const priceRangeContainer = document.querySelector(
      ".price-range-slider-container"
    );

    if (priceRangeHeader && priceRangeContainer) {
      priceRangeHeader.addEventListener("click", () => {
        priceRangeContainer.classList.toggle("active");
        const icon = priceRangeHeader.querySelector("i");
        if (icon) {
          icon.classList.toggle("fa-chevron-up");
          icon.classList.toggle("fa-chevron-down");
        }
      });
    }

    const clearAllLink = document.querySelector(".clear-all-link");
    if (clearAllLink) {
      clearAllLink.addEventListener("click", (e) => {
        e.preventDefault();
        document
          .querySelectorAll(".filter-item input[type='checkbox']")
          .forEach((checkbox) => {
            checkbox.checked = false;
          });
        const minSlider = document.getElementById("price-range-min");
        const maxSlider = document.getElementById("price-range-max");
        if (minSlider) minSlider.value = 0;
        if (maxSlider) maxSlider.value = 3000;
      });
    }
  };

  initFilterToggles();

  const initDualRangeSlider = () => {
    const minSlider = document.getElementById("price-range-min");
    const maxSlider = document.getElementById("price-range-max");
    const minLabel = document.querySelector(".slider-labels span:first-child");
    const maxLabel = document.querySelector(".slider-labels span:last-child");

    if (!minSlider || !maxSlider) return;

    const updateLabels = () => {
      if (minLabel) minLabel.textContent = minSlider.value;
      if (maxLabel) maxLabel.textContent = maxSlider.value;
    };

    const updateSliders = () => {
      const minValue = parseInt(minSlider.value);
      const maxValue = parseInt(maxSlider.value);

      if (minValue > maxValue) {
        minSlider.value = maxValue;
      }
      if (maxValue < minValue) {
        maxSlider.value = minValue;
      }

      updateLabels();
    };

    minSlider.addEventListener("input", updateSliders);
    maxSlider.addEventListener("input", updateSliders);

    updateLabels();
  };

  initDualRangeSlider();

  const initSortDropdown = () => {
    const sortBtn = document.querySelector(".sort-dropdown-btn");
    const sortList = document.querySelector(".sort-dropdown-list");

    if (sortBtn && sortList) {
      sortBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        sortBtn.classList.toggle("active");
        sortList.classList.toggle("active");
      });

      document.addEventListener("click", (e) => {
        if (!sortBtn.contains(e.target) && !sortList.contains(e.target)) {
          sortBtn.classList.remove("active");
          sortList.classList.remove("active");
        }
      });

      const sortOptions = sortList.querySelectorAll("a");
      sortOptions.forEach((option) => {
        option.addEventListener("click", (e) => {
          e.preventDefault();
          sortBtn.querySelector("span").textContent = option.textContent;
          sortBtn.classList.remove("active");
          sortList.classList.remove("active");
        });
      });
    }
  };

  initSortDropdown();

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

  const initAddToCartButtons = () => {
    const addToCartButtons = document.querySelectorAll(
      ".card .left button, .product-actions button[data-add-to-cart]"
    );
    if (!addToCartButtons.length) return;

    addToCartButtons.forEach((button) => {
      button.addEventListener("click", (event) => {
        event.preventDefault();
        event.stopPropagation();
        console.log("Add to cart clicked", button);
      });
    });
  };

  initAddToCartButtons();

  const favoriteButtons = document.querySelectorAll(".product-card-favorite");
  favoriteButtons.forEach((button) => {
    button.addEventListener("click", () => {
      button.classList.toggle("active");
      const icon = button.querySelector("i");
      if (icon) {
        icon.classList.toggle("fa-regular");
        icon.classList.toggle("fa-solid");
      }
    });
  });

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
        const orderId = this.getAttribute("data-order");
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
