<?php
require_once VIEWS . "partials/head.php";
require_once VIEWS . "partials/navbar.php";
?> 


<section class="content">
<div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-center">
                <h4 class="me-2 ms-1"><i class="fa-solid fa-user"></i></h4>
                <h4 class="ml-3">Profile</h4>
            </div>
        </div>
        
        <!-- profile nav -->
        <ul class="nav nav-tabs" id="profileTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active decoration-none" id="biodata-tab" data-toggle="tab" href="#" role="tab">Biodata Diri</a>
            </li>
            <li class="nav-item">
                <a class="nav-link decoration-none" id="address-tab" data-toggle="tab" href="#" role="tab">Daftar Alamat</a>
            </li>
        </ul>
        
        <div class="tab-content mt-3" id="profileTabContent">
            <div class="tab-pane fade show active" id="biodata" role="tabpanel" aria-labelledby="biodata-tab">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="" alt="ini pp">
                        <form>
                            <div class="form-group">
                                <label for="profileImage">Pilih Foto</label>
                                <input type="file" class="form-control-file" id="profileImage">
                                <!-- <small class="form-text text-muted">Besar file: maksimum 10.000.000 bytes (10 Megabyte). Ekstensi file yang diperbolehkan: .JPG, .JPEG, .PNG</small> -->
                            </div>
                            <button class="btn btn-success mt-3 mb-3">Ganti Gambar</button>
                        </form>
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                            <h4>Biodata Diri</h4>
                            <div class="mb-1">
                                <a class="decoration-none">Ubah Biodata
                                    <i class="fa-solid fa-pen-to-square ms-2" style="color: gold;"></i>
                                </a>
                            </div>
                        </div>
                        <form>
                            <div class="form-group mt-1">
                                <label for="">Nama</label>
                                <input type="text" class="form-control" id="nama" placeholder="Masukkan nama..." value="" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal" value="" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="gender">Jenis Kelamin</label>
                                <select class="form-control" id="gender">
                                    <option selected disabled>Pilih Jenis Kelamin</option>
                                    <option>Pria</option>
                                    <option>Wanita</option>
                                </select>
                            </div>
                        </form>
                        <h4 class="mt-4 mb-3">Kontak</h4>
                        <form>
                            <div class="form-group mt-1">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" value="" required>
                                <!-- <small class="form-text text-success">Terverifikasi</small> -->
                            </div>
                            <div class="form-group mt-2">
                                <label for="phone">Nomor HP</label>
                                <input type="text" class="form-control" id="phone" value="" required>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>


     
<?php
require_once VIEWS . "partials/footer.php";
?>