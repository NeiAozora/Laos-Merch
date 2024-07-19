
<!-- footer -->
<footer class="footer mt-5">
    <div class="container bottom_border">
        <div class="row">
            <div class="col-sm-4 col-md-3 col-6 mt-2">
                <h5 class="footer-heading">Layanan Pelanggan</h5>
                <a href="#" class="footer-link">Bantuan</a>
                <a href="#" class="footer-link">Metode Pembayaran</a>
                <a href="#" class="footer-link">Pengembalian Barang & Dana</a>
                <a href="#" class="footer-link">Lacak Pesanan Pembeli</a>
                <a href="#" class="footer-link">Lacak Pengiriman</a>
            </div>
            <div class="col-sm-4 col-md-3 col-6 mt-2">
                <h5 class="footer-heading">Jelajahi</h5>
                <a href="#" class="footer-link">Tentang Kami</a>
                <a href="#" class="footer-link">Kebijakan LAOS Merch</a>
                <a href="#" class="footer-link">Kebijakan Privasi</a>
            </div>
            <div class="col-sm-4 col-md-3 col-6 mt-2">
                <h5 class="footer-heading">Metode Pembayaran</h5>
                <div class="d-flex">
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg" alt="ini dana" class="payment-method-icon col-2 col-md-3">
                <img src="<?= BASEURL ?>public/assets/ovo.png" alt="ini ovo" class="payment-method-icon col-2 col-md-3">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="ini kartu" class="payment-method-icon col-2 col-md-3">
                </div>
                
            </div>
            <div class="col-sm-4 col-md-3 col-6 mt-2">
                <h5 class="footer-heading">Metode Pengiriman</h5>
                <img src="" alt="ini jnt" class="shipping-method-icon">
                <img src="" alt="ini jne" class="shipping-method-icon">
            </div>
        </div>
        <p class="text-center mt-2 footer-copy">Copyright &copy;2024 By <a href="#" class="footer-link" target="_blank">Tim Pemburu Gratisan</a></p>
    </div>
</footer>


<style>
    .footer {
    background-color: #e5e5e5;
    color: #000;
    font-family: 'Oxygen', sans-serif;
    font-size: 12px; /* Adjust font size as needed */
}

.footer-heading {
    font-weight: bold;
    font-size: 14px; /* Adjust heading font size as needed */
}

.footer-link {
    display: block;
    color: #000;
    text-decoration: none;
    font-weight: lighter;
    font-size: 12px; /* Adjust font size as needed */
    margin-bottom: 5px;
}

.footer-link:hover {
    text-decoration: underline;
}

.payment-method-icon,
.shipping-method-icon {
    width: 50px; /* Adjust icon size as needed */
    margin-right: 10px;
}

.footer-copy {
    font-size: 6px; /* Adjust copyright text size as needed */
    color: #000;
}

.payment-method-icon {
    width: 30px; /* Adjust width as needed */
    height: auto; /* Maintain aspect ratio */
    max-height: 50px; /* Adjust max-height as needed */
    margin-right: 10px; /* Space between images */
    object-fit: contain; /* Ensure image fits within the specified dimensions */
}


</style>



<!-- Penutup body -->
<script>
// Function to check if the device is mobile
function isMobile() {
    return window.innerWidth <= 768; // Adjust the breakpoint as needed
}

// Function to add the appropriate class based on device type
function updateDropdownMenuClass() {
    const dropdownMenus = document.querySelectorAll('.dropdown-menu');
    
    dropdownMenus.forEach(menu => {
        if (isMobile()) {
            // On mobile, add 'dropdown-menu-first'
            menu.classList.add('dropdown-menu-first');
            menu.classList.remove('dropdown-menu-end'); // Remove if it was previously added
        } else {
            // On desktop, add 'dropdown-menu-end'
            menu.classList.add('dropdown-menu-end');
            menu.classList.remove('dropdown-menu-first'); // Remove if it was previously added
        }
    });
}

// Initial check
updateDropdownMenuClass();

// Optional: Update class on window resize
window.addEventListener('resize', updateDropdownMenuClass);

</script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

<script src="https://kit.fontawesome.com/36f40da328.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"
    integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE"
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"
    integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ"
    crossorigin="anonymous"></script>
</body>
</html>