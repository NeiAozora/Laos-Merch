<?php
   requireView("partials/head.php");
   requireView("partials/navbar.php");
   ?>
<section class="content mt-5">
   <div class="row">
<!-- gambar produk -->
<div class="col-sm-4 col-md-4 col-12 p-4 product-image-container">
   <img src="" alt="productImage" id="productMainImage" class="img-fluid">
   <nav aria-label="Page navigation example">
      <ul class="image-pagination pagination">
         <li class="page-item disabled">
            <a class="page-link" style="text-decoration: none; color: inherit;"><</a>
         </li>
         <?php $i = 1 ?>
         <?php foreach($productImages as $productImage): ?>
         <li class="page-item">
            <a class="page-link" href="#" style="text-decoration: none; color: inherit;">
               <img src="<?= $productImage['image_url'] ?>" alt="Gambar Produk <?= $i ?>">
            </a>
         </li>
         <?php $i++ ?>
         <?php endforeach; ?>
         <li class="page-item">
            <a class="page-link" href="#" style="text-decoration: none; color: inherit;">></a>
         </li>
      </ul>
   </nav>
</div>


      <!-- info produk -->
      <div class="col-sm-4 col-md-4 col-12 p-4">
         <h2><?= $product["product_name"] ?></h2>
         <p class="title-detail">Stok Tersedia: <span id="stock-value"></span></p>
         <div class="d-flex">
            <h3><span id="price"></span></h3>
            <?php if (!empty($discount)): ?>
            <div class="discount-label-container">
               <svg class="discount-label-svg-image" viewBox="0 0 79 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M0 0H79V30H0L11 15L0 0Z" fill="#D7211E"/>
               </svg>
               <div class="discount-label-overlay-text">-<?= round($discount['discount_value']) ?>%</div>
            </div>
            <?php endif; ?>
         </div>
         <h6><span id="full-price" style="text-decoration: line-through;"></span></h6>
         <?php if (!empty($discount)): ?>
         <div class="d-flex">
            <p class="title-detail">Diskon Berakhir Dalam:</p>
            <div id="countdown"></div>
         </div>
         <script>
            // Set the end date for the countdown (format: YYYY-MM-DDTHH:MM:SS)
            const endDate = new Date('<?= $discount["end_date"] ?>');
            
            function updateCountdown() {
                const now = new Date();
                const timeDifference = endDate - now;
            
                // Calculate days, hours, minutes, and seconds
                const days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);
            
                // Update the countdown element
                document.getElementById("countdown").innerHTML = 
                    days + "Hari " + 
                    hours + "Jam " + 
                    minutes + "Menit " + 
                    seconds + "Detik ";
            
                // If the countdown is finished, display a message
                if (timeDifference < 0) {
                    clearInterval(countdownInterval);
                    document.getElementById("countdown").innerHTML = "Countdown ended";
                }
            }
            
            // Update the countdown every second
            const countdownInterval = setInterval(updateCountdown, 1000);
            
            // Initial call to display the countdown immediately
            updateCountdown();
         </script>
         <?php endif ?>
         <p class="title-detail">Deskripsi Produk</p>
         <p><?= $product["description"] ?></p>
         <p class="title-detail">Pilih Variasi Anda</p>
         <div id="variations-container">
         <?php foreach($productVariations as $variation): ?>
            <div class="variation-group" data-variation-type="<?= $variation['id_variation_type'] ?>">
               <label for="variation-type-<?= $variation['id_variation_type'] ?>" id="<?= $variation['id_variation_type'] ?>"><?= $variation["name"]?>:</label>
               <div>
                     <?php foreach($variation["variation_options"] as $index => $option): ?>
                     <button 
                        class="btn laos-outline-button <?= $index === 0 ? 'active' : '' ?>" 
                        data-option-id="<?= $option['id_option'] ?>" 
                        onclick="chooseVariation('<?= $option['id_option'] ?>', <?= $variation['id_variation_type'] ?>)">
                        <?= $option["option_name"] ?>
                     </button>
                     <?php endforeach; ?>
               </div>
            </div>
            <?php endforeach; ?>
         </div>
      </div>
      <!-- checkout -->
      <div class="col-sm-4 col-md-4 col-12 mt-5 d-flex justify-content-center">
               <div class="card d-flex flex-column justify-content-between" style="width:18rem;">
            <h5 class="mt-2 d-flex justify-content-center">Atur Pilihanmu</h5>
            <div class="ms-2">
               <?php foreach($productVariations as $variation): ?>
               <p><b class="font-weight-bold"><?= $variation["name"] ?></b> : <span id="variation-type-<?= $variation['id_variation_type'] ?>"></span></p>
               <?php endforeach; ?>
               <div class="d-flex align-items-center mb-3">
                     <p class="mb-0 me-2"><b>Jumlah:</b></p>
                     <div class="d-flex align-items-center">
                        <button type="button" id="decrease-quantity" class="btn btn-outline-secondary btn-sm">-</button>
                        <input type="number" id="cart-quantity" name="quantity" value="1" min="1" class="form-control mx-2" style="width:60px;">
                        <button type="button" id="increase-quantity" class="btn btn-outline-secondary btn-sm">+</button>
                     </div>
                  </div>
                  <p><b>Subtotal:</b> <span id="subtotal">Rp 0.00</span></p>

            </div>
            <div class="mt-auto text-center">
               <form method="POST" action="<?= BASEURL?>cart/add" id="add-cart">
                     <input type="hidden" name="id_combination" id="combination-id" value="">
                     <button class="btn btn-success mb-2 mt-3" style="width:12rem;">Masukkan Keranjang</button>
               </form>
               <button class="btn laos-outline-button mb-3" style="width:12rem;">Beli Langsung</button>
            </div>
         </div>

            <!-- Aksi utama -->
            <script>
    const productCombinations = [
        <?php foreach($productCombinations as $combination): ?>
            {
                id_combination: <?= $combination['id_combination'] ?>,
                id_product: <?= $combination['id_product'] ?>,
                price: <?= number_format($combination['price'], 2, '.', '') ?>,
                stock: <?= $combination['stock'] ?>,
                combination_details: <?= json_encode($combination['combination_details']) ?>
            },
        <?php endforeach; ?>
    ];

    <?php

    ?>

      const variationOptions = [
         <?php
            foreach ($variationOptions as $variationOption) {
               $id_option = htmlspecialchars($variationOption['id_option'], ENT_QUOTES, 'UTF-8');
               $id_variation_type = htmlspecialchars($variationOption['id_variation_type'], ENT_QUOTES, 'UTF-8');
               $option_name = htmlspecialchars($variationOption['option_name'], ENT_QUOTES, 'UTF-8');
               $image_url = htmlspecialchars($variationOption['image_url'], ENT_QUOTES, 'UTF-8');

               echo json_encode([
                  "id_option" => $id_option,
                  "id_variation_type" => $id_variation_type,
                  "option_name" => $option_name,
                  "image_url" => $image_url
               ]) . ",\n";
            }
         ?>
      ];

    // Create a map for quick lookup of id_variation_type based on id_option
    const optionMap = variationOptions.reduce((map, option) => {
        map[option.id_option] = option.id_variation_type;
        return map;
    }, {});


    const discount = <?= json_encode($discount['discount_value'] ?? null) ?>;

    // Initialize selectedOptions with null values
    let selectedOptions = {
        <?php foreach($productVariations as $variation): ?>
            <?= $variation['id_variation_type'] ?>: null,
        <?php endforeach; ?>
    };

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


    let selectedPrice = 0;

    function updateSubtotal() {
        const quantity = parseInt(document.getElementById('cart-quantity').value, 10);
        const subtotal = selectedPrice * quantity;
        document.getElementById('subtotal').textContent = `Rp ${removeTrailingZeros(subtotal.toFixed(2))}`;
    }

    // Quantity control event listeners
    document.getElementById('increase-quantity').addEventListener('click', () => {
        const quantityInput = document.getElementById('cart-quantity');
        quantityInput.value = parseInt(quantityInput.value, 10) + 1;
        updateSubtotal();
    });

    document.getElementById('decrease-quantity').addEventListener('click', () => {
        const quantityInput = document.getElementById('cart-quantity');
        const currentValue = parseInt(quantityInput.value, 10);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            updateSubtotal();
        }
    });

    function updateDisplayedValues() {
        const selectedCombination = productCombinations.find(comb => {
            // Create a map of the combination details for quick comparison
            const combinationDetailsMap = comb.combination_details.reduce((map, detail) => {
                map[optionMap[detail.id_option]] = detail.id_option;
                return map;
            }, {});

            // Compare the selected options with the combination details
            return Object.keys(combinationDetailsMap).every(key => {
                return selectedOptions[key] === combinationDetailsMap[key].toString();
            });
        });



        if (!selectedCombination) {
            console.log("Selected combination not found.");
            window.location = "<?= BASEURL ?>error?code=400&message=Bad%20Request&detailMessage=Produk%20memiliki%20kombinasi%20yang%20tidak%20valid.%20Segera%20hubungi%20admin.";

            return;
        }

        if (selectedCombination) {
            const fullPrice = selectedCombination.price;
            const hasDiscount = discount && discount > 0;


            // Update displayed variation options
            variationOptions.forEach(option => {
                if (selectedOptions[option.id_variation_type] === option.id_option) {
                    document.getElementById('variation-type-' + option.id_variation_type).textContent = option.option_name;
                }
            });

            // Update price and stock
            if (hasDiscount) {
                const discountedPrice = fullPrice * (1 - discount / 100);
                document.getElementById('price').textContent = `Rp ${removeTrailingZeros(discountedPrice.toFixed(2))}`;
                document.getElementById('full-price').textContent = `Rp ${removeTrailingZeros(fullPrice.toFixed(2))}`;
               selectedPrice = removeTrailingZeros(discountedPrice.toFixed(2));

            } else {
                document.getElementById('price').textContent = `Rp ${removeTrailingZeros(fullPrice.toFixed(2))}`;
                selectedPrice = removeTrailingZeros(fullPrice.toFixed(2));
            }

            updateSubtotal();

            document.getElementById('stock-value').textContent = selectedCombination.stock;
        }
    }


    document.querySelector('#add-cart').addEventListener('submit', function(event) {
        event.preventDefault();

        const selectedCombination = productCombinations.find(comb => {
            return comb.combination_details.every(detail => {
                return selectedOptions[detail.id_variation_type] == detail.id_option;
            });
        });

        if (selectedCombination) {
            document.getElementById('combination-id').value = selectedCombination.id_combination;
            event.target.submit();
        } else {
            console.log("Selected combination is not valid for adding to cart.");
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Function to update the main image
        function updateMainImage(imageUrl) {
            document.getElementById('productMainImage').src = imageUrl;
        }

        // Function to highlight the selected image
        function highlightSelectedImage(selectedElement) {
            document.querySelectorAll('.image-pagination .page-item img').forEach(img => {
                img.style.border = 'none'; // Remove highlight from all images
            });
            selectedElement.style.border = '2px solid gray'; // Highlight selected image
        }

        // Append option images to pagination if image_url is present
        const pagination = document.querySelector('.image-pagination');
        

        variationOptions.forEach(option => {
            if (option.image_url) {
                const listItem = document.createElement('li');
                listItem.className = 'page-item';

                const link = document.createElement('a');
                link.className = 'page-link';
                link.href = '#';
                link.style.textDecoration = 'none';
                link.style.color = 'inherit';

                const img = document.createElement('img');
                img.src = option.image_url;
                img.alt = `Option Image ${option.option_name}`;
                img.style.width = '50px'; // Adjust size as needed
                img.style.cursor = 'pointer'; // Add pointer cursor

                img.addEventListener('click', () => {
                    updateMainImage(option.image_url);
                    highlightSelectedImage(img);
                });

                link.appendChild(img);
                listItem.appendChild(link);
                pagination.insertBefore(listItem, pagination.querySelector('.page-item:last-child')); // Insert before the last item
            }
        });

        // Initialize first image if necessary
        const firstImage = pagination.querySelector('.page-item img');
        if (firstImage) {
            updateMainImage(firstImage.src);
            highlightSelectedImage(firstImage);
        }
    });
</script>




         </div>
      </div>
   </div>
   <!-- rekomendasi produk lainnya -->
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