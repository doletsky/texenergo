<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile(__FILE__);
CUtil::InitJSCore();
$arCurrentSite = CSite::GetByID(SITE_ID)->Fetch();
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/secure_auth.php';
?><!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="ru"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name='yandex-verification' content='4474e63f57b83eed' />
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic' rel='stylesheet'
          type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Exo+2:400,700,600&subset=latin,cyrillic' rel='stylesheet'
          type='text/css'>
    <meta name="yandex" content="noyaca"/>
    <?

    CJSCore::Init(array("jquery")); // 1.8

    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/normalize.min.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/960.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/owl.carousel.css');
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/owl.carousel2.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/owl.theme.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/catalog.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/popups.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/select2.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/searchbox.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/forms.css', true);
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/forum.css', true);
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/buttons.css', true);
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/integration.css', true);

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/placeholders.jquery.min.js');

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/jquery.masonry.min.js');

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/jquery.autocomplete.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/jquery.carouFredSel-6.2.1-packed.js');

	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/topbtn.js');

    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/js/vendor/fancybox/jquery.fancybox.css');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/fancybox/jquery.fancybox.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/fancybox/helpers/jquery.fancybox-thumbs.js');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/js/vendor/fancybox/helpers/jquery.fancybox-thumbs.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/fancybox.custom.css');


    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/validate.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/garlic.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/jquery.maskedinput.min.js');

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/chart.js');

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH .'/js/vendor/datepicker/js/datepicker.js');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH .'/js/vendor/datepicker/css/datepicker.css');


    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/easytabs.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/rem.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/select2.min.js');
    $APPLICATION->AddHeadScript('//cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/TweenLite.min.js');
    $APPLICATION->AddHeadScript('//cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/TimelineMax.min.js');
    $APPLICATION->AddHeadScript('//cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/plugins/CSSPlugin.min.js');
    $APPLICATION->AddHeadScript('//cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/easing/EasePack.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/dont_replace/owl.carousel.js');

	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/dont_replace/owl.carousel2.js');
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/dont_replace/owl.autoplay.js');
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/dont_replace/owl.navigation.js');

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/jscrollpane/jquery.jscrollpane.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/jscrollpane/jquery.mousewheel.js');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/js/vendor/jscrollpane/jquery.jscrollpane.css');

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/quantity/jquery.quantity.js');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/js/vendor/quantity/jquery.quantity.css');

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/compare.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/favorites.js', true);

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/main.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/forms.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/modals.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/catalog.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/function.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/basket.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/dont_replace/script.js', true);
    ?>

    <? $APPLICATION->ShowHead(); ?>

    <meta name="title" content="<? $APPLICATION->ShowTitle(); ?>">
    <title><? $APPLICATION->ShowTitle(); ?></title>

    <meta name="viewport" content="width=device-width">

    <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script>window.html5 || document.write('<script src="<?=SITE_TEMPLATE_PATH?>/js/vendor/html5shiv.js"><\/script>')</script>
    <script src="//ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
    <![endif]-->
</head>
<body itemscope itemtype="http://schema.org/WebPage">
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>

