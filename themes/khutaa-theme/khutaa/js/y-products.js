fetch("../../components/products/y-c-products.html")
  .then((response) => response.text())
  .then((data) => {
    const temp = document.createElement("div");
    temp.innerHTML = data;

    const productsContainer = document.querySelector('[data-y="products"]');
    const singleProductTrigger = document.querySelector(
      '[data-y="single-product"]'
    );

    if (singleProductTrigger) {
      const productCards = temp.querySelectorAll(".product-card");
      const limit =
        parseInt(singleProductTrigger.getAttribute("data-limit")) || 10;
      const limited = Array.from(productCards).slice(0, limit);

      const productsWrapper = document.createElement("div");
      productsWrapper.className = "products";

      limited.forEach((card) => productsWrapper.appendChild(card));

      productsContainer.innerHTML = "";
      productsContainer.appendChild(productsWrapper);
    } else {
      productsContainer.innerHTML = temp.innerHTML;
    }

    const wishlistBtns = productsContainer.querySelectorAll(".wishlist-btn");
    wishlistBtns.forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();

        const icon = btn.querySelector("i");
        btn.classList.toggle("active");

        if (btn.classList.contains("active")) {
          icon.classList.remove("fa-regular");
          icon.classList.add("fa-solid");
        } else {
          icon.classList.remove("fa-solid");
          icon.classList.add("fa-regular");
        }
      });
    });
  })
  .catch((error) => console.error(error));
