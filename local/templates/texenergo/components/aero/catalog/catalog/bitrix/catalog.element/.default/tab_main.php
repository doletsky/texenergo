<? if (IsViewCertificate()) { $bIsViewCertificate = true; } else {$bIsViewCertificate = false; } ?>
<? 
$guid_type_certificate 	= 'a5b0dd53-5feb-490a-9bcc-cefa1284a991'; 
$guid_type_chemical_analysis_protocol	= '38461303-e3c5-11e7-9483-0025906565cb'; 
$guid_type_approval_certificate	= 'cfae68f1-cded-11e7-9482-0025906565cb'; 
$guid_type_catalog 	= 'cb88c09a-36bb-11e5-9458-0025906565cb'; 
?>
<div class="pPromoRow">
    <?$APPLICATION->IncludeFile('/include/product_action_inc.php', array(),
        array(
            'MODE' => 'html',
            'TEMPLATE' => 'product_action_inc.php',
        )
    );?>
</div>

<div class="product-photos-view">
    <div
        class="pView clearfix product-img-block product-img-slider">
        <? if ($arResult['PROPERTIES']['IS_NEW']['VALUE'] == 'Y'): ?>
            <img src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/new.png" alt="Является новинкой" class="new-in-img">
        <? endif; ?>

        <? if ($arResult['PROPERTIES']['IS_BESTSELLER']['VALUE'] == 'Y'): ?>
            <img src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/bestseller.png" alt="Лидер продаж" class="new-in-img">
        <? endif; ?>

        <figure class="pProductImage j-detail-product-img">
            <?if(!empty($arResult['PROPERTIES']['PHOTOS_PRODUCT']['VALUE'])) {?>
                <?
                $arPhotosProducts = array();
                foreach($arResult['PROPERTIES']['PHOTOS_PRODUCT']['VALUE'] as $sPhotosProductXmlId){
                    if(!is_dir($_SERVER['DOCUMENT_ROOT'] . '/upload/resize_cache/import')){
                        mkdir($_SERVER['DOCUMENT_ROOT'] . '/upload/resize_cache/import', 0775, true);
                    }
                    $arPhotosProduct = CIBlockElement::GetList(array(), array('XML_ID' => $sPhotosProductXmlId), false, false, array('ID', 'NAME', 'PROPERTY_PATH'))->GetNext();
                    $sSourceName = $arResult['impotr_folder'] . $arPhotosProduct['PROPERTY_PATH_VALUE'];
                    $sContent = file_get_contents($sSourceName);
                    $sSourceName = $_SERVER['DOCUMENT_ROOT'] . '/upload/resize_cache/import/'.$sPhotosProductXmlId.'.jpg';
                    file_put_contents($sSourceName, $sContent);
                    $sDestinationName = $_SERVER["DOCUMENT_ROOT"]."/upload/resize_cache/import/".$sPhotosProductXmlId;

                    if(!file_exists($sDestinationName.'_small.jpg')){
                        CFile::ResizeImageFile($sSourceName, $sDestinationNameSmall = $sDestinationName . '_small.jpg', array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, array(), false, array());
                    }
                    if(!file_exists($sDestinationName.'_preview.jpg')){
                        CFile::ResizeImageFile($sSourceName, $sDestinationNamePreview = $sDestinationName . '_preview.jpg', array('width' => 320, 'height' => 320), BX_RESIZE_IMAGE_PROPORTIONAL, array(), false, array());
                    }
                    unlink($sSourceName);
                    $sSourceName = $arResult['impotr_folder'] . $arPhotosProduct['PROPERTY_PATH_VALUE'];
                    $arPhotosProducts[] = array('FULL' => $sSourceName, 'PREVIEW' => '/upload/resize_cache/import/'.$sPhotosProductXmlId.'_preview.jpg', 'SMALL' => '/upload/resize_cache/import/'.$sPhotosProductXmlId.'_small.jpg');
                }
                ?>
                <a href="<?= $arPhotosProducts[0]['FULL']; ?>"
                   target="_blank" class="zoom"
                   style="background-image:url('<?= $arResult['DETAIL_PICTURE']['PREVIEW']; ?>');">
                    <img src="<?= $arPhotosProducts[0]['PREVIEW']; ?>" alt="<?= $arResult['NAME']; ?>" title="<?= $arResult['NAME']; ?>" 
                         class="j-image-to-animate" id="product_picture_<?= $arResult['ID']; ?>" itemprop="image">
                </a>
            <?}else{
            //if ($arResult['DETAIL_PICTURE']['SRC']): ?>
<?if (false):?>
                <a href="<?= $arResult['DETAIL_PICTURE']['SRC']; ?>"<? if (empty($arResult['PHOTOS'])): ?> data-fancybox-group="fancybox-thumb"<? endif; ?>
                   target="_blank" class="zoom"
                   style="background-image:url('<?= $arResult['DETAIL_PICTURE']['PREVIEW']; ?>');">
                    <img src="<?= $arResult['DETAIL_PICTURE']['PREVIEW']; ?>" alt="<?= $arResult['NAME']; ?>" title="<?= $arResult['NAME']; ?>" 
                         class="j-image-to-animate" id="product_picture_<?= $arResult['ID']; ?>" itemprop="image">
                </a>
<?endif;?>
            <? //else: ?>
                <img src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/no_image.jpg" alt="<?= $arResult['NAME']; ?>" title="<?= $arResult['NAME']; ?>" 
                     class="j-image-to-animate" id="product_picture_<?= $arResult['ID']; ?>" itemprop="image">
            <? //endif; ?>
            <?}?>
			<?
			if (!empty($arResult["PROPERTIES"]["PRICE_DOWN_REASON"]["VALUE"])) {
				echo "<div class='markdownMessage'><span>" . $arResult["PROPERTIES"]["PRICE_DOWN_REASON"]["~VALUE"] . "</span></div>";
			}
			?>

            <? if ($arResult['OLD_PRICE'] > 0): ?>
                <div class="cat-discount catDiscount-cart"></div>
            <? endif; ?>
        </figure>

        <?if(!empty($arResult['PROPERTIES']['PHOTOS_PRODUCT']['VALUE'])){?>
            <div class="product-photos j_owl_slider_4 owl-theme owl-slider">
                <? foreach ($arPhotosProducts as $arPhoto): ?>
                    <a href="<?= $arPhoto['FULL']; ?>" class="photo" data-fancybox-group="fancybox-thumb"
                       data-preview="<?= $arPhoto['PREVIEW']; ?>" target="_blank"
                       style="background-image:url('<?= $arPhoto['SMALL']; ?>');">
                        <img src="<?= $arPhoto['SMALL']; ?>" alt="<?= $arResult['NAME']; ?>" title="<?= $arResult['NAME']; ?>">
                    </a>
                <? endforeach; ?>
            </div>
        <?}else{?>
            <? if (!empty($arResult['PHOTOS'])): ?>
                <div class="product-photos j_owl_slider_4 owl-theme owl-slider">
                    <? if ($arResult['DETAIL_PICTURE']['SRC']): ?>
                        <a href="<?= $arResult['DETAIL_PICTURE']['SRC']; ?>" data-fancybox-group="fancybox-thumb"
                           data-preview="<?= $arResult['DETAIL_PICTURE']['PREVIEW']; ?>" class="photo" target="_blank"
                           style="background-image:url('<?= $arResult['DETAIL_PICTURE']['SMALL']; ?>');">
                            <img src="<?= $arResult['DETAIL_PICTURE']['SMALL']; ?>" alt="<?= $arResult['NAME']; ?>" title="<?= $arResult['NAME']; ?>">
                        </a>
                    <? endif; ?>
                    <? foreach ($arResult["PHOTOS"] as $arPhoto): ?>
                        <a href="<?= $arPhoto['FULL']; ?>" class="photo" data-fancybox-group="fancybox-thumb"
                           data-preview="<?= $arPhoto['PREVIEW']; ?>" target="_blank"
                           style="background-image:url('<?= $arPhoto['SMALL']; ?>');">
                            <img src="<?= $arPhoto['SMALL']; ?>" alt="<?= $arResult['NAME']; ?>" title="<?= $arResult['NAME']; ?>">
                        </a>
                    <? endforeach; ?>
                </div>
            <? endif; ?>
        <?}?>

    </div>

    <?
    global $arAnalogFilter;
    //if (!empty($arResult["PROPERTIES"]["ANALOGS"]["VALUE"])) {
    if (!empty($arResult["PROPERTIES"]["RELATED"]["VALUE"])) {
        $arAnalogFilter = Array(
            "ACTIVE" => "Y",
            //"ID" => $arResult["PROPERTIES"]["ANALOGS"]["VALUE"],
            "ID" => $arResult["PROPERTIES"]["RELATED"]["VALUE"],
        );
    } else {
        $arAnalogFilter = Array(
            "ACTIVE" => "Y",
            "ID" => "-1",
        );
    }
    $GLOBALS['arRelated'] = $arResult["PROPERTIES"]["RELATED"]["VALUE"];
    ?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "product-analogs",
        Array(
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "N",
            "AJAX_MODE" => "N",
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => IBLOCK_ID_CATALOG,
            "NEWS_COUNT" => "1000",
            "SORT_BY1" => "ACTIVE_FROM",
            "SORT_ORDER1" => "DESC",
            "SORT_BY2" => "SORT",
            "SORT_ORDER2" => "ASC",
            "FILTER_NAME" => "arAnalogFilter",
            "FIELD_CODE" => array('DETAIL_PICTURE'),
            "PROPERTY_CODE" => array("SKU"),
            "CHECK_DATES" => "Y",
            "DETAIL_URL" => "",
            "PREVIEW_TRUNCATE_LEN" => "",
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "SET_TITLE" => "N",
            "SET_STATUS_404" => "N",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
            "PARENT_SECTION" => "",
            "PARENT_SECTION_CODE" => "",
            "INCLUDE_SUBSECTIONS" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_FILTER" => "N",
            "CACHE_GROUPS" => "Y",
            "PAGER_TEMPLATE" => ".default",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "PAGER_TITLE" => "",
            "PAGER_SHOW_ALWAYS" => "Y",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "Y",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N"
        ),
        false
    );?>
    <div class="clear"></div>
