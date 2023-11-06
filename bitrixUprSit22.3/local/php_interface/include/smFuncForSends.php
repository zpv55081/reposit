<?php

namespace SM;

class FuncForSends
{
    /**
     * Уведомить админов о добавленности новой статьи
     */
    static function newArticleNotify(&$arFields) :void
    {        
        if (($arFields['IBLOCK_ID'] == 5) && ($arFields["RESULT"] !== false)) {
            //добываем email-адреса всех активных админов
            $admins_emails_object = \Bitrix\Main\UserGroupTable::getList(array(
                'filter' => array('GROUP_ID'=>1,'USER.ACTIVE'=>'Y'),            
                'select' => array('EMAIL'=>'USER.EMAIL'),
            ));
            $emails_array = [];
            while ($arGroup = $admins_emails_object->fetch()) {
                if ($validated_email = filter_var($arGroup['EMAIL'], FILTER_VALIDATE_EMAIL)) {
                    $emails_array[] = $validated_email;
                }
            }
            $admins_emails = implode(', ', $emails_array);
            if ($admins_emails != '') {
                //высылаем всем общее письмо
                \Bitrix\Main\Mail\Event::send(array(
                    "EVENT_NAME" => "NEW_ARTICLES_ELEMENT_ADDED",
                    "LID" => "s1",
                    "C_FIELDS" => array(
                        "ADD_USER_LOGIN" => \Bitrix\Main\Engine\CurrentUser::get()->GetLogin(),
                        "ADD_ACTIVE_FROM" => $arFields['ACTIVE_FROM'],
                        "ADD_NAME" => $arFields['NAME'],
                        "RECEPIENTS_EMAILS" => $admins_emails,
                    ),
                ));
            }
        }
    } 
}