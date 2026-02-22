const categoriesContainer = document.querySelector('[data-y="categories"]');
if (
  !categoriesContainer ||
  categoriesContainer.dataset.realCategories === "1" ||
  categoriesContainer.querySelector(".category")
) {
  // Real categories already rendered by PHP.
} else {
  fetch("../../components/categories/y-c-categories.html")
    .then((response) => response.text())
    .then((data) => {
      if (categoriesContainer) {
        categoriesContainer.innerHTML = data;
        if (window.mykitchenResolveAssets) {
          window.mykitchenResolveAssets(categoriesContainer);
        }
      }
    })
    .catch((error) => console.error("Error loading categories:", error));
}

