<?php
requireView("partials/head.php");
requireView("partials/navbar.php");
?>
  
  
  <section class="content">
      <div class="container mt-3">
        <h4>Pesananku</h4>   
        <div class="mb-3">
            <a href="?status=Semua" class="btn laos-outline-button me-2 <?php echo ($status === 'Semua') ? 'active' : ''; ?>">Semua</a>
            <a href="?status=Diproses" class="btn laos-outline-button me-2 <?php echo ($status === 'Diproses') ? 'active' : ''; ?>">Diproses</a>
            <a href="?status=Dikirim" class="btn laos-outline-button me-2 <?php echo ($status === 'Dikirim') ? 'active' : ''; ?>">Dikirim</a>
            <a href="?status=Selesai" class="btn laos-outline-button me-2 <?php echo ($status === 'Selesai') ? 'active' : ''; ?>">Selesai</a>
            <a href="?status=Dibatalkan" class="btn laos-outline-button me-2 <?php echo ($status === 'Dibatalkan') ? 'active' : ''; ?>">Dibatalkan</a>
        </div>
        <div class="row justify-content-center">
        <?php if (!empty($orders)) : ?>
            <?php foreach ($orders as $order) : ?>
            <div class="card">
                <div class="card-body row justify-content-between align-items-center">
                    <div class="col-sm-4 col-md-4 col-12 d-flex align-items-center">
                        <img src="" alt="ini produk" class="img-fluid">
                        <!-- Menambahkan nama produk di sebelah kanan gambar -->
                        <div class="product-name ms-3">
                            <h4><?= $item['product_name']?></h4>
                            <div style="display: flex;">
                                <div class="me20">
                                    <h6 class="title-detail" >Warna:</h6>
                                    <p>warna ini</p>
                                </div>
                                <div class="me20">
                                    <h6 class="title-detail">Ukuran:</h6>
                                    <p>ukuran segini</p>
                                </div>
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
                        <a href="<?= BASEURL?>order/detail/{$id}" class="btn btn-secondary me-2">Detail Pesanan</a>
                        <a href="<?= BASEURL?>" class="btn btn-success">Pesanan Selesai</a>
                    </div>
                </div>
                <?php endforeach;?>
            </div>
            <?php else:?>
                <p>Belum ada pesanan...</p>   
            <?php endif;?>
        </div>
      </div>
  </section>


    <!-- Mengatur tombol aktif berdasarkan status -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        // Ambil status dari query string
        const params = new URLSearchParams(window.location.search);
        const status = params.get('status') || 'Semua'; // Default ke 'Semua' jika tidak ada status

        // Ambil semua tombol
        const buttons = document.querySelectorAll('.laos-outline-button');

        buttons.forEach(button => {
            // Ambil status dari href tombol
            const buttonStatus = new URL(button.href).searchParams.get('status');

            // Setel kelas active jika status tombol sama dengan status query string
            if (buttonStatus === status) {
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }
        });

        // Tambahkan event listener untuk mengubah status saat tombol diklik
        buttons.forEach(button => {
            button.addEventListener('click', function (event) {
                // Hapus kelas active dari semua tombol
                buttons.forEach(btn => btn.classList.remove('active'));
                // Tambahkan kelas active pada tombol yang diklik
                event.currentTarget.classList.add('active');
            });
        });
    });

    </script>
<?php
requireView("partials/footer.php");