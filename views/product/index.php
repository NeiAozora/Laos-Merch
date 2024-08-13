<?php
   requireView("partials/head.php");
   requireView("partials/navbar.php");
   ?>
<section class="content mt-5">
    <div class="row container-fluid d-flex">
      <!-- gambar produk -->
      <div class="col-sm-4 col-md-4 col-12 p-4 product-image-container">
         <img src="" alt="productImage" id="productMainImage" class="img-fluid" style="border-radius:8px; max-height: 17rem">
         <nav aria-label="Page navigation example">
            <ul class="image-pagination pagination">
               <li class="page-item disabled">
                  <a class="page-link" style="text-decoration: none; color: inherit;"><</a>
               </li>
               <?php $i = 1 ?>
               <?php foreach($productImages as $productImage): ?>
               <li class="page-item animate-1sec slideIn ">
                <?php

                    if (strpos($productImage['image_url'], 'public/storage/') !== false) {
                        $productImage['image_url'] = BASEURL . $productImage['image_url'];
                    }

                ?>
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
      <div class="col-sm-4 col-md-4 col-12 p-4 container">
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
                  <input type="hidden" name="quantity" id="input-quantity" value="">
                  <button class="btn btn-success mb-2 mt-3" id="add-to-cart-button" style="width:12rem;">Masukkan Keranjang</button>
               </form>
               <button class="btn laos-outline-button mb-3" id="buy-button" style="width:12rem;">Beli Langsung</button>
            </div>
         </div>
      </div>
   </div>
   </div>
   <div class="container">
    <!-- rekomendasi produk lainnya -->
    <?php if (count($products) > 0 ): ?>
    <h3 class="d-flex justify-content-center mt-5">Produk Lainnya</h3>
    <?php endif; ?>
       <div class="row  " id="products-container">


        <?php

        function updateProducts($products) {
            $filledStar = '
            <svg class="star" width="15" height="14" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 2px;">
              <path d="M10.1304 0L12.4293 7.07548H19.8689L13.8501 11.4484L16.1491 18.5238L10.1304 14.151L4.11159 18.5238L6.41055 11.4484L0.391793 7.07548H7.83139L10.1304 0Z" fill="#FFC100"/>
            </svg>';
            
            $unfilledStar = '
            <svg class="star" width="15" height="14" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 2px;">
              <path d="M9.76121 0L12.0602 7.07548H19.4998L13.481 11.4484L15.78 18.5238L9.76121 14.151L3.74245 18.5238L6.04141 11.4484L0.0226526 7.07548H7.46225L9.76121 0Z" fill="#D9D9D9"/>
            </svg>';

            $output = '';
            
            if (count($products) < 1) {
            } else {
                foreach ($products as $product) {
                    $avgPrice = (float)$product['avg_price'];
                    $discountValue = (float)$product['discount_value'];
                    $discountLabel = '';

                    if (strpos($product['product_image'], 'public/storage/') !== false) {
                        $product['product_image'] = BASEURL . $product['product_image'];
                    }

                    if ($discountValue > 0) {
                        $discountLabel = '
                        <div class="discount-label">
                            <svg class="svg-image" viewBox="0 0 79 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M78.988 29.9637L65.3345 53.6189L65.334 29.9638L78.988 29.9637Z" fill="#830D0B"/>
                                <path d="M0 0H79V30H0L11 15L0 0Z" fill="#D7211E"/>
                            </svg>
                            <div class="overlay-text">' . round($discountValue) . '%</div>
                        </div>';
                    }

                    $discountedPrice = number_format($avgPrice * (1 - $discountValue / 100), 2);

                    $starRatingHTML = '';
                    $fullStars = floor($product['avg_rating']);
                    $halfStar = $product['avg_rating'] % 1 !== 0;
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

                    for ($i = 0; $i < $fullStars; $i++) {
                        $starRatingHTML .= $filledStar;
                    }
                    if ($halfStar) {
                        $starRatingHTML .= '
                        <svg class="star" width="15" height="14" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 2px;">
                            <path d="M10.1304 0L12.4293 7.07548H19.8689L13.8501 11.4484L16.1491 18.5238L10.1304 14.151L4.11159 18.5238L6.41055 11.4484L0.391793 7.07548H7.83139L10.1304 0Z" fill="#FFC100" style="clip-path: inset(0 50% 0 0);"/>
                        </svg>';
                    }
                    for ($i = 0; $i < $emptyStars; $i++) {
                        $starRatingHTML .= $unfilledStar;
                    }

                    $productHTML = '
                    <div class="col-6 col-md-3 product-card-container">
                        <div class="card product-card">
                            ' . $discountLabel . '
                            <a href="' . BASEURL . 'product/' . htmlspecialchars($product['id_product']) . '" style="text-decoration: none; color: inherit;">
                                <div class="img-container">
                                    <img src="' . htmlspecialchars($product['product_image']) . '" class="card-img-top" alt="' . htmlspecialchars($product['product_name']) . '">
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-row mb-2">
                                        ' . $starRatingHTML . '
                                    </div>
                                    <p class="card-text">
                                        ' . htmlspecialchars($product['product_name']) . '<br>
                                        <span style="font-weight: bold;">Rp ' . removeTrailingZeros($discountedPrice) . '</span>
                                        ' . ($discountValue > 0 ? '<br><span style="text-decoration: line-through; color: #888;">Rp ' . removeTrailingZeros(number_format($avgPrice, 2)) . '</span>' : '') . '
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>';

                    $output .= $productHTML;
                }
            }
            
            return $output;
        }

        function removeTrailingZeros($price) {
            return rtrim(rtrim($price, '0'), '.');
        }

        // Fetch and display products (default page and search)
        echo updateProducts($products);
        ?>
        </div>

    </div>

