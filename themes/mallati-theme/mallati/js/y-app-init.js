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

    // 1. Handle Category Links (Hash-based)
    const updateCategoryLinks = () => {
      const currentHash = window.location.hash;
      if (currentHash) {
        anchors.forEach((a) => {
          if (a.classList.contains("category-link")) {
            a.classList.remove("active");
            const href = a.getAttribute("href");
            // Check if href ends with the current hash
            if (href && href.endsWith(currentHash)) {
              a.classList.add("active");
            }
          }
        });
      }
    };

    if (currentEnd === "category.html") {
      updateCategoryLinks();
      // Ensure we don't add multiple listeners if this function runs multiple times
      if (!window._categoryHashListenerAdded) {
        window.addEventListener("hashchange", updateCategoryLinks);
        window._categoryHashListenerAdded = true;
      }
    }

    // 2. Handle Normal Links (Path-based)
    const exactMatches = [];
    anchors.forEach((a) => {
      if (a.classList.contains("nav-link-no-active") || a.classList.contains("category-link")) return;
      const end = getLinkEnd(a);
      // Skip if it's a hash link on the same page (unless we want that)
      if (a.getAttribute('href').startsWith('#')) return;

      if (end && currentPath.endsWith(end)) exactMatches.push(a);
    });

    if (currentEnd === "login.html" || currentEnd === "forget-password.html") {
      anchors.forEach((a) => {
        if (a.classList.contains("nav-link-no-active") || a.classList.contains("category-link")) return;
        const end = getLinkEnd(a);
        if (end === "signup.html") exactMatches.push(a);
      });
    }

    const toActivate = exactMatches.length
      ? exactMatches
      : Array.from(anchors).filter(
          (a) =>
            !a.classList.contains("nav-link-no-active") &&
            !a.classList.contains("category-link") &&
            getLinkEnd(a) === "index.html"
        );
        
    // Only activate path-based links if we are NOT on category page with a hash (to avoid conflict or double active)
    // Or just let them coexist. Usually main nav items are distinct.
    toActivate.forEach((a) => a.classList.add("active"));
  };

  const getComponentPath = (componentName) => {
    const pathname = window.location.pathname;
    if (pathname.includes("/templates/")) {
      return "../../components/" + componentName + ".html";
    } else {
      return "../components/" + componentName + ".html";
    }
  };

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

  const init = async () => {
    const headerPlaceholder = document.querySelector("y-navbar");
    const footerPlaceholder = document.querySelector("y-footer");
    if (headerPlaceholder) {
      const headerPath = getComponentPath("header");
      const footerPath = getComponentPath("footer");
      const headerHost = await loadFragment("y-navbar", headerPath);
      await loadFragment("y-footer", footerPath);
      if (headerHost) wireMobileMenu(headerHost);
    } else {
      wireMobileMenu(document);
    }
    wireActiveLinks(document);
  };

  const initFiltrationDropdowns = () => {
    const filterContainers = document.querySelectorAll(".filtration-button");
    if (!filterContainers || filterContainers.length === 0) return;

    filterContainers.forEach((container) => {
      const trigger = container.querySelector("button");
      if (!trigger) return;

      const menu = container.querySelector(".filtration-menu");

      const open = () => {
        container.classList.add("active");
        trigger.setAttribute("aria-expanded", "true");
      };

      const close = () => {
        container.classList.remove("active");
        trigger.setAttribute("aria-expanded", "false");
      };

      const onToggle = (e) => {
        e.preventDefault();
        e.stopPropagation();
        if (container.classList.contains("active")) {
          close();
        } else {
          open();
        }
      };

      if (!container.__yFilterBound) {
        trigger.addEventListener("click", onToggle);

        document.addEventListener("click", (evt) => {
          if (
            container.classList.contains("active") &&
            !container.contains(evt.target)
          ) {
            close();
          }
        });

        document.addEventListener("keydown", (evt) => {
          if (evt.key === "Escape") {
            close();
          }
        });
        container.__yFilterBound = true;
      }
    });
  };

  const runInit = async () => {
    await init();
    initFiltrationDropdowns();
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", runInit, { once: true });
  } else {
    runInit();
  }
})();
