fetch("../../components/auth/y-c-login.html")
    .then((response) => response.text())
    .then((data) => {
        document.querySelector("[data-y='login']").innerHTML = data;
    });