<?php

$apiUrl = BASEURL . "api/reviews/product/" . $product['id_product'];;
$reviewsData = file_get_contents($apiUrl);
$reviews = json_decode($reviewsData, true)['reviews'];


function censorName($name) {
   $parts = explode(' ', $name);
   $censoredParts = array_map(function($part) {
       return $part[0] . str_repeat('*', strlen($part) - 1);
   }, $parts);
   return implode(' ', $censoredParts);
}

function escapeHtml($string) {
   return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>

?>

<div class="container mt-5" id="reviews-container">
    <?php if (!empty($reviews)) : ?>
        <div class="d-flex justify-content-center mt-5" id="reviews-header">
            <h3>Ulasan Terbaru</h3>
        </div>

        <?php
        $reviewHtmls = '';
        foreach ($reviews as $review) {
            $profilePicture = !empty($review['profile_picture']) ? $review['profile_picture'] : "https://via.placeholder.com/60";
            $name = !empty($review['full_name']) ? $review['full_name'] : $review['username'];
            if ($review['anonymity'] > 0) {
                $name = censorName($name);
            }
            $stars = str_repeat('&#9733;', $review['rating']) . str_repeat('&#9734;', 5 - $review['rating']);
            $variation_name = implode(', ', array_map(function($variation) {
                return $variation['variation_name'];
            }, $review['variations']));
            $comment = escapeHtml($review['comment']);
            
            $reviewHtmls .= '
                <div class="review-card">
                    <div class="review-image">
                        <img src="' . $profilePicture . '" alt="User Image">
                    </div>
                    <div class="review-details">
                        <h5>' . $name . '<span class="review-rating">' . $stars . '</span></h5>
                        <div class="review-date">' . $review['date_posted'] . '</div>
                        <div class="review-product">
                            <span style="font-weight: bold;">Barang</span>: ' . $review['product_name'] . '<br>
                            <span style="font-weight: bold;">Variasi</span>: ' . $variation_name . '
                        </div>
                        <div class="review-text">' . $comment . '</div>
                    </div>';
            if (!empty($review['images'])) {
                foreach ($review['images'] as $image) {
                    $reviewHtmls .= '
                        <div class="review-product-image">
                            <img src="' . $image['image_url'] . '" alt="Product Image">
                        </div>';
                }
            }
            $reviewHtmls .= '</div>';
        }
        ?>

        <!-- Reviews HTML Placeholder -->
        <div id="reviews-cards" class="reviews-cards">
            <?php echo $reviewHtmls; ?>
        </div>

        <!-- Pagination Controls -->
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center mt-5">
                <li class="page-item" id="prev-page">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item" id="page-info"><span class="page-link"></span></li>
                <li class="page-item" id="next-page">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

    <?php else : ?>
        <p>No reviews found.</p>
    <?php endif; ?>
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
      
         if (strpos($image_url, 'public/storage/') !== false) {
            $image_url = $baseUrl . $image_url;
        }

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
</script>
<script src="<?= BASEURL ?>public/js/components/loadingAnimation.js"></script>
<script src="<?= BASEURL ?>public/js/customerPageProduct.js"></script>
<?php
   requireView("partials/footer.php");
   ?>