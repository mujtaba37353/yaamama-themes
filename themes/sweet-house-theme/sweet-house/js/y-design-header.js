fetch("../../components/pages header/y-c-design-header.html")
  .then((response) => response.text())
  .then((data) => {
    document.querySelector('[data-y="design-header"]').innerHTML = data;
  });
