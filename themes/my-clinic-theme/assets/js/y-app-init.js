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

  const wireActiveLinks = (root = document) => {
    const getCurrentMatchPath = () => {
      let path = window.location.pathname;
      if (!path.endsWith("/")) path += "/";
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
        const path = url.pathname;
        if (path === "/" || path.endsWith("/")) return "/";
        return path.toLowerCase();
      } catch (_) {
        return null;
      }
    };

    const anchors = root.querySelectorAll("header a, .mobile-menu a");
    anchors.forEach((a) => a.classList.remove("active"));

    const currentPath = getCurrentMatchPath();
    anchors.forEach((a) => {
      const linkPath = getLinkEnd(a);
      if (linkPath && currentPath === linkPath) {
        a.classList.add("active");
      }
    });
  };

  const init = () => {
    // Wire mobile menu - works with WordPress header.php
    wireMobileMenu(document);
    wireActiveLinks(document);
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init, { once: true });
  } else {
    init();
  }

  // Categories scroll functionality
  document.addEventListener("DOMContentLoaded", () => {
    const boxGrid = document.querySelector(".categories-section .box-grid");
    if (boxGrid) {
      const categoriesGrid = boxGrid.querySelector(".categories-grid");
      const scrollBtns = boxGrid.querySelectorAll(".btn");
      const scrollLeftBtn = scrollBtns[0];
      const scrollRightBtn = scrollBtns[1];

      if (categoriesGrid && scrollLeftBtn && scrollRightBtn) {
        const scrollAmount = 300;
        const isRTL = window.getComputedStyle(categoriesGrid).direction === "rtl";

        scrollRightBtn.addEventListener("click", (e) => {
          e.preventDefault();
          if (isRTL) {
            categoriesGrid.scrollBy({ left: -scrollAmount, behavior: "smooth" });
          } else {
            categoriesGrid.scrollBy({ left: scrollAmount, behavior: "smooth" });
          }
        });

        scrollLeftBtn.addEventListener("click", (e) => {
          e.preventDefault();
          if (isRTL) {
            categoriesGrid.scrollBy({ left: scrollAmount, behavior: "smooth" });
          } else {
            categoriesGrid.scrollBy({ left: -scrollAmount, behavior: "smooth" });
          }
        });
      }
    }

    // Generic scroll function for doctors/clinics sections
    const initScrollSection = (sectionSelector, leftBtnSelector, rightBtnSelector) => {
      const section = document.querySelector(sectionSelector);
      if (!section) return;

      const grid = section.querySelector(".doctors-grid");
      const leftBtn = section.querySelector(leftBtnSelector);
      const rightBtn = section.querySelector(rightBtnSelector);

      if (grid && leftBtn && rightBtn) {
        const scrollAmount = 300;
        const isRTL = window.getComputedStyle(grid).direction === "rtl";
        const isMobile = window.innerWidth <= 768;

        // Function to center the most visible card in mobile view
        const centerVisibleCard = () => {
          if (!isMobile) return;
          
          const cards = Array.from(grid.querySelectorAll(".doctor-info"));
          if (cards.length === 0) return;

          const gridRect = grid.getBoundingClientRect();
          const gridCenter = gridRect.left + gridRect.width / 2;

          let closestCard = null;
          let closestDistance = Infinity;

          cards.forEach((card) => {
            const cardRect = card.getBoundingClientRect();
            const cardCenter = cardRect.left + cardRect.width / 2;
            const distance = Math.abs(cardCenter - gridCenter);

            if (cardRect.right > gridRect.left && cardRect.left < gridRect.right) {
              if (distance < closestDistance) {
                closestDistance = distance;
                closestCard = card;
              }
            }
          });

          if (closestCard) {
            closestCard.scrollIntoView({
              behavior: "smooth",
              block: "nearest",
              inline: "center"
            });
          }
        };

        rightBtn.addEventListener("click", (e) => {
          e.preventDefault();
          e.stopPropagation();
          if (isRTL) {
            grid.scrollBy({ left: -scrollAmount, behavior: "smooth" });
          } else {
            grid.scrollBy({ left: scrollAmount, behavior: "smooth" });
          }
          if (isMobile) {
            setTimeout(centerVisibleCard, 350);
          }
        });

        leftBtn.addEventListener("click", (e) => {
          e.preventDefault();
          e.stopPropagation();
          if (isRTL) {
            grid.scrollBy({ left: scrollAmount, behavior: "smooth" });
          } else {
            grid.scrollBy({ left: -scrollAmount, behavior: "smooth" });
          }
          if (isMobile) {
            setTimeout(centerVisibleCard, 350);
          }
        });
      }
    };

    // Doctors section scroll functionality
    initScrollSection(
      ".doctors-section:not(.opinions-section) .box-grid",
      ".doctors-scroll-left, .btn:nth-child(1)",
      ".doctors-scroll-right, .btn:nth-child(3)"
    );

    // Clinics section scroll functionality
    initScrollSection(
      ".doctors-section .box-grid",
      ".clinics-scroll-left, .btn:nth-child(1)",
      ".clinics-scroll-right, .btn:nth-child(3)"
    );

    // Opinions section scroll functionality
    const opinionsBoxGrid = document.querySelector(".opinions-section .box-grid");
    if (opinionsBoxGrid) {
      const opinionsGrid = opinionsBoxGrid.querySelector(".doctors-grid");
      const opinionsScrollBtns = opinionsBoxGrid.querySelectorAll(".btn");
      const opinionsScrollLeftBtn = opinionsScrollBtns[0];
      const opinionsScrollRightBtn = opinionsScrollBtns[1];

      if (opinionsGrid && opinionsScrollLeftBtn && opinionsScrollRightBtn) {
        const scrollAmount = 300;
        const isRTL = window.getComputedStyle(opinionsGrid).direction === "rtl";

        opinionsScrollRightBtn.addEventListener("click", (e) => {
          e.preventDefault();
          if (isRTL) {
            opinionsGrid.scrollBy({ left: -scrollAmount, behavior: "smooth" });
          } else {
            opinionsGrid.scrollBy({ left: scrollAmount, behavior: "smooth" });
          }
        });

        opinionsScrollLeftBtn.addEventListener("click", (e) => {
          e.preventDefault();
          if (isRTL) {
            opinionsGrid.scrollBy({ left: scrollAmount, behavior: "smooth" });
          } else {
            opinionsGrid.scrollBy({ left: -scrollAmount, behavior: "smooth" });
          }
        });
      }
    }

    // Homepage search functionality
    const homepageSearchBtn = document.getElementById('search-btn');
    const homepageSearchInput = document.getElementById('doctor-search');
    const currentPath = window.location.pathname;
    const isHomepage = currentPath === '/' || currentPath === '' || currentPath === '/index.php';
    
    if (isHomepage) {
      // Handle specialty filter buttons on homepage
      const homepageSpecialtyButtons = document.querySelectorAll('.lists .specialty-filter');
      if (homepageSpecialtyButtons.length > 0) {
        homepageSpecialtyButtons.forEach(button => {
          button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const specialty = this.getAttribute('data-specialty') || '';
            
            // Update active state
            homepageSpecialtyButtons.forEach(btn => btn.classList.remove('active'));
            if (specialty) {
              this.classList.add('active');
            }
            
            // Close dropdown
            const checkbox = document.getElementById('list1');
            if (checkbox) {
              checkbox.checked = false;
            }
            
            // Navigate to doctors page with selected specialty
            const doctorsUrl = new URL('/doctors/', window.location.origin);
            if (specialty) {
              doctorsUrl.searchParams.set('specialty', specialty);
            }
            
            // Also include search term if exists
            if (homepageSearchInput && homepageSearchInput.value.trim()) {
              doctorsUrl.searchParams.set('search', homepageSearchInput.value.trim());
            }
            
            // Navigate to doctors page
            window.location.href = doctorsUrl.toString();
          });
        });
      }
      
      // Handle search button click on homepage
      if (homepageSearchBtn && homepageSearchInput) {
        homepageSearchBtn.addEventListener('click', function(e) {
          e.preventDefault();
          const searchTerm = homepageSearchInput.value.trim();
          const specialtyButtons = document.querySelectorAll('.lists .specialty-filter.active');
          let selectedSpecialty = '';
          
          if (specialtyButtons.length > 0) {
            selectedSpecialty = specialtyButtons[0].getAttribute('data-specialty') || '';
          }
          
          // Build URL with search parameters
          const doctorsUrl = new URL('/doctors/', window.location.origin);
          if (searchTerm) {
            doctorsUrl.searchParams.set('search', searchTerm);
          }
          if (selectedSpecialty) {
            doctorsUrl.searchParams.set('specialty', selectedSpecialty);
          }
          
          // Navigate to doctors page
          window.location.href = doctorsUrl.toString();
        });
        
        // Handle Enter key in search input
        homepageSearchInput.addEventListener('keypress', function(e) {
          if (e.key === 'Enter') {
            e.preventDefault();
            homepageSearchBtn.click();
          }
        });
      }
    }
  });
})();
