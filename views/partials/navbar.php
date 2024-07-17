<!-- navbar -->

<nav class="navbar navbar-light navbar-expand-lg shadow-lg" style="background-color: #fff;">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="<?= BASEURL ?>/public/assets/LogoLaos.png" alt="ini foto" class="me-2" width="30" height="30">
            <h6 class="title mb-0">LAOS Merch</h6>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 w-100 d-flex flex-column align-items-center">
                <div class="search-bar-container mt-1">
                    <form class="d-flex search-bar" role="search">
                        <input class="form-control me-2" type="search" placeholder="Cari Merchandise..." aria-label="Search" autofocus>
                        <button class="btn laos-button" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </ul>
            <a class="d-inline-flex gap-1">
                <button class="btn cart-button me-2" type="button"><i class="fa-solid fa-cart-shopping"></i></button>
                <button class="btn btn-warning active" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="fa-solid fa-user"></i></button>
            </a>
        </div>
    </div>
</nav>

<!-- collapse -->
<div class="collapse" id="collapseExample">
    <div class="card card-body">
        <a href="<?= BASEURL?>" class="isi-collapse d-block mb-2" style="text-decoration: none; color: inherit;">Profil </a>
        <a href="<?= BASEURL?>" class="isi-collapse d-block mb-2" style="text-decoration: none; color: inherit;">Pesananku</a>
        <a href="<?= BASEURL?>" class="isi-collapse d-block mb-2" style="text-decoration: none; color: inherit;">Contact Person</a>
    </div>
</div>
