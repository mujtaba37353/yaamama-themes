const loginContainer = document.querySelector("[data-y='login']");
if (loginContainer) {
  fetch("../../components/auth/y-c-login.html")
    .then((response) => response.text())
    .then((data) => {
      loginContainer.innerHTML = data;
      if (window.mykitchenResolveAssets) {
        window.mykitchenResolveAssets(loginContainer);
      }
    });
}
