<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'laos_merch');

require_once dirname(dirname(__FILE__)) . "/app/core/Database.php";
require_once "helper.php";

$query = 

"
CREATE TABLE `roles` (
    `id_role` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `role_name` VARCHAR(100) UNIQUE NOT NULL,
    `role_description` TEXT
  );
  
  CREATE TABLE `permissions` (
    `id_permission` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `permission_name` VARCHAR(100) UNIQUE NOT NULL,
    `permission_description` TEXT
  );
  
  CREATE TABLE `role_permissions` (
    `id_role_permission` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `id_role` BIGINT,
    `id_permission` BIGINT
  );
  
  CREATE TABLE `users` (
    `id_user` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `id_firebase` VARCHAR(255) UNIQUE,
    `username` VARCHAR(255) UNIQUE NOT NULL,
    `first_name` VARCHAR(128) NOT NULL,
    `last_name` VARCHAR(128) NOT NULL,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `wa_number` VARCHAR(16) UNIQUE NOT NULL,
    `date_joined` TIMESTAMP DEFAULT (CURRENT_TIMESTAMP),
    `id_role` BIGINT,
    `is_active` BOOLEAN DEFAULT true
  );
  
  CREATE TABLE `categories` (
    `id_category` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `category_name` VARCHAR(255) NOT NULL,
    `category_description` TEXT
  );
  
  CREATE TABLE `products` (
    `id_product` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `id_category` BIGINT NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `weight` VARCHAR(150),
    `dimensions` VARCHAR(255),
    `date_added` TIMESTAMP DEFAULT (CURRENT_TIMESTAMP),
    `last_updated` TIMESTAMP DEFAULT (CURRENT_TIMESTAMP),
    `discontinued` BOOLEAN
  );
  
  CREATE TABLE `product_images` (
    `id_product_image` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `id_product` BIGINT NOT NULL,
    `image_url` VARCHAR(255)
  );
  
  CREATE TABLE `variation_types` (
    `id_variation_type` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `id_product` BIGINT NOT NULL,
    `name` VARCHAR(255) NOT NULL
  );
  
  CREATE TABLE `variation_combinations` (
    `id_combination` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `id_product` BIGINT NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `stock` INT NOT NULL
  );
  
  CREATE TABLE `variation_options` (
    `id_option` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `id_variation_type` BIGINT NOT NULL,
    `id_combination` BIGINT NOT NULL,
    `option_name` VARCHAR(255) NOT NULL,
    `image_url` VARCHAR(255)
  );
  
  CREATE TABLE `tags` (
    `id_tag` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `tag_name` VARCHAR(100) UNIQUE
  );
  
  CREATE TABLE `product_tags` (
    `id_product` BIGINT,
    `id_tag` BIGINT,
    PRIMARY KEY (`id_product`, `id_tag`)
  );
  
  CREATE TABLE `reviews` (
    `id_review` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `id_variation_combination` BIGINT NOT NULL,
    `id_user` BIGINT,
    `rating` INT,
    `comment` TEXT,
    `date_posted` TIMESTAMP DEFAULT (CURRENT_TIMESTAMP)
  );
  
  CREATE TABLE `review_images` (
    `id_review_image` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `id_review` BIGINT NOT NULL,
    `image_url` VARCHAR(255)
  );
  
  CREATE TABLE `cart_items` (
    `id_cart_item` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `id_user` BIGINT NOT NULL,
    `id_combination` BIGINT NOT NULL,
    `quantity` INT NOT NULL,
    `date_added` TIMESTAMP DEFAULT (CURRENT_TIMESTAMP),
    `last_updated` TIMESTAMP DEFAULT (CURRENT_TIMESTAMP)
  );
  
  CREATE TABLE `discount_types` (
    `id_discount_type` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `type_name` VARCHAR(50) NOT NULL
  );
  
  CREATE TABLE `discounts` (
    `id_discount` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `id_discount_type` BIGINT,
    `discount_value` DECIMAL(10,2),
    `start_date` TIMESTAMP DEFAULT (CURRENT_TIMESTAMP),
    `end_date` TIMESTAMP DEFAULT (CURRENT_TIMESTAMP)
  );
  
  CREATE TABLE `discount_variation_options` (
    `id_discount` BIGINT,
    `id_variation_option` BIGINT NOT NULL
  );
  
  CREATE TABLE `shipping_addresses` (
    `id_shipping_address` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `id_user` BIGINT,
    `recipient_name` VARCHAR(255),
    `street_address` VARCHAR(255),
    `city` VARCHAR(255),
    `state` VARCHAR(255),
    `postal_code` VARCHAR(50),
    `country` VARCHAR(255),
    `phone_number` VARCHAR(50),
    `extra_note` TEXT,
    `is_temporary` BOOLEAN DEFAULT false
  );
  
  CREATE TABLE `order_statuses` (
    `id_status` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `status_name` VARCHAR(50) UNIQUE NOT NULL
  );
  
  CREATE TABLE `orders` (
    `id_order` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `id_user` BIGINT,
    `order_date` TIMESTAMP DEFAULT (CURRENT_TIMESTAMP),
    `total_price` DECIMAL(10,2),
    `id_status` BIGINT,
    `id_shipping_address` BIGINT
  );
  
  CREATE TABLE `order_items` (
    `id_order_item` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `id_order` BIGINT,
    `quantity` INT,
    `id_variation_combination` BIGINT NOT NULL
  );
  
  CREATE TABLE `carriers` (
    `id_carrier` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `carrier_name` VARCHAR(255),
    `wa_number` VARCHAR(50),
    `email` VARCHAR(255),
    `website` VARCHAR(255)
  );
  
  CREATE TABLE `shipments` (
    `id_shipment` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `id_order` BIGINT,
    `id_carrier` BIGINT,
    `shipping_method` VARCHAR(255),
    `tracking_number` VARCHAR(255) UNIQUE,
    `shipment_date` TIMESTAMP DEFAULT (CURRENT_TIMESTAMP),
    `expected_delivery_date` DATE,
    `actual_delivery_date` DATE,
    `status` VARCHAR(50)
  );
  
  CREATE TABLE `shipment_statuses` (
    `id_shipment_status` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `id_shipment` BIGINT,
    `status_date` TIMESTAMP DEFAULT (CURRENT_TIMESTAMP),
    `status_description` VARCHAR(255)
  );
  
  ALTER TABLE `role_permissions` ADD FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `role_permissions` ADD FOREIGN KEY (`id_permission`) REFERENCES `permissions` (`id_permission`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `users` ADD FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_role`) ON DELETE RESTRICT ON UPDATE CASCADE;
  
  ALTER TABLE `products` ADD FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`) ON DELETE RESTRICT ON UPDATE CASCADE;
  
  ALTER TABLE `product_images` ADD FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `variation_types` ADD FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`);
  
  ALTER TABLE `variation_combinations` ADD FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `variation_options` ADD FOREIGN KEY (`id_combination`) REFERENCES `variation_combinations` (`id_combination`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `variation_options` ADD FOREIGN KEY (`id_variation_type`) REFERENCES `variation_types` (`id_variation_type`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `product_tags` ADD FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `product_tags` ADD FOREIGN KEY (`id_tag`) REFERENCES `tags` (`id_tag`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `reviews` ADD FOREIGN KEY (`id_variation_combination`) REFERENCES `variation_combinations` (`id_combination`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `reviews` ADD FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `review_images` ADD FOREIGN KEY (`id_review`) REFERENCES `reviews` (`id_review`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `cart_items` ADD FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `cart_items` ADD FOREIGN KEY (`id_combination`) REFERENCES `variation_combinations` (`id_combination`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `discounts` ADD FOREIGN KEY (`id_discount_type`) REFERENCES `discount_types` (`id_discount_type`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `discount_variation_options` ADD FOREIGN KEY (`id_variation_option`) REFERENCES `variation_options` (`id_combination`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `discount_variation_options` ADD FOREIGN KEY (`id_discount`) REFERENCES `discounts` (`id_discount`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `shipping_addresses` ADD FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `orders` ADD FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;
  
  ALTER TABLE `orders` ADD FOREIGN KEY (`id_shipping_address`) REFERENCES `shipping_addresses` (`id_shipping_address`) ON DELETE SET NULL ON UPDATE CASCADE;
  
  ALTER TABLE `orders` ADD FOREIGN KEY (`id_status`) REFERENCES `order_statuses` (`id_status`) ON DELETE RESTRICT ON UPDATE CASCADE;
  
  ALTER TABLE `order_items` ADD FOREIGN KEY (`id_variation_combination`) REFERENCES `variation_combinations` (`id_combination`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `order_items` ADD FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `shipments` ADD FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE `shipments` ADD FOREIGN KEY (`id_carrier`) REFERENCES `carriers` (`id_carrier`) ON DELETE SET NULL ON UPDATE CASCADE;
  
  ALTER TABLE `shipment_statuses` ADD FOREIGN KEY (`id_shipment`) REFERENCES `shipments` (`id_shipment`) ON DELETE CASCADE ON UPDATE CASCADE;
  

";

$db = new Database();


$db->query($query);
$db->execute();
echo "Table created successfully.\n";


echo "All tables created successfully.";