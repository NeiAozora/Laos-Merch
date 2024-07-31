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
                        Recovery Account
                    </div>
                    <div class="card-body">
                    <form action="<?= BASEURL ?>recovery" method="post">
                        <p>Password Baru Akan dikirim ke email yang terdaftar</p>
                        <div class="mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Masukan email anda..." required>
                        </div>
                        <?php if($showAlert): ?>
                        <?php if($failed): ?>
                        <div class="alert alert-danger" role="alert">
                        <?= $message ?>
                        </div>
                        <?php endif; ?>
                        <?php if(!$failed): ?>
                        <div class="alert alert-success" role="alert">
                        <?= $message ?>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>

                        <div class="d-flex flex-column flex-md-row justify-content-center">
                            <a href="<?= BASEURL ?>login" class="btn btn-laos-outline mt-2 mb-2 me-md-2" style="width: 100%; border: limegreen solid 1px;">Kembali</a>
                            <button type="submit" class="btn btn-success mt-2 mb-2 ms-md-2" style="width: 100%;">Kirim</button>
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


    <?php if($showModalWarning): ?>
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









