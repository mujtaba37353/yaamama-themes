fetch("../../components/home/y-c-about-sec.html")
  .then((response) => response.text())
  .then((data) => {
    document.querySelector('[data-y="about-sec"]').innerHTML = data;
  });
