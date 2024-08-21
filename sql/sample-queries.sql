USE blemish_inventory;

SELECT * FROM users;
SELECT * FROM permissions;
SELECT * FROM supplier;
SELECT * FROM categories;
SELECT * FROM product;
SELECT * FROM products;
SELECT * FROM orders;
SELECT * FROM order_items;
SELECT * FROM inventory;

-- Insert a new user
INSERT INTO users (username, password, role)
VALUES ('newuser5', SHA2('user3-password', 256), 'user');

-- Select a user by username
SELECT * FROM users
WHERE username = 'newuser';

-- Grant permission to a user
INSERT INTO permissions (user_id, permission_name)
VALUES (1, 'view_inventory');

-- List users with their permissions
SELECT u.username, p.permission_name
FROM users u
JOIN permissions p ON u.user_id = p.user_id;

-- Insert a new supplier
INSERT INTO supplier (name, email, phone, address)
VALUES ('Supplier Name', 'supplier@example.com', '1234567890', '123 Street, City');

-- Get supplier details
SELECT * FROM supplier
WHERE id = 1;

-- Insert a new category
INSERT INTO categories (categories_name)
VALUES ('Shirts');

-- Insert a new product
INSERT INTO products (product_name, category_id, supplier_id, price, quantity_in_stock, size, color, description)
VALUES ('T-shirt', 1, 1, 19.99, 100, 'M', 'Red', 'A comfortable red t-shirt.');

-- Update product stock
UPDATE products
SET quantity_in_stock = quantity_in_stock + 50
WHERE product_id = 1;

-- List products by category
SELECT p.product_name, p.price, p.quantity_in_stock, p.size, p.color, c.categories_name
FROM products p
JOIN categories c ON p.category_id = c.categories_id
WHERE c.categories_name = 'Shirts';

-- Create a new order
INSERT INTO orders (order_date, total_amount)
VALUES (CURDATE(), 59.99);

-- Add an item to an order
INSERT INTO order_items (order_id, product_id, quantity, price, total_price)
VALUES (1, 1, 2, 19.99, 39.98);

-- Get order details with items
SELECT o.order_id, o.order_date, oi.quantity, p.product_name, oi.total_price
FROM orders o
JOIN order_items oi ON o.order_id = oi.order_id
JOIN products p ON oi.product_id = p.product_id
WHERE o.order_id = 1;

-- Insert a new inventory record
INSERT INTO inventory (product_id, quantity, remarks)
VALUES (1, 100, 'Initial stock');

-- Update inventory after a purchase
UPDATE inventory
SET quantity = quantity - 2
WHERE product_id = 1;

-- View all inventory records:
SELECT i.inventory_id, p.product_name, i.quantity, i.remarks
FROM inventory i
JOIN products p ON i.product_id = p.product_id;

-- Total sales for a given date range
SELECT SUM(total_amount) AS total_sales
FROM orders
WHERE order_date BETWEEN '2024-01-01' AND '2024-01-31';

-- Top-selling products
SELECT p.product_name, SUM(oi.quantity) AS total_sold
FROM order_items oi
JOIN products p ON oi.product_id = p.product_id
GROUP BY p.product_id
ORDER BY total_sold DESC;

-- Low stock alert
SELECT product_name, quantity_in_stock
FROM products
WHERE quantity_in_stock < 10;

-- check the validity of order id
SELECT COUNT(*) AS order_count
FROM orders
WHERE order_id = 1;
