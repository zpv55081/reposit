<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div>
    <?if(!empty($arResult["ERROR_MESSAGE"])){
        foreach($arResult["ERROR_MESSAGE"] as $v)
            ShowError($v);
    }?>
    <form method="POST">
        ФИО: <input type="text" name="UF_FEEDBACK_FIO" value="<?=$arResult['UNFILTERED_POST']['UF_FEEDBACK_FIO']?>">
        <br>
        Телефон: <input type="text" name="UF_FEEDBACK_PHONE" value="<?=$arResult['UNFILTERED_POST']['UF_FEEDBACK_PHONE']?>">
        <br>
        Сообщение: <textarea name="UF_FEEDBACK_MESSAGE"><?=$arResult['UNFILTERED_POST']['UF_FEEDBACK_MESSAGE']?></textarea>
        <br>
        Дополнительно: 
        <select name="UF_INFO">
            <option value="good" <?php if($arResult['UNFILTERED_POST']['UF_INFO'] == 'good'){echo ' selected';}?>>хорошо</option>
            <option value="bad" <?php if($arResult['UNFILTERED_POST']['UF_INFO'] == 'bad'){echo ' selected';}?>>плохо</option>
        </select>
        <br>
        Дополнительно2: <input type="text" name="UF_FEEDBACK_INFO" value="<?=$arResult['UNFILTERED_POST']['UF_FEEDBACK_INFO']?>">
        <br>
        <?if($arParams["USE_GCAPTCHA"] == "Y"):?>
        <div class="g-recaptcha" data-sitekey="<?=GOOGLE_SITE_KEY?>"></div>
        <?endif;?>
        <input type="submit" value="Отправить">
    </form>
</div>
<br>