<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>



<?
/**
 * Кол-во ячеек в строке
 */
$rowSize = $arParams['LINE_ELEMENT_COUNT'] > 0 ? $arParams['LINE_ELEMENT_COUNT'] : 5;
$slideSize = $arParams['SLIDE_ELEMENT_COUNT'] > 0 ? $arParams['SLIDE_ELEMENT_COUNT'] : 10;

$pageNum = 1;
$bisTrustedUser = isTrustedUser(); // пользователю разрешено показ остатков
?>

<? if (!empty($arResult['ITEMS'])): ?>

	<ul class="grid-carousel">

	<li>
	<div class="container">
	<div class="twelve mainpage-products">


    <?
    $strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
    $strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
    $arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
    ?>


    <div class="cat-block">

    <div class="products-grid cat-products clearfix j_cat_product_list">
    <?
    $count = 1;
    foreach ($arResult['ITEMS'] as $key => $arItem):?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
        $strMainID = $this->GetEditAreaId($arItem['ID']);
        ?>
        <div class="cell col-xlg-3 col-lg-3 col-md-3" id="<? echo $strMainID; ?>">
            <div class="wrapper clearfix">
                <div class="cat-product j_cat_product_card">
                    <div class='product-image'>

                        <? if ($arItem["PROPERTIES"]["IS_NEW"]["VALUE"] == "Y"): ?>
                            <img src='<?= SITE_TEMPLATE_PATH; ?>/img/catalog/new.png'
                                 alt='<?= $arItem["PROPERTIES"]["IS_NEW"]["NAME"]; ?>' class='new-in-img'>
                        <? endif; ?>

                        <? if ($arItem['PROPERTIES']['IS_BESTSELLER']['VALUE'] == 'Y'): ?>
                            <img src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/bestseller.png" alt="Лидер продаж"
                                 class="new-in-img">
                        <? endif; ?>


                        <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="product-name"
                           title="<?= $arItem['NAME']; ?>">
                            <?= TruncateText($arItem['NAME'], 50); ?>
                        </a>

                            <? if (!empty($arItem['CODE'])): ?>
                                <div
                                    class="product-number"><?= 'Референс: '.$arItem['CODE']; ?></div>
                            <? endif; ?>

                            <? if (!empty($arItem['PROPERTIES']['SKU']['VALUE'])): ?>
                                <div
                                    class="product-number-ref"><?= 'Артикул '.$arItem['PROPERTIES']['SKU']['VALUE']; ?></div>
                            <? endif; ?>

                        <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="picture"
                           style="background-position:left center;background-image:url('<?= $arItem['PICTURE']; ?>');">

                            <img
                                class="j-image-to-animate list-items-img j_preview_img_main_<?= $arItem['ID']; ?>"
                                id="product_picture_<?= $arItem['ID']; ?>"
                                src="<?= $arItem['PICTURE']; ?>"
                                alt="<?= $arItem['NAME']; ?>">

                        </a>
                        <div class="product-info">
                            <div class="row clearfix">
                                <? if (!empty($arItem['SPECIAL_TIMER'])): ?>
                                    <div class="cat-promo">
                                        <div class="cat-promo-time">
                                            <span class="text">До конца распродажи</span>

                                            <div
                                                class="digits cat-day"><?= $arItem['SPECIAL_TIMER']['DAYS']; ?></div>
                                            <span class="two-dots">:</span>

                                            <div
                                                class="digits cat-hour"><?= $arItem['SPECIAL_TIMER']['HOURS']; ?></div>
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
                                <? endif; ?>
                            </div>

                        </div>

                        <!--<div
                            class="cat-rating cat-value-raiting<? if (!empty($arItem['PROPERTIES']['RAITING']['VALUE'])): ?>-<?= $arItem['PROPERTIES']['RAITING']['VALUE']; ?><? endif; ?>"></div>-->

                            <span class="cat-price">
                                <?if($arItem['PRICE'] <= 0){?>
                                    <span class="request-price nowrap">Цена по запросу</span>
                                <?}
                                else {?>
                                    <span class="nowrap"><?=priceFormat($arItem['PRICE']); ?> <i class='rouble'>a</i></span>
                                <?}?>
                            </span>
                            <div class="price-stars">
                							<i class="zmdi zmdi-star"></i> 
                              <i class="zmdi zmdi-star"></i> 
                              <i class="zmdi zmdi-star"></i>
                							<i class="zmdi zmdi-star-half"></i> 
                              <i class="zmdi zmdi-star-outline"></i>
                						</div>

                        <? if (false): //($arItem['OLD_PRICE'] > 0 && (int)$arItem['PRICE'] > 0): ?>
                            <span class="pLineOld old-pirce-catalog-full-last">
                                <span class="nowrap"><?= priceFormat($arItem['OLD_PRICE']); ?> <i class='rouble'>a</i></span>
                            </span>
                        <? endif; ?>

                        <? if (!empty($arItem['PROPERTIES']['GOODS_PHOTOS']['VALUE'])): ?>
                            <div class="thumbnails hidden">
                                <ul class="owl-slider j_owl_slider_3">
                                    <? if ($arItem['PREVIEW_PICTURE']['SRC']): ?>
                                        <li class="j_preview_img active" data-id="<?= $arItem["ID"]; ?>">
                                            <img src="<?= $arItem['PREVIEW_PICTURE']['SRC']; ?>">
                                        </li>
                                    <? endif; ?>
                                    <? foreach ($arItem["PROPERTIES"]["GOODS_PHOTOS"]["VALUE"] as $arPhoto): ?>
                                        <li class="j_preview_img active" data-id="<?= $arItem["ID"]; ?>">
                                            <img src="<?= $arPhoto['SRC']; ?>">
                                        </li>
                                    <? endforeach; ?>
                                </ul>
                            </div>
                        <? endif; ?>
                    </div>
                </div>

                <div class="cat-hoverbox hidden">
                    <? if (!empty($arItem["PROPERTIES"]["ANALOGS"]["VALUE"])): ?>
                        <div class="box-analog">
                            <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="analog-title">
                                <?= GetMessage("ANALOG_TITLE"); ?>
                            </a>
                        </div>
                    <? endif; ?>
                    <?if (false):?>
                    <div class="row clearfix">
                        <? if ($arItem['ADD_URL']): ?>
                            <div class="rollover<?=$arItem['IN_BASKET'] ? ' active' : ''?> grid-flipper">
                                <div class="flip-side rollover__front">
                                    <a href="/basket/ajax/?id=<?= $arItem['ID']; ?>"
                                       class="cat-incart basket-add"
                                       title="<?=GetMessage("ADD_TO_CART");?>"
                                       data-picture="product_picture_<?= $arItem['ID']; ?>">
                                        <?=GetMessage("ADD_TO_CART");?>
                                    </a>
                                </div>
                                <div class="flip-side rollover__bottom">
                                    <form>
                                        <input class="input-basket-count" data-href="/basket/ajax/?id=<?= $arItem['ID']; ?>&action=update" maxlength="7" type="text" data-product="<?= $arItem['ID']; ?>" value="1">
                                        <button class="removeProduct" data-href="/basket/ajax/?id=<?= $arItem['ID']; ?>&action=deleteFast" data-product="<?= $arItem['ID']; ?>" type="reset"></button>
                                    </form>
                                </div>
                            </div><?
                        endif;
                        ?><div class="like-block">

                            <? if ($USER->IsAuthorized()): ?>
                                <a href="#" data-id="<?= $arItem['ID']; ?>" title="Избранное"
                                   class="cat-like catalog-favorite-toggle<? if ($arItem['IN_FAVORITES']): ?> active<? endif; ?>"></a>
                            <? else: ?>
                                <a href="/auth/" class="cat-like" title="Избранное"></a>
                            <?endif; ?>

                            <?
                            if (!$arItem['IN_COMPARE_LIST']) {
                                if ($arItem['COMPARE_URL']) {
                                    echo "<a href='#' data-id='" . $arItem['ID'] . "' class='cat-compare j-animate-img-full j-add-to-compare' title='" . GetMessage("ADD_TO_COMPARE") . "'></a>";
                                }
                            } else {
                                if ($arItem['COMPARE_DELETE_URL']) {
                                    echo "<a href='#' data-id='" . $arItem['ID'] . "' class='cat-compare active j-delete-from-compare' title='" . GetMessage("DEL_FROM_COMPARE") . "'></a>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?endif;?>

                    <?if (false): //($bisTrustedUser): ?>
                    <div class="row center row-rest">
                        <span class="cat-instock"><?= GetMessage("REST"); ?></span>

                            <?//=$arItem['CATALOG_QUANTITY']?>
                            <?=getProductRestsInterval($arItem)?>
                    </div>
                        <?endif;?>
                    <div class="row">
                        <div class="cat-technicals">
                            <table class="pTechnicalsMain">
                                <? foreach ($arItem['DISPLAY_PROPERTIES'] as $arProp): ?>
                                    <? if (empty($arProp['DISPLAY_VALUE'])) continue; ?>
                                    <? if (!$arProp['IS_PACKING']): ?>
                                        <tr>
                                            <th><?= $arProp['NAME']; ?></th>
                                            <td><?= strip_tags($arProp['DISPLAY_VALUE']); ?></td>
                                        </tr>
                                    <?endif;?>
                                <? endforeach; ?>
                                <? foreach ($arItem['DISPLAY_PROPERTIES'] as $arProp): ?>
                                    <? if (empty($arProp['DISPLAY_VALUE'])) continue; ?>
                                    <? if ($arProp['IS_PACKING']):?>
                                        <?if (isset($arProp['VALUE_PRINT'])):?>
                                            <tr>
                                                <th colspan="2" style="text-align: center"><?= $arProp['NAME']; ?></th>
                                            </tr>
                                            <? foreach ($arProp['VALUE_PRINT'] as $arValue): ?>
                                                <tr>
                                                    <th><?= mb_ucfirst($arValue[0]); ?></th>
                                                    <td><?= $arValue[1]; ?></td>
                                                </tr>
                                            <? endforeach; ?>
                                        <?endif;?>
                                    <?endif;?>
                                <? endforeach; ?>
                            </table>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <?
        if ($count != count($arResult['ITEMS'])) {
            if ($count % $rowSize == 0 && $count % $slideSize != 0) {
                echo "</div><div class='products-grid cat-products clearfix j_cat_product_list'>";
            }

			if($count % $slideSize == 0):?>

								</div>
							</div>
						</div> <!--container-->
					</div> <!--twelve-->
				</li>
				<li>
					<div class="container">
						<div class='twelve mainpage-products'>
							<div class='cat-block'>
								<div class='products-grid cat-products clearfix j_cat_product_list'>
			<?endif;
        }



		$count++;
        ?>
    <? endforeach; ?>
    </div>
    </div>

	</div> <!--container-->
	</div> <!--twelve-->
	</li>
	</ul>

<? endif; ?>

