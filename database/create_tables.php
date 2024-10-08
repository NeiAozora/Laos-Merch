<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'laos_merch');

require_once dirname(dirname(__FILE__)) . "/app/core/Database.php";
require_once "helper.php";

$query = 

"
CREATE TABLE carousels (
  id_carousel INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  image_url VARCHAR(255) NOT NULL,
  link VARCHAR(255),
  title VARCHAR(255),
  subtitle VARCHAR(255),
  button_text VARCHAR(255),
  button_link VARCHAR(255)
);

CREATE TABLE roles (
  id_role BIGINT PRIMARY KEY AUTO_INCREMENT,
  role_name VARCHAR(100) UNIQUE NOT NULL,
  role_description TEXT
);

CREATE TABLE permissions (
  id_permission BIGINT PRIMARY KEY AUTO_INCREMENT,
  permission_name VARCHAR(100) UNIQUE NOT NULL,
  permission_description TEXT
);

CREATE TABLE role_permissions (
  id_role_permission BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_role BIGINT,
  id_permission BIGINT,
  FOREIGN KEY (id_role) REFERENCES roles(id_role) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (id_permission) REFERENCES permissions(id_permission) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE users (
  id_user BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_firebase VARCHAR(255) UNIQUE,
  username VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  first_name VARCHAR(128) NOT NULL,
  last_name VARCHAR(128) NOT NULL,
  profile_picture VARCHAR(255) DEFAULT '',
  email VARCHAR(255) UNIQUE NOT NULL,
  wa_number VARCHAR(16) UNIQUE NOT NULL,
  date_joined TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  id_role BIGINT,
  is_active BOOLEAN DEFAULT TRUE,
  FOREIGN KEY (id_role) REFERENCES roles(id_role) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE temp_verifications (
  id_temp_verification INT AUTO_INCREMENT PRIMARY KEY,
  id_user BIGINT NOT NULL,
  code VARCHAR(6) NOT NULL,
  temp_token VARCHAR(255) NOT NULL,
  access_count INT DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  expires_at DATETIME NOT NULL,
  FOREIGN KEY (id_user) REFERENCES users(id_user) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE categories (
  id_category BIGINT PRIMARY KEY AUTO_INCREMENT,
  category_name VARCHAR(255) NOT NULL,
  category_description TEXT
);

CREATE TABLE products (
  id_product BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_category BIGINT NOT NULL,
  product_name VARCHAR(255) NOT NULL,
  description TEXT,
  weight VARCHAR(150),
  dimensions VARCHAR(255),
  date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  discontinued BOOLEAN,
  FOREIGN KEY (id_category) REFERENCES categories(id_category) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE product_images (
  id_product_image BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_product BIGINT NOT NULL,
  image_url VARCHAR(255) NULL,
  FOREIGN KEY (id_product) REFERENCES products(id_product) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE variation_types (
  id_variation_type BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_product BIGINT NOT NULL,
  name VARCHAR(255) NOT NULL,
  FOREIGN KEY (id_product) REFERENCES products(id_product) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE variation_combinations (
  id_combination BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_product BIGINT NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  stock INT NOT NULL,
  FOREIGN KEY (id_product) REFERENCES products(id_product) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE variation_options (
  id_option BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_variation_type BIGINT NOT NULL,
  option_name VARCHAR(255) NOT NULL,
  image_url VARCHAR(255),
  FOREIGN KEY (id_variation_type) REFERENCES variation_types(id_variation_type) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE combination_details (
  id_combination_detail BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_combination BIGINT NOT NULL,
  id_option BIGINT NOT NULL,
  FOREIGN KEY (id_combination) REFERENCES variation_combinations(id_combination) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (id_option) REFERENCES variation_options(id_option) ON UPDATE CASCADE ON DELETE CASCADE
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


CREATE TABLE cart_items (
  id_cart_item BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_user BIGINT NOT NULL,
  id_combination BIGINT NOT NULL,
  quantity INT NOT NULL,
  date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_user) REFERENCES users(id_user) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (id_combination) REFERENCES variation_combinations(id_combination) ON UPDATE CASCADE ON DELETE CASCADE
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

CREATE TABLE discount_products (
  id_discount BIGINT,
  id_product BIGINT NOT NULL,
  FOREIGN KEY (id_product) REFERENCES products(id_product) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (id_discount) REFERENCES discounts(id_discount) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE shipping_addresses (
  id_shipping_address BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_user BIGINT NULL,
  label_name VARCHAR(255),
  street_address VARCHAR(255),
  city VARCHAR(255),
  state VARCHAR(255),
  postal_code VARCHAR(50),
  extra_note TEXT,
  is_prioritize BOOLEAN DEFAULT FALSE,
  is_temporary BOOLEAN DEFAULT FALSE,
  FOREIGN KEY (id_user) REFERENCES users(id_user) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE order_statuses (
  id_status BIGINT PRIMARY KEY AUTO_INCREMENT,
  status_name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE payment_methods (
  id_payment_method BIGINT PRIMARY KEY AUTO_INCREMENT,
  method_name VARCHAR(50) NOT NULL
);

CREATE TABLE orders (
  id_order BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_user BIGINT NULL,
  order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  total_price DECIMAL(10, 2),
  id_status BIGINT,
  id_shipping_address BIGINT,
  shipping_fee DECIMAL(10, 2),
  service_fee DECIMAL(10, 2),
  handling_fee DECIMAL(10, 2),
  id_payment_method BIGINT, -- Foreign key to payment methods table
  FOREIGN KEY (id_user) REFERENCES users(id_user) ON UPDATE CASCADE ON DELETE SET NULL,
  FOREIGN KEY (id_shipping_address) REFERENCES shipping_addresses(id_shipping_address) ON UPDATE CASCADE ON DELETE SET NULL,
  FOREIGN KEY (id_status) REFERENCES order_statuses(id_status) ON UPDATE CASCADE ON DELETE RESTRICT,
  FOREIGN KEY (id_payment_method) REFERENCES payment_methods(id_payment_method) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE order_items (
  id_order_item BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_order BIGINT,
  quantity INT,
  id_combination BIGINT NOT NULL,
  discount_value DECIMAL(10, 2),
  price DECIMAL(10, 2) NOT NULL, -- Price for the individual item
  FOREIGN KEY (id_combination) REFERENCES variation_combinations(id_combination) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (id_order) REFERENCES orders(id_order) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE shipment_companies (
  id_shipment_company BIGINT AUTO_INCREMENT PRIMARY KEY,
  company_name VARCHAR(255) NOT NULL,
  company_email VARCHAR(255),
  company_website VARCHAR(255)
);

CREATE TABLE carriers (
  id_carrier BIGINT AUTO_INCREMENT PRIMARY KEY,
  carrier_name VARCHAR(255) NOT NULL,
  wa_number VARCHAR(20),
  email VARCHAR(255),
  id_shipment_company BIGINT NOT NULL,
  FOREIGN KEY (id_shipment_company) REFERENCES shipment_companies(id_shipment_company)
);

CREATE TABLE shipments (
  id_shipment BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_order BIGINT,
  id_shipment_company BIGINT,
  id_carrier BIGINT, -- Optional, for detailed carrier info
  tracking_number VARCHAR(255) UNIQUE,
  shipment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  expected_delivery_date DATE,
  actual_delivery_date DATE,
  FOREIGN KEY (id_order) REFERENCES orders(id_order) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (id_shipment_company) REFERENCES shipment_companies(id_shipment_company) ON UPDATE CASCADE ON DELETE SET NULL,
  FOREIGN KEY (id_carrier) REFERENCES carriers(id_carrier) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE shipment_statuses (
  id_status BIGINT AUTO_INCREMENT PRIMARY KEY,
  status_name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE shipment_details (
  id_detail BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_shipment BIGINT,
  id_status BIGINT,
  detail_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  detail_description VARCHAR(255),
  FOREIGN KEY (id_shipment) REFERENCES shipments(id_shipment) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (id_status) REFERENCES shipment_statuses(id_status) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE reviews (
  id_review BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_combination BIGINT NOT NULL,
  id_user BIGINT,
  id_order_item BIGINT,
  rating INT CHECK (rating BETWEEN 1 AND 5),
  comment TEXT,
  anonymity BOOLEAN DEFAULT FALSE,
  date_posted TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_combination) REFERENCES variation_combinations(id_combination) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (id_user) REFERENCES users(id_user) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (id_order_item) REFERENCES order_items(id_order_item) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE review_images (
  id_review_image BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_review BIGINT NOT NULL,
  image_url VARCHAR(255),
  FOREIGN KEY (id_review) REFERENCES reviews(id_review) ON UPDATE CASCADE ON DELETE CASCADE
);

";

$db = new Database();


$db->query($query);
$db->execute();


echo "All tables created successfully.";