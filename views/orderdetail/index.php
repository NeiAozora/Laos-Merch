<?php
requireView("partials/head.php");
requireView("partials/navbar.php");

$order = $data['order'];
?>

<section class="content">
    <div class="container mt-3">
        <h4>Detail Pesanan</h4>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="card mb-3">
                    <div class="card-body row justify-content-between align-items-center">
                        <div class="col-lg-6 col-md-6 col-12 d-flex align-items-start">
                            <h5 class="title-detail">Pesanan:</h5>
                        </div>
                    </div>

                    <div class="collapse" id="collapseTrack">
                        <div class="card card-body mb-2 ms-3 me-3">
                            <h>Detail Pelacakan</h>
                            <p>Informasi pelacakan pesanan Anda akan ditampilkan di sini.</p>
                        </div>
                    </div>

                    <div class="card-body d-flex justify-content-between">
                        <div class="d-flex flex-column">
                            <div class="d-flex flex-column text-end">
                                <h6>No. Invoice:</h6>
                                <h6>Tanggal Pembelian:</h6>
                                <h6>Status:</h6>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-start ms-3">
                            <div class="position-top-right">
                                <a class="title-detail decoration-none" data-bs-toggle="collapse" href="#collapseTrack" role="button" aria-expanded="false" aria-controls="collapseTrack">
                                    Lacak Pesanan
                                </a>
                            </div>
                            <h6><?= htmlspecialchars($order['id_order']); ?></h6>
                            <h6><?= htmlspecialchars($order['order_date']); ?></h6>
                            <h6><?= htmlspecialchars($order['status_name']); ?></h6>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body row justify-content-between align-items-center">
                        <h5 class="title-detail mb-2">Produk:</h5>
                        <div class="mb-2">
                            <div class="card">
                                <div class="card-body row justify-content-between align-items-center">
                                    <div class="col-sm-4 col-md-4 col-12 d-flex">
                                        <img src="<?= htmlspecialchars($order['image_url']); ?>" alt="Produk" class="img-fluid rounded">
                                        <div class="product-name ms-3">
                                            <h4><?= htmlspecialchars($order['product_name']); ?></h4>
                                            <div style="display: flex;">
                                                <div class="me20">
                                                    <h6 class="title-detail">Variasi Produk:</h6>
                                                    <p><?= htmlspecialchars($order['option_names']); ?></p>
                                                </div>
                                                <!-- <div class="me20">
                                                    <h6 class="title-detail">Ukuran:</h6>
                                                    <p><?= htmlspecialchars($order['option_names']); ?></p>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-12">
                                        <h6 class="title-detail mb-1">Jumlah:</h6>
                                        <h5><?= htmlspecialchars($order['quantity']); ?></h5>
                                        <h6 class="title-detail">Total:</h6>
                                        <h5>Rp. <?= number_format($order['total_price'], 0, ',', '.'); ?></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="title-detail mb-3">Info Pengiriman:</h5>
                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-column text-end">
                                    <h6>Kurir:</h6>
                                    <h6>Pembeli:</h6>
                                </div>
                            </div>
                            <div class="d-flex flex-column text-start ms-3">
                                <h6><?= htmlspecialchars($order['shipping_method']); ?></h6>
                                <h6 class="mb-0"><?= htmlspecialchars($order['recipient_name']); ?></h6>
                                <h6 class="mb-0"><?= htmlspecialchars($order['wa_number']); ?></h6>
                                <h6 class="mb-0"><?= htmlspecialchars($order['address']); ?></h6>
                            </div>
                        </div>  
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="title-detail mb-3">Info Pembayaran:</h5>
                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-column text-end">
                                    <h6>Metode Pembayaran:</h6>
                                    <h6>Total Harga:</h6>
                                    <h6>Total Ongkos Kirim:</h6>
                                    <h5 class="mt-1" style="font-weight: bold;">Total Belanja:</h5>
                                </div>
                            </div>
                            <div class="d-flex flex-column text-start ms-3">
                                <h6>COD</h6>
                                <h6>Rp. <?= number_format($order['total_price'], 0, ',', '.'); ?></h6>
                                <h6>Rp. 0</h6>
                                <h5 class="mt-1" style="font-weight: bold;">Rp. <?= number_format($order['total_price'], 0, ',', '.'); ?></h5>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-10 col-sm-12">
                <div class="d-flex flex-column justify-content-center mt-1">
                    <a class="btn laos-outline-button mb-3" onclick="history.back()">Kembali</a>
                    <a type="button" class="btn btn-success mb-3" href="https://wa.me/+6285606689642" target="_blank">Hubungi Admin</a>
                    <button class="btn btn-secondary">Batalkan Pesanan</button>
                </div>
            </div>  
        </div>
    </div>
</section>

<?php
requireView("partials/footer.php");
?>
