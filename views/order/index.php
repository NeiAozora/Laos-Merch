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
                <?php if (!empty($order['product_name']) && !empty($order['quantity']) && !empty($order['price'])) : ?>
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
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4 col-12">                     
                            <h6 class="title-detail mb-1">Jumlah:</h6>
                            <h5><?= $order['quantity']?></h5>
                            <h6 class="title-detail">Total:</h6> 
                            <?php 
                                if (empty($order['discount_value'])){
                                    $totalPrice = (float) $order['price'];
                                } else {
                                    $totalPrice = ($order['price'] * (1 - $order['discount_value'] / 100));                                    
                                }
                            ?>
                            <h5>Rp. <?= number_format($totalPrice, 0, ',', '.')?></h5>
                        </div>
                        <hr>
                        <div class="col-sm-4 col-md-4 col-12">
                            <h5 class="title-detail ">Status:</h5>
                            <?php
                                // Define status to class mapping
                                $statusClasses = [
                                    'Diproses' => 'alert-warning',  // Bootstrap class for warning
                                    'Sedang Dikirim' => 'alert-info',        // Bootstrap class for info
                                    'Selesai' => 'alert-success',   // Bootstrap class for success
                                    'Dibatalkan' => 'alert-danger'     // Bootstrap class for danger
                                ];

                                // Get the status from the order
                                $statusName = $order['status_name'];

                                // Determine the appropriate CSS class
                                $statusClass = isset($statusClasses[$statusName]) ? $statusClasses[$statusName] : 'status-default';
                                ?>

                                <p class="alert <?= $statusClass ?> mt-1" role="alert" style="max-width:fit-content;">
                                    <?= htmlspecialchars($statusName) ?>
                                </p>

                        </div>      
                        <div class="col-sm-4 col-md-4 col-12">
                            <a href="<?= BASEURL?>order/detail/<?= $order['id_order_item']?>" class="btn btn-secondary me-2">Detail Pesanan</a>
                            <?php if($order['status_name'] !== 'Selesai' AND 'Dibatalkan'):?>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $order['id_order']?>">
                                Pesanan Selesai
                            </button>
                            <?php else:?>
                                <a href="#" class="btn btn-warning" id="openRatingModal">Beri Rating</a>
                                <!-- <button type="button" class="btn btn-success" disabled>Pesanan Selesai</button> -->
                            <?php endif;?>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal<?= $order['id_order']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                            <form action="<?= BASEURL;?>order/updateStatus" method="post">
                                                <input type="hidden" name="id_order" value="<?= $order['id_order'] ?>">
                                                <input type="hidden" name="status" value="Delivered">
                                                <button type="submit" class="btn btn-success">Selesaikan Pesanan</button>
                                            </form>
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


<style>
        .star-rating {
            font-size: 2rem;
            color: #d3d3d3;
            cursor: pointer;
        }
        .star-rating .star.active {
            color: #f5c518;
        }
        .selected-images img {
            max-width: 100px;
            margin-right: 10px;
            margin-top: 10px;
        }
        .selected-images .image-container {
            position: relative;
            display: inline-block;
        }
        .selected-images .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: rgba(255, 255, 255, 0.7);
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
    </style>

    <!-- Modal -->
    <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ratingModalLabel">Beri Rating Anda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ratingForm">
                        <!-- Star Rating -->
                        <div class="star-rating mb-3">
                            <span class="star" data-value="1">&#9733;</span>
                            <span class="star" data-value="2">&#9733;</span>
                            <span class="star" data-value="3">&#9733;</span>
                            <span class="star" data-value="4">&#9733;</span>
                            <span class="star" data-value="5">&#9733;</span>
                        </div>
                        <!-- Text Area -->
                        <div class="mb-3">
                            <label for="reviewText" class="form-label">Isi Review</label>
                            <textarea id="reviewText" class="form-control" rows="4" placeholder="Write your review here..."></textarea>
                        </div>
                        <!-- Image Upload -->
                        <div class="mb-3">
                            <label for="reviewImages" class="form-label">Upload Images</label>
                            <input type="file" id="reviewImages" class="form-control" multiple>
                            <div class="selected-images" id="selectedImages"></div>
                        </div>
                        <!-- Anonymity Checkbox -->
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="anonymousCheck">
                            <label class="form-check-label" for="anonymousCheck">Submit sebagai anonim</label>
                        </div>
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stars = document.querySelectorAll('.star-rating .star');
        const selectedImagesContainer = document.getElementById('selectedImages');
        const reviewImagesInput = document.getElementById('reviewImages');
        let selectedRating = 0;
        let selectedImages = [];

        // Star Rating Functionality
        stars.forEach(star => {
            star.addEventListener('click', function () {
                selectedRating = this.getAttribute('data-value');
                stars.forEach(s => {
                    s.classList.toggle('active', s.getAttribute('data-value') <= selectedRating);
                });
            });
        });

        // Image Preview and Remove Functionality
        reviewImagesInput.addEventListener('change', function () {
            Array.from(this.files).forEach(image => {
                selectedImages.push(image);

                const imageContainer = document.createElement('div');
                imageContainer.classList.add('image-container');

                const imgElement = document.createElement('img');
                imgElement.src = URL.createObjectURL(image);

                const removeButton = document.createElement('button');
                removeButton.classList.add('remove-image');
                removeButton.innerHTML = '&times;';
                removeButton.addEventListener('click', function () {
                    const index = selectedImages.indexOf(image);
                    if (index > -1) {
                        selectedImages.splice(index, 1);
                    }
                    imageContainer.remove();
                });

                imageContainer.appendChild(imgElement);
                imageContainer.appendChild(removeButton);
                selectedImagesContainer.appendChild(imageContainer);
            });

            reviewImagesInput.value = ''; // Reset the input so the same file can be re-selected if removed
        });

        // Form Submission Handling
        document.getElementById('ratingForm').addEventListener('submit', function (event) {
            event.preventDefault();
            const reviewText = document.getElementById('reviewText').value;
            const anonymousCheck = document.getElementById('anonymousCheck').checked;
            console.log('Review Submitted:', reviewText, 'Anonymous:', anonymousCheck, 'Rating:', selectedRating, 'Images:', selectedImages);
            // Perform the actual submission here
        });

        // Modal Trigger
        const ratingModal = new bootstrap.Modal(document.getElementById('ratingModal'));
        document.getElementById('openRatingModal').addEventListener('click', function () {
            ratingModal.show();
        });
    });
</script>
<?php
requireView("partials/footer.php");
