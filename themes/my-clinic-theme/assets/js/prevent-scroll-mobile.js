/**
 * Prevent body scroll on mobile when dropdowns are open
 */
(function() {
    'use strict';
    
    // Check if device is mobile
    const isMobile = () => {
        return window.innerWidth <= 768;
    };
    
    let scrollPosition = 0;
    
    // Prevent scroll function
    const preventScroll = () => {
        if (isMobile()) {
            // Save current scroll position BEFORE any changes
            scrollPosition = window.pageYOffset || document.documentElement.scrollTop || 0;
            
            // Prevent scroll - use requestAnimationFrame to ensure smooth transition
            requestAnimationFrame(() => {
                document.body.style.overflow = 'hidden';
                document.body.style.position = 'fixed';
                document.body.style.top = `-${scrollPosition}px`;
                document.body.style.width = '100%';
                document.body.style.left = '0';
            });
        }
    };
    
    // Allow scroll function
    const allowScroll = () => {
        if (isMobile()) {
            // Restore scroll - use requestAnimationFrame to ensure smooth transition
            requestAnimationFrame(() => {
                document.body.style.overflow = '';
                document.body.style.position = '';
                document.body.style.top = '';
                document.body.style.width = '';
                document.body.style.left = '';
                
                // Restore scroll position after styles are reset
                requestAnimationFrame(() => {
                    window.scrollTo(0, scrollPosition);
                });
            });
        }
    };
    
    // Handle specialty dropdown (list1 checkbox)
    const handleSpecialtyDropdown = () => {
        const checkbox = document.getElementById('list1');
        if (!checkbox) return;
        
        const label = checkbox.nextElementSibling;
        const items = checkbox.closest('.list')?.querySelector('.items');
        if (!items) return;
        
        const toggleScroll = () => {
            // Use requestAnimationFrame to ensure DOM is ready
            requestAnimationFrame(() => {
                if (checkbox.checked && isMobile()) {
                    preventScroll();
                } else {
                    allowScroll();
                }
            });
        };
        
        // Listen to change event on checkbox
        checkbox.addEventListener('change', toggleScroll);
        
        // Also listen to click on label (which triggers checkbox change)
        if (label) {
            label.addEventListener('click', (e) => {
                // Prevent default to avoid double-trigger
                e.stopPropagation();
                requestAnimationFrame(() => {
                    requestAnimationFrame(toggleScroll);
                });
            });
        }
        
        // Also handle when items are clicked (to close dropdown)
        if (items) {
            items.addEventListener('click', (e) => {
                if (e.target.classList.contains('specialty-filter')) {
                    e.stopPropagation();
                    requestAnimationFrame(() => {
                        checkbox.checked = false;
                        allowScroll();
                    });
                }
            });
        }
        
        // Handle click outside to close
        document.addEventListener('click', (e) => {
            if (isMobile() && checkbox.checked) {
                const list = checkbox.closest('.list');
                if (list && !list.contains(e.target)) {
                    requestAnimationFrame(() => {
                        checkbox.checked = false;
                        allowScroll();
                    });
                }
            }
        });
    };
    
    // Handle custom dropdowns
    const handleCustomDropdowns = () => {
        const dropdowns = document.querySelectorAll('.custom-dropdown');
        
        dropdowns.forEach(dropdown => {
            dropdown.addEventListener('dropdown-open', () => {
                if (isMobile()) {
                    preventScroll();
                }
            });
            
            dropdown.addEventListener('dropdown-close', () => {
                if (isMobile()) {
                    allowScroll();
                }
            });
        });
    };
    
    // Handle calendar popover (if exists)
    const handleCalendarPopover = () => {
        const calendarTriggers = document.querySelectorAll('[data-calendar-trigger], .calendar-trigger');
        const calendarPopovers = document.querySelectorAll('.calendar-popover');
        
        calendarTriggers.forEach(trigger => {
            trigger.addEventListener('click', () => {
                if (isMobile()) {
                    preventScroll();
                }
            });
        });
        
        // Close calendar and allow scroll
        calendarPopovers.forEach(popover => {
            const closeBtn = popover.querySelector('.calendar-popover-close, .close-calendar');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    if (isMobile()) {
                        allowScroll();
                    }
                });
            }
        });
        
        // Also handle click outside calendar
        document.addEventListener('click', (e) => {
            if (isMobile()) {
                const openPopover = document.querySelector('.calendar-popover:not([style*="display: none"])');
                if (openPopover && !openPopover.contains(e.target) && !e.target.closest('[data-calendar-trigger]')) {
                    allowScroll();
                }
            }
        });
    };
    
    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            handleSpecialtyDropdown();
            handleCustomDropdowns();
            handleCalendarPopover();
        });
    } else {
        handleSpecialtyDropdown();
        handleCustomDropdowns();
        handleCalendarPopover();
    }
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (!isMobile()) {
                allowScroll();
            }
        }, 250);
    });
})();
