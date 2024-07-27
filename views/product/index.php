<?php
requireView("partials/head.php");
requireView("partials/navbar.php");
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
            <h2><?php $product["product_name"] ?></h2>
            <p class="title-detail">Stok Tersedia: <span id="stock-value"></span></p>
            <h3><span id="price"></span></h3>
            <?php if (!empty($discount)): ?>
            <h6><?php $discount["discount_value"] ?></h6>


            <div id="countdown"></div>

            <script>
                // Set the end date for the countdown (format: YYYY-MM-DDTHH:MM:SS)
                const endDate = new Date('<?= $discount["end_date"] ?>');

                function updateCountdown() {
                    const now = new Date();
                    const timeDifference = endDate - now;

                    // Calculate days, hours, minutes, and seconds
                    const days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

                    // Update the countdown element
                    document.getElementById("countdown").innerHTML = 
                        days + "H " + 
                        hours + "J " + 
                        minutes + "M " + 
                        seconds + "D ";

                    // If the countdown is finished, display a message
                    if (timeDifference < 0) {
                        clearInterval(countdownInterval);
                        document.getElementById("countdown").innerHTML = "Countdown ended";
                    }
                }

                // Update the countdown every second
                const countdownInterval = setInterval(updateCountdown, 1000);

                // Initial call to display the countdown immediately
                updateCountdown();
            </script>
            <?php endif ?>

            
            
            <p class="title-detail">Deskripsi Produk</p>
            <p><?php $product["description"] ?></p>
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
requireView("partials/footer.php");
?>