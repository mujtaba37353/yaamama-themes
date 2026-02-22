const paginationSection = document.querySelector("section[data-y='pagination']");
if (
  !paginationSection ||
  paginationSection.dataset.realPagination === "1" ||
  paginationSection.querySelector(".pagination")
) {
  // Real pagination already rendered by PHP.
} else {
  fetch("../../components/products/y-c-pagination.html")
    .then((response) => response.text())
    .then((data) => {
      paginationSection.outerHTML = data;

      initPagination();
    })
    .catch((error) => console.error(error));
}

function initPagination() {
  const paginationList = document.querySelector(".pagination-list");
  const prevButton = document.querySelector(".pagination-prev");
  const nextButton = document.querySelector(".pagination-next");

  if (!paginationList || !prevButton || !nextButton) return;

  const totalPages = 10;
  let currentPage = 1;


  function renderPages() {
    paginationList.innerHTML = "";


    if (totalPages <= 7) {
      for (let i = 1; i <= totalPages; i++) {
        createPageButton(i);
      }
      return;
    }


    createPageButton(1);

    let startPage = Math.max(2, currentPage - 1);
    let endPage = Math.min(totalPages - 1, currentPage + 1);


    if (currentPage <= 3) {
      startPage = 2;
      endPage = Math.min(5, totalPages - 1);
    } else if (currentPage >= totalPages - 2) {
      startPage = Math.max(2, totalPages - 4);
      endPage = totalPages - 1;
    }


    if (startPage > 2) {
      createDotsButton();
    }


    for (let i = startPage; i <= endPage; i++) {
      createPageButton(i);
    }


    if (endPage < totalPages - 1) {
      createDotsButton();
    }


    if (totalPages > 1) {
      createPageButton(totalPages);
    }
  }

  function createPageButton(pageNum) {
    const li = document.createElement("li");
    const btn = document.createElement("button");
    btn.textContent = pageNum;
    btn.dataset.page = pageNum;
    if (pageNum === currentPage) {
      btn.classList.add("active");
    }
    li.appendChild(btn);
    paginationList.appendChild(li);
  }

  function createDotsButton() {
    const li = document.createElement("li");
    const span = document.createElement("span");
    span.textContent = "...";
    span.classList.add("pagination-dots");
    li.appendChild(span);
    paginationList.appendChild(li);
  }


  function updateButtonStates() {

    prevButton.disabled = currentPage === totalPages;

    nextButton.disabled = currentPage === 1;
  }

  renderPages();
  updateButtonStates();


  function dispatchPageChange() {
    const event = new CustomEvent("pageChange", {
      detail: { page: currentPage },
    });
    document.dispatchEvent(event);
  }



  prevButton.addEventListener("click", () => {
    if (currentPage < totalPages) {
      currentPage++;
      renderPages();
      updateButtonStates();
      dispatchPageChange();
    }
  });


  nextButton.addEventListener("click", () => {
    if (currentPage > 1) {
      currentPage--;
      renderPages();
      updateButtonStates();
      dispatchPageChange();
    }
  });


  paginationList.addEventListener("click", (e) => {
    if (e.target.tagName === "BUTTON") {
      const page = parseInt(e.target.dataset.page);
      if (page !== currentPage) {
        currentPage = page;
        renderPages();
        updateButtonStates();
        dispatchPageChange();
      }
    }
  });
}
