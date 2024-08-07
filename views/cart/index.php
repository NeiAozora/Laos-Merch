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
                        <div class="card mb-4">
                            <div class="card-body row justify-content-between align-items-center">
                                <div class="col-lg-6 col-md-6 col-12 d-flex">
                                    <input type="checkbox" class="checkbox-item cursor-pointer me-3" data-price="<?= $item['price'] ?>" data-id="<?= $item['id_cart_item'] ?>">
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
                                        <form method="post" action="/cart/remove" style="display: inline;">
                                            <input type="hidden" name="id_cart_item" value="<?= $item['id_cart_item'] ?>">
                                            <button type="submit" class="decoration-none" style="border: none; background: none;"><i class="fa-solid fa-trash" style="color: red;"></i></button>
                                        </form>
                                    </div>
                                    <div class="d-flex justify-content-end my-2">
                                        <form method="post" action="/cart/update" style="display: inline;">
                                            <input type="hidden" name="id_cart_item" value="<?= $item['id_cart_item'] ?>">
                                            <div class="input-group" style="width: 120px;">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="btn btn-outline-secondary" onclick="decrementQuantity(this)" style="border-radius: 0.25rem 0 0 0.25rem; border-color: #ced4da;">-</button>
                                                </div>
                                                <input type="text" name="quantity" class="form-control text-center" value="<?= $item['quantity'] ?>" readonly>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary" onclick="incrementQuantity(this)" style="border-radius: 0 0.25rem 0.25rem 0; border-color: #ced4da;">+</button>
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
                        <button class="btn btn-success mt-2" style="width: 12rem;">Beli</button>
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
            totalCost += quantity * price;
        });

        document.getElementById('totalCost').innerText = `Rp. ${totalCost}`;
    }

    // Handle select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.checkbox-item');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateTotalCost();
    });

    // Handle individual checkbox change
    const checkboxes = document.querySelectorAll('.checkbox-item');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            document.getElementById('selectAll').checked = allChecked;
            updateTotalCost();
        });
    });

    // Handle quantity change
    function decrementQuantity(button) {
        let quantityInput = button.closest('.input-group').querySelector('input[name="quantity"]');
        let quantity = parseInt(quantityInput.value);
        if (quantity > 1) {
            quantityInput.value = quantity - 1;
        }
        updateTotalCost();
    }

    function incrementQuantity(button) {
        let quantityInput = button.closest('.input-group').querySelector('input[name="quantity"]');
        let quantity = parseInt(quantityInput.value);
        quantityInput.value = quantity + 1;
        updateTotalCost();
    }
</script>

<?php
requireView("partials/footer.php");
?>