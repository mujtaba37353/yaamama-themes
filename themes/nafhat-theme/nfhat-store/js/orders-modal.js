(() => {
  const modal = document.getElementById("order-details-modal");
  if (!modal) return;

  const modalCard = modal.querySelector(".modal-card");
  const statusChip = document.getElementById("modal-status-chip");
  const orderNumber = document.getElementById("modal-order-number");
  const orderId = document.getElementById("modal-order-id");
  const orderDate = document.getElementById("modal-order-date");
  const orderAddress = document.getElementById("modal-order-address");
  const orderPayment = document.getElementById("modal-order-payment");
  const productImage = document.getElementById("modal-product-image");
  const productName = document.getElementById("modal-product-name");
  const productCategory = document.getElementById("modal-product-category");
  const productQty = document.getElementById("modal-product-qty");
  const productPrice = document.getElementById("modal-product-price");

  const fillModal = (btn) => {
    const data = btn.dataset;
    statusChip.textContent = data.orderStatus;
    orderNumber.textContent = data.orderNumber;
    orderId.textContent = data.orderNumber;
    orderDate.textContent = data.orderDate;
    orderAddress.textContent = data.orderAddress;
    orderPayment.textContent = data.orderPayment;
    productImage.src = data.productImage;
    productImage.alt = data.productName;
    productName.textContent = data.productName;
    productCategory.textContent = data.productCategory;
    productQty.textContent = `الكمية: ${data.productQty}`;
    productPrice.textContent = data.productPrice;
  };

  const openModal = (btn) => {
    fillModal(btn);
    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
    modalCard.focus();
  };

  const closeModal = () => {
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  };

  document.querySelectorAll(".order-details-trigger").forEach((btn) => {
    btn.addEventListener("click", () => openModal(btn));
  });

  modal.addEventListener("click", (event) => {
    if (event.target.hasAttribute("data-modal-dismiss")) {
      closeModal();
    }
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && modal.classList.contains("is-open")) {
      closeModal();
    }
  });
})();
