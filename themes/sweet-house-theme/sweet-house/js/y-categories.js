fetch("../../components/categories/y-c-categories.html")
  .then((response) => response.text())
  .then((data) => {
    const categoriesContainer = document.querySelector('[data-y="categories"]');
    if (categoriesContainer) {
      categoriesContainer.innerHTML = data;
    }
  })
  .catch((error) => console.error("Error loading categories:", error));

