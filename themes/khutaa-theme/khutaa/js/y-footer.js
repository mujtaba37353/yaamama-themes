fetch("../../components/layout/y-c-footer.html")
    .then((response) => response.text())
    .then((data) => {
        document.querySelector('[data-y="footer"]').innerHTML = data;
    });
