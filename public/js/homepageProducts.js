
document.addEventListener('DOMContentLoaded', function() {
    const productsContainer = document.querySelector('.row');
    const paginationContainer = document.querySelector('.pagination');

    let currentPage = 1;
    const itemsPerPage = 8;

    function fetchProducts(page = 1) {
        fetch(`api/products.php?page=${page}&limit=${itemsPerPage}`)
            .then(response => response.json())
            .then(data => {
                updateProducts(data.products);
                updatePagination(data.currentPage, data.totalPages);
            })
            .catch(error => console.error('Error fetching products:', error));
    }

    function updateProducts(products) {
        productsContainer.innerHTML = '';

        products.forEach(product => {
            const productHTML = `
                <div class="col-6 col-md-3 mt-3">
                    <div class="card" style="text-align: left;">
                        <a href="#" style="text-decoration: none; color: inherit;">
                            <img src="${product.image_url}" class="card-img-top" alt="${product.product_name}">
                            <div class="card-body">
                                <h5 class="card-title">${product.product_name}</h5>
                                <p class="card-text">${product.description}</p>
                            </div>
                        </a>
                    </div>
                </div>`;
            productsContainer.insertAdjacentHTML('beforeend', productHTML);
        });
    }

    function updatePagination(currentPage, totalPages) {
        paginationContainer.innerHTML = '';

        const prevPage = currentPage > 1 ? currentPage - 1 : null;
        if (prevPage) {
            paginationContainer.insertAdjacentHTML('beforeend', `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${prevPage})">&lt;</a></li>`);
        } else {
            paginationContainer.insertAdjacentHTML('beforeend', `<li class="page-item disabled"><a class="page-link">&lt;</a></li>`);
        }

        for (let i = 1; i <= totalPages; i++) {
            const activeClass = i === currentPage ? ' active' : '';
            paginationContainer.insertAdjacentHTML('beforeend', `<li class="page-item${activeClass}"><a class="page-link" href="#" onclick="changePage(${i})">${i}</a></li>`);
        }

        const nextPage = currentPage < totalPages ? currentPage + 1 : null;
        if (nextPage) {
            paginationContainer.insertAdjacentHTML('beforeend', `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${nextPage})">&gt;</a></li>`);
        } else {
            paginationContainer.insertAdjacentHTML('beforeend', `<li class="page-item disabled"><a class="page-link">&gt;</a></li>`);
        }
    }

    window.changePage = function(page) {
        currentPage = page;
        fetchProducts(page);
    }

    // Initial fetch
    fetchProducts(currentPage);
});
