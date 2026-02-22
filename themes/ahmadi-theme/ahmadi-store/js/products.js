'use strict';

const themeConfig = window.ahmadiTheme || {};
const assetBaseUrl = themeConfig.componentBaseUrl ? `${themeConfig.componentBaseUrl}/assets/` : '../../assets/';
const productUrl = themeConfig.productUrl || '../product-single/layout.html';

const products = [
    { id: 'SKU1701', name: 'أرز بسمتي هندي', price: '28.75 ر.س', image: '../../assets/image 50.png', category: 'grains', stock: 20 },
    { id: 'SKU1702', name: 'لحم غنم طازج', price: '89.99 ر.س', image: '../../assets/image 48.png', category: 'meat', stock: 15 },
    { id: 'SKU1703', name: 'سمك سلمون طازج', price: '65.25 ر.س', image: '../../assets/image 46.png', category: 'fish', stock: 4 },
    { id: 'SKU1704', name: 'عسل طبيعي', price: '55.00 ر.س', image: '../../assets/image 50.png', category: 'oils', stock: 30 },
    { id: 'SKU1705', name: 'شاي أخضر', price: '22.99 ر.س', image: '../../assets/image 49.png', category: 'coffee', stock: 0 },
    { id: 'SKU1706', name: 'عدس أحمر', price: '18.50 ر.س', image: '../../assets/image 47.png', category: 'grains', stock: 50 },
    { id: 'SKU1707', name: 'دجاج طازج', price: '35.75 ر.س', image: '../../assets/image 45.png', category: 'meat', stock: 8 },
    { id: 'SKU1708', name: 'جمبري طازج', price: '78.99 ر.س', image: '../../assets/image 43.png', category: 'fish', stock: 2 },
    { id: 'SKU1709', name: 'حليب الصافي', price: '50.00 ر.س', image: '../../assets/image 42.png', category: 'dairy', stock: 25 },
    { id: 'SKU1710', name: 'زيت زيتون', price: '45.50 ر.س', image: '../../assets/image 50.png', category: 'oils', stock: 18 },
    { id: 'SKU1711', name: 'قهوة عربية', price: '35.00 ر.س', image: '../../assets/image 49.png', category: 'coffee', stock: 12 },
    { id: 'SKU1712', name: 'فاصوليا بيضاء', price: '15.75 ر.س', image: '../../assets/image 47.png', category: 'grains', stock: 40 },
    { id: 'SKU1713', name: 'لحم بقر طازج', price: '95.25 ر.س', image: '../../assets/image 48.png', category: 'meat', stock: 10 },
    { id: 'SKU1714', name: 'سمك هامور', price: '70.99 ر.س', image: '../../assets/image 43.png', category: 'fish', stock: 5 },
    { id: 'SKU1715', name: 'عسل سدر', price: '120.00 ر.س', image: '../../assets/image 50.png', category: 'oils', stock: 7 },
    { id: 'SKU1716', name: 'شاي أحمر', price: '18.50 ر.س', image: '../../assets/image 49.png', category: 'coffee', stock: 30 },

    { id: 'SKU1701', name: 'أرز بسمتي هندي', price: '28.75 ر.س', image: '../../assets/image 50.png', category: 'grains', stock: 20 },
    { id: 'SKU1702', name: 'لحم غنم طازج', price: '89.99 ر.س', image: '../../assets/image 48.png', category: 'meat', stock: 15 },
    { name: 'سمك سلمون طازج', price: '65.25 ر.س', image: '../../assets/image 46.png', category: 'fish' },
    { name: 'عسل طبيعي', price: '55.00 ر.س', image: '../../assets/image 50.png', category: 'oils' },
    { name: 'شاي أخضر', price: '22.99 ر.س', image: '../../assets/image 49.png', category: 'coffee' },
    { name: 'عدس أحمر', price: '18.50 ر.س', image: '../../assets/image 47.png', category: 'grains' },
    { name: 'دجاج طازج', price: '35.75 ر.س', image: '../../assets/image 45.png', category: 'meat' },
    { name: 'جمبري طازج', price: '70.99 ر.س', image: '../../assets/image 43.png', category: 'fish' },
    { name: 'أرز بسمتي هندي', price: '28.75 ر.س', image: '../../assets/image 50.png', category: 'grains' },
    { name: 'لحم غنم طازج', price: '89.99 ر.س', image: '../../assets/image 48.png', category: 'meat' },
    { name: 'سمك سلمون طازج', price: '65.25 ر.س', image: '../../assets/image 46.png', category: 'fish' },
    { name: 'عسل طبيعي', price: '55.00 ر.س', image: '../../assets/image 50.png', category: 'oils' },
    { name: 'شاي أخضر', price: '22.99 ر.س', image: '../../assets/image 49.png', category: 'coffee' },
    { name: 'عدس أحمر', price: '18.50 ر.س', image: '../../assets/image 47.png', category: 'grains' },
    { name: 'دجاج طازج', price: '35.75 ر.س', image: '../../assets/image 45.png', category: 'meat' },
    { name: 'جمبري طازج', price: '78.99 ر.س', image: '../../assets/image 43.png', category: 'fish' },
    { name: 'أرز بسمتي هندي', price: '28.75 ر.س', image: '../../assets/image 50.png', category: 'grains' },
    { name: 'لحم غنم طازج', price: '89.99 ر.س', image: '../../assets/image 48.png', category: 'meat' },
    { name: 'سمك سلمون طازج', price: '65.25 ر.س', image: '../../assets/image 46.png', category: 'fish' },
    { name: 'عسل طبيعي', price: '55.00 ر.س', image: '../../assets/image 50.png', category: 'oils' },
    { name: 'شاي أخضر', price: '22.99 ر.س', image: '../../assets/image 49.png', category: 'coffee' },
    { name: 'عدس أحمر', price: '18.50 ر.س', image: '../../assets/image 47.png', category: 'grains' },
    { name: 'دجاج طازج', price: '35.75 ر.س', image: '../../assets/image 45.png', category: 'meat' },
    { name: 'جمبري طازج', price: '70.99 ر.س', image: '../../assets/image 43.png', category: 'fish' },
    { name: 'أرز بسمتي هندي', price: '28.75 ر.س', image: '../../assets/image 50.png', category: 'grains' },
    { name: 'لحم غنم طازج', price: '89.99 ر.س', image: '../../assets/image 48.png', category: 'meat' },
    { name: 'سمك سلمون طازج', price: '65.25 ر.س', image: '../../assets/image 46.png', category: 'fish' },
    { name: 'عسل طبيعي', price: '55.00 ر.س', image: '../../assets/image 50.png', category: 'oils' },
    { name: 'شاي أخضر', price: '22.99 ر.س', image: '../../assets/image 49.png', category: 'coffee' },
    { name: 'عدس أحمر', price: '18.50 ر.س', image: '../../assets/image 47.png', category: 'grains' },
    { name: 'دجاج طازج', price: '35.75 ر.س', image: '../../assets/image 45.png', category: 'meat' },
    { name: 'جمبري طازج', price: '78.99 ر.س', image: '../../assets/image 43.png', category: 'fish' },
    { name: 'أرز بسمتي هندي', price: '28.75 ر.س', image: '../../assets/image 50.png', category: 'grains' },
    { name: 'لحم غنم طازج', price: '89.99 ر.س', image: '../../assets/image 48.png', category: 'meat' },
    { name: 'سمك سلمون طازج', price: '65.25 ر.س', image: '../../assets/image 46.png', category: 'fish' },
    { name: 'عسل طبيعي', price: '55.00 ر.س', image: '../../assets/image 50.png', category: 'oils' },
    { name: 'شاي أخضر', price: '22.99 ر.س', image: '../../assets/image 49.png', category: 'coffee' },
    { name: 'عدس أحمر', price: '18.50 ر.س', image: '../../assets/image 47.png', category: 'grains' },
    { name: 'دجاج طازج', price: '35.75 ر.س', image: '../../assets/image 45.png', category: 'meat' },
    { name: 'جمبري طازج', price: '70.99 ر.س', image: '../../assets/image 43.png', category: 'fish' },
    { name: 'أرز بسمتي هندي', price: '28.75 ر.س', image: '../../assets/image 50.png', category: 'grains' },
    { name: 'لحم غنم طازج', price: '89.99 ر.س', image: '../../assets/image 48.png', category: 'meat' },
    { name: 'سمك سلمون طازج', price: '65.25 ر.س', image: '../../assets/image 46.png', category: 'fish' },
    { name: 'عسل طبيعي', price: '55.00 ر.س', image: '../../assets/image 50.png', category: 'oils' },
    { name: 'شاي أخضر', price: '22.99 ر.س', image: '../../assets/image 49.png', category: 'coffee' },
    { name: 'عدس أحمر', price: '18.50 ر.س', image: '../../assets/image 47.png', category: 'grains' },
    { name: 'دجاج طازج', price: '35.75 ر.س', image: '../../assets/image 45.png', category: 'meat' },
    { name: 'جمبري طازج', price: '78.99 ر.س', image: '../../assets/image 43.png', category: 'fish' },
    { name: 'أرز بسمتي هندي', price: '28.75 ر.س', image: '../../assets/image 50.png', category: 'grains' },
    { name: 'لحم غنم طازج', price: '89.99 ر.س', image: '../../assets/image 48.png', category: 'meat' },
    { name: 'سمك سلمون طازج', price: '65.25 ر.س', image: '../../assets/image 46.png', category: 'fish' },
    { name: 'عسل طبيعي', price: '55.00 ر.س', image: '../../assets/image 50.png', category: 'oils' },
    { name: 'شاي أخضر', price: '22.99 ر.س', image: '../../assets/image 49.png', category: 'coffee' },
    { name: 'عدس أحمر', price: '18.50 ر.س', image: '../../assets/image 47.png', category: 'grains' },
    { name: 'دجاج طازج', price: '35.75 ر.س', image: '../../assets/image 45.png', category: 'meat' },
    { name: 'جمبري طازج', price: '70.99 ر.س', image: '../../assets/image 43.png', category: 'fish' },
    { name: 'أرز بسمتي هندي', price: '28.75 ر.س', image: '../../assets/image 50.png', category: 'grains' },
    { name: 'لحم غنم طازج', price: '89.99 ر.س', image: '../../assets/image 48.png', category: 'meat' },
    { name: 'سمك سلمون طازج', price: '65.25 ر.س', image: '../../assets/image 46.png', category: 'fish' },
    { name: 'عسل طبيعي', price: '55.00 ر.س', image: '../../assets/image 50.png', category: 'oils' },
    { name: 'شاي أخضر', price: '22.99 ر.س', image: '../../assets/image 49.png', category: 'coffee' },
    { name: 'عدس أحمر', price: '18.50 ر.س', image: '../../assets/image 47.png', category: 'grains' },
    { name: 'دجاج طازج', price: '35.75 ر.س', image: '../../assets/image 45.png', category: 'meat' },
    { name: 'جمبري طازج', price: '78.99 ر.س', image: '../../assets/image 43.png', category: 'fish' },
    { name: 'أرز بسمتي هندي', price: '28.75 ر.س', image: '../../assets/image 50.png', category: 'grains' },
    { name: 'لحم غنم طازج', price: '89.99 ر.س', image: '../../assets/image 48.png', category: 'meat' },
    { name: 'سمك سلمون طازج', price: '65.25 ر.س', image: '../../assets/image 46.png', category: 'fish' },
    { name: 'عسل طبيعي', price: '55.00 ر.س', image: '../../assets/image 50.png', category: 'oils' },
    { name: 'شاي أخضر', price: '22.99 ر.س', image: '../../assets/image 49.png', category: 'coffee' },
    { name: 'عدس أحمر', price: '18.50 ر.س', image: '../../assets/image 47.png', category: 'grains' },
    { name: 'دجاج طازج', price: '35.75 ر.س', image: '../../assets/image 45.png', category: 'meat' },
    { name: 'جمبري طازج', price: '70.99 ر.س', image: '../../assets/image 43.png', category: 'fish' },
    { name: 'أرز بسمتي هندي', price: '28.75 ر.س', image: '../../assets/image 50.png', category: 'grains' },
    { name: 'لحم غنم طازج', price: '89.99 ر.س', image: '../../assets/image 48.png', category: 'meat' },
    { name: 'سمك سلمون طازج', price: '65.25 ر.س', image: '../../assets/image 46.png', category: 'fish' },
    { name: 'عسل طبيعي', price: '55.00 ر.س', image: '../../assets/image 50.png', category: 'oils' },
    { name: 'شاي أخضر', price: '22.99 ر.س', image: '../../assets/image 49.png', category: 'coffee' },
    { name: 'عدس أحمر', price: '18.50 ر.س', image: '../../assets/image 47.png', category: 'grains' },
    { name: 'دجاج طازج', price: '35.75 ر.س', image: '../../assets/image 45.png', category: 'meat' },
    { name: 'جمبري طازج', price: '78.99 ر.س', image: '../../assets/image 43.png', category: 'fish' },
    { name: 'أرز بسمتي هندي', price: '28.75 ر.س', image: '../../assets/image 50.png', category: 'grains' },
    { name: 'لحم غنم طازج', price: '89.99 ر.س', image: '../../assets/image 48.png', category: 'meat' },
    { name: 'سمك سلمون طازج', price: '65.25 ر.س', image: '../../assets/image 46.png', category: 'fish' },
    { name: 'عسل طبيعي', price: '55.00 ر.س', image: '../../assets/image 50.png', category: 'oils' },
    { name: 'شاي أخضر', price: '22.99 ر.س', image: '../../assets/image 49.png', category: 'coffee' },
    { name: 'عدس أحمر', price: '18.50 ر.س', image: '../../assets/image 47.png', category: 'grains' },
    { name: 'دجاج طازج', price: '35.75 ر.س', image: '../../assets/image 45.png', category: 'meat' },
    { name: 'جمبري طازج', price: '70.99 ر.س', image: '../../assets/image 43.png', category: 'fish' },
    { name: 'أرز بسمتي هندي', price: '28.75 ر.س', image: '../../assets/image 50.png', category: 'grains' },
    { name: 'لحم غنم طازج', price: '89.99 ر.س', image: '../../assets/image 48.png', category: 'meat' },
    { name: 'سمك سلمون طازج', price: '65.25 ر.س', image: '../../assets/image 46.png', category: 'fish' },
    { name: 'عسل طبيعي', price: '55.00 ر.س', image: '../../assets/image 50.png', category: 'oils' },
    { name: 'شاي أخضر', price: '22.99 ر.س', image: '../../assets/image 49.png', category: 'coffee' },
    { name: 'عدس أحمر', price: '18.50 ر.س', image: '../../assets/image 47.png', category: 'grains' },
    { name: 'دجاج طازج', price: '35.75 ر.س', image: '../../assets/image 45.png', category: 'meat' },
    { name: 'جمبري طازج', price: '78.99 ر.س', image: '../../assets/image 43.png', category: 'fish' },
    { name: 'أرز بسمتي هندي', price: '28.75 ر.س', image: '../../assets/image 50.png', category: 'grains' },
    { name: 'لحم غنم طازج', price: '89.99 ر.س', image: '../../assets/image 48.png', category: 'meat' },
    { name: 'سمك سلمون طازج', price: '65.25 ر.س', image: '../../assets/image 46.png', category: 'fish' },
    { name: 'عسل طبيعي', price: '55.00 ر.س', image: '../../assets/image 50.png', category: 'oils' },
    { name: 'شاي أخضر', price: '22.99 ر.س', image: '../../assets/image 49.png', category: 'coffee' },
    { name: 'عدس أحمر', price: '18.50 ر.س', image: '../../assets/image 47.png', category: 'grains' },
    { name: 'دجاج طازج', price: '35.75 ر.س', image: '../../assets/image 45.png', category: 'meat' },
    { name: 'جمبري طازج', price: '70.99 ر.س', image: '../../assets/image 43.png', category: 'fish' },
    { name: 'أرز بسمتي هندي', price: '28.75 ر.س', image: '../../assets/image 50.png', category: 'grains' },
    { name: 'لحم غنم طازج', price: '89.99 ر.س', image: '../../assets/image 48.png', category: 'meat' },
    { name: 'سمك سلمون طازج', price: '65.25 ر.س', image: '../../assets/image 46.png', category: 'fish' },
    { name: 'عسل طبيعي', price: '55.00 ر.س', image: '../../assets/image 50.png', category: 'oils' },
    { name: 'شاي أخضر', price: '22.99 ر.س', image: '../../assets/image 49.png', category: 'coffee' },
    { name: 'عدس أحمر', price: '18.50 ر.س', image: '../../assets/image 47.png', category: 'grains' },
    { name: 'دجاج طازج', price: '35.75 ر.س', image: '../../assets/image 45.png', category: 'meat' },
    { name: 'جمبري طازج', price: '78.99 ر.س', image: '../../assets/image 43.png', category: 'fish' },
    { name: 'أرز بسمتي هندي', price: '28.75 ر.س', image: '../../assets/image 50.png', category: 'grains' },
    { name: 'لحم غنم طازج', price: '89.99 ر.س', image: '../../assets/image 48.png', category: 'meat' },
    { name: 'سمك سلمون طازج', price: '65.25 ر.س', image: '../../assets/image 46.png', category: 'fish' },
    { name: 'عسل طبيعي', price: '55.00 ر.س', image: '../../assets/image 50.png', category: 'oils' },
    { name: 'شاي أخضر', price: '22.99 ر.س', image: '../../assets/image 49.png', category: 'coffee' },
    { name: 'عدس أحمر', price: '18.50 ر.س', image: '../../assets/image 47.png', category: 'grains' },
    { name: 'دجاج طازج', price: '35.75 ر.س', image: '../../assets/image 45.png', category: 'meat' },
    { name: 'جمبري طازج', price: '70.99 ر.س', image: '../../assets/image 43.png', category: 'fish' },
];

