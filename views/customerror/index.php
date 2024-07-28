<?php

requireView("partials/head.php");
requireView("partials/navbar.php");

?>


<!-- not found -->
<div class="container mt-5 content notfound" style="text-align: center;">
        <h1 class="notfound"><?= $errorCode ?></h1>
        <h3 class="notfound"><?= $errorMessage ?></h3>
        <p  class="notfound"><?= $detailMessage ?></p>
        <button class="btn btn-success mt-3" style="width:280px;" onclick="window.location = '<?= BASEURL ?>'">Reset Pencarian</button>
</div>

<?php
requireView("partials/footer.php");
