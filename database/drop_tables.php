<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'laos_merch');
require_once dirname(dirname(__FILE__)) . "/app/core/Database.php";


$query = 
"
-- Drop Review Images Table
DROP TABLE IF EXISTS review_images;

-- Drop Reviews Table
DROP TABLE IF EXISTS reviews;

-- Drop Product Tags Table
DROP TABLE IF EXISTS product_tags;

-- Drop Tags Table
DROP TABLE IF EXISTS tags;

-- Drop Variation Options Table
DROP TABLE IF EXISTS variation_options;

-- Drop Variation Combinations Table
DROP TABLE IF EXISTS variation_combinations;

-- Drop Variation Types Table
DROP TABLE IF EXISTS variation_types;

-- Drop Product Images Table
DROP TABLE IF EXISTS product_images;

-- Drop Products Table
DROP TABLE IF EXISTS products;

-- Drop Categories Table
DROP TABLE IF EXISTS categories;

-- Drop Users Table
DROP TABLE IF EXISTS users;

-- Drop Role Permissions Table
DROP TABLE IF EXISTS role_permissions;

-- Drop Permissions Table
DROP TABLE IF EXISTS permissions;

-- Drop Roles Table
DROP TABLE IF EXISTS roles;

-- Drop Cart Items Table
DROP TABLE IF EXISTS cart_items;

-- Drop Discount Variation Options Table
DROP TABLE IF EXISTS discount_variation_options;

-- Drop Discounts Table
DROP TABLE IF EXISTS discounts;

-- Drop Discount Types Table
DROP TABLE IF EXISTS discount_types;

-- Drop Shipping Addresses Table
DROP TABLE IF EXISTS shipping_addresses;

-- Drop Order Items Table
DROP TABLE IF EXISTS order_items;

-- Drop Orders Table
DROP TABLE IF EXISTS orders;

-- Drop Shipment Statuses Table
DROP TABLE IF EXISTS shipment_statuses;

-- Drop Shipments Table
DROP TABLE IF EXISTS shipments;

-- Drop Carriers Table
DROP TABLE IF EXISTS carriers;

-- Drop Order Statuses Table
DROP TABLE IF EXISTS order_statuses;



";

$db = new Database();

$db->query($query);
$db->execute();


echo "All tables has dropped successfully.";