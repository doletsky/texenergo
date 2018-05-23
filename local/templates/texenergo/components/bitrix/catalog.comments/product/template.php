<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arParams['IS_AJAX'])
	$arResult["IS_AJAX"] = true;

echo "<section class='pReviews clearfix'>";
    echo "<header class='clearfix'>";
        if($arResult['ELEMENT']['PROPERTIES']['BLOG_COMMENTS_CNT']['VALUE'] != "") {
            echo "<div class='pReviewsTot'>";
                echo "<span class='pAdditionalHeader'><a name='tReview'>Всего отзывов:</a> </span><em>".$arResult['ELEMENT']['PROPERTIES']['BLOG_COMMENTS_CNT']['VALUE']."</em>";
                echo "<div class='pReviewsAvg'><span class='text'>Средний рейтинг:</span>";
                    echo "<div class='cat-rating cat-rating-big cat-value-raiting";
                    if($arParams['GOODS_RATE']['VALUE'] != "") {
                        echo "-".$arParams['GOODS_RATE']['VALUE'];
                    }
                    echo "'></div>";
                echo "</div>";
            echo "</div>";
        }
        echo "<button class='rounded rounded-reviews j_rounded_reviews'>".GetMessage("WRITE_COMMENT")."</button>";
        ?>
        <div class="blog-add-comment hidden"><a class="j_show_reviews_form" href="javascript:void(0)" onclick="return showComment('0')"><?=GetMessage("WRITE_COMMENT")?></a></div>
        <?php
    echo "</header>";

    if(!empty($arResult["ERRORS"]))
    {
        ShowError(implode("<br>", $arResult["ERRORS"]));
        return;
    }

    $arData = array();

    /* BLOG COMMENTS */
    if($arParams["BLOG_USE"] == "Y")
    {
       
		$arCommentParams = array(
                "SEO_USER" => "N",
                "ID" => $arResult["COMMENT_ID"],
                "BLOG_URL" => $arResult['BLOG_DATA']['BLOG_URL'],
                "PATH_TO_SMILE" => $arParams["PATH_TO_SMILE"],
                "COMMENTS_COUNT" => $arParams["COMMENTS_COUNT"],
                "DATE_TIME_FORMAT" => "d F Y",
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "AJAX_POST" => $arParams["AJAX_POST"],
                "SIMPLE_COMMENT" => "Y",
                "SHOW_SPAM" => $arParams["SHOW_SPAM"],
                "NOT_USE_COMMENT_TITLE" => "Y",
                "SHOW_RATING" => $arParams["SHOW_RATING"],
                "RATING_TYPE" => $arParams["RATING_TYPE"],
                "PATH_TO_POST" => $arResult["URL_TO_COMMENT"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "NO_URL_IN_COMMENTS" =>"L",
                "PARENT_PARAMS" => $arParams
            );

        if($arResult["IS_AJAX"])
            $APPLICATION->RestartBuffer();

        ob_start();
        $APPLICATION->IncludeComponent(
            "texenergo:blog.post.comment",
            "product",
            $arCommentParams,
            $component,
            array("HIDE_ICONS" => "Y")
        );

        $blogComments = ob_get_contents();
        ob_end_clean();

        if(isset($arParams["BLOG_AJAX"]) && $arParams["BLOG_AJAX"] == "Y")
        {
            $APPLICATION->RestartBuffer();
            echo $blogComments;
            die();
        }

        $arData["BLOG"] =  array(
                "NAME" => isset($arParams["BLOG_TITLE"]) && trim($arParams["BLOG_TITLE"]) != "" ? $arParams["BLOG_TITLE"] : GetMessage("IBLOCK_CSC_TAB_COMMENTS"),
                "ACTIVE" => "Y",
                "CONTENT" => $blogComments
        );

    ?>
    <script type="text/javascript">

            function hacksForCommentsWindow(addTitleShow)
            {
                if(!addTitleShow)
                    showComment("0");

                var addCommentButtons = BX.findChildren(document,
                    { class: "blog-add-comment" }
                    , true
                );

                if(addCommentButtons[0])
                    for (var i = addCommentButtons.length-1; i >= 0 ; i--)
                        addCommentButtons[i].style.display = addTitleShow ? "" : "none";
            };

            BX.ready( function(){
                hacksForCommentsWindow();

                <?if($arParams["AJAX_POST"] == "Y"):?>
                    BX.addCustomEvent("onAjaxSuccess", function (params){
                        hacksForCommentsWindow(true);
                    });
                <?endif;?>
            });

    </script>

    <?
    }

    /* FACEBOOK */
    if($arParams["FB_USE"] == "Y")
    {

        $arData["FB"] = array(
            "NAME" => isset($arParams["FB_TITLE"]) && trim($arParams["FB_TITLE"]) != "" ? $arParams["FB_TITLE"] : "Facebook",
            "CONTENT" => '
                <div id="fb-root"></div>
                <script type="text/javascript">
                    (function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/'.(strtolower(LANGUAGE_ID)."_".strtoupper(LANGUAGE_ID)).'/all.js#xfbml=1";
                    fjs.parentNode.insertBefore(js, fjs);
                    }(document, "script", "facebook-jssdk"));

                    BX.ready( function(){

                        setTimeout(function(){ JCCatalogSocnetsComments.onFBResize(); }, 2500 );

                        BX.addCustomEvent("onAfterBXCatTabsSetActive_soc_comments", function(params) {
                            if(params.activeTab || params.activeTab == "FB")
                            {
                                FB.XFBML.parse(BX("bx-cat-soc-comments-fb"));
                                JCCatalogSocnetsComments.onFBResize();
                            }
                        });

                        window.onresize = function(){
                            JCCatalogSocnetsComments.onFBResize();
                        };
                    });

                </script>

                <div id="bx-cat-soc-comments-fb"><div'.
                ' class="fb-comments"'.
                ' data-href="'.$arResult["URL_TO_COMMENT"].'"'.
                (isset($arParams["FB_COLORSCHEME"]) ? ' data-colorscheme="'.$arParams["FB_COLORSCHEME"].'"' : '').
                (isset($arParams["COMMENTS_COUNT"]) ? ' data-numposts="'.$arParams["COMMENTS_COUNT"].'"' : '').
                (isset($arParams["FB_ORDER_BY"]) ? ' data-order-by="'.$arParams["FB_ORDER_BY"].'"' : '').
                (isset($arResult["WIDTH"]) ? ' data-width="'.($arResult["WIDTH"] - 20).'"' : '').
                '></div></div>'.PHP_EOL
        );
    }


    /* VKONTAKTE*/
    if($arParams["VK_USE"] == "Y")
    {
        $arData["VK"] = array(
            "NAME" => isset($arParams["VK_TITLE"]) && trim($arParams["VK_TITLE"]) != "" ? $arParams["VK_TITLE"] : GetMessage("IBLOCK_CSC_TAB_VK"),
            "CONTENT" => '
                <div id="vk_comments"></div>

                <script type="text/javascript">
                    BX.ready( function(){
                            VK.init({
                                apiId: "'.(isset($arParams["VK_API_ID"]) && strlen($arParams["VK_API_ID"]) > 0 ? $arParams["VK_API_ID"] : "API_ID").'",
                                onlyWidgets: true
                            });

                            VK.Widgets.Comments(
                                "vk_comments",
                                {
                                    pageUrl: "'.$arResult["URL_TO_COMMENT"].'",'.
                                    (isset($arParams["COMMENTS_COUNT"]) ? "limit: ".$arParams["COMMENTS_COUNT"]."," : "").
                                    (isset($arResult["WIDTH"]) ? "width: ".($arResult["WIDTH"] - 20)."," : "").
                                    'attach: false
                                }
                            );
                    });

                </script>'
        );
    }

    if(!empty($arData))
    {
        $arTabsParams = array(
            "DATA" => $arData,
            "ID" => "soc_comments"
        );

        if(isset($arResult["WIDTH"]))
            $arTabsParams["WIDTH"] = $arResult["WIDTH"];

        ?><div id="soc_comments_div"><?

        $APPLICATION->IncludeComponent(
            "bitrix:catalog.tabs",
            "product",
            $arTabsParams,
            $component,
            array("HIDE_ICONS" => "Y")
        );
        ?></div><?
    }
    else
        ShowError(GetMessage("IBLOCK_CSC_NO_DATA"));
echo "</section>";
?>