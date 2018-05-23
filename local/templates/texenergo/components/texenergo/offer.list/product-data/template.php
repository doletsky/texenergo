<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if(count($arResult["ITEMS"]) > 0) {

    function datePeriod($date) {
        $oneMinute = 60; //in seconds
        $oneHour = 3600; //in seconds
        $oneDay = 86400; //in seconds
        $periodItog = "";
        
        $period = strtotime($date) - time();
        if($period > 0) {
            $dayItog = floor($period/$oneDay);
            $hourItog = floor(($period%$oneDay)/$oneHour);
            $minuteItog = floor(($period%$oneHour)/$oneMinute);
            
            $periodItog .= "<div class='pDays'>";
            if($dayItog > 0) {
                if($dayItog < 10) {
                    $periodItog .= "0".$dayItog;
                } else {
                    $periodItog .= $dayItog;
                }
            } else {
                $periodItog .= "00";
            }
            $periodItog .= "</div><div class='pHours'>";
            if($hourItog > 0) {
                if($hourItog < 10) {
                    $periodItog .= "0".$hourItog;
                } else {
                    $periodItog .= $hourItog;
                }
            } else {
                $periodItog .= "00";
            }
            $periodItog .= "</div><div class='pMinutes'>";
            if($minuteItog > 0) {
                if($minuteItog < 10) {
                    $periodItog .= "0".$minuteItog;
                } else {
                    $periodItog .= $minuteItog;
                }
            } else {
                $periodItog .= "00";
            }
            $periodItog .= "</div>";
            
        }
        
        return $periodItog;
    }
?>
    <div class="pPromotionBlock">
        <span>До конца акции осталось</span>
        <div class="pTimeBlock">
        <?
        $count = 0;
        foreach($arResult["ITEMS"] as $arItem):?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            if($count == 0) {
                echo datePeriod($arItem['DATE_ACTIVE_TO']);
            }
            $count++;
            ?>
        <?endforeach;?>
        </div>
        <ul>
            <li>Дней</li>
            <li>Часов</li>
            <li>Минут</li>
        </ul>
    </div>
<?php
}
?>
