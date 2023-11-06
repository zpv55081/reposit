<?php

define("GOOGLE_SITE_KEY", '6Lc9EBQnAAAAABuNQZAjMPPB8J3b3Jpyk1mQf');
define("GOOGLE_SECRET_KEY", '6Lc9EBQnAAAAAMhi8m5UqL1gei-mO2ZW8f9WE');

require_once 'include/smCheckQualityArticles.php';

require_once 'include/smFuncForSends.php';
\Bitrix\Main\EventManager::getInstance()->AddEventHandler(
    'iblock',
    'OnAfterIBlockElementAdd',
    array('SM\FuncForSends', 'newArticleNotify')
);

require_once 'include/smArticlesCorrector.php';
\Bitrix\Main\EventManager::getInstance()->AddEventHandler(
    'iblock',
    'OnBeforeIBlockElementAdd',
    array('SM\ArticlesCorrector', 'translitAddDateSimbolCode')
);

?>
