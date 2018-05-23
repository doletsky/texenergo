<table class="products-list">
    <thead>
    <tr>
        <td>&nbsp;</td>
        <td><?=GetMessage("MES_NAME");?></td>
        <td>
            <? if($arParams['SHOW_SPECIAL_TIMER'] == 'Y'): ?>
                &nbsp;
            <? else: ?>
                <?=GetMessage("MES_RATE");?>
            <? endif; ?>
        </td>
        <td><?=GetMessage("MES_PRICE");?></td>
 	<? if ($bisTrustedUser) :?>
        <td class="product-rest"><?=GetMessage("MES_REST");?></td>
        <? endif; ?>
        <td><?=GetMessage("MES_BUY");?></td>
    </tr>
    </thead>
    <tbody>
    <?php
    $count = 0;
    $count2 = 1;
    foreach ($part as $key => $arItem):
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
    $strMainID = $this->GetEditAreaId($arItem['ID']);
    ?>
    <tr id="<?=$strMainID?>">
        <td class="line-thumbnail">
            <? if($arItem["PROPERTIES"]["IS_NEW"]["VALUE"] == "Y"): ?>
                <img src='<?=SITE_TEMPLATE_PATH;?>/img/catalog/new.png'
                     alt='<?=$arItem["PROPERTIES"]["IS_NEW"]["NAME"];?>' class='new-in-img'>
            <? endif; ?>

            <? if($arItem['PROPERTIES']['IS_BESTSELLER']['VALUE'] == 'Y'): ?>
                <img src="<?=SITE_TEMPLATE_PATH;?>/img/catalog/bestseller.png" alt="Лидер продаж"
                     class="new-in-img">
            <? endif; ?>

            <div class="wrap">
                <a href="<?=$arItem['DETAIL_PAGE_URL'];?>">
                    <?
                    if(!empty($arItem['PROPERTIES']['PHOTOS_PRODUCT']['VALUE'][0])){
                        $sPhotosProductXmlId = $arItem['PROPERTIES']['PHOTOS_PRODUCT']['VALUE'][0];
                        $sDestinationName = $_SERVER["DOCUMENT_ROOT"]."/upload/resize_cache/import/".$sPhotosProductXmlId;
                        if(!is_dir($_SERVER['DOCUMENT_ROOT'] . '/upload/resize_cache/import')){
                            mkdir($_SERVER['DOCUMENT_ROOT'] . '/upload/resize_cache/import', 0775, true);
                        }
                        if(!file_exists($sDestinationName.'_small.jpg')){
                            $arPhotosProduct = CIBlockElement::GetList(array(), array('XML_ID' => $sPhotosProductXmlId), false, false, array('ID', 'NAME', 'PROPERTY_PATH'))->GetNext();
                            $sSourceName = 'https://www.texenergo.ru/upload/restrict' . $arPhotosProduct['PROPERTY_PATH_VALUE'];
                            $sContent = file_get_contents($sSourceName);
                            $sSourceName = $_SERVER['DOCUMENT_ROOT'] . '/upload/resize_cache/import/'.$sPhotosProductXmlId.'.jpg';
                            file_put_contents($sSourceName, $sContent);
                            CFile::ResizeImageFile($sSourceName, $sDestinationNameSmall = $sDestinationName . '_small.jpg', array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, array(), false, array());
                            unlink($sSourceName);
                        }
                        $arItem['PICTURE'] = '/upload/resize_cache/import/'.$sPhotosProductXmlId.'_small.jpg';
                    }
                    ?>
                    <img class="j-image-to-animate" src="<?=$arItem["PICTURE"];?>"
                         alt="<?=$arItem["NAME"];?>" id="product_picture_<?=$arItem['ID'];?>">

                </a>

                <? if($arItem['OLD_PRICE'] > 0): ?>
                    <div class="cat-discount catDiscount-cart"></div>
                <? endif; ?>
            </div>
        </td>

        <td class="line-product-info">
            <a href="<?=$arItem['DETAIL_PAGE_URL'];?>" class="product-name product-name-line">
                <?=$arItem['NAME'];?>
            </a>
            <? if($arItem["PROPERTIES"]["SKU"]["VALUE"]): ?>
                <span class="product-number"><?=$arItem["PROPERTIES"]["SKU"]["VALUE"];?></span>
            <? endif; ?>
        </td>


        <? if(!empty($arItem['SPECIAL_TIMER'])): ?>
            <td>
                <div class="cat-promo">
                    <div class="cat-promo-time">
                        <span class="text">До конца распродажи</span>

                        <div class="digits cat-day"><?=$arItem['SPECIAL_TIMER']['DAYS'];?></div>
                        <span class="two-dots">:</span>

                        <div class="digits cat-hour"><?=$arItem['SPECIAL_TIMER']['HOURS'];?></div>
                        <span class="two-dots">:</span>

                        <div
                            class="digits cat-min"><?=$arItem['SPECIAL_TIMER']['MINUTES'];?></div>
                    </div>
                    <ul class="cat-promo-captions">
                        <li>день</li>
                        <li>час</li>
                        <li>мин</li>
                    </ul>
                </div>
            </td>
        <? else: ?>

            <td class="rating-col">
                <div
                    class="cat-rating cat-value-raiting<? if(!empty($arItem['PROPERTIES']['RAITING']['VALUE'])): ?>-<?=$arItem['PROPERTIES']['RAITING']['VALUE'];?><? endif; ?>"></div>
            </td>
        <? endif; ?>


        <td class="last-list-price-col">

                <span class="cat-price cat-price-line">
                    <?if($arItem['PRICE'] <= 0) {
                        ?>
                        <span class="request-price nowrap">Цена по запросу</span>
                    <?
                    }
                    else { ?>
                        <span class="nowrap"><?=priceFormat($arItem['PRICE']);?> <i class='rouble'>a</i></span>
                    <?
                    }?>
                </span>
            <? if($arItem['OLD_PRICE'] && (int)$arItem['PRICE'] > 0): ?>
                <span class="pLineOld old-price-last-list">
                        <span class="nowrap"><?=priceFormat($arItem['OLD_PRICE']);?> <i class='rouble'>a</i></span>
                    </span>
            <? endif; ?>

        </td>
 	<? if ($bisTrustedUser) :?>        
	<td class="product-rest bottom-rest">
                <?//=$arItem['CATALOG_QUANTITY']?>
		<?=getProductRestsInterval($arItem)?>
	</td>
        <? endif; ?>        
	<td class="product-icons cat-line-icons">
            <div class="nowrap">
                <? if($arItem['ADD_URL']): ?>
                    <div class="rollover<?=$arItem['IN_BASKET'] ? ' active' : ''?> list-flipper">
                        <div class="flip-side rollover__front">
                            <a href="/basket/ajax/?id=<?=$arItem['ID'];?>"
                               class="cart-line basket-add"
                               title="<?=GetMessage("ADD_TO_CART");?>"
                               data-picture="product_picture_<?=$arItem['ID'];?>">
                                <img src="<?=SITE_TEMPLATE_PATH;?>/img/catalog/cart-line.png"
                                     alt="" width="36" height="36">
                            </a>
                        </div>
                        <div class="flip-side rollover__bottom">
                            <form>
                                <input class="input-basket-count"
                                       data-href="/basket/ajax/?id=<?=$arItem['ID'];?>&action=update" maxlength="5"
                                       type="text" data-product="<?=$arItem['ID'];?>" value="1">
                                <button class="removeProduct"
                                        data-href="/basket/ajax/?id=<?=$arItem['ID'];?>&action=deleteFast"
                                        data-product="<?=$arItem['ID'];?>" type="reset"></button>
                            </form>
                        </div>
                    </div>
                <? endif; ?>

                <? if($USER->IsAuthorized()): ?>
                    <a href="#" data-id="<?=$arItem['ID'];?>" title="Избранное"
                       class="catalog-favorite-toggle<? if($arItem['IN_FAVORITES']): ?> active<? endif; ?>">
                        <img src="<?=SITE_TEMPLATE_PATH;?>/img/catalog/like-gray.png" alt="Избранное" width="24"
                             height="24">
                    </a>
                <? else:
                    $authUrl = "/auth/?backurl=".urlencode($APPLICATION->GetCurPageParam("favorite={$arItem['ID']}", array("favorite")));?>
                    <a href="<?=$authUrl?>" title="Избранное" class="catalog-favorite-toggle unauthorized">
                        <img src="<?=SITE_TEMPLATE_PATH;?>/img/catalog/like-gray.png" alt="Избранное" width="24"
                             height="24">
                    </a>
                <? endif; ?>

                <?
                if(!$arItem['IN_COMPARE_LIST']) {
                    if($arItem['COMPARE_URL']) {
                        echo "<a href='#' data-id='".$arItem['ID']."' class='j-img-animate-last-list j-add-to-compare' title='".GetMessage("ADD_TO_COMPARE")."'><img src='".SITE_TEMPLATE_PATH."/img/catalog/bars-gray.png' alt='".GetMessage("ADD_TO_COMPARE")."'></a>";
                    }
                }
                else {
                    if($arItem['COMPARE_DELETE_URL']) {
                        echo "<a href='#' data-id='".$arItem['ID']."' class='active j-delete-from-compare' title='".GetMessage("DEL_FROM_COMPARE")."'><img src='".SITE_TEMPLATE_PATH."/img/catalog/bars-gray.png' alt='".GetMessage("ADD_TO_COMPARE")."'></a>";
                    }
                }


                ?>
            </div>
        </td>
    </tr>
    <? if ($count == 8 && $countAll > 8): ?>
    </tbody>
</table>
<div class='clear'></div>
<div class='banner-sale'>
    <?
    $APPLICATION->IncludeFile(
        '/include/text_catalog_inc.php',
        array(),
        array(
            'MODE' => 'html',
            'TEMPLATE' => 'text_catalog_inc.php',
        )
    );
    ?>
</div>
<div class="clear"></div>
<table class="products-list">
    <tbody>
    <?endif;
    ?>
    <?
    $count++;
    $count2++;
    endforeach;
    ?>
    </tbody>
</table>