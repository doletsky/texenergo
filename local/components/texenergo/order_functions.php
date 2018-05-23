<?php
/*
 * функции доставки
 */
define ('DELIVERY_USUAL', 1);
define ('DELIVERY_IMMEDIATE', 2);
define ('USER_TYPE_PHISICAL', 1);
define ('USER_TYPE_JURIDICAL', 2);
define ('LOCATION_MOSCOW', 1321);
define ('LOCATION_OTHER', 1323);
define ('LOCATION_LATER', 1324);

function getTownVariants($curVal = 0)
{
    $dbVariants = CSaleLocation::GetList (array (
        "SORT" => "ASC", "COUNTRY_NAME_LANG" => "ASC", "CITY_NAME_LANG" => "ASC"
    ), array (
        "LID" => LANGUAGE_ID
    ), false, false, array (
        "ID", "COUNTRY_NAME", "CITY_NAME", "SORT", "COUNTRY_NAME_LANG", "CITY_NAME_LANG"
    ));
    $variants = array ();
    while ( $arVariants = $dbVariants->GetNext () ) {
        if (empty ($arVariants[ 'CITY_NAME' ])) {
            continue;
        }
        if (IntVal ($arVariants[ "ID" ]) == IntVal ($curVal) || ! isset ($curVal) && IntVal ($arVariants[ "ID" ]) == 1)
            $arVariants[ "SELECTED" ] = "Y";
        $arVariants[ "NAME" ] = $arVariants[ "COUNTRY_NAME" ] . ((strlen ($arVariants[ "CITY_NAME" ]) > 0) ? " - " : "") . $arVariants[ "CITY_NAME" ];
        $variants[ $arVariants[ "ID" ] ] = $arVariants;
    }
    return $variants;
}


