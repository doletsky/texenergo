<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?$bFirst = true;?>

<? foreach ($arResult["ORDERS"] as $arOrderData): ?>
    <?
    $arOrder = $arOrderData['ORDER'];
    $arBasket = $arOrderData['BASKET_ITEMS'];
	$class = '';
	if($bFirst){
		$bFirst = false;
		$class = 'first';
	}
    ?>
    <div class="b-sidebarOrder <?=$class?>">
        
	<span class="sidebarNumber">Заказ № <?= $arOrder['ACCOUNT_NUMBER']; ?> <a href="/personal/orders/?q=<?= $arOrder['ACCOUNT_NUMBER']; ?>">[открыть]</a></span>
        <div class="progress">
            <div class="fill" style="width:<?= $arOrder['PROGRESS']; ?>%">
                <i></i>
            </div>
        </div>
        <em><?= $arOrder['PROGRESS']; ?>%</em>
    </div>
<? endforeach; ?>

<?if(isset($arResult['CURRENT_DISCOUNT']) && intval($arResult['CURRENT_DISCOUNT']) > 0):?>

 <div class="sidebarDetails-cur-discount">
	Персональная скидка <span><?=$arResult['CURRENT_DISCOUNT']?>%</span>
 </div>
 
<?endif;?>