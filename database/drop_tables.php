<?php

require_once dirname(dirname(__FILE__)) . "/app/config/Config.php";
require_once dirname(dirname(__FILE__)) . "/app/core/Database.php";
require_once "helper.php";


$query = 
"

-- Shipment status table
DROP TABLE IF EXISTS shipment_status;

-- Shipments table
DROP TABLE IF EXISTS shipments;

-- Carriers table
DROP TABLE IF EXISTS carriers;

-- Order items table
DROP TABLE IF EXISTS order_items;

-- Orders table
DROP TABLE IF EXISTS orders;

-- Order statuses table
DROP TABLE IF EXISTS order_statuses;

-- Shipping addresses table
DROP TABLE IF EXISTS shipping_addresses;

-- Discount types table
DROP TABLE IF EXISTS discount_type;

-- Junction table for discounts and product variations
DROP TABLE IF EXISTS discount_product_variation;

-- Discounts table
DROP TABLE IF EXISTS discounts;

-- Reviews table
DROP TABLE IF EXISTS reviews;

-- Junction table for products and tags
DROP TABLE IF EXISTS product_tags;

-- Tags table
DROP TABLE IF EXISTS tags;

-- Product variations table
DROP TABLE IF EXISTS product_variation;

-- Products table
DROP TABLE IF EXISTS products;

-- Categories table
DROP TABLE IF EXISTS categories;

-- Users table
DROP TABLE IF EXISTS users;

-- Junction table for roles and permissions
DROP TABLE IF EXISTS role_permissions;

-- Permissions table
DROP TABLE IF EXISTS permissions;

-- Roles table
DROP TABLE IF EXISTS roles;


";

$db = new Database();

$db->query($query);
$db->execute();


echo "All tables has dropped successfully.";