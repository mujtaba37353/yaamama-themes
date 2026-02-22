window.darkThemeAssetUrl = window.darkThemeAssetUrl || function (path) {
  const base = window.DarkTheme && window.DarkTheme.baseUrl ? window.DarkTheme.baseUrl.replace(/\/$/, "") : "";
  return base ? `${base}/${path}` : path;
};

document.addEventListener("DOMContentLoaded", () => {
  const headerRoot = document.querySelector('[data-y="header"]');
  if (!headerRoot) return;
  if (headerRoot.children.length) return;

  fetch(window.darkThemeAssetUrl("components/home/y-c-header.html"))
    .then((response) => response.text())
    .then((data) => {
      const normalized = data.replace(/\.\.\/\.\.\/assets\//g, window.darkThemeAssetUrl("assets/"));
      headerRoot.innerHTML = normalized;
    });
});
