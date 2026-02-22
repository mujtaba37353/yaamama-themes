document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  const category = urlParams.get("category");
  let filterBarPath = "../../components/products/y-c-filter-bar.html";

  if (category === "shoes") {
    filterBarPath = "../../components/products/y-c-shoes-filter-bar.html";
  } else if (category === "bags") {
    filterBarPath = "../../components/products/y-c-bags-filter-bar.html";
  }

  fetch(filterBarPath)
    .then((response) => response.text())
    .then((data) => {
      const filterSection = document.querySelector("section[data-y='filter']");
      if (filterSection) {
        filterSection.outerHTML = data;
        setFilterTitle();
        initializeNewFilterBar();
      }
    })
    .catch((error) => console.error(error));
});

function setFilterTitle() {
  const currentPage = document.body.getAttribute("data-current-page");
  const productsTrigger = document.querySelector(".products-trigger");

  if (!productsTrigger) return;

  let filterTitle = "";

  switch (currentPage) {
    case "products":
      const urlParams = new URLSearchParams(window.location.search);
      const category = urlParams.get("category");
      if (category) {
        filterTitle =
          category === "shoes"
            ? "الأحذية"
            : category === "bags"
            ? "الشنط"
            : "المنتجات";
      } else {
        filterTitle = "المنتجات";
      }
      break;
    case "offers":
      filterTitle = "العروض";
      break;
    case "for-home":
      filterTitle = "أدوات منزلية";
      break;
    case "less-than":
      filterTitle = "أقل من 99 ريال";
      break;
    case "electronics":
      filterTitle = "الأجهزة الإلكترونية";
      break;
    case "decorations":
      filterTitle = "الديكورات";
      break;
    case "wishlist":
      filterTitle = "المفضلة";
      break;
    default:
      filterTitle = "المنتجات";
      break;
  }

  if (filterTitle) {
    productsTrigger.textContent = filterTitle;
  }
}

function initializeNewFilterBar() {
  const dropdowns = document.querySelectorAll(".custom-dropdown");

  dropdowns.forEach((dropdown) => {
    const trigger = dropdown.querySelector(".dropdown-trigger");

    trigger.addEventListener("click", (e) => {
      e.stopPropagation();

      dropdowns.forEach((d) => {
        if (d !== dropdown) d.classList.remove("open");
      });

      dropdown.classList.toggle("open");
    });
  });

  document.addEventListener("click", () => {
    dropdowns.forEach((dropdown) => {
      dropdown.classList.remove("open");
    });
  });

  const sortOptions = document.querySelectorAll(
    ".sort-dropdown .dropdown-options li"
  );
  const sortTriggerText = document.querySelector(
    ".sort-dropdown .current-sort"
  );

  sortOptions.forEach((option) => {
    option.addEventListener("click", (e) => {
      const sortValue = e.target.getAttribute("data-sort");
      const sortText = e.target.textContent;

      console.log("Sort by:", sortValue);

      sortOptions.forEach((opt) => opt.classList.remove("selected"));
      option.classList.add("selected");

      const event = new CustomEvent("product-sort", {
        detail: { sortBy: sortValue },
      });
      document.dispatchEvent(event);
    });
  });

  const productLinks = document.querySelectorAll(
    ".products-dropdown .dropdown-options a"
  );
  productLinks.forEach((link) => {
    if (link.getAttribute("href").includes(currentCategory)) {
      link.style.fontWeight = "bold";
      link.style.color = "var(--y-main)";
    }
  });

  const filterOptions = document.querySelectorAll(
    ".filter-dropdown .dropdown-options li"
  );
  filterOptions.forEach((option) => {
    option.addEventListener("click", (e) => {
      const filterType = e.target.getAttribute("data-filter");
      const filterValue = e.target.getAttribute("data-value");

      const dropdown = e.target.closest(".custom-dropdown");
      const options = dropdown.querySelectorAll("li");
      options.forEach((o) => o.classList.remove("selected"));
      e.target.classList.add("selected");

      console.log(`Filter ${filterType}: ${filterValue}`);
      const event = new CustomEvent("product-filter", {
        detail: { filterType, filterValue },
      });
      document.dispatchEvent(event);
    });
  });
}
