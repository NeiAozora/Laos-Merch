<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'laos_merch');
require_once dirname(dirname(__FILE__)) . "/app/core/Database.php";
require_once "helper.php";


$query = 
"
-- Insert Categories
INSERT INTO categories (category_name, category_description)
VALUES
    ('Electronics', 'Electronic devices and accessories.'),
    ('Apparel', 'Clothing and accessories.');

-- Insert Products
INSERT INTO products (id_category, product_name, description, weight, dimensions, discontinued)
VALUES
    (1, 'Smartphone', 'Latest model smartphone with advanced features', '150g', '145 x 70 x 7 mm', FALSE),
    (2, 'T-Shirt', 'Comfortable cotton t-shirt', '200g', 'Medium', FALSE);

-- Insert Variation Types
INSERT INTO variation_types (id_product, name)
VALUES
    (1, 'Color'), -- id 1 for Smartphone
    (1, 'Storage'), -- id 2 for Smartphone
    (2, 'Size'); -- id 3 for T-Shirt

-- Insert Variation Options
INSERT INTO variation_options (id_variation_type, option_name, image_url)
VALUES
    (1, 'Black', NULL),   -- Option for Color
    (1, 'White', NULL),   -- Option for Color
    (2, '64GB', NULL),    -- Option for Storage
    (2, '128GB', NULL),   -- Option for Storage
    (3, 'Medium', NULL),  -- Option for Size
    (3, 'Large', NULL);   -- Option for Size

-- Insert Variation Combinations
INSERT INTO variation_combinations (id_product, price, stock)
VALUES
    (1, 700.00, 50),  -- Smartphone, Color: Black, Storage: 64GB
    (1, 800.00, 30),  -- Smartphone, Color: Black, Storage: 128GB
    (1, 750.00, 20),  -- Smartphone, Color: White, Storage: 64GB
    (1, 850.00, 10),  -- Smartphone, Color: White, Storage: 128GB
    (2, 15.00, 100),  -- T-Shirt, Size: Medium
    (2, 15.00, 80);   -- T-Shirt, Size: Large

-- Insert Combination Details
INSERT INTO combination_details (id_combination, id_option)
VALUES
    (1, 1),  -- Smartphone, Black color
    (1, 3),  -- Smartphone, 64GB storage
    (2, 1),  -- Smartphone, Black color
    (2, 4),  -- Smartphone, 128GB storage
    (3, 2),  -- Smartphone, White color
    (3, 3),  -- Smartphone, 64GB storage
    (4, 2),  -- Smartphone, White color
    (4, 4),  -- Smartphone, 128GB storage
    (5, 5),  -- T-Shirt, Medium size
    (6, 6);  -- T-Shirt, Large size

-- Insert Carousels
INSERT INTO carousels (name, image_url, link, title, subtitle, button_text, button_link)
VALUES
    ('Summer Sale', 'https://cdn.discordapp.com/attachments/1036284251865366648/1268922775335338064/image.png?ex=66ae2fed&is=66acde6d&hm=fc02309b77bb557860f2eb2663791037994fa2d2bd104221585f9cf5ef860917&', 'https://example.com/summer_sale', 'LAOS T-Shirt', 'Kaos Open Source', 'Beli Sekarang', 'product/2');

-- Insert Roles
INSERT INTO roles (role_name, role_description)
VALUES
    ('Admin', 'Administrator with full access rights.'),
    ('Customer', 'Regular customer with access to order and account management.'),
    ('Support', 'Support staff with access to customer service functionalities.');

-- Insert Permissions
INSERT INTO permissions (permission_name, permission_description)
VALUES
    ('VIEW_PRODUCTS', 'Allows viewing of products.'),
    ('EDIT_PRODUCTS', 'Allows editing of products.'),
    ('MANAGE_ORDERS', 'Allows managing of orders.'),
    ('MANAGE_USERS', 'Allows managing of users.');

