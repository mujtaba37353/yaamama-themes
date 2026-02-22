
// Initialize Mobile Menu
function initMobileMenu() {
  const hamburgerBtn = document.querySelector(".hamburger-menu");
  const closeBtn = document.querySelector(".mobile-menu-close");
  const mobileMenu = document.querySelector(".mobile-menu");
  const overlay = document.querySelector(".mobile-menu-overlay");
  const dropdownToggle = document.querySelector(".mobile-dropdown-toggle");
  const mobileDropdown = document.querySelector(".mobile-dropdown");


  function openMenu() {
    if (mobileMenu) {
      mobileMenu.classList.add("active");
    }
    if (overlay) {
      overlay.classList.add("active");
    }
    document.body.style.overflow = "hidden";
  }

  function closeMenu() {
    if (mobileMenu) {
      mobileMenu.classList.remove("active");
    }
    if (overlay) {
      overlay.classList.remove("active");
    }
    document.body.style.overflow = "";
  }
  

  if (hamburgerBtn) {
    hamburgerBtn.addEventListener("click", function(e) {
      e.preventDefault();
      e.stopPropagation();
      openMenu();
    });
  }

  if (closeBtn) {
    closeBtn.addEventListener("click", function(e) {
      e.preventDefault();
      e.stopPropagation();
      closeMenu();
    });
  }

  if (overlay) {
    overlay.addEventListener("click", function(e) {
      e.preventDefault();
      e.stopPropagation();
      closeMenu();
    });
  }

  if (dropdownToggle && mobileDropdown) {
    dropdownToggle.addEventListener("click", function(e) {
      e.preventDefault();
      e.stopPropagation();
      mobileDropdown.classList.toggle("active");
    });
  }
}

// Set Active Links
function setActiveLinks() {
  const currentPage = document.body.getAttribute("data-current-page");
  if (!currentPage) return;

  const desktopLink = document.querySelector(
    `.links a[data-page="${currentPage}"]`
  );
  if (desktopLink) {
    desktopLink.setAttribute("data-active", "true");
    desktopLink.style.color = "#be8647";
  }

  const mobileLink = document.querySelector(
    `.mobile-menu-links a[data-page="${currentPage}"]`
  );
  if (mobileLink) {
    mobileLink.classList.add("active");
  }
}

// Prevent swipe gestures ONLY on checkout page - don't interfere with menu
function preventGlobalSwipe() {
  // Only apply on checkout page
  if (!document.body.classList.contains('woocommerce-checkout')) {
    return;
  }
  
  let touchStartX = 0;
  let touchStartY = 0;
  let isHorizontalSwipe = false;
  
  document.addEventListener('touchstart', function(e) {
    // Don't interfere with buttons, links, inputs, or mobile menu
    if (e.target.closest('button') || 
        e.target.closest('a') || 
        e.target.closest('input') || 
        e.target.closest('select') || 
        e.target.closest('textarea') ||
        e.target.closest('.mobile-menu') ||
        e.target.closest('.mobile-menu-overlay')) {
      return;
    }
    touchStartX = e.touches[0].clientX;
    touchStartY = e.touches[0].clientY;
    isHorizontalSwipe = false;
  }, { passive: true });
  
  document.addEventListener('touchmove', function(e) {
    // Don't interfere with buttons, links, inputs, or mobile menu
    if (e.target.closest('button') || 
        e.target.closest('a') || 
        e.target.closest('input') || 
        e.target.closest('select') || 
        e.target.closest('textarea') ||
        e.target.closest('.mobile-menu') ||
        e.target.closest('.mobile-menu-overlay')) {
      return;
    }
    
    if (touchStartX === 0) return;
    
    const currentX = e.touches[0].clientX;
    const currentY = e.touches[0].clientY;
    const deltaX = Math.abs(currentX - touchStartX);
    const deltaY = Math.abs(currentY - touchStartY);
    
    // Only prevent horizontal swipe on checkout page
    if (deltaX > deltaY && deltaX > 20) {
      isHorizontalSwipe = true;
      e.preventDefault();
      e.stopPropagation();
    }
  }, { passive: false });
  
  document.addEventListener('touchend', function(e) {
    // Don't interfere with buttons, links, inputs, or mobile menu
    if (e.target.closest('button') || 
        e.target.closest('a') || 
        e.target.closest('input') || 
        e.target.closest('select') || 
        e.target.closest('textarea') ||
        e.target.closest('.mobile-menu') ||
        e.target.closest('.mobile-menu-overlay')) {
      touchStartX = 0;
      touchStartY = 0;
      return;
    }
    
    if (isHorizontalSwipe) {
      e.preventDefault();
      e.stopPropagation();
    }
    touchStartX = 0;
    touchStartY = 0;
    isHorizontalSwipe = false;
  }, { passive: false });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', function() {
    initMobileMenu();
    setActiveLinks();
    // Only prevent swipe on checkout page
    if (document.body.classList.contains('woocommerce-checkout')) {
      preventGlobalSwipe();
    }
  });
} else {
  // DOM already loaded
  initMobileMenu();
  setActiveLinks();
  // Only prevent swipe on checkout page
  if (document.body.classList.contains('woocommerce-checkout')) {
    preventGlobalSwipe();
  }
}