function getPropertyList($USER, $arResult, $arFilter)
{
    $dbProperties = CSaleOrderProps::GetList (array (
        "GROUP_SORT" => "ASC", "PROPS_GROUP_ID" => "ASC", "SORT" => "ASC", "NAME" => "ASC"
    ), $arFilter, false, false, array (
        "ID", "NAME", "TYPE", "REQUIED", "DEFAULT_VALUE", "IS_LOCATION", "PROPS_GROUP_ID", "SIZE1", "SIZE2", "DESCRIPTION", "IS_EMAIL", 
        "IS_PROFILE_NAME", "IS_PAYER", "IS_LOCATION4TAX", "CODE", "GROUP_NAME", "GROUP_SORT", "SORT", "USER_PROPS", "IS_ZIP"
    ));
    $result = array ();
    while ( $arProperties = $dbProperties->GetNext () ) {
        unset ($curVal);
        if (isset ($arResult[ "POST" ][ "ORDER_PROP_" . $arProperties[ "ID" ] ]))
            $curVal = $arResult[ "POST" ][ "ORDER_PROP_" . $arProperties[ "ID" ] ];
        $arProperties[ "FIELD_NAME" ] = "ORDER_PROP_" . $arProperties[ "ID" ];
        if (IntVal ($arProperties[ "PROPS_GROUP_ID" ]) != $propertyGroupID || $propertyUSER_PROPS != $arProperties[ "USER_PROPS" ])
            $arProperties[ "SHOW_GROUP_NAME" ] = "Y";
        $propertyGroupID = $arProperties[ "PROPS_GROUP_ID" ];
        $propertyUSER_PROPS = $arProperties[ "USER_PROPS" ];
        
        if ($arProperties[ "REQUIED" ] == "Y" || $arProperties[ "IS_EMAIL" ] == "Y" || $arProperties[ "IS_PROFILE_NAME" ] == "Y" || $arProperties[ "IS_LOCATION" ] == "Y" || $arProperties[ "IS_LOCATION4TAX" ] == "Y" || $arProperties[ "IS_PAYER" ] == "Y" || $arProperties[ "IS_ZIP" ] == "Y")
            $arProperties[ "REQUIED_FORMATED" ] = "Y";
        
        if ($arProperties[ "TYPE" ] == "CHECKBOX") {
            if ($curVal == "Y" || ! isset ($curVal) && $arProperties[ "DEFAULT_VALUE" ] == "Y")
                $arProperties[ "CHECKED" ] = "Y";
            $arProperties[ "SIZE1" ] = ((IntVal ($arProperties[ "SIZE1" ]) > 0) ? $arProperties[ "SIZE1" ] : 30);
        } elseif ($arProperties[ "TYPE" ] == "TEXT") {
            if (strlen ($curVal) <= 0) {
                if (strlen ($arProperties[ "DEFAULT_VALUE" ]) > 0 && ! isset ($curVal))
                    $arProperties[ "VALUE" ] = $arProperties[ "DEFAULT_VALUE" ];
                elseif ($arProperties[ "IS_EMAIL" ] == "Y")
                    $arProperties[ "VALUE" ] = $USER->GetEmail ();
                elseif ($arProperties[ "IS_PAYER" ] == "Y") {
                    // $arProperties["VALUE"] = $USER->GetFullName();
                    $rsUser = CUser::GetByID ($USER->GetID ());
                    $fio = "";
                    if ($arUser = $rsUser->Fetch ()) {
                        if (strlen ($arUser[ "LAST_NAME" ]) > 0)
                            $fio .= $arUser[ "LAST_NAME" ];
                        if (strlen ($arUser[ "NAME" ]) > 0)
                            $fio .= " " . $arUser[ "NAME" ];
                        if (strlen ($arUser[ "SECOND_NAME" ]) > 0 and strlen ($arUser[ "NAME" ]) > 0)
                            $fio .= " " . $arUser[ "SECOND_NAME" ];
                    }
                    $arProperties[ "VALUE" ] = $fio;
                }
            } else
                $arProperties[ "VALUE" ] = $curVal;
        } elseif ($arProperties[ "TYPE" ] == "SELECT") {
            $arProperties[ "SIZE1" ] = ((IntVal ($arProperties[ "SIZE1" ]) > 0) ? $arProperties[ "SIZE1" ] : 1);
            $dbVariants = CSaleOrderPropsVariant::GetList (array (
                "SORT" => "ASC"
            ), array (
                "ORDER_PROPS_ID" => $arProperties[ "ID" ]
            ), false, false, array (
                "*"
            ));
            while ( $arVariants = $dbVariants->GetNext () ) {
                
                if ($arVariants[ "VALUE" ] == $curVal || ! isset ($curVal) && $arVariants[ "VALUE" ] == $arProperties[ "DEFAULT_VALUE" ])
                    $arVariants[ "SELECTED" ] = "Y";
                $arProperties[ "VARIANTS" ][ ] = $arVariants;
            }
        } elseif ($arProperties[ "TYPE" ] == "MULTISELECT") {
            $arProperties[ "FIELD_NAME" ] = "ORDER_PROP_" . $arProperties[ "ID" ] . '[]';
            $arProperties[ "SIZE1" ] = ((IntVal ($arProperties[ "SIZE1" ]) > 0) ? $arProperties[ "SIZE1" ] : 5);
            $arDefVal = explode (",", $arProperties[ "DEFAULT_VALUE" ]);
            $countDefVal = count ($arDefVal);
            for ($i = 0; $i < $countDefVal; $i ++)
                $arDefVal[ $i ] = Trim ($arDefVal[ $i ]);
            
            $dbVariants = CSaleOrderPropsVariant::GetList (array (
                "SORT" => "ASC"
            ), array (
                "ORDER_PROPS_ID" => $arProperties[ "ID" ]
            ), false, false, array (
                "*"
            ));
            while ( $arVariants = $dbVariants->GetNext () ) {
                if ((is_array ($curVal) && in_array ($arVariants[ "VALUE" ], $curVal)) || (! isset ($curVal) && in_array ($arVariants[ "VALUE" ], $arDefVal)))
                    $arVariants[ "SELECTED" ] = "Y";
                $arProperties[ "VARIANTS" ][ ] = $arVariants;
            }
        } elseif ($arProperties[ "TYPE" ] == "TEXTAREA") {
            $arProperties[ "SIZE2" ] = ((IntVal ($arProperties[ "SIZE2" ]) > 0) ? $arProperties[ "SIZE2" ] : 4);
            $arProperties[ "SIZE1" ] = ((IntVal ($arProperties[ "SIZE1" ]) > 0) ? $arProperties[ "SIZE1" ] : 40);
            $arProperties[ "VALUE" ] = ((isset ($curVal)) ? $curVal : $arProperties[ "DEFAULT_VALUE" ]);
        } elseif ($arProperties[ "TYPE" ] == "LOCATION") {
            $arProperties[ "SIZE1" ] = ((IntVal ($arProperties[ "SIZE1" ]) > 0) ? $arProperties[ "SIZE1" ] : 1);
            $dbVariants = CSaleLocation::GetList (array (
                "SORT" => "ASC", "COUNTRY_NAME_LANG" => "ASC", "CITY_NAME_LANG" => "ASC"
            ), array (
                "LID" => LANGUAGE_ID
            ), false, false, array (
                "ID", "COUNTRY_NAME", "CITY_NAME", "SORT", "COUNTRY_NAME_LANG", "CITY_NAME_LANG"
            ));
            while ( $arVariants = $dbVariants->GetNext () ) {
                if (IntVal ($arVariants[ "ID" ]) == IntVal ($curVal) || ! isset ($curVal) && IntVal ($arVariants[ "ID" ]) == IntVal ($arProperties[ "DEFAULT_VALUE" ]))
                    $arVariants[ "SELECTED" ] = "Y";
                $arVariants[ "NAME" ] = $arVariants[ "COUNTRY_NAME" ] . ((strlen ($arVariants[ "CITY_NAME" ]) > 0) ? " - " : "") . $arVariants[ "CITY_NAME" ];
                $arProperties[ "VARIANTS" ][ ] = $arVariants;
            }
        } elseif ($arProperties[ "TYPE" ] == "RADIO") {
            $dbVariants = CSaleOrderPropsVariant::GetList (array (
                "SORT" => "ASC"
            ), array (
                "ORDER_PROPS_ID" => $arProperties[ "ID" ]
            ), false, false, array (
                "*"
            ));
            while ( $arVariants = $dbVariants->GetNext () ) {
                if ($arVariants[ "VALUE" ] == $curVal || (strlen ($curVal) <= 0 && $arVariants[ "VALUE" ] == $arProperties[ "DEFAULT_VALUE" ]))
                    $arVariants[ "CHECKED" ] = "Y";
                
                $arProperties[ "VARIANTS" ][ ] = $arVariants;
            }
        }
        if ($arProperties[ "USER_PROPS" ] == "Y")
            $result[ "USER_PROPS_Y" ][ $arProperties[ "ID" ] ] = $arProperties;
        else
            $result[ "USER_PROPS_N" ][ $arProperties[ "ID" ] ] = $arProperties;
    }

    return $result;
}

