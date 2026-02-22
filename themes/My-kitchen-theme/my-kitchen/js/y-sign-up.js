const signUpContainer = document.querySelector("[data-y='sign-up']");
if (signUpContainer) {
  fetch("../../components/auth/y-c-sign-up.html")
    .then((response) => response.text())
    .then((data) => {
      signUpContainer.innerHTML = data;
      if (window.mykitchenResolveAssets) {
        window.mykitchenResolveAssets(signUpContainer);
      }
    });
}
