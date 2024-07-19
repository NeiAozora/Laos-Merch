<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'laos_merch');
require_once dirname(dirname(__FILE__)) . "/app/core/Database.php";
require_once "helper.php";


$query = 
"

-- Sample data insertion

-- Roles
INSERT INTO roles (id_role, role_name, role_description)
VALUES (1, 'Super Admin', 'Has all privileges'),
       (2, 'Admin', 'Has limited privileges'),
       (3, 'Regular User', 'Only has buyer privileges');

-- Permissions
INSERT INTO permissions (permission_name, permission_description)
VALUES ('manage_users', 'Can manage user accounts'),
       ('manage_products', 'Can manage products'),
       ('place_orders', 'Can place orders'),
       ('manage_orders', 'Can manage orders');

-- Inserting role permissions
INSERT INTO role_permissions (id_role, id_permission) VALUES
(1, 1),
(1, 2),
(2, 1);

-- Inserting users
INSERT INTO users (id_google, user_name, email, wa_number, id_role) VALUES
('google123', 'John Doe', 'john.doe@example.com', '1234567890', 1),
('google456', 'Jane Smith', 'jane.smith@example.com', '0987654321', 2);

-- Inserting categories
INSERT INTO categories (category_name, category_description) VALUES
('Electronics', 'Electronic gadgets and devices'),
('Books', 'Various kinds of books');

-- Inserting products
INSERT INTO products (product_name, description, id_category, weight, dimensions, image_url, discontinued) VALUES
('Smartphone', 'Latest model smartphone', 1, '200g', '150x70x8mm', 'http://example.com/smartphone.jpg', FALSE),
('Novel', 'Bestselling novel', 2, '500g', '200x130x30mm', 'http://example.com/novel.jpg', FALSE);

-- Inserting product variations
INSERT INTO variations (id_product, variation_name, price, quantity_in_stock) VALUES
(1, '64GB Black', 699.99, 50),
(1, '128GB Silver', 799.99, 30),
(2, 'Paperback', 19.99, 100),
(2, 'Hardcover', 29.99, 50);

-- Inserting tags
INSERT INTO tags (tag_name) VALUES
('New Arrival'),
('Bestseller');

-- Inserting product tags
INSERT INTO product_tags (id_product, id_tag) VALUES
(1, 1),
(2, 2);

-- Inserting reviews
INSERT INTO reviews (id_variation, id_user, rating, comment) VALUES
(1, 1, 5, 'Excellent product!'),
(3, 2, 4, 'Great read, but a bit pricey.');

-- Inserting discount types
INSERT INTO discount_type (type_name)
VALUES ('Percentage Off'), ('Fixed Amount Off'), ('Free Shipping');

-- Inserting discounts
INSERT INTO discounts (id_discount_type, discount_value, start_date, end_date) VALUES
(1, 10.00, '2024-01-01 00:00:00', '2024-12-31 23:59:59'),
(2, 5.00, '2024-06-01 00:00:00', '2024-06-30 23:59:59');

-- Inserting discount product variations
INSERT INTO discount_variations (id_discount, id_variation) VALUES
(1, 1),
(2, 3);

-- Inserting shipping addresses
INSERT INTO shipping_addresses (id_user, recipient_name, street_address, city, state, postal_code, country, phone_number, extra_note) VALUES
(1, 'John Doe', '123 Elm Street', 'Springfield', 'IL', '62704', 'USA', '1234567890', 'Leave at front door'),
(2, 'Jane Smith', '456 Oak Street', 'Shelbyville', 'IL', '62565', 'USA', '0987654321', 'Call on arrival');

-- Inserting order statuses
INSERT INTO order_statuses (id_status, status_name)
VALUES (1, 'pending'), 
       (2, 'processing'), 
       (3, 'shipped'), 
       (4, 'delivered'), 
       (5, 'cancelled');

-- Inserting orders
INSERT INTO orders (id_user, total_amount, id_status, id_shipping_address) VALUES
(1, 699.99, 1, 1),
(2, 49.98, 3, 2);

-- Inserting order items
INSERT INTO order_items (id_order, id_variation, quantity, unit_price, total_price) VALUES
(1, 1, 1, 699.99, 699.99),
(2, 3, 2, 19.99, 39.98),
(2, 4, 1, 29.99, 29.99);

-- Inserting carriers
INSERT INTO carriers (carrier_name, contact_number, email, website) VALUES
('FastShip', '1234567890', 'support@fastship.com', 'http://fastship.com'),
('SpeedyDelivery', '0987654321', 'contact@speedydelivery.com', 'http://speedydelivery.com');

-- Inserting shipments
INSERT INTO shipments (id_order, id_carrier, shipping_method, tracking_number, expected_delivery_date, actual_delivery_date, status) VALUES
(1, 1, 'Standard', 'TRACK123456', '2024-07-20', '2024-07-18', 'Delivered'),
(2, 2, 'Express', 'TRACK654321', '2024-07-21', NULL, 'In Transit');
z
-- Shipment status (sample data)
INSERT INTO shipment_status (id_shipment, status_description)
VALUES (1, 'Order shipped on time and in good condition.'),
       (2, 'Order pending for carrier pickup.');

";

$db = new Database();

$db->query($query);
$db->execute();


echo "All tables values has created successfully.";