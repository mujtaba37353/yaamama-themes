fetch("../../components/recipe/y-c-recipe.html")
  .then((response) => response.text())
  .then((data) => {
    document.querySelector("[data-y='recipe']").innerHTML = data;
  });
