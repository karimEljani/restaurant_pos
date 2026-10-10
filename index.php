<?php $page='pos'; include 'inc/header.php';
$cats  = $pdo->query('SELECT * FROM categories ORDER BY id')->fetchAll();
$items = $pdo->query('SELECT id,name,category_id AS cat,price,emoji FROM menu_items WHERE active=1 ORDER BY id')->fetchAll(); ?>
<div class="pos">
  <aside class="cats" id="cats">
    <button class="on" data-c="0"><span>🍽️</span>All Items</button>
    <?php foreach($cats as $c): ?><button data-c="<?=$c['id']?>"><span><?=h($c['icon'])?></span><?=h($c['name'])?></button><?php endforeach; ?>
  </aside>
  <section class="menu">
    <input id="q" class="search" placeholder="🔍  Search food items...">
    <div class="grid" id="grid"></div>
  </section>
  <section class="order card">
    <div class="ohead"><h2>Current Order</h2>
      <select id="type"><option>Dine-in</option><option>Takeaway</option><option>Delivery</option></select>
      <select id="pay"><option>Cash</option><option>Card</option><option>UPI</option></select></div>
    <table class="otbl"><thead><tr><th>Item</th><th>Qty</th><th>Price</th><th>Amount</th><th></th></tr></thead><tbody id="lines"></tbody></table>
    <div class="sum">
      <div><span>Subtotal</span><b id="sub"></b></div>
      <div><span>Discount (%)</span><input type="number" id="disc" min="0" max="100" value="<?=h($S['discount'])?>"><b id="dv" class="neg"></b></div>
      <div><span>Tax (%)</span><input type="number" id="tax" min="0" max="100" value="<?=h($S['tax'])?>"><b id="tv"></b></div>
      <div class="total"><span>Total</span><b id="tot"></b></div>
    </div>
    <div class="acts"><button class="btn green" id="save">✓ Complete Order &amp; Save</button><button class="btn" id="print" title="Print last receipt">🖨️</button></div>
  </section>
</div>
<div id="toast"></div>
<script>
const MENU=<?=json_encode($items)?>, CUR='<?=CUR?>';
const cart={}; let cat=0, last=null;
const $=s=>document.querySelector(s), fm=n=>n.toFixed(3)+' '+CUR;
function grid(){const q=$('#q').value.toLowerCase();
 $('#grid').innerHTML=MENU.filter(m=>(!cat||m.cat==cat)&&m.name.toLowerCase().includes(q)).map(m=>
 `<div class="item"><div class="em">${m.emoji}</div><b>${m.name}</b><span>${(+m.price).toFixed(3)} ${CUR}</span><button class="btn" onclick="add(${m.id})">+ Add</button></div>`).join('')
 ||'<p class="muted">No item matches your search.</p>';}
function add(id){cart[id]=(cart[id]||0)+1;draw();}
function chg(id,d){cart[id]+=d;if(cart[id]<1)delete cart[id];draw();}
function del(id){delete cart[id];draw();}
function draw(){let sub=0;
 $('#lines').innerHTML=Object.keys(cart).map(id=>{const m=MENU.find(x=>x.id==id),a=m.price*cart[id];sub+=a;
  return `<tr><td>${m.name}</td><td class="qty"><button onclick="chg(${id},-1)">−</button>${cart[id]}<button onclick="chg(${id},1)">+</button></td><td>${(+m.price).toFixed(3)} ${CUR}</td><td>${a.toFixed(3)} ${CUR}</td><td><button class="x" onclick="del(${id})">🗑</button></td></tr>`}).join('')
  ||'<tr><td colspan="5" class="muted">Tap an item to start an order.</td></tr>';
 const d=sub*(+$('#disc').value||0)/100, af=sub-d, t=af*(+$('#tax').value||0)/100;
 $('#sub').textContent=fm(sub);$('#dv').textContent='- '+fm(d);$('#tv').textContent=fm(t);$('#tot').textContent=fm(af+t);}
$('#cats').onclick=e=>{const b=e.target.closest('button');if(!b)return;
 document.querySelectorAll('#cats button').forEach(x=>x.classList.remove('on'));b.classList.add('on');cat=+b.dataset.c;grid();};
$('#q').oninput=grid;$('#disc').oninput=$('#tax').oninput=draw;
function toast(m,err){const t=$('#toast');t.textContent=m;t.className='show'+(err?' err':'');setTimeout(()=>t.className='',2500);}
$('#save').onclick=async()=>{
 const items=Object.keys(cart).map(id=>({id:+id,qty:cart[id]}));
 if(!items.length)return toast('Add at least one item.',1);
 const r=await fetch('api.php?action=save_order',{method:'POST',headers:{'Content-Type':'application/json'},
  body:JSON.stringify({items,type:$('#type').value,payment:$('#pay').value,discount:+$('#disc').value,tax:+$('#tax').value})}).then(r=>r.json());
 if(!r.ok)return toast(r.error||'Could not save the order.',1);
 last=r.id;for(const k in cart)delete cart[k];draw();toast('Order #'+r.id+' saved.');
 window.open('receipt.php?id='+r.id+'&print=1','_blank','width=420,height=650');};
$('#print').onclick=()=>last?window.open('receipt.php?id='+last+'&print=1','_blank','width=420,height=650'):toast('No order saved yet.',1);
grid();draw();
</script>
<?php include 'inc/footer.php'; ?>
