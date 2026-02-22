// Dynamically load header and footer components
function loadComponent(url, position) {
    fetch(url)
        .then(response => response.text())
        .then(html => {
            const tempContainer = document.createElement('div');
            tempContainer.innerHTML = html;

            // Find the placeholder element
            let placeholder;
            if (position === 'top') {
                placeholder = document.getElementById('header-placeholder');
            } else if (position === 'bottom' && document.getElementById('footer-placeholder')) {
                placeholder = document.getElementById('footer-placeholder');
            } else if (position === 'bottom' && document.getElementById('auth-popup-placeholder')) {
                placeholder = document.getElementById('auth-popup-placeholder');
            }


            // Replace placeholder with component
            if (placeholder) {
                // Move all children from tempContainer to placeholder
                while (tempContainer.firstChild) {
                    placeholder.parentNode.insertBefore(tempContainer.firstChild, placeholder);
                }
                placeholder.parentNode.removeChild(placeholder); // Remove the placeholder itself
            } else {
                // Fallback for pages without a placeholder div
                if (position === 'top') {
                    document.body.prepend(tempContainer.firstChild);
                } else {
                    document.body.appendChild(tempContainer.firstChild);
                }
            }


            if (position === 'top') {
                // Set active navigation link after header is loaded
                setTimeout(setActiveNavLink, 100);
                // Add event listener for the mobile menu dropdown
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const mobileMenu = document.getElementById('header-mobile');
                const mobileMenuIcon = document.getElementById('mobile-menu-icon');
                const mobileMenuLinks = document.querySelectorAll('.y-l-header-mobile-links a');

                if (mobileMenuButton && mobileMenu && mobileMenuIcon) {
                    // Toggle dropdown on button click
                    mobileMenuButton.addEventListener('click', (e) => {
                        e.stopPropagation();
                        mobileMenu.classList.toggle('open');
                        // Toggle icon between bars and times (close)
                        if (mobileMenu.classList.contains('open')) {
                            mobileMenuIcon.classList.remove('fa-bars');
                            mobileMenuIcon.classList.add('fa-times');
                            mobileMenu.setAttribute('aria-hidden', 'false'); // <-- FIX: Make it visible to screen readers
                        } else {
                            mobileMenuIcon.classList.remove('fa-times');
                            mobileMenuIcon.classList.add('fa-bars');
                            mobileMenu.setAttribute('aria-hidden', 'true'); // <-- FIX: Hide it again
                        }
                    });

                    // Close dropdown when clicking outside
                    document.addEventListener('click', (e) => {
                        if (mobileMenu.classList.contains('open') &&
                            !mobileMenuButton.contains(e.target) &&
                            !mobileMenu.contains(e.target)) {
                            mobileMenu.classList.remove('open');
                            mobileMenuIcon.classList.remove('fa-times');
                            mobileMenuIcon.classList.add('fa-bars');
                            mobileMenu.setAttribute('aria-hidden', 'true'); // <-- FIX: Hide it when closed
                        }
                    });

                    // Close dropdown when clicking on menu links
                    mobileMenuLinks.forEach(link => {
                        link.addEventListener('click', () => {
                            mobileMenu.classList.remove('open');
                            mobileMenuIcon.classList.remove('fa-times');
                            mobileMenuIcon.classList.add('fa-bars');
                            mobileMenu.setAttribute('aria-hidden', 'true'); // <-- FIX: Hide it when closed
                        });
                    });

                    // Close dropdown on escape key
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && mobileMenu.classList.contains('open')) {
                            mobileMenu.classList.remove('open');
                            mobileMenuIcon.classList.remove('fa-times');
                            mobileMenuIcon.classList.add('fa-bars');
                            mobileMenu.setAttribute('aria-hidden', 'true'); // <-- FIX: Hide it when closed
                        }
                    });
                }

                // Setup header dropdowns after a short delay to ensure DOM is ready
                setTimeout(() => {
                    setupHeaderDropdowns();
                }, 100);

                // Setup search functionality
                setupSearch();
            }
        });
}

