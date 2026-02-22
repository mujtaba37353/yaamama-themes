document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[data-y="contact-form"]');

    if (!form) {
        return;
    }

    form.addEventListener('submit', function (e) {
        if (window.validationUtils && !window.validationUtils.validateContainer(form)) {
            e.preventDefault();
        }
    });
});
