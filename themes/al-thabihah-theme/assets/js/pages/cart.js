document.addEventListener('DOMContentLoaded', function () {
    const cartForm = document.querySelector('#cart-content-wrapper');
    if (!cartForm) return;

    cartForm.addEventListener('click', function (event) {
        const button = event.target.closest('.y-c-qty-btn');
        if (!button) return;
        event.preventDefault();

        const selector = button.closest('.y-c-quantity-selector');
        const input = selector ? selector.querySelector('input[type="number"]') : null;
        if (!input) return;

        let value = parseInt(input.value || '1', 10);
        if (button.classList.contains('y-btn-increase')) {
            value += 1;
        } else if (button.classList.contains('y-btn-decrease')) {
            value = Math.max(1, value - 1);
        }
        input.value = value;

        // WooCommerce requires update_cart=1 in POST when updating quantities (form.submit() does not send button name/value)
        var updateInput = cartForm.querySelector('input[name="update_cart"]');
        if (!updateInput) {
            updateInput = document.createElement('input');
            updateInput.type = 'hidden';
            updateInput.name = 'update_cart';
            cartForm.appendChild(updateInput);
        }
        updateInput.value = '1';

        cartForm.submit();
    });
});
