<?php $page='orders'; include 'inc/header.php';
if(isset($_GET['del'])){$pdo->prepare('DELETE FROM orders WHERE id=?')->execute([(int)$_GET['del']]);header('Location: orders.php');exit;}
$rows=$pdo->query("SELECT o.*,(SELECT GROUP_CONCAT(CONCAT(qty,'× ',name) SEPARATOR ', ') FROM order_items WHERE order_id=o.id) its FROM orders o ORDER BY id DESC LIMIT 200")->fetchAll(); ?>
<div class="card pad"><h2>All Orders</h2><div class="scroll"><table class="tbl"><tr><th>#</th><th>Date</th><th>Items</th><th>Type</th><th>Payment</th><th>Total</th><th></th></tr>
<?php foreach($rows as $o): ?><tr><td><?=$o['id']?></td><td><?=date('d-m-Y h:i A',strtotime($o['created_at']))?></td><td><?=h($o['its'])?></td><td><?=h($o['order_type'])?></td><td><?=h($o['payment'])?></td><td><b><?=money($o['total'])?></b></td>
<td><a class="btn sm" target="_blank" href="receipt.php?id=<?=$o['id']?>">🖨️ Receipt</a> <a class="btn sm red" href="?del=<?=$o['id']?>" onclick="return confirm('Delete order #<?=$o['id']?>?')">Delete</a></td></tr><?php endforeach; ?>
<?php if(!$rows) echo '<tr><td colspan="7" class="muted">No orders yet. Complete one from the POS screen.</td></tr>'; ?></table></div></div>
<?php include 'inc/footer.php'; ?>
