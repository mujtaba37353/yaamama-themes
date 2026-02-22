window.darkThemeAssetUrl = window.darkThemeAssetUrl || function (path) {
  const base = window.DarkTheme && window.DarkTheme.baseUrl ? window.DarkTheme.baseUrl.replace(/\/$/, "") : "";
  return base ? `${base}/${path}` : path;
};

document.addEventListener("DOMContentLoaded", () => {
  const footerRoot = document.querySelector('[data-y="footer"]');
  if (!footerRoot) return;

  if (footerRoot.children.length) {
    return;
  }

  fetch(window.darkThemeAssetUrl("components/layout/y-c-footer.html"))
    .then((response) => response.text())
    .then((data) => {
      footerRoot.innerHTML = data;
    });
});
