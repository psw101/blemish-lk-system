USE blemish_inventory;

-- Insert Sample Users
INSERT INTO users (username, password, role) VALUES
('admin', SHA2('adminpassword', 256), 'admin'),
('user1', SHA2('user1password', 256), 'user');

-- Insert Sample Permissions
INSERT INTO permissions (user_id, permission_name) VALUES
(1, 'manage_users'),
(1, 'manage_inventory'),
(2, 'view_products');

-- Insert Sample Suppliers
INSERT INTO supplier (name, email, phone, address) VALUES
('Supplier A', 'contact@suppliera.com', '+1234567890', '123 Supplier A St, City'),
('Supplier B', 'contact@supplierb.com', '+0987654321', '456 Supplier B Ave, City');

-- Insert Sample Categories
INSERT INTO categories (categories_name) VALUES
('Tops'),
('Bottoms'),
('Outerwear');

-- Insert Sample Products
INSERT INTO product (product_name, product_des, categories_id, sellPrice) VALUES
('Basic T-Shirt', '100% cotton, crew neck', 1, '9.99'),
('Denim Jeans', 'Slim fit, dark wash', 2, '29.99'),
('Winter Jacket', 'Waterproof, hooded', 3, '79.99');

INSERT INTO products (product_name, category_id, supplier_id, price, quantity_in_stock, size, color, description) VALUES
('Basic T-Shirt', 1, 1, 9.99, 100, 'M', 'Black', '100% cotton, crew neck'),
('Denim Jeans', 2, 2, 29.99, 50, 'L', 'Dark Blue', 'Slim fit, dark wash'),
('Winter Jacket', 3, 1, 79.99, 25, 'XL', 'Navy', 'Waterproof, hooded');

-- Insert Sample Orders
INSERT INTO orders (order_date, total_amount, supplier_id) VALUES
('2024-08-01', 999.75, 1),
('2024-08-15', 1499.50, 2);

-- Insert Sample Order Items
INSERT INTO order_items (order_id, product_id, quantity, price, total_price) VALUES
(1, 1, 50, 9.99, 499.50),
(1, 2, 10, 29.99, 299.90),
(1, 3, 5, 79.99, 399.95),
(2, 1, 50, 9.99, 499.50),
(2, 2, 20, 29.99, 599.80),
(2, 3, 10, 79.99, 799.90);

-- Insert Sample Inventory
INSERT INTO inventory (product_id, quantity, remarks) VALUES
(1, 150, 'Restocked from Order 1 and Order 2'),
(2, 70, 'Restocked from Order 1 and Order 2'),
(3, 35, 'Restocked from Order 1 and Order 2');
