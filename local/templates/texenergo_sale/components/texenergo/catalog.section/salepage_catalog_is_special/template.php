<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<?if(1):?>
    <?foreach ($arResult["ITEMS"] as $arItem):?>
        <div class="product_item product_item__padd col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xlg-12">
            <div class="inner">
                <h3 class="product_item__header"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></h3>
                <span class="product_item___disc">%</span>
                <div class="product_item__vendor-code" <?if(strlen($arItem["PROPERTIES"]["SKU"]["VALUE"])<1):?>style="visibility: hidden;" <?endif?>>Артикул: <span class="vendor-code"><?=$arItem["PROPERTIES"]["SKU"]["VALUE"]?></span></div>
                <div class="product_item__img owl-subslider-salepage" id="<?=$arItem["ID"]?>">
                    <?if(!empty($arItem["PICTURE_PHOTOS_PRODUCT"])){
                        foreach ($arItem["PICTURE_PHOTOS_PRODUCT"] as $photoSrc){
                            ?>
                            <div class="row" style="height: 130px;">
                            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                                <img class="img-responsive item-img"
                                     src="<?=$photoSrc?>"
                                     id="product_picture_<?=$arItem["ID"]?>" />
                            </a>
                        </div>
                            <?
                        }
                    }else{
                        if(is_array($arItem["DETAIL_PICTURE"])):?>
                            <div class="row" style="height: 130px;">
                                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                                    <img class="img-responsive item-img"
                                         src="<?=$arItem['PICTURE']?>"
                                         alt="<?=$arItem["DETAIL_PICTURE"]["ALT"]?>"
                                         id="product_picture_<?=$arItem["ID"]?>" />
                                </a>
                            </div>
                        <?endif;
                    }?>

                </div>
                <?if(!empty($arItem["PICTURE_40"])):?>
                        <div class="block-min-img">
                            <div class="img-mini img-responsive" id="product_picture_<?=$arItem["ID"]?>" style="background-image: url(<?=$arItem["PICTURE_40"][0]?>); top:50px;"></div>
                            <div class="img-mini img-responsive" id="product_picture_<?=$arItem["ID"]?>" style="background-image: url(<?=$arItem["PICTURE_40"][1]?>); top:-50px;"></div>
                        </div>
                <?endif;?>
                <div class="row">
                    <div class="col-xs-6">
                        Референс: <span class="reference"><?=$arItem["CODE"]?></span>
                    </div>
                    <div class="col-xs-6 round_nav_string" style="text-align: right;padding-top: 20px;">
                        <div id="left<?=$arItem["ID"]?>" class="round_nav round_nav_left"><</div>
                        <div id="right<?=$arItem["ID"]?>" class="round_nav round_nav_right">></div>
                    </div>
                </div>
                <div class="row">
                    <div class="product_item__price clearfix col-xlg-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="price col-xlg-6 col-lg-6 col-md-6 col-sm-6 col-xs-6"><?=number_format($arItem["PRICE"], 2, '.', ' ')?>
                            <i class="rouble">a</i>
                        </div>
                        <div class="old-price col-xlg-6 col-lg-6 col-md-6 col-sm-6 col-xs-6" <?if((int)$arItem["OLD_PRICE"]==0):?>style="visibility: hidden;" <?endif?>><?=number_format($arItem["OLD_PRICE"], 2, '.', ' ')?> <i
                                    class="rouble">a</i></div>
                    </div>
                </div>
                <div class="row lower-price-bottom-row">
                    <div class="col-xs-6 lower-price-stars">
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