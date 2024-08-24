<!-- navbar -->
<script>
var t;
var baseUrl = "<?= BASEURL ?>";
document.addEventListener('DOMContentLoaded', () => {
    // Check if there are any <footer> elements
    const hasFooter = document.getElementsByTagName('footer').length > 0;
    // Check if there is a <span id="plain-exception"> element with text containing the specified substring
    const hasPlainException = Array.from(document.querySelectorAll('span#plain-exception')).some(
        span => span.textContent.includes('Whoops\\Exception\\')
    );

    // Reload the page after a short delay (if needed)
    if (!hasFooter) {
        setTimeout(() => {
            window.location.reload();
        }, 2000); // Delay of 2 seconds (2000 milliseconds)
    }
});

function openHtmlContentToNewPage(htmlContent) {
    // Make sure the HTML content is properly escaped
    if (typeof htmlContent !== 'string') {
        console.error('Invalid HTML content');
        return;
    }

    // Create a Blob object with the HTML content
    var blob = new Blob([htmlContent], { type: 'text/html' });

    // Create a URL for the Blob
    var url = URL.createObjectURL(blob);

    // Open the Blob URL in a new tab
    var newTab = window.open(url, '_blank');

    // Check if the new tab was successfully created
    if (!newTab) {
        console.error('Failed to open new tab. Please ensure that pop-ups are allowed.');
        return;
    }

    // Optionally, revoke the URL after some time to free up memory
    setTimeout(function() {
        URL.revokeObjectURL(url);
    }, 10000); // 10 seconds
}


function formatPriceValue(value) {
    // Check if the value is a number and not null/undefined
    if (value == null || isNaN(value)) {
        console.error("Invalid number:", value);
        return "0.00"; // Default or fallback value
    }

    // Ensure value is a number
    value = parseFloat(value);

    // Convert the value to a fixed-point notation with two decimals
    let parts = value.toFixed(2).split(".");

    // Add commas to the integer part
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

    // Check if the decimal part is ".00" and remove it if so
    if (parts[1] === "00") {
        return parts[0];
    }

    // Join the integer part with the decimal part
    return parts.join(".");
}

// Utility function to encode data to Base64
function encodeBase64(data) {
    // Convert string to Uint8Array
    const encoder = new TextEncoder();
    const uint8Array = encoder.encode(data);
    // Convert Uint8Array to Base64 string
    return btoa(String.fromCharCode(...uint8Array));
}

</script>
<script type="module" src="<?= BASEURL ?>public/js/fbase.js"></script>
<script src="<?= BASEURL ?>public/js/fbaseAuth.js" type="module"></script>




<style>
    .dropdown-toggle::after {
        display: none;
    }

    * {
        font-family: "Nunito", sans-serif;
        font-optical-sizing: auto;
        font-weight: 300;
        font-style: normal;
    }

</style>

<nav class="navbar navbar-light navbar-expand-lg shadow-lg fixed-top" style="background-color: #fff;">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="<?= BASEURL?>">
            <img src="<?= BASEURL ?>public/assets/LogoLaos.png" alt="ini foto" class="me-2" width="30" height="30">
            <h6 class="title mb-0">LAOS Merch</h6>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">LAOS Merch</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 w-100 d-flex flex-column align-items-center">
                    <div class="search-bar-container">
                        <form class="d-flex search-bar" role="search" id="search-form" method="get" action="">
                            <input class="form-control me-2" name="search" type="search" placeholder="Cari Merchandise..." aria-label="Search" autofocus>
                            <button class="btn btn-success btn-search" type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </ul>
                <div class="d-inline-flex gap-1">
                    <?php if (AuthHelpers::isLoggedIn()): ?>
                        <div class="dropdown">
                            <button class="btn btn-warning active me-2 dropdown-toggle" type="button" id="cartDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end animate slideIn" aria-labelledby="cartDropdown">
                                <li class="dropdown-item"><a href="<?= BASEURL?>cart" class="decoration-none">Keranjang Saya</a></li>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-warning active dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user"></i>
                            </button>
                            <?php

                            if (empty(AuthHelpers::getLoggedInUserData()) && isset($_SESSION['user'])){
                                jsRedirect(BASEURL . 'login?to=' . urlencode(getGlobalVar('url')));
                                return;       
                            }

                            ?>
                            <ul class="dropdown-menu dropdown-menu-end animate slideIn" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item decoration-none" href="<?= BASEURL ?>user/<?= AuthHelpers::getLoggedInUserData()["id"] ?>/profile">Profil Saya</a></li>
                                <li><a class="dropdown-item decoration-none" href="<?= BASEURL?>order">Pesanan Saya</a></li>
                                <li><a class="dropdown-item decoration-none" id="logout" href="#">Logout</a></li>
                            </ul>
                        </div>
                        <?php if(AuthHelpers::getLoggedInUserData()["role_name"] == "Admin"): ?>
                            <a style="text-decoration: none; color: inherit" href="<?= BASEURL ?>admin">
                                <button class="btn btn-warning active me-2 dropdown-toggle">
                                    <i class="fa-solid fa-key me-2"></i>
                                    Admin
                                </button>
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <button class="btn btn-success"><a style="text-decoration: none; color: inherit" href="<?= BASEURL ?>login">Login</a></button>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</nav>

<script>
    let formSearch = document.getElementById("search-form");
    formSearch.setAttribute("action", "<?= BASEURL ?>")

    function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function removeTrailingZeros(number) {
  // Convert the number to a string
  let str = number.toString();
  
  // Check if it contains a decimal point
  if (str.indexOf('.') !== -1) {
    // Remove trailing zeros
    str = str.replace(/\.?0+$/, '');
  }
  
  return str;
}

</script>