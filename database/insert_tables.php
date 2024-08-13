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
    ('Pakaian', 'Pakaian dan aksesoris.');

-- Insert Products
INSERT INTO products (id_category, product_name, description, weight, dimensions, discontinued)
VALUES
    (1, 'Smartphone', 'Smartphone model terbaru dengan fitur canggih', '150g', '145 x 70 x 7 mm', FALSE),
    (2, 'Kaos', 'Kaos katun yang nyaman', '200g', 'Medium', FALSE);

-- Insert Variation Types
INSERT INTO variation_types (id_product, name)
VALUES
    (1, 'Warna'), -- id 1 untuk Smartphone
    (1, 'Storage'), -- id 2 untuk Smartphone
    (2, 'Ukuran'); -- id 3 untuk Kaos

-- Insert Variation Options
INSERT INTO variation_options (id_variation_type, option_name, image_url)
VALUES
    (1, 'Hitam', NULL),   -- Pilihan untuk Warna
    (1, 'Putih', NULL),   -- Pilihan untuk Warna
    (2, '64GB', NULL),    -- Pilihan untuk Storage
    (2, '128GB', NULL),   -- Pilihan untuk Storage
    (3, 'Medium', NULL),  -- Pilihan untuk Ukuran
    (3, 'Large', NULL);   -- Pilihan untuk Ukuran

-- Insert Variation Combinations
INSERT INTO variation_combinations (id_product, price, stock)
VALUES
    (1, 700000.00, 50),  -- Smartphone, Warna: Hitam, Storage: 64GB
    (1, 800000.00, 30),  -- Smartphone, Warna: Hitam, Storage: 128GB
    (1, 750000.00, 20),  -- Smartphone, Warna: Putih, Storage: 64GB
    (1, 850000.00, 10),  -- Smartphone, Warna: Putih, Storage: 128GB
    (2, 150000.00, 100),  -- Kaos, Ukuran: Medium
    (2, 150000.00, 80);   -- Kaos, Ukuran: Large

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
    (6, 6);  -- Kaos, ukuran Large

-- Insert Product Images
INSERT INTO product_images (id_product, image_url)
VALUES
    (1, 'https://images.pexels.com/photos/607812/pexels-photo-607812.jpeg?cs=srgb&dl=pexels-tracy-le-blanc-67789-607812.jpg&fm=jpg'),
    (2, 'public/storage/kaos.webp');

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
    ('Kaos', 'public/storage/carausel_kaos.webp', 'product/2', 'Kaos T-Shirt', 'Kaos Berkualitas', 'Beli Sekarang', 'product/2');

-- Insert Reviews
INSERT INTO reviews (id_combination, id_user, rating, comment, anonymity)
VALUES
    (1, 1, 5, 'Smartphone yang luar biasa dengan fitur-fitur hebat!', FALSE);

-- Insert Review Images
INSERT INTO review_images (id_review, image_url)
VALUES
    (1, 'https://images.pexels.com/photos/699122/pexels-photo-699122.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');

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
    (1, 'Rumah', 'Jl. Merdeka No. 10', 'Jakarta', 'DKI Jakarta', '10110', 'Tinggalkan di depan pintu', TRUE, FALSE);

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
    (1, NOW(), 120000.00, 2, 1, 10000.00, 5000.00, 2000.00, 1);

-- Insert Order Items
INSERT INTO order_items (id_order, quantity, id_combination, id_discount, price)
VALUES
    (1, 1, 1, 1, 700000.00);  -- Pesanan 1: Smartphone dengan diskon 10%

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
INSERT INTO shipments (id_order, id_carrier, shipping_method, tracking_number, shipment_date, expected_delivery_date, status)
VALUES
    (1, 1, 'Pengiriman Standar', 'TRACK12345', NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY), 'Dalam Perjalanan');

-- Insert Shipment Statuses
INSERT INTO shipment_statuses (id_shipment, status_date, status_description)
VALUES
    (1, NOW(), 'Kurir telah ditugaskan, dan sedanng dalam perjalanan.');
";

$db = new Database();

$db->query($query);
$db->execute();


echo "All tables values has created successfully.";