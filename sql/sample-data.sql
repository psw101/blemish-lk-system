USE blemish_inventory;

-- Insert Sample Data into the `users` Table
INSERT INTO users (username, password, role) VALUES
('admin', SHA2('admin123', 256), 'admin'),
('john_doe', SHA2('password123', 256), 'user'),
('jane_smith', SHA2('password456', 256), 'user');

-- Insert Sample Data into the `permissions` Table
INSERT INTO permissions (user_id, permission_name) VALUES
(1, 'view_inventory'),
(1, 'edit_inventory'),
(2, 'view_inventory');

-- Insert Sample Data into the `supplier` Table
INSERT INTO supplier (name, email, phone, address) VALUES
('ABC Textiles', 'contact@abctextiles.com', '123-456-7890', '123 Textile Avenue, City A'),
('Fashion Fabrics', 'info@fashionfabrics.com', '234-567-8901', '456 Fabric Road, City B');

-- Insert Sample Data into the `categories` Table
INSERT INTO categories (categories_name) VALUES
('T-Shirts'),
('Jeans'),
('Jackets'),
('Accessories');

-- Insert Sample Data into the `product` Table
INSERT INTO product (product_name, product_des, categories_id, sellPrice) VALUES
('Classic White T-Shirt', '100% Cotton, Unisex', 1, '15.99'),
('Denim Jeans', 'Slim Fit, Blue', 2, '49.99'),
('Leather Jacket', 'Black, Genuine Leather', 3, '129.99'),
('Wool Scarf', 'Warm and Cozy', 4, '19.99');

-- Insert Sample Data into the `inventory` Table
INSERT INTO inventory (product_id, product_name, quantity_in_stock) VALUES
(1, 'Classic White T-Shirt', 150),
(2, 'Denim Jeans', 75),
(3, 'Leather Jacket', 30),
(4, 'Wool Scarf', 200);

-- Insert Sample Data into the `orders` Table
INSERT INTO orders (order_date, total_amount, supplier_id) VALUES
('2024-08-01', 2000.00, 1),
('2024-08-05', 3500.50, 2);

-- Insert Sample Data into the `order_items` Table
INSERT INTO order_items (order_id, product_id, quantity, price, total_price) VALUES
(1, 1, 100, 10.00, 1000.00),
(1, 2, 50, 20.00, 1000.00),
(2, 3, 20, 50.00, 1000.00),
(2, 4, 100, 25.00, 2500.00);

-- Insert Sample Data into the `sales` Table
INSERT INTO sales (sale_date, total_amount) VALUES
('2024-08-10', 750.00),
('2024-08-15', 1200.00);

-- Insert Sample Data into the `sales_items` Table
INSERT INTO sales_items (sales_id, product_id, quantity, price) VALUES
(1, 1, 10, 15.99),
(1, 2, 5, 49.99),
(2, 3, 2, 129.99),
(2, 4, 20, 19.99);