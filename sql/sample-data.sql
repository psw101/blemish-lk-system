-- Insert sample data into the Users Table
INSERT INTO users (username, password, role) VALUES 
('admin', SHA2('admin_password', 256), 'admin'),
('user1', SHA2('user1_password', 256), 'user'),
('user2', SHA2('user2_password', 256), 'user');

-- Insert sample data into the Permissions Table
INSERT INTO permissions (user_id, permission_name) VALUES 
(1, 'view_inventory'),
(1, 'edit_inventory'),
(2, 'view_inventory'),
(3, 'view_inventory');

-- Insert sample data into the Supplier Table
INSERT INTO supplier (name, email, phone, address) VALUES 
('Supplier A', 'supplierA@example.com', '1234567890', '123 Elm St, Springfield'),
('Supplier B', 'supplierB@example.com', '0987654321', '456 Oak St, Shelbyville'),
('Supplier C', 'supplierC@example.com', '5555555555', '789 Maple St, Capital City');

-- Insert sample data into the Categories Table
INSERT INTO categories (categories_name) VALUES 
('Electronics'),
('Furniture'),
('Clothing');

-- Insert sample data into the Products Table
INSERT INTO products (product_name, category_id, supplier_id, price, quantity_in_stock, size, color, description) VALUES 
('Laptop', 1, 1, 999.99, 50, '15 inch', 'Silver', 'High-performance laptop'),
('Office Chair', 2, 2, 199.99, 200, 'Standard', 'Black', 'Ergonomic office chair'),
('T-shirt', 3, 3, 19.99, 500, 'Large', 'Blue', 'Cotton T-shirt');

-- Insert sample data into the Orders Table
INSERT INTO orders (order_date, total_amount) VALUES 
('2024-08-01', 1199.98),
('2024-08-10', 399.98);

-- Insert sample data into the Order Items Table
INSERT INTO order_items (order_id, product_id, quantity, price, total_price) VALUES 
(1, 1, 1, 999.99, 999.99),
(1, 2, 1, 199.99, 199.99),
(2, 2, 2, 199.99, 399.98);

-- Insert sample data into the Inventory Table
INSERT INTO inventory (product_id, quantity, remarks) VALUES 
(1, 50, 'Initial stock'),
(2, 200, 'Initial stock'),
(3, 500, 'Initial stock');