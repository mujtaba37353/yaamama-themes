window.darkThemeAssetUrl = window.darkThemeAssetUrl || function (path) {
  const base = window.DarkTheme && window.DarkTheme.baseUrl ? window.DarkTheme.baseUrl.replace(/\/$/, "") : "";
  return base ? `${base}/${path}` : path;
};

document.addEventListener("DOMContentLoaded", () => {
  const breadcrumbPlaceholder = document.querySelector('[data-y="breadcrumb"]');
  if (!breadcrumbPlaceholder) return;

  const existingList = breadcrumbPlaceholder.querySelector(".y-breadcrumb");
  if (existingList) {
    generateBreadcrumbItems(existingList);
    return;
  }

  fetch(window.darkThemeAssetUrl("components/layout/y-c-breadcrumb.html"))
    .then((response) => response.text())
    .then((html) => {
      breadcrumbPlaceholder.innerHTML = html;

      const breadcrumbList =
        breadcrumbPlaceholder.querySelector(".y-breadcrumb");
      if (breadcrumbList) {
        generateBreadcrumbItems(breadcrumbList);
      }
    })
    .catch((error) => console.error("Error loading breadcrumb:", error));
});

function generateBreadcrumbItems(listElement) {
  const pageTitle = document.title
    .replace(" - خطى للأحذية", "")
    .replace("خطى للأحذية", "")
    .replace(" – dark", "")
    .replace(" - dark", "")
    .trim();
  const currentPage = document.body.getAttribute("data-current-page");

  const homeUrl = (window.DarkTheme && window.DarkTheme.urls && window.DarkTheme.urls.home) || "/";
  const shopUrl = (window.DarkTheme && window.DarkTheme.urls && window.DarkTheme.urls.shop) || "#";
  const homeItem = createBreadcrumbItem("الرئيسية", homeUrl);
  listElement.appendChild(homeItem);

  let currentItemText = "";
  let parentItem = null;

  switch (currentPage) {
    case "products":
      const urlParams = new URLSearchParams(window.location.search);
      const category = urlParams.get("category");
      if (category) {
        parentItem = createBreadcrumbItem("المنتجات", shopUrl);
        listElement.appendChild(parentItem);
        currentItemText =
          category === "shoes"
            ? "الأحذية"
            : category === "bags"
            ? "الشنط"
            : "المنتجات";
      } else {
        currentItemText = "المنتجات";
      }
      break;
    case "offers":
      currentItemText = "العروض";
      break;
    case "contact":
      currentItemText = "تواصل معنا";
      break;
    case "about":
      currentItemText = "من نحن";
      break;
    case "single-product":
      currentItemText = "تفاصيل المنتج";
      break;
    case "cart":
      currentItemText = "سلة المشتريات";
      break;
    case "payment":
      currentItemText = "الدفع";
      break;
    case "login":
      currentItemText = "تسجيل الدخول";
      break;
    case "signup":
      currentItemText = "إنشاء حساب";
      break;
    case "reset-password":
      currentItemText = "استعادة كلمة المرور";
      break;
    case "my-account":
      currentItemText = "حسابي";
      break;
    case "account":
      currentItemText = "حسابي";
      break;
    case "privacy-policy":
      currentItemText = "سياسة الخصوصية";
      break;
    case "refund-policy":
      currentItemText = "سياسة الاسترجاع";
      break;
    case "using-policy":
      currentItemText = "سياسة الشحن";
      break;
    case "not-found":
      currentItemText = "404";
      break;
    case "empty-cart":
      currentItemText = "سلة المشتريات";
      break;
    case "empty-favourite":
      currentItemText = "المفضلة";
      break;
    default:
      currentItemText = pageTitle || "الصفحة الحالية";
      if (pageTitle === "layout" || pageTitle === "") {
        currentItemText = "";
      }

      const pageTitleAttr = document
        .querySelector("title")
        .getAttribute("data-y");
      if (pageTitleAttr === "page-title" && document.title !== "layout") {
      }

      if (currentPage === "cart") currentItemText = "سلة المشتريات";
      if (currentPage === "payment") currentItemText = "الدفع";
      if (currentPage === "login") currentItemText = "تسجيل الدخول";
      if (currentPage === "signup") currentItemText = "إنشاء حساب";
      if (currentPage === "reset-password")
        currentItemText = "استعادة كلمة المرور";
      if (currentPage === "my-account") currentItemText = "حسابي";
      if (document.URL.includes("privacy-policy"))
        currentItemText = "سياسة الخصوصية";
      if (document.URL.includes("refund-policy"))
        currentItemText = "سياسة الاسترجاع";
      if (document.URL.includes("using-policy"))
        currentItemText = "سياسة الشحن";
      if (document.URL.includes("single-product")) {
        parentItem = createBreadcrumbItem("المنتجات", shopUrl);
        listElement.appendChild(parentItem);
        currentItemText = "تفاصيل المنتج";
      }
      break;
  }

  if (currentItemText) {
    const activeItem = document.createElement("li");
    activeItem.className = "y-breadcrumb-item active";
    activeItem.textContent = currentItemText;
    listElement.appendChild(activeItem);
  }
}

function createBreadcrumbItem(text, href) {
  const li = document.createElement("li");
  li.className = "y-breadcrumb-item";
  const a = document.createElement("a");
  a.href = href;
  a.textContent = text;
  li.appendChild(a);
  return li;
}
