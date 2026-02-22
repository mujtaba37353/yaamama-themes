window.darkThemeAssetUrl = window.darkThemeAssetUrl || function (path) {
  const base = window.DarkTheme && window.DarkTheme.baseUrl ? window.DarkTheme.baseUrl.replace(/\/$/, "") : "";
  return base ? `${base}/${path}` : path;
};

document.addEventListener("DOMContentLoaded", () => {
  const headerRoot = document.querySelector('[data-y="design-header"]');
  if (!headerRoot) return;

  if (headerRoot.children.length) {
    return;
  }

  fetch(window.darkThemeAssetUrl("components/pages header/y-c-design-header.html"))
    .then((response) => response.text())
    .then((data) => {
      headerRoot.innerHTML = data;
    });
});
