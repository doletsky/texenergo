<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

 <? if (count($arResult["ITEMS"]) > 0): ?>

<aside class="pAccessories j-pAccessories">
    <h1>Аналоги</h1>

        <?
        $count = 0;
        $itemCount = count($arResult["ITEMS"]);

        ?>


        <? if ($itemCount > 3) : ?>
            <a href="#" class="accessories-right"></a>
            <a href="#" class="accessories-left"></a>
        <? endif; ?>

        <div class="j-analog-carousel">
            <div class="item">

                <ul class="pAccessoriesUl">

                    <? foreach ($arResult["ITEMS"] as $arItem): ?>

                        <?
                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                        if ($count > 0 && $count % 3 == 0) {
                            echo '</ul></div><div class="item"><ul class="pAccessoriesUl">';
                        }

                        ?>


                        <li class="product" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                            <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>">

                                <img src="<?= $arItem["PICTURE"] ?>"
                                     alt="<?= $arItem["NAME"] ?>"/>


                            </a>

                            <a class="name" href="<?= $arItem['DETAIL_PAGE_URL']; ?>">
                                <?= TruncateText($arItem["NAME"], 70); ?>
                            </a>

                            <? if ($arItem["PROPERTIES"]["SKU"]["VALUE"] != ""): ?>
                                <span class="sku"><? echo $arItem["PROPERTIES"]["SKU"]["VALUE"]; ?></span>
                            <? endif ?>
                            <?php
                            echo "<div class='cat-rating rating cat-value-raiting";
                            if ($arItem['PROPERTIES']['GOODS_RATE']['VALUE'] != "") {
                                echo "-" . $arItem['PROPERTIES']['GOODS_RATE']['VALUE'];
                            }
                            echo "'></div>";
                            $price = CPrice::GetBasePrice($arItem['ID']);
                            $oldPrice = $arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"];

                            if($price["PRICE"] <= 0){?>
                                <span class="price request-price nowrap">Цена по запросу</span>
                            <?}
                            else {
                                echo "<span class='price'>" . priceFormat($price["PRICE"]) . " <i class='rouble'>a</i></span>";
                                    if ($arItem["PROPERTIES"]["SHOW_OLD_PRICE"]["VALUE"] == "Да") {
                                        echo "<span class='pPriceSmall'>" . priceFormat($oldPrice) . "Р</span>";
                                }
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

</aside>

<?endif; ?>
