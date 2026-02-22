// Al Thabihah/js/account.js

document.addEventListener('DOMContentLoaded', function () {
    // References to the tab links
    const ordersLink = document.getElementById('orders-link');
    const accountDetailsLink = document.getElementById('account-details-link');
    const addressLink = document.getElementById('address-link');
    const favoritesLink = document.getElementById('favorites-link');
    const notificationsLink = document.getElementById('notifications-link');
    // Content sections
    const accountDetailsContent = document.getElementById('account-details-content');
    const ordersContent = document.getElementById('orders-content');
    const addressContent = document.getElementById('address-content');
    const favoritesContent = document.getElementById('favorites-content');
    const notificationsContent = document.getElementById('notifications-content');

    // Dynamic elements
    let profileForm, deleteAccountBtn;
    let editImageBtn, profileImage;

    // Function to load component content
    function loadComponent(url, targetElement) {
        return fetch(url)
            .then(response => response.text())
            .then(html => {
                if (targetElement) {
                    targetElement.innerHTML = html;
                }
                return html;
            })
            .catch(error => {
                console.error('Error loading component:', error);
                if (targetElement) {
                    targetElement.innerHTML = '<p>Error loading content. Please try again.</p>';
                }
            });
    }

    // Function to update references to dynamic elements (Profile) and setup listeners
    function setupAccountDetailsLogic() {
        profileForm = document.querySelector('.y-c-profile-form');
        deleteAccountBtn = document.querySelector('[data-y="delete-account-btn"]');
        editImageBtn = document.querySelector('[data-y="edit-image-btn"]');
        profileImage = document.querySelector('[data-y="profile-image"]');

        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                e.preventDefault();
                if (window.validationUtils && window.validationUtils.validateContainer(profileForm)) {
                     // Simulate save
                     if (window.productUtils) window.productUtils.showNotification('تم حفظ التعديلات بنجاح', 'success');
                     else alert('تم حفظ التعديلات بنجاح');
                }
            });

            // Password Toggle Logic for Account Details
            const passwordToggles = profileForm.querySelectorAll('.y-c-password-toggle');
            passwordToggles.forEach(toggle => {
                toggle.addEventListener('click', function () {
                    const passwordWrapper = this.closest('.y-l-password-wrapper');
                    if (!passwordWrapper) return;

                    const passwordInput = passwordWrapper.querySelector('.y-c-form-input');
                    if (!passwordInput) return;

                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    if (type === 'text') {
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    } else {
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    }
                });
            });
        }
    }

    // Function to set active tab and show content
    function setActiveTab(targetSection, activeLink) {
        // Remove active class from all links
        const allLinks = document.querySelectorAll('.y-c-account-nav .y-c-nav-tab');
        allLinks.forEach(link => link.classList.remove('y-c-active'));

        // Hide all content sections
        const allSections = document.querySelectorAll('.y-c-content-section');
        allSections.forEach(section => section.classList.remove('active'));

        // Add active class to the clicked link
        if (activeLink) {
            activeLink.classList.add('y-c-active');
        }

        // Show target section
        if (targetSection) {
            targetSection.classList.add('active');
        }
    }

    // --- Favorites Logic ---
    function setupFavorites() {
        const favoritesGrid = document.getElementById('favorites-grid');
        const emptyState = document.getElementById('favorites-empty');

        if (!favoritesGrid) return;

        // Get favorites from localStorage
        const favorites = JSON.parse(localStorage.getItem('favorites')) || [];

        if (favorites.length === 0) {
            // Show empty state
            if (emptyState) emptyState.style.display = 'flex';
            favoritesGrid.style.display = 'none';
            return;
        }

        // Hide empty state and show grid
        if (emptyState) emptyState.style.display = 'none';
        favoritesGrid.style.display = 'grid';

        // Get products from products.js (assuming it's loaded globally)
        if (typeof products === 'undefined') {
            console.error('Products array not found');
            return;
        }

        // Filter products that are in favorites
        const favoriteProducts = products.filter(product => favorites.includes(product.id));

        // Generate and insert product cards
        favoritesGrid.innerHTML = favoriteProducts.map(product => {
            return window.createProductCard ? window.createProductCard(product) : '';
        }).join('');

        // Note: Event listeners are already handled by products.js global delegation
        // Listen for favorites updates to refresh the view
        document.addEventListener('favoritesUpdated', function () {
            setupFavorites();
        }, { once: true });
    }

    // --- Address Form Logic ---
    function setupAddressForm() {
        const displayView = document.getElementById('address-display-view');
        const editForm = document.getElementById('address-edit-form');
        const editBtn = document.getElementById('edit-address-btn');
        const cancelBtn = document.getElementById('cancel-edit-btn');
        const addBtn = document.getElementById('add-address-btn');

        if (editBtn && displayView && editForm) {
            // Switch to Edit Mode
            editBtn.addEventListener('click', function (e) {
                e.preventDefault();
                displayView.style.display = 'none';
                editForm.style.display = 'block';
            });

            // Add New Address (uses same form for now)
            if (addBtn) {
                addBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    // Clear form fields for new address
                    editForm.reset();
                    editForm.querySelector('[name="country"]').value = 'المملكة العربية السعودية';
                    displayView.style.display = 'none';
                    editForm.style.display = 'block';
                });
            }

            // Cancel Edit
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function () {
                    editForm.style.display = 'none';
                    displayView.style.display = 'block';
                });
            }

            // Save Address
            editForm.addEventListener('submit', function (e) {
                e.preventDefault();

                // Validate form
                if (window.validationUtils && !window.validationUtils.validateContainer(this)) {
                    return;
                }

                const formData = new FormData(this);

                // Update Display Values
                const fullName = formData.get('firstName') + ' ' + formData.get('lastName');
                document.querySelector('[data-field="fullName"]').textContent = fullName;
                document.querySelector('[data-field="country"]').textContent = formData.get('country');
                document.querySelector('[data-field="street"]').textContent = formData.get('street');
                document.querySelector('[data-field="district"]').textContent = formData.get('district');
                document.querySelector('[data-field="city"]').textContent = formData.get('city');
                document.querySelector('[data-field="region"]').textContent = formData.get('region');
                document.querySelector('[data-field="postalCode"]').textContent = formData.get('postalCode');
                document.querySelector('[data-field="buildingNo"]').textContent = formData.get('buildingNo');
                document.querySelector('[data-field="unitNo"]').textContent = formData.get('unitNo');

                // Switch back to view
                editForm.style.display = 'none';
                displayView.style.display = 'block';

            });
        }
    }

    // --- Orders Logic (Toggle Details) ---
    function setupOrdersLogic() {
        const ordersList = document.getElementById('y-v-orders-list');
        const orderDetails = document.getElementById('y-v-order-details');
        const detailBtns = document.querySelectorAll('.y-c-btn-details');
        const backBtn = document.getElementById('y-btn-back-to-orders');

        if (!ordersList || !orderDetails) return;

        // Show Details
        detailBtns.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                // In a real app, you would fetch ID from btn.dataset.order
                // For now, just show the static view
                ordersList.classList.add('y-u-hidden');
                orderDetails.classList.remove('y-u-hidden');

                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });

        // Back to List
        if (backBtn) {
            backBtn.addEventListener('click', function (e) {
                e.preventDefault();
                orderDetails.classList.add('y-u-hidden');
                ordersList.classList.remove('y-u-hidden');
            });
        }
    }

    // Navigation event handlers
    function setupNavigation() {
        // Account Details tab
        if (accountDetailsLink) {
            accountDetailsLink.addEventListener('click', function (e) {
                e.preventDefault();
                loadComponent('./components/account-details.html', accountDetailsContent)
                    .then(() => {
                        setActiveTab(accountDetailsContent, this);
                        setupAccountDetailsLogic();
                        // setupProfileForm(); // Add back if needed
                        // setupAccountManagement(); // Add back if needed
                    });
            });
        }

        // Orders tab
        if (ordersLink) {
            ordersLink.addEventListener('click', function (e) {
                e.preventDefault();
                loadComponent('./components/orders.html', ordersContent)
                    .then(() => {
                        setActiveTab(ordersContent, this);
                        setupOrdersLogic(); // Initialize orders logic
                    });
            });
        }
        // Notifications tab
        if (notificationsLink) {
            notificationsLink.addEventListener('click', function (e) {
                e.preventDefault();
                loadComponent('./components/notifications.html', notificationsContent)
                    .then(() => {
                        setActiveTab(notificationsContent, this);
                        // Optional: Initialize any JS specific to notifications here
                        // setupNotificationsLogic(); 
                    });
            });
        }
        // Address tab
        if (addressLink) {
            addressLink.addEventListener('click', function (e) {
                e.preventDefault();
                loadComponent('./components/address.html', addressContent)
                    .then(() => {
                        setActiveTab(addressContent, this);
                        setupAddressForm(); // Initialize address logic
                    });
            });
        }

        // Favorites tab
        if (favoritesLink) {
            favoritesLink.addEventListener('click', function (e) {
                e.preventDefault();
                loadComponent('./components/favorites.html', favoritesContent)
                    .then(() => {
                        setActiveTab(favoritesContent, this);
                        setupFavorites(); // Initialize favorites display
                    });
            });
        }
    }

    // Initialize all functionality
    function initializeAccount() {
        setupNavigation();

        // Load default component (Account Details) on page load
        if (accountDetailsLink && accountDetailsContent) {
            loadComponent('components/account-details.html', accountDetailsContent)
                .then(() => {
                    setActiveTab(accountDetailsContent, accountDetailsLink);
                    setupAccountDetailsLogic();
                });
        }
    }

    initializeAccount();
});