</div>


<div class="pOverview clearfix">
    <section class="pCopy">
        <h2>Описание</h2>
	<div class="product_description" itemprop="description">
        <?if(!empty($arResult['PROPERTIES']['DESCRIPTION_PRODUCT']['VALUE'])){
            $arDesc = CIBlockElement::GetList(array(), array('XML_ID' => $arResult['PROPERTIES']['DESCRIPTION_PRODUCT']['VALUE']), false, false, array('ID', 'NAME', 'PROPERTY_PATH'))->GetNext();
            $sDesc = file_get_contents($arResult['impotr_folder'] . $arDesc['PROPERTY_PATH_VALUE']);
            if(!empty($sDesc)) {
                echo $sDesc;
            }elseif($arResult['DETAIL_TEXT']){
                echo $arResult['DETAIL_TEXT'];
            }else{
                echo '<p>Описание готовится к публикации</p>';
            }
        }else{?>
            <? if ($arResult['DETAIL_TEXT']): ?>

                <?= $arResult['DETAIL_TEXT']; ?>
            <? else: ?>
                <p>Описание готовится к публикации</p>
            <? endif; ?>
        <?}?>
        </div>
	<p class="disclaimer_description">Внимание: характеристики товара, комплект поставки и внешний вид могут быть изменены производителем без предварительного уведомления.
            Указанная информация не является публичной офертой.</p>
    </section>

    <? //if (!empty($arResult["PROPERTIES"]["PUBLICATIONS"]["VALUE"]) || !empty($arResult['FILES']) || !empty($arResult['PROPERTIES']['FILES_PRODUCT']['VALUE'])): ?>
    <? if (!empty($arResult["PROPERTIES"]["PUBLICATIONS"]["VALUE"]) || !empty($arResult['PROPERTIES']['FILES_PRODUCT']['VALUE'])): ?>
        <aside class="pPublications j_switch_img_block">
            <div class="switch-sbgrp">
                <? if ($arParams['PUBLICATIONS_HTML']): ?>
                    <? if(false):?><span class="switch-label">Публикации</span><? endif;?>
                <? endif; ?>

                <? //if ($arParams['PUBLICATIONS_HTML'] && (!empty($arResult['FILES']) || !empty($arResult['PROPERTIES']['FILES_PRODUCT']['VALUE']))): ?>
                <? if ($arParams['PUBLICATIONS_HTML'] || (!empty($arResult['PROPERTIES']['FILES_PRODUCT']['VALUE']))): ?>
                    <? if(false):?><div class="switch v-switch no-float j_switch_img_link"></div><? endif; ?>
                <? endif; ?>

                <?if(!empty($arResult['PROPERTIES']['FILES_PRODUCT']['VALUE'])){
                    $arFilesProduct = array();
                    foreach($arResult['PROPERTIES']['FILES_PRODUCT']['VALUE'] as $sFileXmlId) {
                        $arSelect = Array("ID", "NAME", "PROPERTY_PATH", "PROPERTY_FILESGROUP");
                        $arFilter = Array("XML_ID" => $sFileXmlId, "ACTIVE" => "Y");
                        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                        while ($arFile = $res->GetNext()) {
                            $sFilesGroupXmlId = $arFile['PROPERTY_FILESGROUP_VALUE'];
                            $arFilesGroup = CIBlockElement::GetList(array(), array('XML_ID' => $sFilesGroupXmlId), false, false, array('ID', 'NAME', "EXTERNAL_ID"))->GetNext();
                            $sIcon = false;
                            $parts = explode('.', $arFile['PROPERTY_PATH_VALUE']);
                            $ext = strtolower($parts[count($parts) - 1]);
                            $img = SITE_TEMPLATE_PATH.'/img/price_list/'.$ext.'.png';
                            if(file_exists($_SERVER['DOCUMENT_ROOT'].$img)){
                                $sIcon = $img;
                            }
                            $arFilesProduct[$arFilesGroup['ID']]['FILES'][] = array('NAME' => $arFile['NAME'], 'PATH' => $arFile['PROPERTY_PATH_VALUE'], 'ICON' => $sIcon);
                            $arFilesProduct[$arFilesGroup['ID']]['NAME'] = $arFilesGroup['NAME'];
                            $arFilesProduct[$arFilesGroup['ID']]['EXTERNAL_ID'] = $arFilesGroup['EXTERNAL_ID'];
                        }
                    }?>
                    <? //if ($arParams['PUBLICATIONS_HTML'] && (!empty($arResult['FILES']) || !empty($arResult['PROPERTIES']['FILES_PRODUCT']['VALUE']))): ?>
                    <? if ($arParams['PUBLICATIONS_HTML'] || (!empty($arResult['PROPERTIES']['FILES_PRODUCT']['VALUE']))): ?>
                        <?if(false):?><span class="switch-label float-left">Файлы</span><?endif;?>
                        <div style="margin-top: 20px">
                    <? endif; ?>
                    <?foreach($arFilesProduct as $arFilesGroup){?>
		<? //#3857 Не показываем сертификаты 
		if ($arFilesGroup['EXTERNAL_ID'] == $guid_type_certificate) $cert_yes = true; else $cert_yes = false; 
    //#4134 Иконкой показываем протокол испытаний
		if ($arFilesGroup['EXTERNAL_ID'] == $guid_type_chemical_analysis_protocol) $chemical_analysis_protocol_yes = true; else $chemical_analysis_protocol_yes = false; 
    //#4042 Иконкой показываем свидетельство
		if ($arFilesGroup['EXTERNAL_ID'] == $guid_type_approval_certificate) $approval_certificate_yes = true; else $approval_certificate_yes = false; 
?>
		<? if ($cert_yes): ?>
                        <span class="switch-label float-left j_switch_img_item" data-switch="2"><?=$arFilesGroup['NAME']?></span>
		<? endif;?>
                        <ul class="j_switch_img_item product_docs" data-switch="2">
                            <? foreach ($arFilesGroup['FILES'] as $arFile): ?>
                                <li>
 		<? if($chemical_analysis_protocol_yes):?>
                        <span class="switch-label float-left j_switch_img_item header-protocol" data-switch="2"><?=$arFile['NAME'];?></span>
		<? endif;?>

		<? if ($cert_yes): ?>
			<? if ($bIsViewCertificate) {
				$linkCertificate = $arResult['impotr_folder'] . $arFile['PATH'];
				$classCertificate = "";
			} else {
				$linkCertificate = "#";
				$classCertificate = " j_file_certificate";				
			}?>
                                    <a href="<?=$linkCertificate?>" target="_blank" class="file_certificate<?= $classCertificate?>">
					<img src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/sertif_icon.jpg" alt="Сертификат" class="certificate-img" width="108" height="154">			
                                    </a>
 		<? elseif($chemical_analysis_protocol_yes):?>
      <? $linkProtocol = $arResult['impotr_folder'] . $arFile['PATH'];?>
                                    <a href="<?=$linkProtocol?>" target="_blank" class="file_certificate">
					<img src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/protokol_ico.jpg" alt="Протокол химического анализа" class="certificate-img" width="108" height="154">			
                                    </a>      
 		<? elseif($approval_certificate_yes):?>
      <? $linkApprovalCertificate = $arResult['impotr_folder'] . $arFile['PATH'];?>
                                    <a href="<?=$linkApprovalCertificate?>" target="_blank" class="file_certificate">
					<img src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/approval_certificate_icon.jpg" alt="Свидетельство" class="certificate-img" width="108" height="154">			
                                    </a>      
		<? else:?>
                                    <a href="<?=$arResult['impotr_folder'] . $arFile['PATH']; ?>" target="_blank">

                                        <?if($arFile['ICON']):?>
                                            <img width="30" src="<?= $arFile['ICON'];?>" alt="<?= $arFile['NAME'];?>" title="<?= $arFile['NAME'];?>"/>
                                        <?endif;?>
                                        <? $file_name = ($arFilesGroup['EXTERNAL_ID'] == $guid_type_catalog) ? $arFile['NAME'] : $arFilesGroup['NAME'];?>
					<? $file_path = $_SERVER["DOCUMENT_ROOT"] . $arResult['path_folder'] . $arFile['PATH'];?>
                                        <span><?= $file_name;?><span class="file-size"> (<?= getFileSize($file_path);?>)</span></span>
                                    </a>
		<? endif;?>
		<? if (isset($cert_yes) && ($cert_yes == true)): ?>
			<? if (!$USER->isAuthorized()): ?>
			<a href="/personal/" class="no-sertificate" style="display:none;">
				<span class="text-cert">для получения сертификата</span>
				<span class="orange text-register">пройдите регистрацию</span>
			</a>
			<? else : ?>
			<a href="/personal/" class="orange no-sertificate" style="display:none;">
				<span class="text-get">Получить сертификат можно в личном кабинете в документе реализация товаров.</p>
			</a>
			<? endif; ?>	
		<? endif; ?>
                                </li>
                            <? endforeach; ?>
                        </ul>

                    <?}
                }else{?>
		  <? if (false): ?>
                    <? if (!empty($arResult['FILES'])): ?>
                        <span class="switch-label float-left">Файлы</span>
                        <? if ($arParams['PUBLICATIONS_HTML'] && (!empty($arResult['FILES']) || !empty($arResult['PROPERTIES']['FILES_PRODUCT']['VALUE']))): ?>
                            <div style="margin-top: 20px">
                        <? endif; ?>
                    <? endif; ?>
		  <? endif; ?>
                <?}?>
                <? if ($arParams['PUBLICATIONS_HTML']): ?>
		    <span class="switch-label">Публикации</span>	
                    <?=$arParams['~PUBLICATIONS_HTML'];?>
                <? endif; ?>

		<? if (false): ?>
                <?if(empty($arResult['PROPERTIES']['FILES_PRODUCT']['VALUE'])){?>
                    <? if (!empty($arResult['FILES'])): ?>
                        <ul class="hidden j_switch_img_item product_docs hidden" data-switch="2">
                            <? foreach ($arResult['FILES'] as $arFile): ?>
                                <li>
                                    <a href="<?= $arFile['SRC']; ?>" target="_blank">

                                        <?if($arFile['ICON']):?>
                                            <img width="30" src="<?= $arFile['ICON'];?>" alt="<?= $arFile['ORIGINAL_NAME'];?>" title="<?= $arFile['ORIGINAL_NAME'];?>"/>
                                        <?endif;?>

                                        <span><?= $arFile['ORIGINAL_NAME'];?></span>
                                    </a>
                                </li>
                            <? endforeach; ?>
                        </ul>
                    <? endif; ?>
                <?}?>
		<? endif; ?>
                <? //if ($arParams['PUBLICATIONS_HTML'] && (!empty($arResult['FILES']) || !empty($arResult['PROPERTIES']['FILES_PRODUCT']['VALUE']))): ?>
                <? if ($arParams['PUBLICATIONS_HTML'] || (!empty($arResult['PROPERTIES']['FILES_PRODUCT']['VALUE']))): ?>
                    </div>
                <? endif; ?>
            </div>
        </aside>
    <? endif; ?>
