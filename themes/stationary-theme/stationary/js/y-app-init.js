(() => {
  const sendDebugLog = (runId, hypothesisId, message, data) => {
    void runId;
    void hypothesisId;
    void message;
    void data;
  };

  const probeHeroSlider = (runId = "initial") => {
    const hero = document.querySelector(".hero-section");
    if (!hero) return;
    const imgsWrap = hero.querySelector(".carsousel .imgs");
    const dots = hero.querySelectorAll(".dots .dot");
    const images = hero.querySelectorAll(".carsousel .imgs img");
    const initialTransform = imgsWrap ? getComputedStyle(imgsWrap).transform : null;

    sendDebugLog(runId, "H4", "Hero slider initial probe", {
      path: window.location.pathname,
      heroFound: !!hero,
      imagesCount: images.length,
      dotsCount: dots.length,
      initialTransform,
    });

    window.setTimeout(() => {
      const laterTransform = imgsWrap ? getComputedStyle(imgsWrap).transform : null;
      const activeDotIndex = Array.from(dots).findIndex((dot) =>
        dot.classList.contains("active")
      );
      sendDebugLog(runId, "H4", "Hero slider follow-up probe", {
        path: window.location.pathname,
        laterTransform,
        activeDotIndex,
        transformChanged: initialTransform !== laterTransform,
      });
    }, 5600);
  };

  const probeAccountUi = (runId = "initial") => {
    if (!window.location.pathname.includes("/my-account")) return;
    const passwordToggle = document.querySelector(
      ".woocommerce-EditAccountForm .show-password-input"
    );
    const accountNavLinks = Array.from(
      document.querySelectorAll(".sidbar .links a")
    ).map((a) => a.textContent.trim());
    const addressCards = document.querySelectorAll(
      ".woocommerce-Addresses .woocommerce-Address"
    ).length;
    const addressFormExists = !!document.querySelector("form.woocommerce-AddressForm");

    sendDebugLog(runId, "A1", "Account UI probe", {
      path: window.location.pathname,
      hasPasswordToggle: !!passwordToggle,
      passwordToggleClass: passwordToggle?.className || null,
      passwordToggleStyles: passwordToggle
        ? {
            display: getComputedStyle(passwordToggle).display,
            background: getComputedStyle(passwordToggle).backgroundColor,
            borderRadius: getComputedStyle(passwordToggle).borderRadius,
            width: getComputedStyle(passwordToggle).width,
            height: getComputedStyle(passwordToggle).height,
          }
        : null,
      accountNavLinks,
      hasDashboardLink: accountNavLinks.includes("لوحة التحكم"),
      addressCards,
      addressFormExists,
    });
  };

  const initHeroSlider = () => {
    const hero = document.querySelector(".hero-section");
    if (!hero) return;

    const imgsWrap = hero.querySelector(".carsousel .imgs");
    const images = Array.from(hero.querySelectorAll(".carsousel .imgs img"));
    const dots = Array.from(hero.querySelectorAll(".dots .dot"));
    if (!imgsWrap || images.length <= 1) return;

    sendDebugLog("initial", "H5", "Hero slider init probe", {
      path: window.location.pathname,
      imagesCount: images.length,
      dotsCount: dots.length,
      wrapClientWidth: imgsWrap.clientWidth,
      wrapScrollWidth: imgsWrap.scrollWidth,
      wrapComputedWidth: getComputedStyle(imgsWrap).width,
      existingTimer: hero.dataset.sliderTimer || null,
    });

    let currentIndex = 0;
    const maxIndex = images.length - 1;
    let slideLogCount = 0;

    const goToSlide = (index) => {
      currentIndex = index < 0 ? maxIndex : index > maxIndex ? 0 : index;
      const wrapWidth = imgsWrap.clientWidth || 0;
      imgsWrap.style.transform = `translateX(-${currentIndex * wrapWidth}px)`;
      dots.forEach((dot, dotIndex) => {
        dot.classList.toggle("active", dotIndex === currentIndex);
      });
      if (slideLogCount < 6) {
        sendDebugLog("initial", "H6", "Hero goToSlide probe", {
          path: window.location.pathname,
          currentIndex,
          inlineTransform: imgsWrap.style.transform,
          computedTransform: getComputedStyle(imgsWrap).transform,
          wrapClientWidth: imgsWrap.clientWidth,
        });
        slideLogCount++;
      }
    };

    dots.forEach((dot, dotIndex) => {
      dot.addEventListener("click", () => goToSlide(dotIndex));
    });

    goToSlide(0);
    window.addEventListener("resize", () => goToSlide(currentIndex));
    if (hero.dataset.sliderTimer) {
      window.clearInterval(Number(hero.dataset.sliderTimer));
    }
    const timer = window.setInterval(() => {
      goToSlide(currentIndex + 1);
    }, 5200);
    hero.dataset.sliderTimer = String(timer);
  };

  let headerOffsetWired = false;
  const wireHeaderOffset = () => {
    const applyHeaderOffset = () => {
      const header = document.querySelector("header");
      if (!header) return;
      const headerHeight = Math.ceil(header.getBoundingClientRect().height);
      document.documentElement.style.setProperty(
        "--y-header-offset",
        `${headerHeight}px`
      );
      document.body.style.paddingTop = `${headerHeight}px`;
    };

    applyHeaderOffset();
    if (headerOffsetWired) return;
    headerOffsetWired = true;
    window.addEventListener("resize", applyHeaderOffset);
    window.addEventListener("orientationchange", applyHeaderOffset);
  };

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
    const getCurrentMatchPath = () => {
      let path = window.location.pathname;
      if (!/\.html$/i.test(path)) {
        if (!path.endsWith("/")) path += "/";
        path += "index.html";
      }
      return path.toLowerCase();
    };

    const getLinkEnd = (anchor) => {
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
        const last = url.pathname.split("/").pop() || "index.html";
        return last.toLowerCase();
      } catch (_) {
        return null;
      }
    };

    const anchors = root.querySelectorAll("header a, .mobile-menu a");
    anchors.forEach((a) => a.classList.remove("active"));

    const currentPath = getCurrentMatchPath();
    const currentEnd = (() => {
      const parts = currentPath.split("/");
      return (parts[parts.length - 1] || "index.html").toLowerCase();
    })();
    const exactMatches = [];
    anchors.forEach((a) => {
      const end = getLinkEnd(a);
      if (end && currentPath.endsWith(end)) exactMatches.push(a);
    });

    if (currentEnd === "login.html" || currentEnd === "forget-password.html") {
      anchors.forEach((a) => {
        const end = getLinkEnd(a);
        if (end === "signup.html") exactMatches.push(a);
      });
    }

    const toActivate = exactMatches.length
      ? exactMatches
      : Array.from(anchors).filter((a) => getLinkEnd(a) === "index.html");
    toActivate.forEach((a) => a.classList.add("active"));
  };

  const init = async () => {
    const headerHost = document.querySelector("y-navbar")
      ? await loadFragment("y-navbar", "../../components/header.html")
      : null;
    const footerHost = document.querySelector("y-footer");
    if (footerHost) await loadFragment("y-footer", "../../components/footer.html");

    const headerRoot = headerHost || document;
    wireMobileMenu(headerRoot);
    wireSearchToggle(headerRoot);
    wireSearchToggle(document);
    addSearchCloseHandlers();
    wireActiveLinks(document);
    wireHeaderOffset();
    initHeroSlider();
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

  const initFavorites = () => {
    const favoriteLabels = Array.from(document.querySelectorAll(".favorite-toggle"));
    favoriteLabels.forEach((label) => {
      label.addEventListener("click", (event) => {
        event.preventDefault();
        event.stopPropagation();
        const input = label.querySelector(".favorite-toggle__checkbox");
        if (!input) return;
        input.checked = !input.checked;
        input.dispatchEvent(new Event("change", { bubbles: true }));
      });
    });

    const favoriteInputs = Array.from(
      document.querySelectorAll(".favorite-toggle__checkbox[data-product-id]")
    );
    if (!favoriteInputs.length) return;

    const cfg = window.stationaryFavorites || {};
    const initialIds = Array.isArray(cfg.initialIds)
      ? cfg.initialIds.map((id) => Number(id))
      : [];
    const favorites = new Set(initialIds);
    const localStorageKey = "stationary_favorites";

    const syncInputState = (input) => {
      const productId = Number(input.dataset.productId || 0);
      if (!productId) return;
      input.checked = favorites.has(productId);
    };

    const persistGuestFavorites = () => {
      try {
        localStorage.setItem(localStorageKey, JSON.stringify(Array.from(favorites)));
      } catch (_) {}
    };

    if (!cfg.isLoggedIn) {
      try {
        const raw = localStorage.getItem(localStorageKey);
        const fromStorage = raw ? JSON.parse(raw) : [];
        if (Array.isArray(fromStorage)) {
          fromStorage.forEach((id) => favorites.add(Number(id)));
        }
      } catch (_) {}
    }

    favoriteInputs.forEach((input) => {
      syncInputState(input);
      input.addEventListener("change", () => {
        const productId = Number(input.dataset.productId || 0);
        if (!productId) return;

        if (input.checked) favorites.add(productId);
        else favorites.delete(productId);

        if (!cfg.isLoggedIn) {
          persistGuestFavorites();
          return;
        }

        const form = new FormData();
        form.append("action", "stationary_toggle_favorite");
        form.append("nonce", cfg.nonce || "");
        form.append("product_id", String(productId));
        form.append("is_favorite", input.checked ? "1" : "0");

        fetch(cfg.ajaxUrl || "", { method: "POST", body: form })
          .then((res) => res.json())
          .then((payload) => {
            if (!payload || !payload.success) return;
            const serverIds = Array.isArray(payload.data?.favorites)
              ? payload.data.favorites.map((id) => Number(id))
              : [];
            favorites.clear();
            serverIds.forEach((id) => favorites.add(id));
            favoriteInputs.forEach((el) => syncInputState(el));
          })
          .catch(() => {});
      });
    });
  };
  initFavorites();

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
