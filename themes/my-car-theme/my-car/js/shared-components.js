// Dynamically load header and footer components
function loadComponent(url, position) {
    fetch(url)
        .then(response => response.text())
        .then(html => {
            const tempContainer = document.createElement('div');
            tempContainer.innerHTML = html;
            const component = tempContainer.firstChild;
            if (position === 'top') {
                document.body.insertBefore(component, document.body.firstChild);
            } else {
                document.body.appendChild(component);
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
                        } else {
                            mobileMenuIcon.classList.remove('fa-times');
                            mobileMenuIcon.classList.add('fa-bars');
                        }
                    });

                    // Close dropdown when clicking outside
                    document.addEventListener('click', (e) => {
                        if (!mobileMenuButton.contains(e.target) && !mobileMenu.contains(e.target)) {
                            mobileMenu.classList.remove('open');
                            mobileMenuIcon.classList.remove('fa-times');
                            mobileMenuIcon.classList.add('fa-bars');
                        }
                    });

                    // Close dropdown when clicking on menu links
                    mobileMenuLinks.forEach(link => {
                        link.addEventListener('click', () => {
                            mobileMenu.classList.remove('open');
                            mobileMenuIcon.classList.remove('fa-times');
                            mobileMenuIcon.classList.add('fa-bars');
                        });
                    });

                    // Close dropdown on escape key
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && mobileMenu.classList.contains('open')) {
                            mobileMenu.classList.remove('open');
                            mobileMenuIcon.classList.remove('fa-times');
                            mobileMenuIcon.classList.add('fa-bars');
                        }
                    });
                }
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

// Handle horizontal scrollable containers
function setupScrollableContainers() {
    const scrollContainers = document.querySelectorAll('.y-l-shop-parts, .y-l-home-parts, .y-l-product-slider');

    scrollContainers.forEach(container => {
        container.addEventListener('scroll', () => {
            if (container.scrollLeft > 10) {
                container.classList.add('scrolled');
            } else {
                container.classList.remove('scrolled');
            }
        });
    });
}

// Handle hero tabs
function initializeHeroTabs() {
    const tabContainer = document.querySelector('.y-c-hero-tabs');
    if (!tabContainer) return; // Exit if tabs aren't on the page

    const tabButtons = tabContainer.querySelectorAll('.y-c-hero-tab-btn');

    tabButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent any default button behavior

            // Remove 'active' from all sibling buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active');
            });

            // Add 'active' to the clicked button
            this.classList.add('active');
        });
    });
}

// Initialize custom datepickers
// Handle location dropdown
function initializeLocationDropdowns() {
    const arrows = document.querySelectorAll('.y-c-arrow');
    const dropdowns = document.querySelectorAll('.y-c-location-dropdown');

    arrows.forEach((arrow, index) => {
        arrow.addEventListener('click', (e) => {
            e.stopPropagation();
            const dropdown = dropdowns[index];
            const isActive = dropdown.classList.contains('active');

            // Close all dropdowns first
            dropdowns.forEach(d => d.classList.remove('active'));
            arrows.forEach(a => a.style.transform = 'rotate(0deg)');

            // Toggle the clicked dropdown
            if (!isActive) {
                dropdown.classList.add('active');
                arrow.style.transform = 'rotate(180deg)';
            }
        });
    });

    // Add click event to dropdown items
    dropdowns.forEach(dropdown => {
        const items = dropdown.querySelectorAll('li');
        const locationText = dropdown.parentElement.querySelector('.y-c-info-location');

        items.forEach(item => {
            item.addEventListener('click', () => {
                locationText.textContent = item.textContent;
                dropdown.classList.remove('active');
                dropdown.parentElement.querySelector('.y-c-arrow').style.transform = 'rotate(0deg)';
            });
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.y-c-info-box-body')) {
            dropdowns.forEach(d => d.classList.remove('active'));
            arrows.forEach(a => a.style.transform = 'rotate(0deg)');
        }
    });
}

function initializeDatepickers() {
    // Check if the flatpickr library is loaded
    if (typeof flatpickr === 'function') {

        // Attach to the "Pickup Date" input
        flatpickr("#pickup-date", {
            dateFormat: "Y-m-d", // Sets the format (YYYY-MM-DD)
            minDate: "today",     // Prevents picking past dates
            placeholder: "اختر تاريخ"
        });

        // Attach to the "Dropoff Date" input
        flatpickr("#dropoff-date", {
            dateFormat: "Y-m-d",
            minDate: "today",
            placeholder: "اختر تاريخ"
        });

        // Attach to time inputs
        flatpickr("#pickup-time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K", // 12-hour format with AM/PM
            time_24hr: false,     // Use 12-hour clock
            placeholder: "اختر وقت"
        });

        flatpickr("#dropoff-time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K", // 12-hour format with AM/PM
            time_24hr: false,     // Use 12-hour clock
            placeholder: "اختر وقت"
        });

    } else {
        // If flatpickr hasn't loaded yet, try again
        setTimeout(initializeDatepickers, 50);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Use relative paths to go up two levels from the /templates/login/ folder
    loadComponent('../../components/y-header.html', 'top');
    loadComponent('../../components/y-footer.html', 'bottom');
    loadComponent('../../components/y-auth-popup.html', 'bottom');

    // Set up scrollable containers after a small delay to ensure DOM is ready
    setTimeout(setupScrollableContainers, 300);

    // Initialize hero tabs functionality
    initializeHeroTabs();

    // Initialize custom datepickers
    initializeDatepickers();
    initializeLocationDropdowns();
});