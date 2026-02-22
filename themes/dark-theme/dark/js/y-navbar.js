window.darkThemeAssetUrl = window.darkThemeAssetUrl || function (path) {
  const base = window.DarkTheme && window.DarkTheme.baseUrl ? window.DarkTheme.baseUrl.replace(/\/$/, "") : "";
  return base ? `${base}/${path}` : path;
};

document.addEventListener("DOMContentLoaded", () => {
  const navRoot = document.querySelector('[data-y="nav"]');
  if (!navRoot) return;

  if (navRoot.children.length) {
    initMobileMenu();
    setActiveLinks();
    return;
  }

  fetch(window.darkThemeAssetUrl("components/layout/y-c-navbar.html"))
    .then((response) => response.text())
    .then((data) => {
      navRoot.innerHTML = data;
      initMobileMenu();
      setActiveLinks();
    });
});

function initMobileMenu() {
  const hamburgerBtn = document.querySelector(".hamburger-menu");
  const closeBtn = document.querySelector(".mobile-menu-close");
  const mobileMenu = document.querySelector(".mobile-menu");
  const overlay = document.querySelector(".mobile-menu-overlay");
  const dropdownToggle = document.querySelector(".mobile-dropdown-toggle");
  const mobileDropdown = document.querySelector(".mobile-dropdown");

  function openMenu() {
    mobileMenu.classList.add("active");
    overlay.classList.add("active");
    document.body.style.overflow = "hidden";
  }

  function closeMenu() {
    mobileMenu.classList.remove("active");
    overlay.classList.remove("active");
    document.body.style.overflow = "";
  }

  if (hamburgerBtn) {
    hamburgerBtn.addEventListener("click", openMenu);
  }

  if (closeBtn) {
    closeBtn.addEventListener("click", closeMenu);
  }

  if (overlay) {
    overlay.addEventListener("click", closeMenu);
  }
  if (dropdownToggle && mobileDropdown) {
    dropdownToggle.addEventListener("click", (e) => {
      e.preventDefault();
      mobileDropdown.classList.toggle("active");
    });
  }
}

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
