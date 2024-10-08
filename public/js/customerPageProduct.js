
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
    document.getElementById('subtotal').textContent = `Rp ${formatPriceValue(subtotal.toFixed(2))}`;
}



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


        const decreaseButton = document.getElementById('decrease-quantity');
        const increaseButton = document.getElementById('increase-quantity');
        const quantityInput = document.getElementById('cart-quantity');

        if (parseInt(quantityInput.value, 10) > selectedCombination.stock) {
            quantityInput.value = selectedCombination.stock;
        }

        if ((parseInt(quantityInput.value, 10) == 0 && selectedCombination.stock != 0)){
            quantityInput.value = 1;
        }

        function updateButtons() {
            const quantity = parseInt(quantityInput.value, 10);
            increaseButton.disabled = quantity >= selectedCombination.stock;
            decreaseButton.disabled = quantity <= 1;
        }

        function increaseListener() {
            let quantity = parseInt(quantityInput.value, 10);
            if (quantity < selectedCombination.stock) {
                quantityInput.value = quantity + 1;
                updateSubtotal();
            }
            updateButtons();
        }

        function decreaseListener() {
            let quantity = parseInt(quantityInput.value, 10);
            if (quantity > 1) {
                quantityInput.value = quantity - 1;
                updateSubtotal();
            }
            updateButtons();
        }

        function inputListener() {
            let quantity = parseInt(quantityInput.value, 10);
            if (isNaN(quantity) || quantity < 1) {
                quantityInput.value = 1;
            } else if (quantity > selectedCombination.stock) {
                quantityInput.value = selectedCombination.stock;
            }
            updateSubtotal();
            updateButtons();
        }

        // Remove existing event listeners if they exist
        increaseButton.removeEventListener('click', window.increaseListener);
        decreaseButton.removeEventListener('click', window.decreaseListener);
        quantityInput.removeEventListener('input', window.inputListener);

        // Store the listeners in the window object to access them for removal later
        window.increaseListener = increaseListener;
        window.decreaseListener = decreaseListener;
        window.inputListener = inputListener;

        // Add event listeners
        increaseButton.addEventListener('click', increaseListener);
        decreaseButton.addEventListener('click', decreaseListener);
        quantityInput.addEventListener('input', inputListener);

        // Initial update to set the correct button states
        updateButtons();

        // Update displayed variation options
        variationOptions.forEach(option => {
            if (selectedOptions[option.id_variation_type] === option.id_option) {
                document.getElementById('variation-type-' + option.id_variation_type).textContent = option.option_name;
            }
        });

        // Update price and stock
        if (hasDiscount) {
            const discountedPrice = fullPrice * (1 - discount / 100);
            document.getElementById('price').textContent = `Rp ${formatPriceValue(removeTrailingZeros(discountedPrice.toFixed(2)))}`;
            document.getElementById('full-price').textContent = `Rp ${formatPriceValue(removeTrailingZeros(fullPrice.toFixed(2)))}`;
            selectedPrice = removeTrailingZeros(discountedPrice.toFixed(2));

        } else {
            document.getElementById('price').textContent = `Rp ${formatPriceValue(removeTrailingZeros(fullPrice.toFixed(2)))}`;
            selectedPrice = removeTrailingZeros(fullPrice.toFixed(2));
        }

        updateSubtotal();

        if(parseInt(quantityInput.value, 10) === 0){
            document.getElementById('add-to-cart-button').disabled = true
            document.getElementById('buy-button').disabled = true
        } else {
            document.getElementById('add-to-cart-button').disabled = false
            document.getElementById('buy-button').disabled = false
        }
        

        document.getElementById('stock-value').textContent = selectedCombination.stock;
    }
}


function getSelectedData() {
    const selectedCombination = productCombinations.find(comb => {
        const combinationDetailsMap = comb.combination_details.reduce((map, detail) => {
            map[optionMap[detail.id_option]] = detail.id_option;
            return map;
        }, {});

        return Object.keys(combinationDetailsMap).every(key => selectedOptions[key] === combinationDetailsMap[key].toString());
    });

    if (selectedCombination) {
        return {
            combinationId: selectedCombination.id_combination,
            quantity: document.getElementById('cart-quantity').value
        };
    }
    console.log("Selected combination is not valid for further process");
    return null;
}

