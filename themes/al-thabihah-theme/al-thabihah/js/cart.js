// Al Thabihah/js/cart.js

document.addEventListener('DOMContentLoaded', function () {
    // Initialize Cart Page
    renderCartPage();

    // Listen for global cart updates (e.g. from header interactions)
    document.addEventListener('cartUpdated', renderCartPage);
    document.addEventListener('cartCleared', renderCartPage);
});

function renderCartPage() {
    const cartContainer = document.getElementById('cart-content-wrapper');
    const emptyMessage = document.getElementById('empty-cart-message');
    const cartItemsContainer = document.getElementById('cart-items-container');

    // Ensure productUtils is available
    if (!window.productUtils) return;

    const cart = window.productUtils.cart; // Get cart via getter

    if (cart.length === 0) {
        if (cartContainer) cartContainer.style.display = 'none';
        if (emptyMessage) emptyMessage.style.display = 'flex';
        return;
    }

    if (cartContainer) cartContainer.style.display = 'grid';
    if (emptyMessage) emptyMessage.style.display = 'none';

    // Render Items
    if (cartItemsContainer) {
        cartItemsContainer.innerHTML = '';
        cart.forEach(item => {
            cartItemsContainer.innerHTML += createCartItemHTML(item);
        });

        // Attach event listeners to new elements
        attachCartItemListeners();
    }

    // Update Summary
    updateCartSummary();
}

function createCartItemHTML(item) {
    const itemTotal = (item.price * item.quantity).toFixed(0);

    // Dummy options for display purpose to match the screenshot
    // In a real app, these would come from the item.options object
    const tagsHTML = `
        <div class="y-c-item-tags">
            <span class="y-c-item-tag ">تقطيع ثلاجة</span>
            <span class="y-c-item-tag">أكياس فاكيوم</span>
        </div>
    `;

    return `
        <div class="y-c-cart-item" data-id="${item.id}">
            
            <!-- Right Side: Image -->
            <div class="y-c-item-image">
                <a href="/templates/single-product/layout.html?id=${item.id}">
                    <img src="${item.image}" alt="${item.name}">
                </a>
            </div>

            <!-- Center: Details -->
            <div class="y-c-item-details">
                <div class="y-c-item-header">
                    <a href="/templates/single-product/layout.html?id=${item.id}" class="y-c-item-name">${item.name}</a>
                </div>
                <div class="y-c-item-price">
                    <img src="/assets/coin.png" class="y-c-coin-icon-small">
                    ${item.price}
                </div>
                ${tagsHTML}
            </div>

            <!-- Left Side: Actions & Total -->
            <div class="y-c-item-actions">
                <div class="y-c-quantity-wrapper">
                    <div class="y-c-quantity-selector" data-y="quantity-selector">
                        <button type="button" class="y-c-qty-btn y-btn-increase" data-id="${item.id}" data-action="increase">+</button>
                        <input type="number" id="qty-input" value="${item.quantity}" min="1" readonly>
                        <button type="button" class="y-c-qty-btn y-btn-decrease" data-id="${item.id}" data-action="decrease">-</button>
                    </div>
                </div>

               

                <div class="y-c-item-total">
                    <span class="y-c-total-label">المجموع : </span>
                    <span class="y-c-total-value">${itemTotal}</span>
                    <img src="/assets/coin.png" class="y-c-coin-icon-small">
                </div>
            </div>

             <button class="y-c-delete-btn" data-action="remove" data-id="${item.id}" title="حذف">
                    <i class="fas fa-times"></i>
            </button>
            
        </div>
    `;
}

function attachCartItemListeners() {
    const buttons = document.querySelectorAll('#cart-items-container button');

    buttons.forEach(btn => {
        btn.addEventListener('click', function () {
            const action = this.dataset.action;
            const id = parseInt(this.dataset.id);
            const item = window.productUtils.cart.find(i => i.id === id);

            if (!item) return;

            if (action === 'increase') {
                window.productUtils.updateCartQuantity(id, item.quantity + 1);
            } else if (action === 'decrease') {
                if (item.quantity > 1) {
                    window.productUtils.updateCartQuantity(id, item.quantity - 1);
                } else {
                    // If quantity is 1, decreasing removes it
                    window.productUtils.removeFromCart(id);
                }
            } else if (action === 'remove') {
                window.productUtils.removeFromCart(id);
            }

            // Re-render is handled by the 'cartUpdated' event listener in init
        });
    });
}

function updateCartSummary() {
    const subtotalEl = document.getElementById('summary-subtotal');
    const totalEl = document.getElementById('summary-total');
    const deliveryEl = document.getElementById('summary-delivery');
    const taxEl = document.getElementById('summary-tax');

    if (!subtotalEl || !totalEl) return;

    const subtotal = window.productUtils.getCartTotal();
    // Example Logic: Delivery is free if subtotal > 500, else 50
    const deliveryCost = subtotal > 500 ? 0 : 50;
    const deliveryText = deliveryCost === 0 ? 'مجاني' : `${deliveryCost} `;

    // Example Logic: Tax included or 0 for now as per image
    const tax = 0;

    const total = subtotal + deliveryCost + tax;

    if (subtotalEl) subtotalEl.textContent = `${subtotal} `;
    if (deliveryEl) deliveryEl.textContent = deliveryText;
    if (taxEl) taxEl.textContent = `${tax} `;
    if (totalEl) totalEl.textContent = `${total} `;

    // Styling logic for free delivery
    if (deliveryEl) {
        if (deliveryCost === 0) {
            deliveryEl.classList.add('y-c-text-success');
            deliveryEl.classList.remove('y-c-text-error');
        } else {
            deliveryEl.classList.remove('y-c-text-success');
        }
    }
}