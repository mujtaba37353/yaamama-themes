document.addEventListener('DOMContentLoaded', function () {
    // References to the tab links
    const accountDetailsLink = document.getElementById('account-details-link');
    const ordersLink = document.getElementById('orders-link');
    const contractsLink = document.getElementById('contracts-link');
    const reservationsLink = document.getElementById('reservations-link');

    // Content sections
    const accountDetailsContent = document.getElementById('account-details-content');
    const ordersContent = document.getElementById('orders-content');
    const contractsContent = document.getElementById('contracts-content');
    const reservationsContent = document.getElementById('reservations-content');

    // Dynamic elements (will be available after loading components)
    let profileForm, stopNotificationsBtn, deleteAccountBtn;
    let editImageBtn, profileImage;

    // Function to load component content
    function loadComponent(url, targetElement) {
        return fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Failed to load ${url}: ${response.statusText}`);
                }
                return response.text();
            })
            .then(html => {
                if (targetElement) {
                    targetElement.innerHTML = html;
                }
                return html;
            })
            .catch(error => {
                console.error('Error loading component:', error);
                if (targetElement) {
                    targetElement.innerHTML = `<p class="y-c-empty-message">Error loading content. Please try again.</p>`;
                }
            });
    }

    // Function to update references to dynamic elements
    function updateElementReferences() {
        profileForm = document.querySelector('.y-c-profile-form');
        // stopNotificationsBtn is no longer in the HTML, this will just be null
        stopNotificationsBtn = document.querySelector('[data-y="stop-notifications-btn"]');
        deleteAccountBtn = document.querySelector('[data-y="delete-account-btn"]');
        editImageBtn = document.querySelector('[data-y="edit-image-btn"]');
        profileImage = document.querySelector('[data-y="profile-image"]');
    }

    // Function to set active tab and show content
    function setActiveTab(targetSection, activeLink) {
        // Remove active class from all links
        const allLinks = document.querySelectorAll('.y-c-Profile-menu a[id$="-link"]');
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

    /*
    // Function to show notification - REMOVED
    function showNotification(message, type = 'success') {
        // ... (function content removed) ...
    }
    */

    // Profile form submission handler
    function setupProfileForm() {
        if (profileForm) {
            profileForm.addEventListener('submit', function (e) {
                e.preventDefault();

                // Get form data
                const formData = new FormData(this);
                const profileData = {
                    firstName: formData.get('first-name') || document.querySelector('[data-y="first-name-input"]').value,
                    lastName: formData.get('last-name') || document.querySelector('[data-y="last-name-input"]').value,
                    // ... other fields if needed ...
                };

                // Simulate API call
                setTimeout(() => {
                    // showNotification('تم حفظ التغييرات بنجاح!', 'success'); // REMOVED

                    // Update profile header name
                    const profileName = document.querySelector('.y-c-profile-name h3');
                    if (profileName) {
                        profileName.textContent = `${profileData.firstName} ${profileData.lastName}`;
                    }
                    /* Email is no longer in the profile header
                    if (profileEmail) {
                        profileEmail.textContent = profileData.email;
                    }
                    */
                }, 500);
            });
        }
    }

    // Profile image upload handler
    function setupImageUpload() {
        if (editImageBtn) {
            editImageBtn.addEventListener('click', function () {
                const input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*';

                input.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            if (profileImage) {
                                profileImage.src = e.target.result;
                            }
                            // showNotification('تم تحديث الصورة بنجاح!', 'success'); // REMOVED
                        };
                        reader.readAsDataURL(file);
                    }
                });

                input.click();
            });
        }
    }

    // Account management handlers
    function setupAccountManagement() {
        // Stop notifications handler (element no longer exists, this will be skipped)
        if (stopNotificationsBtn) {
            stopNotificationsBtn.addEventListener('click', function () {
                // Use custom modal/confirm later
                if (window.confirm('هل تريد إيقاف جميع الإشعارات؟')) {
                    // Turn off all notification toggles
                    const toggles = document.querySelectorAll('.y-c-toggle-switch input');
                    toggles.forEach(toggle => {
                        toggle.checked = false;
                    });

                    // showNotification('تم إيقاف جميع الإشعارات', 'info'); // REMOVED
                }
            });
        }

        // Delete account handler
        if (deleteAccountBtn) {
            const deletePopup = document.getElementById('delete-account-popup');
            const cancelBtn = document.getElementById('cancel-delete-btn');
            const confirmBtn = document.getElementById('confirm-delete-btn');
            const closeBtn = document.getElementById('close-delete-popup');

            console.log('Delete button found:', deleteAccountBtn);
            console.log('Delete popup found:', deletePopup);

            // Show popup when delete button is clicked
            deleteAccountBtn.addEventListener('click', function (e) {
                e.preventDefault();
                console.log('Delete button clicked');
                if (deletePopup) {
                    console.log('Adding show class to popup');
                    deletePopup.classList.add('show');
                } else {
                    console.error('Delete popup not found!');
                }
            });

            // Hide popup when cancel button is clicked
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (deletePopup) {
                        deletePopup.classList.remove('show');
                    }
                });
            }

            // Hide popup when close button (X) is clicked
            if (closeBtn) {
                closeBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (deletePopup) {
                        deletePopup.classList.remove('show');
                    }
                });
            }

            // Confirm delete button handler
            if (confirmBtn) {
                confirmBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    // Simulate account deletion
                    confirmBtn.textContent = 'جاري التعطيل...';
                    confirmBtn.disabled = true;

                    setTimeout(() => {
                        if (deletePopup) {
                            deletePopup.classList.remove('show');
                        }
                        window.location.href = '../home/layout.html'; // Redirect to home
                    }, 1500);
                });
            }

            // Close popup when clicking outside
            if (deletePopup) {
                deletePopup.addEventListener('click', function (e) {
                    if (e.target === deletePopup) {
                        deletePopup.classList.remove('show');
                    }
                });
            }

            // Close popup with Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    const deletePopup = document.getElementById('delete-account-popup');
                    if (deletePopup && deletePopup.classList.contains('show')) {
                        deletePopup.classList.remove('show');
                    }
                }
            });
        } else {
            console.log('Delete account button not found');
        }
    }

    // Navigation event handlers
    function setupNavigation() {
        // Account Details tab
        if (accountDetailsLink) {
            accountDetailsLink.addEventListener('click', function (e) {
                e.preventDefault();
                loadComponent('components/account-details.html', accountDetailsContent)
                    .then(() => {
                        setActiveTab(accountDetailsContent, this);
                        updateElementReferences();
                        setupProfileForm();
                        setupImageUpload();
                        setupAccountManagement();
                        setupNotificationToggles();
                    });
            });
        }

        // Orders tab (Wallet)
        if (ordersLink) {
            ordersLink.addEventListener('click', function (e) {
                e.preventDefault();
                loadComponent('components/orders.html', ordersContent)
                    .then(() => {
                        setActiveTab(ordersContent, this);
                    });
            });
        }

        // Contracts tab
        if (contractsLink) {
            contractsLink.addEventListener('click', function (e) {
                e.preventDefault();
                loadComponent('components/contracts.html', contractsContent)
                    .then(() => {
                        setActiveTab(contractsContent, this);
                        // Dispatch the event that contracts content is loaded
                        document.dispatchEvent(new CustomEvent('contractsContentLoaded'));
                    });
            });
        }

        // Reservations tab
        if (reservationsLink) {
            reservationsLink.addEventListener('click', function (e) {
                e.preventDefault();
                loadComponent('components/reservations.html', reservationsContent)
                    .then(() => {
                        setActiveTab(reservationsContent, this);
                    });
            });
        }
    }

    // Setup notification toggles
    function setupNotificationToggles() {
        const toggles = document.querySelectorAll('.y-c-toggle-switch input');
        toggles.forEach(toggle => {
            toggle.addEventListener('change', function () {
                const notificationType = this.closest('.y-c-management-item').querySelector('h4').textContent.trim();
                const status = this.checked ? 'تم تفعيل' : 'تم إيقاف';
                // showNotification(`${status} ${notificationType}`, 'info'); // REMOVED
                console.log(`${status} ${notificationType}`); // Added console.log for feedback
            });
        });
    }

    // Initialize all functionality
    function initializeAccount() {
        setupNavigation();

        // Load default component (Account Details) on page load
        if (accountDetailsLink && accountDetailsContent) {
            loadComponent('components/account-details.html', accountDetailsContent)
                .then(() => {
                    setActiveTab(accountDetailsContent, accountDetailsLink);
                    updateElementReferences();
                    setupProfileForm();
                    setupImageUpload();
                    setupAccountManagement();
                    setupNotificationToggles();
                });
        }
    }

    // Start the application
    initializeAccount();
});
