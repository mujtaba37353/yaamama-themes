window.darkThemeAssetUrl = window.darkThemeAssetUrl || function (path) {
  const base = window.DarkTheme && window.DarkTheme.baseUrl ? window.DarkTheme.baseUrl.replace(/\/$/, "") : "";
  return base ? `${base}/${path}` : path;
};

fetch(window.darkThemeAssetUrl("components/home/y-c-products-section.html"))
  .then((response) => response.text())
  .then((data) => {
    const normalized = data.replace(/\.\.\/\.\.\/assets\//g, window.darkThemeAssetUrl("assets/"));
    const host = document.querySelector('[data-y="products-sec"]');
    /* لا تستبدل المحتوى إذا كان PHP قد عرض قسم المنتجات (يوجد .section أو .products) */
    const hasPhpContent = host && (host.querySelector('.section') || host.querySelector('ul.products'));
    if (host && !hasPhpContent && !host.children.length) {
      host.innerHTML = normalized;
      var hc = window.DarkTheme && window.DarkTheme.homeContent;
      if (hc) {
        var section1 = host.querySelector('.section.section1');
        if (section1) {
          var h1 = section1.querySelector('.img-container h1');
          if (h1 && hc.products_heading) h1.textContent = hc.products_heading;
          var bannerImg = host.querySelector('.banner img');
          if (bannerImg && hc.products_banner_image) bannerImg.src = hc.products_banner_image;
        }
        var sectionTitleImg = host.querySelector('.section-title img');
        if (sectionTitleImg && hc.products_section_image) sectionTitleImg.src = hc.products_section_image;
      }
    }
  });