function PrintOrderPropertyForm($arSource = Array(), $arParams, $currentDeliveryId = 0, $locationId = 0, $personType = 0, $profileVars = array())
{
    if (! $locationId || ! $personType) {
        return false;
    }
    $display = ($currentDeliveryId == DELIVERY_IMMEDIATE || $currentDeliveryId == DELIVERY_USUAL) ? "display:block;" : "display:none;";
	if ($personType == USER_TYPE_PHISICAL) {
    	$necessesoryFields = array('USER_PAYMENT_TYPE_ID', 'USER_STORE_ID', 'USER_ADDRESS', 'USER_DELIVERY_ID', 'USER_PAYMENT_TYPE_NAME');
	}else {
	    $necessesoryFields = array('J_PAYMENT_TYPE_ID', 'J_STORE_ID', 'J_ADDRESS', 'J_DELIVERY_ID', 'J_PAYMENT_TYPE_NAME');
	}
	$ignoreFields = array('J_ATTACHED_FILES', 'USER_ATTACHED_FILES');
	$regExp = ($personType == USER_TYPE_PHISICAL) ? '/^U_TOWN_/' : '/^J_TOWN_/';

    if (! empty ($arSource)) {
		echo '<table class="sale_order_full_table j-order-properties style=' . ${display} . '">';
        foreach ($arSource as $arProperties) {
			if (in_array(trim($arProperties[ "CODE" ]), $ignoreFields)) {
				continue;
			}
			
			if (! in_array(trim($arProperties[ "CODE" ]), $necessesoryFields)) {
				
				if ($locationId == LOCATION_OTHER && ! preg_match($regExp, trim($arProperties[ "CODE" ]))) {
					continue;
				}elseif ($locationId != LOCATION_OTHER && preg_match($regExp, trim($arProperties[ "CODE" ]))) {
					continue;
				}
			}
			if (in_array(trim($arProperties[ "CODE" ]), $necessesoryFields)) {
				echo '<input type="hidden" name="'. $arProperties["FIELD_NAME"] .'" value="' . $arProperties["VALUE"] . '">';
				continue;
			}
		
			
			$arProperties["VALUE"] = isset($profileVars[trim($arProperties[ "CODE" ])]["VALUE"]) ? $profileVars[trim($arProperties[ "CODE" ])]["VALUE"] : '';
			if ($arProperties[ "TYPE" ] == "CHECKBOX") {
				$arProperties["CHECKED"] = $arProperties["VALUE"] ? "Y" : "";
			}
			
            if ($arProperties[ "SHOW_GROUP_NAME" ] == "Y") {
                echo '<tr>
					<td colspan="2" align="center"><b>' . $arProperties["GROUP_NAME"] . '</b>
					</td>
				</tr>';
            }
            ?>
			<tr>
				<td align="right" valign="top">
					<?php
            		if ($arProperties[ "TYPE" ] != "LOCATION") {
                	?>
						<?= $arProperties["NAME"] ?>:<?
                		if ($arProperties[ "REQUIED_FORMATED" ] == "Y") {
                    	?><span class="sof-req">*</span><?
                		}
            		}
            ?>
				</td>
				<td>
					<?
            		if ($arProperties[ "TYPE" ] == "CHECKBOX") {
                ?>
					<input type="checkbox" name="<?=$arProperties["FIELD_NAME"]?>" value="Y" <?if ($arProperties["CHECKED"]=="Y") echo " checked";?>>
				<?
            		} elseif ($arProperties[ "TYPE" ] == "TEXT") {
                ?>
						<input type="text" maxlength="250" size="<?=$arProperties["SIZE1"]?>" value="<?=$arProperties["VALUE"]?>" name="<?=$arProperties["FIELD_NAME"]?>">
						<?
            		} elseif ($arProperties[ "TYPE" ] == "SELECT") {
                ?>
						<select name="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
						<?
                foreach ($arProperties[ "VARIANTS" ] as $arVariants) {
                    ?>
							<option value="<?=$arVariants["VALUE"]?>"
					<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
							<?
                }
                ?>
						</select>
						<?
            } elseif ($arProperties[ "TYPE" ] == "MULTISELECT") {
                ?>
						<select multiple name="<?=$arProperties["FIELD_NAME"]?>"
			size="<?=$arProperties["SIZE1"]?>">
						<?
                foreach ($arProperties[ "VARIANTS" ] as $arVariants) {
                    ?>
							<option value="<?=$arVariants["VALUE"]?>"
					<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
							<?
                }
                ?>
						</select>
						<?
            } elseif ($arProperties[ "TYPE" ] == "TEXTAREA") {
                ?>
						<textarea rows="<?=$arProperties["SIZE2"]?>"
				cols="<?=$arProperties["SIZE1"]?>"
				name="<?=$arProperties["FIELD_NAME"]?>"><?=$arProperties["VALUE"]?></textarea>
						<?
            } elseif ($arProperties[ "TYPE" ] == "LOCATION") {
// 						$value = 0;
// 						foreach ($arProperties["VARIANTS"] as $arVariant) 
// 						{
// 							if ($arVariant["SELECTED"] == "Y") 
// 							{
// 								$value = $arVariant["ID"]; 
// 								break;
// 							}
// 						}


// 						if ($arParams["USE_AJAX_LOCATIONS"] == "Y"):
// 							$GLOBALS["APPLICATION"]->IncludeComponent(
// 								"bitrix:sale.ajax.locations",
// 								".default",
// 								array(
// 									"AJAX_CALL" => "N",
// 									"COUNTRY_INPUT_NAME" => "COUNTRY_".$arProperties["FIELD_NAME"],
// 									"REGION_INPUT_NAME" => "REGION_".$arProperties["FIELD_NAME"],
// 									"CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
// 									"CITY_OUT_LOCATION" => "Y",
// 									"LOCATION_VALUE" => $locationId,
// 									"ORDER_PROPS_ID" => $arProperties["ID"],
// 									"ONCITYCHANGE" => "",
// 								),
// 								null,
// 								array('HIDE_ICONS' => 'Y')
// 							);						
// 						else:
                ?>
						
						<?php
            } elseif ($arProperties[ "TYPE" ] == "RADIO") {
                foreach ($arProperties[ "VARIANTS" ] as $arVariants) {
                    ?>
							<input type="radio" name="<?=$arProperties["FIELD_NAME"]?>"
			id="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["ID"]?>"
			value="<?=$arVariants["VALUE"]?>"
			<?if($arVariants["CHECKED"] == "Y") echo " checked";?>> <label
			for="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["ID"]?>"><?=$arVariants["NAME"]?></label><br />
							<?
                }
            }
            
            if (strlen ($arProperties[ "DESCRIPTION" ]) > 0) {
                ?><br />
		<small><?echo $arProperties["DESCRIPTION"] ?></small><?
            }
            ?>
					
				</td>
	</tr>
			<?
        }
        ?>
		</table>
<?
        return true;
    }
    return false;
}

