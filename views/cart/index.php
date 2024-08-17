<?php
requireView("partials/head.php");
requireView("partials/navbar.php");
?>

<style>
.cart-image-container {
    width: 7rem; /* Set a fixed width for the image container */
    height: 5rem; /* Set a fixed height for the image container */
    display: flex;
    justify-content: center; /* Center the image horizontally */
    align-items: center; /* Center the image vertically */
    overflow: hidden; /* Hide any overflow if the image is too large */
    padding: 0; /* Remove any padding if present */
    margin: 0; /* Remove any margin if present */
}

.cart-image-container img {
    max-width: 100%; /* Ensure the image fits within the container's width */
    max-height: 100%; /* Ensure the image fits within the container's height */
    object-fit: contain; /* Maintain the aspect ratio of the image */
    display: block; /* Ensure the image behaves like a block element */
}


</style>
<section class="content mt-3">
    <div class="container">
        <h4>Keranjang</h4>
        <div class="row justify-content-center">
            <div class="col-12 text-start mb-2">
                <input type="checkbox" class="cursor-pointer" id="selectAll">
                <label for="selectAll" class="title-detail">Pilih Semua</label>
            </div>
            <div class="col-lg-8 col-md-10 col-sm-12">
                <?php if (!empty($cartItems)) : ?>
                    <?php foreach ($cartItems as $item) : ?>
                        <div class="card mb-4" id="cart-item-<?= $item['id_cart_item'] ?>">
                            <div class="card-body row justify-content-between align-items-center">
                                <div class="col-lg-6 col-md-6 col-12 d-flex">
                                <input type="checkbox" class="checkbox-item cursor-pointer me-3" data-price="<?= $item['price'] ?>" data-id="<?= $item['id_combination'] ?>" data-max-quantity="<?= $item['quantity'] ?>" data-discount="<?= $item['discount_value'] ?>">
                                    <div class="cart-image-container d-flex justify-content-center align-items-center">
                                        <img src="<?= $item['image_url'] ?>" alt="<?= $item['product_name'] ?>" class="img-fluid" style="max-height: 5rem; border-radius:8px;">
                                    </div>

                                    <div class="ms-3">
                                        <div class="d-flex">    
                                            <h4><?= $item['product_name'] ?></h4>
                                            <?php if (($item['discount_value']) > 0): ?>
                                                <div class="discount-label-container">
                                                    <svg class="discount-label-svg-image" viewBox="0 0 79 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M0 0H79V30H0L11 15L0 0Z" fill="#D7211E"/>
                                                    </svg>
                                                    <div class="discount-label-overlay-text">-<?= round($item['discount_value']) ?>%</div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div style="display: flex;">
                                            <div class="me-3">
                                                <h6 class="title-detail">Variasi:</h6>
                                                <p><?= $item['option_name'] ?></p>
                                            </div>
                                            <div class="me-3">
                                                <h6 class="title-detail">Ukuran:</h6>
                                                <p><?= $item['dimensions'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-12 text-end">
                                    <div class="d-flex justify-content-end align-items-center">
                                        <button type="button" class="btn btn-link" onclick="confirmDelete(<?= $item['id_cart_item'] ?>)">
                                            <i class="fa-solid fa-trash" style="color: red;"></i>
                                        </button>
                                    </div>
                                    <div class="d-flex justify-content-end my-2">
                                        <form method="post" action="/cart/update" style="display: inline;">
                                            <input type="hidden" name="id_cart_item" value="<?= $item['id_cart_item'] ?>">
                                            <div class="input-group" style="width: 120px;">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="btn btn-outline-secondary" onclick="decrementQuantity(this)" style="border-radius: 0.25rem 0 0 0.25rem; border-color: #ced4da;">-</button>
                                                </div>
                                                <input type="text" name="quantity" id="input-<?= $item['id_cart_item'] ?>" class="form-control text-center" value="<?= $item['quantity'] ?>" readonly>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary increment-btn" onclick="incrementQuantity(this)" style="border-radius: 0 0.25rem 0.25rem 0; border-color: #ced4da;" disabled>+</button>
                                                </div>
                                            </div>
                                            <button type="submit" style="display: none;"></button>
                                        </form>
                                    </div>

                                    <div>
                                        <h5 class="item-total" id="total-price-per-item-<?= $item['id_cart_item'] ?>">
                                            Rp. <?= formatPriceValue(($item['price'] * (1 - $item['discount_value'] / 100) * $item['quantity'])) ?>
                                        </h5>
                                        <?php if (($item['discount_value'] > 0)): ?> 
                                            <h6 class="original-price" style="text-decoration: line-through; color: #888;">
                                                Rp. <?= (formatPriceValue($item['price'] * $item['quantity'])) ?>
                                            </h6>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php else : ?>
                    <p>Your cart is empty.</p>
                <?php endif; ?>
            </div>

            <div class="col-lg-4 col-md-10 col-sm-12">
                <div class="card d-flex flex-column justify-content-between">
                    <h5 class="mt-2 text-center">Total Belanja</h5>
                    <div class="ms-2">
                        <h4>Total:</h4>
                        <h5 id="totalCost">Rp. <?= $totalCost ?></h5>
                    </div>
                    <div class="mt-auto text-center mb-3">
                        <button class="btn btn-success mt-2" style="width: 12rem;" onclick="redirectToCheckout()">Beli</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>

    function updateTotalCost() {
        let totalCost = 0;
        const selectedItems = document.querySelectorAll('.checkbox-item:checked');
        
        selectedItems.forEach(checkbox => {
            const quantityInput = checkbox.closest('.card-body').querySelector('input[name="quantity"]');
            const quantity = parseInt(quantityInput.value);
            const price = parseFloat(checkbox.getAttribute('data-price'));
            const discount = parseFloat(checkbox.getAttribute('data-discount')) || 0;
            
            // Calculate the discounted price
            const discountedPrice = price * (1 - discount / 100);
            totalCost += quantity * discountedPrice;
        });

        document.getElementById('totalCost').innerText = `Rp. ${formatPriceValue(totalCost)}`;
    }

    function confirmDelete(cartItemId) {
    let quantity = document.getElementById("input-" + cartItemId).value;
    if (confirm('Are you sure you want to delete this item?')) {
        let url = baseUrl + `cart/remove?id_cart_item=${cartItemId}&quantity=${quantity}`;
        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const cardBody = document.getElementById(`cart-item-${cartItemId}`);
            if (data.action === 'update') {
                if (data.new_quantity !== undefined) {
                    const quantityInput = cardBody.querySelector('input[name="quantity"]');
                    const maxQuantity = parseInt(cardBody.querySelector('.checkbox-item').getAttribute('data-max-quantity'));
                    
                    // Update the quantity and the max quantity
                    quantityInput.value = data.new_quantity;
                    cardBody.querySelector('.checkbox-item').setAttribute('data-max-quantity', data.new_quantity);
                    
                    // Update the price and original price
                    updateItemPrices(cardBody, data.new_quantity);

                    updateTotalCost();
                    toggleIncrementButton(cardBody.querySelector('.checkbox-item'));
                }
            } else if (data.action === 'delete' && Object.keys(data).length === 1) {
                cardBody.remove();
                updateTotalCost();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function updateItemPrices(cardBody, newQuantity) {
    const price = parseFloat(cardBody.querySelector('.checkbox-item').getAttribute('data-price'));
    const discountValue = parseFloat(cardBody.querySelector('.checkbox-item').getAttribute('data-discount')) || 0;

    // Calculate discounted price
    const discountedPrice = price * (1 - discountValue / 100);
    const totalItemPrice = discountedPrice * newQuantity;
    
    // Update the displayed prices
    cardBody.querySelector('.item-total').innerText = `Rp. ${formatPriceValue(totalItemPrice)}`;
    if (discountValue > 0) {
        cardBody.querySelector('.original-price').innerText = `Rp. ${formatPriceValue(price * newQuantity)}`;
    }
}


    // Handle select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.checkbox-item');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
            toggleIncrementButton(checkbox);
        });
        updateTotalCost();
    });

    // Handle individual checkbox change
    const checkboxes = document.querySelectorAll('.checkbox-item');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            document.getElementById('selectAll').checked = allChecked;
            toggleIncrementButton(checkbox);
            updateTotalCost();
        });
    });

