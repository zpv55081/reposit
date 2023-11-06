<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Обратная связь");
?>
<?$APPLICATION->IncludeComponent(
	"sm:feedbacktoib", 
	".default", 
	array(
		"EVENT_NAME" => "NEW_MESSAGE_FEEDBACK_TO_IB",
		"IBLOCK_ID" => "6",
		"IBLOCK_TYPE" => "messages",
		"MAIL_NAME" => "35",
		"USE_GCAPTCHA" => "Y",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
