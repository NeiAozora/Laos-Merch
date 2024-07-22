document.addEventListener('DOMContentLoaded', function() {
    const productsContainer = document.getElementById('products-container');
    const paginationContainer = document.querySelector('.pagination');

    const urlParams = new URLSearchParams(window.location.search);
    let currentPage = parseInt(urlParams.get('page')) || 1;
    const itemsPerPage = parseInt(urlParams.get('limit')) || 8;
    const searchQuery = urlParams.get('search') || '';

    function fetchProducts(page = 1, search = '') {
        fetch(`api/products?page=${page}&limit=${itemsPerPage}&search=${search}`)
            .then(response => response.json())
            .then(data => {
                updateProducts(data.products);
                updatePagination(data.current_page, data.total_pages);
            })
            .catch(error => console.error('Error fetching products:', error));
    }

    function updateProducts(products) {
        productsContainer.innerHTML = '';
    
        const filledStar = `
        <svg width="15" height="14" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 2px;">
          <path d="M10.1304 0L12.4293 7.07548H19.8689L13.8501 11.4484L16.1491 18.5238L10.1304 14.151L4.11159 18.5238L6.41055 11.4484L0.391793 7.07548H7.83139L10.1304 0Z" fill="#FFC100"/>
        </svg>`;
        
        const unfilledStar = `
        <svg width="15" height="14" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 2px;">
          <path d="M9.76121 0L12.0602 7.07548H19.4998L13.481 11.4484L15.78 18.5238L9.76121 14.151L3.74245 18.5238L6.04141 11.4484L0.0226526 7.07548H7.46225L9.76121 0Z" fill="#D9D9D9"/>
        </svg>`;
        
        products.forEach(product => {
            // Ensure avg_price and discount_value are numbers
            const avgPrice = parseFloat(product.avg_price);
            const discountValue = parseFloat(product.discount_value);
        
            // Calculate discounted price
            const discountedPrice = (avgPrice - discountValue).toFixed(2);
        
            // Create star rating HTML
            let starRatingHTML = '';
            const fullStars = Math.floor(product.avg_rating);
            const halfStar = product.avg_rating % 1 !== 0;
            const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);
        
            for (let i = 0; i < fullStars; i++) {
                starRatingHTML += filledStar;
            }
            if (halfStar) {
                starRatingHTML += `<svg width="15" height="14" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 2px;">
                    <path d="M10.1304 0L12.4293 7.07548H19.8689L13.8501 11.4484L16.1491 18.5238L10.1304 14.151L4.11159 18.5238L6.41055 11.4484L0.391793 7.07548H7.83139L10.1304 0Z" fill="#FFC100" style="clip-path: inset(0 50% 0 0);"/>
                </svg>`;
            }
            for (let i = 0; i < emptyStars; i++) {
                starRatingHTML += unfilledStar;
            }
        
            const productHTML = `
                <div class="col-6 col-md-3 mt-3">
                    <div class="card" style="text-align: left;">
                        <a href="${baseUrl}product/${product.id_product}" style="text-decoration: none; color: inherit;">
                            <img src="${product.product_image}" class="card-img-top" alt="${product.product_name}">
                            <div class="card-body">
                                <div class="d-flex flex-row mb-2">
                                    ${starRatingHTML}
                                </div>
                                <p class="card-text">${product.product_name}</p>
                                <p class="card-text">
                                    <span style="font-weight: bold;">Rp ${discountedPrice}</span>
                                    ${discountValue > 0 ? `<br><span style="text-decoration: line-through; color: #888;">Rp ${avgPrice.toFixed(2)}</span>` : ''}
                                </p>
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
        const newUrl = new URL(window.location.href);
        newUrl.searchParams.set('page', page);
        window.history.pushState({}, '', newUrl);
        fetchProducts(page, searchQuery);
    }

    // Initial fetch
    fetchProducts(currentPage, searchQuery);
});
