<?php
//формирование ссылки для будущей переадресации
$redirect_linkkk = str_replace( 
   "page=as_co_sending_emails&",
   "/lv/academy/choose_competencies/profiles_filters_submit?",
   $_SERVER['QUERY_STRING']
);
$redirect_link = strstr($redirect_linkkk, '&aciuc=', true);

if (!$redirect_link)
exit;

$razdel='admin';
$dopusk = dopusk();

if ($dopusk < 2) {
    my_mesage("У Вас недостаточно прав для доступа к разделу");
  include ("config/down.ph_");
  exit;
}

//преобразование кодированных данных из get запроса в строки/массивы
$assigned_сompetencies_ids_string = urldecode($_GET['aciuc']);
$au_fio = urldecode($_GET['fiouc']);
$recipients_string = urldecode($_GET['recipientsuc']);
$recipients = explode(', ', $recipients_string);

$sql_competencies = "SELECT 
   competencies.*,
   courses.name as course_name
FROM
   competencies
LEFT JOIN
   courses ON courses.id = competencies.course_id
WHERE
   competencies.id IN ($assigned_сompetencies_ids_string)
";

$desc_sql_competencies = mysql_query($sql_competencies, $db);

require_once 'tpl/academy/as_co_sending_emails.tpl.php';
