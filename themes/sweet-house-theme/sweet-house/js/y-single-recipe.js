    fetch("../../components/recipe/y-c-single-recipe.html")
    .then((response) => response.text())
    .then((data) => {
        document.querySelector("[data-y='single-recipe']").innerHTML = data;
    });