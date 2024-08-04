<?php
requireView("partials/head.php");
requireView("partials/navbar.php");

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

                    <!-- collapse -->
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
                            <h6>INV/22222/3333/444/55</h6>
                            <h6>Kamis, 16 Juli 2024</h6>
                            <h6>Diproses</h6>
                        </div>
                    </div>  

                    <!-- <div class="card-body row justify-content-between align-items-center">
                        <div class="col-lg-6 col-md-6 col-12 d-flex align-items-start">
                            <div class="d-flex flex-column">

                                <div class="d-flex flex-column text-end">
                                    <h6>No. Invoice:</h6>
                                    <h6>Tanggal Pembelian:</h6>
                                    <h6>Status:</h6>
                                </div>

                            </div>
                            
                        </div>
                        <div class="col-lg-6 col-md-6 col-12 d-flex align-items-end">
                            <div class="position-top-right">
                                <a class="title-detail decoration-none" data-bs-toggle="collapse" href="#collapseTrack" role="button" aria-expanded="false" aria-controls="collapseTrack">
                                    Lacak Pesanan
                                </a>
                            </div>
                            <div class="d-flex flex-column text-start ms-3">
                                <h6>INV/22222/3333/444/55</h6>
                                <h6>Kamis, 16 Juli 2024</h6>
                                <h6>Diproses</h6>
                            </div>
                        </div>
                    </div> -->
                </div>

                <div class="card mb-3">
                    <div class="card-body row justify-content-between align-items-center">
                        <h5 class="title-detail mb-2">Produk:</h5>
                        <div class="mb-2">
                            <div class="card">
                                <div class="card-body row justify-content-between align-items-center">
                                    <div class="col-sm-4 col-md-4 col-12 d-flex">
                                        <img src="" alt="ini produk" class="img-fluid">
                                        <!-- Menambahkan nama produk di sebelah kanan gambar -->
                                        <div class="product-name ms-3">
                                            <h4>Kaos Bilek</h4>
                                            <div style="display: flex;">
                                                <div class="me20">
                                                    <h6 class="title-detail" >Warna:</h6>
                                                    <p>warna ini</p>
                                                </div>
                                                <div class="me20">
                                                    <h6 class="title-detail">Ukuran:</h6>
                                                    <p>ukuran segini</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-12">                     
                                        <h6 class="title-detail mb-1">Jumlah:</h6>
                                        <h5>2</h5>
                                        <h6 class="title-detail">Total:</h6> 
                                        <h5>Rp. 1.000.000</h5>
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
                                <h6>Laos-Merch Express - Area UNEJ</h6>
                                <h6 class="mb-0">Ahmad Fauzan</h6>
                                <h6 class="mb-0">088888888888</h6>
                                <h6 class="mb-0">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quo inventore velit distinctio sit cum eveniet exercitationem consequatur sapiente enim corporis perferendis obcaecati, dolores soluta voluptate natus. Sed sequi enim omnis!</h6>
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
                                    <h6>Metode Pembayaran:</h6>
                                    <h6>Total Harga:</h6>
                                    <h6>Total Ongkos Kirim:</h6>
                                    <h5 class="mt-1" style="font-weight: bold;">Total Belanja:</h5>
                                </div>
                            </div>
                            <div class="d-flex flex-column text-start ms-3">
                                <h6>COD</h6>
                                <h6>Rp.1.000.000</h6>
                                <h6>Rp.0</h6>
                                <h5 class="mt-1" style="font-weight: bold;">Rp.1.000.000</h5>
                            </div>
                        </div>  
                    </div>
                </div>

            </div>
                <div class="col-lg-4 col-md-10 col-sm-12">
                    <div class="d-flex flex-column justify-content-center mt-1">
                        <a class="btn laos-outline-button mb-3" onclick="history.back()">Kembali</a>
                        <a type="button" class="btn btn-success mb-3" href="https://wa.me/6285606689642" target="_blank">Hubungi Admin</a>
                        <button class="btn btn-secondary">Batalkan Pesanan</button>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</section>

<?php
requireView("partials/footer.php");
?>