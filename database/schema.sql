-- ==============================================================================
-- MORTYX APPAREL E-COMMERCE PLATFORM
-- Relational Database DDL Schema (MySQL 8.0+ / MariaDB 10.4+ InnoDB)
-- Architecture Standard: docs/SRS.md & docs/UML.md
-- ==============================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Table: users
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(25) NULL,
    `role` ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_users_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Table: categories
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(60) NOT NULL,
    `slug` VARCHAR(60) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_categories_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Table: products
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `base_price` DECIMAL(12, 2) NOT NULL,
    `compare_price` DECIMAL(12, 2) NULL,
    `tag` VARCHAR(50) NULL,
    `tag_color` VARCHAR(80) NULL,
    `short_description` VARCHAR(255) NOT NULL,
    `full_description` TEXT NOT NULL,
    `primary_image` VARCHAR(255) NOT NULL,
    `hover_image` VARCHAR(255) NOT NULL,
    `rating` DECIMAL(2, 1) NOT NULL DEFAULT 5.0,
    `review_count` INT UNSIGNED NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX `idx_products_category` (`category_id`),
    INDEX `idx_products_is_active` (`is_active`),
    INDEX `idx_products_base_price` (`base_price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Table: product_variants (Multi-size & multi-color SKU stock control)
DROP TABLE IF EXISTS `product_variants`;
CREATE TABLE `product_variants` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `size` VARCHAR(15) NOT NULL,
    `color` VARCHAR(50) NOT NULL,
    `color_hex` VARCHAR(10) NOT NULL DEFAULT '#000000',
    `stock` INT NOT NULL DEFAULT 0,
    `sku` VARCHAR(60) NOT NULL UNIQUE,
    `price_override` DECIMAL(12, 2) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX `idx_variants_product` (`product_id`),
    INDEX `idx_variants_sku` (`sku`),
    INDEX `idx_variants_stock` (`stock`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Table: orders
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_number` VARCHAR(32) NOT NULL UNIQUE,
    `user_id` INT UNSIGNED NULL,
    `customer_name` VARCHAR(100) NOT NULL,
    `customer_email` VARCHAR(150) NOT NULL,
    `customer_phone` VARCHAR(25) NOT NULL,
    `shipping_address` TEXT NOT NULL,
    `city` VARCHAR(100) NOT NULL,
    `province` VARCHAR(100) NOT NULL,
    `postal_code` VARCHAR(10) NOT NULL,
    `courier_name` VARCHAR(50) NOT NULL,
    `shipping_cost` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `subtotal` DECIMAL(12, 2) NOT NULL,
    `total_amount` DECIMAL(12, 2) NOT NULL,
    `status` ENUM('PENDING_PAYMENT', 'PAID', 'PROCESSING', 'SHIPPED', 'COMPLETED', 'CANCELLED', 'EXPIRED') NOT NULL DEFAULT 'PENDING_PAYMENT',
    `tracking_number` VARCHAR(100) NULL,
    `expires_at` DATETIME NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX `idx_orders_user` (`user_id`),
    INDEX `idx_orders_status` (`status`),
    INDEX `idx_orders_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Table: order_items
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT UNSIGNED NOT NULL,
    `variant_id` INT UNSIGNED NOT NULL,
    `product_name` VARCHAR(150) NOT NULL,
    `variant_size` VARCHAR(15) NOT NULL,
    `variant_color` VARCHAR(50) NOT NULL,
    `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
    `unit_price` DECIMAL(12, 2) NOT NULL,
    `subtotal_price` DECIMAL(12, 2) NOT NULL,
    CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_order_items_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX `idx_order_items_order` (`order_id`),
    INDEX `idx_order_items_variant` (`variant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Table: payments
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT UNSIGNED NOT NULL UNIQUE,
    `payment_method` VARCHAR(50) NOT NULL,
    `amount` DECIMAL(12, 2) NOT NULL,
    `payment_status` ENUM('PENDING', 'SETTLED', 'EXPIRED', 'FAILED') NOT NULL DEFAULT 'PENDING',
    `transaction_id` VARCHAR(100) NULL,
    `gateway_response_payload` TEXT NULL,
    `paid_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX `idx_payments_order` (`order_id`),
    INDEX `idx_payments_status` (`payment_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

