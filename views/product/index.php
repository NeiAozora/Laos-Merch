<?php
requireView("partials/head.php");
requireView("partials/navbar.php");
?>

<section class="content mt-5">

    <div class="row">
        <!-- gambar produk -->
        <div class="col-sm-4 col-md-4 col-12 p-4">
            <img src="" alt="ini kaos" class="img-fluid">
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center mt-5">
                    <li class="page-item disabled">
                        <a class="page-link" style="text-decoration: none; color: inherit;">
                            << /a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#" style="text-decoration: none; color: inherit;"><img src="" alt="tampak depan"></a></li>
                    <li class="page-item"><a class="page-link" href="#" style="text-decoration: none; color: inherit;"><img src="" alt="tampak samping"></a></li>
                    <li class="page-item"><a class="page-link" href="#" style="text-decoration: none; color: inherit;"><img src="" alt="tampak belakang"></a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" style="text-decoration: none; color: inherit;">></a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="col-sm-4 col-md-4 col-12 p-4">
            <h2><?= $product["product_name"] ?></h2>
            <p class="title-detail">Stok Tersedia: <span id="stock-value"></span></p>
            <h3><span id="price"></span></h3>
            <h6><span id="full-price" style="text-decoration: line-through;"></span></h6>
            <?php if (!empty($discount)) : ?>


                <p class="title-detail">Diskon Berakhir Dalam:</p>
                <div id="countdown"></div>

                <script>
                    const endDate = new Date('<?= $discount["end_date"] ?>');

                    function updateCountdown() {
                        const now = new Date();
                        const timeDifference = endDate - now;

                        const days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

                        document.getElementById("countdown").innerHTML =
                            days + "Hari " +
                            hours + "Jam " +
                            minutes + "Menit " +
                            seconds + "Detik ";

                        if (timeDifference < 0) {
                            clearInterval(countdownInterval);
                            document.getElementById("countdown").innerHTML = "Countdown ended";
                        }
                    }

                    const countdownInterval = setInterval(updateCountdown, 1000);
                    updateCountdown();
                </script>
            <?php endif ?>



            <p class="title-detail">Deskripsi Produk</p>
            <p><?= $product["description"] ?></p>
            <p class="title-detail">Pilih Variasi Anda</p>
            <div id="variations-container">
                <?php foreach ($productVariations as $variation) : ?>
                    <div class="variation-group" data-variation-type="<?= $variation['id_variation_type'] ?>">
                        <label for="variation-type-<?= $variation['id_variation_type'] ?>" id="<?= $variation['id_variation_type'] ?>"><?= $variation["name"] ?></label>
                        <div>
                            <?php foreach ($variation["variation_options"] as $index => $option) : ?>
                                <button class="btn laos-outline-button <?= $index === 0 ? 'active' : '' ?>" data-option-id="<?= $option['id_option'] ?>" data-option-id-combination="<?= $option['id_combination'] ?>" onclick="chooseVariation('<?= $option['id_option'] ?>', <?= $variation['id_variation_type'] ?>)">
                                    <?= $option["option_name"] ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <script>
                const productCombination = [
                    <?php
                    foreach ($productCombinations as $productCombination) {
                        echo '{ id_combination: ' . intval($productCombination['id_combination']) . ', '
                            . 'id_product: ' . intval($productCombination['id_product']) . ', '
                            . 'price: ' . number_format($productCombination['price'], 2, '.', '') . ', '
                            . 'stock: ' . intval($productCombination['stock']) . ' },';
                    }
                    ?>
                ];
                const discount = {
                    discount_value: <?= isset($discount["discount_value"]) ? json_encode($discount["discount_value"]) : 'null' ?>
                }; // Set to null if no discount


                let selectedOptions = {};

                document.addEventListener('DOMContentLoaded', () => {
                    document.querySelectorAll('.variation-group').forEach(group => {
                        const firstOption = group.querySelector('.btn');
                        if (firstOption) {
                            const variationTypeId = group.getAttribute('data-variation-type');
                            firstOption.classList.add('active');
                            selectedOptions[variationTypeId] = firstOption.getAttribute('data-option-id');
                        }
                    });
                    updateDisplayedValues();
                });

                function chooseVariation(optionId, variationTypeId) {
                    selectedOptions[variationTypeId] = optionId;
                    document.querySelectorAll(`.variation-group[data-variation-type="${variationTypeId}"] .btn`).forEach(btn => {
                        btn.classList.toggle('active', btn.getAttribute('data-option-id') === optionId);
                    });

                    updateDisplayedValues();
                }

                function updateDisplayedValues() {
                    const selectedCombination = productCombination.find(comb => {
                        return Object.values(selectedOptions).includes(String(comb.id_combination));
                    });

                    if (!selectedCombination) {
                        console.log("Kombinasi tidak valid");
                        window.location = "<?= BASEURL ?>error?code=400&message=Bad%20Request&detailMessage=Produk%20memiliki%20kombinasi%20yang%20tidak%20valid.%20Segera%20hubungi%20admin.";
                    }

                    if (selectedCombination) {
                        const fullPrice = selectedCombination.price;
                        const hasDiscount = discount && discount.discount_value > 0;
                        console.log(selectedCombination);

                        let checkOutLabelVariationType = document.getElementById("");

                        if (hasDiscount) {
                            const discountedPrice = fullPrice - discount.discount_value;
                            document.getElementById('price').textContent = `Rp ${discountedPrice.toFixed(2)}`;
                            document.getElementById('full-price').textContent = `Rp ${fullPrice.toFixed(2)}`;
                        } else {

                            document.getElementById('price').textContent = 'Rp ${fullPrice.toFixed(2)}'; // Clear discounted price
                            // document.getElementById('full-price').textContent = `Rp ${fullPrice.toFixed(2)}`;
                        }

                        document.getElementById('stock-value').textContent = selectedCombination.stock;
                    }
                }
            </script>

        </div>
        <!-- checkout -->
        <div class="col-sm-4 col-md-4 col-12 mt-5 d-flex justify-content-center">
            <div class="card d-flex flex-column justify-content-between" style="width:18rem;">
                <h5 class="mt-2 d-flex justify-content-center">Atur Pilihanmu</h5>
                <div class="ms-2">
                    <?php foreach ($productVariations as $variation) : ?>
                        <p><?= $variation["name"] ?> : <span id="variation-type-<?= $variation['id_variation_type'] ?>"></span></p>
                    <?php endforeach; ?>
                    <p>Jumlah :</p>
                    <p>Subtotal :</p>
                </div>
                <div class="mt-auto text-center">
                    <form method="POST" action="<?= BASEURL ?>cart/add" id="add-cart">
                        <input type="hidden" name="quantity" id="cart-quantity" value="1">
                        <input type="hidden" name="id_combination" id="combination-id" value="">
                        <button class="btn btn-success mb-2 mt-3" style="width:12rem;">Masukkan Keranjang</button>
                    </form>

                    <button class="btn laos-outline-button mb-3" style="width:12rem;">Beli Langsung</button>
                </div>

                <script>
                    document.querySelector('#add-cart').addEventListener('submit', function(event) {
                        event.preventDefault();

                        let selectedCombination = productCombination.find(comb => {
                            return Object.values(selectedOptions).includes(String(comb.id_combination));
                        });

                        if (selectedCombination) {
                            document.getElementById('combination-id').value = selectedCombination.id_combination;
                            event.target.submit();
                        } else {
                            console.log("Kombinasi tidak valid");
                            window.location = "<?= BASEURL ?>error?code=400&message=Bad%20Request&detailMessage=Produk%20memiliki%20kombinasi%20yang%20tidak%20valid.%20Segera%20hubungi%20admin.";
                        }
                    });
                </script>

            </div>
        </div>
    </div>
    <h3 class="d-flex justify-content-center mt-5">Produk Lainnya</h3>
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-6 col-md-3 mt-3">
                <div class="card extra" style="text-align: left;">
                    <a href="#" style="text-decoration: none; color: inherit;">
                        <img src="#" class="card-img-top" alt="ini foto">
                        <div class="card-body">
                            <h5 class="card-title">Produk 1</h5>
                            <p class="card-text">Rating: 5/5</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-3 mt-3">
                <div class="card extra" style="text-align: left;">
                    <a href="#" style="text-decoration: none; color: inherit;">
                        <img src="#" class="card-img-top" alt="ini foto">
                        <div class="card-body">
                            <h5 class="card-title">Produk 2</h5>
                            <p class="card-text">Rating: 5/5</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-3 mt-3">
                <div class="card extra" style="text-align: left;">
                    <a href="#" style="text-decoration: none; color: inherit;">
                        <img src="#" class="card-img-top" alt="ini foto">
                        <div class="card-body">
                            <h5 class="card-title">Produk 3</h5>
                            <p class="card-text">Rating: 4/5</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-3 mt-3">
                <div class="card extra" style="text-align: left;">
                    <a href="#" style="text-decoration: none; color: inherit;">
                        <img src="#" class="card-img-top" alt="ini foto">
                        <div class="card-body">
                            <h5 class="card-title">Produk 4</h5>
                            <p class="card-text">Rating: 5/5</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>



</section>



<?php
requireView("partials/footer.php");
?>