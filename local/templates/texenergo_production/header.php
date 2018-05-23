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

    //CJSCore::Init(array("jquery")); // 1.8
    //$APPLICATION->AddHeadScript('https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/jquery.js');

    //$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/normalize.min.css');
?>
   <?
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/bootstrap.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/bootstrap-big-grid.css');

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
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/fonts/MaterialDesignIconicFont/material-design-iconic-font.css', true);

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
    //$APPLICATION->AddHeadScript('//cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/TweenLite.min.js');
    //$APPLICATION->AddHeadScript('//cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/TimelineMax.min.js');
    //$APPLICATION->AddHeadScript('//cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/plugins/CSSPlugin.min.js');
    //$APPLICATION->AddHeadScript('//cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/easing/EasePack.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/dont_replace/owl.carousel.js');

	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/dont_replace/owl.carousel2.js');
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/dont_replace/owl.autoplay.js');
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/dont_replace/owl.navigation.js');

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/jscrollpane/jquery.jscrollpane.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/jscrollpane/jquery.mousewheel.js');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/js/vendor/jscrollpane/jquery.jscrollpane.css');

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/quantity/jquery.quantity.js');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/js/vendor/quantity/jquery.quantity.css');
    //$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/cycle/jquery.cycle2.js');

    //$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/compare.js', true);
    //$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/favorites.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/bootstrap.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/bootstrap.file-input.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/main.js', true);
    //$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/forms.js', true);
    //$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/modals.js', true);
    //$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/catalog.js', true);
    //$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/function.js', true);
    //$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/basket.js', true);
    //$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/dont_replace/script.js', true);
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
<style>
#heroSlider,
.block-stock-main,
#news-slider {
display:none;
}
</style>
</head>
<body itemscope itemtype="http://schema.org/WebPage">
<!--<span>nku.texenergo.ru</span>-->
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter1320001 = new Ya.Metrika({
                    id:1320001,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/1320001" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>

<? //include $_SERVER['DOCUMENT_ROOT'] . '/include/infocenter_popup_with_wrap.php'; ?>
<?if(!defined('ERROR_404')):?>
<!-- HEADER START -->
<header class="header" itemscope itemtype="http://schema.org/WPHeader">

<!-- HEADER-TOP END -->

<!-- HEADER-MAIN START -->

<div class="<?= ($APPLICATION->GetCurPage(false) != '/') ? 'header-no-main' : 'header-main'?>" itemscope itemtype="http://schema.org/Organization">
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
	   <div class="row blk-top">
		<nav class="main-nav col-xlg-4 col-lg-4 col-md-4 col-sm-6 col-xs-12">
		<ul>
		<li class="logo "><?= ($APPLICATION->GetCurPage(false) != '/') ? '<a href="/">' : ''?><img src="<?= SITE_TEMPLATE_PATH ?>/img/main/logo-head.jpg" alt="Производство низковольтной аппаратуры. Оптовые поставки электротехнической продукции. Проектирование и сборка электрощитового оборудования." width="280"
                                  height="81" title='<?= ($APPLICATION->GetCurPage(false) != '/') ? 'Перейти на главную страницу' : 'Логотип компании ООО "МФК TEXENERGO"'?>'><?= ($APPLICATION->GetCurPage(false) != '/') ? '</a>' : ''?>
		<span class="logo-text">Успех начинается здесь!</span>
		</li>
		</ul>
		</nav>

		<div class="header-top col-xlg-8 col-lg-8 col-md-8 col-sm-12 col-xs-12">
    			<div class="" itemscope itemtype="http://schema.org/ContactPoint">
            <button class="cmn-toggle-switch cmn-toggle-switch__rot">
              <span>toggle menu</span>
            </button>
            <nav class="header-menu-collapsible" style="display:none;">
              <ul class="header-menu-items">
                <li><a href="https://www.texenergo.ru/publication/novosti_texenergo/">Новости</a></li>
                <li><a href="https://www.texenergo.ru/publication/statyi/">Статьи</a></li>
                <li><a href="https://www.texenergo.ru/forum/">Форум</a></li>
                <li><a href="https://www.texenergo.ru/about/contacts/">Контакты</a></li>
              </ul>              
            </nav>

        		<nav class="top-bar navbar">
<? $menuTop = array (
              "Производство в Китае"=>"/production-in-china/",
              "Производство в России"=>"/our-production/", 
              "Патенты"=>"/patents/",
              "Гарантия и качество"=>"/quality-assurance/",
);?>
	  		<div class="">
            		<ul class="nav navbar-nav navbar-right">
<?foreach ($menuTop as $title=>$url) {
  $class = strpos($APPLICATION->GetCurPage(false), $url) !== false ? " class='active'" : "";
  echo '<li><a'.$class.' href="'.$url.'">'.$title.'</a><li>';
}
?>
            		</ul>
<!--
	    <form class="search-panel navbar-form navbar-right" role="search">
              <div class="form-group">
                <input type="text" class="search-input form-control" placeholder="Я ищу...">
              </div>
                <button type="submit" class="btn btn-default btn-search"></button>
            </form>
-->
	  		</div><!-- container -->
        		</nav><!-- top-bar -->
    			</div><!-- container -->
		</div><!-- ./header-top -->
	</div><!-- ./row -->
<?if ($APPLICATION->GetCurPage(false) == '/'):?>
        <?// block circles ?>
	<?$APPLICATION->IncludeFile('/include_production/prod_main_circle.php');?>
<?endif;?>
  </div><!-- container -->
</div><!-- header-bottom-->


<!-- HEADER-MAIN END -->


</header>

<!-- ./header -->

<?endif;?>