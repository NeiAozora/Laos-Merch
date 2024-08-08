<?php
requireView("partials/head.php");
requireView("partials/navbar.php");
?>

<section class="content">
    <div class="container">
        <h5>Checkout</h5>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="card mb-3">
                    <div class="card-body row justify-content-between align-items-center">
                        <div class="col-lg-6 col-md-6 col-12 d-flex align-items-start">
                            <div class="ms-3">
                                <h5 class="title-detail mb-2">Info Pembeli:</h5>
                                <div style="display: flex;">
                                    <div class="me-3">
                                        <h6>Nama:</h6>
                                        <h6>No.Telepon:</h6>
                                        <h6>Alamat:</h6>
                                    </div>
                                    <div class="me-3">
                                        <h6 style="font-family: nunito; font-weight:lighter;"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h6>
                                        <h6 style="font-family: nunito; font-weight:lighter;"><?php echo htmlspecialchars($user['wa_number']); ?></h6>
                                        <h6 style="font-family: nunito; font-weight:lighter;"><?php echo htmlspecialchars($user['picture']); // Adjust this if it's not an address ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12 text-end">
                            <div class="position-top-right">
                                <a href="#" class="me-3 decoration-none">
                                    Ubah Alamat
                                    <i class="fa-solid fa-pen-to-square ms-2" style="color: gold;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="title-detail mb-2">Jenis Pengiriman:</h5>
                        <select class="form-control" name="shipping_method" id="shipping_method">
                            <option value="disabled" disabled selected>Pilih Pengiriman</option>
                            <?php foreach ($shipping_methods as $shipping_method): ?>
                                <option value="<?php echo htmlspecialchars($shipping_method['id_carrier']); ?>"><?php echo htmlspecialchars($shipping_method['carrier_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="title-detail mb-2">Metode Pembayaran:</h5>
                        <select class="form-control" name="payment_method" id="payment_method">
                            <option value="disabled" disabled selected>Pilih Pembayaran</option>
                            <?php foreach ($payment_methods as $payment_method): ?>
                                <option value="<?php echo htmlspecialchars($payment_method['id_payment_method']); ?>"><?php echo htmlspecialchars($payment_method['payment_method_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body row justify-content-between align-items-center">
                        <h5 class="title-detail mb-2">Produk:</h5>
                        <?php foreach ($products as $product): ?>
                            <div class="mb-2">
                                <div class="card">
                                    <div class="card-body row justify-content-between align-items-center">
                                        <div class="col-sm-4 col-md-4 col-12 d-flex">
                                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image" class="img-fluid">
                                            <div class="product-name ms-3">
                                                <h4><?php echo htmlspecialchars($product['product_name']); ?></h4>
                                                <div style="display: flex;">
                                                    <div class="me20">
                                                        <h6 class="title-detail">Variasi:</h6>
                                                        <p><?php echo htmlspecialchars($product['selected_options']); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-md-4 col-12">
                                            <h6 class="title-detail mb-1">Jumlah:</h6>
                                            <h5><?php echo htmlspecialchars($product['quantity']); ?></h5>
                                            <h6 class="title-detail">Total:</h6>
                                            <div>
                                                <!-- Discounted Price -->
                                                <h5>Rp. <?php echo rtrim(rtrim(number_format($product['quantity'] * ($product['price'] * (1 - ($product['discount_value'] / 100))), 2), '0'), '.'); ?></h5>
                                                
                                                <!-- Original Price -->
                                                <?php if ($product['discount_value'] > 0): ?>
                                                    <span style="text-decoration: line-through; color: #888;">Rp <?php echo rtrim(rtrim(number_format($product['price'] * $product['quantity'], 2), '0'), '.'); ?></span>
                                                <?php endif; ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-10 col-sm-12">
                <div class="card d-flex flex-column justify-content-between">
                    <h4 class="m-2 title-detail text-start">Rincian:</h4>
                    <div class="ms-2">
                        <h5 class="me20">Nama Pembeli:</h5>
                        <h6><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h6>
                        <h5 class="me20 mt-3">Metode Pembayaran:</h5>
                        <h6 id="selected_payment_method">Select payment method</h6>
                        <h5 class="me20 mt-3">Jenis Pengiriman:</h5>
                        <h6 id="selected_shipping_method">Select shipping method</h6>
                        <h5 class="mt-5 me20">Total:</h5>
                        <h6 id="total_amount">Rp. <?php echo number_format($total_price, 2); ?></h6>
                    </div>
                    <div class="mt-2 text-center mb-3">
                        <button class="btn btn-success" style="width: 12rem;" onclick="submitOrder()">Lanjut Bayar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.getElementById('shipping_method').addEventListener('change', function() {
        var selectedMethod = this.options[this.selectedIndex].text;
        document.getElementById('selected_shipping_method').textContent = selectedMethod;
    });

    document.getElementById('payment_method').addEventListener('change', function() {
        var selectedMethod = this.options[this.selectedIndex].text;
        document.getElementById('selected_payment_method').textContent = selectedMethod;
    });

    function submitOrder() {
        // Form submission logic here
    }
</script>


<?php
requireView("partials/footer.php");
?>