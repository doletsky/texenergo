<?php
//if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
    <!--<pre>--><?//print_r($arResult)?><!--</pre>-->
<?if(1):?>
    <?foreach ($arResult["ITEMS"] as $arItem):?>
        <div class="product_item product_item__padd col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xlg-2">
            <div class="inner">
                <h3 class="product_item__header"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></h3>
                <span class="product_item___disc">%</span>
                <div class="product_item__vendor-code">Артикул: <span class="vendor-code"><?=$arItem["PROPERTIES"]["SKU"]["VALUE"]?></span></div>
                <div class="product_item__img">
                    <!--<div class="row">
                        <div class="col-xs-6">-->
                    <?if(is_array($arItem["DETAIL_PICTURE"])):?>
                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                            <img class="img-responsive"
                                 src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>"
                                 alt="<?=$arItem["DETAIL_PICTURE"]["ALT"]?>"
                                 id="product_picture_<?=$arItem["ID"]?>" />
                        </a>
                    <?endif?>
                    <!--</div>
                    <div class="col-xs-6">
                        <div class="img-mini img-responsive" id="product_picture_459548" style="background-image: url(https://www.texenergo.ru/upload/resize_cache/thumbnail_photo/7f/7f291be8-a4f8-4aba-b71e-2357c8756dec/photos_7f291be8-a4f8-4aba-b71e-2357c8756dec_001_130_130.jpg)"></div>
                        <div class="img-mini img-responsive" id="product_picture_459548" style="background-image: url(https://www.texenergo.ru/upload/resize_cache/thumbnail_photo/7f/7f291be8-a4f8-4aba-b71e-2357c8756dec/photos_7f291be8-a4f8-4aba-b71e-2357c8756dec_001_130_130.jpg)"></div>
                    </div>
                </div>-->
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        Референс: <span class="reference"><?=$arItem["CODE"]?></span>
                    </div>
                    <div class="col-xs-6 round_nav_string" style="text-align: right;padding-top: 20px;">
                        <div class="round_nav round_nav_left"><</div>
                        <div class="round_nav round_nav_right">></div>
                    </div>
                </div>
                <div class="row">
                    <div class="product_item__price clearfix col-xlg-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="price col-xlg-6 col-lg-6 col-md-6 col-sm-6 col-xs-6"><?=$arItem["PRICE"]?>
                            <i class="rouble">a</i>
                        </div>
                        <div class="old-price col-xlg-6 col-lg-6 col-md-6 col-sm-6 col-xs-6"><?=$arItem["OLD_PRICE"]?>.00 <i
                                    class="rouble">a</i></div>
                    </div>
                </div>
                <div class="row lower-price-bottom-row">
                    <div class="col-xs-5 lower-price-stars">
                        <i class="zmdi zmdi-star"></i> <i class="zmdi zmdi-star"></i> <i class="zmdi zmdi-star"></i>
                        <i class="zmdi zmdi-star-half"></i> <i class="zmdi zmdi-star-outline"></i>
                    </div>

                    <div class="button-desc col-xs-6">
                        <div class="text-compare">Сравнить</div>
                        <div class="text-favorite">Избранное</div>
                        <div class="text-params">Параметры</div>
                    </div>
                </div>
                <div class="row">
                    <div class="lower-price-CartButtons pCartButtons pCartButtons-line clearfix blk-product__basket col-xlg-12 col-lg-12 col-md-12">
                        <div class="col-xs-6" style="position: relative;right: 10px;text-align: left">
                            <div class="pCart-line clearfix">
                                <div class="rollover grid-flipper">
                                    <div class="flip-side rollover__front">
                                        <a href="/basket/ajax/?id=<?=$arItem["ID"]?>" class="cat-incart basket-add" title="" data-picture="product_picture_<?=$arItem["ID"]?>"> В корзину </a>
                                    </div>
                                    <div class="flip-side rollover__bottom">
                                        <form>
                                            <input class="input-basket-count" data-href="/basket/ajax/?id=<?=$arItem["ID"]?>&action=update" maxlength="7" type="text" data-product="<?=$arItem["ID"]?>" value="1">
                                            <button class="removeProduct" data-href="/basket/ajax/?id=<?=$arItem["ID"]?>&action=deleteFast" data-product="<?=$arItem["ID"]?>" type="reset">
                                                <i class="zmdi zmdi-close-circle-o"></i></button>
                                        </form>
                                    </div>
                                </div>
                                <div class="pCartButtons pCartButtons-line clearfix">
                                    <a href="/auth/?backurl=%2Fsale%2F%3Ffavorite%3D361806" title="Избранное" class="catalog-favorite-toggle unauthorized"></a>
                                    <a href="#" class="j-animate-short j-add-to-compare" data-id="<?=$arItem["ID"]?>" title="Добавить в сравнение"></a>
                                </div>
                                <div class="pCartQuantity">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6" style="padding: 10px 2px;text-align: right">
                            <a href="/auth/?backurl=%2Fcatalog%2Flist.html%2F90000005%3Ffavorite%3D320310" data-id="<?=$arItem["ID"]?>" title="Избранное" class="favor catalog-favorite-toggle blk-product__ico unauthorized"><i class="zmdi zmdi-favorite-outline"></i></a>
                            <a href="#" class="j-anim blk-product__ico catalog-params" data-id="<?=$arItem["ID"]?>" title="Характеристики товара"><span class="lnr lnr-cog"></span></a>
                            <a href="#" class="j-animate-short j-add-to-compare blk-product__ico" data-id="<?=$arItem["ID"]?>" title="Добавить в сравнение"><i class="zmdi zmdi-sort-amount-desc zmdi-sort-amount-desc-rotate"></i></a>
                        </div>
                    </div>
                </div><!--/.row-->
            </div><!--/.inner-->
        </div>
    <?endforeach;?>
<?endif;?>