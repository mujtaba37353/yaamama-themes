fetch("../../components/account/y-c-account-sidebar.html")
  .then((response) => response.text())
  .then((data) => {
    const sidebar = document.querySelector('[data-y="account-sidebar"]');
    const contentContainer = document.getElementById("account-content");
    if (sidebar && contentContainer) {
      sidebar.innerHTML = data;

      // Map sidebar titles to their corresponding component paths
      const componentMap = {
        "تفاصيل الحساب": "../../components/account/y-c-account-details.html",
        "الطلبات": "../../components/account/y-c-orders.html",
        "العنوان": "../../components/account/y-c-current-adddress.html",
      };

      const addressFormPath = "../../components/account/y-c-address.html";
      const currentAddressPath =
        "../../components/account/y-c-current-adddress.html";

      const loadAddressForm = () => {
        fetch(addressFormPath)
          .then((response) => response.text())
          .then((html) => {
            contentContainer.innerHTML = html;
          })
          .catch((err) => {
            console.error("Error loading address form:", err);
            contentContainer.innerHTML = "<p>فشل تحميل العنوان.</p>";
          });
      };

      const loadCurrentAddress = () => {
        fetch(currentAddressPath)
          .then((response) => response.text())
          .then((html) => {
            contentContainer.innerHTML = html;
            attachAddressActions();
          })
          .catch((err) => {
            console.error("Error loading current address:", err);
            contentContainer.innerHTML = "<p>فشل تحميل العنوان.</p>";
          });
      };

      const attachAddressActions = () => {
        const toFormButtons = contentContainer.querySelectorAll(".btn-edit");
        toFormButtons.forEach((btn) => {
          btn.addEventListener("click", (e) => {
            e.preventDefault();
            loadAddressForm();
          });
        });
        const closeBtn = contentContainer.querySelector(".address-close");
        if (closeBtn) {
          closeBtn.addEventListener("click", (e) => {
            e.preventDefault();
            loadCurrentAddress();
          });
        }
      };

      const setContentForTitle = (title) => {
        const normalizedTitle = title ? title.trim() : "";
        if (normalizedTitle === "تفاصيل الحساب") {
          const template = document.getElementById("account-details-template");
          if (template) {
            contentContainer.innerHTML = template.innerHTML;
            return;
          }
        }
        if (normalizedTitle === "الطلبات") {
          const template = document.getElementById("account-orders-template");
          if (template) {
            contentContainer.innerHTML = template.innerHTML;
            attachOrdersActions(contentContainer);
            return;
          }
        }
        if (normalizedTitle === "العنوان") {
          const template = document.getElementById("account-address-template");
          if (template) {
            contentContainer.innerHTML = template.innerHTML;
            return;
          }
        }
        const componentPath =
          componentMap[normalizedTitle] ||
          "../../components/account/y-c-account-details.html";

      if (normalizedTitle === "تسجيل الخروج") {
        const logoutUrl =
          window.MYK_LOGOUT_URL || "/my-kitchen/logout/";
        window.location.href = logoutUrl;
          return;
        }

        fetch(componentPath)
          .then((response) => response.text())
          .then((html) => {
            contentContainer.innerHTML = html;
            if (normalizedTitle === "عنوان الفاتورة") {
              attachAddressActions();
            }
          })
          .catch((err) => {
            console.error("Error loading content component:", err);
            contentContainer.innerHTML = "<p>فشل تحميل المحتوى.</p>";
          });
      };

      const sidebarLinks = sidebar.querySelectorAll(".sidebar-item a");
      sidebarLinks.forEach((link) => {
        link.addEventListener("click", (e) => {
          e.preventDefault();
          const parentItem = link.closest(".sidebar-item");

          sidebar.querySelectorAll(".sidebar-item").forEach((item) => {
            item.classList.remove("active");
          });

          parentItem.classList.add("active");
          if (window.innerWidth <= 991) {
            parentItem.scrollIntoView({ behavior: "smooth", inline: "center", block: "nearest" });
          }

          const title = parentItem.getAttribute("data-title");
          const titleElement = sidebar.querySelector("#sidebar-title");
          if (titleElement && title) {
            titleElement.textContent = title;
          }

          setContentForTitle(title);
        });
      });

      const sidebarItems = sidebar.querySelectorAll(".sidebar-item");
      const activeItem = sidebarItems[0];
      if (activeItem) {
        activeItem.classList.add("active");
        const title = activeItem.getAttribute("data-title");
        const titleElement = sidebar.querySelector("#sidebar-title");
        if (titleElement && title) {
          titleElement.textContent = title;
        }
        setContentForTitle(title);
      }

      const logoutLink = sidebar.querySelector(
        '.sidebar-item[data-title="تسجيل الخروج"] a'
      );
      if (logoutLink) {
        logoutLink.setAttribute(
          "href",
          window.MYK_LOGOUT_URL || "/my-kitchen/logout/"
        );
      }
    }
  })
  .catch((err) => console.error("Error loading account sidebar:", err));

function attachOrdersActions(scope) {
  const root = scope || document;
  const buttons = root.querySelectorAll(".btn-view[data-order-id]");
  if (!buttons.length) return;
  buttons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const orderId = btn.getAttribute("data-order-id");
      if (!orderId) return;
      const details = root.querySelector(
        `.order-details-view[data-order-id="${orderId}"]`
      );
      if (!details) return;
      const isVisible = details.style.display === "block";
      details.style.display = isVisible ? "none" : "block";
    });
  });
}
