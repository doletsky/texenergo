<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>



<?
/**
 * Кол-во ячеек в строке
 */
$rowSize = $arParams['LINE_ELEMENT_COUNT'] > 0 ? $arParams['LINE_ELEMENT_COUNT'] : 5;


$pageNum = 1;
?>

<? if (!empty($arResult['ITEMS'])): ?>

	<ul class="heroSliderAction owl-carousel">

	<li class="slide">
	<div class="container">
	<div class="twelve mainpage-products">




    <div class="cat-block">  

    <div class="products-grid cat-products clearfix j_cat_product_list">
    <?
    $count = 1;		
    foreach ($arResult['ITEMS'] as $key => $arItem):
        $strMainID = $this->GetEditAreaId($arItem['ID']);?>

        <div class="cell" id="<? echo $strMainID; ?>">
            <div class="wrapper clearfix">
                <div class="cat-product j_cat_product_card">
                    <div class='product-image'>

                       


                        <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="picture"
                           style="background-image:url('<?= $arItem['PICTURE']; ?>');">

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
                                            <span class="text">До конца акции</span>

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

                            <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="product-name"
                               title="<?= $arItem['NAME']; ?>">
                                <?= TruncateText($arItem['NAME'], 50); ?>
                            </a>
                           
                        </div>

                      


                    </div>
                </div>


            </div>
        </div>
        <?
        if ($count != count($arResult['ITEMS'])) {
            if ($count % $rowSize == 0):?>
									</div>
								</div>
							</div>
						</div>
					</li>
				<li class="slide">
					<div class="container">
						<div class="twelve mainpage-products">
							<div class="cat-block">  
								<div class="products-grid cat-products clearfix j_cat_product_list">
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

