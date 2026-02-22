fetch("../../components/home/y-c-bag-section.html")
    .then((response) => response.text())
    .then((data) => {
        document.querySelector('[data-y="bag-sec"]').innerHTML = data;
    });
