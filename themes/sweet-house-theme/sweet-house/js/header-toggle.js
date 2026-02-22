// Mobile header/menu interactions
const wireMobileMenu = (root = document) => {
  const mobileMenuBtn = root.querySelector(".mobile-menu-btn");
  const mobileMenuOverlay = root.querySelector(".mobile-menu-overlay");
  const customMoreSelectionBtns = root.querySelectorAll(
    ".custom-more-selection-btn"
  );

  if (!mobileMenuBtn || !mobileMenuOverlay) return;

  const openMobileMenu = () => {
    mobileMenuBtn.classList.add("active");
    mobileMenuOverlay.classList.add("active");
    document.body.style.overflow = "hidden";
  };

  const getBtnMenu = (btn) =>
    btn ? btn.querySelector(".custom-more-selection") : null;

  const closeAllCustomMoreSelections = () => {
    root.querySelectorAll(".custom-more-selection.active").forEach((menu) => {
      menu.classList.remove("active");
    });
  };

  const toggleCustomMoreSelectionForBtn = (btn, event) => {
    if (!btn) return;
    const menu = getBtnMenu(btn);
    if (!menu) return;

    if (
      event &&
      event.target &&
      event.target.closest &&
      event.target.closest("a")
    ) {
      closeAllCustomMoreSelections();
      return;
    }

    const isActive = menu.classList.contains("active");
    closeAllCustomMoreSelections();
    if (!isActive) menu.classList.add("active");
  };

  customMoreSelectionBtns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      if (!e.target.closest("a")) {
        e.preventDefault();
      }
      toggleCustomMoreSelectionForBtn(btn, e);
    });
  });

  const dropdownLinks = root.querySelectorAll(".custom-more-selection a");
  dropdownLinks.forEach((link) => {
    link.addEventListener("click", () => {
      closeAllCustomMoreSelections();
    });
  });

  document.addEventListener("click", (e) => {
    const clickedInsideAnyBtn =
      e.target &&
      e.target.closest &&
      e.target.closest(".custom-more-selection-btn");
    if (!clickedInsideAnyBtn) {
      closeAllCustomMoreSelections();
    }
  });

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

  const mobileMenuLinks = mobileMenuOverlay.querySelectorAll(".mobile-menu a");
  mobileMenuLinks.forEach((link) => {
    link.addEventListener("click", () => {
      closeMobileMenu();
    });
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && mobileMenuOverlay.classList.contains("active")) {
      closeMobileMenu();
    }
  });
};

// Expose for other scripts (e.g., navbar loader)
window.wireMobileMenu = wireMobileMenu;

