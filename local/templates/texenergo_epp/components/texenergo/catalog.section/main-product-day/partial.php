<?

$strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
$strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
$arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));

$count = 1;
foreach ($part as $key => $arItem) {
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
    $strMainID = $this->GetEditAreaId($arItem['ID']);
    ?>
    <article class="pProductInLine clearfix" id="<? echo $strMainID; ?>">
        <section class="pLineImage">
            <? if ($arItem["PROPERTIES"]["IS_NEW"]["VALUE"] == "Y"): ?>
                <img src='<?= SITE_TEMPLATE_PATH; ?>/img/catalog/new.png'
                     alt='<?= $arItem["PROPERTIES"]["IS_NEW"]["NAME"]; ?>' class='new-in-img'>
            <? endif; ?>

            <? if ($arItem['PROPERTIES']['IS_BESTSELLER']['VALUE'] == 'Y'): ?>
                <img src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/bestseller.png" alt="Лидер продаж"
                     class="new-in-img">
            <? endif; ?>
            <figure class="pProductImage pProductImage-line">

                <?php
                echo "<img id='product_picture_".$arItem['ID']."' src='" . $arItem["PICTURE"] . "' alt='" . $arItem["NAME"] . "' class='j-image-to-animate j_preview_img_main_" . $arItem["ID"] . " big-last-full-img' />";

                ?>

                <? if ($arItem['OLD_PRICE'] > 0): ?>
                    <div class="cat-discount catDiscount-cart"></div>
                <? endif; ?>
            </figure>
            <?php
            if ($arItem["PROPERTIES"]["GOODS_PHOTOS"]["VALUE"] != "") {
                echo "<div class='pSlider pSlider-line clearfix'><ul class='owl-slider j_owl_slider_3'>";
                echo "<li class='j_preview_img active' data-id='" . $arItem["ID"] . "'><img src='";
                if ($arItem["PREVIEW_PICTURE"]["SRC"]) {
                    echo $arItem["PREVIEW_PICTURE"]["SRC"];
                } else {
                    if ($arItem["DETAIL_PICTURE"]["SRC"]) {
                        echo $arItem["DETAIL_PICTURE"]["SRC"];
                    }
                }
                echo "' alt='" . $arItem["NAME"] . "'></li>";
                foreach ($arItem["PROPERTIES"]["GOODS_PHOTOS"]["VALUE"] as $foto) {
                    echo "<li class='j_preview_img' data-id='" . $arItem["ID"] . "'><img src='" . $foto["SRC"] . "' alt='" . $arItem["NAME"] . "'></li>";
                }
                echo "</ul></div>";
            }
            ?>
        </section>
        <section class="pLineOverview">
            <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>"
               class="name name-pline"><?= $arItem["NAME"] ?></a>
            <?php
            if ($arItem["PROPERTIES"]["SKU"]["VALUE"] != "") {
                echo "<span class='sku sku-pline'>" . $arItem["PROPERTIES"]["SKU"]["VALUE"] . "</span>";
            }
            ?>

            <div><div
                    class="cat-rating cat-value-raiting<? if (!empty($arItem['PROPERTIES']['RAITING']['VALUE'])): ?>-<?= $arItem['PROPERTIES']['RAITING']['VALUE']; ?><? endif; ?>"></div></div>
            <?
            if ($arItem["PREVIEW_TEXT"] != "") {
                echo "<div class='copy-pline'><a href='". $arItem['DETAIL_PAGE_URL']. "'>" . $arItem["PREVIEW_TEXT"] . "</a></div>";
            }
            if ($arItem['PROPERTIES']['GOODS_FILTER_VALUE']['VALUE'] != "") {
                foreach ($arItem['PROPERTIES']['GOODS_FILTER_VALUE']['VALUE_NAMES'] as $filterValue) {
                    echo "<div class='row-pline'><span class='spanFirst'>" . $filterValue["FILTER_VARS"]["NAME"] . ":</span><span class='spanSecond'>" . $filterValue["NAME"] . "</span></div>";
                }
            }
            ?>
        </section>
        <section class="pLineCart">

            <? if (!empty($arItem['SPECIAL_TIMER'])): ?>
                <td>
                    <div class="cat-promo">
                        <div class="cat-promo-time">
                            <span class="text">До конца распродажи</span>

                            <div class="digits cat-day"><?= $arItem['SPECIAL_TIMER']['DAYS']; ?></div>
                            <span class="two-dots">:</span>

                            <div class="digits cat-hour"><?= $arItem['SPECIAL_TIMER']['HOURS']; ?></div>
                            <span class="two-dots">:</span>

                            <div
                                class="digits cat-min"><?= $arItem['SPECIAL_TIMER']['MINUTES']; ?></div>
                        </div>
                        <ul class="cat-promo-captions">
                            <li>день</li>
                            <li>час</li>
                            <li>мин</li>
                        </ul>
                    </div>
                </td>
            <? endif; ?>

            <?php
            if ($arItem['PROPERTIES']['BLOG_COMMENTS_CNT']['VALUE'] != "") {
                echo "<span>" . $arItem['PROPERTIES']['COMMENTS_CNT']['VALUE'] . " " . GetMessage("REVIEWS_" . wordForm($arItem['PROPERTIES']['COMMENTS_CNT']['VALUE'])) . "</span>";
            }
            ?>
            <div class="pPriceBlock">
                <span class='pLinePrice'>
                    <?if($arItem['PRICE'] <= 0){?>
                        <span class="request-price nowrap">Цена по запросу</span>
                    <?}
                    else {?>
                        <span class="nowrap"><?=priceFormat($arItem['PRICE']); ?> <i class='rouble'>a</i></span>
                    <?}?>
                </span>
                <?if($arItem['OLD_PRICE'] && (int)$arItem['PRICE'] > 0):?>
                    <span class='pLineOld'><?=priceFormat($arItem['OLD_PRICE']);?>Р</span>
                <?endif;?>
                <?
                if ($arItem["PROPERTIES"]["GOODS_BONUS"]["VALUE"] != "") {
                    echo "<span class='pBonuses'>" . $arItem["PROPERTIES"]["GOODS_BONUS"]["VALUE"] . " " . GetMessage("BONUS_WORD_" . wordForm($arItem["PROPERTIES"]["GOODS_BONUS"]["VALUE"])) . "</span>";
                }
                ?>
            </div>
            <div class="pCart-line clearfix">
                <?php
                if ($arItem['ADD_URL']) {?>
                    <div class="rollover<?=$arItem['IN_BASKET'] ? ' active' : ''?> grid-flipper">
                        <div class="flip-side rollover__front">
                            <a href="/basket/ajax/?id=<?= $arItem['ID']; ?>"
                               class="cat-incart basket-add"
                               title="<?=GetMessage("ADD_TO_CART");?>"
                               data-picture="product_picture_<?= $arItem['ID']; ?>">
                                <?=GetMessage("CATALOG_ADD");?>
                            </a>
                        </div>
                        <div class="flip-side rollover__bottom">
                            <form>
                                <input class="input-basket-count" data-href="/basket/ajax/?id=<?= $arItem['ID']; ?>&action=update" maxlength="7" type="text" data-product="<?= $arItem['ID']; ?>" value="1">
                                <button class="removeProduct" data-href="/basket/ajax/?id=<?= $arItem['ID']; ?>&action=deleteFast" data-product="<?= $arItem['ID']; ?>" type="reset"></button>
                            </form>
                        </div>
                    </div>
                <?}?>
                <div class="pCartButtons pCartButtons-line clearfix">

                    <? if ($USER->IsAuthorized()): ?>
                        <a href="#" data-id="<?= $arItem['ID']; ?>" title="Избранное"
                           class="catalog-favorite-toggle<? if ($arItem['IN_FAVORITES']): ?> active<? endif; ?>"></a>
                    <? else:
                        $authUrl = "/auth/?backurl=".urlencode($APPLICATION->GetCurPageParam("favorite={$arItem['ID']}", array("favorite")));?>
                        <a href="<?=$authUrl?>" title="Избранное" class="catalog-favorite-toggle unauthorized"></a>
                    <?endif; ?>


                    <?php

                    if (!$arItem['IN_COMPARE_LIST']) {
                        if ($arItem['COMPARE_URL']) {
                            echo "<a href='#' class='j-animate-short j-add-to-compare' data-id='" . $arItem['ID'] . "' title='" . GetMessage("ADD_TO_COMPARE") . "'></a>";
                        }
                    } else {
                        if ($arItem['COMPARE_DELETE_URL']) {
                            echo "<a href='#' data-id='" . $arItem['ID'] . "' class='active j-delete-from-compare' title='" . GetMessage("DEL_FROM_COMPARE") . "'></a>";
                        }
                    }
                    ?>
                </div>
                <div class="pCartQuantity">

                    <? if (!empty($arItem["PROPERTIES"]["ANALOGS"]["VALUE"])): ?>
                        <span class='pAnalog'>
                            <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>"
                               class="analog-title">Аналоги</a>
                            </span>
                    <? endif; ?>

                    <?php
                        if($bisTrustedUser){
			echo "<em>Остаток: </em>";
                    ?>
                        <em class=''><?=getProductRestsInterval($arItem);//=$arItem['CATALOG_QUANTITY'];?></em>
                        <?}?>
                </div>
            </div>
        </section>
    </article>
    <? if (is_array($arItem['SERIES'])): ?>

        <section class="searchSeries clearfix">
            <ul class="clearfix">
                <? foreach ($arItem['SERIES']['ITEMS'] as $arSeriesItem): ?>
                    <li>
                        <a href="<?= $arSeriesItem['DETAIL_PAGE_URL']; ?>" title="<?= $arSeriesItem['NAME']; ?>"
                           style="background-image: url('<?= $arSeriesItem['PICTURE']; ?>');" class="picture">
                            <img src="<?= $arSeriesItem['PICTURE']; ?>" alt="<?= $arSeriesItem['NAME']; ?>">
                        </a>
                    </li>
                <? endforeach; ?>
                <li>
                    <aside class="seriesOverview">
                        <h1><?= $arItem['SERIES']['NAME']; ?></h1>

                        <p><?= $arItem['SERIES']['PREVIEW_TEXT']; ?></p>

                        <a href="<?= $arItem['SERIES']['DETAIL_PAGE_URL']; ?>" class="more">Смотреть
                            все <?= $arItem['SERIES']['COUNT']; ?></a>
                    </aside>
                </li>
            </ul>
        </section>

    <? endif; ?>
    <?
    if ($arParams['DISPLAY_BANNERS'] == 'Y' && $count == 8) {
        echo "<div class='clear'></div><div class='banner-sale'>";
        $APPLICATION->IncludeFile('/include/text_catalog_inc.php', array(),
                                  array(
                                      'MODE' => 'html',
                                      'TEMPLATE' => 'text_catalog_inc.php',
                                  )
        );
        echo "</div><div class='clear'></div>";
    }
    $count++;
}?>