# Restaurant POS – PHP / MySQL

## Installation (XAMPP / WAMP / Laragon)
1. Copy the `restaurant-pos` folder into `htdocs` (XAMPP) or `www` (WAMP).
2. Open phpMyAdmin → **Import** → select `database.sql`.
3. Edit `config.php` if your MySQL user/password differ (default: root / empty).
4. Open http://localhost/restaurant-pos/

## Pages
- `index.php` – POS: categories, search, cart, discount/tax, save + print receipt
- `dashboard.php` – today's sales, totals, top-selling items, recent orders
- `orders.php` – order history, receipt reprint, delete
- `menu.php` – add / activate / deactivate / delete menu items
- `settings.php` – restaurant name, address, phone, default tax & discount

Requires PHP 7.4+ and MySQL/MariaDB. Prices are always recalculated server-side in `api.php`.
