<?php require_once 'config.php'; $S=settings($pdo);
$st=$pdo->prepare('SELECT * FROM orders WHERE id=?');$st->execute([(int)($_GET['id']??0)]);$o=$st->fetch();
if(!$o)die('Order not found');
$st=$pdo->prepare('SELECT * FROM order_items WHERE order_id=?');$st->execute([$o['id']]);$items=$st->fetchAll();
$dv=$o['subtotal']*$o['discount_pct']/100; $tv=($o['subtotal']-$dv)*$o['tax_pct']/100; ?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Receipt #<?=$o['id']?></title>
<style>body{font:13px 'Courier New',monospace;max-width:340px;margin:16px auto;color:#000}
h2,p{text-align:center;margin:2px 0}hr{border:0;border-top:1px dashed #000;margin:8px 0}
table{width:100%;border-collapse:collapse}td:last-child,th:last-child{text-align:right}th{text-align:left}
.t{font-size:18px;font-weight:bold}@media print{.np{display:none}}</style></head><body>
<h2>🍴 <?=h(strtoupper($S['name']))?></h2><p><?=h($S['address'])?></p><p>Phone: <?=h($S['phone'])?></p><p>Thank you for dining with us!</p><hr>
<p style="text-align:left">Order # : <?=$o['id']?><br>Date : <?=date('d-m-Y h:i A',strtotime($o['created_at']))?><br>Order Type : <?=h($o['order_type'])?><br>Payment : <?=h($o['payment'])?></p><hr>
<table><tr><th>Item</th><th>Qty</th><th>Amount</th></tr>
<?php foreach($items as $i): ?><tr><td><?=h($i['name'])?></td><td><?=$i['qty']?></td><td><?=number_format($i['qty']*$i['price'],3)?></td></tr><?php endforeach; ?></table><hr>
<table><tr><td>Subtotal</td><td><?=number_format($o['subtotal'],3)?></td></tr>
<tr><td>Discount (<?=+$o['discount_pct']?>%)</td><td>-<?=number_format($dv,3)?></td></tr>
<tr><td>Tax (<?=+$o['tax_pct']?>%)</td><td><?=number_format($tv,3)?></td></tr></table><hr>
<table class="t"><tr><td>TOTAL (DT)</td><td><?=number_format($o['total'],3)?></td></tr></table><hr><p>Thank you! Visit Again!</p>
<p class="np"><br><button onclick="print()">🖨️ Print</button> <button onclick="close()">Close</button></p>
<?php if(isset($_GET['print'])): ?><script>onload=()=>print()</script><?php endif; ?></body></html>
