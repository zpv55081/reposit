<?php
/*mb_internal_encoding('UTF-8'); //"установка внутренней кодировки скрипта"
/*ini_set('session.gc_maxlifetime',300);//время без риска "мусорозабора" сессии
session_start(); /*для работы сессии на нескольких страницах должна быть 
настроена директива конфигураций*/ 
/*setcookie('nirshopcn', 'kod0', time()+60*1, '/', '127.0.0.1', false, false);*/
//вышескрытое уже указано на родительской странице
//символ: "И" - для правильного определения кодировки Эдитрой
echo <<<_nachTegi_
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title> Авторизатор магазина </title>
</head>
<body>
_nachTegi_;  

//функция формы запроса и приема имени пользователя и пароля
function forma_poluch_auth() {echo <<<_HTML_
<form action='$_SERVER[PHP_SELF]' method='POST'>
ЛОГИН: <input type='text' name='znach_iz_f_login_polz'>
ПАРОЛЬ: <input type='password' name='znach_iz_f_parol_polz'>
<input type='submit' value='Вход'>
</form>
_HTML_;};

//функция выявления ошибок написания логина(разнести на php и js)
function osh_napis_login_polz(){
/*    echo  "Vyp f-iya: " . __FUNCTION__ . "<br>";////////для отладки/////////*/
    $pretenzii_k_login = array();
    //ниже преобразование введенных символов в сущности и контроль длины логина
    if (strlen(htmlentities($_POST['znach_iz_f_login_polz']))<4) {
        $pretenzii_k_login[] = "<b>логин должен быть подлиннее</b>";
        }/*здесь место для дополнительных проверок корректности ввода логина*/
    if ($pretenzii_k_login){
        print '<u>Исправьте ошибки:</u> <ul><li>';
        print implode ('</li><li>', $pretenzii_k_login);
        print ' </li></ul>';
        }    
    return $pretenzii_k_login;
};

//логика работы с формой
if (!isset ($_SESSION['auth'])){
    forma_poluch_auth()
;}
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $vyyavl_osh = osh_napis_login_polz();
    if (!$vyyavl_osh) {
        @$soedbd = new mysqli('127.0.0.1', 'root', '', 'nirshop');
        if (!$soedbd->connect_errno){
            $soedbd->set_charset('utf8');
            $login_ekr = mysqli_real_escape_string($soedbd,
                                              $_POST['znach_iz_f_login_polz']);
            $zapr = $soedbd->query 
            ("SELECT * FROM `users` WHERE login='$login_ekr'");
            $nepr_log_par = '<p><b>Неправильный ЛОГИН/ПАРОЛЬ</b></p>';
            if (($zapr->num_rows) !== 1){
                $zapr->free();
                $soedbd->close();
                echo $nepr_log_par;
            }
            else {
                $arr_iz_zapr = $zapr->fetch_array(MYSQLI_ASSOC);
                $zapr->free();//очищает результат запроса
                $ekr_post_pw = mysqli_real_escape_string($soedbd,
                $_POST['znach_iz_f_parol_polz']);
                $soedbd->close();
                $hpassw = $arr_iz_zapr['password'];
                if (!password_verify($ekr_post_pw, $hpassw)){
                    echo $nepr_log_par;
                }
                else {
                    $_SESSION['auth'] = true;
                    $_SESSION['firstname'] = $arr_iz_zapr['firstname'];
                    echo '<meta http-equiv="refresh"content="0;
                    URL=/shop/index.php">';
                }
            }
        }
        else echo '<p><b>Произошла ошибка. Повторите попытку позднее.</b></p>';
    }
}
/*echo '<hr>';//---------------------------------------------------
echo '<br> $_COOKIE: '///////////////////для отладки/////////////
; print_r ($_COOKIE);////////////////////////////////////////////
echo "<br>" . "<br>";////////////////////////////////////////////
echo '$_SESSION : '; print_r($_SESSION);/////////////////////////
echo "<br>" . "<br>";//////////////////////////////////////////*/
?>
</body>
</html>