<? include $_SERVER['DOCUMENT_ROOT'] . '/include/infocenter_popup_with_wrap.php'; ?>
<?if(!defined('ERROR_404')):?>
<!-- HEADER START -->
<header class="header" itemscope itemtype="http://schema.org/WPHeader">
<div class="header-top">
    <div class="container" itemscope itemtype="http://schema.org/ContactPoint">
        <nav class="top-bar twelve">
            <ul>
                <li id="menu" class="menu">
                    <a href="#" id="menu-down">Меню<i class="icon"></i></a>
                    <?$APPLICATION->IncludeComponent(
						"bitrix:menu",
						"topleft",
						Array(
							"ROOT_MENU_TYPE" => "topleft",
							"MAX_LEVEL" => "1",
							"CHILD_MENU_TYPE" => "",
							"USE_EXT" => "N",
							"DELAY" => "N",
							"ALLOW_MULTI_SELECT" => "N",
							"MENU_CACHE_TYPE" => "N",
							"MENU_CACHE_TIME" => "3600",
							"MENU_CACHE_USE_GROUPS" => "Y",
							"MENU_CACHE_GET_VARS" => array()
						)
					);?>
                </li>
                <li class="phone"><i class="icon"></i><span itemprop="telephone">Москва: +7 (495) 651-99-99</span></li>
                <li id="callback" class="callback"><a data-height="auto" data-width="300" class="popup popup_cb" href="/ajax/callback.php">Обратный звонок</a></li>
                <li id="manufacture" class="callback"><i class="icon"></i><a href="/eshop/delivery/">Доставка</a></li>
                <li id="contacts" class="callback"><i class="icon"></i><a href="/about/contacts/">Контакты</a></li>
                <li class="delivery">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => "/include/top_header_text.php"
                        )
                    );?>
                </li>
                <li id="consultancy" class="consultancy">
					<?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => "/include/top_header_online_consult.php"
                        )
                    );?>
				</li>
                <!--<li id="manufacture" class="manufacture">Наше производство<i class="icon"></i></li>-->
				<li id="info_center_link" class="manufacture">Информационный центр<i class="icon"></i></li>
            </ul>
        </nav>
        <!-- top-bar -->
    </div>
    <!-- container -->
</div>
<!-- ./header-top -->

<!-- HEADER-TOP END -->

<!-- HEADER-MAIN START -->

<div class="header-main" itemscope itemtype="http://schema.org/Organization">
	<meta itemprop="name" content="МФК Texenergo" />
        <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
         <meta itemprop="addressCountry" content="Россия" />
         <meta itemprop="addressRegion" content="Московская область" />
         <meta itemprop="addressLocality" content="дер. Черная Грязь" />
         <meta itemprop="streetAddress" content="ул. Сходненская дом 65" />
         <meta itemprop="email" content="inform@texenergo.ru" />
         <meta itemprop="postalCode" content="141580"/>
         <meta itemprop="telephone" content="+7 (495) 651-99-99" />
         <meta itemprop="description" content="Производство низковольтной аппаратуры. Оптовые поставки электротехнической продукции. Проектирование и сборка электрощитового оборудования" />
        </div>
<div class="container">
<nav class="main-nav twelve">
<ul>
<li class="logo"><?= ($APPLICATION->GetCurPage(false) != '/') ? '<a href="/">' : ''?><img src="<?= SITE_TEMPLATE_PATH ?>/img/header/logo.png" alt="Производство низковольтной аппаратуры. Оптовые поставки электротехнической продукции. Проектирование и сборка электрощитового оборудования." width="166"
                                  height="46" title='<?= ($APPLICATION->GetCurPage(false) != '/') ? 'Перейти на главную страницу' : 'Логотип компании ООО "МФК TEXENERGO"'?>'><?= ($APPLICATION->GetCurPage(false) != '/') ? '</a>' : ''?></li>
<li class="j-market-menu-link">
    <a href="/catalog/" id="categories-link">Каталог товаров <!--<i class="icon"></i>--> </a>
    <?/*<section class="categories clearfix hidden">
        <div class="box-arrow"></div>

        <?$APPLICATION->IncludeComponent(
            "texenergo:catalog.section.list",
            "main_menu_sections",
            Array(
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => IBLOCK_ID_CATALOG,
                "SECTION_ID" => "",
                "SECTION_CODE" => "",
                "SECTION_URL" => "/catalog/list.html/#SECTION_CODE#",
                "COUNT_ELEMENTS" => "N",
                "TOP_DEPTH" => "3",
                "SECTION_FIELDS" => array(),
                "SECTION_USER_FIELDS" => array("UF_*"),
                "ADD_SECTIONS_CHAIN" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000",
                "CACHE_GROUPS" => "N",
                "FILTER_NAME" => "vendorFilter_" . IntVal($_COOKIE['main_vendor']),
            ),
            false
        );?>
        <aside>
            <?$APPLICATION->IncludeComponent(
                "texenergo:offer.list",
                "main_menu_banner",
                Array(
                    "DISPLAY_DATE" => "Y",
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => "Y",
                    "DISPLAY_PREVIEW_TEXT" => "Y",
                    "AJAX_MODE" => "N",
                    "IBLOCK_TYPE" => "banners",
                    "IBLOCK_ID" => "14",
                    "NEWS_COUNT" => "1",
                    "SORT_BY1" => "ACTIVE_FROM",
                    "SORT_ORDER1" => "DESC",
                    "SORT_BY2" => "SORT",
                    "SORT_ORDER2" => "ASC",
                    "FILTER_NAME" => "",
                    "FIELD_CODE" => array(),
                    "PROPERTY_CODE" => array("BANNER_LINK"),
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
                    "CACHE_GROUPS" => "N",
                    "PAGER_TEMPLATE" => ".default",
                    "DISPLAY_TOP_PAGER" => "N",
                    "DISPLAY_BOTTOM_PAGER" => "N",
                    "PAGER_TITLE" => "Новости",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "AJAX_OPTION_HISTORY" => "N"
                ),
                false
            );?>
        </aside>
    </section>*/ ?>
