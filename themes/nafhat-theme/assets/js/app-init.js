/**
 * Mobile Menu Toggle
 */
(function() {
    'use strict';
    
    const initMobileMenu = () => {
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
        
        if (!mobileMenuBtn || !mobileMenuOverlay) {
            return;
        }
        
        // Store scroll position to restore later
        let scrollPosition = 0;
        
        const openMobileMenu = () => {
            // Save current scroll position
            scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
            
            mobileMenuBtn.classList.add('active');
            mobileMenuOverlay.classList.add('active');
            document.body.classList.add('mobile-menu-open');
            
            // Prevent body scroll on mobile
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.top = `-${scrollPosition}px`;
            document.body.style.width = '100%';
            document.body.style.touchAction = 'none';
        };
        
        const closeMobileMenu = () => {
            mobileMenuBtn.classList.remove('active');
            mobileMenuOverlay.classList.remove('active');
            document.body.classList.remove('mobile-menu-open');
            
            // Restore body scroll
            document.body.style.overflow = '';
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.width = '';
            document.body.style.touchAction = '';
            
            // Restore scroll position
            window.scrollTo(0, scrollPosition);
        };
        
        const toggleMobileMenu = () => {
            if (mobileMenuOverlay.classList.contains('active')) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        };
        
        // Toggle menu on button click (supports both click and touch)
        const handleToggle = (e) => {
            e.preventDefault();
            e.stopPropagation();
            toggleMobileMenu();
        };
        
        mobileMenuBtn.addEventListener('click', handleToggle);
        mobileMenuBtn.addEventListener('touchend', handleToggle);
        
        // Close menu when clicking on overlay
        mobileMenuOverlay.addEventListener('click', (event) => {
            if (event.target === mobileMenuOverlay) {
                closeMobileMenu();
            }
        });
        
        // Close menu when clicking on menu links
        const mobileMenuLinks = mobileMenuOverlay.querySelectorAll('.mobile-menu a');
        mobileMenuLinks.forEach((link) => {
            link.addEventListener('click', () => {
                closeMobileMenu();
            });
        });
        
        // Close menu on Escape key
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && mobileMenuOverlay.classList.contains('active')) {
                closeMobileMenu();
            }
        });
    };
    
    // Initialize mobile menu when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMobileMenu);
    } else {
        initMobileMenu();
    }
})();

/**
 * Header Search Bar Toggle
 */
(function() {
    'use strict';
    
    const initSearchBar = () => {
        const searchContainer = document.querySelector('.header-search-container');
        const searchToggle = document.querySelector('.header-search-toggle');
        const searchForm = document.querySelector('.header-search-form');
        const searchInput = document.querySelector('.header-search-input');
        
        if (!searchContainer || !searchToggle || !searchForm || !searchInput) {
            return;
        }
        
        const backdrop = document.querySelector('.search-overlay-backdrop');
        
        // Toggle search bar
        const toggleSearch = () => {
            const isActive = searchContainer.classList.contains('active');
            
            if (isActive) {
                closeSearch();
            } else {
                openSearch();
            }
        };
        
        // Open search bar
        const openSearch = () => {
            searchContainer.classList.add('active');
            searchForm.classList.add('active');
            if (backdrop) {
                backdrop.classList.add('active');
            }
            document.body.style.overflow = 'hidden';
            
            // Focus input when opened
            setTimeout(() => {
                searchInput.focus();
            }, 100);
        };
        
        // Close search bar
        const closeSearch = () => {
            searchContainer.classList.remove('active');
            searchForm.classList.remove('active');
            if (backdrop) {
                backdrop.classList.remove('active');
            }
            document.body.style.overflow = '';
            searchInput.blur();
        };
        
        // Event listeners
        searchToggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            toggleSearch();
        });
        
        // Close when clicking on backdrop
        if (backdrop) {
            backdrop.addEventListener('click', (e) => {
                if (e.target === backdrop) {
                    closeSearch();
                }
            });
        }
        
        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && searchContainer.classList.contains('active')) {
                closeSearch();
            }
        });
        
        // Prevent form submission from closing (let it submit naturally)
        searchForm.addEventListener('submit', (e) => {
            // Allow form to submit normally
        });
    };
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSearchBar);
    } else {
        initSearchBar();
    }
})();
