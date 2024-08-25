<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'laos_merch');
require_once dirname(dirname(__FILE__)) . "/app/core/Database.php";
require_once "helper.php";


$query = 
"
-- Insert Roles
INSERT INTO roles (role_name, role_description)
VALUES
    ('Admin', 'Administrator dengan hak akses penuh.'),
    ('Customer', 'Pelanggan biasa dengan akses untuk manajemen pesanan dan akun.'),
    ('Support', 'Staf dukungan dengan akses ke fungsionalitas layanan pelanggan.');

-- Insert Permissions
INSERT INTO permissions (permission_name, permission_description)
VALUES
    ('VIEW_PRODUCTS', 'Memungkinkan melihat produk.'),
    ('EDIT_PRODUCTS', 'Memungkinkan mengedit produk.'),
    ('MANAGE_ORDERS', 'Memungkinkan mengelola pesanan.'),
    ('MANAGE_USERS', 'Memungkinkan mengelola pengguna.');

-- Insert Role Permissions
INSERT INTO role_permissions (id_role, id_permission)
VALUES
    (1, 1),  -- Admin dapat VIEW_PRODUCTS
    (1, 2),  -- Admin dapat EDIT_PRODUCTS
    (1, 3),  -- Admin dapat MANAGE_ORDERS
    (1, 4),  -- Admin dapat MANAGE_USERS
    (2, 1),  -- Customer dapat VIEW_PRODUCTS
    (2, 3);  -- Customer dapat MANAGE_ORDERS

-- Insert Users
INSERT INTO users (id_firebase, username, password, first_name, last_name, profile_picture, email, wa_number, id_role, is_active)
VALUES
    ('ljiO9mSdnocXG1veOvRfW3g84wX2', 'noxindocraft', 'password123', 'Ahmad', 'Fauzan', '', 'noxindocraft@gmail.com', '+6283119624458', 1, TRUE);

-- Insert Categories
INSERT INTO categories (category_name, category_description)
VALUES
    ('Elektronik', 'Perangkat dan aksesoris elektronik.'),
    ('Minuman', 'PErrangkat Minuman.'),
    ('Pakaian', 'Pakaian dan aksesoris.');

-- Insert Products
INSERT INTO products (id_category, product_name, description, weight, dimensions, discontinued) VALUES
(1, 'Smartphone', 'Temukan teknologi mutakhir dengan smartphone terbaru kami. Dirancang untuk menawarkan performa optimal dengan fitur-fitur canggih, seperti layar beresolusi tinggi, kamera berkualitas, dan kapasitas penyimpanan yang luas. Dengan desain ramping dan elegan, smartphone ini ideal untuk kebutuhan sehari-hari Anda.', '150 g', '145 x 70 x 7 mm', false),
(3, 'Kaos', 'Rasakan kenyamanan maksimal dengan kaos katun premium kami. Dibuat dari bahan katun berkualitas tinggi, kaos ini memberikan keleluasaan bergerak dan sirkulasi udara yang baik. Desain yang simpel namun stylish cocok untuk berbagai kesempatan, dari santai hingga semi-formal.', '200 g', 'Medium', false),
(1, 'Ubuntu 24.04', 'Dapatkan salinan fisik dari distribusi Linux paling populer dengan Ubuntu 24.04. Ideal untuk para pengembang dan pengguna Linux, versi ini menawarkan stabilitas dan keamanan dengan berbagai fitur baru. Datang dalam format yang mudah digunakan, memastikan Anda mendapatkan semua manfaat dari sistem operasi open-source ini.', '14–33 g', '4.75 inci (disk)', false),
(1, 'Botol Minuman', 'Botol air dengan desain yang menarik dan warna yang unik, sempurna untuk gaya hidup aktif dan modern. Terbuat dari material berkualitas tinggi, botol ini menjaga minuman Anda tetap segar dan dingin. Ideal untuk dibawa kemana saja, baik saat berolahraga maupun bepergian.', '150 g', '25 x 4 cm', false),
(1, 'Kamera Fujifilm', 'Kamera Fujifilm ini merupakan pilihan sempurna bagi pecinta fotografi yang menginginkan kualitas gambar yang tajam dan warna yang akurat. Dengan desain retro dan fungsionalitas canggih, kamera ini ideal untuk berbagai situasi pemotretan, dari potret hingga pemandangan. Nikmati pengalaman fotografi yang menyenangkan dengan keandalan dari FujiFilm.', '800 g', '6 x 6 cm', false);

-- Insert Variation Types
INSERT INTO variation_types (id_product, name)
VALUES
    (1, 'Warna'), -- id 1 untuk Smartphone
    (1, 'Storage'), -- id 2 untuk Smartphone
    (2, 'Ukuran'), -- id 3 untuk Kaos
    (3, 'Warna'),
    (4, 'Tipe'),
    (5, 'Tipe');


