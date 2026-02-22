// My Car/js/auth-popup.js
function initializeAuthPopup() {
    const modal = document.getElementById('auth-popup-modal');
    if (!modal) {
        // If modal isn't loaded yet, try again
        setTimeout(initializeAuthPopup, 50);
        return;
    }

    const forms = modal.querySelectorAll('.y-c-auth-form');
    const openTriggers = document.querySelectorAll('[data-auth-trigger]');
    const switchTriggers = modal.querySelectorAll('[data-auth-show]');
    const closeTriggers = modal.querySelectorAll('[data-auth-close]');

    // Function to show a specific form
    function showForm(formName) {
        forms.forEach(form => {
            if (form.dataset.authForm === formName) {
                form.classList.add('active');
            } else {
                form.classList.remove('active');
            }
        });
    }

    // Function to open the modal
    function openModal(formName) {
        showForm(formName);
        modal.classList.add('show');
    }

    // Function to close the modal
    function closeModal() {
        modal.classList.remove('show');
    }

    // 1. Add listeners to OPEN the modal (e.g., header login button)
    openTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const formName = trigger.dataset.authTrigger;
            openModal(formName);
        });
    });

    // 2. Add listeners to SWITCH forms (e.g., "Need an account?")
    switchTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const formName = trigger.dataset.authShow;
            showForm(formName);
        });
    });

    // 3. Add listeners to CLOSE the modal
    closeTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            closeModal();
        });
    });

    // Close modal by clicking on the overlay
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('show')) {
            closeModal();
        }
    });
}

// Wait for the DOM to be fully loaded before initializing
document.addEventListener('DOMContentLoaded', initializeAuthPopup);