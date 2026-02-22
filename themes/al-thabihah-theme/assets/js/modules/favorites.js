(function () {
    const storageKey = 'favorites';

    function getFavorites() {
        const stored = localStorage.getItem(storageKey);
        return stored ? JSON.parse(stored) : [];
    }

    function setFavorites(list) {
        localStorage.setItem(storageKey, JSON.stringify(list));
    }

    function toggleFavorite(productId) {
        const favorites = getFavorites();
        const index = favorites.indexOf(productId);
        let isFavorite = false;
        if (index === -1) {
            favorites.push(productId);
            isFavorite = true;
        } else {
            favorites.splice(index, 1);
        }
        setFavorites(favorites);
        document.dispatchEvent(new CustomEvent('favoritesUpdated', { detail: { favorites } }));
        return isFavorite;
    }

    function updateButtonState(button, isFavorite) {
        const icon = button.querySelector('i');
        if (!icon) return;
        if (isFavorite) {
            icon.className = 'fas fa-heart';
            button.classList.add('active');
        } else {
            icon.className = 'far fa-heart';
            button.classList.remove('active');
        }
    }

    function syncButtons() {
        const favorites = getFavorites();
        document.querySelectorAll('.y-c-favorite-btn').forEach(button => {
            const id = parseInt(button.dataset.productId || '0', 10);
            updateButtonState(button, favorites.includes(id));
        });
    }

    document.addEventListener('click', function (event) {
        const button = event.target.closest('.y-c-favorite-btn');
        if (!button) return;
        event.preventDefault();
        const productId = parseInt(button.dataset.productId || '0', 10);
        if (!productId) return;
        const isFavorite = toggleFavorite(productId);
        updateButtonState(button, isFavorite);
    });

    document.addEventListener('DOMContentLoaded', function () {
        syncButtons();
    });

    document.addEventListener('favoritesUpdated', function () {
        syncButtons();
    });

    window.favoriteUtils = {
        getFavorites
    };
})();