// set the active navigation link
function setActiveNavLink() {
    // Get the current page path
    const currentPath = window.location.pathname;

    // Find all navigation links in header (both desktop and mobile)
    // Updated selector to include new dropdown buttons
    const navLinks = document.querySelectorAll('.y-l-header-nav .y-c-nav-link, .y-l-header-mobile-links a, .y-l-header-mobile-links .y-c-mobile-dropdown-toggle');

    navLinks.forEach(link => {
        // Get href from 'a' tag or data-y attribute from button
        const linkPath = link.getAttribute('href') || (link.dataset.y === 'header-mobile-link-products' ? '../store/layout.html' : null);

        if (!linkPath) return; // Skip if it's a button without a clear path (like the main products dropdown)

        // Remove existing active class
        link.classList.remove('y-c-active-link');

        // Check if the link href matches the current path exactly
        if (linkPath === currentPath) {
            link.classList.add('y-c-active-link');
        } else if (currentPath.includes(linkPath) && linkPath !== "../home/layout.html" && linkPath !== "/") {
            // Handle partial matches (for nested pages like store/category)
            // Exclude home to prevent it from being active on all pages
            if (currentPath.startsWith(linkPath)) {
                link.classList.add('y-c-active-link');
            }
        }
    });

    // Special case for home page
    const homeLinks = document.querySelectorAll('.y-l-header-nav a[href="../home/layout.html"], .y-l-header-mobile-links a[href="../home/layout.html"]');
    if (currentPath === '../home/layout.html' || currentPath === '/') {
        homeLinks.forEach(link => link.classList.add('y-c-active-link'));
    } else {
        homeLinks.forEach(link => link.classList.remove('y-c-active-link'));
    }

    // If no link is active, check for store/category pages
    const activeLinks = document.querySelectorAll('.y-c-active-link');
    if (activeLinks.length === 0) {
        if (currentPath.includes('/store/') || currentPath.includes('/category/') || currentPath.includes('/offers/')) {
            // Highlight the main "Products" dropdown toggle
            const storeLinks = document.querySelectorAll('[data-y="nav-link-products"], [data-y="header-mobile-link-products"]');
            storeLinks.forEach(link => link.classList.add('y-c-active-link'));
        }
    }

    // Also highlight "Offers" link if on offers page
    if (currentPath.includes('/offers/')) {
        const offerLinks = document.querySelectorAll('[data-y="nav-link-offers"], [data-y="header-mobile-link-offers"]');
        offerLinks.forEach(link => link.classList.add('y-c-active-link'));
    }
}

// Setup header dropdowns (for Products)
function setupHeaderDropdowns() {
    // --- Desktop Dropdown ---
    const desktopDropdownToggle = document.querySelector('[data-y="nav-dropdown-products"] .y-c-nav-link');
    const desktopDropdownMenu = document.querySelector('[data-y="nav-dropdown-products"] .y-c-nav-dropdown-menu');

    if (desktopDropdownToggle && desktopDropdownMenu) {
        desktopDropdownToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            // Toggle this dropdown
            desktopDropdownMenu.classList.toggle('open');
            desktopDropdownToggle.classList.toggle('active');
            desktopDropdownToggle.setAttribute('aria-expanded', desktopDropdownMenu.classList.contains('open'));
        });
    }

    // --- Mobile Dropdown ---
    const mobileDropdownToggle = document.querySelector('[data-y="mobile-dropdown-products"] .y-c-mobile-dropdown-toggle');
    const mobileDropdownMenu = document.querySelector('[data-y="mobile-dropdown-products"] .y-c-mobile-dropdown-menu');

    if (mobileDropdownToggle && mobileDropdownMenu) {
        mobileDropdownToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            // Toggle this dropdown
            mobileDropdownMenu.classList.toggle('open');
            mobileDropdownToggle.classList.toggle('active');
            mobileDropdownToggle.setAttribute('aria-expanded', mobileDropdownMenu.classList.contains('open'));
        });
    }

    // Global click listener to close dropdowns
    document.addEventListener('click', (e) => {
        // Close desktop dropdown
        if (desktopDropdownMenu && desktopDropdownMenu.classList.contains('open') && !desktopDropdownToggle.contains(e.target) && !desktopDropdownMenu.contains(e.target)) {
            desktopDropdownMenu.classList.remove('open');
            desktopDropdownToggle.classList.remove('active');
            desktopDropdownToggle.setAttribute('aria-expanded', 'false');
        }

        // Mobile dropdown is inside the main mobile menu, so it's handled by the main mobile menu close logic.
        // But just in case, we can add this (though it might conflict if not careful)
        // No, the mobile dropdown is inside the mobile menu content, so it won't be closed by the main 'click outside' logic.
        // We need to handle it *if* the mobile menu is open.
        const mobileMenu = document.getElementById('header-mobile');
        if (mobileMenu && mobileMenu.classList.contains('open')) {
            if (mobileDropdownMenu && mobileDropdownMenu.classList.contains('open') && !mobileDropdownToggle.contains(e.target) && !mobileDropdownMenu.contains(e.target)) {
                mobileDropdownMenu.classList.remove('open');
                mobileDropdownToggle.classList.remove('active');
                mobileDropdownToggle.setAttribute('aria-expanded', 'false');
            }
        }
    });
}

