<?php
mb_internal_encoding('UTF-8'); //"установка внутренней кодировки скрипта"
setcookie ('nirshopcn', 'kod0', time()+30*1, '/', '127.0.0.1', false, false);  
session_start();

echo <<<_nachTegi
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title> Магазин </title>
</head>
<body>
_nachTegi;//символ "И" - для правильного определения кодировки Эдитрой,   

if(isset($_SESSION['auth'])){
    echo '<p>Здравствуйте, '.$_SESSION['firstname'].'!
    <a href="http://127.0.0.1/unset.php?'.$_SERVER['QUERY_STRING'].'"a> 
    выйти</a><p>';
}
else { 
    echo '<p><i>Для получения расширенных возможностей рекомендуем 
    зарегистрироваться или авторизоваться.</i><p>';
    include_once "auth.php";
}
echo '<hr>';
echo '<b> ЭТО НАШ МАГАЗИН</b>';///// это место для основной информации
echo '<br><br>';
echo '<a href="http://127.0.0.1/catalog73.php"> Каталог с БД АКБ </a>';
?>

<hr>

<p>Thanks for visiting &copy;</p>

<?php echo '<br> $_COOKIE: '//////////для отладки////////////////
; print_r ($_COOKIE);////////////////////////////////////////////
echo "<br>" . "<br>";////////////////////////////////////////////
echo '$_SESSION : '; @print_r($_SESSION);/////////////////////////
echo "<br>" . "<br>";////////////////////////////////////////////
?>
</body>
</html>