</div>

<? //if (!empty($arResult["PROPERTIES"]["RELATED"]["VALUE"])): ?>
<? if (!empty($arResult["PROPERTIES"]["ANALOGS"]["VALUE"])): ?>
    <?
    $GLOBALS['arRelatedFilter'] = Array(
        //'=ID' => $arResult["PROPERTIES"]["RELATED"]["VALUE"],
            "ACTIVE" => "Y",
        'ID' => $arResult["PROPERTIES"]["ANALOGS"]["VALUE"],
    );
    ?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "product-related",
        Array(
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "N",
            "AJAX_MODE" => "N",
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => $arParams['IBLOCK_ID'],
            "NEWS_COUNT" => "1000",
            "SORT_BY1" => "ACTIVE_FROM",
            "SORT_ORDER1" => "DESC",
            "SORT_BY2" => "SORT",
            "SORT_ORDER2" => "ASC",
            "FILTER_NAME" => "arRelatedFilter",
            "FIELD_CODE" => array('DETAIL_PICTURE'),
//            "PROPERTY_CODE" => array(),
            "PROPERTY_CODE" => array("SKU"),
            "CHECK_DATES" => "Y",
            "DETAIL_URL" => "",
            "PREVIEW_TRUNCATE_LEN" => "",
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "SET_TITLE" => "N",
            "SET_STATUS_404" => "N",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
            "PARENT_SECTION" => "",
            "PARENT_SECTION_CODE" => "",
            "INCLUDE_SUBSECTIONS" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_FILTER" => "N",
            "CACHE_GROUPS" => "Y",
            "PAGER_TEMPLATE" => ".default",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "PAGER_TITLE" => "",
            "PAGER_SHOW_ALWAYS" => "Y",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "Y",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N"
        ),
        false
    );?>
