<?php
requireView("partials/head.php");
?>

<section class="content" style="justify-content: center; align-items: center; height: 100%;">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-sm-16 col-md-6 d-flex justify-content-center align-items-center mb-4 mb-md-0">
                <div class="text-center mt-2 animate-1sec slideIn">
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
                        <form id="loginForm">
                            <div class="mb-2">
                                <label for="email" class="form-label">Email:</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan Email..." required autocomplete="email" autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" placeholder="Masukkan Password..." required>
                                    <div class="input-group-append">
                                        <span class="input-group-text eye-icon" onclick="togglePasswordVisibility('password', 'togglePasswordIcon')">
                                            <i class="fas fa-eye p-1" id="togglePasswordIcon"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div id="bar-loader"></div>
                                <div id="error-message"></div>
                            </div>
                            

                            <!-- Placeholder for success message -->
                            <?php if(isset($_GET["verificationSuccess"])): ?>
                            <div class="alert alert-success" role="alert" id="verification">
                                Verification has been successfully completed.
                            </div>
                            <?php endif; ?>

                            <!-- Placeholder for error message -->
                            <div id="error-message" class="alert alert-danger" style="display: none;"></div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success mt-2 mb-2" style="width: 100%;">Login</button>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-12">
                            <hr class="mt-2 mb-4 border-secondary-subtle">
                            <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end">
                                <a href="<?= BASEURL ?>register" class="link-secondary text-decoration-none">Create new account</a>
                                <a href="<?= BASEURL ?>recovery" class="link-secondary text-decoration-none">Forgot password</a>
                            </div>
                            </div>
                        </div>
                            <div class="col-12">
                            <p class="mb-4 mt-3">Or sign in with<span class="ms-2 fs-6 text-success"><button href="#!" class="link-success border-0" id="google-signin" style="color: inherit; background-color: transparent;">Google</button></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    var baseUrl = "<?= BASEURL ?>"
</script>
<script>
    function togglePasswordVisibility(inputId, iconId) {
        var inputElement = document.getElementById(inputId);
        var iconElement = document.getElementById(iconId);

        if (inputElement.type === "password") {
            inputElement.type = "text";
            iconElement.classList.remove('fa-eye');
            iconElement.classList.add('fa-eye-slash');
        } else {
            inputElement.type = "password";
            iconElement.classList.remove('fa-eye-slash');
            iconElement.classList.add('fa-eye');
        }
    }
</script>
<script src="<?= BASEURL ?>public/js/components/loadingAnimation.js"></script>
<script type="module" src="<?= BASEURL ?>public/js/fbase.js"></script>
<script type="module" src="<?= BASEURL ?>public/js/fbaseSignIn.js"></script>








