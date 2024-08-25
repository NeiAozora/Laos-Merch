<?php
requireView("partials/head.php");
requireView("partials/navbar.php");

$order = $data['order'];
?>

<style>
.collapse {
    transition: height 0.5s ease-out;
}

.collapsing {
    height: 0;
    overflow: hidden;
    transition: height 0.5s ease-out;
}

</style>

<section class="content">
    <div class="container mt-3">
        <h4>Detail Pesanan</h4>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="card mb-3">
                    <div class="card-body row justify-content-between align-items-center">
                        <div class="col-lg-6 col-md-6 col-12 d-flex align-items-start">
                            <h5 class="title-detail">Pesanan:</h5>
                        </div>
                    </div>

                    <div class="collapse" id="collapseTrack">
                        <div class="card card-body mb-2 ms-3 me-3">
                            <h5>Detail Pelacakan</h5>
                            <table class="table">
                                <tbody id="shipment-details">
                                    <?php $currentStatus = ''; ?>
                                    <?php foreach (array_reverse($shipmentDetails) as $shipmentDetail): ?>
                                        <tr>
                                            <td style="width: 20%; vertical-align: top;">
                                                <h6 class="mb-0"><?= $shipmentDetail['detail_date'] ?></h6>
                                            </td>
                                            <td style="width: 80%; vertical-align: top;">
                                                <?php if ($currentStatus != $shipmentDetail['status_name']): ?>
                                                    <h6 class="mb-0"><?= $shipmentDetail['status_name']; ?></h6>
                                                    <?php $currentStatus = $shipmentDetail['status_name']; ?>
                                                <?php endif; ?>
                                                <div style="">
                                                    <h6 class="text-muted"><?= $shipmentDetail['detail_description'] ?></h6>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="card-body d-flex justify-content-between">
                        <div class="d-flex flex-column">
                            <div class="d-flex flex-column text-end">
                                <h6>No. Invoice:</h6>
                                <h6>Tanggal Pembelian:</h6>
                                <h6>Status:</h6>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-start ms-3">
                            <div class="position-top-right">
                            <a class="title-detail decoration-none" data-bs-toggle="collapse" href="#collapseTrack" role="button" aria-expanded="false" aria-controls="collapseTrack">
                                Lacak Pesanan
                            </a>
                            </div>
                            <h6><?= htmlspecialchars($order['id_order']); ?></h6>
                            <h6><?= htmlspecialchars($order['order_date']); ?></h6>
                            
                            <?php

                                $reverseStatusMapping = [
                                    null => 'Semua',
                                    'Processing' => 'Diproses',
                                    'Shipped' => 'Sedang Dikirim',
                                    'Delivered' => 'Selesai',
                                    'Cancelled' => 'Dibatalkan'
                                ];



                                // Define status to Bootstrap text color class mapping
                                $statusClasses = [
                                    'Diproses' => 'text-warning',  // Bootstrap class for warning
                                    'Sedang Dikirim' => 'text-info',        // Bootstrap class for info
                                    'Selesai' => 'text-success',   // Bootstrap class for success
                                    'Dibatalkan' => 'text-danger'     // Bootstrap class for danger
                                ];

                                // Get the status from the order
                                $statusName =  $reverseStatusMapping[$order['status_name']];

                                // Determine the appropriate Bootstrap text color class
                                $statusClass = isset($statusClasses[$statusName]) ? $statusClasses[$statusName] : 'text-secondary';
                                ?>

                                <h6 class="<?= $statusClass ?>"><?= htmlspecialchars( $statusName); ?></h6>

                        </div>
                    </div>
                </div>


                <div class="card mb-3">
                    <div class="card-body row justify-content-between align-items-center">
                        <h5 class="title-detail mb-2">Produk:</h5>
                        <?php $orderItem = $orderItems[getIndexByValue($orderItems, 'id_order_item', $idOrderItem)] ?>
                        <div class="mb-2">
                            <div class="card">
                                <div class="card-body row justify-content-between align-items-center">
                                    <div class="col-sm-4 col-md-4 col-12 d-flex">
                                        <img src="<?= htmlspecialchars($orderItem['image_url']); ?>" alt="Produk" class="img-fluid rounded">
                                        <div class="product-name ms-3">
                                            <h4><?= htmlspecialchars($orderItem['product_name']); ?></h4>
                                            <div style="display: flex;">
                                                <div class="me20">
                                                    <h6 class="title-detail">Variasi Produk:</h6>
                                                    <p><?= htmlspecialchars($orderItem['option_names']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-12">
                                        <h6 class="title-detail mb-1">Jumlah:</h6>
                                        <h5><?= htmlspecialchars($order['quantity']); ?></h5>
                                        <h6 class="title-detail">Total:</h6>
                                        <?php 
                                            if (empty($orderItem['discount_value'])){
                                                $totalPrice = $orderItem['price'] * $orderItem['quantity'];
                                            } else {
                                                $totalPrice = ($orderItem['price'] * (1 - $orderItem['discount_value'] / 100)) * $orderItem['quantity'];                                    
                                            }
                                        ?>
                                        <h5>Rp. <?= number_format($totalPrice, 0, ',', '.')?></h5>
                                        <?php if (!empty($orderItem['discount_value'])): ?>
                                            <span style="text-decoration: line-through; color: #888;">Rp <?php echo rtrim(rtrim(number_format($orderItem['price'] * $orderItem['quantity'], 2), '0'), '.'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if(count($orderItems) > 1): ?>
                <div class="card mb-3">
                    <div class="card-body row justify-content-between align-items-center">
                        <h5 class="title-detail mb-2">Produk lain yang terkait dalam pesanan:</h5>
                        <?php unset($orderItems[getIndexByValue($orderItems, 'id_order_item', $idOrderItem)]) ?>
                        <?php foreach($orderItems as $orderItem): ?>
                        <div class="mb-2">
                            <div class="card">
                                <div class="card-body row justify-content-between align-items-center">
                                    <div class="col-sm-4 col-md-4 col-12 d-flex">
                                        <img src="<?= htmlspecialchars($orderItem['image_url']); ?>" alt="Produk" class="img-fluid rounded">
                                        <div class="product-name ms-3">
                                            <h4><?= htmlspecialchars($orderItem['product_name']); ?></h4>
                                            <div style="display: flex;">
                                                <div class="me20">
                                                    <h6 class="title-detail">Variasi Produk:</h6>
                                                    <p><?= htmlspecialchars($orderItem['option_names']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-12">
                                        <h6 class="title-detail mb-1">Jumlah:</h6>
                                        <h5><?= htmlspecialchars($order['quantity']); ?></h5>
                                        <h6 class="title-detail">Total:</h6>
                                        <?php 
                                            if (empty($orderItem['discount_value'])){
                                                $totalPrice = (float) $orderItem['price'];
                                            } else {
                                                $totalPrice = ($orderItem['price'] * (1 - $orderItem['discount_value'] / 100));                                    
                                            }
                                        ?>
                                        <h5>Rp. <?= number_format($totalPrice, 0, ',', '.')?></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>

                <?php endif; ?>


                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="title-detail mb-3">Info Pengiriman:</h5>
                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-column text-end">

                                    <?php if(isset($order['carrier_name'])): ?>
                                    <h6>Kurir:</h6>
                                    <?php endif; ?>
                                    <?php if(isset($shipment['tracking_number'])): ?>
                                    <h6>No. resi pengiriman:</h6>
                                    <?php endif; ?>
                                    <h6>Nama Penerima:</h6>
                                    <h6>No Whatsapp:</h6>
                                    <h6>Alamat:</h6>
                                    <h6>Catatan:</h6>
                                </div>
                            </div>
                            <div class="d-flex flex-column text-start ms-3">
                                <?php if(isset($order['carrier_name'])): ?>
                                    <h6><?= htmlspecialchars($order['carrier_name']); ?></h6>
                                <?php endif; ?>
                                <?php if(isset($shipment['tracking_number'])): ?>
                                <h6 class=""><?= htmlspecialchars($shipment['tracking_number']); ?></h6>
                                <?php endif; ?>
                                <h6 class=""><?= htmlspecialchars($order['recipient_name']); ?></h6>
                                <h6 class=""><?= htmlspecialchars($order['wa_number']); ?></h6>
                                
                                <h6 class=""><?= htmlspecialchars($order['address'] ?? '<span class="text-danger">(Dihapus)</span>'); ?></h6>
                                <h6 class=""><?= htmlspecialchars($order['extra_note']); ?></h6>

                            </div>
                        </div>  
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="title-detail mb-3">Info Pembayaran:</h5>
                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-column text-end">
                                    <h6>Metode Pembayaran:</h6>
                                    <h6>Sub Total Produk:</h6>
                                    <h6>Biaya Penanganan:</h6>
                                    <h6>Biaya Layanan:</h6>
                                    <h6>Total Ongkos Kirim:</h6>
                                    <h5 class="mt-1" style="font-weight: bold;">Total Belanja:</h5>
                                </div>
                            </div>
                            <div class="d-flex flex-column text-start ms-3">
                                <h6><?= $order['payment_method'] ?></h6>
                                <h6>Rp. <?= number_format($order['total_price'], 0, ',', '.'); ?></h6>
                                <h6>Rp. <?= number_format($order['service_fee'], 0, ',', '.'); ?></h6>
                                <h6>Rp. <?= number_format($order['handling_fee'], 0, ',', '.'); ?></h6>
                                <h6>Rp. 0</h6>
                                <h5 class="mt-1" style="font-weight: bold;">Rp. <?= number_format($order['total_price'] + $order['service_fee'] + $order['handling_fee'], 0, ',', '.'); ?></h5>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-10 col-sm-12">
            <form action="<?= BASEURL;?>order/updateStatus" method="post">
                <input type="hidden" name="id_order" value="<?= $order['id_order'] ?>">
                <input type="hidden" name="status" value="Cancelled">
                <div class="d-flex flex-column justify-content-center mt-1">
                    <a class="btn laos-outline-button mb-3" onclick="history.back()">Kembali</a>
                    <?php if(($order['status_name'] !== 'Cancelled') && ($order['status_name'] !== 'Delivered')): ?>
                        <a type="button" class="btn btn-success mb-3" href="https://wa.me/+6285606689642" target="_blank">Hubungi Admin</a>
                        <button class="btn btn-secondary">Batalkan Pesanan</button>
                   
                    <?php endif; ?>
                </div>
            </form>
            </div>  
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select the tbody element by its id
    var tbody = document.getElementById('shipment-details');

    // Fetch all the tr elements
    var rows = Array.from(tbody.getElementsByTagName('tr'));

    // Reverse the order of rows
    rows.reverse();

    // Clear the existing tbody content
    tbody.innerHTML = '';

    // Append the reversed rows back to tbody
    rows.forEach(function(row) {
        tbody.appendChild(row);
    });
});
</script>


<?php
requireView("partials/footer.php");
?>