</li>
<!--<li>-->
    <!--<a href="/manufacturers/" id="manufactures-link">каталоги <i class="icon"></i></a>-->
    <?
    /* global $topVendorFilter,
           $otherVendorFilter,
           $localTopVendorFilter,
           $localOtherVendorFilter;

    $topVendorFilter = array(
        'PROPERTY_SHOW_VENDOR_IN_CATALOG_VALUE' => 'Y'
    );
    $otherVendorFilter = array(
        '!PROPERTY_SHOW_VENDOR_IN_CATALOG_VALUE' => 'Y'
    );

    ?>

    <section class="manufacture-dropdown clearfix hidden">

        <div class="brands-wrap all-manufactures">
            <?$APPLICATION->IncludeComponent(
                "texenergo:offer.list",
                "top-manufactures",
                Array(
                    "DISPLAY_DATE" => "Y",
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => "Y",
                    "DISPLAY_PREVIEW_TEXT" => "Y",
                    "AJAX_MODE" => "N",
                    "IBLOCK_TYPE" => "catalog",
                    "IBLOCK_ID" => IBLOCK_ID_BRANDS,
                    "NEWS_COUNT" => "4",
                    "SORT_BY1" => "ACTIVE_FROM",
                    "SORT_ORDER1" => "DESC",
                    "SORT_BY2" => "SORT",
                    "SORT_ORDER2" => "ASC",
                    "FILTER_NAME" => "topVendorFilter",
                    "FIELD_CODE" => array(
                        0 => "DETAIL_PICTURE",
                        1 => "",
                    ),
                    "PROPERTY_CODE" => array("RUSSIAN_VENDOR", "VENDOR_POPULAR_ON"),
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
                    "CACHE_GROUPS" => "N",
                    "PAGER_TEMPLATE" => ".default",
                    "DISPLAY_TOP_PAGER" => "N",
                    "DISPLAY_BOTTOM_PAGER" => "N",
                    "PAGER_TITLE" => "Новости",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "AJAX_OPTION_HISTORY" => "N"
                ),
                false
            );?>
            <?$APPLICATION->IncludeComponent("texenergo:offer.list", "other-manufactures", array(
                    "IBLOCK_TYPE" => "vendors",
                    "IBLOCK_ID" => IBLOCK_ID_BRANDS,
                    "NEWS_COUNT" => "24",
                    "SORT_BY1" => "ACTIVE_FROM",
                    "SORT_ORDER1" => "DESC",
                    "SORT_BY2" => "SORT",
                    "SORT_ORDER2" => "ASC",
                    "FILTER_NAME" => "otherVendorFilter",
                    "FIELD_CODE" => array(
                        0 => "DETAIL_PICTURE",
                        1 => "",
                    ),
                    "PROPERTY_CODE" => array(
                        0 => "RUSSIAN_VENDOR",
                        1 => "VENDOR_POPULAR_ON",
                        2 => "",
                    ),
                    "CHECK_DATES" => "Y",
                    "DETAIL_URL" => "",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "AJAX_OPTION_HISTORY" => "N",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "36000000",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "N",
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
                    "PAGER_TEMPLATE" => ".default",
                    "DISPLAY_TOP_PAGER" => "N",
                    "DISPLAY_BOTTOM_PAGER" => "N",
                    "PAGER_TITLE" => "Новости",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "AJAX_OPTION_ADDITIONAL" => ""
                ),
                false
            );?>
        </div>

    </section>

	<? */?>
    <!-- ./manufacture-dropdown -->
