DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `restaurantId` INT UNSIGNED NOT NULL,
    `amount` DECIMAL(8 , 2 ) NOT NULL,
	`cost` decimal(8,2) NOT NULL,
    `tax` decimal(4,2) NOT NULL,
    `created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `invoice_positions`;
CREATE TABLE `invoice_positions` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `orderId` INT UNSIGNED NOT NULL,
	`totalCost` decimal(8,2) NOT NULL,
    `taxAmount` decimal(8,2) NOT NULL,
    `created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `invoice`;
CREATE TABLE `invoice` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `restaurantId` INT UNSIGNED NOT NULL,
	`totalCost` decimal(8,2) NOT NULL,
    `taxAmount` decimal(8,2) NOT NULL,
    `created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
