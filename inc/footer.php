</main>
<script>
function tick(){const n=new Date();
 d.textContent=n.toLocaleDateString('en-GB',{weekday:'long',day:'2-digit',month:'short',year:'numeric'});
 t.textContent=n.toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'});}
tick();setInterval(tick,30000);
</script></body></html>
