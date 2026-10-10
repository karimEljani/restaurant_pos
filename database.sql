CREATE DATABASE IF NOT EXISTS restaurant_pos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE restaurant_pos;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  icon VARCHAR(10) NOT NULL DEFAULT '🍽️'
);
CREATE TABLE menu_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  category_id INT NOT NULL,
  price DECIMAL(10,3) NOT NULL,
  emoji VARCHAR(10) NOT NULL DEFAULT '🍽️',
  active TINYINT(1) NOT NULL DEFAULT 1,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);
CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  order_type VARCHAR(20) NOT NULL,
  payment VARCHAR(20) NOT NULL,
  subtotal DECIMAL(10,3) NOT NULL,
  discount_pct DECIMAL(5,2) NOT NULL DEFAULT 0,
  tax_pct DECIMAL(5,2) NOT NULL DEFAULT 0,
  total DECIMAL(10,3) NOT NULL
);
CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  qty INT NOT NULL,
  price DECIMAL(10,3) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);
CREATE TABLE settings (k VARCHAR(50) PRIMARY KEY, v VARCHAR(255) NOT NULL);

INSERT INTO settings VALUES
 ('name','Restaurant POS'),('address','Avenue Habib Bourguiba, Tunis'),
 ('phone','71 000 000'),('tax','7'),('discount','0');

INSERT INTO categories (name,icon) VALUES
 ('Burgers','🍔'),('Pizza','🍕'),('Sides','🍟'),('Drinks','🥤'),('Desserts','🍰'),('Snacks','🥪');

INSERT INTO menu_items (name,category_id,price,emoji) VALUES
 ('Burger',1,8.000,'🍔'),('Cheese Burger',1,10.000,'🍔'),
 ('Veg Pizza',2,9.000,'🍕'),('Paneer Pizza',2,12.000,'🍕'),
 ('French Fries',3,4.000,'🍟'),('Garlic Bread',3,5.000,'🥖'),
 ('Cold Coffee',4,5.000,'🥤'),('Fresh Lime Soda',4,3.500,'🍹'),
 ('Chocolate Brownie',5,6.000,'🍫'),('Ice Cream',5,4.500,'🍨'),
 ('Veg Sandwich',6,6.500,'🥪'),('Pasta',6,9.500,'🍝');

INSERT INTO orders (created_at,order_type,payment,subtotal,discount_pct,tax_pct,total) VALUES
 (NOW() - INTERVAL 1 HOUR,'Takeaway','Card',31.500,0,0,31.500),
 (NOW() - INTERVAL 2 HOUR,'Dine-in','Cash',19.500,0,0,19.500);
INSERT INTO order_items (order_id,name,qty,price) VALUES
 (1,'Paneer Pizza',2,12.000),(1,'French Fries',1,4.000),(1,'Fresh Lime Soda',1,3.500),
 (2,'Cheese Burger',1,10.000),(2,'Pasta',1,9.500);
