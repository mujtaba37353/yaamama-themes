document.addEventListener("DOMContentLoaded", () => {
  const breadcrumbPlaceholder = document.querySelector('[data-y="breadcrumb"]');
  if (!breadcrumbPlaceholder) return;

  fetch("../../components/layout/y-c-breadcrumb.html")
    .then((response) => response.text())
    .then((html) => {
      breadcrumbPlaceholder.innerHTML = html;
      if (window.mykitchenResolveAssets) {
        window.mykitchenResolveAssets(breadcrumbPlaceholder);
      }

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
    .trim();
  const currentPage = document.body.getAttribute("data-current-page");
  const siteRoot =
    window.MYK_SITE_URL || window.location.origin + "/my-kitchen/";
  const shopUrl = siteRoot + "shop/";

  const homeItem = createBreadcrumbItem(
    "الرئيسية",
    siteRoot
  );
  listElement.appendChild(homeItem);

  let currentItemText = "";
  let parentItem = null;

  switch (currentPage) {
    case "products":
      const urlParams = new URLSearchParams(window.location.search);
      const category = urlParams.get("category");
      if (category) {
        parentItem = createBreadcrumbItem(
          "المنتجات",
          shopUrl
        );
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
    case "store":
      currentItemText = "المتجر";
      break;
    case "offers":
      currentItemText = "العروض";
      break;
    case "for-home":
      currentItemText = "أدوات منزلية";
      break;
    case "less-than":
      currentItemText = "أقل من 99 ريال";
      break;
    case "electronics":
      currentItemText = "الأجهزة الإلكترونية";
      break;
    case "decorations":
      currentItemText = "الديكورات";
      break;
    case "contact":
    case "contact-us":
      currentItemText = "تواصل معنا";
      break;
    case "about":
    case "about-us":
      currentItemText = "من نحن";
      break;
    case "single-product":
      parentItem = createBreadcrumbItem(
        "المتجر",
        shopUrl
      );
      listElement.appendChild(parentItem);
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
    case "policy":
      currentItemText = "سياسة الخصوصية";
      break;
    case "refund-policy":
      currentItemText = "سياسة الاسترجاع";
      break;
    case "using-policy":
      currentItemText = "سياسة الشحن";
      break;
    case "wishlist":
      currentItemText = "المفضلة";
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
        parentItem = createBreadcrumbItem(
          "المنتجات",
          "../../templates/products/layout.html"
        );
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
