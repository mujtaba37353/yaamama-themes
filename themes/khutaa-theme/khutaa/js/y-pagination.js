fetch("../../components/products/y-c-pagination.html")
  .then((response) => response.text())
  .then((data) => {
    document.querySelector("section[data-y='pagination']").outerHTML = data;

    initPagination();
  })
  .catch((error) => console.error(error));

function initPagination() {
  const paginationList = document.querySelector(".pagination-list");
  const prevButton = document.querySelector(".pagination-prev");
  const nextButton = document.querySelector(".pagination-next");

  if (!paginationList || !prevButton || !nextButton) return;

  const totalPages = 10;
  let currentPage = 1;

  function renderPages() {
    paginationList.innerHTML = "";
    for (let i = 1; i <= totalPages; i++) {
      const li = document.createElement("li");
      const btn = document.createElement("button");
      btn.textContent = i;
      btn.dataset.page = i;
      if (i === currentPage) {
        btn.classList.add("active");
      }
      li.appendChild(btn);
      paginationList.appendChild(li);
    }
  }

  renderPages();

  function dispatchPageChange() {
    const event = new CustomEvent("pageChange", {
      detail: { page: currentPage },
    });
    document.dispatchEvent(event);
  }

  prevButton.addEventListener("click", () => {
    if (currentPage > 1) {
      currentPage--;
      renderPages();
      dispatchPageChange();
    }
  });

  nextButton.addEventListener("click", () => {
    if (currentPage < totalPages) {
      currentPage++;
      renderPages();
      dispatchPageChange();
    }
  });

  paginationList.addEventListener("click", (e) => {
    if (e.target.tagName === "BUTTON") {
      const page = parseInt(e.target.dataset.page);
      if (page !== currentPage) {
        currentPage = page;
        renderPages();
        dispatchPageChange();
      }
    }
  });
}
