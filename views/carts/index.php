<?php
require_once VIEWS . '/partials/head.php';
require_once VIEWS . '/partials/navbar.php';
?>


<section class="content">
    <div class="container mt-3">
        <h5>Keranjang</h5>
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-body row justify-content-between align-items-center">
                    <div class="col-sm-4 col-md-4 col-12 d-flex">
                        <img src="" alt="ini kaos" class="img-fluid">
                        <div class="ms-3">
                            <h4>Kaos Bilek</h4>
                            <div style="display: flex;">
                                <div style="margin-right: 20px;">
                                    <h6 class="title-detail">Warna:</h6>
                                    <p>warna begini</p>
                                </div>
                                <div style="margin-right: 20px;">
                                    <h6 class="title-detail">Ukuran</h6>
                                    <p>ukuran segini</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4 col-12">
                        <div style="display: flex;">
                            <h6 class="me-3">ini edit</h6>
                            <h6>ini hapus</h6>
                        </div>
                        <div>
                            
                            <div class="col-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="decrement">-</span>
                                    </div>
                                    <input type="text" class="form-control" style="text-align:center;" value="1" id="quantity" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="increment">+</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <h5 class="mt-2">Rp. sekian</h5>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-md-4 col-12 mt-5 d-flex justify-content-center">
                <div class="card d-flex flex-column justify-content-between" style="width:18rem; height:100%">
                <h5 class="mt-2 d-flex justify-content-center">Atur Pilihanmu</h5>
                    <div class="ms-2">
                        <p>Warna :</p>
                        <p>Ukuran :</p>
                        <p>Jumlah :</p>
                        <p>Subtotal :</p>
                    </div>
                    <div class="mt-auto text-center">
                        <button class="btn laos-button mb-2 mt-3" style="width:12rem;">Masukkan Keranjang</button>
                        <button class="btn laos-outline-button mb-3" style="width:12rem;">Beli Langsung</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php
require_once VIEWS . '/partials/footer.php';
?>