// Handle quantity decrement
function decrementQuantity(button) {
    let cardBody = button.closest('.card-body');
    let quantityInput = cardBody.querySelector('input[name="quantity"]');
    let quantity = parseInt(quantityInput.value);
    let price = parseFloat(cardBody.querySelector('.checkbox-item').getAttribute('data-price'));
    let discountValue = parseFloat(cardBody.querySelector('.checkbox-item').getAttribute('data-discount')) || 0;

    if (quantity > 1) {
        quantityInput.value = quantity - 1;
        let discountedPrice = price * (1 - discountValue / 100);
        let totalItemPrice = discountedPrice * (quantity - 1);
        cardBody.querySelector('.item-total').innerText = `Rp. ${formatPriceValue(totalItemPrice)}`;
        if(discountValue != 0){
            cardBody.querySelector('.original-price').innerText = `Rp. ${formatPriceValue(price * ( parseInt(quantityInput.value)))}`;
        }
        updateTotalCost();
    }
    toggleIncrementButton(cardBody.querySelector('.checkbox-item'));

}

// Handle quantity increment
function incrementQuantity(button) {
    let cardBody = button.closest('.card-body');
    let quantityInput = cardBody.querySelector('input[name="quantity"]');
    let quantity = parseInt(quantityInput.value);
    let maxQuantity = parseInt(cardBody.querySelector('.checkbox-item').getAttribute('data-max-quantity'));
    let price = parseFloat(cardBody.querySelector('.checkbox-item').getAttribute('data-price'));
    let discountValue = parseFloat(cardBody.querySelector('.checkbox-item').getAttribute('data-discount')) || 0;

    if (quantity < maxQuantity) {
        quantityInput.value = quantity + 1;
        let discountedPrice = price * (1 - discountValue / 100);
        let totalItemPrice = discountedPrice * (quantity + 1);
        cardBody.querySelector('.item-total').innerText = `Rp. ${formatPriceValue(totalItemPrice)}`;
        if(discountValue != 0){
            cardBody.querySelector('.original-price').innerText = `Rp. ${formatPriceValue(price * ( parseInt(quantityInput.value)))}`;
        }
        updateTotalCost();
    }
    toggleIncrementButton(cardBody.querySelector('.checkbox-item'));
}

    function toggleIncrementButton(checkbox) {
        const quantityInput = checkbox.closest('.card-body').querySelector('input[name="quantity"]');
        const incrementButton = checkbox.closest('.card-body').querySelector('.increment-btn');
        const quantity = parseInt(quantityInput.value);
        const maxQuantity = parseInt(checkbox.getAttribute('data-max-quantity'));
        incrementButton.disabled = quantity >= maxQuantity;
    }

    function redirectToCheckout() {
        let selectedItems = document.querySelectorAll('.checkbox-item:checked');
        let productIds = [];
        let quantities = [];
        let cartItemIds = [];

        selectedItems.forEach(item => {
            let productId = item.getAttribute('data-id');
            let quantity = parseInt(item.closest('.card-body').querySelector('input[name="quantity"]').value);
            let cartItemId = parseInt(item.closest('.card-body').querySelector('input[name="id_cart_item"]').value);
            productIds.push(productId);
            quantities.push(quantity);
            cartItemIds.push(cartItemId);
        });

        if (productIds.length > 0) {
            // Join the arrays into comma-separated strings
            const productIdsString = productIds.join(',');
            const quantitiesString = quantities.join(',');
            const cartItemIdsString = cartItemIds.join(',');

            // Encode the comma-separated strings using Base64
            const encodedProductIds = encodeBase64(productIdsString);
            const encodedQuantities = encodeBase64(quantitiesString);
            const encodedcartItemIds = encodeBase64(cartItemIdsString);

            // Construct the query string with the encoded parameters
            let url = baseUrl + `checkout?p=${encodedProductIds}&q=${encodedQuantities}&ici=${encodedcartItemIds}`;
            window.location.href = url;
        } else {
            alert('Please select at least one item to proceed.');
        }
    }

</script>


<?php
requireView("partials/footer.php");
?>