<div style="width:480px;margin:auto;padding:5px;border: 1px solid #aa7740; background: #edcc80; color: #363636;">
  <?= $text; ?>
</div>
<script>
  t = <?= $time ?>;

  function dorefresh() {
    ti = setTimeout("dorefresh();", 1000);
    if (t > 0) {
      t -= 1;
    } else {
      clearTimeout(ti);
      window.location = '<?= $link ?>';
    }
  }
  window.onLoad = dorefresh();
</script>
<br> <center>
<a href='<?= $link ?>'><button class="button-grey" style="cursor: pointer; width: 300x;">Продолжить
<b>  (<span id="countdowntimer"><?= $time ?> </span>)</b>
</button></a>
</center>
<script type="text/javascript">
  var timeleft = <?= $time ?>;
  var downloadTimer = setInterval(function(){
  timeleft--;
  document.getElementById("countdowntimer").textContent = timeleft;
  if(timeleft <= 0)
    clearInterval(downloadTimer);
  },1000);
</script>