function PrintPropsForm($arSource = Array(), $PRINT_TITLE = "", $arParams)
{
    if (! empty ($arSource)) {
        if (strlen ($PRINT_TITLE) > 0) {
            ?>
<b><?= $PRINT_TITLE ?></b>
<br />
<br />
<?
        }
        ?>
<table class="sale_order_full_table">
		<?
        foreach ($arSource as $arProperties) {
            if ($arProperties[ "SHOW_GROUP_NAME" ] == "Y") {
                ?>
				<tr>
		<td colspan="2" align="center"><b><?= $arProperties["GROUP_NAME"] ?></b>
		</td>
	</tr>
				<?
            }
            ?>
			<tr>
		<td align="right" valign="top">
					<?= $arProperties["NAME"] ?>:<?
            if ($arProperties[ "REQUIED_FORMATED" ] == "Y") {
                ?><span class="sof-req">*</span><?
            }
            ?>
				</td>
		<td>
					<?
            if ($arProperties[ "TYPE" ] == "CHECKBOX") {
                ?>
						<input type="checkbox" name="<?=$arProperties["FIELD_NAME"]?>"
			value="Y" <?if ($arProperties["CHECKED"]=="Y") echo " checked";?>>
						<?
            } elseif ($arProperties[ "TYPE" ] == "TEXT") {
                ?>
						<input type="text" maxlength="250"
			size="<?=$arProperties["SIZE1"]?>"
			value="<?=$arProperties["VALUE"]?>"
			name="<?=$arProperties["FIELD_NAME"]?>">
						<?
            } elseif ($arProperties[ "TYPE" ] == "SELECT") {
                ?>
						<select name="<?=$arProperties["FIELD_NAME"]?>"
			size="<?=$arProperties["SIZE1"]?>">
						<?
                foreach ($arProperties[ "VARIANTS" ] as $arVariants) {
                    ?>
							<option value="<?=$arVariants["VALUE"]?>"
					<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
							<?
                }
                ?>
						</select>
						<?
            } elseif ($arProperties[ "TYPE" ] == "MULTISELECT") {
                ?>
						<select multiple name="<?=$arProperties["FIELD_NAME"]?>"
			size="<?=$arProperties["SIZE1"]?>">
						<?
                foreach ($arProperties[ "VARIANTS" ] as $arVariants) {
                    ?>
							<option value="<?=$arVariants["VALUE"]?>"
					<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
							<?
                }
                ?>
						</select>
						<?
            } elseif ($arProperties[ "TYPE" ] == "TEXTAREA") {
                ?>
						<textarea rows="<?=$arProperties["SIZE2"]?>"
				cols="<?=$arProperties["SIZE1"]?>"
				name="<?=$arProperties["FIELD_NAME"]?>"><?=$arProperties["VALUE"]?></textarea>
						<?
            } elseif ($arProperties[ "TYPE" ] == "LOCATION") {
                $value = 0;
                foreach ($arProperties[ "VARIANTS" ] as $arVariant) {
                    if ($arVariant[ "SELECTED" ] == "Y") {
                        $value = $arVariant[ "ID" ];
                        break;
                    }
                }
                
                if ($arParams[ "USE_AJAX_LOCATIONS" ] == "Y") :
                    $GLOBALS[ "APPLICATION" ]->IncludeComponent ("bitrix:sale.ajax.locations", ".default", array (
                        "AJAX_CALL" => "N", "COUNTRY_INPUT_NAME" => "COUNTRY_" . $arProperties[ "FIELD_NAME" ], 
                        "REGION_INPUT_NAME" => "REGION_" . $arProperties[ "FIELD_NAME" ], "CITY_INPUT_NAME" => $arProperties[ "FIELD_NAME" ], 
                        "CITY_OUT_LOCATION" => "Y", "LOCATION_VALUE" => $value, "ORDER_PROPS_ID" => $arProperties[ "ID" ], "ONCITYCHANGE" => ""
                    ), null, array (
                        'HIDE_ICONS' => 'Y'
                    ));
                 else :
                    ?>
						<select name="<?=$arProperties["FIELD_NAME"]?>"
			size="<?=$arProperties["SIZE1"]?>">
						<?
                    foreach ($arProperties[ "VARIANTS" ] as $arVariants) {
                        ?>
							<option value="<?=$arVariants["ID"]?>"
					<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
							<?
                    }
                    ?>
						</select>
						
                <?endif;
            } elseif ($arProperties[ "TYPE" ] == "RADIO") {
                foreach ($arProperties[ "VARIANTS" ] as $arVariants) {
                    ?>
							<input type="radio" name="<?=$arProperties["FIELD_NAME"]?>"
			id="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["ID"]?>"
			value="<?=$arVariants["VALUE"]?>"
			<?if($arVariants["CHECKED"] == "Y") echo " checked";?>> <label
			for="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["ID"]?>"><?=$arVariants["NAME"]?></label><br />
							<?
                }
            }
            
            if (strlen ($arProperties[ "DESCRIPTION" ]) > 0) {
                ?><br />
		<small><?echo $arProperties["DESCRIPTION"] ?></small><?
            }
            ?>
					
				</td>
	</tr>
			<?
        }
        ?>
		</table>
<?
        return true;
    }
    return false;
}
function getProfileProperties($arResult)
{
    $dbUserProps = CSaleOrderUserPropsValue::GetList (array (
    		"SORT" => "ASC"
    ), array (
    		"USER_PROPS_ID" => $arResult[ "PROFILE_ID" ]
    ), false, false, array (
    		"ID", "ORDER_PROPS_ID", "VALUE", "SORT", "CODE"
    ));
	$result = array();
    while ( $arUserProps = $dbUserProps->GetNext () ) {
    	$result[$arUserProps['CODE']] = $arUserProps;
    }
    return $result;
}

