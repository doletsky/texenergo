<?

$strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
$strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
$arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));

global $gl_options_sale_product_day_text;
$count = 1;
foreach ($part as $key => $arItem) {
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
    $strMainID = $this->GetEditAreaId($arItem['ID']);
    ?>
<!--    <article class="pProductInLine clearfix blk-product__item first col-xlg-6 col-lg-6 col-md-6 col-sm-6 col-xs-12" id="<? echo $strMainID; ?>">-->
    <article class="pProductInLine blk-product__item first col-xlg-6 col-lg-6 col-md-6 col-sm-6 col-xs-12" id="<? echo $strMainID; ?>">
          <h3 class="product-day__header">      
            <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>"
               class="name name-pline"><?= $arItem["NAME"] ?></a>
          </h3>
<?if (false):?>
          <div class="row">
            <div class="blk-product__vendor-code col-xlg-5 col-lg-5 col-md-6 col-sm-6 col-xs-12">
           <? if ($arItem["PROPERTIES"]["SKU"]["VALUE"] != "") {
                echo "Артикул: <span class='vendor-code'>" . $arItem["PROPERTIES"]["SKU"]["VALUE"] . "</span><br>";
            }
            if ($arItem["PROPERTIES"]["REFERENCE"]["VALUE"] != "") {
                echo "Референс: <span class='reference'>" . $arItem["PROPERTIES"]["REFERENCE"]["VALUE"] . "</span>";
            }?>
            </div>
            <div>
              <i class="zmdi zmdi-star"></i>
              <i class="zmdi zmdi-star"></i>
              <i class="zmdi zmdi-star"></i>
              <i class="zmdi zmdi-star-half"></i>
              <i class="zmdi zmdi-star-outline"></i>
            </div>
          </div>
<?endif;?>
        <div class="row" style="width:150px;">
              <?php
                  //pr(empty($arItem['PROPERTIES']['PHOTOS_PRODUCT']['VALUE']));
                  if(!empty($arItem['PROPERTIES']['PHOTOS_PRODUCT']['VALUE'])) {
                  //pr($arItem['PROPERTIES']['PHOTOS_PRODUCT']['VALUE']);
                  $count_items = 1;
                                  $arPhotosProducts = array();
                  foreach($arItem['PROPERTIES']['PHOTOS_PRODUCT']['VALUE'] as $sPhotosProductXmlId){
                      if(!is_dir($_SERVER['DOCUMENT_ROOT'] . '/upload/resize_cache/import')){
                          mkdir($_SERVER['DOCUMENT_ROOT'] . '/upload/resize_cache/import', 0775, true);
                      }
                      $arPhotosProduct = CIBlockElement::GetList(array(), array('XML_ID' => $sPhotosProductXmlId), false, false, array('ID', 'NAME', 'PROPERTY_PATH'))->GetNext();
                      $sSourceName = $arResult['impotr_folder'] . $arPhotosProduct['PROPERTY_PATH_VALUE'];
                      $sContent = file_get_contents($sSourceName);
                      $sSourceName = $_SERVER['DOCUMENT_ROOT'] . '/upload/resize_cache/import/'.$sPhotosProductXmlId.'.jpg';
                      file_put_contents($sSourceName, $sContent);
                      $sDestinationName = $_SERVER["DOCUMENT_ROOT"]."/upload/resize_cache/import/".$sPhotosProductXmlId;

                      if(!file_exists($sDestinationName.'_small.jpg')){
                          CFile::ResizeImageFile($sSourceName, $sDestinationNameSmall = $sDestinationName . '_small.jpg', array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, array(), false, array());
                      }
                      if(!file_exists($sDestinationName.'_preview.jpg')){
                          CFile::ResizeImageFile($sSourceName, $sDestinationNamePreview = $sDestinationName . '_preview.jpg', array('width' => 320, 'height' => 320), BX_RESIZE_IMAGE_PROPORTIONAL, array(), false, array());
                      }
                      unlink($sSourceName);
                      $sSourceName = $arResult['impotr_folder'] . $arPhotosProduct['PROPERTY_PATH_VALUE'];
                      if ( $count_items > 4 ) break;
                      $arPhotosProducts[] = array('FULL' => $sSourceName, 'PREVIEW' => '/upload/resize_cache/import/'.$sPhotosProductXmlId.'_preview.jpg', 'SMALL' => '/upload/resize_cache/import/'.$sPhotosProductXmlId.'_small.jpg');
                      $count_items++;
                  }
  }
  //pr($arPhotosProducts);
  ?>
              <div class="product-photos j_owl_slider_4 owl-theme owl-slider" style="width:150px;">
                  <? foreach ($arPhotosProducts as $arPhoto): ?>
                      <a href="<?= $arPhoto['FULL']; ?>" class="photo" data-fancybox-group="fancybox-thumb"
                         data-preview="<?= $arPhoto['PREVIEW']; ?>" target="_blank"
                         style="background-image:url('<?= $arPhoto['SMALL']; ?>');">
                          <img src="<?= $arPhoto['SMALL']; ?>" alt="<?= $arResult['NAME']; ?>" title="<?= $arResult['NAME']; ?>">
                      </a>
                  <? endforeach; ?>
              </div>
        </div>
        <div class="row">
            <div class="col-xlg-1 col-lg-1 col-md-1">

            </div>
        <section class="col-xlg-5 col-lg-5 col-md-6 col-sm-12 col-xs-12">
          <div class="pLineImage blk-product__img">
            <? if ($arItem["PROPERTIES"]["IS_NEW"]["VALUE"] == "Y"): ?>
                <img src='<?= SITE_TEMPLATE_PATH; ?>/img/catalog/new.png'
                     alt='<?= $arItem["PROPERTIES"]["IS_NEW"]["NAME"]; ?>' class='new-in-img'>
            <? endif; ?>

            <? if ($arItem['PROPERTIES']['IS_BESTSELLER']['VALUE'] == 'Y'): ?>
                <img src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/bestseller.png" alt="Лидер продаж"
                     class="new-in-img">
            <? endif; ?>
            <figure class="pProductImage pProductImage-line">
              <div class="pProductImage__header_left"></div>
              <div class="pProductImage__header"><span class="pProductImage__header_text">Товар дня</span><i class="pProductImage__header_ico zmdi zmdi-chevron-down"></i></div>
              <div class="pProductImage__header_right"></div>
              <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="">
                <?php
                echo "<img id='product_picture_".$arItem['ID']."' src='" . $arItem["PICTURE"] . "' alt='" . $arItem["NAME"] . "' class='j_product_of_day j-image-to-animate j_preview_img_main_" . $arItem["ID"] . " big-last-full-img' />";

                ?>
              <div class="img-product-day-text" style="display:none;">
              <? foreach($gl_options_sale_product_day_text as $ItemText){
                $arText = explode('|',$ItemText);
                $ref = $arText[0];
                if ($ref == $arItem["PROPERTIES"]["REFERENCE"]["VALUE"]) {
                  echo $arText[1];
                  break;
                  }
              }?>
              </div>
              </a>
            </figure>
<?                
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
          </div>
        </section>
        <section class="pLineOverview  col-xlg-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="lower-price-stars">
							<i class="zmdi zmdi-star"></i>
							<i class="zmdi zmdi-star"></i>
							<i class="zmdi zmdi-star"></i>
							<i class="zmdi zmdi-star"></i>
							<i class="zmdi zmdi-star"></i>
						</div>
            <?
            if ($arItem["PREVIEW_TEXT"] != "") {
                echo "<div class='copy-pline blk-product__text'><a href='". $arItem['DETAIL_PAGE_URL']. "'>" . TruncateText($arItem["PREVIEW_TEXT"],175) . "</a></div>";
            }
            if ($arItem['PROPERTIES']['GOODS_FILTER_VALUE']['VALUE'] != "") {
                foreach ($arItem['PROPERTIES']['GOODS_FILTER_VALUE']['VALUE_NAMES'] as $filterValue) {
                    //echo "<div class='row-pline'><span class='spanFirst'>" . $filterValue["FILTER_VARS"]["NAME"] . ":</span><span class='spanSecond'>" . $filterValue["NAME"] . "</span></div>";
                }
            }
            ?>
<!--        </section>-->
<!--      </div>--><!--/.row-->
        <section class="pLineCart">

            <?php
            if ($arItem['PROPERTIES']['BLOG_COMMENTS_CNT']['VALUE'] != "") {
                echo "<span>" . $arItem['PROPERTIES']['COMMENTS_CNT']['VALUE'] . " " . GetMessage("REVIEWS_" . wordForm($arItem['PROPERTIES']['COMMENTS_CNT']['VALUE'])) . "</span>";
            }
            ?>
            <div class="pPriceBlock">
                <span class='pLinePrice'>
                    <?if($arItem['PRICE'] <= 0){?>
                        <span class="request-price nowrap price">Цена по запросу</span>
                    <?}
                    else {?>
                        <span class="nowrap price"><?=priceFormat($arItem['PRICE']); ?> <i class='rouble'>a</i></span> 
                        <span class="nowrap price old-price"><?=priceFormat($arItem['OLD_PRICE']); ?> <i class='rouble'>a</i></span>
                    <?}?>
                </span>
                <?
                if ($arItem["PROPERTIES"]["GOODS_BONUS"]["VALUE"] != "") {
                    echo "<span class='pBonuses'>" . $arItem["PROPERTIES"]["GOODS_BONUS"]["VALUE"] . " " . GetMessage("BONUS_WORD_" . wordForm($arItem["PROPERTIES"]["GOODS_BONUS"]["VALUE"])) . "</span>";
                }
                ?>
            </div>
            <div class="row">
            <div class="pCart-line clearfix col-xlg-5 col-lg-5 col-md-5">
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
                                <button class="removeProduct" data-href="/basket/ajax/?id=<?= $arItem['ID']; ?>&action=deleteFast" data-product="<?= $arItem['ID']; ?>" type="reset"><i class="zmdi zmdi-close-circle-o"></i></button>
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
<?if (false):?>
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
<?endif;?>
            </div><!--/.pCart-line-->
              <div class="col-xlg-7 col-lg-7 col-md-7" style="padding: 10px 2px;">
								<a href="/auth/?backurl=%2Fcatalog%2Flist.html%2F90000005%3Ffavorite%3D320310" data-id="320310" title="Избранное" class="favor catalog-favorite-toggle blk-product__ico unauthorized"><i class="zmdi zmdi-favorite-outline"></i></a>
								<a href="#" class="j-anim blk-product__ico catalog-params" data-id="320310" title="Характеристики товара"><span class="lnr lnr-cog"></span></a>
								<a href="#" class="j-animate-short j-add-to-compare blk-product__ico" data-id="320310" title="Добавить в сравнение"><i class="zmdi zmdi-sort-amount-desc zmdi-sort-amount-desc-rotate"></i></a>
							</div>
            </div><!--./row-->
            <? if (!empty($arItem['SPECIAL_TIMER'])): ?>
                <!--<td>-->
                    <div class="cat-promo">
                        <ul class="cat-promo-captions row">
                            <li class="col-xlg-2 col-lg-2 col-md-2">дней</li>
                            <li class="col-xlg-2 col-lg-2 col-md-2">часов</li>
                            <li class="col-xlg-2 col-lg-2 col-md-2">минут</li>
                        </ul>
                        <div class="cat-promo-time row">

                            <div class="digits cat-day col-xlg-2 col-lg-2 col-md-2"><?= $arItem['SPECIAL_TIMER']['DAYS']; ?></div>
                            <div class="digits cat-hour col-xlg-2 col-lg-2 col-md-2"><?= $arItem['SPECIAL_TIMER']['HOURS']; ?></div>                            
                            <div
                                class="digits cat-min col-xlg-2 col-lg-2 col-md-2"><?= $arItem['SPECIAL_TIMER']['MINUTES']; ?></div>
                            <span class="text col-xlg-6 col-lg-6 col-md-6">&#8212; до конца акции</span>          
                        </div>
                    </div>
                <!--</td>-->
            <? endif; ?>

        </section>
        </section>
      </div><!--/.row-->
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