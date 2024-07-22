<?php

require_once "views/partials/head.php";
require_once "views/partials/navbar.php";

?>

<section class="main">

<style>
    .card {
        text-align: left;
        margin: auto;
    }
    .card img {
        width: 100%;
        height: auto;
    }
</style>

<!-- carousel -->
    <div id="carouselAutoplaying" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <a href=""><img src="<?= BASEURL ?>public/assets/OldTrafford.jpg" class="d-block w-100 carousel-img" alt="..."></a>
            </div>
            <div class="carousel-item">
                <a href=""><img src="<?= BASEURL ?>public/assets/OldTrafford.jpg" class="d-block w-100 carousel-img" alt="..."></a>
            </div>
            <div class="carousel-item">
                <a href=""><img src="<?= BASEURL ?>public/assets/OldTrafford.jpg" class="d-block w-100 carousel-img" alt="..."></a>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselAutoplaying" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselAutoplaying" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        <ol class="carousel-indicators">
            <li data-bs-target="#carouselAutoplaying" data-bs-slide-to="0" class="active"></li>
            <li data-bs-target="#carouselAutoplaying" data-bs-slide-to="1"></li>
            <li data-bs-target="#carouselAutoplaying" data-bs-slide-to="2"></li>
        </ol>
    </div>



    
  
<!-- card -->
    <h1 class="d-flex justify-content-center mt-5">Official Merchandise</h1>
    <div class="container">
        <div class="row justify-content-center" id="products-container">

        <!-- being looped 8 times per page -->


            
        </div>
    </div>


<!-- pagination -->
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center mt-5">
          <li class="page-item disabled">
            <a class="page-link" style="text-decoration: none; color: inherit;"><</a>
          </li>
          <li class="page-item"><a class="page-link" href="#" style="text-decoration: none; color: inherit;">1</a></li>
          <li class="page-item"><a class="page-link" href="#" style="text-decoration: none; color: inherit;">2</a></li>
          <li class="page-item"><a class="page-link" href="#" style="text-decoration: none; color: inherit;">3</a></li>
          <li class="page-item">
            <a class="page-link" href="#" style="text-decoration: none; color: inherit;">></a>
          </li>
        </ul>
      </nav>
  
      
      
      <div class="d-flex justify-content-center mt-5">
          <h3>Ulasan Terbaru</h3>
        </div>
        
</section>

<script>
    formSearch = document.getElementById("search-form");
    formSearch.setAttribute("action", "")
</script>
<script src="<?= BASEURL ?>public/js/components/loadingAnimation.js"></script>
<script src="<?= BASEURL ?>public/js/homepageProducts.js"></script>


<?php
require_once "views/partials/footer.php";
?>