-- Insert Role Permissions
INSERT INTO role_permissions (id_role, id_permission)
VALUES
    (1, 1),  -- Admin can VIEW_PRODUCTS
    (1, 2),  -- Admin can EDIT_PRODUCTS
    (1, 3),  -- Admin can MANAGE_ORDERS
    (1, 4),  -- Admin can MANAGE_USERS
    (2, 1),  -- Customer can VIEW_PRODUCTS
    (2, 3);  -- Customer can MANAGE_ORDERS

-- Insert Users
INSERT INTO users (id_firebase, username, password, first_name, last_name, profile_picture, email, wa_number, id_role, is_active)
VALUES
    ('ljiO9mSdnocXG1veOvRfW3g84wX2', 'noxindocraft', 'password123', 'Ahmad', 'Fauzan', '', 'noxindocraft@gmail.com', '+6283119624458', 1, TRUE);

-- Insert Tags
INSERT INTO tags (tag_name)
VALUES
    ('New Arrival'),
    ('Best Seller'),
    ('Discounted');

-- Insert Product Tags
INSERT INTO product_tags (id_product, id_tag)
VALUES
    (1, 1),  -- Smartphone tagged as New Arrival
    (2, 3);  -- T-Shirt tagged as Discounted

-- Insert Product Images
INSERT INTO product_images (id_product, image_url)
VALUES
    (1, 'https://images.pexels.com/photos/607812/pexels-photo-607812.jpeg?cs=srgb&dl=pexels-tracy-le-blanc-67789-607812.jpg&fm=jpg'),
    (2, 'https://media.discordapp.net/attachments/1036284251865366648/1268921522932944997/image.png?ex=66ae2ec2&is=66acdd42&hm=7167cecf399551eb8ef57385a4587cfd97f96a8a4134624771c7e8b79064198b&=&format=webp&quality=lossless');

-- Insert Reviews
INSERT INTO reviews (id_combination, id_user, rating, comment, anonymity)
VALUES
    (1, 1, 5, 'Excellent smartphone with amazing features!', FALSE);

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
    ('Percentage'),
    ('Fixed Amount');

-- Insert Discounts
INSERT INTO discounts (id_discount_type, discount_value, start_date, end_date)
VALUES
    (1, 10.00, NOW(), DATE_ADD(NOW(), INTERVAL 1 MONTH)),  -- 10% discount
    (2, 20.00, NOW(), DATE_ADD(NOW(), INTERVAL 1 MONTH));  -- $20 discount

-- Insert Discount Products
INSERT INTO discount_products (id_discount, id_product)
VALUES
    (1, 1);  -- 10% discount for Smartphone

-- Insert Shipping Addresses
INSERT INTO shipping_addresses (id_user, recipient_name, street_address, city, state, postal_code, extra_note, is_temporary)
VALUES
    (1, 'Alice Johnson', '123 Main St', 'Springfield', 'IL', '62701', 'Leave at front door', FALSE);

-- Insert Order Statuses
INSERT INTO order_statuses (status_name)
VALUES
    ('Pending'),
    ('Shipped'),
    ('Delivered'),
    ('Cancelled');

-- Insert Orders
INSERT INTO orders (id_user, total_price, id_status, id_shipping_address)
VALUES
    (1, 120.00, 1, 1);

-- Insert Order Items
INSERT INTO order_items (id_order, quantity, id_combination, id_discount)
VALUES
    (1, 1, 1, 1);  -- Order 1: Smartphone with 10% discount

-- Insert Carriers
INSERT INTO carriers (carrier_name, wa_number, email, website)
VALUES
    ('Fast Ship', '123-456-7890', 'support@fastship.com', 'http://fastship.com'),
    ('Quick Delivery', '098-765-4321', 'info@quickdelivery.com', 'http://quickdelivery.com');

-- Insert Shipments
INSERT INTO shipments (id_order, id_carrier, shipping_method, tracking_number, shipment_date, expected_delivery_date, status)
VALUES
    (1, 1, 'Standard Shipping', 'TRACK12345', NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY), 'Shipped');

-- Insert Shipment Statuses
INSERT INTO shipment_statuses (id_shipment, status_date, status_description)
VALUES
    (1, NOW(), 'Package is out for delivery.');

";

$db = new Database();

$db->query($query);
$db->execute();


echo "All tables values has created successfully.";