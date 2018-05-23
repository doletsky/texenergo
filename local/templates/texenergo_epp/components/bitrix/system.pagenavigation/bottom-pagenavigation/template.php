<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$startPageNum = $arResult["nStartPage"];
if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}
?>
<nav class="bottom-pager">
    <ul>
    <?

    $strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
    $strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
    
    if($arResult["bDescPageNumbering"] === true):
        $bFirst = true;
        
        if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
            if ($arResult["nStartPage"] < $arResult["NavPageCount"]):
                $bFirst = false;
                if($arResult["bSavePage"]):
                    ?>
                    <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>">1</a></li>
                    <?
                else:
                    ?>
                    <li><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a></li>
                    <?
                endif;
                
                if ($arResult["nStartPage"] < ($arResult["NavPageCount"] - 1)):
                    ?>	
                    <li>...</li>
                    <?
                endif;
            endif;
        endif;
        
        do
        {
            $NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;
            if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
                if($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false) {
                    ?>
                    <li><a class="pager-current" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$NavRecordGroupPrint?></a></li>
                    <?
                } else {
                    ?>
                    <li><a class="pager-current" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$NavRecordGroupPrint?></a></li>
                    <?
                }
            elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):
                ?>
                <li><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$NavRecordGroupPrint?></a></li>
                <?
            else:
                ?>
                <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$NavRecordGroupPrint?></a></li>
                <?
            endif;
            
            $arResult["nStartPage"]--;
            $bFirst = false;
        } while($arResult["nStartPage"] >= $arResult["nEndPage"]);
        
        if ($arResult["NavPageNomer"] > 1):
            if ($arResult["nEndPage"] > 1):
                if ($arResult["nEndPage"] > 2):
                    ?>
                    <li>...</li>
                    <?
                endif;
                ?>
                <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1"><?=$arResult["NavPageCount"]?></a></li>
                <?
            endif;
        endif;
        ?>
        </ul>
        <?php
        
        if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
            if($arResult["bSavePage"]):
                ?>
                <a class="bottom-pager-left" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"></a>
                <?
            else:
                if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1) ):
                    ?>
                    <a class="bottom-pager-left" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"></a>
                    <?
                else:
                    ?>
                    <a class="bottom-pager-left" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"></a>
                    <?
                endif;
            endif;
        else:
            ?>
            <div class="bottom-pager-left"></div>
            <?
        endif;
        if ($arResult["NavPageNomer"] > 1):
            ?>
            <a class="bottom-pager-right" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"></a>
            <?
        else:
            ?>
            <div class="bottom-pager-right"></div>
            <?
        endif;
    else:
        $bFirst = true;

        if ($arResult["NavPageNomer"] > 1):
            if ($arResult["nStartPage"] > 1):
                $bFirst = false;
                if($arResult["bSavePage"]):
                    ?>
                    <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1">1</a></li>
                    <?
                else:
                    ?>
                    <li><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a></li>
                    <?
                endif;
                
                if ($arResult["nStartPage"] > 2):
                    ?>
                    <li>...</li>
                    <?
                endif;
            endif;
        endif;

        do
        {
            if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
                if($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false) {
                    ?>
                    <li><a class="pager-current" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a></li>
                    <?
                } else {
                    ?>
                    <li><a class="pager-current" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a></li>
                    <?
                }
            elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):
                ?>
                <li><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a></li>
                <?
            else:
                ?>
                <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a></li>
                <?
            endif;
            $arResult["nStartPage"]++;
            $bFirst = false;
        } while($arResult["nStartPage"] <= $arResult["nEndPage"]);
        
        if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
            if ($arResult["nEndPage"] < $arResult["NavPageCount"]):
                if ($arResult["nEndPage"] < ($arResult["NavPageCount"] - 1)):
                    ?>
                    <li>...</li>
                    <?
                endif;
                ?>
                <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=$arResult["NavPageCount"]?></a></li>
                <?
            endif;
        endif;
        ?>
        </ul>
        <?php
        
        if ($arResult["NavPageNomer"] > 1):
            if($arResult["bSavePage"]):
                ?>
                <a class="bottom-pager-left" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"></a>
                <?
            else:
                if ($arResult["NavPageNomer"] > 2):
                    ?>
                    <a class="bottom-pager-left" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"></a>
                    <?
                else:
                    ?>
                    <a class="bottom-pager-left" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"></a>
                    <?
                endif;
            endif;
        else:
            ?>
            <div class="bottom-pager-left"></div>
            <?php
        endif;
        if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
            ?>
            <a class="bottom-pager-right" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"></a>
            <?
        else:
            ?>
            <div class="bottom-pager-right"></div>
            <?
        endif;
    endif;
    ?>
