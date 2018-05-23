<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (count($arResult["ITEMS"]) > 0) {
    ?>

    <?
    $count = 0;
    $itemCount = count($arResult["ITEMS"]);
    ?>


    <section class="pAdditional">
        <header>
            <h1 class="pAdditionalHeader">Сопутствующие товары</h1>
            <? if ($itemCount > 3) : ?>
                <a href="#" class="additional-right"></a>
                <a href="#" class="additional-left"></a>
            <? endif; ?>
        </header>

        <div class="j-additional-carousel">
            <div class="item">


                <ul class="pAdditionalUl">
                    <? foreach ($arResult["ITEMS"] as $arItem): ?>

                        <?

                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                        if ($count > 0 && $count % 3 == 0) {
                            echo '</ul></div><div class="item"><ul class="pAdditionalUl">';
                        }

                        ?>

                        <li class="product clearfix" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">

                            <a class="pAdditionalImage clearfix" href="<?= $arItem["DETAIL_PAGE_URL"] ?>">


                                <img src="<?= $arItem["PICTURE"] ?>"
                                     alt="<?= $arItem["NAME"] ?>"/>


                            </a>

                            <a class="name fx"
                               href="<?= $arItem["DETAIL_PAGE_URL"] ?>" title="<?=$arItem["NAME"];?>"><?= TruncateText($arItem["NAME"], 50); ?></a>
                            <?php
                            if ($arItem["PROPERTIES"]["SKU"]["VALUE"] != "") {
                                echo "<span class='sku'>" . $arItem["PROPERTIES"]["SKU"]["VALUE"] . "</span>";
                            }
							?>
						   
						    <?$APPLICATION->IncludeComponent(
								"bitrix:iblock.vote",
								"stars",
								Array(
									"IBLOCK_TYPE" => "",
									"IBLOCK_ID" => $arItem['IBLOCK_ID'],
									"ELEMENT_ID" => $arItem['ID'],
									"CACHE_TYPE" => "N",
									"CACHE_TIME" => "3600",
									"MAX_VOTE" => "5",
									"VOTE_NAMES" => array(0=>"0",1=>"1",2=>"2",3=>"3",4=>"4",5=>"",),
									"SET_STATUS_404" => "Y",
									"DISPLAY_AS_RATING" => "rating"
								)
							);?>
							
							<?
							/* echo "<div class='cat-rating rating cat-value-raiting";
                            if ($arItem['PROPERTIES']['GOODS_RATE']['VALUE'] != "") {
                                echo "-" . $arItem['PROPERTIES']['GOODS_RATE']['VALUE'];
                            }
                            echo "'></div>"; */
							
							
                            $price = CPrice::GetBasePrice($arItem['ID']);
                            $oldPrice = $arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"];
                            if ($price["PRICE"]) {
                                echo "<span class='price'>" . priceFormat($price["PRICE"]) . " <i class='rouble'>a</i></span>";
                            }
                            if ($arItem["PROPERTIES"]["SHOW_OLD_PRICE"]["VALUE"] == "Да") {
                                echo "<span class='pPriceSmall'>" . priceFormat($oldPrice) . "Р</span>";
                            }
                            ?>
                        </li>

                        <?
                        $count++;
                        ?>

                    <? endforeach; ?>
                </ul>


            </div>
        </div>

    </section>
<?php
}
?>
