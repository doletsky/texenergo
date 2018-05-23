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
      //if ($APPLICATION->GetCurPage(false) !== '/') :
    //if (!isResponsive()) : ?>
	<style>/*960.css*/body{min-width:960px}.container{margin-left:auto;margin-right:auto;width:960px}.one,.two,.three,.four,.five,.six,.seven,.eight,.nine,.ten,.eleven,.twelve{display:inline;float:left;margin-left:10px;margin-right:10px}.push_1,.pull_1,.push_2,.pull_2,.push_3,.pull_3,.push_4,.pull_4,.push_5,.pull_5,.push_6,.pull_6,.push_7,.pull_7,.push_8,.pull_8,.push_9,.pull_9,.push_10,.pull_10,.push_11,.pull_11{position:relative}.alpha{margin-left:0}.omega{margin-right:0}.container .one{width:60px}.container .two{width:140px}.container .three{width:220px}.container .four{width:300px}.container .five{width:380px}.container .six{width:460px}.container .seven{width:540px}.container .eight{width:620px}.container .nine{width:700px}.container .ten{width:780px}.container .eleven{width:860px}.container .twelve{width:940px}.container .prefix_1{padding-left:80px}.container .prefix_2{padding-left:160px}.container .prefix_3{padding-left:240px}.container .prefix_4{padding-left:320px}.container .prefix_5{padding-left:400px}.container .prefix_6{padding-left:480px}.container .prefix_7{padding-left:560px}.container .prefix_8{padding-left:640px}.container .prefix_9{padding-left:720px}.container .prefix_10{padding-left:800px}.container .prefix_11{padding-left:880px}.container .suffix_1{padding-right:80px}.container .suffix_2{padding-right:160px}.container .suffix_3{padding-right:240px}.container .suffix_4{padding-right:320px}.container .suffix_5{padding-right:400px}.container .suffix_6{padding-right:480px}.container .suffix_7{padding-right:560px}.container .suffix_8{padding-right:640px}.container .suffix_9{padding-right:720px}.container .suffix_10{padding-right:800px}.container .suffix_11{padding-right:880px}.container .push_1{left:80px}.container .push_2{left:160px}.container .push_3{left:240px}.container .push_4{left:320px}.container .push_5{left:400px}.container .push_6{left:480px}.container .push_7{left:560px}.container .push_8{left:640px}.container .push_9{left:720px}.container .push_10{left:800px}.container .push_11{left:880px}.container .pull_1{left:-80px}.container .pull_2{left:-160px}.container .pull_3{left:-240px}.container .pull_4{left:-320px}.container .pull_5{left:-400px}.container .pull_6{left:-480px}.container .pull_7{left:-560px}.container .pull_8{left:-640px}.container .pull_9{left:-720px}.container .pull_10{left:-800px}.container .pull_11{left:-880px}.clear{clear:both;display:block;overflow:hidden;visibility:hidden;width:0;height:0}.clearfix:before,.clearfix:after,.container:before,.container:after{content:'.';display:block;overflow:hidden;visibility:hidden;font-size:0;line-height:0;width:0;height:0}.clearfix:after,.container:after{clear:both}.clearfix,.container{zoom:1}</style>
   <? if(false): ?>
	<style>/*grid-responsive.css*/@media(max-width:400px){@-ms-viewport{width:320px}}.clear{clear:both;display:block;overflow:hidden;visibility:hidden;width:0;height:0}.container:before,.grid-5:before,.mobile-grid-5:before,.tablet-grid-5:before,.grid-10:before,.mobile-grid-10:before,.tablet-grid-10:before,.grid-15:before,.mobile-grid-15:before,.tablet-grid-15:before,.grid-20:before,.mobile-grid-20:before,.tablet-grid-20:before,.grid-25:before,.mobile-grid-25:before,.tablet-grid-25:before,.grid-30:before,.mobile-grid-30:before,.tablet-grid-30:before,.grid-35:before,.mobile-grid-35:before,.tablet-grid-35:before,.grid-40:before,.mobile-grid-40:before,.tablet-grid-40:before,.grid-45:before,.mobile-grid-45:before,.tablet-grid-45:before,.grid-50:before,.mobile-grid-50:before,.tablet-grid-50:before,.grid-55:before,.mobile-grid-55:before,.tablet-grid-55:before,.grid-60:before,.mobile-grid-60:before,.tablet-grid-60:before,.grid-65:before,.mobile-grid-65:before,.tablet-grid-65:before,.grid-70:before,.mobile-grid-70:before,.tablet-grid-70:before,.grid-75:before,.mobile-grid-75:before,.tablet-grid-75:before,.grid-80:before,.mobile-grid-80:before,.tablet-grid-80:before,.grid-85:before,.mobile-grid-85:before,.tablet-grid-85:before,.grid-90:before,.mobile-grid-90:before,.tablet-grid-90:before,.grid-95:before,.mobile-grid-95:before,.tablet-grid-95:before,.grid-100:before,.twelve:before,.mobile-grid-100:before,.tablet-grid-100:before,.grid-33:before,.mobile-grid-33:before,.tablet-grid-33:before,.grid-66:before,.mobile-grid-66:before,.tablet-grid-66:before,.grid-offset:before,.clearfix:before,.container:after,.grid-5:after,.mobile-grid-5:after,.tablet-grid-5:after,.grid-10:after,.mobile-grid-10:after,.tablet-grid-10:after,.grid-15:after,.mobile-grid-15:after,.tablet-grid-15:after,.grid-20:after,.mobile-grid-20:after,.tablet-grid-20:after,.grid-25:after,.mobile-grid-25:after,.tablet-grid-25:after,.grid-30:after,.mobile-grid-30:after,.tablet-grid-30:after,.grid-35:after,.mobile-grid-35:after,.tablet-grid-35:after,.grid-40:after,.mobile-grid-40:after,.tablet-grid-40:after,.grid-45:after,.mobile-grid-45:after,.tablet-grid-45:after,.grid-50:after,.mobile-grid-50:after,.tablet-grid-50:after,.grid-55:after,.mobile-grid-55:after,.tablet-grid-55:after,.grid-60:after,.mobile-grid-60:after,.tablet-grid-60:after,.grid-65:after,.mobile-grid-65:after,.tablet-grid-65:after,.grid-70:after,.mobile-grid-70:after,.tablet-grid-70:after,.grid-75:after,.mobile-grid-75:after,.tablet-grid-75:after,.grid-80:after,.mobile-grid-80:after,.tablet-grid-80:after,.grid-85:after,.mobile-grid-85:after,.tablet-grid-85:after,.grid-90:after,.mobile-grid-90:after,.tablet-grid-90:after,.grid-95:after,.mobile-grid-95:after,.tablet-grid-95:after,.grid-100:after,.twelve:after,.mobile-grid-100:after,.tablet-grid-100:after,.grid-33:after,.mobile-grid-33:after,.tablet-grid-33:after,.grid-66:after,.mobile-grid-66:after,.tablet-grid-66:after,.grid-offset:after,.clearfix:after{content:".";display:block;overflow:hidden;visibility:hidden;font-size:0;line-height:0;width:0;height:0}.container:after,.grid-5:after,.mobile-grid-5:after,.tablet-grid-5:after,.grid-10:after,.mobile-grid-10:after,.tablet-grid-10:after,.grid-15:after,.mobile-grid-15:after,.tablet-grid-15:after,.grid-20:after,.mobile-grid-20:after,.tablet-grid-20:after,.grid-25:after,.mobile-grid-25:after,.tablet-grid-25:after,.grid-30:after,.mobile-grid-30:after,.tablet-grid-30:after,.grid-35:after,.mobile-grid-35:after,.tablet-grid-35:after,.grid-40:after,.mobile-grid-40:after,.tablet-grid-40:after,.grid-45:after,.mobile-grid-45:after,.tablet-grid-45:after,.grid-50:after,.mobile-grid-50:after,.tablet-grid-50:after,.grid-55:after,.mobile-grid-55:after,.tablet-grid-55:after,.grid-60:after,.mobile-grid-60:after,.tablet-grid-60:after,.grid-65:after,.mobile-grid-65:after,.tablet-grid-65:after,.grid-70:after,.mobile-grid-70:after,.tablet-grid-70:after,.grid-75:after,.mobile-grid-75:after,.tablet-grid-75:after,.grid-80:after,.mobile-grid-80:after,.tablet-grid-80:after,.grid-85:after,.mobile-grid-85:after,.tablet-grid-85:after,.grid-90:after,.mobile-grid-90:after,.tablet-grid-90:after,.grid-95:after,.mobile-grid-95:after,.tablet-grid-95:after,.grid-100:after,.twelve:after,.mobile-grid-100:after,.tablet-grid-100:after,.grid-33:after,.mobile-grid-33:after,.tablet-grid-33:after,.grid-66:after,.mobile-grid-66:after,.tablet-grid-66:after,.grid-offset:after,.clearfix:after{clear:both}.container{margin-left:auto;margin-right:auto;//:1200px;max-width:960px;padding-left:10px;padding-right:10px}.grid-5,.mobile-grid-5,.tablet-grid-5,.grid-10,.mobile-grid-10,.tablet-grid-10,.grid-15,.mobile-grid-15,.tablet-grid-15,.grid-20,.mobile-grid-20,.tablet-grid-20,.grid-25,.mobile-grid-25,.tablet-grid-25,.grid-30,.mobile-grid-30,.tablet-grid-30,.grid-35,.mobile-grid-35,.tablet-grid-35,.grid-40,.mobile-grid-40,.tablet-grid-40,.grid-45,.mobile-grid-45,.tablet-grid-45,.grid-50,.mobile-grid-50,.tablet-grid-50,.grid-55,.mobile-grid-55,.tablet-grid-55,.grid-60,.mobile-grid-60,.tablet-grid-60,.grid-65,.mobile-grid-65,.tablet-grid-65,.grid-70,.mobile-grid-70,.tablet-grid-70,.grid-75,.mobile-grid-75,.tablet-grid-75,.grid-80,.mobile-grid-80,.tablet-grid-80,.grid-85,.mobile-grid-85,.tablet-grid-85,.grid-90,.mobile-grid-90,.tablet-grid-90,.grid-95,.mobile-grid-95,.tablet-grid-95,.grid-100,.twelve,.mobile-grid-100,.tablet-grid-100,.grid-33,.mobile-grid-33,.tablet-grid-33,.grid-66,.mobile-grid-66,.tablet-grid-66{-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;padding-left:10px;padding-right:10px}.grid-parent{padding-left:0;padding-right:0}.grid-offset{margin-left:-10px;margin-right:-10px}@media(max-width:767px){.mobile-push-5,.mobile-pull-5,.mobile-push-10,.mobile-pull-10,.mobile-push-15,.mobile-pull-15,.mobile-push-20,.mobile-pull-20,.mobile-push-25,.mobile-pull-25,.mobile-push-30,.mobile-pull-30,.mobile-push-35,.mobile-pull-35,.mobile-push-40,.mobile-pull-40,.mobile-push-45,.mobile-pull-45,.mobile-push-50,.mobile-pull-50,.mobile-push-55,.mobile-pull-55,.mobile-push-60,.mobile-pull-60,.mobile-push-65,.mobile-pull-65,.mobile-push-70,.mobile-pull-70,.mobile-push-75,.mobile-pull-75,.mobile-push-80,.mobile-pull-80,.mobile-push-85,.mobile-pull-85,.mobile-push-90,.mobile-pull-90,.mobile-push-95,.mobile-pull-95,.mobile-push-33,.mobile-pull-33,.mobile-push-66,.mobile-pull-66{position:relative}.hide-on-mobile{display:none!important}.mobile-grid-5{float:left;width:5%}.mobile-prefix-5{margin-left:5%}.mobile-suffix-5{margin-right:5%}.mobile-push-5{left:5%}.mobile-pull-5{left:-5%}.mobile-grid-10{float:left;width:10%}.mobile-prefix-10{margin-left:10%}.mobile-suffix-10{margin-right:10%}.mobile-push-10{left:10%}.mobile-pull-10{left:-10%}.mobile-grid-15{float:left;width:15%}.mobile-prefix-15{margin-left:15%}.mobile-suffix-15{margin-right:15%}.mobile-push-15{left:15%}.mobile-pull-15{left:-15%}.mobile-grid-20{float:left;width:20%}.mobile-prefix-20{margin-left:20%}.mobile-suffix-20{margin-right:20%}.mobile-push-20{left:20%}.mobile-pull-20{left:-20%}.mobile-grid-25{float:left;width:25%}.mobile-prefix-25{margin-left:25%}.mobile-suffix-25{margin-right:25%}.mobile-push-25{left:25%}.mobile-pull-25{left:-25%}.mobile-grid-30{float:left;width:30%}.mobile-prefix-30{margin-left:30%}.mobile-suffix-30{margin-right:30%}.mobile-push-30{left:30%}.mobile-pull-30{left:-30%}.mobile-grid-35{float:left;width:35%}.mobile-prefix-35{margin-left:35%}.mobile-suffix-35{margin-right:35%}.mobile-push-35{left:35%}.mobile-pull-35{left:-35%}.mobile-grid-40{float:left;width:40%}.mobile-prefix-40{margin-left:40%}.mobile-suffix-40{margin-right:40%}.mobile-push-40{left:40%}.mobile-pull-40{left:-40%}.mobile-grid-45{float:left;width:45%}.mobile-prefix-45{margin-left:45%}.mobile-suffix-45{margin-right:45%}.mobile-push-45{left:45%}.mobile-pull-45{left:-45%}.mobile-grid-50{float:left;width:50%}.mobile-prefix-50{margin-left:50%}.mobile-suffix-50{margin-right:50%}.mobile-push-50{left:50%}.mobile-pull-50{left:-50%}.mobile-grid-55{float:left;width:55%}.mobile-prefix-55{margin-left:55%}.mobile-suffix-55{margin-right:55%}.mobile-push-55{left:55%}.mobile-pull-55{left:-55%}.mobile-grid-60{float:left;width:60%}.mobile-prefix-60{margin-left:60%}.mobile-suffix-60{margin-right:60%}.mobile-push-60{left:60%}.mobile-pull-60{left:-60%}.mobile-grid-65{float:left;width:65%}.mobile-prefix-65{margin-left:65%}.mobile-suffix-65{margin-right:65%}.mobile-push-65{left:65%}.mobile-pull-65{left:-65%}.mobile-grid-70{float:left;width:70%}.mobile-prefix-70{margin-left:70%}.mobile-suffix-70{margin-right:70%}.mobile-push-70{left:70%}.mobile-pull-70{left:-70%}.mobile-grid-75{float:left;width:75%}.mobile-prefix-75{margin-left:75%}.mobile-suffix-75{margin-right:75%}.mobile-push-75{left:75%}.mobile-pull-75{left:-75%}.mobile-grid-80{float:left;width:80%}.mobile-prefix-80{margin-left:80%}.mobile-suffix-80{margin-right:80%}.mobile-push-80{left:80%}.mobile-pull-80{left:-80%}.mobile-grid-85{float:left;width:85%}.mobile-prefix-85{margin-left:85%}.mobile-suffix-85{margin-right:85%}.mobile-push-85{left:85%}.mobile-pull-85{left:-85%}.mobile-grid-90{float:left;width:90%}.mobile-prefix-90{margin-left:90%}.mobile-suffix-90{margin-right:90%}.mobile-push-90{left:90%}.mobile-pull-90{left:-90%}.mobile-grid-95{float:left;width:95%}.mobile-prefix-95{margin-left:95%}.mobile-suffix-95{margin-right:95%}.mobile-push-95{left:95%}.mobile-pull-95{left:-95%}.mobile-grid-33{float:left;width:33.33333%}.mobile-prefix-33{margin-left:33.33333%}.mobile-suffix-33{margin-right:33.33333%}.mobile-push-33{left:33.33333%}.mobile-pull-33{left:-33.33333%}.mobile-grid-66{float:left;width:66.66667%}.mobile-prefix-66{margin-left:66.66667%}.mobile-suffix-66{margin-right:66.66667%}.mobile-push-66{left:66.66667%}.mobile-pull-66{left:-66.66667%}.mobile-grid-100{clear:both;width:100%}}@media(min-width:768px) and (max-width:1024px){.tablet-push-5,.tablet-pull-5,.tablet-push-10,.tablet-pull-10,.tablet-push-15,.tablet-pull-15,.tablet-push-20,.tablet-pull-20,.tablet-push-25,.tablet-pull-25,.tablet-push-30,.tablet-pull-30,.tablet-push-35,.tablet-pull-35,.tablet-push-40,.tablet-pull-40,.tablet-push-45,.tablet-pull-45,.tablet-push-50,.tablet-pull-50,.tablet-push-55,.tablet-pull-55,.tablet-push-60,.tablet-pull-60,.tablet-push-65,.tablet-pull-65,.tablet-push-70,.tablet-pull-70,.tablet-push-75,.tablet-pull-75,.tablet-push-80,.tablet-pull-80,.tablet-push-85,.tablet-pull-85,.tablet-push-90,.tablet-pull-90,.tablet-push-95,.tablet-pull-95,.tablet-push-33,.tablet-pull-33,.tablet-push-66,.tablet-pull-66{position:relative}.hide-on-tablet{display:none!important}.tablet-grid-5{float:left;width:5%}.tablet-prefix-5{margin-left:5%}.tablet-suffix-5{margin-right:5%}.tablet-push-5{left:5%}.tablet-pull-5{left:-5%}.tablet-grid-10{float:left;width:10%}.tablet-prefix-10{margin-left:10%}.tablet-suffix-10{margin-right:10%}.tablet-push-10{left:10%}.tablet-pull-10{left:-10%}.tablet-grid-15{float:left;width:15%}.tablet-prefix-15{margin-left:15%}.tablet-suffix-15{margin-right:15%}.tablet-push-15{left:15%}.tablet-pull-15{left:-15%}.tablet-grid-20{float:left;width:20%}.tablet-prefix-20{margin-left:20%}.tablet-suffix-20{margin-right:20%}.tablet-push-20{left:20%}.tablet-pull-20{left:-20%}.tablet-grid-25{float:left;width:25%}.tablet-prefix-25{margin-left:25%}.tablet-suffix-25{margin-right:25%}.tablet-push-25{left:25%}.tablet-pull-25{left:-25%}.tablet-grid-30{float:left;width:30%}.tablet-prefix-30{margin-left:30%}.tablet-suffix-30{margin-right:30%}.tablet-push-30{left:30%}.tablet-pull-30{left:-30%}.tablet-grid-35{float:left;width:35%}.tablet-prefix-35{margin-left:35%}.tablet-suffix-35{margin-right:35%}.tablet-push-35{left:35%}.tablet-pull-35{left:-35%}.tablet-grid-40{float:left;width:40%}.tablet-prefix-40{margin-left:40%}.tablet-suffix-40{margin-right:40%}.tablet-push-40{left:40%}.tablet-pull-40{left:-40%}.tablet-grid-45{float:left;width:45%}.tablet-prefix-45{margin-left:45%}.tablet-suffix-45{margin-right:45%}.tablet-push-45{left:45%}.tablet-pull-45{left:-45%}.tablet-grid-50{float:left;width:50%}.tablet-prefix-50{margin-left:50%}.tablet-suffix-50{margin-right:50%}.tablet-push-50{left:50%}.tablet-pull-50{left:-50%}.tablet-grid-55{float:left;width:55%}.tablet-prefix-55{margin-left:55%}.tablet-suffix-55{margin-right:55%}.tablet-push-55{left:55%}.tablet-pull-55{left:-55%}.tablet-grid-60{float:left;width:60%}.tablet-prefix-60{margin-left:60%}.tablet-suffix-60{margin-right:60%}.tablet-push-60{left:60%}.tablet-pull-60{left:-60%}.tablet-grid-65{float:left;width:65%}.tablet-prefix-65{margin-left:65%}.tablet-suffix-65{margin-right:65%}.tablet-push-65{left:65%}.tablet-pull-65{left:-65%}.tablet-grid-70{float:left;width:70%}.tablet-prefix-70{margin-left:70%}.tablet-suffix-70{margin-right:70%}.tablet-push-70{left:70%}.tablet-pull-70{left:-70%}.tablet-grid-75{float:left;width:75%}.tablet-prefix-75{margin-left:75%}.tablet-suffix-75{margin-right:75%}.tablet-push-75{left:75%}.tablet-pull-75{left:-75%}.tablet-grid-80{float:left;width:80%}.tablet-prefix-80{margin-left:80%}.tablet-suffix-80{margin-right:80%}.tablet-push-80{left:80%}.tablet-pull-80{left:-80%}.tablet-grid-85{float:left;width:85%}.tablet-prefix-85{margin-left:85%}.tablet-suffix-85{margin-right:85%}.tablet-push-85{left:85%}.tablet-pull-85{left:-85%}.tablet-grid-90{float:left;width:90%}.tablet-prefix-90{margin-left:90%}.tablet-suffix-90{margin-right:90%}.tablet-push-90{left:90%}.tablet-pull-90{left:-90%}.tablet-grid-95{float:left;width:95%}.tablet-prefix-95{margin-left:95%}.tablet-suffix-95{margin-right:95%}.tablet-push-95{left:95%}.tablet-pull-95{left:-95%}.tablet-grid-33{float:left;width:33.33333%}.tablet-prefix-33{margin-left:33.33333%}.tablet-suffix-33{margin-right:33.33333%}.tablet-push-33{left:33.33333%}.tablet-pull-33{left:-33.33333%}.tablet-grid-66{float:left;width:66.66667%}.tablet-prefix-66{margin-left:66.66667%}.tablet-suffix-66{margin-right:66.66667%}.tablet-push-66{left:66.66667%}.tablet-pull-66{left:-66.66667%}.tablet-grid-100{clear:both;width:100%}}@media(min-width:1025px){.push-5,.pull-5,.push-10,.pull-10,.push-15,.pull-15,.push-20,.pull-20,.push-25,.pull-25,.push-30,.pull-30,.push-35,.pull-35,.push-40,.pull-40,.push-45,.pull-45,.push-50,.pull-50,.push-55,.pull-55,.push-60,.pull-60,.push-65,.pull-65,.push-70,.pull-70,.push-75,.pull-75,.push-80,.pull-80,.push-85,.pull-85,.push-90,.pull-90,.push-95,.pull-95,.push-33,.pull-33,.push-66,.pull-66{position:relative}.hide-on-desktop{display:none!important}.grid-5{float:left;width:5%}.prefix-5{margin-left:5%}.suffix-5{margin-right:5%}.push-5{left:5%}.pull-5{left:-5%}.grid-10{float:left;width:10%}.prefix-10{margin-left:10%}.suffix-10{margin-right:10%}.push-10{left:10%}.pull-10{left:-10%}.grid-15{float:left;width:15%}.prefix-15{margin-left:15%}.suffix-15{margin-right:15%}.push-15{left:15%}.pull-15{left:-15%}.grid-20{float:left;width:20%}.prefix-20{margin-left:20%}.suffix-20{margin-right:20%}.push-20{left:20%}.pull-20{left:-20%}.grid-25{float:left;width:25%}.prefix-25{margin-left:25%}.suffix-25{margin-right:25%}.push-25{left:25%}.pull-25{left:-25%}.grid-30{float:left;width:30%}.prefix-30{margin-left:30%}.suffix-30{margin-right:30%}.push-30{left:30%}.pull-30{left:-30%}.grid-35{float:left;width:35%}.prefix-35{margin-left:35%}.suffix-35{margin-right:35%}.push-35{left:35%}.pull-35{left:-35%}.grid-40{float:left;width:40%}.prefix-40{margin-left:40%}.suffix-40{margin-right:40%}.push-40{left:40%}.pull-40{left:-40%}.grid-45{float:left;width:45%}.prefix-45{margin-left:45%}.suffix-45{margin-right:45%}.push-45{left:45%}.pull-45{left:-45%}.grid-50{float:left;width:50%}.prefix-50{margin-left:50%}.suffix-50{margin-right:50%}.push-50{left:50%}.pull-50{left:-50%}.grid-55{float:left;width:55%}.prefix-55{margin-left:55%}.suffix-55{margin-right:55%}.push-55{left:55%}.pull-55{left:-55%}.grid-60{float:left;width:60%}.prefix-60{margin-left:60%}.suffix-60{margin-right:60%}.push-60{left:60%}.pull-60{left:-60%}.grid-65{float:left;width:65%}.prefix-65{margin-left:65%}.suffix-65{margin-right:65%}.push-65{left:65%}.pull-65{left:-65%}.grid-70{float:left;width:70%}.prefix-70{margin-left:70%}.suffix-70{margin-right:70%}.push-70{left:70%}.pull-70{left:-70%}.grid-75{float:left;width:75%}.prefix-75{margin-left:75%}.suffix-75{margin-right:75%}.push-75{left:75%}.pull-75{left:-75%}.grid-80{float:left;width:80%}.prefix-80{margin-left:80%}.suffix-80{margin-right:80%}.push-80{left:80%}.pull-80{left:-80%}.grid-85{float:left;width:85%}.prefix-85{margin-left:85%}.suffix-85{margin-right:85%}.push-85{left:85%}.pull-85{left:-85%}.grid-90{float:left;width:90%}.prefix-90{margin-left:90%}.suffix-90{margin-right:90%}.push-90{left:90%}.pull-90{left:-90%}.grid-95{float:left;width:95%}.prefix-95{margin-left:95%}.suffix-95{margin-right:95%}.push-95{left:95%}.pull-95{left:-95%}.grid-33{float:left;width:33.33333%}.prefix-33{margin-left:33.33333%}.suffix-33{margin-right:33.33333%}.push-33{left:33.33333%}.pull-33{left:-33.33333%}.grid-66{float:left;width:66.66667%}.prefix-66{margin-left:66.66667%}.suffix-66{margin-right:66.66667%}.push-66{left:66.66667%}.pull-66{left:-66.66667%}.grid-100,.twelve{clear:both;width:100%}}</style>
   <? endif; ?>
   <?
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
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/cycle/jquery.cycle2.js');

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
<style>
#heroSlider,
.block-stock-main,
#news-slider {
display:none;
}
</style>
</head>
<body itemscope itemtype="http://schema.org/WebPage">
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

