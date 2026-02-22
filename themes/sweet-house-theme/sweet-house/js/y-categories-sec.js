fetch("../../components/home/y-c-categories-sec.html")
  .then((response) => response.text())
  .then((data) => {
    document.querySelector('[data-y="categories-sec"]').innerHTML = data;
  });
