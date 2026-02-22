// Dynamically load header and footer components
function loadComponent(url, position) {
    fetch(url)
        .then(response => response.text())
        .then(html => {
            if (position === 'top') {
                // For header, directly insert the content (not wrapped in a div)
                const tempContainer = document.createElement('div');
                tempContainer.innerHTML = html;
                const headerContent = tempContainer.firstChild;
                document.body.insertBefore(headerContent, document.body.firstChild);

                // Set active navigation link after header is loaded
                setTimeout(setActiveNavLink, 100);
            } else if (position === 'bottom') {
                // For footer, same approach
                const tempContainer = document.createElement('div');
                tempContainer.innerHTML = html;
                const footerContent = tempContainer.firstChild;
                document.body.appendChild(footerContent);
            }
        });
}

// set the active navigation link
function setActiveNavLink() {
    // Get the current page path
    const currentPath = window.location.pathname;

    // Find all navigation links in header (both desktop and mobile)
    const navLinks = document.querySelectorAll('.y-l-header-links a, .y-l-header-mobile-links a');

    navLinks.forEach(link => {
        // Check if the link href matches the current path
        if (link.getAttribute('href') === currentPath) {
            // Add active class
            link.classList.add('y-c-active-link');
        } else if (currentPath.includes(link.getAttribute('href')) && link.getAttribute('href') !== '/') {
            // Handle partial matches (for nested pages)
            link.classList.add('y-c-active-link');
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    loadComponent('/components/y-header.html', 'top');
    loadComponent('/components/y-footer.html', 'bottom');

    // Initialize Mega Menu functionality
    setTimeout(() => {
        initializeMegaMenu();
        initializeMobileMenuArrows();
        initializeSearchBar();
    }, 300);
    
    // Also initialize search bar immediately (for WordPress)
    initializeSearchBar();
});

// Mega Menu functionality
function initializeMegaMenu() {
    const menuToggle = document.getElementById('menu-toggle');
    const megaMenu = document.getElementById('mega-menu');
    const menuBackdrop = document.getElementById('mega-menu-backdrop');
    const mobileMenuClose = document.getElementById('mobile-menu-close');

    if (menuToggle && megaMenu) {
        // Toggle menu on click
        menuToggle.addEventListener('click', function (e) {
            e.preventDefault();
            megaMenu.classList.toggle('y-is-active');
            menuToggle.classList.toggle('y-is-active');

            // Toggle backdrop for mobile
            if (menuBackdrop) {
                menuBackdrop.classList.toggle('y-is-active');
            }
        });

        // Close menu with close button
        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', function () {
                megaMenu.classList.remove('y-is-active');
                menuToggle.classList.remove('y-is-active');

                if (menuBackdrop) {
                    menuBackdrop.classList.remove('y-is-active');
                }
            });
        }

        // Close menu when clicking outside or on backdrop
        document.addEventListener('click', function (e) {
            if (!megaMenu.contains(e.target) && !menuToggle.contains(e.target)) {
                megaMenu.classList.remove('y-is-active');
                menuToggle.classList.remove('y-is-active');

                if (menuBackdrop) {
                    menuBackdrop.classList.remove('y-is-active');
                }
            }
        });

        // Close menu when clicking on backdrop
        if (menuBackdrop) {
            menuBackdrop.addEventListener('click', function () {
                megaMenu.classList.remove('y-is-active');
                menuToggle.classList.remove('y-is-active');
                menuBackdrop.classList.remove('y-is-active');
            });
        }

        // Close menu on escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && megaMenu.classList.contains('y-is-active')) {
                megaMenu.classList.remove('y-is-active');
                menuToggle.classList.remove('y-is-active');

                if (menuBackdrop) {
                    menuBackdrop.classList.remove('y-is-active');
                }
            }
        });
    }
}

// Initialize mobile menu arrows functionality
function initializeMobileMenuArrows() {
    // Only initialize if on mobile screen
    if (window.innerWidth <= 768) {
        const menuTitles = document.querySelectorAll('.y-c-mega-menu-title');

        menuTitles.forEach(title => {
            // Skip the main navigation pages
            if (title.closest('.y-c-mega-menu-pages')) {
                return;
            }

            const arrow = title.querySelector('.y-c-mobile-menu-arrow');
            const menuList = title.nextElementSibling;

            if (arrow && menuList && menuList.classList.contains('y-c-mega-menu-list')) {
                // Add click handler
                title.addEventListener('click', function () {
                    this.classList.toggle('y-is-expanded');

                    // Update ARIA attributes for accessibility
                    const isExpanded = this.classList.contains('y-is-expanded');
                    this.setAttribute('aria-expanded', isExpanded);
                });

                // Add keyboard support
                title.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.click();
                    }
                });

                // Make title focusable and add accessibility attributes
                title.setAttribute('tabindex', '0');
                title.setAttribute('role', 'button');
                title.setAttribute('aria-expanded', 'false');
                title.setAttribute('aria-controls', 'menu-list-' + Array.from(menuTitles).indexOf(title));

                // Add ID to menu list for ARIA relationship
                menuList.setAttribute('id', 'menu-list-' + Array.from(menuTitles).indexOf(title));
            }
        });
    }

    // Reinitialize on window resize
    let resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            // Remove existing event listeners and reinitialize
            initializeMobileMenuArrows();
        }, 250);
    });
}

// Initialize Search Bar expand/collapse functionality
function initializeSearchBar() {
    const searchContainer = document.querySelector('[data-y="header-search-container"]');
    const searchInput = document.querySelector('[data-y="header-search-input"]');
    const searchIcon = document.querySelector('[data-y="header-search-icon"]');
    
    if (!searchContainer || !searchInput) {
        return;
    }
    
    // Make search icon clickable to expand
    if (searchIcon) {
        searchIcon.style.pointerEvents = 'auto';
        searchIcon.style.cursor = 'pointer';
        
        searchIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            searchContainer.classList.add('expanded');
            setTimeout(() => {
                searchInput.focus();
            }, 100);
        });
    }
    
    // Expand on input click
    searchInput.addEventListener('click', function(e) {
        e.stopPropagation();
        searchContainer.classList.add('expanded');
    });
    
    // Keep expanded when focused
    searchInput.addEventListener('focus', function() {
        searchContainer.classList.add('expanded');
    });
    
    // Collapse when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchContainer.contains(e.target) && 
            !e.target.closest('[data-y="header-search-container"]')) {
            // Only collapse if input is empty
            if (!searchInput.value) {
                searchContainer.classList.remove('expanded');
            }
        }
    });
    
    // Collapse on blur if empty
    searchInput.addEventListener('blur', function() {
        if (!searchInput.value) {
            setTimeout(() => {
                searchContainer.classList.remove('expanded');
            }, 200);
        }
    });
}
