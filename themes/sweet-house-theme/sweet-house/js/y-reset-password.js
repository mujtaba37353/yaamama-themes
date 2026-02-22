fetch("../../components/auth/y-c-reset-password.html")
  .then((response) => response.text())
  .then((data) => {
    document.querySelector("[data-y='reset-password']").innerHTML = data;
  });
