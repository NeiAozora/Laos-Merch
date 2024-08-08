<?php
requireView("partials/head.php");
requireView("partials/navbar.php");
?>
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
                                    <input type="checkbox" class="checkbox-item cursor-pointer me-3" data-price="<?= $item['price'] ?>" data-id="<?= $item['id_combination'] ?>" data-max-quantity="<?= $item['quantity'] ?>">
                                    <img src="<?= $item['image_url'] ?>" alt="<?= $item['product_name'] ?>" class="img-fluid" style="max-height: 5rem; border-radius:8px">
                                    <div class="ms-3">
                                        <h4><?= $item['product_name'] ?></h4>
                                        <div style="display: flex;">
                                            <div class="me-3">
                                                <h6 class="title-detail">Warna:</h6>
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
                                                    <button type="button" class="btn btn-outline-secondary increment-btn" onclick="incrementQuantity(this)" style="border-radius: 0 0.25rem 0.25rem 0; border-color: #ced4da;">+</button>
                                                </div>
                                            </div>
                                            <button type="submit" style="display: none;"></button>
                                        </form>
                                    </div>

                                    <h5 class="item-total">Rp. <?= $item['price'] * $item['quantity'] ?></h5>
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
        // Utility function to encode data to Base64
    function encodeBase64(data) {
        // Convert string to Uint8Array
        const encoder = new TextEncoder();
        const uint8Array = encoder.encode(data);
        // Convert Uint8Array to Base64 string
        return btoa(String.fromCharCode(...uint8Array));
    }


    function updateTotalCost() {
        let totalCost = 0;
        const selectedItems = document.querySelectorAll('.checkbox-item:checked');
        
        selectedItems.forEach(checkbox => {
            const quantityInput = checkbox.closest('.card-body').querySelector('input[name="quantity"]');
            const quantity = parseInt(quantityInput.value);
            const price = parseFloat(checkbox.getAttribute('data-price'));
            totalCost += quantity * price;
        });

        document.getElementById('totalCost').innerText = `Rp. ${totalCost}`;
    }

    function confirmDelete(cartItemId) {
        let quantity = document.getElementById("input-" + cartItemId).value;
        if (confirm('Are you sure you want to delete this item?')) {
            // Make AJAX request to delete the item
            let url = baseUrl + `cart/remove?id_cart_item=${cartItemId}&quantity=${quantity}`;
            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.action === 'update') {
                    // Update the row with new data
                    const cardBody = document.getElementById(`cart-item-${cartItemId}`);
                    if (data.new_quantity !== undefined) {
                        const quantityInput = cardBody.querySelector('input[name="quantity"]');
                        const maxQuantity = parseInt(cardBody.querySelector('.checkbox-item').getAttribute('data-max-quantity'));
                        quantityInput.value = data.new_quantity;
                        cardBody.querySelector('.checkbox-item').setAttribute('data-max-quantity', data.new_quantity);
                        updateTotalCost();
                        toggleIncrementButton(cardBody.querySelector('.checkbox-item'));
                    }
                } else if (data.action === 'delete' && Object.keys(data).length === 1) {
                    // Delete the row
                    const cardBody = document.getElementById(`cart-item-${cartItemId}`);
                    cardBody.remove();
                    updateTotalCost();
                }
            })
            .catch(error => console.error('Error:', error));
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

    // Handle quantity change
    function decrementQuantity(button) {
        let quantityInput = button.closest('.input-group').querySelector('input[name="quantity"]');
        let quantity = parseInt(quantityInput.value);
        if (quantity > 1) {
            quantityInput.value = quantity - 1;
            updateTotalCost();
            toggleIncrementButton(button.closest('.card-body').querySelector('.checkbox-item'));
        }
    }

    function incrementQuantity(button) {
        let quantityInput = button.closest('.input-group').querySelector('input[name="quantity"]');
        let quantity = parseInt(quantityInput.value);
        let maxQuantity = parseInt(button.closest('.card-body').querySelector('.checkbox-item').getAttribute('data-max-quantity'));
        if (quantity < maxQuantity) {
            quantityInput.value = quantity + 1;
            updateTotalCost();
        }
        toggleIncrementButton(button.closest('.card-body').querySelector('.checkbox-item'));
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

        selectedItems.forEach(item => {
            let productId = item.getAttribute('data-id');
            let quantity = parseInt(item.closest('.card-body').querySelector('input[name="quantity"]').value);
            productIds.push(productId);
            quantities.push(quantity);
        });

        if (productIds.length > 0) {
            const encodedProductIds = productIds.map(id => encodeBase64(id));
            const encodedQuantities = quantities.map(qty => encodeBase64(qty));
            
            // Construct query string parameters
            const productParams = encodedProductIds.map((id, index) => `p${index + 1}=${id}`).join('&');
            const quantityParams = encodedQuantities.map((qty, index) => `q${index + 1}=${qty}`).join('&');

            // Redirect to checkout with encoded parameters
            window.location.href = `/checkout?${productParams}&${quantityParams}`;
        } else {
            alert('Please select at least one item to proceed.');
        }
    }
</script>


<?php
requireView("partials/footer.php");
?>