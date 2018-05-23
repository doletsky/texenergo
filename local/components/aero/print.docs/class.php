<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use \Bitrix\Main;
use \Bitrix\Main\SystemException as SystemException;

class CPrintDocsComponent extends CBitrixComponent {

    protected function checkModules() {
        if(!Main\Loader::includeModule('iblock')) {
            throw new Exception('iblock module not installed');
        }

        if(!Main\Loader::includeModule('sale')) {
            throw new Exception('sale module not installed');
        }

    }

    /**
     * Extract data from cache. No action by default.
     * @return bool
     */
    protected function extractDataFromCache() {

    }

    protected function putDataToCache() {

    }

    protected function abortDataCache() {

    }

    protected function prepareData() {

        if(!isset($_GET['orderId'])) {
            throw new Main\ArgumentException("Не указан ID заказа");
        }

        $accId = $_GET['orderId'];
        $userID = CUser::GetID();
        $arFilter = array("USER_ID"        => $userID,
                          "ACCOUNT_NUMBER" => $accId,);

        $dbOrder = CSaleOrder::GetList(Array("ID" => "ASC"), $arFilter, false, false);
        if($arOrder = $dbOrder->GetNext()) {
            $orderId = $arOrder["ID"];
        }
        else {
            throw new Main\Security\SecurityException("Нет доступа");
        }

        $arFilter = array("USER_ID" => $userID,
                          "ID"      => $orderId,);

        $arOrder = CSaleOrder::GetList(Array("ID" => "ASC"), $arFilter)->GetNext();

        if($arOrder) {
            $this->arResult = $arOrder;

            $dbBasket = CSaleBasket::GetList(array("NAME" => "ASC"), array("ORDER_ID" => $orderId), false, false,
                                             array("ID",
                                                   "DETAIL_PAGE_URL",
                                                   "NAME",
                                                   "NOTES",
                                                   "QUANTITY",
                                                   "PRICE",
                                                   "CURRENCY",
                                                   "PRODUCT_ID",
                                                   "DISCOUNT_PRICE",
                                                   "WEIGHT",
                                                   "CATALOG_XML_ID",
                                                   "VAT_RATE",
                                                   "PRODUCT_XML_ID",
                                                   "TYPE",
                                                   "SET_PARENT_ID"));
            $this->arResult["BASKET"] = Array();
            while($arBasket = $dbBasket->Fetch()) {
                if(CSaleBasketHelper::isSetItem($arBasket)) {
                    continue;
                }

                $productsIds[] = $arBasket['PRODUCT_ID'];
                $arBasketTmp = Array();
                $arBasketTmp = $arBasket;
                $arBasketTmp["QUANTITY"] = DoubleVal($arBasketTmp["QUANTITY"]);

                $arBasketTmp["PRICE_FORMATED"] = SaleFormatCurrency($arBasket["PRICE"], $arBasket["CURRENCY"]);
                if(DoubleVal($arBasketTmp["DISCOUNT_PRICE"]) > 0) {
                    $arBasketTmp["DISCOUNT_PRICE_PERCENT"] = $arBasketTmp["DISCOUNT_PRICE"] * 100
                                                             / ($arBasketTmp["DISCOUNT_PRICE"] + $arBasketTmp["PRICE"]);
                    $arBasketTmp["DISCOUNT_PRICE_PERCENT_FORMATED"] =
                        roundEx($arBasketTmp["DISCOUNT_PRICE_PERCENT"], SALE_VALUE_PRECISION)."%";
                }

                $arBasketTmp["PROPS"] = Array();
                $dbBasketProps = CSaleBasket::GetPropsList(array("SORT" => "ASC",
                                                                 "ID"   => "DESC"),
                                                           array("BASKET_ID" => $arBasketTmp["ID"],
                                                                 "!CODE"     => array("CATALOG.XML_ID",
                                                                                      "PRODUCT.XML_ID")), false, false,
                                                           array("ID",
                                                                 "BASKET_ID",
                                                                 "NAME",
                                                                 "VALUE",
                                                                 "CODE",
                                                                 "SORT"));
                while($arBasketProps = $dbBasketProps->GetNext()) {
                    $arBasketTmp["PROPS"][] = $arBasketProps;
                }
                $this->arResult["BASKET"][] = $arBasketTmp;
            }

            $rsProducts = CIBlockElement::GetList(array(), array('ID' => $productsIds), false, false);
            while($obProduct = $rsProducts->GetNextElement()) {
                $arProduct = $obProduct->GetFields();
                $arProduct['PROPERTIES'] = $obProduct->GetProperties();
                $this->arResult['PRODUCTS'][$arProduct['ID']] = $arProduct;
            }
        }
        $arUser = CUser::GetByID($userID)->GetNext();
        $this->arResult['CMS_USER'] = $arUser;
        if($arUser && $arUser['UF_COMPANY_ID']) {
            $obConrtagent = CIBlockElement::GetList(array(), array('ID' => $arUser['UF_COMPANY_ID']), false, false)
                                          ->GetNextElement();
            if($obConrtagent) {
                $arContragent = $obConrtagent->GetFields();
                $arContragent['PROPERTIES'] = $obConrtagent->GetProperties();
                $this->arResult['USER'] = $arContragent;
            }
        }

        $wholeSaleData = getBasketPriceMarkup();
        if(!empty($wholeSaleData)){
            $this->arResult = array_merge($this->arResult, $wholeSaleData);
        }
    }

    public function executeComponent() {
        try {
            $this->checkModules();

            if(!$this->extractDataFromCache()) {
                $this->prepareData();
                $this->includeComponentTemplate();
                $this->putDataToCache();
            }
        }
        catch(SystemException $e) {
            $this->abortDataCache();
            ShowError($e->getMessage());
        }
    }
}