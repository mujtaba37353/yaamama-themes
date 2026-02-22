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
        "الإشعارات": "../../components/account/y-c-notifications.html",
        "الاشعارات": "../../components/account/y-c-notifications.html", // fallback without hamza
        "عنوان الفاتورة": "../../components/account/y-c-current-adddress.html",
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
        const componentPath =
          componentMap[normalizedTitle] ||
          "../../components/account/y-c-account-details.html";

        if (normalizedTitle === "تسجيل الخروج") {
          window.location.href = "../../templates/login/layout.html";
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
    }
  })
  .catch((err) => console.error("Error loading account sidebar:", err));
