document.addEventListener('DOMContentLoaded', function () {
    // References to the tab links and content area
    const ordersLink = document.getElementById('orders-link');
    const addressLink = document.getElementById('address-link');
    const accountDetailsLink = document.getElementById('account-details-link');

    // Mobile navigation links
    const mobileOrdersLink = document.getElementById('mobile-orders-link');
    const mobileAddressLink = document.getElementById('mobile-address-link');
    const mobileAccountDetailsLink = document.getElementById('mobile-account-details-link');

    const contentPlaceholder = document.getElementById('profile-content-placeholder');
    
    // Check if we're on a WooCommerce account page
    // If so, don't interfere with WooCommerce's navigation
    // WooCommerce handles content loading via PHP templates, not JavaScript
    const isWooCommerceAccountPage = document.body.classList.contains('woocommerce-account') || 
                                      document.body.classList.contains('woocommerce-page') ||
                                      window.location.pathname.includes('/my-account');
    
    if (isWooCommerceAccountPage) {
        // Only initialize UI components that exist in the PHP-rendered content
        // Don't try to load HTML files via fetch
        setTimeout(function() {
            // Initialize after DOM is fully loaded
            if (typeof initializeAccountDetailsView === 'function') {
                initializeAccountDetailsView();
            }
            if (typeof initializeAddressView === 'function') {
                initializeAddressView();
            }
            if (typeof initializeOrdersView === 'function') {
                initializeOrdersView();
            }
        }, 100);
        return; // Exit early, let WooCommerce handle navigation
    }

    // Dummy Data for Addresses
    let userAddresses = [
        {
            id: 1,
            firstName: 'عبدالله',
            lastName: 'محمد',
            country: 'المملكة العربية السعودية',
            street: '345 الهادي',
            apartment: '',
            city: 'المدينة المنورة',
            state: 'المدينة',
            postalCode: '128475',
            phone: '05673948',
            email: 'abd@gmail.com'
        },
        {
            id: 2,
            firstName: 'خالد',
            lastName: 'أحمد',
            country: 'المملكة العربية السعودية',
            street: 'حي العليا',
            apartment: '12',
            city: 'الرياض',
            state: 'الرياض',
            postalCode: '11543',
            phone: '05555555',
            email: 'khalid@gmail.com'
        }
    ];

    let editingAddressId = null; // State to track which address is being edited

    // Function to set active tab
    function setActiveTab(link, isMobile = false) {
        // Remove active class from all desktop links
        const allDesktopLinks = document.querySelectorAll('.y-c-Profile-menu a');
        allDesktopLinks.forEach(el => {
            el.classList.remove('y-c-active');
        });

        // Remove active class from all mobile links
        const allMobileLinks = document.querySelectorAll('.y-c-mobile-nav-item');
        allMobileLinks.forEach(el => {
            el.classList.remove('y-c-active');
        });

        // Add active class to selected desktop link
        if (!isMobile) {
            link.classList.add('y-c-active');
            // Sync with mobile
            const linkTitle = link.getAttribute('data-title');
            const mobileLink = document.querySelector(`.y-c-mobile-nav-item[data-title="${linkTitle}"]`);
            if (mobileLink) {
                mobileLink.classList.add('y-c-active');
            }
        } else {
            // For mobile links, also update desktop counterpart
            const linkTitle = link.getAttribute('data-title');
            const desktopLink = document.querySelector(`.y-c-Profile-menu a[data-title="${linkTitle}"]`);
            if (desktopLink) {
                desktopLink.classList.add('y-c-active');
            }
            link.classList.add('y-c-active');
        }
    }

    // Function to load component content
    function loadComponent(url) {
        fetch(url)
            .then(response => response.text())
            .then(html => {
                contentPlaceholder.innerHTML = html;

                // After loading account-details component, set up form submission handler
                if (url.includes('account-details')) {
                    setupAccountDetailsForm();
                }
                // After loading address component, initialize address view
                if (url.includes('address')) {
                    initializeAddressView();
                }
                // After loading orders component, initialize order details modal
                if (url.includes('orders')) {
                    initializeOrdersView();
                }
            })
            .catch(error => {
                console.error('Error loading component:', error);
                contentPlaceholder.innerHTML = '<p>Error loading content. Please try again.</p>';
            });
    }

    // --- Orders View Logic ---
    function initializeOrdersView() {
        const showDetailsButtons = document.querySelectorAll('.js-show-order-details');
        const modal = document.getElementById('order-details-popup');
        const closeButton = document.getElementById('close-order-details');

        if (showDetailsButtons && modal && closeButton) {
            // Open Modal
            showDetailsButtons.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    modal.style.display = 'flex';
                });
            });

            // Close Modal via Button
            closeButton.addEventListener('click', function () {
                modal.style.display = 'none';
            });

            // Close Modal via Outside Click
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }
    }

    // --- Account Details View Logic ---
    function initializeAccountDetailsView() {
        // Initialize gender dropdown
        const genderDropdown = document.getElementById('gender-dropdown');
        if (genderDropdown) {
            const selected = genderDropdown.querySelector('.y-c-dropdown-selected');
            const options = genderDropdown.querySelectorAll('.y-c-dropdown-option');

            // Toggle dropdown
            selected.addEventListener('click', function () {
                genderDropdown.classList.toggle('y-c-dropdown-open');
            });

            // Select option
            options.forEach(option => {
                option.addEventListener('click', function () {
                    selected.textContent = this.textContent;
                    selected.setAttribute('data-value', this.getAttribute('data-value'));
                    genderDropdown.classList.remove('y-c-dropdown-open');
                });
            });

            // Close when clicking outside
            document.addEventListener('click', function (e) {
                if (!genderDropdown.contains(e.target)) {
                    genderDropdown.classList.remove('y-c-dropdown-open');
                }
            });
        }
    }

    function initializeAddressView() {
        const emptyState = document.getElementById('empty-address-state');
        const addressList = document.getElementById('address-list');
        const addressListTitle = document.getElementById('address-list-title');
        const addAddressAction = document.getElementById('add-address-action');

        // We do NOT fetch 'form' here to avoid stale references, we fetch it inside functions.

        // Helper: Show the Form, Hide the List
        function showForm() {
            const form = document.getElementById('address-form'); // Always get the current form

            if (emptyState) emptyState.style.display = 'none';
            if (addressList) addressList.style.display = 'none';
            if (addressListTitle) addressListTitle.style.display = 'none';
            if (addAddressAction) addAddressAction.style.display = 'none';
            if (form) form.style.display = 'block';
        }

        // Helper: Hide the Form, Show the List (or Empty State)
        function hideForm() {
            const form = document.getElementById('address-form'); // Always get the current form

            if (form) {
                form.style.display = 'none';
                form.reset();
            }
            editingAddressId = null; // Reset edit state
            refreshView(); // Re-evaluate what to show
        }

        // Helper: Decide whether to show List or Empty State based on data
        function refreshView() {
            const form = document.getElementById('address-form');

            if (userAddresses.length === 0) {
                if (emptyState) emptyState.style.display = 'flex';
                if (addressList) addressList.style.display = 'none';
                if (addressListTitle) addressListTitle.style.display = 'none';
                if (addAddressAction) addAddressAction.style.display = 'none';
                if (form) form.style.display = 'none';
            } else {
                if (emptyState) emptyState.style.display = 'none';
                if (addressListTitle) addressListTitle.style.display = 'block';
                if (addAddressAction) addAddressAction.style.display = 'block';
                if (form) form.style.display = 'none';
                if (addressList) {
                    addressList.style.display = 'flex';
                    renderAddressList(addressList, showForm);
                }
            }
        }

        // 1. Initial Render
        refreshView();

        // 2. Attach Event Listeners to Buttons
        // "Add New Address" button in Empty State
        const emptyStateBtn = document.querySelector('.y-c-empty-btn');
        if (emptyStateBtn) {
            const newBtn = emptyStateBtn.cloneNode(true);
            emptyStateBtn.parentNode.replaceChild(newBtn, emptyStateBtn);
            newBtn.addEventListener('click', showForm);
        }

        // "Add New Invoice Address" button in List State
        const addActionBtn = document.querySelector('#add-address-action button');
        if (addActionBtn) {
            const newBtn = addActionBtn.cloneNode(true);
            addActionBtn.parentNode.replaceChild(newBtn, addActionBtn);
            newBtn.addEventListener('click', showForm);
        }

        // "Cancel" button in Form
        const cancelBtn = document.querySelector('#address-form .y-c-btn-cancel');
        if (cancelBtn) {
            const newBtn = cancelBtn.cloneNode(true);
            cancelBtn.parentNode.replaceChild(newBtn, cancelBtn);
            newBtn.addEventListener('click', hideForm);
        }

        // 3. Form Submission Handler
        const originalForm = document.getElementById('address-form');
        if (originalForm) {
            // Clone to remove old listeners
            const newForm = originalForm.cloneNode(true);
            originalForm.parentNode.replaceChild(newForm, originalForm);

            // Re-attach cancel button listener on the NEW form
            const newCancelBtn = newForm.querySelector('.y-c-btn-cancel');
            if (newCancelBtn) {
                newCancelBtn.addEventListener('click', hideForm);
            }

            newForm.addEventListener('submit', function (e) {
                e.preventDefault();

                // Gather data
                const addrData = {
                    firstName: document.getElementById('new-first-name').value,
                    lastName: document.getElementById('new-last-name').value,
                    country: document.getElementById('new-country').value,
                    street: document.getElementById('new-street').value,
                    apartment: document.getElementById('new-apartment').value,
                    city: document.getElementById('new-city').value,
                    state: document.getElementById('new-state').value,
                    postalCode: document.getElementById('new-postal-code').value,
                    phone: document.getElementById('new-phone').value,
                    email: document.getElementById('new-email').value
                };

                if (editingAddressId) {
                    // Update existing
                    const index = userAddresses.findIndex(a => a.id === editingAddressId);
                    if (index !== -1) {
                        userAddresses[index] = { ...userAddresses[index], ...addrData };
                    }
                    editingAddressId = null;
                } else {
                    // Create new
                    const newAddress = {
                        id: Date.now(),
                        ...addrData
                    };
                    userAddresses.push(newAddress);
                }

                hideForm(); // This will reset form and refresh view
            });
        }
    }

    // Render List with Table-Row structure and Icon Buttons
    function renderAddressList(container, showFormCallback) {
        container.innerHTML = '';
        userAddresses.forEach(addr => {
            const card = document.createElement('div');
            card.className = 'y-c-address-card';

            // Build the row structure
            card.innerHTML = `
                <div class="y-c-address-details">
                    <div class="y-c-address-col y-c-address-name">${addr.firstName} ${addr.lastName}</div>
                    <div class="y-c-address-col y-c-address-street">${addr.street}${addr.apartment ? ', ' + addr.apartment : ''}</div>
                    <div class="y-c-address-col y-c-address-city">${addr.city}, ${addr.state}</div>
                    <div class="y-c-address-col y-c-address-postal">${addr.postalCode}</div>
                    <div class="y-c-address-col y-c-address-phone">${addr.phone}</div>
                    <div class="y-c-address-col y-c-address-email">${addr.email}</div>
                </div>
                <div class="y-c-address-actions">
                    <button class="y-c-icon-btn edit-btn"><i class="fas fa-pen"></i></button>
                    <button class="y-c-icon-btn delete-btn"><i class="fas fa-times-circle"></i></button>
                </div>
            `;

            // Edit Action
            const editBtn = card.querySelector('.edit-btn');
            editBtn.addEventListener('click', () => {
                // Populate Form
                document.getElementById('new-first-name').value = addr.firstName;
                document.getElementById('new-last-name').value = addr.lastName;
                document.getElementById('new-country').value = addr.country || 'المملكة العربية السعودية';
                document.getElementById('new-street').value = addr.street;
                document.getElementById('new-apartment').value = addr.apartment || '';
                document.getElementById('new-city').value = addr.city;
                document.getElementById('new-state').value = addr.state;
                document.getElementById('new-postal-code').value = addr.postalCode;
                document.getElementById('new-phone').value = addr.phone;
                document.getElementById('new-email').value = addr.email;

                // Set State
                editingAddressId = addr.id;

                // Show Form
                if (showFormCallback) showFormCallback();
            });

            // Delete Action
            const deleteBtn = card.querySelector('.delete-btn');
            deleteBtn.addEventListener('click', () => {
                userAddresses = userAddresses.filter(a => a.id !== addr.id);
                initializeAddressView(); // Re-render
            });

            container.appendChild(card);
        });
    }

    // --- End Address View Logic ---


    // Function to set up account details form submission
    function setupAccountDetailsForm() {
        const accountForm = document.getElementById('account-details-form');
        if (accountForm) {
            accountForm.addEventListener('submit', function (event) {
                event.preventDefault();
                console.log("Account details updated");
            });
        }

        // Initialize gender dropdown
        const genderDropdown = document.getElementById('gender-dropdown');
        if (genderDropdown) {
            const selected = genderDropdown.querySelector('.y-c-dropdown-selected');
            const options = genderDropdown.querySelectorAll('.y-c-dropdown-option');

            // Toggle dropdown
            selected.addEventListener('click', function () {
                genderDropdown.classList.toggle('y-c-dropdown-open');
            });

            // Select option
            options.forEach(option => {
                option.addEventListener('click', function () {
                    selected.textContent = this.textContent;
                    selected.setAttribute('data-value', this.getAttribute('data-value'));
                    genderDropdown.classList.remove('y-c-dropdown-open');
                });
            });

            // Close when clicking outside
            document.addEventListener('click', function (e) {
                if (!genderDropdown.contains(e.target)) {
                    genderDropdown.classList.remove('y-c-dropdown-open');
                }
            });
        }
    }

    // Event listeners for tab clicks
    // Remove event listeners that prevent default navigation
    // WooCommerce handles navigation via URL endpoints, so we don't need to load HTML files
    // The links will work normally and WooCommerce will load the correct template
    
    // Only set active tab based on current URL endpoint
    if (accountDetailsLink && (accountDetailsLink.classList.contains('y-c-active') || window.location.pathname.includes('/my-account') && !window.location.pathname.includes('/orders') && !window.location.pathname.includes('/edit-address'))) {
        setActiveTab(accountDetailsLink);
    } else if (ordersLink && (ordersLink.classList.contains('y-c-active') || window.location.pathname.includes('/orders'))) {
        setActiveTab(ordersLink);
    } else if (addressLink && (addressLink.classList.contains('y-c-active') || window.location.pathname.includes('/edit-address'))) {
        setActiveTab(addressLink);
    }
});