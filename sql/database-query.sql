-- Existing Database Creation --
CREATE DATABASE clothing_store_inventory;
USE clothing_store_inventory;
-- Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password CHAR(64) NOT NULL,  -- SHA-256 produces a 64-character hexadecimal string
    role ENUM('admin', 'user') NOT NULL
);

-- Permissions Table
CREATE TABLE permissions (
    permission_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    permission_name VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);