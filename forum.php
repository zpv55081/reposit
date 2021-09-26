<?php
/*mb_internal_encoding('UTF-8'); //"установка внутренней кодировки скрИпта"
/*setcookie('nirshopcn', 'kod0', time()+60*1, '/', '127.0.0.1', false, false);*/
//вышескрытое уже указано на родительской странице??????????????????????????????????????????????????????????
session_start(); //для работы сессии на нескольких страницах должна быть настроена директива конфигураций???
/*ini_set('session.gc_maxlifetime',300);//время без риска "мусорозабора" сессии*/
echo <<<_nachTegi_
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title> Отзывы и предложения </title>
</head>
<body>
_nachTegi_;

//функция формы ввода автора и текста сообщения
function forma_avtor_tekst() {echo <<<_HTML_
<form action='$_SERVER[PHP_SELF]' method='POST'>
Ваше имя: <input type='text' placeholder='
_HTML_;
//автоподстановка логина в строку имени
if (isset ($_SESSION['auth'])) {
    echo $_SESSION['firstname'];
    }
echo <<<_HTML_
' name='znach_iz_f_avtor'>
<br>
<textarea name='znach_iz_f_tekst' cols= "33" rоws="4"> </textarea>
<br> <input type='submit' value="ОТПРАВИТЬ">
</form>
_HTML_;};
forma_avtor_tekst();
//функция выявления ошибок написания имени - можно удалить/разнести на php и js
function osh_napis_login_polz(){
/*    echo  "Vyp f-iya: " . __FUNCTION__ . "<br>";////////для отладки/////////*/
    $pretenzii_k_login = array();
    //ниже преобразование введенных символов в сущности и контроль длины логина
    if (strlen(htmlentities($_POST['znach_iz_f_avtor']))<3) {
        $pretenzii_k_login[] = "<b>имя должно быть подлиннее</b>";
        }/*здесь место для дополнительных проверок корректности ввода имени*/
    if ($pretenzii_k_login){
        print '<u>Исправьте ошибки:</u> <ul><li>';
        print implode ('</li><li>', $pretenzii_k_login);
        print ' </li></ul>';
        }    
    return $pretenzii_k_login;
};

//обработка и сохранение данных формы
if ($_SERVER['REQUEST_METHOD']=='POST'){
/*    if ($_POST['znach_iz_f_avtor'])=0*/ //при незаполненом поле имени при наличии авторизации..см.ТЗ 
    $avtor_ekr = htmlentities($_POST['znach_iz_f_avtor']);
    $tekst_ekr = htmlentities($_POST['znach_iz_f_tekst']);
    //игнор @шибки не раскрывает сведения в случае лежачей sql
    @$soedbd = new mysqli ('127.0.0.1', 'root', '', 'regin'); 
    if (!$soedbd->connect_errno) {
        $soedbd ->set_charset ('utf8');
        $avtor_sqlekr = mysqli_real_escape_string($soedbd, $avtor_ekr);
        $tekst_sqlekr = mysqli_real_escape_string($soedbd, $tekst_ekr);
        $zapr = $soedbd->query ("INSERT INTO `forum` (`id`, `avtor`, `tekst`, 
                                `data-vremya`, `moder`) VALUES (NULL, 
                                '$avtor_sqlekr', '$tekst_sqlekr',
                                 CURRENT_TIMESTAMP,'no')");
        $soedbd->close();
    }
    else echo '<p><b>Произошла ошибка сбд</b></p>'; 
}

//вывод имеющихся отмодерированных сообщений на страницу
$soedbd = new mysqli ('127.0.0.1', 'root', '', 'regin'); 
    if (!$soedbd->connect_errno) {
        $soedbd ->set_charset ('utf8');
        $zaprbd = $soedbd->query("SELECT * FROM `forum` WHERE 
                                    `moder` = 'yes' OR `moder`='no'");           
 $zaprbd->data_seek(0);// перемещает внутренний указатель на указанную строку

            while ($itog = $zaprbd->fetch_array()){
            echo $itog[3].' '.$itog['1'].': '.$itog['2'].' '.'<br>';
            };
        $soedbd->close();
    }
    else echo '<p><b>Произошла ошибка сбд</b></p>';

echo '<hr>';//---------------------------------------------------
echo '<br> $_COOKIE: '; print_r ($_COOKIE);/////для отладки///////
echo "<br>" . "<br>";////////////////////////////////////////////
echo '$_SESSION : '; print_r($_SESSION);////////////////////////
echo "<br>" . "<br>";//////////////////////////////////////////
echo '$_POST : '; print_r($_POST);////////////////////////
echo "<br>" . "<br>";//////////////////////////////////////////
?>
</body>
</html>