fetch("../../components/not found/y-c-not-found.html")
  .then((response) => response.text())
  .then((data) => {
    document.querySelector('[data-y="not-found"]').innerHTML = data;
  });
