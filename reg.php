<?php
mb_internal_encoding('UTF-8'); //"установка внутренней кодировки скрипта"
//символ: "И" - для правильного определения кодировки Эдитрой
echo <<<_nachTegi_
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title> Регистратор в магазина </title>
</head>
<body>
_nachTegi_;  

//функция формы запроса и приема реквизитов
function forma_poluch_auth() {echo <<<_HTML_
<form action='$_SERVER[PHP_SELF]' method='POST'>
<p> ДОБАВИТЬ ПОЛЬЗОВАТЕЛЯ В БД <p>
ЛОГИН: <input type='text' name='znach_iz_f_login_polz'>
ПАРОЛЬ: <input type='text' name='znach_iz_f_parol_polz'>
ИМЯ/ФАМИЛИЯ: <input type='text' name='znach_iz_f_firstname'>
ПРОЧЕЕ: <input type='text' name='znach_iz_f_other'>
<input type='submit' value='ДОБАВИТЬ'>
</form>
_HTML_;};

forma_poluch_auth();

/*!!!!!!!!!!!!!! нужно дообезопасить принимаемые из формы значения, 
исключать совпадения имён!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $forlogin = $_POST['znach_iz_f_login_polz'];
    $forfname = $_POST['znach_iz_f_firstname'];
    $forother = $_POST['znach_iz_f_other'];
    $soedbd = new mysqli('127.0.0.1', 'root', '', 'nirshop');
    $forpw=(mysqli_real_escape_string($soedbd,$_POST['znach_iz_f_parol_polz']));
        $pwhash = password_hash($forpw, PASSWORD_DEFAULT, 
        ['cost'=>16]); //тормозит ~ на 3 секунды, кост >18 exceeded ожидание
    if (!$soedbd->connect_errno){
        $soedbd->set_charset('utf8');
        $zapr = $soedbd->query ("INSERT INTO users VALUES('$forlogin',
        '$pwhash','$forfname','$forother')");
        $soedbd->close();
    }
}
?>