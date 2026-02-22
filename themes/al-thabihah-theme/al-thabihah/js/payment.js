// Al Thabihah/js/payment.js

document.addEventListener('DOMContentLoaded', function () {

    // 1. Render Order Summary from Cart
    renderOrderSummary();

    // 2. Handle Payment Method Toggle
    setupPaymentMethodToggle();

    // 3. Handle Complete Order
    setupCompleteOrder();

    // 4. Handle Create Account Toggle
    setupCreateAccountToggle();
});

function setupCreateAccountToggle() {
    const header = document.getElementById('create-account-toggle');
    const content = document.getElementById('create-account-password');
    const passwordInput = content ? content.querySelector('input') : null;
    const toggleIcon = content ? content.querySelector('.y-c-password-toggle') : null;

    if (header && content) {
        header.addEventListener('click', function () {
            const isVisible = content.style.display !== 'none';
            content.style.display = isVisible ? 'none' : 'block';
            this.classList.toggle('active', !isVisible);
            
            // If hidden, clear value (optional, depends on requirement)
            if (isVisible && passwordInput) {
                passwordInput.value = '';
            }
        });
    }

    if (toggleIcon && passwordInput) {
        toggleIcon.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle icon class
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }
}

function renderOrderSummary() {
    const container = document.getElementById('summary-items-container');
    const subtotalEl = document.getElementById('summary-subtotal');
    const taxEl = document.getElementById('summary-tax');
    const deliveryEl = document.getElementById('summary-delivery');
    const totalEl = document.getElementById('summary-total');

    if (!window.productUtils) {
        console.error("Product Utils not found");
        return;
    }

    const cart = window.productUtils.cart;

    // If cart empty
    if (!cart || cart.length === 0) {
        if (container) container.innerHTML = '<p class="y-u-text-center">السلة فارغة</p>';
        return;
    }

    let itemsHTML = '';
    cart.forEach(item => {
        // Static options to match the design
        const optionsHTML = `
            <div class="y-c-summary-options">
                <span class="y-c-summary-option">تقطيع ثلاجة</span>
                <span class="y-c-summary-option">أكياس فاكيوم</span>
            </div>
        `;

        itemsHTML += `
            <div class="y-c-summary-item">
                <div class="y-c-summary-image-wrapper">
                    <img src="${item.image}" alt="${item.name}" class="y-c-summary-img">
                    <span class="y-c-summary-qty-badge">${item.quantity}</span>
                </div>
                <div class="y-c-summary-details">
                    <div class="y-c-summary-header">
                        <span class="y-c-summary-name">${item.name}</span>
                        <div class="y-c-summary-price">
                             <span>${item.price}</span>
                             <img src="/assets/coin.png" class="y-c-coin-icon-small">
                        </div>
                    </div>
                    ${optionsHTML}
                </div>
            </div>
        `;
    });

    if (container) container.innerHTML = itemsHTML;

    // Calculate Totals
    const subtotal = window.productUtils.getCartTotal();
    const tax = Math.round(subtotal * 0.15);
    const delivery = 50; // Fixed delivery fee

    const total = subtotal + tax + delivery;

    if (subtotalEl) subtotalEl.textContent = subtotal;
    if (taxEl) taxEl.textContent = tax;
    if (deliveryEl) deliveryEl.textContent = delivery;
    if (totalEl) totalEl.textContent = total;
}

function setupPaymentMethodToggle() {
    const radios = document.querySelectorAll('input[name="payment_method"]');
    const cardDetailsForm = document.getElementById('card-details-form');
    const options = document.querySelectorAll('.y-c-payment-option');

    radios.forEach(radio => {
        radio.addEventListener('change', function () {
            // Update visual selection state
            options.forEach(opt => opt.classList.remove('selected'));
            this.closest('.y-c-payment-option').classList.add('selected');

            // Toggle Card Form
            if (this.value === 'card') {
                if (cardDetailsForm) cardDetailsForm.classList.add('active');
            } else {
                if (cardDetailsForm) cardDetailsForm.classList.remove('active');
            }
        });
    });

    // Trigger initial state
    const checked = document.querySelector('input[name="payment_method"]:checked');
    if (checked && checked.value === 'card') {
        if (cardDetailsForm) cardDetailsForm.classList.add('active');
    }
}

function setupCompleteOrder() {
    const btn = document.getElementById('complete-order-btn');
    if (!btn) return;

    btn.addEventListener('click', function () {
        const formsContainer = document.querySelector('.y-c-payment-forms-col');
        
        if (window.validationUtils && window.validationUtils.validateContainer(formsContainer)) {
             // Show success popup if valid
            showSuccessPopup();
        } else {
             if (window.productUtils) window.productUtils.showNotification('يرجى ملء جميع الحقول المطلوبة بشكل صحيح', 'warning');
        }
    });
}

function showSuccessPopup() {
    const overlay = document.createElement('div');
    overlay.className = 'y-c-popup-overlay active';

    // Structure matching the design: 
    // - Icon circle container
    // - Check icon
    // - Text message
    overlay.innerHTML = `
        <div class="y-c-popup-content y-c-success-popup-content">
            <div class="y-c-success-icon-wrapper">
                <i class="fas fa-check y-c-success-icon"></i>
            </div>
            <h2 class="y-c-success-title">تم تسجيل طلبك بنجاح</h2>
            
            <!-- Hidden trigger to close/redirect after a delay or click -->
             <button class="y-c-outline-btn" style="margin-top: 20px;" onclick="window.location.href='/templates/home/layout.html'">العودة للرئيسية</button>
        </div>
    `;
    document.body.appendChild(overlay);

    // Clear cart
    if (window.productUtils) window.productUtils.clearCart();
}