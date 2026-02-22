const privacyContainer = document.querySelector('[data-y="privacy-policy"]');
if (privacyContainer) {
  fetch("../../components/policy/y-c-privacy-policy.html")
    .then((response) => response.text())
    .then((data) => {
      privacyContainer.innerHTML = data;
    });
}

const refundContainer = document.querySelector('[data-y="refund-policy"]');
if (refundContainer) {
  fetch("../../components/policy/y-c-refund-policy.html")
    .then((response) => response.text())
    .then((data) => {
      refundContainer.innerHTML = data;
    });
}

const usingContainer = document.querySelector('[data-y="using-policy"]');
if (usingContainer) {
  fetch("../../components/policy/y-c-using-policy.html")
    .then((response) => response.text())
    .then((data) => {
      usingContainer.innerHTML = data;
    });
}
