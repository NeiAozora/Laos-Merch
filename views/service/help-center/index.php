<?php
require_once VIEWS . "partials/head.php";
require_once VIEWS . "partials/navbar.php";
?> 

<section class="content">
    <div class="container">
        <h4 class="mb-3">Pusat Bantuan</h4>
        <div class="search-bar-container mb-5">
            <form class="d-flex search-bar" style="max-width: 900px;" role="search" id="search-form" method="get" action="">
                <input class="form-control me-2" name="search" style="border: 2px solid limegreen;" type="search" placeholder="Ada yang bisa kami bantu?" aria-label="Search" autofocus>
                <button class="btn btn-success btn-search" type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        

        <h4 class="mb-3">Pilih sesuai kendala kamu</h4>
        <div class="d-flex justify-content-center mb-5">
            <div class="text-center mx-auto">
                <a href="" class="btn laos-outline-button decoration-none"><i class="fa-solid fa-truck"></i></a>
                <h6 class="mt-2">Pengiriman</h6>
            </div>
            <div class="text-center mx-auto">
                <a href="" class="btn laos-outline-button decoration-none"><i class="fa-solid fa-money-bill"></i></i></a>
                <h6 class="mt-2">Pembayaran</h6>
            </div>
            <div class="text-center mx-auto">
                <a href="" class="btn laos-outline-button decoration-none"><i class="fa-solid fa-circle-exclamation"></i></a>
                <h6 class="mt-2">Barang Rusak</h6>
            </div> 
            <div class="text-center mx-auto">
                <a href="" class="btn laos-outline-button decoration-none"><i class="fa-solid fa-pen"></i></i></a>
                <h6 class="mt-2">Buat Pertanyaan</h6>
            </div>

        </div>

        <h4>Paling sering ditanyakan</h4>
        <div class="card">
            <div class="card-body p-0">
                <a href="" class="decoration-none dropdown-item"><p class="mt-0 mb-0 p-2">Bagaimana cara melacak paket pesanan?</p></a>
                <hr class="m-0">
                <a href="" class="decoration-none dropdown-item"><p class="mt-0 mb-0 p-2">Berapa lama waktu pengembalian dana?</p></a>
                <hr class="m-0">
                <a href="" class="decoration-none dropdown-item"><p class="mt-0 mb-0 p-2">Cara mendapat diskon 100% work no root</p></a>
                <hr class="m-0">
            </div>
        </div>

    </div>
</section>

<?php
require_once VIEWS . "partials/footer.php";