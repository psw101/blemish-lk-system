-- Insert sample data into the users table
INSERT INTO users (username, password, role) VALUES
('admin_user', 'e3afed0047b08059d0fada10f400c1e5', 'admin'),  -- password: "admin"
('john_doe', '5e884898da28047151d0e56f8dc62927', 'user'),     -- password: "password"
('jane_smith', '2c1743a391305fbf367df8e4f069f9f9', 'user'),   -- password: "123456"
('mike_jones', 'bcb8fbf9b80b0d6cc22a6cd2d1f4bc64', 'user');   -- password: "qwerty"

-- Insert sample data into the permissions table
INSERT INTO permissions (user_id, permission_name) VALUES
(1, 'view_inventory'),
(1, 'edit_inventory'),
(1, 'delete_inventory'),
(2, 'view_inventory'),
(3, 'view_inventory'),
(4, 'view_inventory'),
(4, 'edit_inventory');