<!--</li>-->
<li style="margin-right:150px;"><a href="/price_list/" class="orange">прайс-листы</a></li>
<!-- bitrix/search.form/catalog-search -->
<li class="search">
    <?$APPLICATION->IncludeComponent(
        "bitrix:search.form",
        "full-search",
        Array(
            "USE_SUGGEST" => "N",
            "PAGE" => "#SITE_DIR#search/index.php"
        ),
        false
    );?>
</li>
<!-- end bitrix/search.form/catalog-search -->
<li id="cart" class="cart">
    <? include $_SERVER['DOCUMENT_ROOT'] . '/basket/ajax/small.php'; ?>
</li>
<li id="login" class="login">
    <? if ($USER->isAuthorized()): ?>
        <a href="/personal/">
            <i class="icon-cabinet"></i>

			<?$userName = $USER->GetEmail();
			if(empty($userName))
				$userName = $USER->GetFirstName();
			?>

			<span title="<?= $userName; ?>">
                <?= TruncateText($userName, 7); ?>
            </span>
        </a>
        <a class="logout" href="<?= $APPLICATION->GetCurPageParam('logout=yes', Array('logout')); ?>">
            Выйти
        </a>
    <? else: ?>
        <a href="/personal/">
            <i class="icon-login"></i>
            <span>Войти</span>
        </a>
    <?endif; ?>
    <? /*<i class="icon"></i>*/ ?>
</li>
</ul>
</nav>
<!-- main-nav -->
</div>
<!-- container -->
</div>
<!-- header-main -->

<!-- HEADER-MAIN END -->


</header>

<!-- ./header -->

<?$APPLICATION->IncludeComponent(
    "bitrix:menu",
    "profilemenu",
    Array(
        "ROOT_MENU_TYPE" => "profile",
        "MAX_LEVEL" => "1",
        "CHILD_MENU_TYPE" => "left",
        "USE_EXT" => "Y",
        "DELAY" => "N",
        "ALLOW_MULTI_SELECT" => "N",
        "MENU_CACHE_TYPE" => "N",
        "MENU_CACHE_TIME" => "3600",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "MENU_CACHE_GET_VARS" => array()
    )
);?>

<!-- HEADER END -->

<? if ($APPLICATION->GetCurDir() != '/'): ?>
<!-- START CRUMBS -->
<?$APPLICATION->IncludeComponent(
    "bitrix:breadcrumb",
    "",
    Array(
        "START_FROM" => "0",
        "PATH" => "",
        "SITE_ID" => SITE_ID
    ),
    false
);?>
<!-- END CRUMBS -->

<div class="main">
    <div class="container">

        <? endif; ?>


		<?
		if($_REQUEST['ajax_call'] == 'y'){
			$APPLICATION->RestartBuffer();
			$APPLICATION->IncludeComponent(
				"bitrix:breadcrumb",
				"infocenter",
				Array(
					"START_FROM" => "0",
					"PATH" => "",
					"SITE_ID" => SITE_ID
				),
				false
			);
		}
		?>


        <?/**
         *  В заказе используется системная авторизация, поэтому шапка вынесена за пределы компонента и страницы
         */
        ?>
        <? if (!$USER->isAuthorized() && $APPLICATION->GetCurDir() == '/order/'): ?>
            <? include $_SERVER['DOCUMENT_ROOT'] . '/local/components/aero/sale.order/templates/.default/head.php'; ?>
        <? endif; ?>

		<?$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
				"AREA_FILE_SHOW" => "sect",
				"AREA_FILE_SUFFIX" => "inc_menu",
				"AREA_FILE_RECURSIVE" => "Y",
				"EDIT_TEMPLATE" => "standard.php"
			)
		);?>
<?endif;?>