// Setup search functionality
function setupSearch() {
    // Mobile search elements
    const mobileSearchIcon = document.getElementById('expandable-search-icon');
    const mobileSearchInput = document.getElementById('expandable-search-input');
    const mobileSearchFocusLink = document.getElementById('mobile-search-focus');

    // Desktop search elements
    const desktopSearchIcon = document.getElementById('expandable-search-icon-desktop');
    const desktopSearchInput = document.getElementById('expandable-search-input-desktop');

    // Mobile menu elements
    const mobileMenu = document.getElementById('header-mobile');
    const mobileMenuIcon = document.getElementById('mobile-menu-icon');

    // Function to open/focus mobile search
    const openMobileSearch = (e) => {
        e.preventDefault();
        e.stopPropagation();
        if (mobileSearchInput) {
            mobileSearchInput.focus();
        }
        // If mobile menu is open, close it
        if (mobileMenu && mobileMenu.classList.contains('open')) {
            mobileMenu.classList.remove('open');
            if (mobileMenuIcon) {
                mobileMenuIcon.classList.remove('fa-times');
                mobileMenuIcon.classList.add('fa-bars');
            }
        }
    };

    // Function to open/focus desktop search
    const openDesktopSearch = (e) => {
        e.preventDefault();
        e.stopPropagation();
        if (desktopSearchInput) {
            desktopSearchInput.focus();
        }
    };

    // --- Event Listeners ---

    // Mobile: Link in dropdown focuses mobile search input
    if (mobileSearchFocusLink) {
        mobileSearchFocusLink.addEventListener('click', openMobileSearch);
    }

    // Mobile: Clicking icon (if it becomes clickable) focuses mobile search
    if (mobileSearchIcon) {
        // Note: In the new CSS, the mobile icon is decorative (pointer-events: none)
        // But we'll wire it just in case CSS changes.
        mobileSearchIcon.addEventListener('click', openMobileSearch);
    }

    // Desktop: Clicking icon focuses desktop search input
    if (desktopSearchIcon) {
        desktopSearchIcon.addEventListener('click', openDesktopSearch);
    }

    // Close desktop search when clicking outside
    document.addEventListener('click', (e) => {
        if (desktopSearchInput && document.activeElement === desktopSearchInput &&
            desktopSearchIcon && !desktopSearchIcon.contains(e.target) &&
            !desktopSearchInput.contains(e.target)) {
            desktopSearchInput.blur();
        }

        // We don't need to blur mobile search on click-outside, 
        // as it's not an expandable overlay.
    });
}

document.addEventListener('DOMContentLoaded', function () {
    // Use absolute paths for components
    if (document.getElementById('header-placeholder')) {
        loadComponent('../../components/y-header.html', 'top');
    }
    if (document.getElementById('footer-placeholder')) {
        loadComponent('../../components/y-footer.html', 'bottom');
    }
    if (document.getElementById('auth-popup-placeholder')) {
        loadComponent('../../components/y-auth-popup.html', 'bottom');
    }
});