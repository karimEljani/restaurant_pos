<?php $page='menu'; include 'inc/header.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(isset($_POST['add'])&&trim($_POST['name'])!=='')
    $pdo->prepare('INSERT INTO menu_items (name,category_id,price,emoji) VALUES (?,?,?,?)')->execute([trim($_POST['name']),(int)$_POST['cat'],max(0,(float)$_POST['price']),trim($_POST['emoji'])?:'🍽️']);
  header('Location: menu.php');exit;}
if(isset($_GET['toggle']))$pdo->prepare('UPDATE menu_items SET active=1-active WHERE id=?')->execute([(int)$_GET['toggle']]);
if(isset($_GET['del'])){try{$pdo->prepare('DELETE FROM menu_items WHERE id=?')->execute([(int)$_GET['del']]);}catch(Exception $e){}}
if(isset($_GET['toggle'])||isset($_GET['del'])){header('Location: menu.php');exit;}
$cats=$pdo->query('SELECT * FROM categories')->fetchAll();
$rows=$pdo->query('SELECT m.*,c.name cat FROM menu_items m JOIN categories c ON c.id=m.category_id ORDER BY m.id')->fetchAll(); ?>
<div class="card pad"><h2>Menu Manager</h2>
<form method="post" class="frm"><input type="hidden" name="add" value="1">
 <label>Item Name<input name="name" placeholder="New Item" required></label>
 <label>Emoji<input name="emoji" placeholder="🍔" size="3"></label>
 <label>Category<select name="cat"><?php foreach($cats as $c): ?><option value="<?=$c['id']?>"><?=h($c['name'])?></option><?php endforeach; ?></select></label>
 <label>Price (<?=CUR?>)<input type="number" step="0.001" min="0" name="price" value="5.000" required></label>
 <button class="btn">Add Item</button></form>
<div class="scroll"><table class="tbl"><tr><th>ID</th><th>Item Name</th><th>Category</th><th>Price</th><th>Active</th><th></th></tr>
<?php foreach($rows as $m): ?><tr><td><?=$m['id']?></td><td><?=h($m['emoji'].' '.$m['name'])?></td><td><?=h($m['cat'])?></td><td><?=number_format($m['price'],3)?></td>
<td><span class="pill <?=$m['active']?'':'off'?>"><?=$m['active']?'Yes':'No'?></span></td>
<td><a class="btn sm red" href="?toggle=<?=$m['id']?>"><?=$m['active']?'Deactivate':'Activate'?></a> <a class="btn sm" href="?del=<?=$m['id']?>" onclick="return confirm('Delete this item?')">Delete</a></td></tr><?php endforeach; ?></table></div></div>
<?php include 'inc/footer.php'; ?>
