<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'laos_merch');

require_once dirname(dirname(__FILE__)) . "/app/core/Database.php";
require_once "helper.php";

$query = 

"
-- Role
CREATE TABLE roles (
    id_role BIGINT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(100) UNIQUE NOT NULL,
    role_description TEXT
);


-- Hak Akses
CREATE TABLE permissions (
    id_permission BIGINT PRIMARY KEY AUTO_INCREMENT,
    permission_name VARCHAR(100) UNIQUE NOT NULL,
    permission_description TEXT
);


-- Hak Akses Role (tabel penengah)
CREATE TABLE role_permissions (
    id_role_permission BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_role BIGINT,
    id_permission BIGINT,
    FOREIGN KEY (id_role) REFERENCES roles(id_role) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_permission) REFERENCES permissions(id_permission) ON UPDATE CASCADE ON DELETE CASCADE
);


-- pengguna
CREATE TABLE users (
    id_user BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_google VARCHAR(255) UNIQUE,
    user_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    wa_number VARCHAR(16) UNIQUE NOT NULL,
    date_joined TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_role BIGINT,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_role) REFERENCES roles(id_role) ON UPDATE CASCADE ON DELETE RESTRICT
);


-- ketegori produk
CREATE TABLE categories (
    id_category BIGINT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(255) NOT NULL,
    category_description TEXT
);


-- produk
CREATE TABLE products (
    id_product BIGINT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(255) NOT NULL,
    description TEXT,
    id_category BIGINT NULL,
    weight VARCHAR(150),
    dimensions VARCHAR(255),
    image_url VARCHAR(255),
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    discontinued BOOLEAN,
    FOREIGN KEY (id_category) REFERENCES categories(id_category) ON UPDATE CASCADE ON DELETE SET NULL 
);


-- Variasi produk
CREATE TABLE variations (
    id_variation BIGINT PRIMARY KEY,
    id_product BIGINT NOT NULL,
    variation_name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255),
    FOREIGN KEY (id_product) REFERENCES products(id_product)
);

-- table opsi variasi produk dengan recursive relationship
CREATE TABLE variation_options (
    id_option BIGINT PRIMARY KEY,
    id_variation BIGINT NOT NULL,
    option_name VARCHAR(255) NOT NULL,
    id_parent_option BIGINT, -- Points to the parent option
    FOREIGN KEY (id_variation) REFERENCES variations(id_variation),
    FOREIGN KEY (id_parent_option) REFERENCES variation_options(id_option)
);

CREATE TABLE tags (
    id_tag BIGINT PRIMARY KEY AUTO_INCREMENT,
    tag_name VARCHAR(100) UNIQUE
);

CREATE TABLE product_tags (
    id_product BIGINT,
    id_tag BIGINT,
    PRIMARY KEY (id_product, id_tag),
    FOREIGN KEY (id_product) REFERENCES products(id_product) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_tag) REFERENCES tags(id_tag) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE reviews (
    id_review BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_variation BIGINT,
    id_user BIGINT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    date_posted TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_variation) REFERENCES variations(id_variation) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON UPDATE CASCADE ON DELETE CASCADE 
);

-- Create cart_items table
CREATE TABLE cart_items (
    id_cart_item BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_user BIGINT NOT NULL,
    id_variation BIGINT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    total_price DECIMAL(10, 2) AS (quantity * unit_price) STORED,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_variation) REFERENCES variations(id_variation) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE discount_types (
    id_discount_type BIGINT PRIMARY KEY AUTO_INCREMENT,
    type_name VARCHAR(50) NOT NULL
);

CREATE TABLE discounts (
    id_discount BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_discount_type BIGINT,
    discount_value DECIMAL(10, 2),
    start_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    end_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_discount_type) REFERENCES discount_types(id_discount_type) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE discount_variations (
    id_discount BIGINT,
    id_variation BIGINT,
    FOREIGN KEY (id_discount) REFERENCES discounts(id_discount) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_variation) REFERENCES variations(id_variation) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE shipping_addresses (
    id_shipping_address BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_user BIGINT NULL,
    recipient_name VARCHAR(255),
    street_address VARCHAR(255),
    city VARCHAR(255),
    state VARCHAR(255),
    postal_code VARCHAR(50),
    country VARCHAR(255),
    phone_number VARCHAR(50),
    extra_note TEXT,
    is_temporary BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE order_statuses (
    id_status BIGINT PRIMARY KEY AUTO_INCREMENT,
    status_name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE orders (
    id_order BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_user BIGINT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10, 2),
    id_status BIGINT,
    id_shipping_address BIGINT,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (id_shipping_address) REFERENCES shipping_addresses(id_shipping_address) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (id_status) REFERENCES order_statuses(id_status) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE order_items (
    id_order_item BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_order BIGINT,
    id_variation BIGINT,
    quantity INT,
    unit_price DECIMAL(10, 2),
    total_price DECIMAL(10, 2),
    FOREIGN KEY (id_order) REFERENCES orders(id_order) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_variation) REFERENCES variations(id_variation)ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE carriers (
    id_carrier BIGINT PRIMARY KEY AUTO_INCREMENT,
    carrier_name VARCHAR(255),
    contact_number VARCHAR(50),
    email VARCHAR(255),
    website VARCHAR(255)
);

CREATE TABLE shipments (
    id_shipment BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_order BIGINT,
    id_carrier BIGINT,
    shipping_method VARCHAR(255),
    tracking_number VARCHAR(255) UNIQUE,
    shipment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expected_delivery_date DATE,
    actual_delivery_date DATE,
    status VARCHAR(50),
    FOREIGN KEY (id_order) REFERENCES orders(id_order) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_carrier) REFERENCES carriers(id_carrier) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE shipment_statuses (
    id_shipment_status BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_shipment BIGINT,
    status_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status_description VARCHAR(255),
    FOREIGN KEY (id_shipment) REFERENCES shipments(id_shipment) ON UPDATE CASCADE ON DELETE CASCADE
);


";

$db = new Database();


$db->query($query);
$db->execute();
echo "Table created successfully.\n";


echo "All tables created successfully.";