fetch("../../components/auth/y-c-sign-up.html")
    .then((response) => response.text())
    .then((data) => {
        document.querySelector("[data-y='sign-up']").innerHTML = data;
    });