<!-- navbar -->

<style>
    .dropdown-toggle::after {
        display: none;
    }

    * {
        font-family: "Nunito", sans-serif;
        font-optical-sizing: auto;
        font-weight: 300;
        font-style: normal;
    }

</style>

<nav class="navbar navbar-light navbar-expand-lg shadow-lg fixed-top" style="background-color: #fff;">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="<?= BASEURL ?>public/assets/LogoLaos.png" alt="ini foto" class="me-2" width="30" height="30">
            <h6 class="title mb-0">LAOS Merch</h6>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">LAOS Merch</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 w-100 d-flex flex-column align-items-center">
                    <div class="search-bar-container mt-1">
                        <form class="d-flex search-bar" role="search">
                            <input class="form-control me-2" type="search" placeholder="Mau cari apa?" aria-label="Search" autofocus>
                            <button class="btn btn-success" type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </ul>
                <div class="d-inline-flex gap-1">
                    <div class="dropdown">
                        <button class="btn btn-dark me-2 dropdown-toggle" type="button" id="cartDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="cartDropdown" >
                            <li class="dropdown-item"><a href="<?= BASEURL?>carts" class="decoration-none">Keranjang Saya</a></li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-warning active active dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown" >
                            <li><a class="dropdown-item" href="#" class="decoration-none">Profil Saya</a></li>
                            <li><a class="dropdown-item" href="#" class="decoration-none">Pesanan Saya</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
