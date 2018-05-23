<?php

class CAeroCatalogPanel extends CBitrixComponent
{

    public function onPrepareComponentParams($arParams)
    {
        //prepare params
        return $arParams;
    }

    public function executeComponent()
    {

        if (!CModule::IncludeModule("sale")) {
            ShowError("Не установлен модуль sale");
            return;
        }

        // переменные шаблона
        $this->arResult['FAVORITES'] = getGoodsIdsInFavorites();
        $this->arResult['RECENT'] = getGoodsIdsRecentViewed();
        $this->arResult['COMPARE'] = getGoodsIdsInCompareList();

        $this->includeComponentTemplate();
    }

}