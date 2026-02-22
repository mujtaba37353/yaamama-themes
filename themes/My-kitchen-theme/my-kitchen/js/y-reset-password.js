const resetContainer = document.querySelector("[data-y='reset-password']");
if (resetContainer) {
  fetch("../../components/auth/y-c-reset-password.html")
    .then((response) => response.text())
    .then((data) => {
      resetContainer.innerHTML = data;
      if (window.mykitchenResolveAssets) {
        window.mykitchenResolveAssets(resetContainer);
      }
    });
}
