<?php
require_once "views/partials/head.php";
?>

<section class="content" style="justify-content: center; align-items: center; height: 100%;">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-sm-16 col-md-6 d-flex justify-content-center align-items-center mb-4 mb-md-0">
                <div class="text-center mt-2">
                    <img src="<?= BASEURL?>public/assets/LogoLaos.png" alt="ini logo" width="35%" height="35%">
                    <h3 class="notfound">LAOS Merch</h3>
                    <p style="font-size: 20px;">Official Merchandise of LAOS</p>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 d-flex justify-content-center align-items-center">
                <div class="card" style="width: 90%; max-width: 32rem;">
                    <div class="card-header title-detail text-center">
                        Silahkan Login
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="mb-2">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" class="form-control" name="username" placeholder="Masukkan Username..." required autocomplete="email" autofocus>
                            </div>
                            <div class="mb-2">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" class="form-control" name="password" placeholder="Masukkan Password..." required autocomplete="current-password">
                            </div>
                            <div class="text-center">
                                <a type="submit" href="" class="btn btn-success mt-2 mb-2" style="width: 100%;">Login</a>
                                <a type="submit" href="" class="btn btn-outline-success mt-2 mb-2" style="width: 100%;">Register</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
