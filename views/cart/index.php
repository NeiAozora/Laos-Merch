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
                                    <input type="checkbox" class="checkbox-item cursor-pointer me-3">
                                    <img src="<?= $item['image_url'] ?>" alt="<?= $item['product_name'] ?>" class="img-fluid">
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
                                        <a href="#" class="me-3 decoration-none"><i class="fa-solid fa-pen-to-square" style="color: gold;"></i></a>
                                        <form method="post" action="/cart/remove" style="display: inline;">
                                            <input type="hidden" name="id_cart_item" value="<?= $item['id_cart_item'] ?>">
                                            <button type="submit" class="decoration-none" style="border: none; background: none;"><i class="fa-solid fa-trash" style="color: red;"></i></button>
                                        </form>
                                    </div>
                                    <div class="d-flex justify-content-end my-2">
                                        <form method="post" action="/cart/update" style="display: inline;">
                                            <input type="hidden" name="id_cart_item" value="<?= $item['id_cart_item'] ?>">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="input-group-text cursor-pointer" onclick="decrementQuantity(this)">-</button>
                                                </div>
                                                <input type="text" name="quantity" class="form-control text-center" value="<?= $item['quantity'] ?>" readonly>
                                                <div class="input-group-append">
                                                    <button type="button" class="input-group-text cursor-pointer" onclick="incrementQuantity(this)">+</button>
                                                </div>
                                            </div>
                                            <button type="submit" style="display: none;"></button>
                                        </form>
                                    </div>
                                    <h5>Rp. <?= $item['price'] * $item['quantity'] ?></h5>
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
                        <h5>Rp. <?= $totalCost ?></h5>
                    </div>
                    <div class="mt-auto text-center mb-3">
                        <button class="btn btn-success mt-2" style="width: 12rem;">Beli</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- select all checkbox cart -->
<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.checkbox-item');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked
        });
    });
    const checkboxes = document.querySelectorAll('.checkbox-item');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            document.getElementById('selectAll').checked = allChecked;
        });
    });
</script>

<!-- button plus minus -->
<script>
    function decrementQuantity(button) {
        let quantityInput = button.closest('.input-group').querySelector('input[name="quantity"]');
        let quantity = parseInt(quantityInput.value);
        if (quantity > 1) {
            quantityInput.value = quantity - 1;
        }
    }

    function incrementQuantity(button) {
        let quantityInput = button.closest('.input-group').querySelector('input[name="quantity"]');
        let quantity = parseInt(quantityInput.value);
        quantityInput.value = quantity + 1;
    }
</script>

<?php
requireView("partials/footer.php");
?>