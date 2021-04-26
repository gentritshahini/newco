CREATE DATABASE IF NOT EXISTS `newco`;
USE `newco`;

CREATE TABLE IF NOT EXISTS `services` (
    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
    `description` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    `price` float NOT NULL,
    `active` tinyint(1) NOT NULL DEFAULT '0',    
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `products` (
    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    `description` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    `validity` datetime NOT NULL,
    `state` tinyint(1) NOT NULL DEFAULT '0',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `product_services` (
    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
    `product_id` int(6) unsigned NOT NULL,
    `service_id` int(6) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    KEY `product_id` (`product_id`),
    KEY `service_id` (`service_id`),
    CONSTRAINT `product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `service_id` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `customers` (
    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    `surename` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    `address` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `phone_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `locations` (
    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
    `city` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `shops` (
    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    `location_id` int(6) unsigned NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `shop_location` (`location_id`),
    CONSTRAINT `shop_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `assistants` (
    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    `surename` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    `shop_id` int(6) unsigned NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `shop_assitant` (`shop_id`),
    CONSTRAINT `shop_assitant` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `orders` (
    `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
    `product_id` int(6) unsigned NOT NULL,
    `customer_id` int(6) unsigned NOT NULL,
    `assistant_id` int(6) unsigned NOT NULL,
    `price` float NOT NULL,
    `amount` int(6) unsigned NOT NULL DEFAULT 1,
    `total` float NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `order_product` (`product_id`),
    KEY `order_customer` (`customer_id`),
    KEY `order_assistant` (`assistant_id`),
    CONSTRAINT `order_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `order_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `order_assistant` FOREIGN KEY (`assistant_id`) REFERENCES `assistants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO `customers` (`name`, `surename`, `address`, `phone_number`) VALUES ('John', 'Doe', 'Nene Tereza', '+38344123456');

INSERT INTO `locations` (`city`) VALUES ('Prishtine'), ('Gjakove'), ('Peje');

INSERT INTO `shops` (`name`, `location_id`) VALUES ('Shop 1', 1), ('Shop 2', 2), ('Shop 3', 3);

INSERT INTO `assistants` (`name`, `surename`, `shop_id`) VALUES ('John', 'Doe', 1), ('John', 'Doe', 2), ('John', 'Doe', 3);

INSERT INTO `products` (`id`, `name`, `description`, `validity`, `state`) VALUES
(1, 'Product 1', 'product desc 1', '2022-06-01 00:00:00', 1),
(2, 'Product 2', 'product desc 2', '2022-06-01 00:00:00', 1),
(3, 'Product 3', 'product desc 3', '2022-06-01 00:00:00', 1);

INSERT INTO `services` (`id`, `description`, `price`, `active`) VALUES
(1, 'Service 1', 19.99, 1),
(2, 'Service 2', 22.22, 1),
(3, 'Service 3', 9.99, 1);

INSERT INTO `product_services` (`product_id`, `service_id`) VALUES
(1, 1),
(2, 3),
(3, 2),
(2, 2),
(3, 3);

INSERT INTO `orders` (`product_id`, `customer_id`, `assistant_id`, `price`, `amount`, `total`) VALUES (1, 1, 1, 19.99, 1, 19.99);

-- Stored Procedures

-- Create Shop
DELIMITER //

CREATE PROCEDURE createShop(
    IN name varchar(30), 
    IN location_id int(6)
	 )
BEGIN
	INSERT INTO `shops` (`name`, `location_id`) VALUES (name, location_id);
END //

DELIMITER ;

CALL createShop('Shop 2', 2);

-- Add new Shop assistant 
DELIMITER //

CREATE PROCEDURE createShopAssistant(
    IN name varchar(30), 
    IN surename varchar(30), 
    IN shop_id int(6)
	 )
BEGIN
	INSERT INTO `assistants` (`name`, `surename`, `shop_id`) VALUES (name, surename, shop_id);
END //

DELIMITER ;

CALL createShopAssistant('Gentrit', 'Shahini', 1);

-- Add new Service
DELIMITER //

CREATE PROCEDURE createService(
    IN description varchar(30), 
    IN price float, 
    IN active boolean
	 )
BEGIN
	INSERT INTO `services` (`description`, `price`, `active`) VALUES (description, price, active);
END //

DELIMITER ;

CALL createService('Service procedure', 19.99, 1);

-- Enable/Disable Service 
DELIMITER //

CREATE PROCEDURE updateServiceStatus(
    IN service int(6),
    IN param boolean
	 )
BEGIN
    UPDATE `services` SET active = param WHERE id = service;
END //

DELIMITER ;

CALL updateServiceStatus(1, 0);

-- Add new Product
DELIMITER //

CREATE PROCEDURE createProduct(
    IN name varchar(30),
    IN description varchar(30), 
    IN validity datetime, 
    IN state boolean
	 )
BEGIN
    INSERT INTO `products` (`name`, `description`, `validity`, `state`) VALUES (name, description, validity, state);
END //

DELIMITER ;

CALL createProduct('New Product', 'product procedure', '2022-06-01 00:00:00', 1);

-- Change state of the Product
DELIMITER //

CREATE PROCEDURE updateProductState(
    IN product int(6),
    IN param boolean
	 )
BEGIN
    UPDATE `products` SET state = param WHERE id = product;
END //

DELIMITER ;

CALL updateProductState(1, 0);

-- Add service for the Product
DELIMITER //

CREATE PROCEDURE addProductService(
    IN product int(6),
    IN service int(6)
	 )
BEGIN
    INSERT INTO `product_services` (`product_id`, `service_id`) VALUES (product, service);
END //

DELIMITER ;

CALL addProductService(4, 1);

-- Remove service for the Product
DELIMITER //

CREATE PROCEDURE removeProductService(
    IN product int(6),
    IN service int(6)
	 )
BEGIN
    DELETE FROM `product_services` WHERE service_id = service AND product_id = product;
END //

DELIMITER ;

CALL removeProductService(4, 1);

-- Add new Customer
DELIMITER //

CREATE PROCEDURE createCustomer(
    IN name varchar(30),
    IN surename varchar(30), 
    IN address varchar(50), 
    IN phone_number varchar(30)
	 )
BEGIN
    INSERT INTO `customers` (`name`, `surename`, `address`, `phone_number`) VALUES (name, surename, address, phone_number);
END //

DELIMITER ;

CALL createCustomer('customer new', 'Doe', 'test', '+123456');

-- Add a sale to the Customer
DELIMITER //

CREATE PROCEDURE createOrder(
    IN product_id int(6),
    IN customer_id int(6),
    IN assistant_id int(6),
    IN price float,
    IN amount int(6), 
    IN total float
	 )
BEGIN
    INSERT INTO `orders` (`product_id`, `customer_id`, `assistant_id`, `price`, `amount`, `total`) VALUES (product_id, customer_id, assistant_id, price, amount, total);
END //

DELIMITER ;

CALL createOrder(1, 1, 1, 220, 1, 220);