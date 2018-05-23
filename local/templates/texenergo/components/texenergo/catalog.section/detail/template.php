<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
    $bisTrustedUser = isTrustedUser(); // пользователю разрешено показ остатков
echo '<input type="hidden" class="j-count-elements-insection" value="' . $arResult['countElements'] . '">';
if (!empty($arResult['ITEMS'])) {
    echo "<section class='cat-block'>";
    ?>
    <? if (strlen($arParams['FEATURED_TITLE']) > 0): ?>
        <header class="cat-header">
            <h2><?= $arParams['FEATURED_TITLE']; ?></h2>
            <?if($arParams['CAROUSEL'] == 'Y'){?>
                <a href="javascript:" class="cat-arrow cat-arrow-left"></a>
                <a href="javascript:" class="cat-arrow cat-arrow-right"></a>
            <?}?>
        </header>
    <? endif; ?>
    <?if($arParams['CAROUSEL'] == 'Y'){
        $itemsParts = array_chunk($arResult['ITEMS'], 4);?>
        <div class="specials-carousel">
            <?foreach($itemsParts as $part) {?>
                <div class="specials-carousel-item">
                    <?require __DIR__."/partial.php";?>
                </div>
            <?}?>
        </div>
    <?}
    else {
        $part = $arResult['ITEMS'];
        require __DIR__."/partial.php";
    }?>
    <? if ($arParams['DISPLAY_BOTTOM_PAGER'] == 'Y'): ?>
        <? echo $arResult["NAV_STRING"]; ?>
    <? endif; ?>
    <?echo "</section>"; //End cat-block
}
?>