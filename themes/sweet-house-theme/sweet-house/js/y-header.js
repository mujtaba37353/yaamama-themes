fetch("../../components/home/y-c-header.html")
  .then((response) => response.text())
  .then((data) => {
    document.querySelector('[data-y="header"]').innerHTML = data;
  });
