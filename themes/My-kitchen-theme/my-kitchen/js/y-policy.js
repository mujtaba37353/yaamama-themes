fetch("../../components/policy/y-c-policy.html")
  .then((response) => response.text())
  .then((data) => {
    document.querySelector('[data-y="policy"]').innerHTML = data;
  });
