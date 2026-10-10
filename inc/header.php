<?php require_once __DIR__ . '/../config.php'; $S = settings($pdo);
$nav = ['pos'=>['index.php','🏠','POS'],'dash'=>['dashboard.php','📊','Dashboard'],'orders'=>['orders.php','🧾','Orders'],'menu'=>['menu.php','🍴','Menu'],'set'=>['settings.php','⚙️','Settings']]; ?>
<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=h($S['name'])?> | Smart Billing</title>
<link rel="stylesheet" href="assets/style.css"></head><body>
<header class="top">
  <div class="brand"><span class="logo">👨‍🍳</span><div><h1><?=h(strtoupper($S['name']))?></h1><small>Smart Billing System</small></div></div>
  <nav><?php foreach($nav as $k=>[$u,$i,$t]): ?>
    <a href="<?=$u?>" class="<?=$page===$k?'on':''?>"><span><?=$i?></span><?=$t?></a><?php endforeach; ?></nav>
  <div class="clock"><span id="d"></span><b id="t"></b></div>
</header><main>