<? endif; ?>

<? if ('Y' == $arParams['USE_COMMENTS']): ?>
    <div class="j_comments_block">
        <?$APPLICATION->IncludeComponent(
            "aero:catalog.comments"
            ,"",
            Array(
                "IBLOCK_ID" => IBLOCK_ID_COMMENTS,
                "ELEMENT_ID" => $arResult['ID'],
                "ELEMENT_IBLOCK_ID" => $arParams['IBLOCK_ID'],
                "PAGE_URL" => $arResult['DETAIL_PAGE_URL'],
            )
        );?>

        <?/*$APPLICATION->IncludeComponent("bitrix:forum.topic.reviews","",Array(
                "SHOW_LINK_TO_FORUM" => "N",
                "FILES_COUNT" => "",
                "FORUM_ID" => "2",
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                "ELEMENT_ID" => $arResult['ID'],
                "AJAX_POST" => "N",
                "POST_FIRST_MESSAGE" => "Y",
                "POST_FIRST_MESSAGE_TEMPLATE" => "#IMAGE#[url=#LINK#]#TITLE#[/url]#BODY#",
                "URL_TEMPLATES_READ" => "read.php?FID=#FID#&TID=#TID#",
                "URL_TEMPLATES_DETAIL" => "photo_detail.php?ID=#ELEMENT_ID#",
                "URL_TEMPLATES_PROFILE_VIEW" => "profile_view.php?UID=#UID#",
                "MESSAGES_PER_PAGE" => "20",
                "PAGE_NAVIGATION_TEMPLATE" => "",
                "DATE_TIME_FORMAT" => "d.m.Y H:i:s",
                "PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
                "EDITOR_CODE_DEFAULT" => "Y",
                "SHOW_AVATAR" => "Y",
                "SHOW_RATING" => "Y",
                "RATING_TYPE" => "like",
                "SHOW_MINIMIZED" => "Y",
                "USE_CAPTCHA" => "Y",
                "PREORDER" => "N",
                "CACHE_TYPE" => "N",
                "CACHE_TIME" => "0"
            )
        );*/?>

		<?