-- Insert Variation Options
INSERT INTO variation_options (id_variation_type, option_name, image_url)
VALUES
    (1, 'Hitam', NULL),   -- Pilihan untuk Warna
    (1, 'Putih', NULL),   -- Pilihan untuk Warna
    (2, '64GB', NULL),    -- Pilihan untuk Storage
    (2, '128GB', NULL),   -- Pilihan untuk Storage
    (3, 'Medium', NULL),  -- Pilihan untuk Ukuran
    (3, 'Large', NULL),   -- Pilihan untuk Ukuran
    (4, 'Coklat', 'public/storage/ubuntu_2.png'),
    (4, 'Merah', 'public/storage/ubuntu_3.png'),
    (5, 'Original', NULL),
    (6, 'Original', NULL);

-- Insert Variation Combinations
INSERT INTO variation_combinations (id_product, price, stock)
VALUES
    (1, 700000.00, 50),  -- Smartphone, Warna: Hitam, Storage: 64GB
    (1, 800000.00, 30),  -- Smartphone, Warna: Hitam, Storage: 128GB
    (1, 750000.00, 20),  -- Smartphone, Warna: Putih, Storage: 64GB
    (1, 850000.00, 10),  -- Smartphone, Warna: Putih, Storage: 128GB
    (2, 150000.00, 100),  -- Kaos, Ukuran: Medium
    (2, 150000.00, 80),   -- Kaos, Ukuran: Large
    (3, 7000.00, 100),  
    (3, 7000.00, 80),  
    (4, 24000.00, 30),   
    (5, 1000000.00, 15);   



-- Insert Combination Details
INSERT INTO combination_details (id_combination, id_option)
VALUES
    (1, 1),  -- Smartphone, warna Hitam
    (1, 3),  -- Smartphone, storage 64GB
    (2, 1),  -- Smartphone, warna Hitam
    (2, 4),  -- Smartphone, storage 128GB
    (3, 2),  -- Smartphone, warna Putih
    (3, 3),  -- Smartphone, storage 64GB
    (4, 2),  -- Smartphone, warna Putih
    (4, 4),  -- Smartphone, storage 128GB
    (5, 5),  -- Kaos, ukuran Medium
    (6, 6),  -- Kaos, ukuran Large
    (7, 7),  
    (8, 8),  
    (9, 9),
    (10, 10);

-- Insert Product Images
INSERT INTO product_images (id_product, image_url)
VALUES
    (1, 'public/storage/smartphone.png'),
    (2, 'public/storage/kaos.png'),
    (3, 'public/storage/ubuntu_2.png'),
    (4, 'public/storage/botol.png'),
    (5, 'public/storage/fujifilm-kamera.jpeg');



-- Insert Tags
INSERT INTO tags (tag_name)
VALUES
    ('Produk Baru'),
    ('Best Seller'),
    ('Diskon');

-- Insert Product Tags
INSERT INTO product_tags (id_product, id_tag)
VALUES
    (1, 1),  -- Smartphone ditandai sebagai Produk Baru
    (2, 3);  -- Kaos ditandai sebagai Diskon

-- Insert Carousels
INSERT INTO carousels (name, image_url, link, title, subtitle, button_text, button_link)
VALUES
    ('Kaos', 'public/storage/carausel_kaos.png', 'product/2', 'Kaos T-Shirt', 'Kaos Berkualitas', 'Beli Sekarang', 'product/2'),
    ('Kaos', 'public/storage/carausel_ubuntu.png', 'product/3', 'Ubuntu 24.04', 'Salinan Fisik Linux Ubuntu 24.04', 'Beli Sekarang', 'product/3');

-- Insert Cart Items
INSERT INTO cart_items (id_user, id_combination, quantity)
VALUES
    (1, 1, 2);

-- Insert Discount Types
INSERT INTO discount_types (type_name)
VALUES
    ('Persentase'),
    ('Jumlah Tetap');

-- Insert Discounts
INSERT INTO discounts (id_discount_type, discount_value, start_date, end_date)
VALUES
    (1, 10.00, NOW(), DATE_ADD(NOW(), INTERVAL 1 MONTH)),  -- Diskon 10%
    (2, 20000.00, NOW(), DATE_ADD(NOW(), INTERVAL 1 MONTH));  -- Diskon Rp20.000

-- Insert Discount Products
INSERT INTO discount_products (id_discount, id_product)
VALUES
    (1, 1);  -- Diskon 10% untuk Smartphone

-- Insert Payment Methods
INSERT INTO payment_methods (method_name)
VALUES
    ('COD'),
    ('Transfer');

-- Insert Shipping Addresses
INSERT INTO shipping_addresses (id_user, label_name, street_address, city, state, postal_code, extra_note, is_prioritize, is_temporary)
VALUES
    (1, 'Rumah', 'Jl. X No. 10', 'Banyuwangi', 'Jawa Timur', '9999', 'Tinggalkan di depan pintu', TRUE, FALSE);

