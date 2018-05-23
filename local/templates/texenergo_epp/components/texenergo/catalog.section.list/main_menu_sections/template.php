<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
$sectionCount = 0;
$count = 0;
?>

<?foreach ($arResult["SECTIONS"] as $arSection) {

    if ($arSection["DEPTH_LEVEL"] == 1){// && $arSection["UF_SHOW_POPUP"] == 1) {

        $count++;

    }

}

$newCount = ceil($count / 2);

?>


<nav class="category-nav">
    <ul class="<? if ($count <= 1) { ?>long-list<? } ?>">
        <?foreach ($arResult["SECTIONS"] as $arSection) {

            if ($arSection["DEPTH_LEVEL"] == 1){ //&& $arSection["UF_SHOW_POPUP"] == 1) {
                ?>

                <? if ($sectionCount == $newCount) {

                    echo "</ul><ul>";

                }?>

                <li><a href="<?= $arSection['SECTION_PAGE_URL']; ?>"><?= $arSection["NAME"] ?></a></li>

                <? $sectionCount++; ?>

            <?
            }

        }?>
    </ul>
</nav>
