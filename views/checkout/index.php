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
                            <a href="#" class="me-3 decoration-none" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
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
                                <option value="<?php echo htmlspecialchars($shipping_method['id_shipment_company']); ?>"><?php echo htmlspecialchars($shipping_method['company_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="title-detail mb-2">Metode Pembayaran:</h5>
                        <div class="d-flex flex-wrap">
                            <?php foreach ($payment_methods as $payment_method): ?>
                                <div class="form-check me-3 mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_method_<?php echo htmlspecialchars($payment_method['id_payment_method']); ?>" value="<?php echo htmlspecialchars($payment_method['id_payment_method']); ?>">
                                    <label class="form-check-label" for="payment_method_<?php echo htmlspecialchars($payment_method['id_payment_method']); ?>">
                                        <?php echo htmlspecialchars($payment_method['method_name']); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div id="snap-embed-container"></div>
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
                                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image" class="img-fluid" style="max-height: 5rem; border-radius:8px">
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
                        <h6 id="selected_payment_method">Pilih Metode Pembayaran</h6>
                        <h5 class="me20 mt-3">Jenis Pengiriman:</h5>
                        <h6 id="selected_shipping_method">Pilih Metode Pengiriman</h6>
                        <h5 class="mt-5 me20">Total:</h5>
                        <h6 id="total_amount">Rp. <?php echo number_format($total_price, 2); ?></h6>
                    </div>
                    <div class="mt-2 text-center mb-3">
                        <button class="btn btn-success" style="width: 12rem;" id="submitOrderButton">Lanjut Bayar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Pilih Alamat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Search bar -->
        <div class="mb-3 position-relative">
            <span class="position-absolute top-50 start-0 translate-middle-y ms-3">
                <i class="fa-solid fa-magnifying-glass text-muted"></i>
            </span>
            <input type="text" class="form-control ps-5" placeholder="Cari alamat yang dimiliki">
        </div>

        <!-- Add new address button -->
        <button type="button" class="btn btn-outline-success w-100 mb-3">Kelola Alamat</button>

        <!-- First Address Card -->
        <div class="card mb-3">
          <div class="card-body" style="background-color: #034d26; color: #fff;">
            <div class="d-flex justify-content-between">
              <div>
                <h6 class="card-title mb-1">Rumah <span class="badge bg-warning text-dark">Utama</span></h6>
                <p class="card-text mb-1">Ahmad Fauzan</p>
                <p class="card-text">6283199624458</p>
                <p class="card-text">Perempatan puskesmas tembokrejo munca...</p>
              </div>
              <div>
                <i class="fa-solid fa-check text-success" style="font-size: 24px;"></i>
              </div>
            </div>
            <div class="d-flex justify-content-between mt-3">
              <a href="#" class="text-light"></a>
              <div>
                <a href="#" class="text-light">Ubah Alamat</a>
              </div>
            </div>
          </div>
        </div>

        <!-- Second Address Card -->
        <div class="card mb-3">
          <div class="card-body" style="background-color: #1e1e1e; color: #fff;">
            <div class="d-flex justify-content-between">
              <div>
                <h6 class="card-title mb-1">Kos</h6>
                <p class="card-text mb-1">Ahmad Fauzan</p>
                <p class="card-text">6283199624458</p>
                <p class="card-text">Jl. Kalimantan X, Gang X, Rumah no. 6...</p>
              </div>
              <div class="text-end">
                <button type="button" class="btn btn-outline-success mb-2">Pilih</button>
              </div>
            </div>
            <div class="d-flex justify-content-between mt-3">
              <a href="#" class="text-light"></a>
              <div>
                <a href="#" class="text-light me-3">Ubah Alamat</a>
                <a href="#" class="text-light me-3">Jadikan Alamat Utama & Pilih</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<style>
  .modal-body .form-control {
    background-color: #1e1e1e;
    border: 1px solid #04d4a4;
    color: #fff;
  }
  

  .modal-content {
    background-color: #121212;
  }
  
  .card {
    border: none;
  }

  .card-body {
    border-radius: 8px;
  }

  .card-body .text-light {
    font-size: 0.9rem;
  }
</style>






<script>
    const selected = '<?= "p=" . $_GET['p'] . "&q=" . $_GET['q'] ?>';
</script>
<script type="text/javascript" 
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="Mid-client-VVa8MqsfFdh9WpVr"></script>
<script src="<?= BASEURL ?>public/js/checkout.js"></script>
<?php
requireView("partials/footer.php");
?>