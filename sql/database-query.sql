CREATE DATABASE blemish_inventory;
USE blemish_inventory;

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


CREATE TABLE supplier (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(191) NOT NULL,
    email VARCHAR(191) NOT NULL,
    phone VARCHAR(191) NOT NULL,
    address VARCHAR(200) NOT NULL,
    PRIMARY KEY (id)
);


CREATE TABLE `categories` (
  `categories_id` int(11) PRIMARY KEY AUTO_INCREMENT ,
  `categories_name` varchar(255) NOT NULL
  
);

CREATE TABLE `product` (
  `product_id` int(11) PRIMARY KEY NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_des` varchar(255) NOT NULL,
  `categories_id` int(11) NOT NULL,
  `sellPrice` varchar(255) NOT NULL
);

-- Products Table
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    category_id INT,
    supplier_id INT,
    price DECIMAL(10, 2) NOT NULL,
    quantity_in_stock INT NOT NULL,
    size VARCHAR(50),
    color VARCHAR(50),
    description TEXT,
    FOREIGN KEY (category_id) REFERENCES categories(categories_id),
    FOREIGN KEY (supplier_id) REFERENCES supplier(id)
);


-- Orders Table (Modified with 'status' column)
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    order_date DATE NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL
);

-- Order Items Table
CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- Inventory Table
CREATE TABLE inventory (
    inventory_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    quantity INT NOT NULL,
    remarks TEXT,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);






