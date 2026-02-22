(() => {
  const wireMobileMenu = (root = document) => {
    const mobileMenuBtn =
      root.querySelector(".mobile-menu-btn") ||
      root.querySelector(".menu-toggle-btn") ||
      root.querySelector(".menu-btn");
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


  const wireActiveLinks = (root = document) => {
    const anchors = root.querySelectorAll("header a, .mobile-menu a, .sidebar a");

    const normalize = (path) => {
      if (!path) return "";
      try {
        const url = new URL(path, window.location.href);
        let p = url.pathname.toLowerCase();
        p = p.replace(/\/$/, "");
        if (p.endsWith("/index.html")) {
          p = p.substring(0, p.length - 11);
        }
        return p || "/";
      } catch (e) {
        return path.toLowerCase().replace(/\/$/, "").replace(/\/index\.html$/, "") || "/";
      }
    };

    const currentPath = normalize(window.location.pathname);

    anchors.forEach((a) => {
      a.classList.remove("active");

      const href = a.getAttribute("href");
      if (!href || href.startsWith("#") || href.startsWith("mailto:") || href.startsWith("tel:")) return;

      const linkPath = normalize(a.href);

      if (linkPath === currentPath) {
        a.classList.add("active");

        const parentSubmenu = a.closest(".sidebar-item.has-submenu");
        if (parentSubmenu) {
          parentSubmenu.classList.add("open");
        }
      }
    });
  };

  const init = () => {
    wireMobileMenu(document);
    wireActiveLinks(document);
    wireFaqs(document);
    wirePasswordToggles(document);
    wireBillingToggle(document);
    wireTemplateTabs(document);
  };

  const wirePasswordToggles = (root = document) => {
    const passwordToggles = root.querySelectorAll(".password-toggle");
    passwordToggles.forEach((toggle) => {
      toggle.addEventListener("click", () => {
        const targetId = toggle.getAttribute("data-target");
        const passwordInput = root.querySelector(`#${targetId}`);
        if (!passwordInput) return;

        const isPassword = passwordInput.type === "password";
        passwordInput.type = isPassword ? "text" : "password";

        toggle.classList.toggle("fa-eye", !isPassword);
        toggle.classList.toggle("fa-eye-slash", isPassword);
      });
    });
  };

  const wireFaqs = (root = document) => {
    const faqQuestions = root.querySelectorAll(".faq-question");
    faqQuestions.forEach((button) => {
      button.addEventListener("click", () => {
        const faqItem = button.closest(".faq-item");
        if (!faqItem) return;

        const isActive = faqItem.classList.contains("active");

        root.querySelectorAll(".faq-item.active").forEach((item) => {
          if (item !== faqItem) item.classList.remove("active");
        });

        faqItem.classList.toggle("active", !isActive);
      });
    });
  };

  const wireBillingToggle = (root = document) => {
    const toggleButtons = root.querySelectorAll(".billing-toggle .toggle-option");
    const freePlanCard = root.querySelector(".plan-card.free-plan");

    toggleButtons.forEach((button) => {
      button.addEventListener("click", () => {
        toggleButtons.forEach((btn) => btn.classList.remove("active"));

        button.classList.add("active");

        if (button.textContent.includes("سنوي")) {
          if (freePlanCard) freePlanCard.style.display = "none";
        } else {
          if (freePlanCard) freePlanCard.style.display = "flex";
        }
      });
    });
  };

  const wireTemplateTabs = (root = document) => {
    const tabButtons = root.querySelectorAll(".tabs [data-template-filter]");
    const templateGroups = root.querySelectorAll("[data-template-group]");
    if (!tabButtons.length || !templateGroups.length) return;

    const showGroup = (filter) => {
      templateGroups.forEach((group) => {
        const matches = group.getAttribute("data-template-group") === filter;
        group.style.display = matches ? "" : "none";
      });
    };

    tabButtons.forEach((button) => {
      button.addEventListener("click", () => {
        tabButtons.forEach((btn) => btn.classList.remove("active"));
        button.classList.add("active");
        showGroup(button.getAttribute("data-template-filter"));
      });
    });

    const activeButton = root.querySelector(".tabs [data-template-filter].active") || tabButtons[0];
    if (activeButton) {
      activeButton.classList.add("active");
      showGroup(activeButton.getAttribute("data-template-filter"));
    }
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init, { once: true });
  } else {
    init();
  }
})();
