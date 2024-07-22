<?php
require_once "views/partials/head.php";
require_once "views/partials/navbar.php";
?>

<section class="content mt-5">

    <div class="row">
        <!-- gambar produk -->
        <div class="col-sm-4 col-md-4 col-12 p-4">
            <img src="" alt="ini kaos" class="img-fluid">
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center mt-5">
                  <li class="page-item disabled">
                    <a class="page-link" style="text-decoration: none; color: inherit;"><</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="#" style="text-decoration: none; color: inherit;"><img src="" alt="tampak depan"></a></li>
                  <li class="page-item"><a class="page-link" href="#" style="text-decoration: none; color: inherit;"><img src="" alt="tampak samping"></a></li>
                  <li class="page-item"><a class="page-link" href="#" style="text-decoration: none; color: inherit;"><img src="" alt="tampak belakang"></a></li>
                  <li class="page-item">
                    <a class="page-link" href="#" style="text-decoration: none; color: inherit;">></a>
                  </li>
                </ul>
              </nav>
        </div>
        <!-- info produk -->
        <div class="col-sm-4 col-md-4 col-12 p-4">
            <h2>Kaos Bilek</h2>
            <p class="title-detail">Stok Tersedia: 1</p>
            <h3>Rp. 1.000.000</h3>
            <p class="title-detail">Deskripsi Produk</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus explicabo magni accusantium illo distinctio tempora perferendis veritatis autem inventore eos harum odit aliquam totam temporibus, perspiciatis libero ut debitis pariatur.</p>
            <p class="title-detail">Pilih Warna dan Ukuran</p>
            <div>
                <label for="">Warna:</label>
                <div>
                    <a href="" class="btn laos-outline-button active">Hitam</a>
                </div>
                <label for="">Ukuran:</label>
                <div>
                    <a href="" class="btn laos-outline-button active">L</a>
                    <a href="" class="btn laos-outline-button">XL</a>
                    <a href="" class="btn laos-outline-button">XXL</a>
                </div>
            </div>
        </div>
        <!-- checkout -->
        <div class="col-sm-4 col-md-4 col-12 mt-5 d-flex justify-content-center">
            <div class="card d-flex flex-column justify-content-between" style="width:18rem;">
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
        <!-- rekomendasi produk lainnya -->
        <h3 class="d-flex justify-content-center mt-5">Produk Lainnya</h3>
        <div class="container">
            
            <div class="row justify-content-center">
                <div class="col-6 col-md-3 mt-3">
                    <div class="card extra" style="text-align: left;">
                        <a href="#" style="text-decoration: none; color: inherit;">
                        <img src="#" class="card-img-top" alt="ini foto">
                        <div class="card-body">
                            <h5 class="card-title">Produk 1</h5>
                            <p class="card-text">Rating: 5/5</p>
                        </div>
                        </a>
                    </div>
                </div>  
    
                <div class="col-6 col-md-3 mt-3">
                    <div class="card extra" style="text-align: left;">
                        <a href="#" style="text-decoration: none; color: inherit;">
                        <img src="#" class="card-img-top" alt="ini foto">
                        <div class="card-body">
                            <h5 class="card-title">Produk 2</h5>
                            <p class="card-text">Rating: 5/5</p>
                        </div>
                        </a>
                    </div>
                </div>  
                
                <div class="col-6 col-md-3 mt-3">
                    <div class="card extra" style="text-align: left;">
                        <a href="#" style="text-decoration: none; color: inherit;">
                        <img src="#" class="card-img-top" alt="ini foto">
                        <div class="card-body">
                            <h5 class="card-title">Produk 3</h5>
                            <p class="card-text">Rating: 4/5</p>
                        </div>
                        </a>
                    </div>
                </div>  
    
                <div class="col-6 col-md-3 mt-3">
                    <div class="card extra" style="text-align: left;">
                        <a href="#" style="text-decoration: none; color: inherit;">
                        <img src="#" class="card-img-top" alt="ini foto">
                        <div class="card-body">
                            <h5 class="card-title">Produk 4</h5>
                            <p class="card-text">Rating: 5/5</p>
                        </div>
                        </a>
                    </div>
                </div>  
    
            </div>
        </div>
    
    
        
</section>


<?php
require_once "views/partials/footer.php";
?>