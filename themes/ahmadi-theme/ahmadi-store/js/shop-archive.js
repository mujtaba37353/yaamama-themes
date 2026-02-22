'use strict';

const productsPerPage = 10;
let currentPage = 1;

function initializeApp() {
    setupPagination();
    generateProducts(currentPage, productsPerPage);
    setupShoppingDropdown();
}

function setupPagination() {
    const paginationContainer = document.querySelector('.y-c-pagination');
    if (!paginationContainer) return;

    const totalProducts = products.length;
    const totalPages = Math.ceil(totalProducts / productsPerPage);

    const renderButtons = () => {
        paginationContainer.innerHTML = '';

        // Previous button
        const prevButton = document.createElement('button');
        prevButton.innerHTML = '<i class="fa-solid fa-arrow-right"></i>';
        prevButton.disabled = currentPage === 1;
        prevButton.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                generateProducts(currentPage, productsPerPage);
                renderButtons();
            }
        });
        paginationContainer.appendChild(prevButton);

        if (totalPages <= 9) {
            for (let i = 1; i <= totalPages; i++) {
                createPageButton(i);
            }
        } else {
            // First page
            createPageButton(1);

            if (currentPage > 4) {
                createEllipsis();
            }

            let startPage = Math.max(2, currentPage - 1);
            let endPage = Math.min(totalPages - 1, currentPage + 1);

            if (currentPage <= 4) {
                startPage = 2;
                endPage = 5;
            } else if (currentPage >= totalPages - 3) {
                startPage = totalPages - 4;
                endPage = totalPages - 1;
            }

            for (let i = startPage; i <= endPage; i++) {
                if (i > 1 && i < totalPages) {
                    createPageButton(i);
                }
            }

            if (currentPage < totalPages - 3) {
                createEllipsis();
            }

            // Last page
            createPageButton(totalPages);
        }

        // Next button
        const nextButton = document.createElement('button');
        nextButton.innerHTML = '<i class="fa-solid fa-arrow-left"></i>';
        nextButton.disabled = currentPage === totalPages;
        nextButton.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                generateProducts(currentPage, productsPerPage);
                renderButtons();
            }
        });
        paginationContainer.appendChild(nextButton);
    };

    const createPageButton = (pageNumber) => {
        const pageButton = document.createElement('button');
        pageButton.textContent = pageNumber;
        pageButton.classList.toggle('y-c-active', pageNumber === currentPage);
        pageButton.addEventListener('click', () => {
            currentPage = pageNumber;
            generateProducts(currentPage, productsPerPage);
            renderButtons();
        });
        paginationContainer.appendChild(pageButton);
    };

    const createEllipsis = () => {
        const ellipsis = document.createElement('span');
        ellipsis.textContent = '...';
        ellipsis.style.padding = "10px";
        paginationContainer.appendChild(ellipsis);
    };

    renderButtons();
}

function setupShoppingDropdown() {
    const dropdownBtn = document.querySelector('.y-c-shopping-dropdown-btn');
    const dropdownContent = document.querySelector('.y-c-shopping-dropdown-content');

    if (dropdownBtn && dropdownContent) {
        dropdownBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdownContent.classList.toggle('y-c-show');
            const icon = this.querySelector('.fa-angle-down');
            if (icon) {
                icon.style.transform = dropdownContent.classList.contains('y-c-show') ? 'rotate(180deg)' : 'rotate(0)';
            }
        });

        document.addEventListener('click', function (e) {
            if (!dropdownBtn.contains(e.target) && !dropdownContent.contains(e.target)) {
                dropdownContent.classList.remove('y-c-show');
                const icon = dropdownBtn.querySelector('.fa-angle-down');
                if (icon) {
                    icon.style.transform = 'rotate(0)';
                }
            }
        });
    }
}