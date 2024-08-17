-- Insert sample data into the users table
INSERT INTO users (username, password, role) VALUES
('admin_user', '8C6976E5B5410415BDE908BD4DEE15DFB167A9C873FC4BB8A81F6F2AB448A918', 'admin'),  -- password: "admin"
('john_doe', '5E884898DA28047151D0E56F8DC6292773603D0D6AABBDD62A11EF721D1542D8', 'user'),     -- password: "password"
('jane_smith', '8D969EEF6ECAD3C29A3A629280E686CF0C3F5D5A86AFF3CA12020C923ADC6C92', 'user');   -- password: "123456"

-- Insert sample data into the permissions table
INSERT INTO permissions (user_id, permission_name) VALUES
(1, 'view_inventory'),
(1, 'edit_inventory'),
(1, 'delete_inventory'),
(2, 'view_inventory'),
(3, 'view_inventory');
