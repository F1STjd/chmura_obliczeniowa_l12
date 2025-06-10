-- MySQL Database Initialization Script
-- LEMP Stack - Development Environment
-- Author: Konrad Nowak
-- Date: 2025-06-10

-- Create application database if not exists
CREATE DATABASE IF NOT EXISTS `lemp_db` 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

-- Use the application database
USE `lemp_db`;

-- Create sample table for testing
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `first_name` VARCHAR(50) DEFAULT NULL,
    `last_name` VARCHAR(50) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `is_active` BOOLEAN DEFAULT TRUE,
    PRIMARY KEY (`id`),
    INDEX `idx_username` (`username`),
    INDEX `idx_email` (`email`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data
INSERT INTO `users` (`username`, `email`, `password_hash`, `first_name`, `last_name`) VALUES
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User'),
('john_doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John', 'Doe'),
('jane_smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane', 'Smith');

-- Create sample products table
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `category` VARCHAR(50) DEFAULT NULL,
    `stock_quantity` INT(11) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_name` (`name`),
    INDEX `idx_category` (`category`),
    INDEX `idx_price` (`price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample products
INSERT INTO `products` (`name`, `description`, `price`, `category`, `stock_quantity`) VALUES
('Laptop Dell XPS 13', 'High-performance ultrabook with Intel Core i7', 4999.99, 'Electronics', 25),
('iPhone 15 Pro', 'Latest Apple smartphone with A17 Pro chip', 5499.99, 'Electronics', 50),
('Gaming Chair', 'Ergonomic gaming chair with lumbar support', 899.99, 'Furniture', 15),
('Wireless Mouse', 'Bluetooth wireless mouse with precision tracking', 149.99, 'Accessories', 100),
('Mechanical Keyboard', 'RGB backlit mechanical keyboard', 299.99, 'Accessories', 35);

-- Create user permissions table
CREATE TABLE IF NOT EXISTS `user_permissions` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `permission` VARCHAR(50) NOT NULL,
    `granted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_user_permission` (`user_id`, `permission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample permissions
INSERT INTO `user_permissions` (`user_id`, `permission`) VALUES
(1, 'admin'),
(1, 'read'),
(1, 'write'),
(1, 'delete'),
(2, 'read'),
(2, 'write'),
(3, 'read');

-- Create application settings table
CREATE TABLE IF NOT EXISTS `app_settings` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT,
    `description` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default settings
INSERT INTO `app_settings` (`setting_key`, `setting_value`, `description`) VALUES
('app_name', 'LEMP Stack Application', 'Application name'),
('app_version', '1.0.0', 'Current application version'),
('maintenance_mode', 'false', 'Maintenance mode status'),
('max_upload_size', '50M', 'Maximum file upload size'),
('timezone', 'Europe/Warsaw', 'Application timezone');

-- Display completion message
SELECT 'Database initialization completed successfully!' as message;
SELECT COUNT(*) as total_users FROM users;
SELECT COUNT(*) as total_products FROM products;
SELECT COUNT(*) as total_permissions FROM user_permissions;
SELECT COUNT(*) as total_settings FROM app_settings;
