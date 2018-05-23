<?php
$arResult[ "BASE_LANG_CURRENCY" ] = CSaleLang::GetLangCurrency (SITE_ID);

if ($arResult[ "CurrentStep" ] > 0 && $arResult[ "CurrentStep" ] <= 6) {
    if ($arResult[ "PAY_CURRENT_ACCOUNT" ] != "N" && $arParams[ "ALLOW_PAY_FROM_ACCOUNT" ] == "Y")
        $arResult[ "PAY_CURRENT_ACCOUNT" ] = "Y";
        
        // <***************** BEFORE 1 STEP
    $arResult[ "ORDER_PRICE" ] = 0;
    $arResult[ "ORDER_WEIGHT" ] = 0;
    $bProductsInBasket = False;
    $arResult[ "bUsingVat" ] = "N";
    $arResult[ "vatRate" ] = 0;
    $arResult[ "vatSum" ] = 0;
    $arProductsInBasket = array ();
    $DISCOUNT_PRICE_ALL = 0;
    CSaleBasket::UpdateBasketPrices (CSaleBasket::GetBasketUserID (), SITE_ID);
    $dbBasketItems = CSaleBasket::GetList (array (
        "NAME" => "ASC"
    ), array (
        "FUSER_ID" => CSaleBasket::GetBasketUserID (), "LID" => SITE_ID, "ORDER_ID" => "NULL"
    ), false, false, array (
        "ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY", "CAN_BUY", "PRICE", "WEIGHT", "NAME", "DISCOUNT_PRICE", "VAT_RATE"
    ));
    while ( $arBasketItems = $dbBasketItems->GetNext () ) {
        if ($arBasketItems[ "DELAY" ] == "N" && $arBasketItems[ "CAN_BUY" ] == "Y") {
            $arBasketItems[ "PRICE" ] = roundEx ($arBasketItems[ "PRICE" ], SALE_VALUE_PRECISION);
            $arBasketItems[ "QUANTITY" ] = DoubleVal ($arBasketItems[ "QUANTITY" ]);
            $arBasketItems[ "WEIGHT" ] = DoubleVal ($arBasketItems[ "WEIGHT" ]);
            $arBasketItems[ "WEIGHT_FORMATED" ] = roundEx (DoubleVal ($arBasketItems[ "WEIGHT" ] / $arResult[ "WEIGHT_KOEF" ]), SALE_WEIGHT_PRECISION) . " " . $arResult[ "WEIGHT_UNIT" ];
            $arBasketItems[ "VAT_RATE" ] = DoubleVal ($arBasketItems[ "VAT_RATE" ]);
            //$arBasketItems["DISCOUNT_PRICE"] = roundEx($arBasketItems["DISCOUNT_PRICE"], SALE_VALUE_PRECISION);
            

            $DISCOUNT_PRICE_ALL += $arBasketItems[ "DISCOUNT_PRICE" ] * $arBasketItems[ "QUANTITY" ];
            
            $arResult[ "ORDER_PRICE" ] += $arBasketItems[ "PRICE" ] * $arBasketItems[ "QUANTITY" ];
            $arResult[ "ORDER_WEIGHT" ] += $arBasketItems[ "WEIGHT" ] * $arBasketItems[ "QUANTITY" ];
            if (DoubleVal ($arBasketItems[ "VAT_RATE" ]) > 0) {
                
                $arResult[ "bUsingVat" ] = "Y";
                if ($arBasketItems[ "VAT_RATE" ] > $arResult[ "vatRate" ])
                    $arResult[ "vatRate" ] = $arBasketItems[ "VAT_RATE" ];
                    
                    //$arBasketItems["VAT_VALUE"] = roundEx((($arBasketItems["PRICE"] / ($arBasketItems["VAT_RATE"] +1)) * $arBasketItems["VAT_RATE"]), SALE_VALUE_PRECISION);
                $arBasketItems[ "VAT_VALUE" ] = (($arBasketItems[ "PRICE" ] / ($arBasketItems[ "VAT_RATE" ] + 1)) * $arBasketItems[ "VAT_RATE" ]);
                $arResult[ "vatSum" ] += roundEx ($arBasketItems[ "VAT_VALUE" ] * $arBasketItems[ "QUANTITY" ], SALE_VALUE_PRECISION);
            }
            $arBasketItems[ "PRICE_FORMATED" ] = SaleFormatCurrency ($arBasketItems[ "PRICE" ], $arBasketItems[ "CURRENCY" ]);
            
            $arProductsInBasket[ ] = $arBasketItems;
            $bProductsInBasket = true;
        }
    }
    
    if (! $bProductsInBasket) {
        LocalRedirect ($arParams[ "PATH_TO_BASKET" ]);
        $arResult[ "ERROR_MESSAGE" ] .= GetMessage ("SALE_BASKET_EMPTY");
    }
    
    // DISCOUNT
    $countProdInBaket = count ($arProductsInBasket);
    for ($i = 0; $i < $countProdInBaket; $i ++)
        $arProductsInBasket[ $i ][ "DISCOUNT_PRICE" ] = DoubleVal ($arProductsInBasket[ $i ][ "PRICE" ]);
    
    $arMinDiscount = array ();
    $allSum = 0;
    foreach ($arProductsInBasket as &$arResultItem) {
        $allSum += ($arResultItem[ "PRICE" ] * $arResultItem[ "QUANTITY" ]);
    }
    $dblMinPrice = $allSum;
    
    $dbDiscount = CSaleDiscount::GetList (array (
        "SORT" => "ASC"
    ), array (
        "LID" => SITE_ID, "ACTIVE" => "Y", "!>ACTIVE_FROM" => Date ($DB->DateFormatToPHP (CSite::GetDateFormat ("FULL"))), 
        "!<ACTIVE_TO" => Date ($DB->DateFormatToPHP (CSite::GetDateFormat ("FULL"))), "<=PRICE_FROM" => $arResult[ "ORDER_PRICE" ], 
        ">=PRICE_TO" => $arResult[ "ORDER_PRICE" ], "USER_GROUPS" => $USER->GetUserGroupArray ()
    ), false, false, array (
        "*"
    ));
    $arResult[ "DISCOUNT_PRICE" ] = 0;
    $arResult[ "DISCOUNT_PERCENT" ] = 0;
    $arDiscounts = array ();
    
    while ( $arDiscount = $dbDiscount->Fetch () ) {
        $dblDiscount = 0;
        $allSum_tmp = $allSum;
        
        if ($arDiscount[ "DISCOUNT_TYPE" ] == "P") {
            if ($arParams[ "COUNT_DISCOUNT_4_ALL_QUANTITY" ] == "Y") {
                foreach ($arProductsInBasket as &$arBasketItem) {
                    $curDiscount = roundEx ($arBasketItem[ "PRICE" ] * $arBasketItem[ "QUANTITY" ] * $arDiscount[ "DISCOUNT_VALUE" ] / 100, SALE_VALUE_PRECISION);
                    $dblDiscount += $curDiscount;
                }
            } else {
                foreach ($arProductsInBasket as &$arBasketItem) {
                    $curDiscount = roundEx ($arBasketItem[ "PRICE" ] * $arDiscount[ "DISCOUNT_VALUE" ] / 100, SALE_VALUE_PRECISION);
                    $dblDiscount += $curDiscount * $arBasketItem[ "QUANTITY" ];
                }
            }
        } else {
            $dblDiscount = roundEx (CCurrencyRates::ConvertCurrency ($arDiscount[ "DISCOUNT_VALUE" ], $arDiscount[ "CURRENCY" ], $arResult[ "BASE_LANG_CURRENCY" ]), SALE_VALUE_PRECISION);
        }
        
        $allSum = $allSum - $dblDiscount;
        if ($dblMinPrice > $allSum) {
            $dblMinPrice = $allSum;
            $arMinDiscount = $arDiscount;
        }
        $allSum = $allSum_tmp;
    }
    
    if (! empty ($arMinDiscount)) {
        if ($arMinDiscount[ "DISCOUNT_TYPE" ] == "P") {
            $arResult[ "DISCOUNT_PERCENT" ] = $arMinDiscount[ "DISCOUNT_VALUE" ];
            $countProdBasket = count ($arProductsInBasket);
            for ($bi = 0; $bi < $countProdBasket; $bi ++) {
                if ($arParams[ "COUNT_DISCOUNT_4_ALL_QUANTITY" ] == "Y") {
                    $curDiscount = roundEx ($arProductsInBasket[ $bi ][ "PRICE" ] * $arProductsInBasket[ $bi ][ "QUANTITY" ] * $arMinDiscount[ "DISCOUNT_VALUE" ] / 100, SALE_VALUE_PRECISION);
                    $arResult[ "DISCOUNT_PRICE" ] += $curDiscount;
                } else {
                    $curDiscount = roundEx ($arProductsInBasket[ $bi ][ "PRICE" ] * $arMinDiscount[ "DISCOUNT_VALUE" ] / 100, SALE_VALUE_PRECISION);
                    $arResult[ "DISCOUNT_PRICE" ] += $curDiscount * $arProductsInBasket[ $bi ][ "QUANTITY" ];
                }
                $arProductsInBasket[ $bi ][ "DISCOUNT_PRICE" ] = $arProductsInBasket[ $bi ][ "PRICE" ] - $curDiscount;
            }
        } else {
            $arResult[ "DISCOUNT_PRICE" ] = CCurrencyRates::ConvertCurrency ($arMinDiscount[ "DISCOUNT_VALUE" ], $arMinDiscount[ "CURRENCY" ], $arResult[ "BASE_LANG_CURRENCY" ]);
            $arResult[ "DISCOUNT_PRICE" ] = roundEx ($arResult[ "DISCOUNT_PRICE" ], SALE_VALUE_PRECISION);
        }
    }

// if (strlen($arResult["ERROR_MESSAGE"]) <= 0 && $arResult["CurrentStep"] > 1)
// {
// // <***************** AFTER 1 STEP
// if ($arResult["PERSON_TYPE"] <= 0)
// $arResult["ERROR_MESSAGE"] .= GetMessage("SALE_NO_PERS_TYPE")."<br />";


// if (($arResult["PERSON_TYPE"] > 0) && !($arPersType = CSalePersonType::GetByID($arResult["PERSON_TYPE"])))
// 	$arResult["ERROR_MESSAGE"] .= GetMessage("SALE_PERS_TYPE_NOT_FOUND")."<br />";


// if (strlen($arResult["ERROR_MESSAGE"]) > 0)
// 	//$arResult["CurrentStep"] = 1;
// 	$arResult["CurrentStep"] = 2;
// }
    

    if (strlen ($arResult[ "ERROR_MESSAGE" ]) <= 0 && $arResult[ "CurrentStep" ] > 2) {
        //<***************** AFTER 2 STEP
        

        if (! $arResult[ "DELIVERY_LOCATION" ]) {
            $arResult[ "ERROR_MESSAGE" ] .= GetMessage ("SALE_EMPTY_FIELD") . " \"" . "Локация" . "\".<br />";
        }
        
        $arFilter = array (
            "PERSON_TYPE_ID" => $arResult[ "PERSON_TYPE" ], "ACTIVE" => "Y", "UTIL" => "N"
        );
        if (! empty ($arParams[ "PROP_" . $arResult[ "PERSON_TYPE" ] ]))
            $arFilter[ "!ID" ] = $arParams[ "PROP_" . $arResult[ "PERSON_TYPE" ] ];
        
        if (strlen ($arResult[ "ERROR_MESSAGE" ]) > 0)
            $arResult[ "CurrentStep" ] = 2;
    } //<***************** END AFTER 2 STEP
    

    if (strlen ($arResult[ "ERROR_MESSAGE" ]) <= 0 && $arResult[ "CurrentStep" ] > 3) {
        // <***************** AFTER 3 STEP
        if ($arResult[ "PROFILE_ID" ] > 0 && $USER->IsAuthorized ()) {
            /*
	     * это перезатирает установленные пользователем параметры
	     */
            $dbUserProps = CSaleOrderUserPropsValue::GetList (array (
                "SORT" => "ASC"
            ), array (
                "USER_PROPS_ID" => $arResult[ "PROFILE_ID" ]
            ), false, false, array (
                "ID", "ORDER_PROPS_ID", "VALUE", "SORT"
            ));
            while ( $arUserProps = $dbUserProps->GetNext () ) {
                if (! isset ($arResult[ "POST" ][ "ORDER_PROP_" . $arUserProps[ "ORDER_PROPS_ID" ] ])) {
                    $arResult[ "POST" ][ "ORDER_PROP_" . $arUserProps[ "ORDER_PROPS_ID" ] ] = $arUserProps[ "VALUE" ];
                    $arResult[ "POST" ][ "~ORDER_PROP_" . $arUserProps[ "ORDER_PROPS_ID" ] ] = $arUserProps[ "~VALUE" ];
                }
            }
        }
        $currentDeliveryId = isset ($arResult[ 'POST' ][ 'DELIVERY_ID' ]) && $arResult[ 'POST' ][ 'DELIVERY_ID' ] ? $arResult[ 'POST' ][ 'DELIVERY_ID' ] : $arResult[ "DELIVERY_ID" ];
        
        //$arResult["ERROR_MESSAGE"] .= checkPropertiesProfile($arResult, $arFilter);
        $dbOrderProps = CSaleOrderProps::GetList (array (
            "SORT" => "ASC"
        ), $arFilter, false, false, array (
            "ID", "NAME", "TYPE", "IS_LOCATION", "IS_LOCATION4TAX", "IS_PROFILE_NAME", "IS_PAYER", "IS_EMAIL", "IS_ZIP", "REQUIED", "SORT"
        ));
        while ( $arOrderProps = $dbOrderProps->GetNext () ) {
            if ($arOrderProps[ "TYPE" ] == "LOCATION") {
                continue;
            }
            $bErrorField = False;
            $curVal = $arResult[ "POST" ][ "~ORDER_PROP_" . $arOrderProps[ "ID" ] ];
            
            if ($arOrderProps[ "TYPE" ] == "LOCATION") {
                if (isset ($arResult[ "POST" ][ "NEW_LOCATION_" . $arOrderProps[ "ID" ] ]) && intval ($arResult[ "POST" ][ "NEW_LOCATION_" . $arOrderProps[ "ID" ] ]) > 0) {
                    $curVal = intval ($arResult[ "POST" ][ "NEW_LOCATION_" . $arOrderProps[ "ID" ] ]);
                    $arResult[ "POST" ][ "ORDER_PROP_" . $arOrderProps[ "ID" ] ] = $curVal;
                }
            }
            if ($arOrderProps[ "TYPE" ] == "LOCATION" && ($arOrderProps[ "IS_LOCATION" ] == "Y" || $arOrderProps[ "IS_LOCATION4TAX" ] == "Y")) {
                if ($arOrderProps[ "IS_LOCATION" ] == "Y")
                    $arResult[ "DELIVERY_LOCATION" ] = IntVal ($curVal);
                if ($arOrderProps[ "IS_LOCATION4TAX" ] == "Y")
                    $arResult[ "TAX_LOCATION" ] = IntVal ($curVal);
                
                if (IntVal ($curVal) <= 0)
                    $bErrorField = True;
            } elseif ($arOrderProps[ "IS_PROFILE_NAME" ] == "Y" || $arOrderProps[ "IS_PAYER" ] == "Y" || $arOrderProps[ "IS_EMAIL" ] == "Y" || $arOrderProps[ "IS_ZIP" ] == "Y") {
                if ($arOrderProps[ "IS_PROFILE_NAME" ] == "Y") {
                    $arResult[ "PROFILE_NAME" ] = Trim ($curVal);
                    if (strlen ($arResult[ "PROFILE_NAME" ]) <= 0)
                        $bErrorField = True;
                }
                if ($arOrderProps[ "IS_PAYER" ] == "Y") {
                    $arResult[ "PAYER_NAME" ] = Trim ($curVal);
                    if (strlen ($arResult[ "PAYER_NAME" ]) <= 0)
                        $bErrorField = True;
                }
                if ($arOrderProps[ "IS_EMAIL" ] == "Y") {
                    $arResult[ "USER_EMAIL" ] = Trim ($curVal);
                    if (strlen ($arResult[ "USER_EMAIL" ]) <= 0 || ! check_email ($arResult[ "USER_EMAIL" ]))
                        $bErrorField = True;
                }
                if ($arOrderProps[ "IS_ZIP" ] == "Y") {
                    $arResult[ "DELIVERY_LOCATION_ZIP" ] = $curVal;
                    if (strlen ($arResult[ "DELIVERY_LOCATION_ZIP" ]) <= 0)
                        $bErrorField = True;
                }
            } elseif ($arOrderProps[ "REQUIED" ] == "Y") {
                if ($arOrderProps[ "TYPE" ] == "TEXT" || $arOrderProps[ "TYPE" ] == "TEXTAREA" || $arOrderProps[ "TYPE" ] == "RADIO" || $arOrderProps[ "TYPE" ] == "SELECT" || $arOrderProps[ "TYPE" ] == "CHECKBOX") {
                    if (strlen ($curVal) <= 0)
                        $bErrorField = True;
                } elseif ($arOrderProps[ "TYPE" ] == "LOCATION") {
                    if (IntVal ($curVal) <= 0)
                        $bErrorField = True;
                } elseif ($arOrderProps[ "TYPE" ] == "MULTISELECT") {
                    if (! is_array ($curVal) || count ($curVal) <= 0)
                        $bErrorField = True;
                }
            }
            if ($currentDeliveryId == DELIVERY_USUAL || $currentDeliveryId == DELIVERY_IMMEDIATE) {
                if ($bErrorField)
                    $arResult[ "ERROR_MESSAGE" ] .= GetMessage ("SALE_EMPTY_FIELD") . " \"" . $arOrderProps[ "NAME" ] . "\".<br />";
            }
        }
        //}
        $arResult[ "TaxExempt" ] = array ();
        $arUserGroups = $USER->GetUserGroupArray ();
        
        if ($arResult[ "bUsingVat" ] != "Y") {
            $dbTaxExemptList = CSaleTax::GetExemptList (array (
                "GROUP_ID" => $arUserGroups
            ));
            while ( $TaxExemptList = $dbTaxExemptList->Fetch () ) {
                if (! in_array (IntVal ($TaxExemptList[ "TAX_ID" ]), $arResult[ "TaxExempt" ])) {
                    $arResult[ "TaxExempt" ][ ] = IntVal ($TaxExemptList[ "TAX_ID" ]);
                }
            }
        }
        
        // DELIVERY
        

        $arResult[ "DELIVERY_PRICE" ] = 0;
        
        if (is_array ($arResult[ "DELIVERY_ID" ])) {
            $arOrder = array (
                "PRICE" => $arResult[ "ORDER_PRICE" ], "WEIGHT" => $arResult[ "ORDER_WEIGHT" ], 
                "LOCATION_FROM" => COption::GetOptionInt ('sale', 'location'), "LOCATION_TO" => $arResult[ "DELIVERY_LOCATION" ], 
                "LOCATION_ZIP" => $arResult[ "DELIVERY_LOCATION_ZIP" ]
            );
            
            $arDeliveryPrice = CSaleDeliveryHandler::CalculateFull ($arResult[ "DELIVERY_ID" ][ 0 ], $arResult[ "DELIVERY_ID" ][ 1 ], $arOrder, $arResult[ "BASE_LANG_CURRENCY" ]);
            
            if ($arDeliveryPrice[ "RESULT" ] == "ERROR")
                $arResult[ "ERROR_MESSAGE" ] = $arDeliveryPrice[ "TEXT" ];
            else
                $arResult[ "DELIVERY_PRICE" ] = roundEx ($arDeliveryPrice[ "VALUE" ], SALE_VALUE_PRECISION);
        } else {
            if (($arResult[ "DELIVERY_ID" ] > 0) && ! ($arDeliv = CSaleDelivery::GetByID ($arResult[ "DELIVERY_ID" ])))
                $arResult[ "ERROR_MESSAGE" ] .= GetMessage ("SALE_DELIVERY_NOT_FOUND") . "<br />";
            elseif (($arResult[ "DELIVERY_ID" ] > 0) && $arDeliv)
                $arResult[ "DELIVERY_PRICE" ] = roundEx (CCurrencyRates::ConvertCurrency ($arDeliv[ "PRICE" ], $arDeliv[ "CURRENCY" ], $arResult[ "BASE_LANG_CURRENCY" ]), SALE_VALUE_PRECISION);
        }
        $arResult[ 'currentDeliveryId' ] = $currentDeliveryId;
        
        if (strlen ($arResult[ "ERROR_MESSAGE" ]) > 0)
            $arResult[ "CurrentStep" ] = 3;
    } // <***************** END AFTER 3 STEP


// TAX
    $arResult[ "TAX_EXEMPT" ] = (($_REQUEST[ "TAX_EXEMPT" ] == "Y") ? "Y" : "N");
    if ($arResult[ "TAX_EXEMPT" ] == "N") {
        unset ($arResult[ "TaxExempt" ]);
        $arResult[ "TaxExempt" ] = array ();
    }
    
    $arResult[ "TAX_PRICE" ] = 0;
    $arResult[ "arTaxList" ] = array ();
    if ($arResult[ "bUsingVat" ] != "Y") {
        $dbTaxRate = CSaleTaxRate::GetList (array (
            "APPLY_ORDER" => "ASC"
        ), array (
            "LID" => SITE_ID, "PERSON_TYPE_ID" => $arResult[ "PERSON_TYPE" ], "ACTIVE" => "Y", "LOCATION" => IntVal ($arResult[ "TAX_LOCATION" ])
        ));
        while ( $arTaxRate = $dbTaxRate->GetNext () ) {
            if (! in_array (IntVal ($arTaxRate[ "TAX_ID" ]), $arResult[ "TaxExempt" ])) {
                $arResult[ "arTaxList" ][ ] = $arTaxRate;
            }
        }
        
        $arTaxSums = array ();
        if (count ($arResult[ "arTaxList" ]) > 0) {
            $countProdBasket = count ($arProductsInBasket);
            for ($i = 0; $i < $countProdBasket; $i ++) {
                $arResult[ "TAX_PRICE_tmp" ] = CSaleOrderTax::CountTaxes ($arProductsInBasket[ $i ][ "DISCOUNT_PRICE" ] * $arProductsInBasket[ $i ][ "QUANTITY" ], $arResult[ "arTaxList" ], $arResult[ "BASE_LANG_CURRENCY" ]);
                
                $countResultTax = count ($arResult[ "arTaxList" ]);
                for ($j = 0; $j < $countResultTax; $j ++) {
                    $arResult[ "arTaxList" ][ $j ][ "VALUE_MONEY" ] += $arResult[ "arTaxList" ][ $j ][ "TAX_VAL" ];
                }
            }
            if (DoubleVal ($arResult[ "DELIVERY_PRICE" ]) > 0 && $arParams[ "COUNT_DELIVERY_TAX" ] == "Y") {
                $arResult[ "TAX_PRICE_tmp" ] = CSaleOrderTax::CountTaxes ($arResult[ "DELIVERY_PRICE" ], $arResult[ "arTaxList" ], $arResult[ "BASE_LANG_CURRENCY" ]);
                
                $countResTax = count ($arResult[ "arTaxList" ]);
                for ($j = 0; $j < $countResTax; $j ++) {
                    $arResult[ "arTaxList" ][ $j ][ "VALUE_MONEY" ] += $arResult[ "arTaxList" ][ $j ][ "TAX_VAL" ];
                }
            }
            
            $countResultTax = count ($arResult[ "arTaxList" ]);
            for ($i = 0; $i < $countResultTax; $i ++) {
                $arTaxSums[ $arResult[ "arTaxList" ][ $i ][ "TAX_ID" ] ][ "VALUE" ] = $arResult[ "arTaxList" ][ $i ][ "VALUE_MONEY" ];
                $arTaxSums[ $arResult[ "arTaxList" ][ $i ][ "TAX_ID" ] ][ "NAME" ] = $arResult[ "arTaxList" ][ $i ][ "NAME" ];
                if ($arResult[ "arTaxList" ][ $i ][ "IS_IN_PRICE" ] != "Y") {
                    $arResult[ "TAX_PRICE" ] += $arResult[ "arTaxList" ][ $i ][ "VALUE_MONEY" ];
                }
            }
        }
    } else {
        if (DoubleVal ($arResult[ "DELIVERY_PRICE" ]) > 0 && $arParams[ "COUNT_DELIVERY_TAX" ] == "Y")
            $arResult[ "vatSum" ] += roundEx ($arResult[ "DELIVERY_PRICE" ] * $arResult[ "vatRate" ] / (1 + $arResult[ "vatRate" ]), 2);
        
        $arResult[ "arTaxList" ][ ] = Array (
            "NAME" => GetMessage ("STOF_VAT"), "IS_PERCENT" => "Y", "VALUE" => $arResult[ "vatRate" ] * 100, "VALUE_MONEY" => $arResult[ "vatSum" ], 
            "APPLY_ORDER" => 100, "IS_IN_PRICE" => "Y", "CODE" => "VAT"
        );
    }
    
    if (strlen ($arResult[ "ERROR_MESSAGE" ]) <= 0 && $arResult[ "CurrentStep" ] >= 4) {
        // <***************** AFTER 4 STEP
        // PAY_SYSTEM
        if ($arResult[ "CurrentStep" ] > 4) {
            $arResult[ "PAY_SYSTEM_ID" ] = IntVal ($_REQUEST[ "PAY_SYSTEM_ID" ]);
            if ($arResult[ "PAY_SYSTEM_ID" ] <= 0)
                $arResult[ "ERROR_MESSAGE" ] .= GetMessage ("SALE_NO_PAY_SYS") . "<br />";
            if (($arResult[ "PAY_SYSTEM_ID" ] > 0) && ! ($arPaySys = CSalePaySystem::GetByID ($arResult[ "PAY_SYSTEM_ID" ], $arResult[ "PERSON_TYPE" ])))
                $arResult[ "ERROR_MESSAGE" ] .= GetMessage ("SALE_PAY_SYS_NOT_FOUND") . "<br />";
        }
        //if ($arResult["PAY_CURRENT_ACCOUNT"] != "Y")
        //$arResult["PAY_CURRENT_ACCOUNT"] = "N";
        

        if (strlen ($arResult[ "ERROR_MESSAGE" ]) > 0)
            $arResult[ "CurrentStep" ] = 4;
    }
    
    if (strlen ($arResult[ "ERROR_MESSAGE" ]) <= 0 && $arResult[ "CurrentStep" ] > 5) {
        
        if (strlen ($arResult[ "ERROR_MESSAGE" ]) > 0)
            $arResult[ "CurrentStep" ] = 5;
        
        if (strlen ($arResult[ "ERROR_MESSAGE" ]) <= 0) {
            $totalOrderPrice = $arResult[ "ORDER_PRICE" ] + $arResult[ "DELIVERY_PRICE" ] + $arResult[ "TAX_PRICE" ] - $arResult[ "DISCOUNT_PRICE" ];
            
            $arFields = array (
                "LID" => SITE_ID, "PERSON_TYPE_ID" => $arResult[ "PERSON_TYPE" ], 
                "STORE_ID" => isset ($arResult[ 'STORE_ID' ]) ? (int) $arResult[ 'STORE_ID' ] : '', "PAYED" => "N", "CANCELED" => "N", 
                "STATUS_ID" => "N", "PRICE" => $totalOrderPrice, "CURRENCY" => $arResult[ "BASE_LANG_CURRENCY" ], "USER_ID" => IntVal ($USER->GetID ()), 
                "PAY_SYSTEM_ID" => $arResult[ "PAY_SYSTEM_ID" ], "PRICE_DELIVERY" => $arResult[ "DELIVERY_PRICE" ], 
                "DELIVERY_ID" => is_array ($arResult[ "DELIVERY_ID" ]) ? implode (":", $arResult[ "DELIVERY_ID" ]) : ($arResult[ "DELIVERY_ID" ] > 0 ? $arResult[ "DELIVERY_ID" ] : false), 
                "DISCOUNT_VALUE" => $arResult[ "DISCOUNT_PRICE" ], 
                "TAX_VALUE" => $arResult[ "bUsingVat" ] == "Y" ? $arResult[ "vatSum" ] : $arResult[ "TAX_PRICE" ], 
                "USER_DESCRIPTION" => $arResult[ "ORDER_DESCRIPTION" ]
            );
            
            // add Guest ID
            if (CModule::IncludeModule ("statistic"))
                $arFields[ "STAT_GID" ] = CStatistic::GetEventParam ();
            
            $affiliateID = CSaleAffiliate::GetAffiliate ();
            if ($affiliateID > 0) {
                $dbAffiliat = CSaleAffiliate::GetList (array (), array (
                    "SITE_ID" => SITE_ID, "ID" => $affiliateID
                ));
                $arAffiliates = $dbAffiliat->Fetch ();
                if (count ($arAffiliates) > 1)
                    $arFields[ "AFFILIATE_ID" ] = $affiliateID;
            } else
                $arFields[ "AFFILIATE_ID" ] = false;
            
            $arResult[ "ORDER_ID" ] = CSaleOrder::Add ($arFields);
            $arResult[ "ORDER_ID" ] = IntVal ($arResult[ "ORDER_ID" ]);
            
            if ($arResult[ "ORDER_ID" ] <= 0) {
                if ($ex = $APPLICATION->GetException ())
                    $arResult[ "ERROR_MESSAGE" ] .= $ex->GetString ();
                else
                    $arResult[ "ERROR_MESSAGE" ] .= GetMessage ("SALE_ERROR_ADD_ORDER") . "<br />";
            } else {
                $arOrder = CSaleOrder::GetByID ($arResult[ "ORDER_ID" ]);
            }
        }
        
        if (strlen ($arResult[ "ERROR_MESSAGE" ]) <= 0) {
            CSaleBasket::OrderBasket ($arResult[ "ORDER_ID" ], CSaleBasket::GetBasketUserID (), SITE_ID, false);
            
            $dbBasketItems = CSaleBasket::GetList (array (
                "NAME" => "ASC"
            ), array (
                "FUSER_ID" => CSaleBasket::GetBasketUserID (), "LID" => SITE_ID, "ORDER_ID" => $arResult[ "ORDER_ID" ]
            ), false, false, array (
                "ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY", "CAN_BUY", "PRICE", "WEIGHT", "NAME"
            ));
            $arResult[ "ORDER_PRICE" ] = 0;
            while ( $arBasketItems = $dbBasketItems->GetNext () ) {
                $arResult[ "ORDER_PRICE" ] += DoubleVal ($arBasketItems[ "PRICE" ]) * DoubleVal ($arBasketItems[ "QUANTITY" ]);
            }
            
            $totalOrderPrice = $arResult[ "ORDER_PRICE" ] + $arResult[ "DELIVERY_PRICE" ] + $arResult[ "TAX_PRICE" ] - $arResult[ "DISCOUNT_PRICE" ];
            CSaleOrder::Update ($arResult[ "ORDER_ID" ], Array (
                "PRICE" => $totalOrderPrice
            ));
        }
        
        if (strlen ($arResult[ "ERROR_MESSAGE" ]) <= 0) {
            //if($arResult["bUsingVat"] != "Y")
            //{
            $countResultTax = count ($arResult[ "arTaxList" ]);
            for ($i = 0; $i < $countResultTax; $i ++) {
                $arFields = array (
                    "ORDER_ID" => $arResult[ "ORDER_ID" ], "TAX_NAME" => $arResult[ "arTaxList" ][ $i ][ "NAME" ], 
                    "IS_PERCENT" => $arResult[ "arTaxList" ][ $i ][ "IS_PERCENT" ], 
                    "VALUE" => ($arResult[ "arTaxList" ][ $i ][ "IS_PERCENT" ] == "Y") ? $arResult[ "arTaxList" ][ $i ][ "VALUE" ] : RoundEx (CCurrencyRates::ConvertCurrency ($arResult[ "arTaxList" ][ $i ][ "VALUE" ], $arResult[ "arTaxList" ][ $i ][ "CURRENCY" ], $arResult[ "BASE_LANG_CURRENCY" ]), SALE_VALUE_PRECISION), 
                    "VALUE_MONEY" => $arResult[ "arTaxList" ][ $i ][ "VALUE_MONEY" ], "APPLY_ORDER" => $arResult[ "arTaxList" ][ $i ][ "APPLY_ORDER" ], 
                    "IS_IN_PRICE" => $arResult[ "arTaxList" ][ $i ][ "IS_IN_PRICE" ], "CODE" => $arResult[ "arTaxList" ][ $i ][ "CODE" ]
                );
                CSaleOrderTax::Add ($arFields);
            }
            //}
            /*
						elseif($arResult["vatRate"] > 0)
						{
						$arFields = array(
								"ORDER_ID" => $arResult["ORDER_ID"],
								"TAX_NAME" => GetMessage("STOF_VAT"),
								"IS_PERCENT" => "Y",
								"VALUE" => $arResult["vatRate"],
								"VALUE_MONEY" => $arResult["vatSum"],
								"APPLY_ORDER" => 100,
								"IS_IN_PRICE" => "Y",
								"CODE" => "VAT"
						);
						CSaleOrderTax::Add($arFields);

						}
				*/
            $arFilter = array (
                "PERSON_TYPE_ID" => $arResult[ "PERSON_TYPE" ], "ACTIVE" => "Y", "UTIL" => "N"
            );
            if (! empty ($arParams[ "PROP_" . $arResult[ "PERSON_TYPE" ] ]))
                $arFilter[ "!ID" ] = $arParams[ "PROP_" . $arResult[ "PERSON_TYPE" ] ];
            
            $dbOrderProperties = CSaleOrderProps::GetList (array (
                "SORT" => "ASC"
            ), $arFilter, false, false, array (
                "ID", "TYPE", "NAME", "CODE", "USER_PROPS", "SORT"
            ));
    
            $oldProperties = (int)$arResult[ "PROFILE_ID" ] >= 0 ? getProfileProperties($arResult) : array();
			$prefix = $arResult[ "PERSON_TYPE" ] == 1 ? 'USER_' : 'J_';

            while ( $arOrderProperties = $dbOrderProperties->Fetch () ) {
			
                $curVal = $arResult[ "POST" ][ "~ORDER_PROP_" . $arOrderProperties[ "ID" ] ];
                if ((int)$arResult[ "PROFILE_ID" ] >= 0 && isset($oldProperties[$arOrderProperties['CODE']]['VALUE']) && $oldProperties[$arOrderProperties['CODE']]['VALUE'] != $curVal) {
                    /*TODO
                     * создаем новый профиль
                     */
                    $arResult[ "PROFILE_ID" ] = 0;
                }
                if (trim($arOrderProperties['CODE']) == $prefix . 'ATTACHED_FILES' && isset($arResult['POST']['ATTACHED_FILES']) && count($arResult['POST']['ATTACHED_FILES'])) {
                    $fileNames = array();
                    foreach ($arResult['POST']['ATTACHED_FILES'] as $fileId) {
                        if ($name = getFilePathById($fileId)) {
                    		$fileNames[] = $name;
                        }
   
                    }
                    if (count($fileNames)) {
                    	$curVal = implode(';', $fileNames);
                    }
                	$arResult[ "POST" ][ "~ORDER_PROP_" . $arOrderProperties[ "ID" ] ] = $curVal;
                	$arResult[ "POST" ][ "ORDER_PROP_" . $arOrderProperties[ "ID" ] ] = $curVal;
                }
                if ($arOrderProperties[ "TYPE" ] == "MULTISELECT") {
                    $curVal = "";
                    $countResProp = count ($arResult[ "POST" ][ "~ORDER_PROP_" . $arOrderProperties[ "ID" ] ]);
                    for ($i = 0; $i < $countResProp; $i ++) {
                        if ($i > 0)
                            $curVal .= ",";
                        $curVal .= $arResult[ "POST" ][ "~ORDER_PROP_" . $arOrderProperties[ "ID" ] ][ $i ];
                    }
                }
                
                if ($arOrderProperties[ "TYPE" ] == "CHECKBOX" && strlen ($curVal) <= 0 && $arOrderProperties[ "REQUIED" ] != "Y") {
                    $curVal = "N";
                }
                
                if (strlen ($curVal) > 0) {
                    $arFields = array (
                        "ORDER_ID" => $arResult[ "ORDER_ID" ], "ORDER_PROPS_ID" => $arOrderProperties[ "ID" ], "NAME" => $arOrderProperties[ "NAME" ], 
                        "CODE" => $arOrderProperties[ "CODE" ], "VALUE" => $curVal
                    );
                    CSaleOrderPropsValue::Add ($arFields);
                    if ($arOrderProperties[ "USER_PROPS" ] == "Y" && IntVal ($arResult[ "PROFILE_ID" ]) <= 0 && IntVal ($arResult[ "USER_PROPS_ID" ]) <= 0) {
                        if (strlen ($arResult[ "PROFILE_NAME" ]) <= 0)
                            $arResult[ "PROFILE_NAME" ] = GetMessage ("SALE_PROFILE_NAME") . " " . Date ("Y-m-d");
                        
                        $arFields = array (
                            "NAME" => $arResult[ "PROFILE_NAME" ], "USER_ID" => IntVal ($USER->GetID ()), "PERSON_TYPE_ID" => $arResult[ "PERSON_TYPE" ]
                        );
                        $arResult[ "USER_PROPS_ID" ] = CSaleOrderUserProps::Add ($arFields);
                        $arResult[ "USER_PROPS_ID" ] = IntVal ($arResult[ "USER_PROPS_ID" ]);
                    }
                    
                    if (IntVal ($arResult[ "PROFILE_ID" ]) <= 0 && $arOrderProperties[ "USER_PROPS" ] == "Y" && $arResult[ "USER_PROPS_ID" ] > 0) {
                        $arFields = array (
                            "USER_PROPS_ID" => $arResult[ "USER_PROPS_ID" ], "ORDER_PROPS_ID" => $arOrderProperties[ "ID" ], 
                            "NAME" => $arOrderProperties[ "NAME" ], "VALUE" => $curVal
                        );
                        CSaleOrderUserPropsValue::Add ($arFields);
                    }
                }
            }
      
        }
      
        $withdrawSum = 0.0;
        if (strlen ($arResult[ "ERROR_MESSAGE" ]) <= 0) {
            if ($arResult[ "PAY_CURRENT_ACCOUNT" ] == "Y" && $arParams[ "ALLOW_PAY_FROM_ACCOUNT" ] == "Y") {
                $dbUserAccount = CSaleUserAccount::GetList (array (), array (
                    "USER_ID" => $USER->GetID (), "CURRENCY" => $arResult[ "BASE_LANG_CURRENCY" ]
                ));
                if ($arUserAccount = $dbUserAccount->GetNext ()) {
                    if ($arUserAccount[ "CURRENT_BUDGET" ] > 0) {
                        if (($arParams[ "ONLY_FULL_PAY_FROM_ACCOUNT" ] == "Y" && DoubleVal ($arUserAccount[ "CURRENT_BUDGET" ]) >= DoubleVal ($totalOrderPrice)) || $arParams[ "ONLY_FULL_PAY_FROM_ACCOUNT" ] != "Y") {
                            $withdrawSum = CSaleUserAccount::Withdraw ($USER->GetID (), $totalOrderPrice, $arResult[ "BASE_LANG_CURRENCY" ], $arResult[ "ORDER_ID" ]);
                            
                            if ($withdrawSum > 0) {
                                $arFields = array (
                                    "SUM_PAID" => $withdrawSum, "USER_ID" => $USER->GetID ()
                                );
                                
                                CSaleOrder::Update ($arResult[ "ORDER_ID" ], $arFields);
                                
                                if ($withdrawSum == $totalOrderPrice)
                                    CSaleOrder::PayOrder ($arResult[ "ORDER_ID" ], "Y", False, False);
                            }
                        }
                    }
                }
            }
        }
        // mail message
        if (strlen ($arResult[ "ERROR_MESSAGE" ]) <= 0) {
            $strOrderList = "";
            $dbBasketItems = CSaleBasket::GetList (array (
                "NAME" => "ASC"
            ), array (
                "ORDER_ID" => $arResult[ "ORDER_ID" ]
            ), false, false, array (
                "ID", "NAME", "QUANTITY"
            ));
            while ( $arBasketItems = $dbBasketItems->Fetch () ) {
                $strOrderList .= $arBasketItems[ "NAME" ] . " - " . $arBasketItems[ "QUANTITY" ] . " " . GetMessage ("SALE_QUANTITY_UNIT");
                $strOrderList .= "\n";
            }
            
            $arFields = Array (
                "ORDER_ID" => $arOrder[ "ACCOUNT_NUMBER" ], "ORDER_DATE" => Date ($DB->DateFormatToPHP (CLang::GetDateFormat ("SHORT", SITE_ID))), 
                "ORDER_USER" => ((strlen ($arResult[ "PAYER_NAME" ]) > 0) ? $arResult[ "PAYER_NAME" ] : $USER->GetFormattedName (false)), 
                "PRICE" => SaleFormatCurrency ($totalOrderPrice, $arResult[ "BASE_LANG_CURRENCY" ]), 
                "BCC" => COption::GetOptionString ("sale", "order_email", "order@" . $SERVER_NAME), "EMAIL" => $arResult[ "USER_EMAIL" ], 
                "ORDER_LIST" => $strOrderList, "SALE_EMAIL" => COption::GetOptionString ("sale", "order_email", "order@" . $SERVER_NAME)
            );
            
            $eventName = "SALE_NEW_ORDER";
            
            $bSend = true;
            foreach (GetModuleEvents ("sale", "OnOrderNewSendEmail", true) as $arEvent)
                if (ExecuteModuleEventEx ($arEvent, Array (
                    $arResult[ "ORDER_ID" ], &$eventName, &$arFields
                )) === false)
                    $bSend = false;
            
            if ($bSend) {
                $event = new CEvent ();
                $event->Send ($eventName, SITE_ID, $arFields, "N");
            }
            
            CSaleMobileOrderPush::send ("ORDER_CREATED", array (
                "ORDER_ID" => $arFields[ "ORDER_ID" ]
            ));
        }
        if (strlen ($arResult[ "ERROR_MESSAGE" ]) <= 0) {
            LocalRedirect ($arParams[ "PATH_TO_ORDER" ] . "?CurrentStep=7&ORDER_ID=" . urlencode (urlencode ($arOrder[ "ACCOUNT_NUMBER" ])));
        }
        
        if (strlen ($arResult[ "ERROR_MESSAGE" ]) > 0)
            $arResult[ "CurrentStep" ] = 5;
    }
}

?>