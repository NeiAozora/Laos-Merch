<?php
require_once VIEWS . "/partials/head.php";
require_once VIEWS . "/partials/navbar.php";
?>
  
  
  <section class="content">
      <div class="container mt-3">
        <h5>Pesananku</h5>   
        <div class="mb-3">
            <a href="" class="btn laos-outline-button active me-2">Semua</a>
            <a href="" class="btn laos-outline-button me-2">Diproses</a>
            <a href="" class="btn laos-outline-button me-2">Dikirim</a>
            <a href="" class="btn laos-outline-button me-2">Selesai</a>
        </div>
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-body row justify-content-between align-items-center">
                    <div class="col-sm-4 col-md-4 col-12 d-flex align-items-center">
                        <img src="" alt="ini produk" class="img-fluid">
                        <!-- Menambahkan nama produk di sebelah kanan gambar -->
                        <div class="product-name ms-3">
                            <h4>Kaos Bilek</h4>
                            <div style="display: flex;">
                                <div class="" style="margin-right: 20px;">
                                    <h6 class="title-detail" >Warna:</h6>
                                    <p>Deskripsi Warna</p>
                                </div>
                                <div style="margin-right: 20px;">
                                    <h6 class="title-detail">Ukuran:</h6>
                                    <p>Deskripsi Ukuran</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4 col-12">
                        <h6 class="title-detail">Jumlah:</h6>
                        <h5></h5>
                        <h6 class="title-detail">Total:</h6> 
                        <h5></h5>
                    </div>
                <hr>
                    <div class="col-sm-4 col-md-4 col-12">
                        <h5 class="title-detail">Status:</h5>
                        <p class="alert alert-secondary mt-1" role="alert" style="max-width:fit-content;">
                           Selesai
                        </p>
                    </div>      
                    <div class="col-sm-4 col-md-4 col-12">
                        <a type="button" class="btn btn-warning me-2" href="https://wa.me/6285606689642" target="_blank">Hubungi Admin</a>
                        <button type="button" class="btn laos-button">Pesanan Selesai</button>
                    </div>
                </div>
            </div>
        </div>
      </div>
  </section>



<?php
require_once VIEWS . "/partials/footer.php";