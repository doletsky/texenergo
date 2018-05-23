<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?php

if(count($arResult["SEARCH"])>0) {
    foreach($arResult["SEARCH"] as $arItem) {
        if(preg_match("/\D/", trim($arItem['ITEM_ID']))) {
            echo "<article class='search-category clearfix'>
            <aside></aside>
            <section>
            <ul>
            <li class='search-result'>
	            <a href='/catalog/?SECTION_ID=".preg_replace("/\D/", '',trim($arItem['ITEM_ID']))."'>";
            echo $arItem['TITLE'];
            echo "</a></li></ul></section></article>";
        }
    }
}
?>