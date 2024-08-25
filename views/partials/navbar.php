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

                        <?php

                        if (empty(AuthHelpers::getLoggedInUserData()) && isset($_SESSION['user'])){
                            jsRedirect(BASEURL . 'login?to=' . urlencode(getGlobalVar('url')));
                            return;       
                        }

                        ?>

                        <?php if(AuthHelpers::getLoggedInUserData()["role_name"] == "Admin"): ?>
                            <a style="text-decoration: none; color: inherit" href="<?= BASEURL ?>admin">
                                <button class="btn btn-warning active me-2 dropdown-toggle">
                                    <i class="fa-solid fa-key me-2"></i>
                                    Admin
                                </button>
                            </a>
                        <?php endif; ?>

                        <?php if(!isset($hideCart)): ?>

                            <div class="dropdown">
                                <button class="btn btn-warning active me-2 dropdown-toggle" type="button" id="cartDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end animate slideIn" aria-labelledby="cartDropdown" id="cartDropdownMenu">
                                    <!-- List items will be dynamically added here -->
                                </ul>
                            </div>


                        <?php endif; ?>

                        <div class="dropdown">
                            <button class="btn btn-warning active dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end animate slideIn" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item decoration-none" href="<?= BASEURL ?>user/<?= AuthHelpers::getLoggedInUserData()["id"] ?>/profile">Profil Saya</a></li>
                                <li><a class="dropdown-item decoration-none" href="<?= BASEURL?>order">Pesanan Saya</a></li>
                                <li><a class="dropdown-item decoration-none" id="logout" href="#">Logout</a></li>
                            </ul>
                        </div>

                    <?php else: ?>
                        <button class="btn btn-success"><a style="text-decoration: none; color: inherit" href="<?= BASEURL ?>login">Login</a></button>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</nav>

<script src="<?= BASEURL ?>public/js/components/loadingAnimation.js"></script>

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

function fetchCart(maxRetries = 5, retryDelay = 1000) {
    let attempt = 0;

    function attemptFetch() {
        return fetch(baseUrl + "api/get-mini-cart", { // Replace with your API endpoint
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                'token': t
            })
        })
        .then(response => {
            const contentType = response.headers.get('Content-Type');

            if (contentType.includes('text/html')) {
                return response.text().then(htmlContent => {
                    openHtmlContentToNewPage(htmlContent);
                    throw Error('Received HTML instead of JSON');
                });
            }

            return response.json();
        })
        .then(data => {
            return data.data; // Adjust based on your API response structure
        })
        .catch(error => {
            console.error('Error fetching cart items:', error);

            // Retry logic
            if (attempt < maxRetries) {
                attempt++;
                return new Promise((resolve) => setTimeout(resolve, retryDelay))
                    .then(() => attemptFetch());
            } else {
                return []; // Return an empty array on error after retries
            }
        });
    }

    return attemptFetch();
}

const menu = document.getElementById('cartDropdownMenu');

document.getElementById('cartDropdown').addEventListener('click', function() {
    injectBarLoader('cartDropdownMenu');
    fetchCart().then(cartItems => {
        console.log(cartItems);


        menu.innerHTML = ''; // Clear existing items

        let htmlContent = '<ul class="list-unstyled mb-0">';

        if (cartItems.length === 0) {
            // If there are no items, show "Keranjang Kosong" centered
            htmlContent += `
                <li class="text-center">Keranjang Kosong</li>
            `;
        } else {
            // Populate the list with cart items
            cartItems.forEach(item => {
                htmlContent += `
                    <li class="d-flex align-items-center mb-2 dropdown-item">
                        <a href="${baseUrl}cart?id=${item.id_cart_item}" class="d-flex align-items-center w-100 text-decoration-none text-dark" style="cursor: pointer;">
                            <img src="${item.image_url}" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;" alt="${item.product_name}">
                            <span class="flex-grow-1">${item.product_name.length > 20 ? item.product_name.substring(0, 20) + '...' : item.product_name}</span>
                            <span class="text-end" style="width: 100px;">Rp. ${item.price}</span>
                        </a>
                    </li>
                `;
            });

            htmlContent += `
                <li class="text-center mt-2">
                    <a href="${baseUrl}cart" class="text-decoration-none" 
                       onmouseover="this.style.textDecoration='underline'"
                       onmouseout="this.style.textDecoration='none'">
                       Tampilkan Semua
                    </a>
                </li>
            `;
        }

        htmlContent += '</ul>';
        menu.innerHTML = htmlContent;
    });
});
</script>