//		$APPLICATION->IncludeComponent(
//            "bitrix:catalog.comments",
//            "product",
//            array(
//				"IS_AJAX" => false,
//				"ELEMENT_ID" => $arResult['ID'],
//                "ELEMENT_CODE" => "",
//                "IBLOCK_ID" => $arParams['IBLOCK_ID'],
//                "URL_TO_COMMENT" => "",
//                "WIDTH" => "",
//                "COMMENTS_COUNT" => "1000",
//                "BLOG_USE" => $arParams['BLOG_USE'],
//                "FB_USE" => $arParams['FB_USE'],
//                "FB_APP_ID" => $arParams['FB_APP_ID'],
//                "VK_USE" => $arParams['VK_USE'],
//                "VK_API_ID" => $arParams['VK_API_ID'],
//                "CACHE_TYPE" => $arParams['CACHE_TYPE'],
//                "CACHE_TIME" => $arParams['CACHE_TIME'],
//                "BLOG_TITLE" => "",
//                "BLOG_URL" => "",
//                "PATH_TO_SMILE" => "/bitrix/images/blog/smile/",
//                "EMAIL_NOTIFY" => "N",
//                "AJAX_POST" => "Y",
//                "SHOW_SPAM" => "Y",
//                "SHOW_RATING" => "Y",
//                "FB_TITLE" => "",
//                "FB_USER_ADMIN_ID" => "",
//                "FB_APP_ID" => $arParams['FB_APP_ID'],
//                "FB_COLORSCHEME" => "light",
//                "FB_ORDER_BY" => "reverse_time",
//                "VK_TITLE" => "",
//                "GOODS_RATE" => isset($arResult['PROPERTIES']['RATING']) ? $arResult['PROPERTIES']['RATING'] : ''
//            ),
//            $component,
//            array("HIDE_ICONS" => "Y")
//        );?>

    </div>
<? endif; ?>