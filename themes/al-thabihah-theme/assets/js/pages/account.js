document.addEventListener('DOMContentLoaded', function () {
    const ordersLink = document.getElementById('orders-link');
    const accountDetailsLink = document.getElementById('account-details-link');
    const addressLink = document.getElementById('address-link');
    const favoritesLink = document.getElementById('favorites-link');

    const accountDetailsContent = document.getElementById('account-details-content');
    const ordersContent = document.getElementById('orders-content');
    const addressContent = document.getElementById('address-content');
    const favoritesContent = document.getElementById('favorites-content');

    function setActiveTab(targetSection, activeLink) {
        const allLinks = document.querySelectorAll('.y-c-account-nav .y-c-nav-tab');
        allLinks.forEach(link => link.classList.remove('y-c-active'));

        const allSections = document.querySelectorAll('.y-c-content-section');
        allSections.forEach(section => section.classList.remove('active'));

        if (activeLink) {
            activeLink.classList.add('y-c-active');
        }
        if (targetSection) {
            targetSection.classList.add('active');
        }
    }

    function setupAccountDetailsLogic() {
        const profileForm = document.querySelector('.y-c-profile-form');
        if (!profileForm) return;

        profileForm.addEventListener('submit', function (e) {
            if (window.validationUtils && !window.validationUtils.validateContainer(profileForm)) {
                e.preventDefault();
            }
        });

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

    function setupAddressForm() {
        const displayView = document.getElementById('address-display-view');
        const editForm = document.getElementById('address-edit-form');
        const editBtn = document.getElementById('edit-address-btn');
        const cancelBtn = document.getElementById('cancel-edit-btn');
        const addBtn = document.getElementById('add-address-btn');

        if (!editBtn || !displayView || !editForm) return;

        editBtn.addEventListener('click', function (e) {
            e.preventDefault();
            displayView.style.display = 'none';
            editForm.style.display = 'block';
        });

        if (addBtn) {
            addBtn.addEventListener('click', function (e) {
                e.preventDefault();
                editForm.reset();
                const countryInput = editForm.querySelector('[name="country"]');
                if (countryInput) {
                    countryInput.value = 'المملكة العربية السعودية';
                }
                displayView.style.display = 'none';
                editForm.style.display = 'block';
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', function () {
                editForm.style.display = 'none';
                displayView.style.display = 'block';
            });
        }

        editForm.addEventListener('submit', function (e) {
            if (window.validationUtils && !window.validationUtils.validateContainer(this)) {
                e.preventDefault();
            }
        });
    }

    function setupOrdersLogic() {
        const ordersList = document.getElementById('y-v-orders-list');
        const orderDetails = document.getElementById('y-v-order-details');
        const detailBtns = document.querySelectorAll('.y-c-btn-details');
        const backBtn = document.getElementById('y-btn-back-to-orders');

        if (!ordersList || !orderDetails) return;

        const detailViews = orderDetails.querySelectorAll('.y-c-order-details');

        detailBtns.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-order-id');
                detailViews.forEach(view => {
                    view.style.display = view.getAttribute('data-order-id') === targetId ? 'block' : 'none';
                });
                ordersList.classList.add('y-u-hidden');
                orderDetails.classList.remove('y-u-hidden');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });

        if (backBtn) {
            backBtn.addEventListener('click', function (e) {
                e.preventDefault();
                orderDetails.classList.add('y-u-hidden');
                ordersList.classList.remove('y-u-hidden');
                detailViews.forEach(view => {
                    view.style.display = 'none';
                });
            });
        }
    }

    function setupFavorites() {
        const favoritesGrid = document.getElementById('favorites-grid');
        const emptyState = document.getElementById('favorites-empty');
        if (!favoritesGrid) return;

        const favorites = window.favoriteUtils ? window.favoriteUtils.getFavorites() : [];
        var baseUrl = (window.alThabihahData && window.alThabihahData.favoritesProductsUrl) ? window.alThabihahData.favoritesProductsUrl : (window.location.origin + '/wp-json/al-thabihah/v1/favorites-products');
        var apiUrl = baseUrl + (baseUrl.indexOf('?') >= 0 ? '&' : '?') + 'ids=' + favorites.join(',');
        if (favorites.length === 0) {
            if (emptyState) emptyState.style.display = 'flex';
            favoritesGrid.style.display = 'none';
            favoritesGrid.innerHTML = '';
            return;
        }
        if (emptyState) emptyState.style.display = 'none';
        favoritesGrid.style.display = 'grid';
        fetch(apiUrl)
            .then(function (response) { return response.json(); })
            .then(function (products) {
                if (!Array.isArray(products) || products.length === 0) {
                    favoritesGrid.innerHTML = '';
                    if (emptyState) { emptyState.style.display = 'flex'; favoritesGrid.style.display = 'none'; }
                    return;
                }
                var coinUrl = window.alThabihahData && window.alThabihahData.assetsUrl ? window.alThabihahData.assetsUrl + '/coin.png' : '';
                favoritesGrid.innerHTML = products.map(function (product) {
                    var price = product.price !== undefined ? String(product.price) : '';
                    var image = product.image || '';
                    var addToCartUrl = product.add_to_cart_url || product.permalink || '#';
                    var name = (product.name || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    return '<li class="y-c-product-card" data-y="product-card-' + product.id + '">' +
                        '<button class="y-c-favorite-btn active" data-product-id="' + product.id + '" data-y="product-favorite-' + product.id + '">' +
                        '<i class="fas fa-heart" data-y="favorite-icon-' + product.id + '"></i></button>' +
                        '<a href="' + (product.permalink || '#') + '" class="y-c-card-link" data-y="product-link-' + product.id + '">' +
                        '<div class="y-c-product-image-container" data-y="product-image-container-' + product.id + '">' +
                        '<img src="' + image + '" alt="' + name + '" class="y-c-product-image" loading="lazy" data-y="product-image-' + product.id + '">' +
                        '</div></a>' +
                        '<div class="y-c-product-info" data-y="product-info-' + product.id + '">' +
                        '<h3 class="y-c-product-title" title="' + name + '" data-y="product-title-' + product.id + '">' + name + '</h3>' +
                        '<div class="y-c-product-price" data-y="product-price-container-' + product.id + '">' +
                        '<span class="y-c-price-amount" data-y="product-price-amount-' + product.id + '">' + price + '</span>' +
                        (coinUrl ? '<img src="' + coinUrl + '" class="y-c-coin-icon" alt="">' : '') +
                        '</div>' +
                        '<a href="' + addToCartUrl + '" class="y-c-outline-btn y-c-add-to-cart add_to_cart_button ajax_add_to_cart" data-product_id="' + product.id + '" data-quantity="1" data-y="product-add-to-cart-' + product.id + '">' +
                        'اضف للسلة <i class="fas fa-shopping-cart" data-y="cart-icon-' + product.id + '"></i></a>' +
                        '</div></li>';
                }).join('');
            })
            .catch(function () {
                favoritesGrid.innerHTML = '';
                if (emptyState) { emptyState.style.display = 'flex'; favoritesGrid.style.display = 'none'; }
            });
    }

    function bindNav(link, content, setupFn) {
        if (!link || !content) return;
        link.addEventListener('click', function (e) {
            e.preventDefault();
            setActiveTab(content, link);
            if (typeof setupFn === 'function') {
                setupFn();
            }
        });
    }

    bindNav(accountDetailsLink, accountDetailsContent, setupAccountDetailsLogic);
    bindNav(ordersLink, ordersContent, setupOrdersLogic);
    bindNav(addressLink, addressContent, setupAddressForm);
    bindNav(favoritesLink, favoritesContent, setupFavorites);

    if (accountDetailsLink && accountDetailsContent) {
        setActiveTab(accountDetailsContent, accountDetailsLink);
        setupAccountDetailsLogic();
    }

    document.addEventListener('favoritesUpdated', function () {
        if (favoritesContent && favoritesContent.classList.contains('active')) {
            setupFavorites();
        }
    });
});
