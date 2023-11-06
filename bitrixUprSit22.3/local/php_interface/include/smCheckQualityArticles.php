<?

namespace SM;

class CheckQualityArticles
{
    static function appriseUnfilledActiveFrom()
    {
        if (\Bitrix\Main\Loader::includeModule('iblock')) {
            $elements_iblo_articles = \Bitrix\Iblock\Elements\ElementArtiTable::getList([
                'select' => ['*'],
                'filter' => ['=ACTIVE' => 'Y', '=ACTIVE_FROM' => NULL],
            ])->fetchAll();
        
            //названия статей, у которых не заполнена дата начала активности
            $articles_unfilled_active_from = [];
            foreach($elements_iblo_articles as $el_iblo) {
                $articles_unfilled_active_from[] = $el_iblo["NAME"];
            }
        
            \CEventLog::Add(array(
                "SEVERITY" => "INFO",
                "AUDIT_TYPE_ID" => "CHECK_UNFILLED",
                "MODULE_ID" => "iblock",
                "ITEM_ID" => '',
                "DESCRIPTION" => "У ". count($articles_unfilled_active_from) . " статей не заполнена дата начала активности",
            ));
            
            if (count($articles_unfilled_active_from) > 0) {
                \Bitrix\Main\Mail\Event::send(array(
                    "EVENT_NAME" => "CONTROL_ARTICLES_UNFILLED_ACTIVE_FROM",
                    "LID" => "s1",
                    "C_FIELDS" => array(
                        "NOTIFICATION_RECEPIENTS" => "za@sm.org",
                        "UNFILLED_ARTICLES" => implode(', ', $articles_unfilled_active_from),
                    ),
                ));
            }
        }

        return "\SM\CheckQualityArticles::appriseUnfilledActiveFrom();";
    }
}