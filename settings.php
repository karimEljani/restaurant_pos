<?php $page='set'; include 'inc/header.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $u=$pdo->prepare('REPLACE INTO settings (k,v) VALUES (?,?)');
  foreach(['name','address','phone','tax','discount'] as $k)$u->execute([$k,trim($_POST[$k]??'')]);
  header('Location: settings.php?ok=1');exit;} ?>
<div class="card pad" style="max-width:520px"><h2>Settings</h2>
<?php if(isset($_GET['ok'])) echo '<p class="pill">Settings saved.</p>'; ?>
<form method="post" class="frm col">
 <label>Restaurant name<input name="name" value="<?=h($S['name'])?>" required></label>
 <label>Address<input name="address" value="<?=h($S['address'])?>"></label>
 <label>Phone<input name="phone" value="<?=h($S['phone'])?>"></label>
 <label>Default tax (%)<input type="number" step="0.01" name="tax" value="<?=h($S['tax'])?>"></label>
 <label>Default discount (%)<input type="number" step="0.01" name="discount" value="<?=h($S['discount'])?>"></label>
 <button class="btn green">Save settings</button></form></div>
<?php include 'inc/footer.php'; ?>
