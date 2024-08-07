<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'laos_merch');
require_once dirname(dirname(__FILE__)) . "/app/core/Database.php";


$query = 
"
-- Drop tables in the correct order to avoid foreign key constraint issues

DROP TABLE IF EXISTS shipment_statuses;
DROP TABLE IF EXISTS shipments;
DROP TABLE IF EXISTS carriers;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS shipping_addresses;
DROP TABLE IF EXISTS discount_products;
DROP TABLE IF EXISTS discounts;
DROP TABLE IF EXISTS discount_types;
DROP TABLE IF EXISTS cart_items;
DROP TABLE IF EXISTS review_images;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS product_images;
DROP TABLE IF EXISTS product_tags;
DROP TABLE IF EXISTS tags;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS role_permissions;
DROP TABLE IF EXISTS permissions;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS carousels;
DROP TABLE IF EXISTS combination_details;
DROP TABLE IF EXISTS variation_combinations;
DROP TABLE IF EXISTS variation_options;
DROP TABLE IF EXISTS variation_types;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;


";

$db = new Database();

$db->query($query);
$db->execute();

$db = new Database();

$db->query($query);
$db->execute();


echo "All tables has dropped successfully.";