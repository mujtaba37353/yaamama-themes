document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    const currentCategory = params.get('product_cat') || 'all';
    const categoryTitles = {
        all: 'جميع المنتجات',
        cuts: 'لحوم بالكيلو',
        minced: 'مفروم',
        naemi: 'نعيمي',
        tays: 'تيس كشميري',
        ejel: 'عجل',
        bbq: 'مجهز للشواء',
        offers: 'العروض'
    };

    const titleElement = document.getElementById('products-section-title');
    if (titleElement) {
        titleElement.textContent = categoryTitles[currentCategory] || 'جميع المنتجات';
    }

    const categoryCards = document.querySelectorAll('.y-c-store-category-card');
    categoryCards.forEach(card => {
        card.classList.toggle('active', card.dataset.category === currentCategory);
    });

    const triggerBtn = document.getElementById('category-menu-trigger');
    const closeBtn = document.getElementById('category-menu-close');
    const sidebar = document.getElementById('category-grid');
    const overlay = document.getElementById('category-overlay');

    if (!triggerBtn || !sidebar || !overlay) {
        return;
    }

    function openSidebar() {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    triggerBtn.addEventListener('click', openSidebar);
    if (closeBtn) {
        closeBtn.addEventListener('click', closeSidebar);
    }
    overlay.addEventListener('click', closeSidebar);
});
