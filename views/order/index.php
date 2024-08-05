<?php
requireView("partials/head.php");
requireView("partials/navbar.php");
?>
  
  
  <section class="content">
      <div class="container mt-3">
        <h4>Pesananku</h4>   
        <div class="mb-3">
            <a href="?status=Semua" class="btn laos-outline-button me-2 my-2 <?php echo ($status === 'Semua') ? 'active' : ''; ?>">Semua</a>
            <a href="?status=Diproses" class="btn laos-outline-button me-2 my-2 <?php echo ($status === 'Diproses') ? 'active' : ''; ?>">Diproses</a>
            <a href="?status=Dikirim" class="btn laos-outline-button me-2 my-2 <?php echo ($status === 'Dikirim') ? 'active' : ''; ?>">Dikirim</a>
            <a href="?status=Selesai" class="btn laos-outline-button me-2 my-2 <?php echo ($status === 'Selesai') ? 'active' : ''; ?>">Selesai</a>
            <a href="?status=Dibatalkan" class="btn laos-outline-button me-2 my-2 <?php echo ($status === 'Dibatalkan') ? 'active' : ''; ?>">Dibatalkan</a>
        </div>
        <div class="row justify-content-center">
            <?php foreach ($orders as $order) : ?>
                <?php if (!empty($order['product_name']) && !empty($order['quantity']) && !empty($order['total_price'])) : ?>
            <div class="card">
                <div class="card-body row justify-content-between align-items-center">
                    <div class="col-sm-4 col-md-4 col-12 d-flex align-items-center">
                        <img src="<?= $order['image_url']?>" alt="ini produk" class="img-fluid responsive-img rounded mb-3">
                        <!-- Menambahkan nama produk di sebelah kanan gambar -->
                        <div class="product-name ms-3">
                            <h4><?= $order['product_name']?></h4>
                            <div style="display: flex;">
                                <div class="me20">
                                    <h6 class="title-detail" >Variasi Produk:</h6>
                                    <p><?= $order['option_names']?></p>
                                </div>
                                <!-- <div class="me20">
                                    <h6 class="title-detail">Ukuran:</h6>
                                    <p>ukuran segini</p>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4 col-12">                     
                        <h6 class="title-detail mb-1">Jumlah:</h6>
                        <h5><?= $order['quantity']?></h5>
                        <h6 class="title-detail">Total:</h6> 
                        <h5>Rp. <?= number_format($order['total_price'], 0, ',', '.')?></h5>
                    </div>
                <hr>
                    <div class="col-sm-4 col-md-4 col-12">
                        <h5 class="title-detail ">Status:</h5>
                        <p class="alert alert-success mt-1" role="alert" style="max-width:fit-content;">
                           <?= $order['status_name']?>
                        </p>
                    </div>      
                    <div class="col-sm-4 col-md-4 col-12">
                        <a href="<?= BASEURL?>order/detail/<?= $order['id_order']?>" class="btn btn-secondary me-2">Detail Pesanan</a>
                        <a href="<?= BASEURL?>" class="btn btn-success" data-order-id="<?= $order['id_order']?>"  data-bs-toggle="modal" data-bs-target="#exampleModal">Pesanan Selesai</a>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Peringatan!</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Yakin ingin selesaikan pesanan?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="button" id="ConfirmCompleteOrder" class="btn btn-success">Selesaikan Pesanan</button>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php else:?>
                <h6>Belum ada pesanan...</h6>   
            <?php endif;?>
            <?php endforeach;?>
        </div>
      </div>
  </section>


   
    <!-- memperbarui status ketika menekan tombol selesaikan pesanan dan memfilter status -->
     <script>
            document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.laos-outline-button');
            const completeOrderButton = document.getElementById('confirmCompleteOrder');

            let orderId = null;

            // Menyimpan ID pesanan yang akan diselesaikan saat tombol "Pesanan Selesai" diklik
            document.querySelectorAll('.btn-success').forEach(button => {
                button.addEventListener('click', function() {
                    orderId = this.getAttribute('data-order-id'); // Ambil ID pesanan dari atribut data
                });
            });

            completeOrderButton.addEventListener('click', function() {
                if (orderId) {
                    fetch('/order/updateStatus', { // Arahkan ke rute updateStatus
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            'id_order': orderId,
                            'status': 'Delivered' // Status yang ingin diupdate
                        })
                    })
                    .then(response => response.text())
                    .then(result => {
                        if (result === 'success') {
                            // Reload page to reflect the status change
                            window.location.reload();
                        } else {
                            alert('Terjadi kesalahan saat memperbarui status pesanan.');
                        }
                    });
                }
            });

            buttons.forEach(button => {
                button.addEventListener('click', function (event) {
                    buttons.forEach(btn => btn.classList.remove('active'));
                    event.currentTarget.classList.add('active');
                });
            });
        });

     </script>
<?php
requireView("partials/footer.php");