<?php $page='dash'; include 'inc/header.php';
$one=fn($sql)=>$pdo->query($sql)->fetch(PDO::FETCH_NUM);
[$tc,$ts]=$one("SELECT COUNT(*),COALESCE(SUM(total),0) FROM orders WHERE DATE(created_at)=CURDATE()");
[$ac,$as]=$one("SELECT COUNT(*),COALESCE(SUM(total),0) FROM orders");
$top=$pdo->query("SELECT oi.name,SUM(oi.qty) q FROM order_items oi JOIN orders o ON o.id=oi.order_id WHERE DATE(o.created_at)=CURDATE() GROUP BY oi.name ORDER BY q DESC LIMIT 5")->fetchAll();
$rec=$pdo->query("SELECT * FROM orders ORDER BY id DESC LIMIT 8")->fetchAll();
$max=max(array_column($top,'q')?:[1]); $cols=['#2f80ed','#27ae60','#f2c94c','#eb5757','#9b51e0']; ?>
<div class="stats">
  <div class="stat" style="background:#2f80ed"><b><?=$tc?></b>Today's Orders</div>
  <div class="stat" style="background:#12805C"><b><?=money($ts)?></b>Today's Sales</div>
  <div class="stat" style="background:#f2994a"><b><?=$ac?></b>Total Orders</div>
  <div class="stat" style="background:#7B3FE4"><b><?=money($as)?></b>Total Revenue</div>
</div>
<div class="card pad"><h2>Top Selling Items (Today)</h2>
<?php if(!$top) echo '<p class="muted">No sales yet today.</p>'; foreach($top as $i=>$t): ?>
<div class="bar"><span><?=$i+1?>. <?=h($t['name'])?></span><i><u style="width:<?=$t['q']/$max*100?>%;background:<?=$cols[$i]?>"></u></i><b><?=$t['q']?></b></div><?php endforeach; ?></div>
<div class="card pad"><h2>Recent Orders</h2><div class="scroll"><table class="tbl"><tr><th>#</th><th>Date &amp; Time</th><th>Type</th><th>Payment</th><th>Total</th></tr>
<?php foreach($rec as $o): ?><tr><td><?=$o['id']?></td><td><?=date('d-m-Y h:i A',strtotime($o['created_at']))?></td><td><?=h($o['order_type'])?></td><td><?=h($o['payment'])?></td><td><b><?=money($o['total'])?></b></td></tr><?php endforeach; ?></table></div></div>
<?php include 'inc/footer.php'; ?>
