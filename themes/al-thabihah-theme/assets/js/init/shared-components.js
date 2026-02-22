function setActiveNavLink() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.y-l-header-nav .y-c-nav-link, .y-l-header-mobile-links a');

    navLinks.forEach(link => {
        link.classList.remove('y-c-active-link');
        const linkPath = link.getAttribute('href');
        if (!linkPath) {
            return;
        }
        const linkUrl = new URL(linkPath, window.location.origin);
        if (linkUrl.pathname === currentPath) {
            link.classList.add('y-c-active-link');
        }
    });

    if (currentPath === '/' || currentPath === '') {
        const homeLinks = document.querySelectorAll('.y-l-header-nav a[href="/"], .y-l-header-mobile-links a[href="/"]');
        homeLinks.forEach(link => link.classList.add('y-c-active-link'));
    }
}

function setupMobileMenu() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('header-mobile');
    const mobileMenuIcon = document.getElementById('mobile-menu-icon');
    const mobileMenuLinks = document.querySelectorAll('.y-l-header-mobile-links a');
    const mobileMenuClose = document.getElementById('mobile-menu-close');

    if (!mobileMenuButton || !mobileMenu || !mobileMenuIcon) {
        return;
    }

    const closeMenu = () => {
        mobileMenu.classList.remove('open');
        mobileMenuIcon.classList.remove('fa-times');
        mobileMenuIcon.classList.add('fa-bars');
        mobileMenu.setAttribute('aria-hidden', 'true');
    };

    const openMenu = () => {
        mobileMenu.classList.add('open');
        mobileMenuIcon.classList.remove('fa-bars');
        mobileMenuIcon.classList.add('fa-times');
        mobileMenu.setAttribute('aria-hidden', 'false');
    };

    mobileMenuButton.addEventListener('click', (e) => {
        e.stopPropagation();
        if (mobileMenu.classList.contains('open')) {
            closeMenu();
            return;
        }
        openMenu();
    });

    if (mobileMenuClose) {
        mobileMenuClose.addEventListener('click', (e) => {
            e.stopPropagation();
            closeMenu();
        });
    }

    document.addEventListener('click', (e) => {
        if (mobileMenu.classList.contains('open') &&
            !mobileMenuButton.contains(e.target) &&
            !mobileMenu.contains(e.target)) {
            closeMenu();
        }
    });

    mobileMenuLinks.forEach(link => {
        link.addEventListener('click', closeMenu);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && mobileMenu.classList.contains('open')) {
            closeMenu();
        }
    });
}

function setupHeaderDropdowns() {
    const desktopDropdownToggle = document.querySelector('[data-y="nav-dropdown-products"] .y-c-nav-link');
    const desktopDropdownMenu = document.querySelector('[data-y="nav-dropdown-products"] .y-c-nav-dropdown-menu');

    if (desktopDropdownToggle && desktopDropdownMenu) {
        desktopDropdownToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            desktopDropdownMenu.classList.toggle('open');
            desktopDropdownToggle.classList.toggle('active');
            desktopDropdownToggle.setAttribute('aria-expanded', desktopDropdownMenu.classList.contains('open'));
        });
    }

    document.addEventListener('click', (e) => {
        if (desktopDropdownMenu && desktopDropdownMenu.classList.contains('open') && !desktopDropdownToggle.contains(e.target) && !desktopDropdownMenu.contains(e.target)) {
            desktopDropdownMenu.classList.remove('open');
            desktopDropdownToggle.classList.remove('active');
            desktopDropdownToggle.setAttribute('aria-expanded', 'false');
        }
    });
}

function setupSearch() {
    const mobileSearchInput = document.getElementById('mobile-search-input');
    const mobileSearchButton = document.querySelector('.y-c-mobile-search .y-c-search-btn');
    const desktopSearchInput = document.getElementById('expandable-search-input-desktop');
    const desktopSearchIcon = document.getElementById('expandable-search-icon-desktop');

    const submitSearch = (query) => {
        if (!query) {
            return;
        }
        const url = new URL(window.location.origin);
        url.searchParams.set('s', query);
        window.location.href = url.toString();
    };

    if (desktopSearchIcon && desktopSearchInput) {
        desktopSearchIcon.addEventListener('click', (e) => {
            e.preventDefault();
            submitSearch(desktopSearchInput.value.trim());
        });
        desktopSearchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitSearch(desktopSearchInput.value.trim());
            }
        });
    }

    if (mobileSearchButton && mobileSearchInput) {
        mobileSearchButton.addEventListener('click', (e) => {
            e.preventDefault();
            submitSearch(mobileSearchInput.value.trim());
        });
        mobileSearchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitSearch(mobileSearchInput.value.trim());
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    setActiveNavLink();
    setupMobileMenu();
    setupHeaderDropdowns();
    setupSearch();
});
