<?php
require_once VIEWS . "/partials/head.php";
require_once VIEWS . "/partials/navbar.php";
?>
  
  
  <section class="content">
      <div class="container mt-3">
        <h4>Pesananku</h4>   
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
                <hr>
                    <div class="col-sm-4 col-md-4 col-12">
                        <h5 class="title-detail ">Status:</h5>
                        <p class="alert alert-success mt-1" role="alert" style="max-width:fit-content;">
                           Selesai
                        </p>
                    </div>      
                    <div class="col-sm-4 col-md-4 col-12">
                        <a href="<?= BASEURL?>order/detail" class="btn btn-secondary me-2">Detail Pesanan</a>
                        <a href="<?= BASEURL?>" class="btn btn-success">Pesanan Selesai</a>
                    </div>
                </div>
            </div>
        </div>
      </div>
  </section>



<?php
require_once VIEWS . "/partials/footer.php";