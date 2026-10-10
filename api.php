<?php
require_once 'config.php';
header('Content-Type: application/json');
$in = json_decode(file_get_contents('php://input'), true) ?: [];
if (($_GET['action'] ?? '') !== 'save_order') { http_response_code(400); echo '{"ok":false}'; exit; }

$qtys = [];
foreach ((array)($in['items'] ?? []) as $it) {
    $q = max(1, (int)($it['qty'] ?? 0)); $qtys[(int)$it['id']] = $q;
}
if (!$qtys) { echo json_encode(['ok'=>false,'error'=>'Order is empty']); exit; }

// Prices are read from the DB, never trusted from the browser
$in_ids = implode(',', array_fill(0, count($qtys), '?'));
$st = $pdo->prepare("SELECT id,name,price FROM menu_items WHERE active=1 AND id IN ($in_ids)");
$st->execute(array_keys($qtys));
$rows = $st->fetchAll();
if (!$rows) { echo json_encode(['ok'=>false,'error'=>'Items not found']); exit; }

$sub = 0; foreach ($rows as $r) $sub += $r['price'] * $qtys[$r['id']];
$disc = min(100, max(0, (float)($in['discount'] ?? 0)));
$tax  = min(100, max(0, (float)($in['tax'] ?? 0)));
$after = $sub - $sub * $disc / 100;
$total = round($after + $after * $tax / 100, 3);
$type = in_array($in['type'] ?? '', ['Dine-in','Takeaway','Delivery']) ? $in['type'] : 'Dine-in';
$pay  = in_array($in['payment'] ?? '', ['Cash','Card','UPI']) ? $in['payment'] : 'Cash';

$pdo->beginTransaction();
$pdo->prepare('INSERT INTO orders (order_type,payment,subtotal,discount_pct,tax_pct,total) VALUES (?,?,?,?,?,?)')
    ->execute([$type,$pay,$sub,$disc,$tax,$total]);
$oid = $pdo->lastInsertId();
$ins = $pdo->prepare('INSERT INTO order_items (order_id,name,qty,price) VALUES (?,?,?,?)');
foreach ($rows as $r) $ins->execute([$oid,$r['name'],$qtys[$r['id']],$r['price']]);
$pdo->commit();
echo json_encode(['ok'=>true,'id'=>(int)$oid,'total'=>$total]);
