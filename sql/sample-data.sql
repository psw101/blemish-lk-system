USE blemish_inventory;

-- Insert data into the users table
INSERT INTO users (username, password, role) VALUES
('admin_user', SHA2('admin_password', 256), 'admin'),
('regular_user', SHA2('user_password', 256), 'user');

-- Insert data into the permissions table
INSERT INTO permissions (user_id, permission_name) VALUES
(1, 'manage_users'),
(1, 'view_reports'),
(2, 'view_inventory');

-- Insert data into the supplier table
INSERT INTO supplier (name, email, phone, address) VALUES
('Supplier One', 'supplier1@example.com', '1234567890', '123 Main St, City'),
('Supplier Two', 'supplier2@example.com', '0987654321', '456 Elm St, City');

-- Insert data into the categories table
INSERT INTO categories (categories_name) VALUES
('Electronics'),
('Clothing'),
('Books');

-- Insert data into the product table
INSERT INTO product (product_name, product_des, categories_id, sellPrice) VALUES
('Laptop', 'High-end gaming laptop', 1, '1500.00'),
('T-shirt', 'Cotton T-shirt', 2, '20.00'),
('Novel', 'Best-selling fiction novel', 3, '15.00');

-- Insert data into the products table
INSERT INTO products (product_name, category_id, supplier_id, price, quantity_in_stock, size, color, description) VALUES
('Gaming Laptop', 1, 1, 1500.00, 10, '15 inch', 'Black', 'High-end gaming laptop with latest specs'),
('Casual T-shirt', 2, 2, 20.00, 50, 'L', 'Blue', 'Comfortable cotton T-shirt'),
('Mystery Novel', 3, 1, 15.00, 100, NULL, NULL, 'Best-selling mystery novel by a popular author');

-- Insert data into the orders table
INSERT INTO orders (order_date, total_amount, supplier_id) VALUES
('2024-08-01', 450.00, 1),
('2024-08-02', 1000.00, 2);

-- Insert data into the order_items table
INSERT INTO order_items (order_id, product_id, quantity, price, total_price) VALUES
(1, 1, 2, 1500.00, 3000.00),
(2, 3, 20, 15.00, 300.00);

-- Insert data into the inventory table
INSERT INTO inventory (product_id, quantity, remarks) VALUES
(1, 20, 'Initial stock'),
(2, 100, 'Initial stock'),
(3, 200, 'Initial stock');
