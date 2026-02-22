document.addEventListener('DOMContentLoaded', () => {
    const favoritesGrid = document.getElementById('favorites-grid');
    const emptyFavoritesMessage = document.getElementById('empty-favorites-message');
    const FAVORITES_STORAGE_KEY = 'technosouq_favorites';

    // Define products array locally to avoid dependency issues
    const localProducts = [
        {
            id: 1,
            name: 'غسالة ملابس أوتوماتيك',
            price: '1200 ر.س',
            oldPrice: '1500 ر.س',
            image: '/assets/wash.png',
            category: 'appliances'
        },
        {
            id: 2,
            name: 'شاشة تلفزيون ذكية 55 بوصة',
            price: '2500 ر.س',
            oldPrice: '3000 ر.س',
            image: '/assets/tv.png',
            category: 'electronics'
        },
        {
            id: 3,
            name: 'مكيف هواء سبليت',
            price: '3000 ر.س',
            oldPrice: '3500 ر.س',
            image: '/assets/conditioner.png',
            category: 'appliances'
        },
        {
            id: 4,
            name: 'مكنسة كهربائية لاسلكية',
            price: '800 ر.س',
            oldPrice: '1000 ر.س',
            image: '/assets/vacup_cleaner.png',
            category: 'appliances'
        },
        {
            id: 5,
            name: 'غسالة ملابس تعبئة علوية',
            price: '1500 ر.س',
            oldPrice: '1800 ر.س',
            image: '/assets/wash.png',
            category: 'appliances'
        },
        {
            id: 6,
            name: 'تلفزيون 4K فائق الدقة',
            price: '2800 ر.س',
            oldPrice: '3200 ر.س',
            image: '/assets/tv.png',
            category: 'electronics'
        },
        {
            id: 7,
            name: 'مكيف شباك قوي',
            price: '1800 ر.س',
            image: '/assets/conditioner.png',
            category: 'appliances'
        },
        {
            id: 8,
            name: 'مكنسة روبوت ذكية',
            price: '1200 ر.س',
            image: '/assets/vacup_cleaner.png',
            category: 'appliances'
        },
        {
            id: 9,
            name: 'لابتوب محمول',
            price: '4500 ر.س',
            oldPrice: '5000 ر.س',
            image: '/assets/laptop.png',
            category: 'electronics'
        },
        {
            id: 10,
            name: 'هاتف ذكي',
            price: '2000 ر.س',
            oldPrice: '2500 ر.س',
            image: '/assets/phone.png',
            category: 'electronics'
        },
        {
            id: 11,
            name: 'ثلاجة كبيرة',
            price: '3500 ر.س',
            image: '/assets/fridge.png',
            category: 'appliances'
        },
        {
            id: 12,
            name: 'فرن ميكروويف',
            price: '600 ر.س',
            oldPrice: '750 ر.س',
            image: '/assets/microwave.png',
            category: 'appliances'
        }
    ];

    // Get favorites from localStorage
    function getFavorites() {
        const favoritesJson = localStorage.getItem(FAVORITES_STORAGE_KEY);
        return favoritesJson ? JSON.parse(favoritesJson) : [];
    }

    // Save favorites to localStorage
    function saveFavorites(favorites) {
        localStorage.setItem(FAVORITES_STORAGE_KEY, JSON.stringify(favorites));
    }

    // Check if a product is in favorites
    function isFavoriteProduct(productId) {
        return getFavorites().includes(Number(productId));
    }

    // Get products data from local array
    function getProductById(productId) {
        return localProducts.find(product => product.id === Number(productId));
    }

    // Toggle favorite status for a product
    function toggleFavorite(productId, event) {
        event.preventDefault();
        const favorites = getFavorites();
        const numericId = Number(productId);

        if (isFavoriteProduct(numericId)) {
            // Remove from favorites
            const index = favorites.indexOf(numericId);
            favorites.splice(index, 1);

            // Remove the product card from the grid with animation
            const productCard = event.target.closest('.y-c-card');
            if (productCard) {
                productCard.style.opacity = '0';
                productCard.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    productCard.remove();
                    // Check if there are any favorites left
                    if (favoritesGrid.children.length === 0) {
                        showEmptyMessage();
                    }
                }, 300);
            }
        }

        saveFavorites(favorites);
        return isFavoriteProduct(numericId);
    }

    // Create a product card
    function createProductCard(product) {
        const card = document.createElement('li');
        card.className = 'y-c-card';
        card.dataset.productId = product.id;
        card.setAttribute('data-y', 'product-card');

        const hasDiscount = product.oldPrice && product.oldPrice !== product.price;

        card.innerHTML = `
        <div class="y-c-card__top-actions" data-y="card-top-actions">
            <a href="#" class="y-c-card__favorite active" data-favorite-toggle data-y="favorite-toggle">
                <i class="fas fa-heart" data-y="favorite-icon"></i>
            </a>
            ${hasDiscount ? `<span class="y-c-card__discount" data-y="discount-badge">خصم</span>` : ''}
        </div>
        <a href="/templates/product-single/layout.html?id=${product.id}" class="y-c-card__link" data-y="product-link">
            <div class="y-c-card__image-container" data-y="image-container">
                <img src="${product.image}" alt="${product.name}" class="y-c-card__image" data-y="product-image">
            </div>
            <div class="y-c-card__body" data-y="card-body">
                <h3 class="y-c-card__title" data-y="product-title">${product.name}</h3>
                <div class="y-c-card__footer" data-y="card-footer">
                    <div class="y-c-card__price-container" data-y="price-container">
                        <span class="y-c-card__price" data-y="product-price">${product.price}</span>
                        ${hasDiscount ? `<span class="y-c-card__old-price" data-y="old-price">${product.oldPrice}</span>` : ''}
                    </div>
                </div>
            </div>
        </a>
    `;

        // Add event listener to favorite toggle
        const favoriteToggle = card.querySelector('[data-favorite-toggle]');
        favoriteToggle.addEventListener('click', (event) => {
            toggleFavorite(product.id, event);
        });

        return card;
    }

    // Show empty favorites message
    function showEmptyMessage() {
        if (emptyFavoritesMessage) {
            emptyFavoritesMessage.style.display = 'flex';
        }
        if (favoritesGrid) {
            favoritesGrid.style.display = 'none';
        }
    }

    // Render favorite products
    function renderFavorites() {
        if (!favoritesGrid) return;

        const favorites = getFavorites();

        // Show empty message if no favorites
        if (favorites.length === 0) {
            showEmptyMessage();
            return;
        }

        // Hide empty message and show grid
        if (emptyFavoritesMessage) {
            emptyFavoritesMessage.style.display = 'none';
        }
        favoritesGrid.style.display = 'grid';

        // Clear grid
        favoritesGrid.innerHTML = '';

        // Add favorite products to grid
        favorites.forEach(productId => {
            const product = getProductById(productId);
            if (product) {
                const card = createProductCard(product);
                favoritesGrid.appendChild(card);
            }
        });
    }

    // Initialize favorites page
    renderFavorites();
});