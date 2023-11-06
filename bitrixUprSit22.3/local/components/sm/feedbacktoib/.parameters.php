<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (!CModule::IncludeModule("iblock")) {
	ShowMessage(GetMessage("IBLOCK_ERROR"));
	return false;
}

// Получение списка типов инфоблоков
$dbIBlockTypes = CIBlockType::GetList(array("SORT"=>"ASC"), array("ACTIVE"=>"Y"));
while ($arIBlockTypes = $dbIBlockTypes->GetNext()) {
	$paramIBlockTypes[$arIBlockTypes["ID"]] = $arIBlockTypes["ID"];
}

// Получение списка инфоблоков заданного типа
$dbIBlocks = CIBlock::GetList(
	array("SORT" => "ASC"),
	array("ACTIVE" =>  "Y",	"TYPE" => $arCurrentValues["IBLOCK_TYPE"])
);
while ($arIBlocks = $dbIBlocks->GetNext()) {
	$paramIBlocks[$arIBlocks["ID"]] = "[" . $arIBlocks["ID"] . "] " . $arIBlocks["NAME"];
}

//получение списка почтовых событий
$eventList = CEventType::GetList();


$paramsEventsType[] = "Не отправлять";
while($event = $eventList->fetch()) {
	$paramsEventsType[$event['EVENT_NAME']] = "[".$event["LID"]."] ".$event["EVENT_NAME"];
}

$mailTemplatesList = CEventMessage::GetList($by="site_id", $order="desc", array("EVENT_NAME" => array($arCurrentValues["EVENT_NAME"])));

while($mailTemplate = $mailTemplatesList->fetch()) {
	$paramsMailTemplates[$mailTemplate['ID']] = $mailTemplate["SUBJECT"];
}

// Формирование массива параметров
$arComponentParameters = array(
	"GROUPS" => array(),
	"PARAMETERS" => array(
		"EVENT_NAME" =>  array(
				"PARENT"    =>  "BASE",
				"NAME"      =>  "Тип почтового события",
				"TYPE"      =>  "LIST",
				"VALUES"    =>  $paramsEventsType,
				"REFRESH"   =>  "Y",
				"MULTIPLE"  =>  "N",
		),
		"MAIL_NAME" =>  array(
				"PARENT"    =>  "BASE",
				"NAME"      =>  "Почтовый шаблон",
				"TYPE"      =>  "LIST",
				"VALUES"    =>  $paramsMailTemplates,
				"REFRESH"   =>  "Y",
				"MULTIPLE"  =>  "N",
		),
		"IBLOCK_TYPE"   =>  array(
				"PARENT"    =>  "BASE",
				"NAME"      =>  "Тип инфоблока для сохранения заявок",
				"TYPE"      =>  "LIST",
				"VALUES"    =>  $paramIBlockTypes,
				"REFRESH"   =>  "Y",
				"MULTIPLE"  =>  "N",
		),
		"IBLOCK_ID" =>  array(
				"PARENT"    =>  "BASE",
				"NAME"      =>  "Инфоблок, куда сохраняются заявки",
				"TYPE"      =>  "LIST",
				"VALUES"    =>  $paramIBlocks,
				"REFRESH"   =>  "Y",
				"MULTIPLE"  =>  "N",
		),
		"USE_GCAPTCHA" =>  array(
				"PARENT"    =>  "ADDITIONAL_SETTINGS",
				"NAME"      =>  "Использовать Google Re-captcha",
				"TYPE"      =>  "CHECKBOX",
				"DEFAULT"   => "Y",
		),
	),
);