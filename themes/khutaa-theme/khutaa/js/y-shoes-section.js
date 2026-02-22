fetch("../../components/home/y-c-shoes-section.html")
    .then((response) => response.text())
    .then((data) => {
        document.querySelector('[data-y="shoes-sec"]').innerHTML = data;
    });
