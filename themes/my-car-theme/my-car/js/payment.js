document.addEventListener('DOMContentLoaded', function () {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const cardDetails = document.querySelector('.y-c-payment-card-details');
    const stcPayDetails = document.querySelector('.y-c-stc-pay-details');
    const cardOption = document.querySelector('[data-y="payment-option-card"]');
    const stcPayOption = document.querySelector('[data-y="payment-option-stc"]');

    // --- START OF FIX ---

    // This is the text in the summary box
    const paymentMethodDisplay = document.getElementById('payment-method-display');

    // Dropdown elements
    const dropdown = document.querySelector('.y-c-payment-dropdown');
    const dropdownToggle = document.querySelector('.y-c-dropdown-toggle-payment');
    const dropdownMenu = document.querySelector('.y-c-payment-dropdown-menu');
    const dropdownItems = document.querySelectorAll('.y-c-payment-dropdown-item');

    // This is the text inside the dropdown button
    const dropdownSelectedText = dropdownToggle ? dropdownToggle.querySelector('span') : null;

    // Toggle dropdown on click
    if (dropdownToggle && dropdownMenu) {
        dropdownToggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            // This toggles the .show class on the menu, which matches y-payment.css
            dropdownMenu.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (dropdownMenu && dropdownMenu.classList.contains('show') && !e.target.closest('.y-c-payment-dropdown')) {
                dropdownMenu.classList.remove('show');
            }
        });
    }

    // Handle dropdown item selection
    if (dropdownItems && dropdownMenu && dropdownSelectedText) {
        dropdownItems.forEach(item => {
            item.addEventListener('click', function () {
                const method = this.dataset.method;
                const methodValue = method === 'card' ? 'online' : 'stc';
                const text = this.textContent.trim(); // Get text from button

                // Update radio buttons
                const radio = document.querySelector(`input[value="${methodValue}"]`);
                if (radio) {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change')); // Trigger the change event
                }

                // Update dropdown UI
                const allDropdownItems = document.querySelectorAll('.y-c-payment-dropdown-item');
                allDropdownItems.forEach(i => i.classList.remove('active'));
                this.classList.add('active');

                // Update the text inside the toggle button
                dropdownSelectedText.textContent = text;

                // Close dropdown
                dropdownMenu.classList.remove('show');
            });
        });
    }

    // --- END OF FIX ---

    paymentMethods.forEach(radio => {
        radio.addEventListener('change', function () {
            let selectedText = 'البطاقة الائتمانية'; // Default

            if (this.value === 'online') {
                if (cardDetails) cardDetails.style.display = 'flex';
                if (stcPayDetails) stcPayDetails.style.display = 'none';
                if (cardOption) cardOption.classList.add('y-c-active');
                if (stcPayOption) stcPayOption.classList.remove('y-c-active');
                selectedText = 'البطاقة الائتمانية';
            } else if (this.value === 'stc') {
                if (cardDetails) cardDetails.style.display = 'none';
                if (stcPayDetails) stcPayDetails.style.display = 'flex';
                if (stcPayOption) stcPayOption.classList.add('y-c-active');
                if (cardOption) cardOption.classList.remove('y-c-active');
                selectedText = 'stc pay';
            }

            // Update both text displays
            if (paymentMethodDisplay) {
                paymentMethodDisplay.textContent = selectedText;
            }
            if (dropdownSelectedText) {
                dropdownSelectedText.textContent = selectedText;
            }
        });
    });

    // Set initial state based on the checked radio button
    function setInitialState() {
        const initialChecked = document.querySelector('input[name="payment_method"]:checked');
        if (initialChecked) {
            // Manually trigger the change event to set the correct initial state
            initialChecked.dispatchEvent(new Event('change'));
        } else if (paymentMethods.length > 0) {
            // If nothing is checked, check the first one and trigger
            paymentMethods[0].checked = true;
            paymentMethods[0].dispatchEvent(new Event('change'));
        }
    }
    setInitialState();

    // Function to show the success popup
    function showSuccessPopup() {
        // Create overlay
        const overlay = document.createElement('div');
        overlay.className = 'y-c-popup-overlay';
        overlay.setAttribute('data-y', 'popup-overlay');

        // Create popup content
        overlay.innerHTML = `
        <div class="y-c-popup-content" data-y="popup-content">
            <i class="fas fa-check-circle" data-y="popup-icon"></i>
            <h2 data-y="popup-title">تم استلام طلبك بنجاح</h2>
            <a href="/templates/home/layout.html" class="y-c-basic-btn" data-y="return-home-btn">
                العودة إلى الرئيسية
            </a>
        </div>
    `;

        // Append to body
        document.body.appendChild(overlay);

        // Clear the cart from local storage after a successful order
        localStorage.removeItem('cart');
    }

    // Add event listener to the form
    const paymentForm = document.querySelector('[data-y="payment-form"]');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent actual form submission for this example

            // In a real application, you would handle payment processing here.
            // For now, we'll just show the success popup immediately.
            showSuccessPopup();
        });
    }
});