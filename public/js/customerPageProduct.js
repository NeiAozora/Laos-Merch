
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.variation-group').forEach(group => {
        const firstOption = group.querySelector('.btn');
        if (firstOption) {
            const variationTypeId = group.getAttribute('data-variation-type');
            firstOption.classList.add('active');
            selectedOptions[variationTypeId] = firstOption.getAttribute('data-option-id');
        }
    });
    updateDisplayedValues();
});

function chooseVariation(optionId, variationTypeId) {
    selectedOptions[variationTypeId] = optionId;
    document.querySelectorAll(`.variation-group[data-variation-type="${variationTypeId}"] .btn`).forEach(btn => {
        btn.classList.toggle('active', btn.getAttribute('data-option-id') === optionId);
    });
    updateDisplayedValues();
}


let selectedPrice = 0;

function updateSubtotal() {
    const quantity = parseInt(document.getElementById('cart-quantity').value, 10);
    const subtotal = selectedPrice * quantity;
    document.getElementById('subtotal').textContent = `Rp ${removeTrailingZeros(subtotal.toFixed(2))}`;
}

// Quantity control event listeners
document.getElementById('increase-quantity').addEventListener('click', () => {
    const quantityInput = document.getElementById('cart-quantity');
    quantityInput.value = parseInt(quantityInput.value, 10) + 1;
    updateSubtotal();
});

document.getElementById('decrease-quantity').addEventListener('click', () => {
    const quantityInput = document.getElementById('cart-quantity');
    const currentValue = parseInt(quantityInput.value, 10);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
        updateSubtotal();
    }
});

function updateDisplayedValues() {
    const selectedCombination = productCombinations.find(comb => {
        // Create a map of the combination details for quick comparison
        const combinationDetailsMap = comb.combination_details.reduce((map, detail) => {
            map[optionMap[detail.id_option]] = detail.id_option;
            return map;
        }, {});

        // Compare the selected options with the combination details
        return Object.keys(combinationDetailsMap).every(key => {
            return selectedOptions[key] === combinationDetailsMap[key].toString();
        });
    });



    if (!selectedCombination) {
        console.log("Selected combination not found.");
        window.location = baseUrl + "error?code=400&message=Bad%20Request&detailMessage=Produk%20memiliki%20kombinasi%20yang%20tidak%20valid.%20Segera%20hubungi%20admin.";

        return;
    }

    if (selectedCombination) {
        const fullPrice = selectedCombination.price;
        const hasDiscount = discount && discount > 0;


        // Update displayed variation options
        variationOptions.forEach(option => {
            if (selectedOptions[option.id_variation_type] === option.id_option) {
                document.getElementById('variation-type-' + option.id_variation_type).textContent = option.option_name;
            }
        });

        // Update price and stock
        if (hasDiscount) {
            const discountedPrice = fullPrice * (1 - discount / 100);
            document.getElementById('price').textContent = `Rp ${removeTrailingZeros(discountedPrice.toFixed(2))}`;
            document.getElementById('full-price').textContent = `Rp ${removeTrailingZeros(fullPrice.toFixed(2))}`;
            selectedPrice = removeTrailingZeros(discountedPrice.toFixed(2));

        } else {
            document.getElementById('price').textContent = `Rp ${removeTrailingZeros(fullPrice.toFixed(2))}`;
            selectedPrice = removeTrailingZeros(fullPrice.toFixed(2));
        }

        updateSubtotal();

        document.getElementById('stock-value').textContent = selectedCombination.stock;
    }
}


document.querySelector('#add-cart').addEventListener('submit', function(event) {
    event.preventDefault();

    const selectedCombination = productCombinations.find(comb => {
        // Create a map of the combination details for quick comparison
        const combinationDetailsMap = comb.combination_details.reduce((map, detail) => {
            map[optionMap[detail.id_option]] = detail.id_option;
            return map;
        }, {});

        // Compare the selected options with the combination details
        return Object.keys(combinationDetailsMap).every(key => {
            return selectedOptions[key] === combinationDetailsMap[key].toString();
        });
    });


    if (selectedCombination) {
        document.getElementById('combination-id').value = selectedCombination.id_combination;
        document.getElementById('input-quantity').value = document.getElementById('cart-quantity').value;

        event.target.submit();
    } else {
        console.log("Selected combination is not valid for adding to cart.");
    }
});

document.addEventListener('DOMContentLoaded', () => {
    // Function to update the main image
    function updateMainImage(imageUrl) {
        document.getElementById('productMainImage').src = imageUrl;
    }

    // Function to highlight the selected image
    function highlightSelectedImage(selectedElement) {
        document.querySelectorAll('.image-pagination .page-item img').forEach(img => {
            img.style.border = 'none'; // Remove highlight from all images
        });
        selectedElement.style.border = '2px solid gray'; // Highlight selected image
    }

    // Append option images to pagination if image_url is present
    const pagination = document.querySelector('.image-pagination');
    

    variationOptions.forEach(option => {
        if (option.image_url) {
            const listItem = document.createElement('li');
            listItem.className = 'page-item';

            const link = document.createElement('a');
            link.className = 'page-link';
            link.href = '#';
            link.style.textDecoration = 'none';
            link.style.color = 'inherit';

            const img = document.createElement('img');
            img.src = option.image_url;
            img.alt = `Option Image ${option.option_name}`;
            img.style.width = '50px'; // Adjust size as needed
            img.style.cursor = 'pointer'; // Add pointer cursor

            img.addEventListener('click', () => {
                updateMainImage(option.image_url);
                highlightSelectedImage(img);
            });

            link.appendChild(img);
            listItem.appendChild(link);
            pagination.insertBefore(listItem, pagination.querySelector('.page-item:last-child')); // Insert before the last item
        }
    });

    // Initialize first image if necessary
    const firstImage = pagination.querySelector('.page-item img');
    if (firstImage) {
        updateMainImage(firstImage.src);
        highlightSelectedImage(firstImage);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const reviewsContainer = document.getElementById('reviews-container');
    const reviewsCards = document.getElementById('reviews-cards');
    const reviewsHeader = document.getElementById('reviews-header');
    const prevPageBtn = document.getElementById('prev-page');
    const nextPageBtn = document.getElementById('next-page');
    const pageInfo = document.getElementById('page-info');

    let currentPage = 1;
    const reviewsPerPage = 5; // Number of reviews per page
    const allReviews = Array.from(reviewsCards.children);
    const totalPages = Math.ceil(allReviews.length / reviewsPerPage);

    function renderPage(page) {
        // Clear current reviews
        reviewsCards.innerHTML = '';

        // Calculate start and end index for slicing
        const start = (page - 1) * reviewsPerPage;
        const end = start + reviewsPerPage;
        const reviewsToShow = allReviews.slice(start, end);

        // Add reviews to the container
        reviewsToShow.forEach(review => reviewsCards.appendChild(review));

        // Update page info
        pageInfo.innerHTML = `Page ${page} of ${totalPages}`;

        // Update pagination buttons
        prevPageBtn.classList.toggle('disabled', page === 1);
        nextPageBtn.classList.toggle('disabled', page === totalPages);
    }

    // Initial render
    renderPage(currentPage);

    // Pagination button event listeners
    prevPageBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage > 1) {
            currentPage--;
            renderPage(currentPage);
        }
    });

    nextPageBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage < totalPages) {
            currentPage++;
            renderPage(currentPage);
        }
    });
});