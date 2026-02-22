fetch("../../components/contact us/y-c-contact-us.html")
    .then((response) => response.text())
    .then((data) => {
        document.querySelector('[data-y="contactus"]').innerHTML = data;
    });
