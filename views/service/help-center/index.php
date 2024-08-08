<?php
requireView("partials/head.php");
requireView("partials/navbar.php");
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
                <div class="btn laos-outline-button decoration-none"><i class="fa-solid fa-truck"></i></div>
                <h6 class="mt-2">Pengiriman</h6>
            </div>
            <div class="text-center mx-auto">
                <div class="btn laos-outline-button decoration-none"><i class="fa-solid fa-money-bill"></i></div>
                <h6 class="mt-2">Pembayaran</h6>
            </div>
            <div class="text-center mx-auto">
                <div class="btn laos-outline-button decoration-none"><i class="fa-solid fa-circle-exclamation"></i></div>
                <h6 class="mt-2">Barang Rusak</h6>
            </div> 
            <div class="text-center mx-auto" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <div class="btn laos-outline-button decoration-none"><i class="fa-solid fa-pen"></i></div>
                <h6 class="mt-2">Buat Pertanyaan</h6>
            </div>
        </div>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Form Pertanyaan</h1>
                        <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="formPertanyaan">
                            <div class="form-group">
                                <label for="nama" class="text-start d-block">Nama:</label>
                                <input type="text" class="form-control" name="nama" value="" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="pertanyaan" class="text-start d-block">Pertanyaan:</label>
                                <input type="text" class="form-control" name="pertanyaan" value="">
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="saveChangesBtn" class="btn btn-success">Kirim</button>
                    </div>
                    </div>
                </div>
                </div>
                

        <h4>Baru Baru ini ditanyakan</h4>
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


    <!-- <script>
        function closeModal() {
            var modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
            modal.hide();
        }
        document.getElementById('saveChangesBtn').addEventListener('click', function () {
            var form = document.getElementById('formPertanyaan');
            var formData = new FormData(form);

            // Process form data (e.g., send to server or perform validation)
            console.log('Nama:', formData.get('nama'));
            console.log('Pertanyaan:', formData.get('pertanyaan'));

            // Add your form submission logic here
            // For demonstration, prevent modal from closing
            // If the form is valid, you can close the modal using the line below
            // var modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
            // modal.hide();
            if (nama && pertanyaan) {
                console.log('Nama:', nama);
                console.log('Pertanyaan:', pertanyaan);

                // Jika validasi berhasil, tutup modal
                closeModal();
            } else {
                alert('Nama dan Pertanyaan harus diisi!');
            }
        });
    </script> -->
<?php
requireView("partials/footer.php");