function getFilePathById($fileId)
{
	$r = CFile::GetFileArray ($fileId);
	if (is_array($r) && isset($r['SRC'])) {
		return $r['SRC'];
	}
	return;
}

// function getPaymentPropertyNameByCodes($codeArr, $arResult, $arParams)
// {
// 	$arFilter = array("PERSON_TYPE_ID" => $arResult["PERSON_TYPE"], "ACTIVE" => "Y", "UTIL" => "N");
// 	if(!empty($arParams["PROP_".$arResult["PERSON_TYPE"]]))
// 		$arFilter["!ID"] = $arParams["PROP_".$arResult["PERSON_TYPE"]];
// 	$result = array();
// 	$dbProperties = CSaleOrderProps::GetList ( array (
// 			"GROUP_SORT" => "ASC",
// 			"PROPS_GROUP_ID" => "ASC",
// 			"SORT" => "ASC",
// 			"NAME" => "ASC"
// 	), $arFilter, false, false, array (
// 			"ID",
// 			"NAME",
// 			"TYPE",
// 			"REQUIED",
// 			"DEFAULT_VALUE",
// 			"IS_LOCATION",
// 			"PROPS_GROUP_ID",
// 			"SIZE1",
// 			"SIZE2",
// 			"DESCRIPTION",
// 			"IS_EMAIL",
// 			"IS_PROFILE_NAME",
// 			"IS_PAYER",
// 			"IS_LOCATION4TAX",
// 			"CODE",
// 			"GROUP_NAME",
// 			"GROUP_SORT",
// 			"SORT",
// 			"USER_PROPS",
// 			"IS_ZIP"
// 	));

// 	$result = array();
// 	while ( $arProperties = $dbProperties->GetNext () ) {
// 		if (in_array($arProperties['CODE'], $codeArr)) {
// 			$result[$arProperties['CODE']] = "ORDER_PROP_" . $arProperties ["ID"];
// 		}
// 	}
// 	return $result;
// }
?>