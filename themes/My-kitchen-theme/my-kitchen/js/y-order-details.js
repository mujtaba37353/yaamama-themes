document.addEventListener("click", function (e) {
  const viewBtn = e.target.closest(".btn-view");
  if (!viewBtn) return;

  const ordersContainer = viewBtn.closest(".orders-container");
  if (!ordersContainer) return;

  const ordersListView = ordersContainer.querySelector(".orders-list-view");
  const orderDetailsView = ordersContainer.querySelector(".order-details-view");

  e.preventDefault();

  if (ordersListView && orderDetailsView) {
    ordersListView.style.display = "none";
    orderDetailsView.style.display = "block";
  }
});