-- Insert Order Statuses
INSERT INTO order_statuses (status_name)
VALUES
    ('Pending Payment'),
    ('Processing'),
    ('Shipped'),
    ('Delivered'),
    ('Cancelled');

-- Insert Orders
INSERT INTO orders (id_user, order_date, total_price, id_status, id_shipping_address, shipping_fee, service_fee, handling_fee, id_payment_method)
VALUES
    (1, NOW(),  630000.00, 4, 1, 10000.00, 5000.00, 2000.00, 1);

-- Insert Order Items
INSERT INTO order_items (id_order, quantity, id_combination, discount_value, price)
VALUES
    (1, 1, 1, 10.00, 700000.00);  -- Pesanan 1: Smartphone dengan diskon 10%

-- Insert Shipment Companies
INSERT INTO shipment_companies (company_name, company_email, company_website)
VALUES
    ('Laos Merch Shipment', 'kurir@laosmerch.co.id', 'http://laosmerch.neiaozora.my.id'),
    ('JNT Express', 'info@jntexpress.co.id', 'http://jntexpress.co.id');

-- Insert Carriers
INSERT INTO carriers (carrier_name, wa_number, email, id_shipment_company)
VALUES
    ('Bobon', '0812-3456-7890', 'bobon@gmail.com', 1),
    ('Kushiro', '0856-7890-1234', 'kushiro@gmail.com', 2);

-- Insert Shipments
INSERT INTO shipments (id_order, id_shipment_company, id_carrier, tracking_number, shipment_date, expected_delivery_date, actual_delivery_date)
VALUES
    (1, 2, NULL, 'T7252GTHH6262', NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY), NULL);


-- Insert Shipment Statuses
INSERT INTO shipment_statuses (status_name)
VALUES
    ('Pesanan Dibuat'),
    ('Sedang Dikemas'),
    ('Pesanan dalam Pengiriman'),
    ('Terkirim');

-- Insert Shipment Details
INSERT INTO shipment_details (id_shipment, id_status, detail_date, detail_description)
VALUES
    (1, 4, '2024-07-07 09:00:00', 'Pesanan telah sampai diterima oleh Yang bersangkutan. Penerima: AhmadFauzan. Lihat Bukti Pengiriman.'),
    (1, 3, '2024-07-07 07:49:00', 'Pesanan sedang diantar ke alamat tujuan.'),
    (1, 3, '2024-07-07 07:48:00', 'Kurir sudah ditugaskan. Pesanan segera dikirim.'),
    (1, 3, '2024-07-07 07:08:00', 'Pesanan telah sampai di lokasi transit Hub terakhir Srono Hub.'),
    (1, 3, '2024-07-07 05:48:00', 'Pesanan diproses di lokasi transit terakhir.'),
    (1, 3, '2024-07-07 03:04:00', 'Pesanan sedang dalam perjalanan menuju ke Virtual Jember DC.'),
    (1, 3, '2024-07-06 14:06:00', 'Pesanan telah sampai di lokasi sortir Jember DC.'),
    (1, 3, '2024-07-06 13:44:00', 'Pesanan diproses di lokasi transit terakhir.'),
    (1, 3, '2024-07-05 11:33:00', 'Pesanan sedang dalam perjalanan menuju ke Jember DC.'),
    (1, 3, '2024-07-05 07:58:00', 'Pesanan telah sampai di lokasi sortir Tapos DC.'),
    (1, 3, '2024-07-05 04:13:00', 'Pesanan telah sampai di lokasi transit Hub.'),
    (1, 3, '2024-07-05 01:08:00', 'Pesanan telah dikirim dari lokasi transit Hub.'),
    (1, 3, '2024-07-04 15:44:00', 'Pesanan telah sampai di lokasi transit Hub Kalideres First Mile Hub.'),
    (1, 3, '2024-07-04 14:14:00', 'Pesanan telah diserahkan ke jasa kirim untuk diproses.'),
    (1, 3, '2024-07-03 22:23:00', 'Pesanan telah diterima oleh: Agen SPX Express DOP OnDelivery Ruko Palm Boulevard.'),
    (1, 2, '2024-07-03 12:30:00', 'Kurir ditugaskan untuk menjemput pesanan.'),
    (1, 2, '2024-07-02 18:11:00', 'Penjual telah mengatur pengiriman. Menunggu pesanan diserahkan ke pihak jasa kirim.'),
    (1, 1, '2024-07-02 17:59:00', 'Pesanan Dibuat.');


    -- Insert Reviews
    INSERT INTO reviews (id_combination, id_user, id_order_item, rating, comment, anonymity)
    VALUES
        (1, 1, NULL, 3, 'Smartphone nya bagus tapi kurirnya kurang ramah, bintang 3', FALSE);

    -- Insert Review Images
    INSERT INTO review_images (id_review, image_url)
    VALUES
        (1, 'https://images.pexels.com/photos/699122/pexels-photo-699122.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');


";

$db = new Database();

$db->query($query);
$db->execute();


echo "All tables values has created successfully.";