</nav>
<?
$arResult["nStartPage"] = $startPageNum;
if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}
?>
<div id="top-navigation" class="hidden">
    <ul class="pager-top">
        <?
        $strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
        $strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
        
        if($arResult["bDescPageNumbering"] === true):
            $bFirst = true;
            
            if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
                if ($arResult["nStartPage"] < $arResult["NavPageCount"]):
                    $bFirst = false;
                    if($arResult["bSavePage"]):
                        ?>
                        <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>">1</a></li>
                        <?
                    else:
                        ?>
                        <li><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a></li>
                        <?
                    endif;
                    
                    if ($arResult["nStartPage"] < ($arResult["NavPageCount"] - 1)):
                        ?>	
                        <li>...</li>
                        <?
                    endif;
                endif;
            endif;
            
            do
            {
                $NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;
                
                if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
                    if($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false) {
                        ?>
                        <li><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="selected"><?=$NavRecordGroupPrint?></a></li>
                        <?
                    } else {
                        ?>
                        <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>" class="selected"><?=$NavRecordGroupPrint?></a></li>
                        <?
                    }
                elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):
                    ?>
                    <li><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$NavRecordGroupPrint?></a></li>
                    <?
                else:
                    ?>
                    <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$NavRecordGroupPrint?></a></li>
                    <?
                endif;
                
                $arResult["nStartPage"]--;
                $bFirst = false;
            } while($arResult["nStartPage"] >= $arResult["nEndPage"]);
            
            if ($arResult["NavPageNomer"] > 1):
                if ($arResult["nEndPage"] > 1):
                    if ($arResult["nEndPage"] > 2):
                        ?>
                        <li>...</li>
                        <?
                    endif;
                    ?>
                    <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1"><?=$arResult["NavPageCount"]?></a></li>
                    <?
                endif;
            endif;
            
        else:
            $bFirst = true;

            if ($arResult["NavPageNomer"] > 1):
                if ($arResult["nStartPage"] > 1):
                    $bFirst = false;
                    if($arResult["bSavePage"]):
                        ?>
                        <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1">1</a></li>
                        <?
                    else:
                        ?>
                        <li><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a></li>
                        <?
                    endif;
                    
                    if ($arResult["nStartPage"] > 2):
                        ?>
                        <li>...</li>
                        <?
                    endif;
                endif;
            endif;

            do
            {
                if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
                    if($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false) {
                        ?>
                        <li><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="selected"><?=$arResult["nStartPage"]?></a></li>
                        <?
                    } else {
                        ?>
                        <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>" class="selected"><?=$arResult["nStartPage"]?></a></li>
                        <?
                    }
                elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):
                    ?>
                    <li><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a></li>
                    <?
                else:
                    ?>
                    <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a></li>
                    <?
                endif;
                $arResult["nStartPage"]++;
                $bFirst = false;
            } while($arResult["nStartPage"] <= $arResult["nEndPage"]);
            
            if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
                if ($arResult["nEndPage"] < $arResult["NavPageCount"]):
                    if ($arResult["nEndPage"] < ($arResult["NavPageCount"] - 1)):
                        ?>
                        <li>...</li>
                        <?
                    endif;
                    ?>
                    <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=$arResult["NavPageCount"]?></a></li>
                    <?
                endif;
            endif;
        endif;
        ?>
    </ul>
</div>
<script type="text/javascript">
    BX.ready(function(){
        $("#level-pager-top").html($("#top-navigation").html());
    }); 
</script>