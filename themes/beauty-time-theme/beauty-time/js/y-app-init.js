(() => {
  const closeAllCustomMoreSelections = () => {};

  const wireMobileMenu = (root = document) => {
    const mobileMenuBtn = root.querySelector(".mobile-menu-btn");
    const mobileMenuOverlay = root.querySelector(".mobile-menu-overlay");
    if (!mobileMenuBtn || !mobileMenuOverlay) return;

    const openMobileMenu = () => {
      mobileMenuBtn.classList.add("active");
      mobileMenuOverlay.classList.add("active");
      document.body.style.overflow = "hidden";
    };

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
    const getCurrentSlug = () => {
      const path = (window.location.pathname || "/").replace(/\/+$/, "") || "/";
      const parts = path.split("/").filter(Boolean);
      return parts.length ? parts[parts.length - 1].toLowerCase() : "front";
    };

    const getLinkSlug = (anchor) => {
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
        const path = (url.pathname || "/").replace(/\/+$/, "") || "/";
        const parts = path.split("/").filter(Boolean);
        return parts.length ? parts[parts.length - 1].toLowerCase() : "front";
      } catch (_) {
        return null;
      }
    };

    const anchors = root.querySelectorAll(
      ".desktop-menu a, .mobile-menu a, .custom-more-selection a"
    );
    anchors.forEach((a) => a.classList.remove("active"));

    const current = getCurrentSlug();
    const exactMatches = [];
    anchors.forEach((a) => {
      const slug = getLinkSlug(a);
      if (slug && current === slug) exactMatches.push(a);
    });

    if (current === "my-account" || current === "login") {
      anchors.forEach((a) => {
        if (getLinkSlug(a) === "my-account" || getLinkSlug(a) === "login")
          exactMatches.push(a);
      });
    }

    const toActivate =
      exactMatches.length > 0
        ? exactMatches
        : Array.from(anchors).filter((a) => getLinkSlug(a) === "front");
    toActivate.forEach((a) => a.classList.add("active"));
  };

  const wireProfileTabs = (root = document) => {
    const tabButtons = root.querySelectorAll(".tabs button[data-tab]");
    const tabContents = root.querySelectorAll(".tab-content[data-content]");

    if (tabButtons.length === 0 || tabContents.length === 0) return;

    tabButtons.forEach((button) => {
      button.addEventListener("click", (e) => {
        e.preventDefault();

        const targetTab = button.getAttribute("data-tab");

        tabButtons.forEach((btn) => btn.classList.remove("active"));

        tabContents.forEach((content) => content.classList.remove("active"));

        button.classList.add("active");

        const targetContent = root.querySelector(
          `.tab-content[data-content="${targetTab}"]`
        );
        if (targetContent) {
          targetContent.classList.add("active");
        }
      });
    });
  };

  const wireTabNext = (root = document) => {
    const nextBtns = root.querySelectorAll(".tab-next[data-next]");
    nextBtns.forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        const next = btn.getAttribute("data-next");
        if (!next) return;
        const tabBtn = root.querySelector(`.tabs button[data-tab="${next}"]`);
        if (tabBtn) tabBtn.click();
      });
    });
  };

  const wireSlider = (root = document) => {
    const slider = root.querySelector(".hero-section .slider");
    const dots = root.querySelectorAll(".hero-section .dots .dot");
    const slides = root.querySelectorAll(".hero-section .slider .content");

    if (!slider || !dots.length || !slides.length) return;

    let currentIndex = 0;

    slider.style.transition = "transform 0.5s ease-in-out";

    const goToSlide = (index) => {
      if (index < 0 || index >= slides.length) return;

      const slide = slides[index];
      slider.style.transform = `translateX(${slide.offsetLeft}px)`;

      dots.forEach((dot) => dot.classList.remove("active"));
      dots[index].classList.add("active");

      currentIndex = index;
    };

    dots.forEach((dot, index) => {
      dot.addEventListener("click", () => {
        goToSlide(index);
      });
    });

    window.addEventListener("resize", () => {
      goToSlide(currentIndex);
    });
  };

  const wireServiceList = (root = document) => {
    const serviceItems = root.querySelectorAll(".custome-list .item");
    const selectedServiceText = root.querySelector(
      ".custome-list .selected-service-text"
    );
    const serviceListToggle = document.getElementById("service-list-toggle");

    if (!serviceItems.length) return;

    serviceItems.forEach((item) => {
      item.addEventListener("click", (e) => {
        e.preventDefault();

        serviceItems.forEach((i) => i.classList.remove("selected"));

        item.classList.add("selected");

        const serviceName = item.getAttribute("data-name");
        if (selectedServiceText) {
          selectedServiceText.textContent = serviceName;
        }

        if (serviceListToggle) {
          serviceListToggle.checked = false;
        }
      });
    });

    document.addEventListener("click", (e) => {
      const customeList = root.querySelector(".custome-list");
      if (customeList && !customeList.contains(e.target) && serviceListToggle) {
        serviceListToggle.checked = false;
      }
    });
  };

  const wireDisableAccountPopup = (root = document) => {
    const disableAccountBtn = root.getElementById("disable-account-btn");
    const disableAccountPopup = root.getElementById("disable-account-popup");
    const closePopupBtn = root.getElementById("close-disable-popup");
    const cancelBtn = root.getElementById("cancel-disable-btn");
    const confirmBtn = root.getElementById("confirm-disable-btn");
    const popupOverlay = disableAccountPopup?.querySelector(".popup-overlay");

    if (!disableAccountBtn || !disableAccountPopup) return;

    const openPopup = () => {
      disableAccountPopup.classList.add("active");
      document.body.style.overflow = "hidden";
    };

    const closePopup = () => {
      disableAccountPopup.classList.remove("active");
      document.body.style.overflow = "";
    };

    disableAccountBtn.addEventListener("click", (e) => {
      e.preventDefault();
      openPopup();
    });

    if (closePopupBtn) {
      closePopupBtn.addEventListener("click", closePopup);
    }

    if (cancelBtn) {
      cancelBtn.addEventListener("click", closePopup);
    }

    if (popupOverlay) {
      popupOverlay.addEventListener("click", (e) => {
        if (e.target === popupOverlay) {
          closePopup();
        }
      });
    }

    if (confirmBtn) {
      confirmBtn.addEventListener("click", () => {
        console.log("Account disabled");
        closePopup();
      });
    }

    document.addEventListener("keydown", (event) => {
      if (
        event.key === "Escape" &&
        disableAccountPopup.classList.contains("active")
      ) {
        closePopup();
      }
    });
  };

  const init = () => {
    wireMobileMenu(document);
    wireActiveLinks(document);
    wireProfileTabs(document);
    wireTabNext(document);
    wireSlider(document);
    wireServiceList(document);
    wireDisableAccountPopup(document);
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init, { once: true });
  } else {
    init();
  }
})();