/**
 * Generates product cards and adds them to the grid.
 * @param {number} page The current page number.
 * @param {number} productsPerPage The number of products to display per page.
 */
function generateProducts(page = 1, productsPerPage = 10) {
    const productsGrid = document.querySelector('.y-c-products-grid');
    if (!productsGrid) return;

    const startIndex = (page - 1) * productsPerPage;
    const endIndex = startIndex + productsPerPage;
    const paginatedProducts = products.slice(startIndex, endIndex);

    productsGrid.innerHTML = '';
    paginatedProducts.forEach(product => {
        const productCard = createProductCard(product);
        productsGrid.appendChild(productCard);
    });
}

/**
 * Creates a product card element.
 * @param {object} product The product data.
 * @returns {HTMLElement} The product card element.
 */
function createProductCard(product) {
    const card = document.createElement('li');
    card.className = 'y-c-product-card';
    const imageSrc = product.image
        ? product.image.replace(/^(\.\.\/)+assets\//, assetBaseUrl)
        : '';
    card.innerHTML = `
        <a href="${productUrl}" class="y-c-product-image">
            <img src="${imageSrc}" alt="${product.name}" loading="lazy" > 
           <span class="y-c-favorite-icon"><i class="far fa-heart"></i></span>
        </a> 
        <div class="y-c-product-info">
            <h4>${product.name}</h4>
            <div class="y-c-product-price">${product.price}</div>
            <button class="y-c-shop-now-btn" data-product="${product.name}" data-price="${product.price}">
                إضافة الى السلة
            </button>
        </div>
    `;

    const favoriteIcon = card.querySelector('.y-c-favorite-icon');
    favoriteIcon.addEventListener('click', (e) => {
        e.preventDefault(); // Prevent navigation to product page
        e.stopPropagation(); // Stop event bubbling
        const icon = favoriteIcon.querySelector('i');
        icon.classList.toggle('far');
        icon.classList.toggle('fas');

        const isFavorite = icon.classList.contains('fas');
        favoriteIcon.classList.toggle('y-c-active', isFavorite);
    });

    return card;
}

generateProducts();