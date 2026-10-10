<?php
// ---- Edit these values for your MySQL server ----
const DB_HOST = 'localhost';
const DB_NAME = 'restaurant_pos';
const DB_USER = 'root';
const DB_PASS = '';
const CUR = 'DT';

date_default_timezone_set('Asia/Kolkata');
try {
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
} catch (PDOException $e) {
    die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
}
function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money($n) { return number_format((float)$n, 3, '.', ' ') . ' ' . CUR; }
function settings($pdo) { return $pdo->query('SELECT k,v FROM settings')->fetchAll(PDO::FETCH_KEY_PAIR); }
