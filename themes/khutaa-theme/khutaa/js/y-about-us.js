fetch("../../components/about us/y-c-about-us.html")
    .then((response) => response.text())
    .then((data) => {
        document.querySelector('[data-y="about-us"]').innerHTML = data;
    });
