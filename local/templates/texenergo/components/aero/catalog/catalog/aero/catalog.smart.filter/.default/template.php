<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<aside class="filter">
    <form name="<? echo $arResult["FILTER_NAME"] . "_form" ?>" action="<? echo $arResult["FORM_ACTION"] ?>" method="get"
          class="smartfilter">
        <? foreach ($arResult["HIDDEN"] as $arItem): ?>
            <input
                type="hidden"
                name="<? echo $arItem["CONTROL_NAME"] ?>"
                id="<? echo $arItem["CONTROL_ID"] ?>"
                value="<? echo $arItem["HTML_VALUE"] ?>"
                />
        <? endforeach; ?>
        <div class="filters">
            <? foreach ($arResult["ITEMS"] as $arItem): ?>
                <? // if (count($arItem["VALUES"]) == 1) continue; ?>
                <ul class="menu-section j_menu_section">
                    <? if ($arItem["PROPERTY_TYPE"] == "N" || isset($arItem["PRICE"])): ?>

                        <li class="sidebar-item sidebar-header collapsed">
                    <span>Цена <span class="more-icon"></span>
                    </span>
                        </li>

                        <li class="sidebar-item sidebar-child" style="display:none;">
                            <?
                            //$arItem["VALUES"]["MIN"]["VALUE"];
                            //$arItem["VALUES"]["MAX"]["VALUE"];
                            ?>
                            <input
                                class="min-price"
                                type="text"
                                name="<? echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
                                id="<? echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
                                value="<? echo $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?>"
                                size="5"
                                onkeyup="smartFilter.keyup(this)"
                                placeholder="от"
                                >
                            <input
                                class="max-price"
                                type="text"
                                name="<? echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
                                id="<? echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
                                value="<? echo $arItem["VALUES"]["MAX"]["HTML_VALUE"] ?>"
                                size="5"
                                onkeyup="smartFilter.keyup(this)"
                                placeholder="до"
                                >
                        </li>
                    <? elseif (!empty($arItem["VALUES"])):; ?>
                        <? $c = 0; ?>
                        <?
                        // убираем пустые фильтры
                        foreach ($arItem["VALUES"] as $val => $ar) {
                            if (empty($ar["VALUE"])) {
                                unset($arItem["VALUES"][$val]);
                                continue;
                            }

                            if ($ar["DISABLED"]) {
                                unset($arItem["VALUES"][$val]);
                                continue;
                            }
                        }

                        if (empty($arItem["VALUES"])) continue;

                        ?>


                        <li class="sidebar-item sidebar-header collapsed">
                        <span>
                            <?= $arItem["NAME"] ?>
                            <span class="more-icon"></span>
                        </span>
                        </li>
                        <? if ($arItem['DISPLAY_TYPE'] == 'K' || $arItem['DISPLAY_TYPE'] == 'F' || $arItem['PROPERTY_TYPE'] !== 'L'): ?>
                            <? if ($arItem['DISPLAY_TYPE'] == 'K'): ?>
                                <?
                                $bChecked = true;
                                foreach ($arItem["VALUES"] as $val => $ar) {
                                    if ($ar["CHECKED"]) {
                                        $bChecked = false;
                                        break;
                                    }
                                }?>
                                <li class="sidebar-item sidebar-child<? if ($c > 5): ?> collapse<? endif; ?>" style="display:none;">
                                    <input
                                        type="radio"
                                        class="sidebar-radio j-catalog-filter"
                                        value=""
                                        name="f_<?= $arItem['ID']; ?>"
                                        id="f_<?= $arItem['ID']; ?>_all"
                                        <? echo $bChecked ? 'checked="checked"' : '' ?>
                                        onclick="smartFilter.click(this)">
                                    <label for="f_<?= $arItem['ID']; ?>_all"
                                           class="label label-rounded">Все</label>
                                </li>
                            <? endif; ?>


                            <? foreach ($arItem["VALUES"] as $val => $ar): ?>
                                <? $c++; ?>
                                <li class="sidebar-item sidebar-child<? if ($c > 5): ?> collapse<? endif; ?>" style="display:none;">

                                    <? if ($arItem['DISPLAY_TYPE'] == 'F'): ?>

                                        <input
                                            type="checkbox"
                                            class="sidebar-radio j-catalog-filter"
                                            value="<? echo $ar["HTML_VALUE"] ?>"
                                            name="<? echo $ar["CONTROL_NAME"] ?>"
                                            id="<? echo $ar["CONTROL_ID"] ?>"
                                            <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                                            onclick="smartFilter.click(this)">
                                        <label for="<? echo $ar["CONTROL_ID"] ?>"
                                               class="label label-squared"><? echo $ar["VALUE"]; ?></label>

                                    <? else: ?>

                                        <input
                                            type="radio"
                                            class="sidebar-radio j-catalog-filter"
                                            value="<? echo $ar["HTML_VALUE_ALT"] ?>"
                                            name="<? echo $ar["CONTROL_NAME_ALT"] ?>"
                                            id="<? echo $ar["CONTROL_ID"] ?>"
                                            <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                                            onclick="smartFilter.click(this)">
                                        <label for="<? echo $ar["CONTROL_ID"] ?>"
                                               class="label label-rounded"><? echo $ar["VALUE"]; ?></label>


                                    <?endif; ?>

                                </li>
                            <? endforeach; ?>
                        <? else: ?>
                            <li class="sidebar-item sidebar-child" style="display:none;">
                                <select name="f_<?= $arItem['ID']; ?>"
                                        onchange="smartFilter.click(this)">
                                    <option value="">Все</option>
                                    <? foreach ($arItem["VALUES"] as $val => $ar): ?>
                                        <option
                                            value="<? echo $ar["HTML_VALUE_ALT"] ?>" <? echo $ar["CHECKED"] ? 'selected="selected"' : '' ?>><? echo $ar["VALUE"]; ?></option>
                                    <? endforeach; ?>
                                </select>
                            </li>
                        <?endif; ?>


                        <? if ($c > 5): ?>
                            <li class="sidebar-item sidebar-child show-all" style="display:none;">
                                <a href="#">Показать все <?= count($arItem["VALUES"]); ?></a>
                            </li>
                        <? endif ?>
                    <?endif; ?>

                </ul>
            <? endforeach; ?>
        </div>
        <? /*
		<input type="submit" id="set_filter" name="set_filter" value="<?=GetMessage("CT_BCSF_SET_FILTER")?>" />
*/
        ?>
        <? if ($_REQUEST['set_filter'] == 'y'): ?>
            <a href="<?=$APPLICATION->GetCurPage()?>" id="del_filter" class="clear-filter j_clear_dinamic_filter"></a>
        <? endif; ?>
        <div class="modef" id="modef" <? if (!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"'; ?>>
            <? echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">' . intval($arResult["ELEMENT_COUNT"]) . '</span>')); ?>
            <a href="<? echo $arResult["FILTER_URL"] ?>"
               class="showchild"><? echo GetMessage("CT_BCSF_FILTER_SHOW") ?></a>
            <!--<span class="ecke"></span>-->
        </div>
    </form>
    <script>
        var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>');
    </script>
</aside>