<?php
requireView("partials/head.php");
?>

<section class="content" style="justify-content: center; align-items: center; height: 100%;">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-sm-16 col-md-6 d-flex justify-content-center align-items-center mb-4 mb-md-0">
                <div class="text-center mt-2 animate-1sec slideIn">
                    <img src="<?= BASEURL?>public/assets/LogoLaosRegister.svg" onerror="this.src='<?= BASEURL?>public/assets/LogoLaos.png'"  alt="ini logo" width="35%" height="35%">
                    <h3 class="notfound">LAOS Merch</h3>
                    <p style="font-size: 20px;">Official Merchandise of LAOS</p>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 d-flex justify-content-center align-items-center">
            <div class="card" style="width: 90%; max-width: 32rem;">
                    <div class="card-header title-detail text-center">
                        Verifikasi
                    </div>
                    <div class="card-body">
                    <form action="<?= BASEURL ?>auth-verification" method="post">
                        <div class="mb-3">
                            <p>Kode Verifikasi telah dikirim ke email <b><?= $email ?></b></b><br>Kode Verifikasi akan kadaluarsa dalam 3 jam setelah dikirim!, silahkan cek email anda</b></p>
                        </div>
                        <input type="hidden" name="token" value="<?= $token ?>">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="code" name="code" placeholder="Masukkan 6 digit kode..." required>
                        </div>
                        <?php if($failed): ?>
                        <div class="alert alert-danger" role="alert">
                        Kode Salah, Silahkan Coba lagi
                        </div>
                        <?php endif; ?>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success mt-2 mb-2" style="width: 100%;">Verifikasi</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    var baseUrl = "<?= BASEURL ?>"
</script>

<style>
    .is-valid {
        border-color: #28a745 !important;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }
    
    .input-group-append .input-group-text {
        cursor: pointer;
    }
</style>









