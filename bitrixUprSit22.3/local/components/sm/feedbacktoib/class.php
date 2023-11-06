<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;

class FeedbackToIb extends CBitrixComponent
{     
    private function checkCaptcha() {
        $recaptchaSecret = GOOGLE_SECRET_KEY;

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $url .= '?secret='.$recaptchaSecret.'&response='.$_POST["g-recaptcha-response"];

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response, true);
    
        if($response['success'] != true) {        
            $this->arResult['ERROR_MESSAGE'][] = "Вы не прошли проверку на робота!";
        }
    }

    public function executeComponent() {
        
        $this->arResult['ERROR_MESSAGE'] = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST" && CModule::IncludeModule("iblock")) {
            $this->arResult['UNFILTERED_POST'] = \Bitrix\Main\Context::getCurrent()->getRequest()->getPostList()->toArray();
            foreach ($this->arResult['UNFILTERED_POST'] as $k => $v) {
                if (substr($k, 0, 12) === 'UF_FEEDBACK_') {
                    $this->arResult['FILTERED_POST'][$k] = $v;
                }
            } 
            
            if($this->arParams["USE_GCAPTCHA"] == "Y") {
                $this->checkCaptcha();
            }

            if (empty($this->arResult['ERROR_MESSAGE'])) {           
                global $USER;
                $arLoadMessageArray = [
                    "MODIFIED_BY"    => $USER->GetID(),
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID"      => $this->arParams["IBLOCK_ID"],
                    "PROPERTY_VALUES"=> $this->arResult['FILTERED_POST'],
                    "NAME"           => "Сообщение обратной связи " . $this->arResult['FILTERED_POST']['UF_FEEDBACK_FIO'],
                    "ACTIVE"         => "Y",
                ];
                
                $el = new CIBlockElement;
                //если новый элемент добавился
                if ($el->Add($arLoadMessageArray)) {
                    //отправляем E-MAIL уведомление
                    \Bitrix\Main\Mail\Event::send(array(
                        "EVENT_NAME" => $this->arParams["EVENT_NAME"],
                        "MESSAGE_ID" => $this->arParams["MAIL_NAME"],
                        "LID" => SITE_ID,
                        "C_FIELDS" => $this->arResult['FILTERED_POST'],
                    ));
                    //очищаем ранее вошедшую из формы информацию
                    $this->arResult['UNFILTERED_POST'] = [];
                } else {
                    $this->arResult['ERROR_MESSAGE'][] = $el->LAST_ERROR;
                }
            }        
        }
        
        $this->includeComponentTemplate();
    }
}
