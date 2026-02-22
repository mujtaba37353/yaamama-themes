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

    // Profile form submission handler
    function setupProfileForm() {
        if (profileForm) {
            profileForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const profileData = {
                    firstName: formData.get('first-name') || document.querySelector('[data-y="first-name-input"]').value,
                    lastName: formData.get('last-name') || document.querySelector('[data-y="last-name-input"]').value,
                };
                setTimeout(() => {
                    const profileName = document.querySelector('.y-c-profile-name h3');
                    if (profileName) {
                        profileName.textContent = `${profileData.firstName} ${profileData.lastName}`;
                    }
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
        if (deleteAccountBtn) {
            deleteAccountBtn.addEventListener('click', function () {
                if (window.confirm('هل أنت متأكد من حذف الحساب؟ هذا الإجراء غير قابل للتراجع.')) {
                    if (window.confirm('تأكيد نهائي: سيتم حذف جميع بياناتك نهائياً. هل تريد المتابعة؟')) {
                        setTimeout(() => {
                            window.location.href = '../login/layout.html';
                        }, 2000);
                    }
                }
            });
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
                        setupContractTabs(); // <-- UPDATED CALL
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

    // --- UPDATED FUNCTION ---
    // Handles tab switching and content generation for contracts.html
    function setupContractTabs() {
        const filterButtons = document.querySelectorAll('[data-y^="filter-contracts-"]');
        const contractsList = document.querySelector('[data-y="contracts-list"]');

        if (!filterButtons.length || !contractsList) {
            console.log('Contracts filter elements not found.');
            return;
        }

        // Sample data for different contract types
        const contractsData = {
            open: [
                {
                    id: 'I25000R38704472',
                    name: 'هيونداي جراند I10',
                    image: '/assets/product.png',
                    pickup: {
                        location: 'الاحمدية, الرياض',
                        date: 'Thu, 20 Nov, 01:00 PM'
                    },
                    dropoff: {
                        location: 'الاحمدية, الرياض',
                        date: 'Wed, 26 Nov, 01:00 PM'
                    },
                    total: '2094.23',
                    status: 'تم الدفع'
                }
            ],
            claim: [
                {
                    id: 'I25000R38704475',
                    name: 'كيا K5',
                    image: '/assets/product.png',
                    pickup: {
                        location: 'العليا, الرياض',
                        date: 'Mon, 15 Nov, 03:00 PM'
                    },
                    dropoff: {
                        location: 'العليا, الرياض',
                        date: 'Fri, 20 Nov, 03:00 PM'
                    },
                    total: '3050.00',
                    status: 'تحت المراجعة'
                }
            ],
            closed: [
                {
                    id: 'I25000R38704480',
                    name: 'تويوتا كامري',
                    image: '/assets/product.png',
                    pickup: {
                        location: 'النخيل, الرياض',
                        date: 'Sun, 1 Nov, 10:00 AM'
                    },
                    dropoff: {
                        location: 'النخيل, الرياض',
                        date: 'Wed, 4 Nov, 10:00 AM'
                    },
                    total: '1580.50',
                    status: 'مكتمل'
                }
            ]
        };

        function createContractCard(contract) {
            // Determine button class and state
            let btnClass = 'y-c-basic-btn';
            let btnDisabled = '';
            if (contract.status === 'تحت المراجعة') {
                btnClass = 'y-c-basic-btn y-c-pending-btn';
                btnDisabled = 'disabled';
            } else if (contract.status === 'مكتمل') {
                btnClass = 'y-c-basic-btn y-c-received-btn';
                btnDisabled = 'disabled';
            }

            return `
                <li class="y-c-booking-card">

                    <div class="y-c-booking-card__image">
                        <img src="${contract.image}" alt="${contract.name}">
                    </div>

                    <div class="y-c-booking-card__details">
                        <h3 class="y-c-booking-id">${contract.id}</h3>
                        <h4 class="y-c-booking-name">${contract.name}</h4>

                        <div class="y-c-booking-location">
                            <span class="y-c-booking-location-title">الاستلام</span>
                            <p class="y-c-booking-location-text">${contract.pickup.location}</p>
                            <p class="y-c-booking-location-date">${contract.pickup.date}</p>
                        </div>

                        <div class="y-c-booking-location">
                            <span class="y-c-booking-location-title">التسليم</span>
                            <p class="y-c-booking-location-text">${contract.dropoff.location}</p>
                            <p class="y-c-booking-location-date">${contract.dropoff.date}</p>
                        </div>
                    </div>

                    <div class="y-c-booking-card__summary">
                        <div class="y-c-booking-total">
                            <span class="y-c-booking-total-label">مجموع الايجار:</span>
                            <span class="y-c-booking-total-price">${contract.total} ريال</span>
                        </div>
                        <button class="${btnClass}" ${btnDisabled}>
                            ${contract.status}
                        </button>
                    </div>

                </li>
            `;
        }


        function updateContractsList(type) {
            const contracts = contractsData[type] || [];
            if (contracts.length === 0) {
                contractsList.innerHTML = `
                    <li class="y-c-empty-message">
                        لا توجد عقود ${type === 'open' ? 'مفتوحة' : type === 'claim' ? 'تحت المطالبة' : 'مغلقة'} حالياً
                    </li>
                `;
                return;
            }
            const contractsHTML = contracts.map(contract => createContractCard(contract)).join('');
            contractsList.innerHTML = contractsHTML;
        }

        // Add click handlers to filter buttons
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                const type = button.getAttribute('data-y').replace('filter-contracts-', '');
                updateContractsList(type);
            });
        });

        // Initialize with open contracts
        updateContractsList('open');
    }
    // --- END UPDATED FUNCTION ---

    // Setup notification toggles
    function setupNotificationToggles() {
        const toggles = document.querySelectorAll('.y-c-toggle-switch input');
        toggles.forEach(toggle => {
            toggle.addEventListener('change', function () {
                const notificationType = this.closest('.y-c-management-item').querySelector('h4').textContent.trim();
                const status = this.checked ? 'تم تفعيل' : 'تم إيقاف';
                console.log(`${status} ${notificationType}`);
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