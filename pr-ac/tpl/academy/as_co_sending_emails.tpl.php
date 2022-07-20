<?php
require_once 'config/classes/mail.php';
use Mail\Mail;

$notice = "Сотруднику ".$au_fio." назначено обучение: <ul>";
while ($сompetence = mysql_fetch_assoc($desc_sql_competencies)) {
   $notice .= " <li><p>{$сompetence['description']} ({$сompetence['course_name']}), <br>
   ссылка на учебный курс: {$сompetence['course_link']} </p></li> ";
}
$notice .= "</ul>";

$message = '<p>Добрый день!</p>' .
   $notice .
   '<p>За подробной информацией можете обратиться к сотруднику Учебного центра.</p>';

$subject = 'Учебный центр - уведомление';

Mail::sendAll($recipients, $subject, $message);

$text = $notice.'<br><i> Произведено email уведомление на '. implode(', ', $recipients).'</i>';
include("tpl/message.tpl.php");

$redirect_time = 15;
myredirect($redirect_link, $redirect_time);

?>
<br> <center>
<a href='<?= $redirect_link ?>'><button class="button-grey" style="cursor: pointer; width: 300x;">Продолжить
<b>  (<span id="countdowntimer"><?= $redirect_time ?> </span>)</b>
</button></a>
</center>
<script type="text/javascript">
  var timeleft = <?= $redirect_time ?>;
  var downloadTimer = setInterval(function(){
  timeleft--;
  document.getElementById("countdowntimer").textContent = timeleft;
  if(timeleft <= 0)
    clearInterval(downloadTimer);
  },1000);
</script>
