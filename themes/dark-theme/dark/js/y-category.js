window.darkThemeAssetUrl = window.darkThemeAssetUrl || function (path) {
  const base = window.DarkTheme && window.DarkTheme.baseUrl ? window.DarkTheme.baseUrl.replace(/\/$/, "") : "";
  return base ? `${base}/${path}` : path;
};

fetch(window.darkThemeAssetUrl("components/home/y-c-category.html"))
  .then((response) => response.text())
  .then((data) => {
    const normalized = data.replace(/\.\.\/\.\.\/assets\//g, window.darkThemeAssetUrl("assets/"));
    const host = document.querySelector('[data-y="category"]');
    if (host && !host.children.length) {
      host.innerHTML = normalized;
    }
  });