<? include $_SERVER['DOCUMENT_ROOT'] . '/include/infocenter_popup_with_wrap.php'; ?>
<?if(!defined('ERROR_404')):?>
<!-- HEADER START -->
<header class="header" itemscope itemtype="http://schema.org/WPHeader">
<div class="header-top">
    <div class="container" itemscope itemtype="http://schema.org/ContactPoint">
        <nav class="top-bar twelve">
            <span class="main-nav-trigger trigger-nav">&#9776;</span>
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
                <li class="city"><i class="icon"></i><span>Москва</span></li>
                <li class="phone"><i class="icon"></i><span itemprop="telephone">+7 (495) 651-99-99</span></li>
                <li id="callback" class="callback"><a data-height="auto" data-width="300" class="popup popup_cb" href="/ajax/callback.php">Обратный звонок</a></li>
		<? if (!$USER->isAuthorized()): ?>
                <li><a class="orange" href="/personal/">Регистрация</a></li>
		<? endif; ?>
                <li class="callback right"><i class="icon"></i><a href="/about/manufacture/">Производство</a></li>
                <li class="callback right"><i class="icon"></i><a href="/about/company/">О компании</a></li>
                <!--<li class="callback"><i class="icon"></i><a href="/about/project/">Наши партнеры</a></li>-->
                <!--<li class="delivery">
                    <?/*$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => "/include/top_header_text.php"
                        )
                    );*/?>
                </li>-->
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
        <div class="sidebar-responsive"></div>
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
                                  height="47" title='<?= ($APPLICATION->GetCurPage(false) != '/') ? 'Перейти на главную страницу' : 'Логотип компании ООО "МФК TEXENERGO"'?>'><?= ($APPLICATION->GetCurPage(false) != '/') ? '</a>' : ''?>
	<span class="logo-text">Успех начинается здесь!</span>
</li>
<li class="j-market-menu-link">
    <a href="/catalog/" id="categories-link">Продукция <!--<i class="icon"></i>--> </a>
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
<li class="price-list"><a href="/price_list/" class="orange">прайс-листы</a></li>
<li class="price-list"><a href="/publication/akcii/" class="orange">Акции</a></li>
<li><a href="/eshop/delivery/" class="">Доставка</a></li>
<li><a href="/about/contacts/" class="">Контакты</a></li>
<li id="login" class="login right">
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
<li id="cart" class="cart right">
    <? include $_SERVER['DOCUMENT_ROOT'] . '/basket/ajax/small.php'; ?>
</li>
<!-- bitrix/search.form/catalog-search -->
<li class="search right">
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
