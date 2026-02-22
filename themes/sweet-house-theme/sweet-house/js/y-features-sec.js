fetch("../../components/home/y-c-features-sec.html")
  .then((response) => response.text())
  .then((data) => {
    document.querySelector('[data-y="features-sec"]').innerHTML = data;
  });
