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
INSERT INTO users (id_firebase, user_name, email, wa_number, id_role, is_active)
VALUES
    ('firebase_id_1', 'Alice Johnson', 'alice@example.com', '1234567890', 1, TRUE),
    ('firebase_id_2', 'Bob Smith', 'bob@example.com', '0987654321', 2, TRUE);

-- Insert Categories
INSERT INTO categories (category_name, category_description)
VALUES
    ('Electronics', 'Electronic devices and accessories.'),
    ('Accessories', 'Various accessories for electronic devices.');

-- Insert Products
INSERT INTO products (id_category, product_name, description, weight, dimensions, date_added, last_updated, discontinued)
VALUES
    (1, 'Mechanical Keyboard', 'A high-quality mechanical keyboard with backlighting.', '1.5 kg', '45x15x5 cm', NOW(), NOW(), FALSE),
    (2, 'Gaming Mouse', 'A precision gaming mouse with adjustable DPI.', '0.2 kg', '10x6x4 cm', NOW(), NOW(), FALSE);

-- Insert Product Images
INSERT INTO product_images (id_product, image_url)
VALUES
    (1, 'http://example.com/images/keyboard_main.png'),
    (2, 'http://example.com/images/mouse_main.png');

-- Insert Variation Types
INSERT INTO variation_types (id_product, name)
VALUES
    (1, 'Keyboard Type'),
    (1, 'Keyboard Color'),
    (2, 'Mouse Type'),
    (2, 'Mouse Color');

-- Insert Variation Combinations
INSERT INTO variation_combinations (id_product, price, stock)
VALUES
    (1, 120.00, 50),  -- Mechanical Keyboard, Basic
    (1, 140.00, 30),  -- Mechanical Keyboard, Pro
    (2, 40.00, 100),  -- Gaming Mouse, Standard
    (2, 60.00, 75);   -- Gaming Mouse, Advanced

-- Insert Variation Options
INSERT INTO variation_options (id_variation_type, id_combination, option_name, image_url)
VALUES
    (1, 1, 'RGB Backlight', 'http://example.com/images/rgb_backlight.png'),
    (1, 2, 'Single Color', 'http://example.com/images/single_color.png'),
    (2, 1, 'Black', 'http://example.com/images/black.png'),
    (2, 2, 'White', 'http://example.com/images/white.png'),
    (3, 1, 'Basic', 'http://example.com/images/basic.png'),
    (3, 2, 'Ergonomic', 'http://example.com/images/ergonomic.png'),
    (4, 1, 'Red', 'http://example.com/images/red.png'),
    (4, 2, 'Blue', 'http://example.com/images/blue.png');

-- Insert Tags
INSERT INTO tags (tag_name)
VALUES
    ('New Arrival'),
    ('Best Seller'),
    ('Discounted');

-- Insert Product Tags
INSERT INTO product_tags (id_product, id_tag)
VALUES
    (1, 1),  -- Mechanical Keyboard tagged as New Arrival
    (1, 2),  -- Mechanical Keyboard tagged as Best Seller
    (2, 3);  -- Gaming Mouse tagged as Discounted

-- Insert Reviews
INSERT INTO reviews (id_variation_combination, id_user, rating, comment, date_posted)
VALUES
    (1, 1, 5, 'Excellent keyboard with great features!', NOW()),
    (2, 2, 4, 'Good quality, but a bit pricey.', NOW());

-- Insert Review Images
INSERT INTO review_images (id_review, image_url)
VALUES
    (1, 'http://example.com/images/review1.png'),
    (2, 'http://example.com/images/review2.png');

-- Insert Cart Items
INSERT INTO cart_items (id_user, id_combination, quantity, date_added, last_updated)
VALUES
    (1, 1, 2, NOW(), NOW()),
    (2, 3, 1, NOW(), NOW());

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

-- Insert Discount Variation Options
INSERT INTO discount_variation_options (id_discount, id_variation_option)
VALUES
    (1, 1),  -- 10% discount on RGB Backlight option
    (2, 3);  -- $20 discount on Basic Mouse option

-- Insert Shipping Addresses
INSERT INTO shipping_addresses (id_user, recipient_name, street_address, city, state, postal_code, country, phone_number, extra_note, is_temporary)
VALUES
    (1, 'Alice Johnson', '123 Main St', 'Springfield', 'IL', '62701', 'USA', '123-456-7890', 'Leave at front door', FALSE),
    (2, 'Bob Smith', '456 Oak St', 'Metropolis', 'NY', '10001', 'USA', '987-654-3210', NULL, TRUE);

-- Insert Order Statuses
INSERT INTO order_statuses (status_name)
VALUES
    ('Pending'),
    ('Shipped'),
    ('Delivered'),
    ('Cancelled');

-- Insert Orders
INSERT INTO orders (id_user, order_date, total_price, id_status, id_shipping_address)
VALUES
    (1, NOW(), 120.00, 1, 1),
    (2, NOW(), 60.00, 2, 2);

-- Insert Order Items
INSERT INTO order_items (id_order, quantity, id_variation_combination)
VALUES
    (1, 1, 1),  -- Order 1: Mechanical Keyboard, Basic
    (2, 1, 3);  -- Order 2: Gaming Mouse, Standard

-- Insert Carriers
INSERT INTO carriers (carrier_name, wa_number, email, website)
VALUES
    ('Fast Ship', '123-456-7890', 'support@fastship.com', 'http://fastship.com'),
    ('Quick Delivery', '098-765-4321', 'info@quickdelivery.com', 'http://quickdelivery.com');

-- Insert Shipments
INSERT INTO shipments (id_order, id_carrier, shipping_method, tracking_number, shipment_date, expected_delivery_date, actual_delivery_date, status)
VALUES
    (1, 1, 'Standard Shipping', 'TRACK12345', NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY), NULL, 'Shipped'),
    (2, 2, 'Express Shipping', 'TRACK67890', NOW(), DATE_ADD(NOW(), INTERVAL 2 DAY), NULL, 'Shipped');

-- Insert Shipment Statuses
INSERT INTO shipment_statuses (id_shipment, status_date, status_description)
VALUES
    (1, NOW(), 'Package is out for delivery.'),
    (2, NOW(), 'Package has been dispatched.');




";

$db = new Database();

$db->query($query);
$db->execute();


echo "All tables values has created successfully.";