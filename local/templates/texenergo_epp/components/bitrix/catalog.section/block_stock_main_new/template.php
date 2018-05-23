<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>



<?
/**
 * Кол-во ячеек в строке
 */
$rowSize = $arParams['LINE_ELEMENT_COUNT'] > 0 ? $arParams['LINE_ELEMENT_COUNT'] : 4;


$pageNum = 1;
?>

<? if (!empty($arResult['ITEMS'])): ?>
<div class="container block-stock-main">
    <ul class="stock-list">
    <?
    $count = 1;		    
    foreach ($arResult['ITEMS'] as $key => $arItem):
	if ($count > $rowSize) break;
        $strMainID = $this->GetEditAreaId($arItem['ID']);?>
      <li class="slide grid-20">
                        <div class="stock-info <?= ($count%2==0)?'even':'od'?>">
                            <span class="date"><?=$arItem['DATE_ACTIVE_FROM_FORMATTED']?></span>
                            <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="stock-name"
                               title="<?= $arItem['NAME']; ?>">
                                <?= TruncateText($arItem['NAME'], 40); ?>
                            </a>
                           
                        </div>
    </li>	
    <? $count++;?>
    <? endforeach; ?>
   </ul>
      <div class="all-pubs">
         <a href="/publication/akcii/" class="show">Посмотреть все акции</a>
     </div>
</div><!--/.block-stock-main-->
<? endif; ?>

