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
                                        <h6 style="font-family: nunito; font-weight:lighter;">Ahmad Fauzan</h6>
                                        <h6 style="font-family: nunito; font-weight:lighter;">ini</h6>
                                        <h6 style="font-family: nunito; font-weight:lighter;">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ipsam numquam ducimus assumenda cupiditate tenetur quaerat porro exercitationem amet, nobis omnis voluptas excepturi doloribus, hic corporis! Deserunt reprehenderit aspernatur aliquam. Rem!</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12  text-end">
                            <div class="position-top-right">
                                <a href="#" class=" me-3 decoration-none">
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
                        <select class="form-control" name="" id="pembayaran">
                            <option value="disabled" disabled selected>Pilih Pengiriman</option>
                            <option value="ovo">J&T Express</option>
                            <option value="dana">Laos-Merch Express (Area UNEJ)</option>
                        </select>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="title-detail mb-2">Metode Pembayaran:</h5>
                        <select class="form-control" name="" id="pembayaran">
                            <option value="disabled" disabled selected>Pilih Pembayaran</option>
                            <option value="dana">DANA</option>
                            <option value="ovo">OVO</option>
                            <option value="cod">COD (Area UNEJ)</option>
                        </select>
                    </div>
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

            </div>
            
            <div class="col-lg-4 col-md-10 col-sm-12">
                <div class="card d-flex flex-column justify-content-between">
                    <h4 class="m-2 title-detail text-start">Rincian:</h4>
                    <div class="ms-2">
                        <h5 class="me20">Nama Pembeli:</h5>
                        <H6>Ahmad Fauzan</H6>
                        <h5 class="me20 mt-3">Metode Pembayaran:</h5>
                        <H6>COD (Area UNEJ)</H6>
                        <h5 class="me20 mt-3">Jenis Pengiriman:</h5>
                        <h6>Laos-Merch Express (Area UNEJ)</h6>
                        <h5 class="mt-5 me20">Total:</h5>
                        <h6>Rp. 1.006.000</h6>
                    </div>
                    <div class="mt-2 text-center mb-3">
                        <button class="btn btn-success" style="width: 12rem;">Lanjut Bayar</button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<?php
requireView("partials/footer.php");
?>