<?php
require_once VIEWS . '/partials/head.php';
require_once VIEWS . '/partials/navbar.php';
?>


<section class="content mt-3">
    <div class="container">
    <h4>Keranjang</h4>
        <div class="row justify-content-center">
            <div class="col-12 text-start mb-2">
                <input type="checkbox" class="cursor-pointer" id="selectAll">
                <label for="selectAll" class="title-detail">Pilih Semua</label>
            </div>
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="card mb-4">
                    <div class="card-body row justify-content-between align-items-center">
                        <div class="col-lg-6 col-md-6 col-12 d-flex">
                        <input type="checkbox" class="checkbox-item cursor-pointer me-3">
                            <img src="" alt="ini kaos" class="img-fluid">
                            <div class="ms-3">
                                <h4>Kaos Bilek</h4>
                                <div style="display: flex;">
                                    <div class="me-3">
                                        <h6 class="title-detail">Warna:</h6>
                                        <p>warna begini</p>
                                    </div>
                                    <div class="me-3">
                                        <h6 class="title-detail">Ukuran:</h6>
                                        <p>ukuran segini</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-12 text-end">
                            <div class="d-flex justify-content-end align-items-center">
                                <a href="#" class="me-3 decoration-none"><i class="fa-solid fa-pen-to-square" style="color: gold;"></i></a>
                                <a href="#" class="decoration-none"><i class="fa-solid fa-trash" style="color: red;"></i></a>
                            </div>
                            <div class="d-flex justify-content-end my-2">
                                <div class="col-5">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text cursor-pointer" id="decrement">-</span>
                                        </div>
                                        <input type="text" class="form-control text-center" value="1" id="quantity" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text cursor-pointer" id="increment">+</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h5>Rp. sekian</h5>
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="col-lg-4 col-md-10 col-sm-12">
                <div class="card d-flex flex-column justify-content-between">
                    <h5 class="mt-2 text-center">Total Belanja</h5>
                    <div class="ms-2">
                        <h4>Total:</h4>
                        <h5>Rp. sekian sekian</h5>
                    </div>
                    <div class="mt-auto text-center mb-3">
                        <button class="btn btn-success mt-2" style="width: 12rem;">Beli</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- select all checkbox cart -->
<script>
    document.getElementById('selectAll').addEventListener('change', function(){
        const checkboxes = document.querySelectorAll('.checkbox-item');
        checkboxes.forEach(checkbox=>{checkbox.checked = this.checked});
    });
</script>
<!-- button plus minus -->
<script>
    document.getElementById('decrement').addEventListener('click', function() {
      let quantity = parseInt(document.getElementById('quantity').value);
      if (quantity > 1) {document.getElementById('quantity').value = quantity - 1;}
    });

    document.getElementById('increment').addEventListener('click', function() {
      let quantity = parseInt(document.getElementById('quantity').value);
      document.getElementById('quantity').value = quantity + 1;
    });
</script>

<?php
require_once VIEWS . '/partials/footer.php';
?>