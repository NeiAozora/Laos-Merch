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
                        Registrasi Akun
                    </div>
                    <div class="card-body">
                    <form action="<?= BASEURL ?>register" method="post">
                        <div class="mb-3 d-flex flex-column flex-md-row">
                            <div class="me-md-3 mb-3 mb-md-0 w-100">
                                <label for="firstName" class="form-label">Nama Depan:</label>
                                <input type="text" class="form-control" id="firstName" value="<?= $firstName ?>" placeholder="Masukkan Nama..." name="firstName" required>
                            </div>
                            <div class="w-100">
                                <label for="lastName" class="form-label">Nama Belakang:</label>
                                <input type="text" class="form-control" id="lastName" value="<?= $lastName ?>" placeholder="Masukkan Nama..." name="lastName" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email (untuk login):</label>
                            <input type="email" class="form-control" id="email" value="<?= $email ?>" placeholder="Masukkan email..." name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username :</label>
                            <input type="text" class="form-control" id="username" value="<?= $username ?>" placeholder="Masukkan Username..." name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" value="<?= $password ?>" placeholder="Masukkan Password..." name="password" required>
                                <div class="input-group-append">
                                    <span class="input-group-text eye-icon" onclick="togglePasswordVisibility('password', 'togglePasswordIcon')">
                                        <i class="fas fa-eye p-1" id="togglePasswordIcon"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Konfirmasi Password:</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmPassword" value="<?= $confirmPassword ?>" placeholder="Masukkan Password..." name="confirmPassword" required>
                                <div class="input-group-append">
                                    <span class="input-group-text eye-icon" onclick="togglePasswordVisibility('confirmPassword', 'toggleConfirmPasswordIcon')">
                                        <i class="fas fa-eye p-1" id="toggleConfirmPasswordIcon"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">No. Whatsapp:</label>
                            <input type="text" class="form-control" id="phone" value="<?= $noWA ?>" placeholder="Masukkan Nomor HP..." name="noWA" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success mt-2 mb-2" style="width: 100%;">Registrasi</button>
                        </div>
                    </form>
                        <div class="text-center mt-3">
                            <a href="<?= BASEURL ?>login" class="link-secondary text-decoration-none">sudah punya akun</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if(strlen($modalMessage) > 0): ?>
<!-- Modal Warning -->
<div class="modal fade" id="modalWarning" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><?= $modalTitle ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?= $modalMessage ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>


<script>
    var baseUrl = "<?= BASEURL ?>"
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirmPassword');

        function checkPasswords() {
            if (password.value === confirmPassword.value && password.value !== '') {
                password.classList.add('is-valid');
                confirmPassword.classList.add('is-valid');
                password.classList.remove('is-invalid');
                confirmPassword.classList.remove('is-invalid');
            } else {
                password.classList.remove('is-valid');
                confirmPassword.classList.remove('is-valid');
                if (password.value !== '' && confirmPassword.value !== '') {
                    password.classList.add('is-invalid');
                    confirmPassword.classList.add('is-invalid');
                } else {
                    password.classList.remove('is-invalid');
                    confirmPassword.classList.remove('is-invalid');
                }
            }
        }

        function markFilled() {
            if (this.value != ''){
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');

            }
        }

        password.addEventListener('input', checkPasswords);
        confirmPassword.addEventListener('input', checkPasswords);

        // Add event listeners to mark fields as filled
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('input', markFilled);
        });
    });

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

    // Other than Passwords input
    document.addEventListener('DOMContentLoaded', function() {
        // Function to add 'is-valid' class on input event
        function markFilled() {
            this.classList.add('is-valid');
        }

        // Add event listeners to inputs except password and confirm password
        const inputs = document.querySelectorAll('.form-control:not(#password):not(#confirmPassword)');
        inputs.forEach(input => {
            input.addEventListener('input', markFilled);
        });

        // Password visibility toggle function
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

        // Attach togglePasswordVisibility function to the global scope
        window.togglePasswordVisibility = togglePasswordVisibility;
    });

    <?php if(strlen($modalMessage) > 0): ?>
    // Modal Functionality
    function showModal() {
        var modal = document.getElementById('modalWarning');
        modal.classList.add('show'); // Bootstrap class to display the modal
        modal.style.display = 'block'; // Make sure the modal is displayed
        modal.removeAttribute('aria-hidden'); // Set aria-hidden attribute to false
        modal.setAttribute('aria-modal', 'true'); // Set aria-modal attribute to true
        modal.setAttribute('role', 'dialog'); // Set role attribute to dialog

        // Add a backdrop manually
        var backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        document.body.appendChild(backdrop);

        // Handle modal closing
        modal.querySelector('[data-dismiss="modal"]').onclick = function() {
            hideModal(modal, backdrop);
        };
    }

    // Function to hide the modal
    function hideModal(modal, backdrop) {
        modal.classList.remove('show');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        modal.removeAttribute('aria-modal');
        modal.removeAttribute('role');
        if (backdrop) {
            document.body.removeChild(backdrop);
        }
    }

    showModal();
    <?php endif; ?>


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









