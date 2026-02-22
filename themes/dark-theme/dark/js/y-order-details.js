document.addEventListener("DOMContentLoaded", function () {
  const ordersListView = document.querySelector(".orders-list-view");
  const orderDetailsView = document.querySelector(".order-details-view");
  const viewButtons = document.querySelectorAll(".btn-view");

  viewButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();

      if (ordersListView && orderDetailsView) {
        ordersListView.style.display = "none";
        orderDetailsView.style.display = "block";
      }
    });
  });
});