document.querySelector('#buy-button').addEventListener('click', (event) => {
    event.preventDefault();
    const selectedData = getSelectedData();
    if (selectedData) {
        const encodedProductIds = encodeBase64(selectedData.combinationId.toString());
        const encodedQuantities = encodeBase64(selectedData.quantity.toString());
        const encodedIdCartItem = encodeBase64('x');
        window.location.href = baseUrl + `checkout?p=${encodedProductIds}&q=${encodedQuantities}&ici=${encodedIdCartItem}`;
    }
});

document.querySelector('#add-cart').addEventListener('click', (event) => {
    event.preventDefault();
    const selectedData = getSelectedData();
    if (selectedData) {
        document.getElementById('combination-id').value = selectedData.combinationId;
        document.getElementById('input-quantity').value = selectedData.quantity;
        event.target.closest('form').submit();
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

    // Remove duplicate images and return image URLs
    function getUniqueImageUrls() {
        const seenUrls = new Set();
        const uniqueUrls = [];
        document.querySelectorAll('.image-pagination .page-item img').forEach(img => {
            if (!seenUrls.has(img.src)) {
                seenUrls.add(img.src);
                uniqueUrls.push(img.src);
            }
        });
        return uniqueUrls;
    }

    // Remove duplicate images by URL
    function removeDuplicateImages() {
        const uniqueUrls = getUniqueImageUrls();
        const allImages = document.querySelectorAll('.image-pagination .page-item img');
        const seenUrls = new Set();

        allImages.forEach(img => {
            if (seenUrls.has(img.src)) {
                img.closest('.page-item').remove(); // Remove the parent <li> of the duplicate image
            } else {
                seenUrls.add(img.src);
            }
        });
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

    // Remove duplicates after adding new images
    removeDuplicateImages();

    // Initialize first image if necessary
    const firstImage = pagination.querySelector('.page-item img');
    if (firstImage) {
        updateMainImage(firstImage.src);
        highlightSelectedImage(firstImage);
    }

    // Add click event listeners to newly added images
    document.querySelectorAll('.image-pagination .page-item img').forEach(img => {
        img.addEventListener('click', () => {
            updateMainImage(img.src);
            highlightSelectedImage(img);
        });
    });
    
});


document.addEventListener('DOMContentLoaded', () => {
    const pagination = document.querySelector('.image-pagination');
    const items = Array.from(pagination.querySelectorAll('.page-item:not(.disabled):not(:last-child)')); // Exclude the disabled and last items

    let currentIndex = 0;

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

    // Update pagination buttons
    function updatePaginationButtons() {
        const prevButton = pagination.querySelector('.page-item.disabled');
        const nextButton = pagination.querySelector('.page-item:not(.disabled):last-child');
        
        if (currentIndex === 0) {
            prevButton.classList.add('disabled');
        } else {
            prevButton.classList.remove('disabled');
        }
        
        if (currentIndex === items.length - 1) {
            nextButton.classList.add('disabled');
        } else {
            nextButton.classList.remove('disabled');
        }
    }

    // Show the image at the given index
    function showImageAtIndex(index) {
        const img = items[index].querySelector('img');
        updateMainImage(img.src);
        highlightSelectedImage(img);
    }

    // Event listener for pagination buttons
    pagination.addEventListener('click', (event) => {
        const target = event.target.closest('.page-item');
        if (!target) return;
        
        if (target.querySelector('a').textContent === '<') {
            if (currentIndex > 0) {
                currentIndex--;
                showImageAtIndex(currentIndex);
                updatePaginationButtons();
            }
        } else if (target.querySelector('a').textContent === '>') {
            if (currentIndex < items.length - 1) {
                currentIndex++;
                showImageAtIndex(currentIndex);
                updatePaginationButtons();
            }
        } else {
            // Clicked on an image
            const imgIndex = items.indexOf(target);
            if (imgIndex !== -1) {
                currentIndex = imgIndex;
                showImageAtIndex(currentIndex);
                updatePaginationButtons();
            }
        }
    });

    // Initialize first image and pagination buttons
    if (items.length > 0) {
        showImageAtIndex(0);
        updatePaginationButtons();
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